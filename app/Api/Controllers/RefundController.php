<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/28
 * Time: 15:24
 * Function:
 */

namespace App\Api\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\OrderRefundDetailResource;
use App\Http\Resources\OrderRefundResource;
use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function index()
    {
        $refunds = OrderRefund::filterUserId(auth()->user()->id)->latest()->paginate(10);
        return api()->collection($refunds, OrderRefundResource::class);
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return \Tanmo\Api\Http\Response
     */
    public function store(Request $request, Order $order)
    {
        $this->authorize('refund', $order);

        ///
        $refund = OrderRefund::where('order_id', $order->id)->first();
        if ($refund) {
            $refund->status = OrderRefund::APPLYING;
        }
        else {
            $refund = new OrderRefund();
            $refund->order_id = $order->id;
            $refund->user_id = auth()->user()->id;
            $refund->sn = date('YmdHis') . $order->id . rand(10, 99);
        }

        ///
        $refund->reason = $request->get('reason');
        $refund->require_price = $request->get('require_price', $order->price);
        $refund->describe = $request->get('describe');
        $refund->save();

        ///
        $order->refund = 1;
        $order->save();

        return api()->noContent();
    }

    /**
     * @param OrderRefund $orderRefund
     * @return \Tanmo\Api\Http\Response
     */
    public function show(OrderRefund $orderRefund)
    {
        $this->authorize('show', $orderRefund);

        return api()->item($orderRefund, OrderRefundDetailResource::class);
    }

    /**
     * @param OrderRefund $orderRefund
     * @return \Tanmo\Api\Http\Response
     */
    public function cancel(OrderRefund $orderRefund)
    {
        $this->authorize('cancel', $orderRefund);

        $orderRefund->status = OrderRefund::CANCEL;
        $orderRefund->order->refund = 0;
        $orderRefund->order->save();
        $orderRefund->save();

        return api()->noContent();
    }
}