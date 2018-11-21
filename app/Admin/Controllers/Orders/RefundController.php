<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/5/8
 * Time: 15:54
 *
 *                                _oo8oo_
 *                               o8888888o
 *                               88" . "88
 *                               (| -_- |)
 *                               0\  =  /0
 *                             ___/'==='\___
 *                           .' \\|     |// '.
 *                          / \\|||  :  |||// \
 *                         / _||||| -:- |||||_ \
 *                        |   | \\\  -  /// |   |
 *                        | \_|  ''\---/''  |_/ |
 *                        \  .-\__  '-'  __/-.  /
 *                      ___'. .'  /--.--\  '. .'___
 *                   ."" '<  '.___\_<|>_/___.'  >' "".
 *                  | | :  `- \`.:`\ _ /`:.`/ -`  : | |
 *                  \  \ `-.   \_ __\ /__ _/   .-` /  /
 *              =====`-.____`.___ \_____/ ___.`____.-`=====
 *                                `=---=`
 *
 *
 *             ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *
 *                         佛祖保佑    永无BUG
 *
 */

namespace App\Admin\Controllers\Orders;


use App\Http\Controllers\Controller;
use App\Jobs\ProcessRefund;
use App\Models\OrderRefund;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * @module 退单管理
 *
 * Class RefundController
 * @package App\Admin\Controllers\Orders
 */
class RefundController extends Controller
{
    /**
     * @permission 退单列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = $this->screening_conditions(request());
        if(request()->get('start') || request()->get('end') || request()->get('sn')) {
            $ids = $this->getOrderIds(request());
            $refunds = OrderRefund::where($data)->whereIn('order_id',$ids)->latest()->paginate(10);
        }
        else {
            $refunds = OrderRefund::where($data)->latest()->paginate(10);
        }
        $condition = request()->all();

        $header = '退单管理';
        return view('admin::orders.refunds', compact('refunds','condition','header'));
    }

    /**
     * @permission 同意退款
     *
     * @param OrderRefund $refund
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function agree(OrderRefund $refund, Request $request)
    {
        $refund->status = OrderRefund::AGREE;
        $refund->price = $request->get('price');
        $refund->save();

        dispatch(new ProcessRefund($refund));

        return response()->json(['status' => 1, 'message' => '已同意']);
    }

    /**
     * @permission 拒绝退款
     *
     * @param OrderRefund $refund
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refuse(OrderRefund $refund, Request $request)
    {
        $refund->status = OrderRefund::REFUSE;
        $refund->refuse_reason = $request->get('refuse_reason');
        $refund->save();

        return response()->json(['status' => 1, 'message' => '已拒绝']);
    }

    /**
     * @param $request
     * @return array
     */
    protected function screening_conditions($request)
    {
        $data=[];
        if($request->get('status') != '') {
            $data[] = ['status','=',$request->get('status')];
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getOrderIds()
    {
        $condition = [];
        $ids = [];
        if(request()->get('sn') != '')
        {
            $condition[] = ['sn','like','%'.request()->get('sn').'%'];
        }
        if(request()->get('start'))
        {
            $condition[] = ['created_at','>=',request()->get('start')];
        }
        if(request()->get('end'))
        {
            $condition[] = ['created_at','<=',request()->get('end')];
        }
        if($condition) {
            $ids = Order::where($condition)->pluck('id')->toArray();
        }

        return $ids;
    }
}