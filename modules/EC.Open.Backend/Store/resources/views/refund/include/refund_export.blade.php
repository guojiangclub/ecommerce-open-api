<div class="form-group">
    <label class="control-label col-md-2">售后状态：</label>
    <div class="col-md-10">
        <select class="form-control refund_status">
            <option value="all">所有状态</option>
            <option value="0">待处理</option>
            <option value="1">处理中</option>
            <option value="2">已完成</option>
        </select>
    </div>
</div>


<div class="form-group">
    <label class="control-label col-md-2">申请时间：</label>
    <div class="col-md-10">
        <div class="col-sm-6">
            <div class="input-group date form_datetime">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;</span>
                <input type="text" class="form-control inline" name="stime"
                       value="{{\Carbon\Carbon::now()->addDay(-1)}}" placeholder="开始 " readonly>
                <span class="add-on"><i class="icon-th"></i></span>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control" name="etime" value="{{\Carbon\Carbon::now()}}"
                       placeholder="截止" readonly>
                <span class="add-on"><i class="icon-th"></i></span>
            </div>
        </div>
    </div>
</div>

<div class="form-group" id="complete_time" style="display: none">
    <label class="control-label col-md-2">完成时间：</label>
    <div class="col-md-10">
        <div class="col-sm-6">
            <div class="input-group date form_datetime">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;</span>
                <input type="text" class="form-control inline" name="c_stime"
                       value="{{\Carbon\Carbon::now()->addDay(-1)}}" placeholder="开始 " readonly>
                <span class="add-on"><i class="icon-th"></i></span>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control" name="c_etime" value="{{\Carbon\Carbon::now()}}"
                       placeholder="截止" readonly>
                <span class="add-on"><i class="icon-th"></i></span>
            </div>
        </div>
    </div>
</div>


<div class="form-group">
    <div class="col-md-6 col-md-offset-4">
        <a class="btn btn-primary" data-toggle="modal"
           data-target="#modal" data-backdrop="static" data-keyboard="false"
           data-link="{{route('admin.refund.getExportData')}}" id="refunds_xls"
           data-url="{{route('admin.export.index',['toggle'=>'refunds_xls'])}}"
           data-type="xls"
           href="javascript:;">导出</a>
    </div>
</div>
