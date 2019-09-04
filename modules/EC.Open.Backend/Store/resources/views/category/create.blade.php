    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">

            {!! Form::open(['route' => 'admin.category.store'
            , 'class' => 'form-horizontal'
            , 'role' => 'form'
            , 'method' => 'POST'
             ,'id'=>'Category_form']) !!}

            @include('store-backend::category.form')

            {!! Form::close() !!}
                    <!-- /.tab-content -->
        </div>
    </div>

    <script>
        $('#Category_form').ajaxForm({
            success: function (result) {
                if(!result.status)
                {
                    swal("保存失败!", result.message, "error")
                }else{
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        location = '{{route('admin.category.index')}}';
                    });
                }

            }
        });

    </script>