<script>
    $('#create-message-form').ajaxForm({
        success: function (result) {
            if (!result.status) {
                swal("保存失败!", result.error, "error")
            } else {
                swal({
                    title: "保存成功！",
                    text: "",
                    type: "success"
                }, function () {
                    location = '{!!route('admin.RoleManagement.role.index')!!}';
                });
            }
        }
    });


    /*发送对象选择切换*/
    $("input[name='group_type']").on('ifClicked', function () {
        var type = $(this).val();
        if (type == 'all') {
            $('.type_box').hide();
        }

        if (type == 'users') {
            $('#type_users').show();
            $('#type_users').siblings().hide();
        }

        if (type == 'groups') {
            $('#type_groups').show();
            $('#type_groups').siblings().hide();
        }

        if (type == 'roles') {
            $('#type_roles').show();
            $('#type_roles').siblings().hide();
        }
    });
</script>