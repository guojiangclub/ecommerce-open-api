@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
modal-lg
@stop
@section('title')
@if($action == 'view')
查看已选微信群
@elseif($action == 'view_exclude')
查看已排微信群
@else
选择微信群
@endif
@stop

@section('after-styles-end')
{!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop


@section('body')
    <div class="row">
        <div class="col-md-12 clearfix">
            <div class="col-sm-3">
                <select class="form-control" name="field">
                    <option value="group_name" {{!empty(request('field')=='group_name')?'selected ':''}}>群名称</option>
	                <option value="group_id" {{!empty(request('field')=='group_id')?'selected ':''}}>群id</option>
                </select>
            </div>

            <div class="col-sm-7">
                <input type="text" name="value" placeholder="Search" value="{{!empty(request('value'))?request('value'):''}}" class=" form-control">
            </div>
            <div class="col-sm-2">
                <button type="button" id="send" class="ladda-button btn btn-primary">搜索
                </button>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="hr-line-dashed "></div>

        <div class="panel-body">
            <h3 class="header">请选择微信群：</h3>
            <div class="table-responsive" id="goodsList">
                <table class="table table-hover table-striped">
                    <thead>
                    <!--tr-th start-->
                    <tr>
                        <th>群id</th>
                        <th>群名称</th>
                        <th>操作</th>
                    </tr>
                    <!--tr-th end-->
                    </thead>

                    <tbody class="page-groups-list">

                    </tbody>
                </table>
            </div><!-- /.box-body -->

            <div class="pages">

            </div>
        </div>
    </div>


    <script type="text/html" id="page-group">
        <tr>
            <td>
                {#group_id#}
            </td>
            <td>
                {#group_name#}
            </td>
            <td>
                <button onclick="changeGroupSelect(this, '{{$action}}')" class="btn btn-circle {#class#}" type="button" data-id="{#id#}"><i class="fa fa-{#icon#}"></i>
                </button>
            </td>
        </tr>
    </script>
@stop
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}


@section('footer')
    <input type="hidden" id="temp_selected_group">
    <input type="hidden" id="temp_exclude_group">

    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="button" onclick="sendGroupIds('{{$action}}');" class="ladda-button btn btn-primary"> 确定
    </button>

@include('store-backend::promotion.public.modal.script')
{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/common.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/jquery.http.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/page/jquery.pages.js') !!}
    <script>
        var action = '{{$action}}';
        var paraDiscount = {_token: _token};

        function getGroupList() {

	        var postUrl = '{{route('admin.promotion.getWechatGroupData')}}';

	        if (action == 'exclude' || action == 'view_exclude') {
		        var selected_group = $('#exclude_group').val();
	        } else {
		        var selected_group = $('#selected_group').val();
	        }

	        $('.pages').pages({
		        page: 1,
		        url: postUrl,
		        get: $.http.post.bind($.http),
		        body: {
			        _token: _token,
			        action: action,
			        ids: paraDiscount.ids,
			        field: $("select[name=field] option:selected").val(),
			        value: $("input[name=value]").val()
		        },
		        marks: {
			        total: 'data.last_page',
			        index: 'data.current_page',
			        data: 'data'
		        }
	        }, function (data) {
		        var html = '';
		        var ids = data.ids;

		        data.data.forEach(function (item) {
		        	if(!item.group_name){
				        item.group_name = '';
                    }

			        if (!~ids.indexOf(String(item.id))) {
				        item.class = 'btn-warning unselect';
				        item.icon = 'times';

			        } else {
				        item.class = 'btn-info select';
				        item.icon = 'check';
			        }

			        html += $.convertTemplate('#page-group', item, '');
		        });
		        $('.page-groups-list').html(html);
	        });
        }

        $(document).ready(function () {

	        if (action == 'exclude' || action == 'view_exclude') {
		        $('#temp_exclude_group').val($('#exclude_group').val());
		        paraDiscount.ids = $('#temp_exclude_group').val();
	        } else {
		        $('#temp_selected_group').val($('#selected_group').val());
		        paraDiscount.ids = $('#temp_selected_group').val();

	        }

	        getGroupList();
        });

        $('#send').on('click', function () {
	        getGroupList();
        });
    </script>
@stop