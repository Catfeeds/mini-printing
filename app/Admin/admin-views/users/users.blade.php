@extends('admin::layouts.main')

@section('content')

    @include('admin::search.users-users')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">会员列表</h3>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::users.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>昵称</th>
                            <th>OpenId</th>
                            <th>生日</th>
                            <th>联系方式</th>
                            <th>成交</th>
                            <th>注册时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->nickname }}</td>
                                <td>{{ $user->authWechat->open_id }}</td>
                                <td>{{ $user->birthday }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    订单数：{{ $user->orders->count() }}
                                    <br>
                                    金额： ￥{{ $user->orders->sum('price') }}
                                </td>
                                <td>{{ $user->created_at }}</td>
                                <td>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $users->appends($data)->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection