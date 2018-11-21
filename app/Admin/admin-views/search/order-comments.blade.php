<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">筛选</h4>
            </div>
            <form action="{{ route('admin::comments.index') }}" method="get" pjax-container>
                <div class="modal-body">
                    <div class="form">
                        <div class="form-group">
                            <label>用户昵称</label>
                            <input type="text" class="form-control" placeholder="用户昵称" name="nickname" value="{{ request('nickname') }}">
                        </div>
                        <div class="form-group">
                            <label>评论时间区间</label>
                            <input type="text" class="form-control" id="begin" placeholder="起始时间" name="start" value="{{ request('start') }}">
                            -
                            <input type="text" class="form-control" id="end" placeholder="结束时间" name="end" value="{{ request('end') }}">
                        </div>
                        <div class="form-group">
                            <label>阅读状态</label>
                            <select class="form-control" name="read" >
                                <option value=''>请选择</option>
                                <option value='0' {{ request('read')=='0'?'selected':'' }} >未读</option>
                                <option value='1' {{ request('read')?'selected':'' }} >已读</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit">提交</button>
                    <button type="reset" class="btn btn-warning pull-left">撤销</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#filter-modal .submit").click(function () {
        $("#filter-modal").modal('toggle');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });
</script>