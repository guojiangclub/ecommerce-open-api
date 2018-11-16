{{--@extends('member-backend::layout')--}}

{{--@section ('title','会员卡列表')--}}

{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/css/member_card.css') !!}
    {!! Html::style(env("APP_URL").'/assets/wechat-backend/libs/ladda/ladda-themeless.min.css') !!}
{{--@stop--}}

{{--@section('breadcrumbs')--}}

    {{--<h2>会员卡列表</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li>--}}
            {{--<a href="{!!route('admin.users.index')!!}">--}}
                {{--<i class="fa fa-dashboard"></i> 首页--}}
            {{--</a>--}}
        {{--</li>--}}
        {{--<li class="active">会员卡列表</li>--}}
    {{--</ol>--}}

{{--@endsection--}}

{{--@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="row">
                <div class="col-sm-5">
                    <div class="btn-group">
                        @if($count>=1)
                            {{--<a class="btn btn-primary" id="add_member_card">添加会员卡</a>--}}
                        @else
                            <a class="btn btn-primary" href="{{route('admin.member.card.create')}}">添加会员卡</a>
                        @endif

                    </div>
                    <div class="btn-group">
                        <a class="btn btn-primary syncWeixin" href="javascript:void(0)">同步发布至微信卡包</a>
                    </div>
                </div>
            </div>
            <div class="row">
                {!! Form::open( [ 'route' => ['admin.member.card'], 'method' => 'get', 'class'=>'form-horizontal'] ) !!}
                <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="name" placeholder="会员卡名称" class=" form-control">
                            <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary">查找</button>
                            </span>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>

            <div class="hr-line-dashed"></div>

            <div class="box box-primary">

                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <tr>
                            <th style="text-align: center;">会员卡名称</th>
                            <th style="text-align: center; width: 400px;">卡片封面</th>
                            {{--<th style="text-align: center;">消费升级金额</th>
                            <th style="text-align: center;">等级计算周期</th>
                            <th style="text-align: center;">会员卡等级</th>--}}
                            <th style="text-align: center;">激活</th>
                            <th style="text-align: center;">状态</th>
                            <th style="text-align: center;">操作</th>
                        </tr>
                        @foreach ($cardList as $item)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{$item->name}}</td>
                                <td>
                                    <div class="card-region" style="@if($item->card_cover==1)background-color: #{{$item->bg_color}};@elseif($item->card_cover==2)background-image: url({{$item->bg_img}});@else background-color: #55bd47;@endif">
                                        <div class="card-header">
                                            <h4 class="shop-name">
                                                <span class="shop-logo" style="background-image:url('{{$item->store_logo}}')"></span>
                                                <div class="shop-name-title">
                                                    <p class="card_store_name">{{$item->store_name}}</p>
                                                    <p class="card_name">{{$item->name}}</p>
                                                </div>
                                            </h4>
                                            <div class="qr-code"></div>
                                        </div>
                                        <h3 class="member-type"></h3>
                                        <div class="card-content">
                                            <p class="expiry-date">有效期：<span>无限期</span></p>
                                        </div>
                                    </div>
                                </td>

                                {{--<td style="text-align: center; vertical-align: middle;">{{$item->upgrade_amount}} 元</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->period}} 天</td>
                                <td style="text-align: center; vertical-align: middle;">等级 {{$item->grade}}</td>--}}

                                <td style="text-align: center; vertical-align: middle;">{{$item->isActivate ? '需要激活' : '无需激活'}}</td>
                                <td style="text-align: center; vertical-align: middle;">{{$item->status ? '上架' : '下架'}}</td>
                                <td style="text-align: center; vertical-align: middle;">
                                        <a href="javascript:void(0)" data-id="{{ $item->id }}" class="btn btn-xs btn-danger delete-member-card">
                                            <i data-toggle="tooltip" data-placement="top" class="fa fa-trash" title="删除"></i>
                                        </a>
                                        <a class="btn btn-xs btn-primary" href="{{route('admin.member.card.update', ['id'=>$item->id])}}">
                                            <i data-toggle="tooltip" data-placement="top" class="fa fa-pencil-square-o" title="编辑"></i>
                                        </a>
                                        <a>
                                            <i class="fa switch @if($item->status) fa-toggle-on @else fa-toggle-off @endif" title="切换状态" value= {{$item->status}} ><input type="hidden" value={{$item->id}}></i>
                                        </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    {!! $cardList->render() !!}
                </div>
            </div>
        </div>
    </div>
{{--@endsection--}}

{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/wechat-backend/libs/ladda/spin.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/wechat-backend/libs/ladda/ladda.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/wechat-backend/libs/ladda/ladda.jquery.min.js') !!}
    <script type="text/javascript">
        function to_swal(msg, type, handle) {
            swal({
                title: msg,
                text: "",
                type: type,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                cancelButtonText: '取消',
                closeOnConfirm: false
            }, handle);
        }

        $('.syncWeixin').on('click', function () {
            var syncWeixinHandle = function () {
                $.post("{{route('admin.member.card.wxCard')}}", function (res) {
                    if (res.code == 200) {
                        swal("操作成功!", "", "success");
                    } else {
                        swal("操作失败!", res.message, "error");
                    }
                }, 'json');
            };
            to_swal('您真的要同步发布至微信卡包要吗', 'warning', syncWeixinHandle);
        });

        $('.activate').on('click', function () {
            var syncWeixinHandle = function () {
                $.post("{{route('admin.member.card.wxCardActivate')}}", function (res) {
                    if (res.code == 200) {
                        swal("操作成功!", "", "success");
                    } else {
                        swal("操作失败!", res.message, "error");
                    }
                }, 'json');
            };
            to_swal('您真的要激活会员卡吗', 'warning', syncWeixinHandle);
        });

        $('#add_member_card').on('click', function () {
            swal('只允许添加一张会员卡卷', '', "warning");
        });

        $('.delete-member-card').on('click', function () {
            var id = $(this).attr('data-id');
            var deleteHandle = function () {
                var url = '{{ route('admin.member.card.delMemberCard') }}' + '?id=' + id;
                $.get(url, function (res) {
                    if (res.status) {
                        swal("操作成功!", "", "success");
                        window.location.reload();
                    } else {
                        swal("操作失败!", res.message, "error");
                    }
                });
            };
            to_swal('您真的要删除该会员卡吗', 'warning', deleteHandle);
        });

        $('.switch').on('click', function () {
            var value = $(this).attr('value');
            var modelId = $(this).children('input').attr('value');


            value = parseInt(value);
            modelId = parseInt(modelId);

            if (value == 1) {
                value = 0;
            } else {
                value = 1;
            }

            var that = $(this);
            $.post("{{route('admin.member.card.status')}}",
                    {
                        status: value,
                        modelId: modelId
                    },
                    function (data, status) {
                        if (status) {
                            if (1 == value) {
                                that.removeClass('fa-toggle-off');
                                that.addClass('fa-toggle-on');
                                that.parent('a').parent('td').prev('td').html('上架');
                            } else {
                                that.removeClass('fa-toggle-on');
                                that.addClass('fa-toggle-off');
                                that.parent('a').parent('td').prev('td').html('下架');
                            }
                            that.attr('value', value);
                        }
                    });

        })
    </script>
{{--@stop--}}