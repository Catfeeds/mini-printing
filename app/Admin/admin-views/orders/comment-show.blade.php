@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">评论详情</h3>
                    <div class="btn-group pull-right" style="margin-right: 10px">
                        <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                    </div>
                </div>

                <div class="box-body">
                    <div id="direct-chat-messages" class="direct-chat-messages" style="height: 400px;">
                        <div class="direct-chat-msg">
                            <div style="margin-bottom: 10px">
                                @inject('itemPresenter', "App\Admin\Presenters\ItemPresenter")
                                {!! $itemPresenter->cover($comment->item) !!} &nbsp; {{ $comment->item->title }}
                            </div>

                            <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-left">{{ $comment->user->nickname }}</span>
                                <span class="direct-chat-timestamp pull-right">{{ $comment->created_at }}</span>
                            </div>

                            <img class="direct-chat-img" src="{{ Admin::user()->avatar }}" alt="Message User Image">
                            <div class="direct-chat-text">

                                {{ $comment->message }} <br><br>

                                @if($comment->images != null)
                                    @foreach($comment->images as $image)
                                        <img src="{{ $image->url }}" alt="Message User Image" height="200" width="150">
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        @if($comment->reply != null)
                            <div class="direct-chat-msg right">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-right">管理员</span>
                                    <span class="direct-chat-timestamp pull-left">{{ $comment->reply->created_at }}</span>
                                </div>

                                <img class="direct-chat-img" src="{{ Admin::user()->avatar }}" alt="Message User Image">
                                <div class="direct-chat-text">
                                    {{ $comment->reply->message }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="box-footer">
                    @if($comment->reply === null)
                        <form id="post-form" action="{{ route('admin::comments.update', $comment->id) }}" method="post">
                            {{ csrf_field() }}
                            <div class="input-group">
                                <input type="text" name="message" placeholder="回复 ..." class="form-control">
                                <span class="input-group-btn">
                                <button type="submit" id="submit-btn" class="btn btn-primary btn-flat">发送</button>
                            </span>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('.form-history-back').on('click', function (event) {
                event.preventDefault();
                history.back();
            });

            ///
            $("#post-form").bootstrapValidator({
                live: 'enable',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    message:{
                        validators:{
                            notEmpty:{
                                message: '请输入内容'
                            }
                        }
                    }
                }
            });

            $("#submit-btn").click(function () {
                var $form = $("#post-form");
                var tpl = "<div class=\"direct-chat-msg right\">\n" +
                    "<div class=\"direct-chat-info clearfix\">\n" +
                    "<span class=\"direct-chat-name pull-right\">管理员</span>\n" +
                    "<span class=\"direct-chat-timestamp pull-left\">"+ new Date().toLocaleTimeString() +"</span>\n" +
                    "</div>\n" +
                    "<img class=\"direct-chat-img\" src=\"{{ Admin::user()->avatar }}\" alt=\"Message User Image\">\n" +
                    "<div class=\"direct-chat-text\">\n" +
                    $("input[name='message']").val() +
                    "</div>\n" +
                    "</div>";

                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()) {
                    $("#direct-chat-messages").append(tpl);

                    $.ajax({
                        url: $form.attr('action'),
                        type: 'PUT',
                        data: $form.serialize(),
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                $form.remove();
                            }
                        }
                    });
                }
            })
        })
    </script>
@endsection