    <link href="{{env("APP_URL")}}/assets/backend/libs/webuploader-0.1.5/webuploader.css" rel="stylesheet">

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {!! Form::open( [ 'url' => [route('admin.comments.store')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}

            <div class="form-group">
                {!! Form::label('name','评论商品：', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    <div class="row">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="请输入商品名称搜索" id="name">
                                    <span class="input-group-btn"> <button type="button" id="search-goods"
                                                                           class="btn btn-primary">搜索
                                        </button> </span>
                        </div>
                    </div>
                    <div class="row" id="goods_list">

                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','评论用户：', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10" style="margin-left: 140px;">

                    <div class="row">
                        <div class="col-sm-2" style="text-align: right">用户昵称：</div>
                        <div class="col-sm-10"><input type="text" name="nick_name" class="form-control"></div>
                    </div>
                    <div class="row" style="margin-top: 10px">
                        <div class="col-sm-2" style="text-align: right">用户等级：</div>
                        <div class="col-sm-10"><input type="text" name="grade" class="form-control" value="0"></div>
                    </div>
                    <div class="row" style="margin-top: 10px">
                        <div class="col-sm-2" style="text-align: right">用户头像：</div>
                        <div class="col-sm-10">
                            <div class="pull-left" id="userAvatar">
                                <img src="{{env('APP_URL').'/assets/backend/images/default_head_ico.png'}}" id="avatar"
                                     style="
                                         margin-right: 23px;
                                         width: 100px;
                                         height: 100px;
                                         border-radius: 50px;">
                                <input class="form-control" name="avatar" type="hidden"
                                       value="{{env('APP_URL').'/assets/backend/images/default_head_ico.png'}}">
                            </div>

                            <div id="avatarPicker" style="padding-top: 30px">上传头像</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','评论内容：', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    <textarea class="form-control" name="contents" rows="3"></textarea>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','评论图片：', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    <div id="logoPicker">上传图片</div>
                    <div class="row" id="img_list">

                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','评论星级：', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    <select name="point" class="form-control">
                        <option value="5">5星</option>
                        <option value="4">4星</option>
                        <option value="3">3星</option>
                        <option value="2">2星</option>
                        <option value="1">1星</option>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">推荐状态：</label>
                <div class="col-sm-10">
                    <div class="i-checks">
                        <label> <input type="radio" value="0" name="recommend" checked/> <i></i>不推荐</label>
                        <label> <input type="radio" value="1" name="recommend"/> <i></i>推荐</label>
                    </div>
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8 controls">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>

            {!! Form::close() !!}
                    <!-- /.tab-content -->
        </div>
    </div>

    <script src="{{env("APP_URL")}}/assets/backend/libs/webuploader-0.1.5/webuploader.js"></script>
    <script>
        $('#search-goods').on('click', function () {
            var name = $('#name').val();
            if (!name) {
                swal('请输入商品名称', '', 'warning');
            }
            $.post('{{route('admin.comments.searchGoods')}}', {name: name, _token: _token}, function (result) {
                $('#goods_list').html(result);
            });
        });

        $('#search-users').on('click', function () {
            var mobile = $('#mobile').val();
            if (!mobile) {
                swal('请输入用户手机', '', 'warning');
            }
            $.post('{{route('admin.comments.searchUsers')}}', {mobile: mobile, _token: _token}, function (result) {
                $('#users_list').html(result);
            });
        });

        var uploader = WebUploader.create({
            auto: true,
            swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
            server: '{{route('file.upload',['_token'=>csrf_token()])}}',
            pick: '#logoPicker',
            fileVal: 'file',
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on('uploadSuccess', function (file, response) {
            $('input[name="dmp_config[backend_logo]"]').val(response.url);
            $('.backend_logo').attr('src', response.url);
            var imgUrl = response.url;
            var img = '<img src=' + imgUrl + ' width=100 />';
            var input = '<input name=img[] type=hidden value=' + imgUrl + '>';

            $('#img_list').append(img + input);
        });

        //上传头像
        var avatarUploader = WebUploader.create({
            auto: true,
            swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
            server: '{{route('file.upload',['_token'=>csrf_token()])}}',
            pick: '#avatarPicker',
            fileVal: 'file',
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        avatarUploader.on('uploadSuccess', function (file, response) {
            $('input[name="avatar]').val(response.url);
            $('#avatar').attr('src', response.url);

        });


        $('#base-form').ajaxForm({
            success: function (result) {
                if (result.status) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        window.location.href = '{{route('admin.comments.index',['status'=>'show'])}}'
                    });
                } else {
                    swal('保存失败', result.message, 'warning');
                }

            }
        });

    </script>
