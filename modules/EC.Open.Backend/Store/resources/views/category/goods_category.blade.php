@extends ('backend.layouts.block')

@section('content')
    <div class="pop_win">

        <table class="form_table" style="margin-top: 0px;">
            <colgroup>
                <col width="85px" />
                <col />
            </colgroup>
            <th>所属分类：</th>
            <td id="categoryBox"></td>
        </table>

        <!--分类列表-->
        <script id="categoryListTemplate" type="text/html">
            <ul class="select">
                <%for(var item in templateData){%>
                <%item = templateData[item]%>
                <li onmouseover="showCategory(<%=item['id']%>,<%=level%>);"><label><input name="categoryVal" type="{{ $type }}" value="<%=item['id']%>" onchange="selectCategory(this);" <%if( jQuery().inArray(item['id'],checkedCategory) != -1 ){%>checked="checked"<%}%> /><%=item['name']%></label></li>
                <%}%>
            </ul>
        </script>
    </div>

@stop

@section('after-scripts-end')
    <script type="text/javascript">
        //完整分类数据

art.dialog.data('categoryWhole', <?php echo json_encode($categoryData, JSON_UNESCAPED_UNICODE); ?> );

categoryParentData = <?php echo json_encode($categoryParent, JSON_UNESCAPED_UNICODE); ?>;

        //初始化被选中的分类ID
        checkedCategory = art.dialog.data('categoryValue') ? art.dialog.data('categoryValue') : [];

        $(function()
        {
            //生成顶级分类信息
            var templateHtml = template.render('categoryListTemplate',{'templateData':categoryParentData[0],'level':0,'checkedCategory':checkedCategory});
            $('#categoryBox').append(templateHtml);
        })

        //显示分类数据信息
        function showCategory(categoryId,level)
        {
            $('ul.select:gt('+level+')').remove();
            var childCategory = categoryParentData[categoryId];
            if(childCategory)
            {
                var templateHtml = template.render('categoryListTemplate',{'templateData':childCategory,'level':level+1,'checkedCategory':checkedCategory});
                $('#categoryBox').append(templateHtml);
            }
        }

        //选择规格数据
        function selectCategory(_self)
        {
            var categoryId = $(_self).val();
            var valueIndex  = jQuery.inArray(categoryId,checkedCategory);

            if($(_self).attr('checked'))
            {
                (valueIndex == -1) ? checkedCategory.push(categoryId) : "";
            }
            else
            {
                (valueIndex == -1) ? "" : checkedCategory.splice(valueIndex,1);
            }
            //更新分类数据值
            <?php if($type == 'checkbox'){ ?>

            art.dialog.data('categoryValue',checkedCategory);
            <?php }else { ?>
            var result = checkedCategory.pop();
            art.dialog.data('categoryValue',Array(result));

            <?php } ?>

            }
    </script>
@stop
