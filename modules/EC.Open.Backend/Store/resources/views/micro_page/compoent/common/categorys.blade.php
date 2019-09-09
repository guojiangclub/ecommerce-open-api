
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<div class="col-md-12" style="height:300px;">
    <div class="form-group" style="margin-top: 50px;">
        <label for="exampleInputEmail1" class="col-sm-2 control-label"> 分类名称:</label>
        <div class="col-sm-10">
            <select class="form-control category" name="category">
                <option value=0>请选择分类</option>

                @if(count($categorys))

                    @foreach($categorys as $item)

                         <option  value="{{$item->id}}" >{{$item->html}}{{$item->name}}</option>

                    @endforeach

                @endif

            </select>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        var  obj=$('.category').select2();
        obj.val("{{request('category_id')}}").trigger("change");
    })
</script>


