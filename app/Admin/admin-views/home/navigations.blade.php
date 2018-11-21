@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">导航管理</h3>

                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::navigations.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>图标</th>
                            <th>名称</th>
                            <th>排序</th>
                            <th>是否启用</th>
                            <th>操作</th>
                        </tr>
                        @foreach($navigations as $navigation)
                            <tr>
                                <td><img src="{{ $navigation->icon }}" class="img-circle" width="50" height="50" alt=""></td>
                                <td>{{ $navigation->title }}</td>
                                <td>{{ $navigation->order }}</td>
                                <td>{{ $navigation->status ? '是' : '否' }}</td>
                                <td>
                                    <a href="{{ route('admin::navigations.edit', $navigation->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $navigation->id }}" class="grid-row-delete">
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
    @include('admin::js.grid-row-delete', ['url' => route('admin::navigations.index')])
@endsection