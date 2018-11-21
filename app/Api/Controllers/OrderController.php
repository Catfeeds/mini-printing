<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/23
 * Time: 16:01
 * Function:
 */

namespace App\Api\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\OrderSubmitRequest;
use App\Http\Resources\ExpressResource;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Models\OrderWechatPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tanmo\Wechat\Facades\Payment;
use Illuminate\Http\Request;
class OrderController extends Controller
{
    /**
     * 所有订单
     */
    public function index()
    {
        $user = auth()->user();
        $status = request()->get('status');
        if (isset($status)) {
                $orders = Order::filterUserId($user->id)->filterStatus($status)->latest()->paginate(10);
        }
        else {
            $orders = Order::filterUserId($user->id)->filterRefund(0)->latest()->paginate(10);
        }

        return api()->collection($orders, OrderResource::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * 订单状态统计
     */
    public function orderstatus(){
        $user = auth()->user();
        $payment = $ship = $receipt = $comment = $finish = $refund = 0;
        $order_status = Order::where('user_id',$user->id)->select('status','refund')->get();
        foreach ($order_status as $arr){
            if ($arr['refund'] == 1){
                $refund++;
                continue;
            }
            switch ($arr['status']){
                case 0:
                    $payment++;
                    break;
                case 1:
                    $ship++;
                    break;
                case 2:
                    $receipt++;
                    break;
                case 3:
                    $comment++;
                    break;
                case 4:
                    $finish++;
                    break;
            }
        }
        $data = array('payment'=>$payment,'Ship'=>$ship,'Receipt'=>$receipt,'comment'=>$comment,'refund'=>$refund,'finish'=>$finish);
        return response()->json(['data' => $data]);
    }

    /**
     * @param Order $order
     * @return \Tanmo\Api\Http\Response
     */
    public function show(Order $order)
    {
        $this->authorize('show', $order);

        return api()->item($order, OrderDetailResource::class);
    }

    /**
     * 提交订单
     *
     * @param OrderSubmitRequest $request
     * @return \Tanmo\Api\Http\Response
     */
    public function store(Request $request)
    {
      //  mlog('text',$request->all());
        $address = $request->all(['user_name', 'national_code', 'postal_code', 'tel', 'province', 'city', 'county', 'detail']);
        $remark = $request->get('remark', '');
        $content = $request->get('content');
        $items = json_decode($content, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            return api()->error('Submission of data errors', 400);
        }

        $order = (new Order())->submit(auth()->user(), $items, $address, $remark);

        return api()->item($order, OrderResource::class)->created();
    }

    /**
     * @param Order $order
     * @return \Tanmo\Api\Http\Response
     */
    public function destroy(Order $order)
    {
        $this->authorize('destroy', $order);

        $order->delete();

        return api()->noContent();
    }

    /**
     * @param Order $order
     * @return \Tanmo\Api\Http\Response
     */
    public function express(Order $order)
    {
        $this->authorize('express', $order);

        return api()->item($order, ExpressResource::class);
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay(Order $order)
    {
        $user = auth()->user();
        $payment = Payment::app();

        ///
        $response = $payment->order()->unify([
            'body' => config('app.name') . '-订单:' . $order->sn,
            'out_trade_no' => $order->sn,
            'total_fee' => $order->price * 100,
            'trade_type' => 'JSAPI',
            'openid' => $user->authWechat->open_id,
            'notify_url' => url()->route('wechat.paid_notify')
        ]);

        $data = [
            'appId' => $payment->config()->getAppId(),
            'timeStamp' => (string)time(),
            'nonceStr' => $response['nonce_str'],
            'package' => 'prepay_id=' . $response['prepay_id'],
            'signType' => 'MD5'
        ];

        $data['paySign'] = gen_sign($data, $payment->config()->getKey());
        $data['sign'] = $response['sign'];
        unset($data['appId']);

        return response()->json(['data' => $data]);
    }

    public function adbpay(Order $order){
        $user = auth()->user();
        $payment = Payment::app();

        ///
        $response = $payment->order()->adbunify([
            'body' => config('app.name') . '-订单:' . $order->sn,
            'out_trade_no' => $order->sn,
            'total_fee' => $order->price * 100,
            'trade_type' => 'APP',
            'notify_url' => 'http://bn7esh.natappfree.cc/orders/paid_notify'
        ]);

        $data = [
            'appid' => $payment->config()->getAdbAppId(),
            'partnerid' =>$payment->config()->getMchId(),
            'prepayid' => $response['prepay_id'],
            'package' => 'Sign=WXPay',
            'noncestr' => $response['nonce_str'],
            'timestamp' => (string)time(),
        ];
        $data['sign']=gen_sign($data, $payment->config()->getKey());

        return response()->json(['data' => $data]);
    }


    /**
     * @param Order $order
     * @return \Tanmo\Api\Http\Response
     */
    public function confirm(Order $order)
    {
        $this->authorize('confirm', $order);

        $order->status = Order::WAIT_COMMENT;
        $order->confirmed_at = Carbon::now();
        $order->save();

        return api()->noContent();
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function paidNotify()
    {
        return Payment::app()->paidNotify(function ($message, $fail) {
            Log::notice(json_encode($message));

            /**
             * @var $order Order
             */
            $order = Order::filterSn($message['out_trade_no'])->first();

            if (!$order || $order->paid_at) {
                return true;
            }

            if ($message['return_code'] === 'SUCCESS') {
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $order->paid_at = Carbon::now();
                    $order->status = Order::WAIT_DELIVER;

                    $orderWechatPayment = new OrderWechatPayment(['sn' => $message['transaction_id']]);
                    $order->wechatPayment()->save($orderWechatPayment);
                }
                else {
                    Log::error('用户支付失败，SN:' . $message['out_trade_no']);
                }
            }
            else {
                return $fail('通信失败，请稍后再通知我');
            }

            $order->save();

            return true;
        });
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function refundNotify()
    {
        return Payment::app()->refundNotify(function ($message, $reqInfo, $fail) {
            if (!$reqInfo) {
                return $fail('解密失败');
            }

            $refund = OrderRefund::filterSn($reqInfo['out_refund_no']);
            if (!$refund || $refund->status === OrderRefund::SUCCESS || $refund->status === OrderRefund::REFUSE) {
                return true;
            }

            ///
            if (array_get($reqInfo, 'refund_status') === 'SUCCESS') {
                $refund->price = $reqInfo['settlement_refund_fee'] / 100;
                $refund->status = OrderRefund::SUCCESS;
                $refund->save();
            }
            else {
                Log::error('Refund Error:' . $reqInfo['refund_status']);
            }

            return true;
        });
    }
}