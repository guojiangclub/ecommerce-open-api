<form action="" method="get" class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-2 control-label">邮箱</label>
        <div class="col-sm-10"><input type="text" name="email"   value="{{request('email')}}"    class="form-control"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">电话</label>
        <div class="col-sm-10"><input type="text" name="mobile"  value="{{request('mobile')}}"    class="form-control"></div>
    </div>

    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <button class="btn btn-primary" type="submit">搜索</button>
        </div>
    </div>
</form>