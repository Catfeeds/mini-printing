@extends('admin::layouts.main')

@section('content')

    {{--@include('admin::search.items-items')--}}

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">商品列表</h3>

                    <div class="btn-group pull-right">
                        <a href="javascript:void(0);" class="btn btn-sm btn-success setting" data-id="{{ config('admin.early_warning') }}">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;设置
                        </a>
                    </div>

                    {{--@include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::items.index')])--}}
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>图片</th>
                            <th>编号</th>
                            <th>商品标题</th>
                            <th>销售价</th>
                            <th>原价</th>
                            <th>库存</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        @inject('itemPresenter', "App\Admin\Presenters\ItemPresenter")
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{!! $itemPresenter->cover($item) !!}</td>
                                <td>{{ $item->sn }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{!! $itemPresenter->price($item) !!}</td>
                                <td>{!! $itemPresenter->originalPrice($item) !!}</td>
                                <td>{!! $itemPresenter->stock($item) !!}</td>
                                <td>{!! $itemPresenter->status($item) !!}</td>
                                <td>
                                    <a href="{{ route('admin::items.edit', $item->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $items->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.setting').unbind('click').click(function() {

            var warning_count = $(this).data('id');

            swal({
                    title: "设置预警值",
                    type: "input",
                    inputValue: warning_count,
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    closeOnConfirm: false,
                    cancelButtonText: "取消"
                },
                function(inputValue){
                    if (inputValue === false) return false;

                    if (inputValue === "") {
                        swal.showInputError("请输入预警值！");
                        return false
                    }

                    $.ajax({
                        method: 'put',
                        url: "{{ route('admin::items.warning.update') }}",
                        data: {
                            _token:LA.token,
                            warning_count:inputValue
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