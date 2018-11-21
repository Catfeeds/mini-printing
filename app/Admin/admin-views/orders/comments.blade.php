@extends('admin::layouts.main')

@section('content')
    @include('admin::search.order-comments')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">评论列表</h3>
                     @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::comments.index')])
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>商品标题</th>
                            <th>用户昵称</th>
                            <th>评论内容</th>
                            <th>评论时间</th>
                            <th>阅读状态</th>
                            <th>操作</th>
                        </tr>
                        @foreach($comments as $comment)
                            <tr>
                                <td>{{ $comment->item->title }}</td>
                                <td>{{ $comment->user->nickname }}</td>
                                <td>{{ $comment->message }}</td>
                                <td>{{ $comment->created_at }}</td>
                                <td>{{ $comment->read_text }}</td>
                                <td>
                                    <a href="{{ route('admin::comments.show', $comment->id) }}" title="查看">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $comment->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $comments->appends($condition)->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::comments.index')])

    <script>
        $('#begin').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: moment.locale('zh-cn')
        });

        $('#end').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: moment.locale('zh-cn')
        });
    </script>
@endsection