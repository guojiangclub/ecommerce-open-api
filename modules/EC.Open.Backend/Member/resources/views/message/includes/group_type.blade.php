<!--指定分组-->
<div class="row type_box" id="type_groups" style="display: none;">
    <label class="col-sm-3 control-label">请选择分组：<br>
    </label>
    <div class="col-sm-9">
        @foreach($groups as $group)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="groups[]" value="{{$group->id}}">
                    <i></i> {{$group->name}}
                </label>
            </div>
        @endforeach
    </div>
</div>

<!--指定角色-->
<div class="row type_box" id="type_roles" style="display: none">
    <label class="col-sm-3 control-label">请选择角色：<br>
    </label>
    <div class="col-sm-9">
        @foreach($roles as $role)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="roles[]" value="{{$role->id}}">
                    <i></i> {{$role->display_name}}
                </label>
            </div>
        @endforeach
    </div>
</div>

<!--指定用户-->
<div class="row type_box" id="type_users" style="display: none">
    <label class="col-sm-3 control-label">请输入用户手机或邮箱：<br>
        <small class="text-navy">一行一个，最多支持50个</small>
    </label>
    <div class="col-sm-9">
        <textarea class="form-control" name="users" rows="10"></textarea>
    </div>
</div>