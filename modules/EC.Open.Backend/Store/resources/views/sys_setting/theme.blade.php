{{--@extends('backend::settings.layout')

@section('breadcrumbs')
    <h2>主题设置</h2>
    <ol class="breadcrumb">
        <li class="active">主题设置</li>
    </ol>
@endsection

@section('content')--}}
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="tabs.html#tab-2" aria-expanded="false">主题设置</a></li>
        </ul>
        {{csrf_field()}}
        <div class="tab-content">

            <div id="tab-2" class="tab-pane active">
                <form method="post" action="{{route('admin.setting.theme.save')}}" class="form-horizontal"
                      id="setting_themes_form">
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">选择主题</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="themes[default]">
                                    @if(config('themes.themes'))
                                        @foreach(config('themes.themes') as $key=>$theme)
                                            <option value="{{$key}}"
                                                    @if(config('themes.default')==$key) selected @endif>{{$key}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="ibox-content m-b-sm border-bottom text-right">
                            <button class="btn btn-primary" type="submit">保存设置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
{{--@endsection
@section('after-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}

    <script>
        $(function () {
            $('#add_themes_form').ajaxForm({
                success: function (result) {
                    swal("保存成功!", "", "success");
                    window.location.reload();
                }
            });

            $('#setting_themes_form').ajaxForm({
                success: function (result) {
                    swal("保存成功!", "", "success");
                    window.location.reload();
                }
            });
        })
    </script>
{{--@stop/--}}