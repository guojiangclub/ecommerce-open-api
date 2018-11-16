<table class="table table-hover table-striped" id="app">
    <tbody>
    <!--tr-th start-->
    <tr>
        <th>商品名称</th>
        <th>销售价</th>
        <th>库存</th>
        <th>操作</th>
    </tr>
    <!--tr-th end-->
    @foreach($goods as $item)
        <tr>
            <td><img src="{{$item->img}}" width="30" height="30"> &nbsp; {{$item->name}}</td>
            <td>{{$item->sell_price}}</td>
            <td>{{$item->store_nums}}</td>
            <td>

                <input type="checkbox"  value="{{$item->id}}" v-model="checked" @change="one();">
                {{--<p>选中：{#checked#}</p>--}}
                {{--<button onclick="getData(this)" class="btn btn-info btn-circle select"--}}
                        {{--type="button" data-id="{{$item->id}}" data-price="{{$item->sell_price}}"   ><i class="fa fa-times"></i>--}}
                {{--</button>--}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>




<script>
    var page="{{$goods->currentPage()}}";
    var pid= $('#goods_modal').data('pid');

    if (typeof(pid) !== "undefined") {
        new Vue({
            delimiters: ['{#', '#}'],
            el:'#app',
            data:{
                checked:[],
                picked:'',
            },

            methods:{
                one:function(){
                    getDataChild[page]=this.checked;
                },
            },
            mounted:function(){
                this.checked = getDataChild[page] || [];
            }

        });

    }else{
        new Vue({
            delimiters: ['{#', '#}'],
            el:'#app',
            data:{
                checked:[],
                picked:'',
            },

            methods:{
                one:function(){
                    getData[page]=this.checked;
                },
            },
            mounted:function(){
                this.checked = getData[page] || [];
            }

        });
    }

</script>
