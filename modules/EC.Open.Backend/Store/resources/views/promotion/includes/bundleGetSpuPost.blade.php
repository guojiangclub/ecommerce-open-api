                    <table class="table table-hover table-striped">
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
                                    <button onclick="addItem(this)" class="btn btn-info btn-circle select"
                                            type="button" data-id="{{$item->id}}" data-price="{{$item->sell_price}}"><i class="fa fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>