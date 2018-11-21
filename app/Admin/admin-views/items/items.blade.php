@extends('admin::layouts.main')

@section('content')

    @include('admin::search.items-items')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">商品列表</h3>

                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::items.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>

                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::items.index')])
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
                                    <a href="javascript:void(0);" data-id="{{ $item->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    @if(!in_array($item->id,$recommends))
                                        <a href="javascript:void(0);" data-id="{{ $item->id }}" class="grid-row-store">
                                        <i class="fa fa-star-o"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $items->appends($data)->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::items.index')])
    <script>
    $('.grid-row-store').unbind('click').click(function() {

        var id = $(this).data('id');

        swal({
                title: "确认推荐该商品?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                closeOnConfirm: false,
                cancelButtonText: "取消"
            },
            function(){
                $.ajax({
                    method: 'post',
                    url: "{{ route('admin::recommends.store') }}" + '?item_id=' + id,
                    data: {
                        _token:LA.token
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