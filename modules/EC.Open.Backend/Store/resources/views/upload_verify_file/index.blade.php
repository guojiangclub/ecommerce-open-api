<div class="ibox-content" style="display: block; margin-bottom: 20px;">
    <div class="box box-primary">
        <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
                <tbody>
                <tr>
                    <th>文件名称</th>
                    <th>URL</th>
                    <th>预览</th>
                </tr>
                @if(!empty($fileList))
                    @foreach($fileList as $file)
                        <tr>
                            <td>{{$file['name']}}</td>
                            <td>{{$file['url']}}</td>
                            <td><a target="_blank" href="{{$file['url']}}">点击查看</a></td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
        </div>
    </div>
</div>

<div class="ibox float-e-margins">
    <div class="ibox-content" style="display: block;height: 100px">
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">上传文件(txt文件)：</label>
                        <div class="col-lg-8">
                            <div id="filePicker">选择文件</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        var uploader2 = WebUploader.create({
            auto: true,
            swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
            server: '{{route('admin.uploads.up',['_token'=>csrf_token(),'strategy'=>'index'])}}',
            pick: '#filePicker',
            fileVal: 'file',
            accept: {
                title: 'txt',
                extensions: 'txt',
            }
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader2.on('uploadSuccess', function (file, response) {
            if (!response.status) {
                swal("上传失败!", response.message, "error")
            } else {
                swal({
                    title: "上传成功！",
                    text: "",
                    type: "success"
                }, function () {
                    location = '{!!route('admin.uploads.index')!!}'
                });
            }
        });

    })
</script>