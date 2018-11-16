<li class="{{ Active::pattern('admin/image*') }} ">
    <a href="#"><i class="iconfont icon-tupianguanli"></i>
        <span class="nav-label">图片管理</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level collapse">
        <li class="{{ Active::pattern('admin/image/file*') }}"><a
                    href="{{route('admin.image.index',['category_id'=>1])}}">图片管理</a></li>

        <li class="{{ Active::pattern('admin/image/category*') }}"><a
                href="{{route('admin.image-category.index')}}">图片分类管理</a></li>


    </ul>
</li>