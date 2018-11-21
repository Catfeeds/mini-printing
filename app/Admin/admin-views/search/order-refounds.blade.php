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
            <form action="{{ route('admin::refunds.index') }}" method="get" pjax-container>
                <div class="modal-body">
                    <div class="form">
                        <div class="form-group">
                            <div class="form-group">
                                <label>订单号</label>
                                <input type="text" class="form-control" placeholder="编号" name="sn" value="{{ request('sn') }}">
                            </div>
                            <div class="form-group">
                                <label>下单时间区间</label>
                                <input type="text" class="form-control" id="begin" placeholder="起始时间" name="start" value="{{ request('start') }}">
                                -
                                <input type="text" class="form-control" id="end" placeholder="结束时间" name="end" value="{{ request('end') }}">
                            </div>
                            <div class="form-group">
                                <label>退单状态</label>
                                <select class="form-control" name="status" >
                                    <option value=''>请选择</option>
                                    <option value='0' {{ request('status')=='0'?'selected':'' }} >已取消</option>
                                    <option value='1' {{ request('status')?'selected':'' }} >申请中</option>
                                    <option value='2' {{ request('status')?'selected':'' }} >已同意</option>
                                    <option value='3' {{ request('status')?'selected':'' }} >退单中</option>
                                    <option value='4' {{ request('status')?'selected':'' }} >已退单</option>
                                    <option value='5' {{ request('status')?'selected':'' }} >已拒绝</option>
                                </select>
                            </div>
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