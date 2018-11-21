<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/5/8
 * Time: 16:03
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
use App\Models\Express;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;

/**
 * @module 订单管理
 *
 * Class OrderController
 * @package App\Admin\Controllers\Orders
 */
class OrderController extends Controller
{
    public function index()
    {

    }

    /**
     * @permission 待付款
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paying()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('sn');
        });
        $start=request()->get('start');
        $end=request()->get('end');
        $orders = $this->orderList($start,$end,$searcher,Order::WAIT_PAY);

        $data = request()->all();
        $header = '待付款';
        return view('admin::orders.paying', compact('orders', 'header','data'));
    }

    /**
     * @permission 待发货
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delivering()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('sn');
        });
        $start=request()->get('start');
        $end=request()->get('end');
        $orders = $this->orderList($start,$end,$searcher,Order::WAIT_DELIVER);

        $data = request()->all();
        $header = '待发货';
        $expresses = Express::all();
        return view('admin::orders.delivering', compact('orders', 'header', 'expresses','data'));
    }

    /**
     * @permission 待收货
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function receiving()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('sn');
        });
        $start=request()->get('start');
        $end=request()->get('end');
        $orders = $this->orderList($start,$end,$searcher,Order::WAIT_DELIVER);

        $data = request()->all();
        $header = '待收货';
        return view('admin::orders.receiving', compact('orders', 'header','data'));
    }

    /**
     * @permission 待评价
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function commenting()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('sn');
        });
        $start=request()->get('start');
        $end=request()->get('end');
        $orders = $this->orderList($start,$end,$searcher,Order::WAIT_DELIVER);

        $data = request()->all();
        $header = '待评价';
        return view('admin::orders.commenting', compact('orders', 'header','data'));
    }

    /**
     * @permission 已完成
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('sn');
        });

        $start=request()->get('start');
        $end=request()->get('end');
        $orders = $this->orderList($start,$end,$searcher,Order::FINISH);

        $data = request()->all();
        $header = '已完成';
        return view('admin::orders.finish', compact('orders', 'header','data'));
    }

    /**
     * @permission 订单详情
     *
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Order $order)
    {
        $header = '订单详情';
        return view('admin::orders.order-show', compact('order', 'header'));
    }

    /**
     * @permission 修改价格
     *
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modifyPrice(Order $order, Request $request)
    {
        $order->price = $request->get('price');
        $order->save();

        return response()->json(['status' => 1, 'message' => '成功']);
    }

    /**
     * @permission 发货
     *
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deliver(Order $order, Request $request)
    {
        $order->express_type = $request->get('express_type');
        $order->tracking_no = $request->get('tracking_no');
        $order->status = Order::WAIT_RECV;
        $order->delivered_at = Carbon::now();
        $order->save();

        return response()->json(['status' => 1, 'message' => '发货成功']);
    }

    /**
     * @permission 删除订单
     *
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(['status' => 1, 'message' => '成功']);
    }

    protected function orderList($start,$end,$searcher,$status){
        if($start && $end) {
            $orders = (new Order())->search($searcher)->whereBetween('created_at', [$start, $end])->filterStatus($status)->orderBy('created_at', 'desc')->paginate(10);
        }
        else if($start){
            $orders = (new Order())->search($searcher)->where('created_at','>=',$start)->filterStatus($status)->orderBy('created_at', 'desc')->paginate(10);
        }
        else if($end){
            $orders = (new Order())->search($searcher)->where('created_at','<=',$end)->filterStatus($status)->orderBy('created_at', 'desc')->paginate(10);
        }
        else {
            $orders = (new Order())->search($searcher)->filterStatus($status)->orderBy('created_at', 'desc')->paginate(10);
        }
        return $orders;
    }
}