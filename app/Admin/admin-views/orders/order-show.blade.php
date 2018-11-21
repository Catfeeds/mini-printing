@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <section class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#overview" data-toggle="tab" aria-expanded="false">订单概况</a>
                    </li>
                    <li>
                        <a href="#items" data-toggle="tab" aria-expanded="true">商品列表</a>
                    </li>
                    <li class="">
                        <a href="#express" data-toggle="tab" aria-expanded="true">物流信息</a>
                    </li>
                    <li class="pull-right">
                        <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                    </li>
                </ul>
                <div class="tab-content no-padding">
                    <div class="tab-pane active" id="overview">
                        <table class="table">
                            <tr>
                                <th>订单编号</th>
                                <td>{{ $order->sn }}</td>
                            </tr>
                            <tr>
                                <th>下单时间</th>
                                <td>{{ $order->created_at }}</td>
                            </tr>
                            <tr>
                                <th>订单状态</th>
                                <td>{{ $order->status_text }}</td>
                            </tr>
                            <tr>
                                <th>支付时间</th>
                                <td>{{ $order->paid_at }}</td>
                            </tr>
                            <tr>
                                <th>订单金额</th>
                                <td>{{ $order->items_price }}</td>
                            </tr>
                            <tr>
                                <th>运费</th>
                                <td>{{ $order->freight }}</td>
                            </tr>
                            <tr>
                                <th>应付款</th>
                                <td>{{ $order->payable_price }}</td>
                            </tr>
                            <tr>
                                <th>实付款</th>
                                <td>{{ $order->price }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="tab-pane" id="items">
                        <table class="table">
                            <tr>
                                <th>图片</th>
                                <th>标题</th>
                                <th>数量</th>
                                <th>销售价</th>
                                <th>总价</th>
                            </tr>
                            @inject('itemPresenter', "App\Admin\Presenters\ItemPresenter")
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{!! $itemPresenter->cover($item->item) !!}</td>
                                    <td>{{ $item->item->title }}</td>
                                    <td>{{ $item->count }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->price * $item->count }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="tab-pane" id="express">
                        <table class="table">
                            <tr>
                                <th>收货地址</th>
                                <td>
                                    {{ $order->address->province }}&nbsp;
                                    {{ $order->address->city }}&nbsp;
                                    {{ $order->address->county }}
                                </td>
                            </tr>
                            <tr>
                                <th>详细地址</th>
                                <td>{{ $order->address->detail }}</td>
                            </tr>
                            <tr>
                                <th>收货人</th>
                                <td>{{ $order->address->user_name }}</td>
                            </tr>
                            <tr>
                                <th>联系电话</th>
                                <td>{{ $order->address->tel }}</td>
                            </tr>
                            <tr>
                                <th>邮编</th>
                                <td>{{ $order->address->postal_code }}</td>
                            </tr>
                            <tr>
                                <th>物流公司</th>
                                <td>{{ $order->express_type }}</td>
                            </tr>
                            <tr>
                                <th>物流单号</th>
                                <td>{{ $order->tracking_no }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('.form-history-back').on('click', function (event) {
                event.preventDefault();
                history.back();
            });
        })
    </script>
@endsection