<div class="hr-line-dashed"></div>
<div class="table-responsive">
    @if(count($comments_list)>0)
        <table class="table table-hover table-striped">
            <tbody>
            <!--tr-th start-->
            <tr>
                <th>商品名称</th>
                <th>发表人</th>
                <th>分数</th>
                <th>内容</th>
                <th>评论时间</th>
                <th>推荐评论</th>
                <th>操作</th>
            </tr>
            <!--tr-th end-->
                @foreach($comments_list as $comment)
                    <tr>
                        <td>{{$comment->goods->name}}</td>
                        <td>@if(count($comment->user)){{$comment->user->nick_name?$comment->user->nick_name:$comment->user->mobile}} @else / @endif</td>
                        <td>{{$comment->point}}</td>
                        <th>{{str_limit($comment->contents,36)}}</th>
                        <th>{{$comment->created_at}}</th>
                        <td>
                            @if($comment->recommend==0)
                                <label class="label label-danger">No</label>
                            @elseif($comment->recommend==1)
                                <label class="label label-success">Yes</label>
                            @endif
                        </td>

                        <td>

                        <a class="btn btn-xs btn-success" id="chapter-create-btn" data-toggle="modal"
                           data-target="#modal" data-backdrop="static" data-keyboard="false"
                           data-url="{{route('admin.comments.edit',['id'=>$comment->id])}}">
                            @if($comment->audit==1)
                            <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="查看"></i></a>
                            @elseif($comment->audit==0||$comment->audit==2)
                            <i data-toggle="tooltip" data-placement="top"
                               class="fa fa-pencil-square-o"
                               title="审核"></i></a>
                            @endif
                        </a>

                       @if($comment->audit==2)
                        <a  data-method="delete" class="btn btn-xs btn-danger"
                            href="{{route('admin.comments.destroy',['id'=>$comment->id])}}">
                            <i data-toggle="tooltip" data-placement="top"
                               class="fa fa-trash"
                               title="删除"></i></a>
                        @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pull-left">
            &nbsp;&nbsp;共&nbsp;{!!$comments_list->total() !!} 条记录
        </div>

        <div class="pull-right id='ajaxpag'">
            {!!$comments_list->render() !!}
        </div>
        <!-- /.box-body -->
    @else
        <div>
            &nbsp;&nbsp;&nbsp;当前无数据
        </div>
    @endif
</div>












