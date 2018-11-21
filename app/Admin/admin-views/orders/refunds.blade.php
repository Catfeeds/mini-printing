@extends('admin::layouts.main')

@section('content')
    @include('admin::search.order-refounds')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">待付款订单</h3>

                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::refunds.index')])
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>商品</th>
                            <th>价格</th>
                            <th>买家信息</th>
                            <th>申请金额</th>
                            <th>退款状态</th>
                            <th>退款原因</th>
                            <th>退款说明</th>
                            <th>操作</th>
                        </tr>
                        @inject('itemPresenter', "App\Admin\Presenters\ItemPresenter")
                        @inject('refundPresenter', "App\Admin\Presenters\RefundPresenter")
                        @foreach($refunds as $refund)
                            @php
                            $order = $refund->order;
                            @endphp
                            <tr>
                                <td>
                                    <a class="btn btn-xs btn-default grid-expand collapsed" data-inserted="0" data-key="{{ $order->id }}" data-toggle="collapse" data-target="#grid-collapse-{{ $order->id }}" aria-expanded="false">
                                        <i class="fa fa-caret-right"></i> 详情
                                    </a>
                                    <template class="grid-expand-{{ $order->id }}">
                                        <div id="grid-collapse-{{ $order->id }}" class="collapse">
                                            <div class="box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">订单详情</h3>
                                                    <div class="box-tools pull-right">
                                                    </div>
                                                </div>
                                                <div class="box-body" style="display: block;">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>商品图片</th>
                                                            <th>商品名称</th>
                                                            <th>商品单价</th>
                                                            <th>商品数量</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($order->items as $item)
                                                            <tr>
                                                                <td>{!! $itemPresenter->cover($item->item) !!}</td>
                                                                <td>{{ $item->item->title }}</td>
                                                                <td>{{ $item->price }}</td>
                                                                <td>{{ $item->count }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="box-footer">
                                                    <span>订单号：{{ $order->sn }}</span>
                                                    <span style="margin-left: 10px">下单时间： {{ $order->created_at }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td>
                                    订单金额：{{ $order->items_price }}<br>
                                    运费：{{ $order->freight }}<br>
                                    应付：{{ $order->payable_price }}<br>
                                    实付：{{ $order->price }}<br>
                                </td>
                                <td>
                                    用户名：{{ $order->user->nickname }}<br>
                                    收件人：{{ $order->address->user_name }}<br>
                                    手机号：{{ $order->address->tel }}<br>
                                </td>
                                <td>
                                    {{ $refund->require_price }}
                                </td>
                                <td>
                                    {!! $refundPresenter->status($refund) !!}
                                </td>
                                <td>{{ $refund->reason }}</td>
                                <td>{{ $refund->describe }}</td>
                                <td>
                                    <a href="{{ route('admin::orders.show', $order->id) }}" title="订单详情">
                                        订单详情
                                    </a><br>
                                    @if(Admin::user()->can('handle', $refund))
                                    <a href="javascript:void(0);" data-action="{{ route('admin::refunds.agree', $refund->id) }}" data-content="{{ $refund->require_price }}" class="grid-row-agree">
                                        同意退款
                                    </a><br>
                                    <a href="javascript:void(0);" data-action="{{ route('admin::refunds.refuse', $refund->id) }}" class="grid-row-refuse">
                                        拒绝退款
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $refunds->appends($condition)->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.grid-expand').on('click', function () {
            if ($(this).data('inserted') == '0') {
                var key = $(this).data('key');
                var row = $(this).closest('tr');
                var html = $('template.grid-expand-'+key).html();

                row.after("<tr><td colspan='"+row.find('td').length+"' style='padding:0 !important; border:0px;'>"+html+"</td></tr>");

                $(this).data('inserted', 1);
            }

            $("i", this).toggleClass("fa-caret-right fa-caret-down");
        });

        $('#begin').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: moment.locale('zh-cn')
        });

        $('#end').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: moment.locale('zh-cn')
        });

        $('.grid-row-agree').unbind('click').click(function() {
            var action = $(this).data('action');
            var price = $(this).data('content');

            swal({
                    title: "同意退款?",
                    type: "input",
                    inputValue: price,
                    inputPlaceholder: "输入退款金额",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    closeOnConfirm: false,
                    cancelButtonText: "取消"
                },
                function(inputValue){
                    if (inputValue === false) return false;

                    if (inputValue === "") {
                        swal.showInputError("请输入退款金额！");
                        return false
                    }

                    $.ajax({
                        method: 'put',
                        url: action,
                        data: {
                            _token:LA.token,
                            price:inputValue
                        },
                        success: function (data) {
                            $.pjax.reload('#pjax-container');

                            if (typeof data === 'object') {
                                if (data.status) {
                                    swal(data.message, '', 'success');
                                } else {
                                    swal(data.message, '', 'error');
                                }
                            }
                        }
                    });
                });
        });

        $('.grid-row-refuse').unbind('click').click(function() {
            var action = $(this).data('action');

            swal({
                    title: "残忍拒绝?",
                    type: "input",
                    inputPlaceholder: "输入拒绝原因",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    closeOnConfirm: false,
                    cancelButtonText: "取消"
                },
                function(inputValue){
                    if (inputValue === false) return false;

                    if (inputValue === "") {
                        swal.showInputError("请输入拒绝原因！");
                        return false
                    }

                    $.ajax({
                        method: 'put',
                        url: action,
                        data: {
                            _token:LA.token,
                            reason:inputValue
                        },
                        success: function (data) {
                            $.pjax.reload('#pjax-container');

                            if (typeof data === 'object') {
                                if (data.status) {
                                    swal(data.message, '', 'success');
                                } else {
                                    swal(data.message, '', 'error');
                                }
                            }
                        }
                    });
                });
        });
    </script>
@endsection