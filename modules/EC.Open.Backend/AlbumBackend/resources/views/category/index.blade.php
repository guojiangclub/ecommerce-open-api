    {!! Html::style(env("APP_URL").'/assets/backend/file-manage/bootstrap-treeview/bootstrap-treeview.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/file-manage/el-Upload/css/pop.css') !!}


    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <!-- /.row -->
            <a data-toggle="modal"
               data-target="#category_modal" data-backdrop="static" data-keyboard="false"
               data-url="{{route('admin.image-category.create')}}" class="btn btn-primary margin-bottom">添加图片分组</a>

            <div class="dataTable_wrapper">
                <table class="table table-striped table-hover" id="list_table">
                    <thead>
                    <tr>
                        <th>排序</th>
                        <th>分类名称</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr id="{{ $category->id }}" parent="{{ $category->parent_id }}">
                            <td>
                                @if($category->id == 1)
                                    <input style="width: 45px" id="s{{ $category->id }}" value="{{ $category->sort }}"
                                           class="form-control" type="text" size="2" disabled>
                                @else
                                    <input style="width: 45px" id="s{{ $category->id }}" value="{{ $category->sort }}"
                                           class="form-control" type="text" size="2"
                                           onblur="toSort( {{ $category->id }} );">
                                @endif
                            </td>
                            <td>
                                <img style='margin-left:{{ ($category->level - 1) * 20 }}px' class="operator"
                                     src="{!! url('assets/backend/images/close.gif') !!}" onclick="displayData(this);"
                                     alt="关闭"/>
                                {{ $category->name }}
                            </td>

                            <td>
                                @if($category->id !== 1)
                                    <a class="btn btn-xs btn-primary" data-toggle="modal"
                                       data-target="#category_modal" data-backdrop="static" data-keyboard="false"
                                       data-url="{{route('admin.image-category.edit',['id'=>$category->id])}}">
                                        <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top"
                                           title="编辑"></i>
                                    </a>

                                    @if($category->level <3)
                                        <a class="btn btn-xs btn-primary" data-toggle="modal"
                                           data-target="#category_modal" data-backdrop="static" data-keyboard="false"
                                           data-url="{{route('admin.image-category.create',['parent_id'=>$category->id])}}">
                                            <i data-toggle="tooltip" data-placement="top" class="fa fa-pencil-square-o"
                                               title="" data-original-title="添加子分类"></i></a>

                                    @endif
                                    <button onclick="checkCategory({{$category->id}})" class="btn btn-xs btn-danger">
                                        <i class="fa fa-trash" data-toggle="tooltip" data-placement="top"
                                           title="删除"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div id="category_modal" class="modal inmodal fade"></div>
    <!-- /.row -->

    @include('file-manage::category.script')
