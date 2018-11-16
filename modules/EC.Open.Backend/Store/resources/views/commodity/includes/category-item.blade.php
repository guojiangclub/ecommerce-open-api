<style>
    .category-wrap{margin-top:15px}
    .category-content{margin-left:15px}
    .major{width:100%;background-color:#fff;overflow:auto}
    .category-contents{background-color:#fff}
    .btn{margin-left:10px}
    .category_name{padding:15px;width:100%;background-color:#fafafa;overflow:auto}
    .category_name ul{list-style:none}
</style>

<div class="major">
    <div id="hidden-category-id">
        @if(isset($cateIds))
            @foreach($cateIds as $cate)
                <input type="hidden" name="category_id[]" id=category_{{$cate}} value="{{$cate}}">
            @endforeach
        @endif
    </div>
    <div class="row category_name">
        <ul>
            @if(isset($cateNames))
                @foreach($cateNames as $val)
                    <li class="" data-id="{{$val->id}}" data-parent="{{$val->parent_id | 0}}"><span>{{$val->name}}</span>
                        <ul ></ul>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
    <div class="category-contents" style="display:flex;">
        <div class="category-content col-md-3" data-position="left">
            @foreach($categories as $key => $val)
                <div class="category-wrap">
                    <input data-id="{{$val->id}}" data-parent="{{$val->parent_id | 0}}" data-name="{{$val->name}}" data-uniqueId="categoryIds_{{$val->id}}" class="category_checks" type="checkbox" @if(isset($cateIds)) {{in_array($val->id, $cateIds) ? 'checked' : ''}} @endif />
                    &nbsp;&nbsp;&nbsp;
                    <input class="btn btn-outline btn-primary category-btn" type="button" value="{{$val->name}}"/>
                </div>
            @endforeach
        </div>
        <div class="category-content col-md-3" data-position="middle">
            @if(!empty($categoriesLevelTwo))
                @foreach($categoriesLevelTwo as $val)
                    @foreach($val as $v)
                        <div class="category-wrap">
                            <input data-id="{{$v->id}}" data-parent="{{$v->parent_id | 0}}" data-name="{{$v->name}}" data-uniqueId="categoryIds_{{$v->id}}" class="category_checks" type="checkbox" @if(isset($cateIds)) {{in_array($v->id, $cateIds) ? 'checked' : ''}} @endif />
                            &nbsp;&nbsp;&nbsp;
                            <input class="btn btn-outline btn-primary category-btn" type="button" value="{{$v->name}}"/>
                        </div>
                    @endforeach
                @endforeach
            @endif
        </div>
        <div class="category-content col-md-3" data-position="right">

        </div>
    </div>
</div>






