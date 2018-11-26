<form action="" method="get" class="form-horizontal">

    <div class="form-group">
        <label class="col-sm-2 control-label">手机:</label>
        <div class="col-sm-10"><input type="text" name="mobile"  value="{{request('mobile')}}"  class="form-control"></div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">创建日期:</label>
        <div class="col-sm-10">
            <div class="col-sm-6">
                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                    <input  type="text" class="form-control inline" name="stime"  value="{{request('stime')}}"   placeholder="开始 " readonly>
                    <span class="add-on"><i class="icon-th"></i></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                    <input type="text" class="form-control" name="etime"   value="{{request('etime')}}"    placeholder="截止" readonly>
                    <span class="add-on"><i class="icon-th"></i></span>
                </div>
            </div>
        </div>

    </div>


    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <button class="btn btn-primary" type="submit">搜索</button>
            <a href="javascript:;" class="btn btn-primary" id="empty">清空</a>
        </div>
    </div>

</form>