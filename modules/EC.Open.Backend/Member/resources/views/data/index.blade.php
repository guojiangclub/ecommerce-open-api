{!! Html::script(env("APP_URL").'/assets/backend/libs/echarts.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/china.js') !!}

<div class="ibox float-e-margins">
    <div class="ibox-content" style="display: block;">
        <div class="row">
            <div class="panel-body">

                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <div class="box-tΩols pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <div class="col-md-4">
                                    <h3 class="box-title">用户属性</h3>
                                    <div id="user_1" style="width: 400px;height:400px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <h3 class="box-title">用户购买情况</h3>
                                    <div id="user_2" style="width: 400px;height:400px;"></div>
                                </div>

                                <div class="col-md-4">
                                    <h3 class="box-title">用户会员等级</h3>
                                    <div  id="user_3" style="width: 400px;height:400px;"></div>
                                </div>

                            </div>
                            <!-- /.table-responsive -->
                        </div>

                    </div>

                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">粉丝分布</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <div id="china-map" style="width: 100%;height:600px;"></div>
                            </div>
                            <!-- /.table-responsive -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(function () {
        var myChart1 = echarts.init(document.getElementById('user_1'));

        var option_user_1 = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:['男','女','未知']
            },
            color:['#57C174','#EF4C4B','#5292E9'],
            series: [
                {
                    name:'用户性别',
                    type:'pie',
                    radius: ['25%', '35%'],
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '12',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:[
                        {value:"{{$user_man_count}}", name:'男'},
                        {value:"{{$user_woman_count}}", name:'女'},
                        {value:"{{$user_un_known_count}}", name:'未知'},

                    ]

                }
            ]
        };
        myChart1.setOption(option_user_1);

        var myChart2 = echarts.init(document.getElementById('user_2'));

        var option_user_2 = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:['购买过的用户','未购买过的用户']
            },
            color:['#57C174','#EF4C4B'],
            series: [
                {
                    name:'购买情况',
                    type:'pie',
                    radius: ['25%', '35%'],
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '12',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:[
                        {value:"{{$user_buy_count}}", name:'购买过的用户'},
                        {value:"{{$user_no_buy_count}}", name:'未购买过的用户'},
                    ]
                }
            ]
        };
        myChart2.setOption(option_user_2);


        var myChart3 = echarts.init(document.getElementById('user_3'));



        @if($group AND count($group)>0)

         var data_group=[];
         var data_user_group=[];

         @foreach($group as $key=> $item)
         data_group.push("{{$item}}");
         @endforeach

         @if(count($user_group))
              @foreach($user_group as $key=> $item)
                data_user_group.push( {value:"{{$item}}", name:"{{$key}}"});
              @endforeach
         @endif

        var option_user_3 = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:data_group
            },

            series: [
                {
                    name:'用户会员等级',
                    type:'pie',
                    radius: ['25%', '35%'],
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '12',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:data_user_group
                }
            ]
        };

        @else
        var option_user_3 = {
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data:['未分等级']
                },
                color:['#5292E9'],
                series: [
                    {
                        name:'用户会员等级',
                        type:'pie',
                        radius: ['25%', '35%'],
                        avoidLabelOverlap: false,
                        label: {
                            normal: {
                                show: false,
                                position: 'center'
                            },
                            emphasis: {
                                show: true,
                                textStyle: {
                                    fontSize: '12',
                                    fontWeight: 'bold'
                                }
                            }
                        },
                        labelLine: {
                            normal: {
                                show: false
                            }
                        },
                        data:[
                            {value:0, name:'未分等级'},

                        ]
                    }
                ]
            };

        @endif

        myChart3.setOption(option_user_3);

    });

</script>

<script>
    var myChart = echarts.init(document.getElementById('china-map'));
    var data_user_bind=[];

    @if(count($user_bind))
    @foreach($user_bind as $key=> $item)
          data_user_bind.push( {value:"{{$item}}", name:"{{$key}}"});
            @endforeach
     @endif


    var option_map = {
        title : {
            text: '粉丝数量',
            // subtext: '纯属虚构',
            x:'center'
        },
        tooltip : {//提示框组件。
            trigger: 'item'//数据项图形触发，主要在散点图，饼图等无类目轴的图表中使用。
        },
        legend: {
            orient: 'horizontal',//图例的排列方向
            x:'left',//图例的位置
            data:['粉丝数量']
        },

        visualMap: {//颜色的设置  dataRange
            x: 'left',
            y: 'center',
            // splitList: [
            //     {start: 5, end: 5, label: '5（自定义特殊颜色）', color: '#0064FF'},
            //     {start: 10, end: 200, label: '10 到 200（自定义label）'},
            //     {start: 200, end: 300},
            //     {start: 310, end: 1000},
            //     {start: 900, end: 1500},
            //     {start: 1500},
            //
            // ],
           min: 0,
           max: 5000,
            calculable : true,//颜色呈条状
            text:['高','低'],// 文本，默认为数值文本
            // color: ['#E0022B', '#E09107', '#A3E00B']
        },
        toolbox: {//工具栏
            show: true,
            orient : 'vertical',//工具栏 icon 的布局朝向
            x: 'right',
            y: 'center',
            feature : {//各工具配置项。
                mark : {show: true},
                dataView : {show: true, readOnly: false},//数据视图工具，可以展现当前图表所用的数据，编辑后可以动态更新。
                restore : {show: true},//配置项还原。
                saveAsImage : {show: true}//保存为图片。
            }
        },
        roamController: {//控制地图的上下左右放大缩小 图上没有显示
            show: true,
            x: 'right',
            mapTypeControl: {
                'china': true
            }
        },
        series : [
            {
                name: '订单量',
                type: 'map',
                mapType: 'china',
                roam: false,//是否开启鼠标缩放和平移漫游
                itemStyle:{//地图区域的多边形 图形样式
                    normal:{//是图形在默认状态下的样式
                        label:{
                            show:true,//是否显示标签
                            textStyle: {
                                color: "rgb(249, 249, 249)"
                            }
                        }
                    },
                    emphasis:{//是图形在高亮状态下的样式,比如在鼠标悬浮或者图例联动高亮时
                        label:{show:true}
                    }
                },
                top:"3%",//组件距离容器的距离
                data:data_user_bind
            }
        ]
    };
    myChart.setOption(option_map);
    // myChart.on('mouseover', function (params) {
    //     var dataIndex = params.dataIndex;
    //     console.log(params);
    // });
</script>

