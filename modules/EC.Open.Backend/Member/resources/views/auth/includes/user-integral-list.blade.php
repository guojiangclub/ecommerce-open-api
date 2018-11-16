<div class="col-md-12">
    <div class="col-md-4">
        线上积分：{{app('ElementVip\Component\Point\Repository\PointRepository')->getSumPointValid($user->id,'default')}}
    </div>
    <div class="col-md-4">
        线下积分：{{app('ElementVip\Component\Point\Repository\PointRepository')->getSumPointValid($user->id,'offline')}}
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="col-md-12 form-horizontal">
{{--    {!! Form::open(['route' => ['admin.users.addPoint', $user->id], 'method' => 'post', 'class' => 'form-horizontal']) !!}--}}

    <div class="form-group">
        <div class="col-md-5">
            <label class="col-sm-5 control-label">操作积分：</label>

            <div class="col-sm-7">
                {!! Form::text('value',null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
        </div>

        <div class="col-md-5">
            <label class="col-sm-5 control-label">说明：</label>

            <div class="col-sm-7">
                {!! Form::text('note', '管理员操作积分', ['class' => 'col-lg-7 form-control', 'placeholder' =>'']) !!}
            </div>
        </div>

        <div class="col-md-2">
            {{--<input type="submit" class="btn btn-success" value="提交"/>--}}
            <button type="button" class="btn btn-success" id="submit-integral">提交</button>
        </div>

    </div>

{{--    {!! Form::close() !!}--}}
</div>

<div class="ibox-content">
    <button type="button" class="btn btn-w-m btn-success viewPoint"
            data-url="{{route('admin.users.getUserPointList',['id' =>$user->id,'type' => 'online'])}}">查看线上积分
    </button>
    <button type="button" class="btn btn-w-m viewPoint"
            data-url="{{route('admin.users.getUserPointList',['id' =>$user->id,'type' => 'offline'])}}">查看线下积分
    </button>
</div>


<table class="table table-striped table-bordered table-hover table-responsive">
    <thead>
    <tr>
        <th>积分值</th>
        <th>状态</th>
        <th>备注</th>
        <th>动作</th>
        <th>创建日期</th>
    </tr>
    </thead>
    <tbody class="page-point-list">

    </tbody>
</table>
<div class="pull-left">
    <div class="pages">

    </div>
</div>


<div id="no-data">
</div>

<script type="text/html" id="page-temp">
    <tr>
        <td>{#value#}</td>
        <td>{#statusText#}</td>
        <td> {#note#}</td>
        <td> {#action#}</td>
        <td>{#created_at#}</td>
    </tr>
</script>







