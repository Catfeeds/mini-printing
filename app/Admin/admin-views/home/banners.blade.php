@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">轮播图管理</h3>

                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::banners.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>图片</th>
                            <th>跳转地址</th>
                            <th>排序</th>
                            <th>是否启用</th>
                            <th>操作</th>
                        </tr>
                        @foreach($banners as $banner)
                            <tr>
                                <td><img src="{{ $banner->path }}" width="150" height="80" alt=""></td>
                                <td>{{ json_encode($banner->redirect) }}</td>
                                <td>{{ $banner->order }}</td>
                                <td>{{ $banner->status ? '是' : '否' }}</td>
                                <td>
                                    <a href="{{ route('admin::banners.edit', $banner->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $banner->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::banners.index')])
@endsection