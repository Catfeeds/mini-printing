@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">创建</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::topics.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::topics.store') }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">专题名</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="name" name="name" value="" class="form-control name" placeholder="输入 名称">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="items" class="col-sm-2 control-label">商品集合</label>
                                <div class="col-sm-8">
                                    <select class="form-control items" style="width: 100%;" name="items[]" multiple="multiple" data-placeholder="输入 权限"  >
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" >{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="btn-group pull-left">
                            <button type="reset" class="btn btn-warning">重置</button>
                        </div>
                        <div class="btn-group pull-right">
                            <button type="submit" id="submit-btn" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('.form-history-back').on('click', function (event) {
                event.preventDefault();
                history.back(1);
            });
        });

        $('.items').bootstrapDualListbox({
            "infoText": "text_total",
            "infoTextEmpty": "text_empty",
            "infoTextFiltered": "过滤",
            "filterTextClear": "清除",
            "filterPlaceHolder": "搜索"
        });

        $("#post-form").bootstrapValidator({
            live: 'enable',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                name:{
                    validators:{
                        notEmpty:{
                            message: '名称不能为空'
                        }
                    }
                }
            }
        });

        $("#submit-btn").click(function () {
            var $form = $("#post-form");

            $form.bootstrapValidator('validate');
            if ($form.data('bootstrapValidator').isValid()) {
                $form.submit();
            }
        })
    </script>
@endsection