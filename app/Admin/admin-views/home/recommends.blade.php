@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">推荐商品</h3>

                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::items.index') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
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
                        @foreach($recommends as $recommend)
                            <tr>
                                <td>{{ $recommend->item->id }}</td>
                                <td>{!! $itemPresenter->cover($recommend->item) !!}</td>
                                <td>{{ $recommend->item->sn }}</td>
                                <td>{{ $recommend->item->title }}</td>
                                <td>{!! $itemPresenter->price($recommend->item) !!}</td>
                                <td>{!! $itemPresenter->originalPrice($recommend->item) !!}</td>
                                <td>{!! $itemPresenter->stock($recommend->item) !!}</td>
                                <td>{!! $itemPresenter->status($recommend->item) !!}</td>
                                <td>
                                    <a href="javascript:void(0);" data-id="{{ $recommend->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $recommends->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::recommends.index')])
@endsection