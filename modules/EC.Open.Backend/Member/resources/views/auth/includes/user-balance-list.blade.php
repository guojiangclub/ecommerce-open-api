<div class="col-md-12">


        <div class="col-md-5">
            <label class="col-sm-3 control-label">操作余额：</label>
            <div class="col-sm-9">
                <input class="form-control" placeholder="" type="text" name="balance_value">
            </div>
        </div>

        <div class="col-md-5">
            <label class="col-sm-3 control-label">说明：</label>
            <div class="col-sm-9">
                <input class="form-control" placeholder="管理员操作积分" value="管理员操作余额" type="text" name="balance_note">
            </div>
        </div>

        <div class="col-md-2">
            <input type="hidden" value="{{$user->id}}" name="user_id">
            <button type="button" class="btn btn-success operateBalance">提交</button>
        </div>


</div>


<div style="margin: 10px 0; clear: both; height: 10px;"></div>

<table class="table table-striped table-bordered table-hover table-responsive">
    <thead>
    <tr>
        <th>金额</th>
        <th>备注</th>
        <th>当前余额</th>
        <th>创建日期</th>
    </tr>
    </thead>
    <tbody class="page-balance-list">

    </tbody>
</table>
<div class="pull-left">
    <div class="balance-pages">

    </div>
</div>


<div id="no-balance-data">
</div>

<script type="text/html" id="page-balance-temp">
    <tr>
        <td>{#value#}</td>
        <td>{#note#}</td>
        <td>{#current_balance#}</td>
        <td>{#created_at#}</td>
    </tr>
</script>







