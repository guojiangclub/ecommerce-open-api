    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
    <style>
        .search_select {
            display: inline-block;
            float: left;
            width: 30%;
            min-width: 95px;
            max-width: 135px;
            margin-right: 4px;
        }

        .search_range {
            display: inline-block;
            float: left;
            width: 65%;
            line-height: 34px;
        }

        .search_range_store {
            width: 100%;
        }

        .search_range .search_input {
            width: 45%;
            min-width: 65px;
            max-width: 145px;
        }

        .search_range_store .search_input {
            width: 35%;
        }

        .search_label {
            width: 15%;
            min-width: 45px;
            float: left;
        }

        .search_select_text {
            min-width: 105px;
        }
    </style>

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="{{ Active::query('view',0) }}">
                <a href="{{route('admin.goods.index',['view'=>0, 'price'=>request('price') ? request('price') : '', 'price_begin'=>request('price_begin') ? request('price_begin') : '', 'price_end'=>request('price_end') ? request('price_end') : '', 'store_begin'=>request('store_begin') ? request('store_begin') : '', 'store_end'=>request('store_end') ? request('store_end') : '', 'field'=>request('field') ? request('field') : '', 'value'=>request('value') ? request('value') : ''])}}" no-pjax> 上架商品
                    <span class="badge">{{$allCount}}</span></a>
            </li>
            <li class="{{ Active::query('view',2) }}">
                <a href="{{route('admin.goods.index',['view'=>2, 'price'=>request('price') ? request('price') : '', 'price_begin'=>request('price_begin') ? request('price_begin') : '', 'price_end'=>request('price_end') ? request('price_end') : '', 'store_begin'=>request('store_begin') ? request('store_begin') : '', 'store_end'=>request('store_end') ? request('store_end') : '', 'field'=>request('field') ? request('field') : '', 'value'=>request('value') ? request('value') : ''])}}" no-pjax> 已下架商品
                    <span class="badge">{{$offCount}}</span></a>
            </li>
            <li class="{{ Active::query('view',1) }}">
                <a href="{{route('admin.goods.index',['view'=>1, 'price'=>request('price') ? request('price') : '', 'price_begin'=>request('price_begin') ? request('price_begin') : '', 'price_end'=>request('price_end') ? request('price_end') : '', 'store_begin'=>request('store_begin') ? request('store_begin') : '', 'store_end'=>request('store_end') ? request('store_end') : '', 'field'=>request('field') ? request('field') : '', 'value'=>request('value') ? request('value') : ''])}}" no-pjax> 已删除商品
                    <span class="badge">{{$delCount}}</span></a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="btn-group">
                                <a class="btn btn-primary " href="{{ route('admin.goods.create') }}" no-pjax>添加商品</a>
                            </div>
                            <div class="btn-group">
                                <a class="btn btn-primary ladda-button dropdown-toggle batch" data-toggle="dropdown"
                                   href="javascript:;" data-style="zoom-in">导出全部商品 <span
                                            class="caret"></span></a>
                                <ul class="dropdown-menu">

                                    <li><a class="export-goods" data-toggle="modal"
                                           data-target="#download_modal" data-backdrop="static" data-keyboard="false"
                                           data-link="{{route('admin.goods.getExportData',['type'=>'xls','view'=>request('view')])}}"
                                           id="xls"
                                           data-url="{{route('admin.export.index',['toggle'=>'xls'])}}"
                                           data-type="xls"
                                           href="javascript:;">导出xls格式</a></li>

                                    <li><a class="export-goods" data-toggle="modal"
                                           data-target="#download_modal" data-backdrop="static" data-keyboard="false"
                                           data-link="{{route('admin.goods.getExportData',['type'=>'csv','view'=>request('view')])}}"
                                           id="csv"
                                           data-url="{{route('admin.export.index',['toggle'=>'csv'])}}"
                                           data-type="csv"
                                           href="javascript:;">导出csv格式</a></li>
                                </ul>

                            </div>
                            <div class="btn-group">
                                <a class="btn btn-primary ladda-button dropdown-toggle batch" data-toggle="dropdown"
                                   href="javascript:;" data-style="zoom-in">导出搜索结果 / 勾选商品 <span
                                            class="caret"></span></a>
                                <ul class="dropdown-menu">

                                    <li><a class="export-search-goods" data-toggle="modal-filter"
                                           data-target="#download_modal" data-backdrop="static" data-keyboard="false"
                                           id="filter-xls"
                                           data-url="{{route('admin.export.index',['toggle'=>'filter-xls'])}}"
                                           data-type="xls"
                                           href="javascript:;">导出xls格式</a></li>

                                    <li><a class="export-search-goods" data-toggle="modal-filter"
                                           data-target="#download_modal" data-backdrop="static" data-keyboard="false"
                                           id="filter-csv"
                                           data-url="{{route('admin.export.index',['toggle'=>'filter-csv'])}}"
                                           data-type="csv"
                                           href="javascript:;">导出csv格式</a></li>
                                </ul>
                            </div>

                            <div class="btn-group">
                                <a class="btn btn-primary ladda-button dropdown-toggle batch" data-toggle="dropdown"
                                   href="javascript:;" data-style="zoom-in">批量操作 <span
                                            class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a class="lineGoods" data-status="3" href="javascript:;">上架商品</a></li>
                                    <li><a class="lineGoods" data-status="2" href="javascript:;">下架商品</a></li>
                                    <li><a class="lineGoods" data-status="1" href="javascript:;">删除商品</a></li>
                                    <li><a no-pjax href="{{ route('admin.goods.uplode_inventorys') }}">批量导入库存</a></li>
                                    <li><a data-toggle="modal-modify-title"
                                           data-target="#modal" data-backdrop="static" data-keyboard="false"
                                           data-link="{{route('admin.goods.operationTitle')}}"
                                           href="javascript:;">批量修改商品标题</a></li>

                                    <li><a data-toggle="modal-modify-title"
                                           data-target="#modal" data-backdrop="static" data-keyboard="false"
                                           data-link="{{route('admin.goods.operationTags')}}"
                                           href="javascript:;">批量添加商品标签</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <form action="" method="get">
                            <input type="hidden" name="view" value="{{!empty(request('view'))?request('view'):0}}">
                            <div class="col-md-4">
                                <select class="form-control search_select" name="price">
                                    <option value="sell_price" {{!empty(request('price')=='sell_price')?'selected ':''}}>
                                        SPU销售价
                                    </option>
                                    <option value="market_price" {{!empty(request('price')=='market_price')?'selected ':''}}>
                                        SPU吊牌价
                                    </option>

                                    <option value="sku_market_price" {{!empty(request('price')=='sku_market_price')?'selected ':''}}>
                                        SKU市场价
                                    </option>
                                    <option value="sku_sell_price" {{!empty(request('price')=='sku_sell_price')?'selected ':''}}>
                                        SKU销售价
                                    </option>
                                </select>
                                <div class="input-group search_range">
                                    <input type="text" name="price_begin" class="form-control search_input"
                                           value="{{!empty(request('price_begin'))?request('price_begin'):''}}">
                                    <span style="float: left;margin: 0 5px;">-</span>
                                    <input type="text" name="price_end" class="form-control search_input"
                                           value="{{!empty(request('price_end'))?request('price_end'):''}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group search_range search_range_store">
                                    <label class="search_label">库存：</label>
                                    <input type="text" name="store_begin" class="form-control search_input"
                                           value="{{!empty(request('store_begin'))?request('store_begin'):''}}">
                                    <span style="float: left;margin: 0 5px;">-</span>
                                    <input type="text" name="store_end" class="form-control search_input"
                                           value="{{!empty(request('store_end'))?request('store_end'):''}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control search_select search_select_text" name="field">
                                    <option value="sku" {{!empty(request('field')=='sku')?'selected ':''}}>SKU编码
                                    </option>
                                    <option value="name" {{!empty(request('field')=='name')?'selected ':''}}>商品名称
                                    </option>
                                    <option value="goods_no" {{!empty(request('field')=='goods_no')?'selected ':''}}>
                                        商品编码
                                    </option>
                                    <option value="category" {{!empty(request('field')=='category')?'selected ':''}}>
                                        商品分类
                                    </option>
                                </select>
                                <div class="input-group search_text">
                                    <input type="text" name="value" placeholder="Search"
                                           value="{{!empty(request('value'))?request('value'):''}}"
                                           class=" form-control">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">查找</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="table-responsive">
                        @if(count($goods)>0)
                            <table class="table table-hover table-striped">
                                <tbody>
                                <!--tr-th start-->
                                <tr>
                                    <th><input type="checkbox" class="check-all"></th>
                                    <th>商品名称</th>
                                    <th>分类</th>
                                    <th>销售价</th>
                                    <th>吊牌价</th>
                                    <th>库存</th>
                                    <th>上架</th>
                                    <th>类型</th>
                                    <th>商品编码</th>
                                    <th>操作</th>
                                </tr>
                                <!--tr-th end-->
                                @foreach ($goods as $item)
                                    <tr class="goods{{$item->id}}" gid="{{$item->id}}">
                                        <td><input type="checkbox" class="checkbox" value="{{$item->id}}" name="ids[]">
                                        </td>
                                        <td>
                                            @if($storeUrl=settings('pc_store_domain_url'))
                                                <a href="{{$storeUrl.'/store/detail/'.$item->id}}" target="_blank">
                                                    <img
                                                            src="{{$item->img}}" width="50" height="50"></a> &nbsp;
                                                <a href="{{$storeUrl.'/store/detail/'.$item->id}}"
                                                   target="_blank">{{$item->name}}</a>
                                            @elseif($mobileStoreUrl=settings('mobile_domain_url'))
                                                <a href="{{$mobileStoreUrl.'/#!/store/detail/'.$item->id}}"
                                                   target="_blank">
                                                    <img
                                                            src="{{$item->img}}" width="50" height="50"></a> &nbsp;
                                                <a href="{{$mobileStoreUrl.'/#!/store/detail/'.$item->id}}"
                                                   target="_blank">{{$item->name}}</a>
                                            @else
                                                <img src="{{$item->img}}" width="50" height="50"> &nbsp;
                                                {{$item->name}}
                                            @endif
                                        </td>
                                        <td>
                                            @foreach ($item->categories as $cate)
                                                {!! $cate->name !!}<br/>
                                            @endforeach
                                        </td>
                                        <td>{{$item->sell_price}}</td>
                                        <td>{{$item->market_price}}</td>
                                        <td>{{$item->store_nums}}</td>
                                        <th>{{$item->is_del==0?"是":"否"}}</th>
                                        <th>{{$item->model->name}}</th>
                                        <td>{{$item->goods_no}}</td>
                                        <td>
                                            @if(request('view')==1)
                                                <a   class="btn btn-xs btn-primary restore-goods"
                                                     data-href="{{route('admin.goods.restore',['id'=>$item->id])}}">
                                                    <i data-toggle="tooltip" data-placement="top"
                                                       class="fa fa-refresh"
                                                       title="恢复商品"></i></a>
                                                <a class="btn btn-xs btn-danger delete-goods"
                                                   data-href="{{route('admin.goods.destroy',['id'=>$item->id])}}">
                                                    <i data-toggle="tooltip" data-placement="top"
                                                       class="fa fa-trash"
                                                       title="彻底删除"></i></a>
                                            @else
                                                <a target="_blank"
                                                   class="btn btn-xs btn-primary"
                                                   href="{{route('admin.goods.edit',['id'=>$item->id,'redirect_url'=>urlencode(Request::getRequestUri())])}}" no-pjax>
                                                    <i data-toggle="tooltip" data-placement="top"
                                                       class="fa fa-pencil-square-o"
                                                       title="编辑"></i></a>
                                                <a class="btn btn-xs btn-danger off-goods"
                                                   data-href="{{route('admin.goods.delete',['id'=>$item->id])}}">
                                                    <i data-toggle="tooltip" data-placement="top"
                                                       class="fa fa-trash"
                                                       title="删除"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="10" class="footable-visible">
                                        {!! $goods->render() !!}
                                    </td>
                                </tr>
                                </tfoot>
                                @else
                                    <div>
                                        &nbsp;&nbsp;&nbsp;当前无数据
                                    </div>
                                @endif
                            </table>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
    <div id="modal" class="modal inmodal fade"></div>
    <div id="download_modal" class="modal inmodal fade"></div>

    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/el.common.js') !!}
    @include('store-backend::commodity.includes.script')