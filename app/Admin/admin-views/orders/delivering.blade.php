@extends('admin::layouts.main')

@section('content')
    @include('admin::search.orders-orders',['resetUrl' => route('admin::orders.delivering')])

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">待发货订单</h3>

                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::orders.delivering')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>商品</th>
                            <th>价格</th>
                            <th>买家信息</th>
                            <th>订单状态</th>
                            <th>备注</th>
                            <th>操作</th>
                        </tr>
                        @inject('itemPresenter', "App\Admin\Presenters\ItemPresenter")
                        @foreach($orders as $order)
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
                                    待付款
                                </td>
                                <td>{{ $order->remark }}</td>
                                <td>
                                    <a href="{{ route('admin::orders.show', $order->id) }}" title="订单详情">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="javascript:void(0);"
                                       data-action="{{ route('admin::orders.deliver', $order->id) }}"
                                       data-toggle="modal"
                                       data-target="#deliver-modal"
                                       title="发货" class="grid-row-edit">
                                        <i class="fa fa-paper-plane"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $order->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $orders->appends($data)->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deliver-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">发货</h4>
                </div>
                <form id="post-form" action="" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form">
                            <div class="form-group">
                                <label>快递公司</label>
                                <select style="width: 100%;" name="express_type" id="express-type" tabindex="-1" data-placeholder="选择快递公司" class="form-control express-type select2-hidden-accessible">
                                    @foreach($expresses as $express)
                                        <option value="{{ $express->type }}">{{ $express->company }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>单号</label>
                                <input type="text" class="form-control" placeholder="请输入 单号" name="tracking_no">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-warning pull-left">撤销</button>
                        <button type="submit" class="btn btn-primary" id="deliver-btn">发货</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::orders.index')])
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

        $(".express-type").select2({
            "allowClear": true
        });

        var $form = $("#post-form");
        $('.grid-row-edit').unbind('click').click(function() {

            var action = $(this).data('action');

            $form.attr('action', action);
        });

        $form.bootstrapValidator({
            live: 'enable',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                tracking_no:{
                    validators:{
                        notEmpty:{
                            message: '请输入单号'
                        }
                    }
                }
            }
        });

        $('#deliver-btn').click(function () {
            $form.bootstrapValidator('validate');
            if ($form.data('bootstrapValidator').isValid()) {
                $.ajax({
                    url: $form.attr('action'),
                    type: 'PUT',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            $.pjax.reload('#pjax-container');
                            swal(res.message, '', 'success');
                        }
                        else {
                            swal(res.message, '', 'error');
                            $form[0].reset();
                            $(".has-feedback").removeClass('has-success').removeClass('has-error');
                            $(".form-control-feedback").hide();
                        }
                        $("#deliver-modal").modal('toggle');
                    }
                });
            }
        });
    </script>
@endsection