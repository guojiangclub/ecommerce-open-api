<script>
    /**
     * Created by Administrator on 2018/7/11.
     */
    $(document).ready(function () {
        //7天下单趋势折现图开始
        var myChart1 = echarts.init(document.getElementById("main-seven"));
        var option = {
            tooltip: {
                /*show:false*/
                trigger: 'axis',
                formatter: function (params, ticket, callback) {
                    // var htmlStr = '<div>';
                    var htmlStr = '';
                    var color = params.color;

                    for (var i = 0; i < params.length; i++) {
                        var param = params[i];
                        var xName = param.name;//x轴的名称
                        var seriesName = param.seriesName;//图例名称
                        var value = param.value;//y轴值
                        var color = param.color;//图例颜色

                        if (i === 0) {
                            htmlStr += xName + '<br/>';//x轴的名称
                        }
                        htmlStr += '<div>';
                        //为了保证和原来的效果一样，这里自己实现了一个点的效果
                        htmlStr += '<span style="margin-right:5px;display:inline-block;width:6px;height:6px;border-radius:3px;background-color:' + color + ';"></span>';

                        //圆点后面显示的文本
                        htmlStr += value + '笔';

                        htmlStr += '</div>';
                    }
                    return htmlStr;


                },
                axisPointer: {
                    show: true,
                    type: 'line'
                }
            },
            xAxis: {
                type: 'category',
                data: [{!! $sevenDays !!}],
                axisLine: {
                    show: false
                },
                axisTick: {
                    show: false
                }
            },
            yAxis: {
                type: 'value',
                /*boundaryGap: ['20%', '20%']*/
                axisLine: {
                    show: false
                },
                axisTick: {
                    show: false,
                    /*lineStyle:{
                     color:'#E9E9E9'
                     }*/
                },
                splitLine: {
                    lineStyle: {
                        color: '#F3F3F3'
                    }
                }
            },
            series: [{
                type: 'line',
                data: [{!! $sevenDaysCount !!}],
                symbol: 'rect',
                symbolSize: 4
            }
            ],
            textStyle: {
                fontSize: 12,
                color: '#4A4A4A'
            }
            ,
            lineStyle: {
                color: '#2BC0BE'
            }
            ,
            color: '#2BC0BE'
            /*parallelAxis:{

             }*/
        };
        //使用刚刚指定的配置项和数据显示图表
        myChart1.setOption(option);
        //让图表响应式布局
        $(window).resize(function () {
            myChart1.resize();
        });
        //7天下单趋势折现图结束

        //交易汇总柱状图开始
        var myChart2 = echarts.init(document.getElementById("main-money"));

        var option2 = {
            tooltip: {
                /*show:false*/
                trigger: 'item',
                formatter: function (params) {
                    var htmlStr = '<div>';
                    var color = params.color;
                    htmlStr += params.name + '<br/>';//x轴的名称
                    //为了保证和原来的效果一样，这里自己实现了一个点的效果
                    htmlStr += '<span style="margin-right:5px;margin-bottom:2px;display:inline-block;width:6px;height:6px;background-color:' + color + ';"></span>';

                    //添加一个汉字，这里你可以格式你的数字或者自定义文本内容
                    htmlStr += params.value + '万';

                    htmlStr += '</div>';

                    return htmlStr;

                },
                axisPointer: {
                    show: true,
                    type: 'shadow'
                }
            },
            xAxis: {
                data: [],
                axisLine: {
                    lineStyle: {
                        color: '#9B9B9B'
                    }
                }
            },
            yAxis: {
                name: '金额 (万)',
                nameTextStyle: {
                    color: '#9B9B9B',
                    fontSize: 12,
                    lineHight: 17
                },
                nameGap: 20,
                /*max:3500,*/
                splitLine: {
                    lineStyle: {
                        color: '#F3F3F3'
                    }
                },
                splitNumber: 7,
                axisLine: {
                    lineStyle: {
                        color: '#9B9B9B'
                    }
                }
            },
            series: [
                {
                    type: 'bar',
                    data: [],
                    barWidth: '50%',
                    symbol: 'rect',
                    /*itemStyle:{
                     emphasis:{
                     shadowBlur: 10,
                     shadowOffsetX: 0,
                     shadowColor: 'rgba(0, 0, 0, 0.5)'
                     }
                     }*/
                }

            ],
            color: '#2BC0BE',
            textStyle: {
                fontSize: 12,
                color: '#4A4A4A'
            }
        };
        var option3 = {
            tooltip: {
                /*show:false*/
                trigger: 'item',
                formatter: function (params) {
                    var htmlStr = '<div>';
                    var color = params.color;
                    htmlStr += params.name + '<br/>';//x轴的名称
                    //为了保证和原来的效果一样，这里自己实现了一个点的效果
                    htmlStr += '<span style="margin-right:5px;margin-bottom:2px;display:inline-block;width:6px;height:6px;background-color:' + color + ';"></span>';

                    //添加一个汉字，这里你可以格式你的数字或者自定义文本内容
                    htmlStr += params.value + '万';

                    htmlStr += '</div>';

                    return htmlStr;

                }
            },
            xAxis: {
                data: [],
                axisLine: {
                    lineStyle: {
                        color: '#9B9B9B'
                    }
                },
                axisLabel: {
                    rotate: -90
                }
            },
            yAxis: {
                name: '金额 (万)',
                nameTextStyle: {
                    color: '#9B9B9B',
                    fontSize: 12,
                    lineHight: 17
                },
                nameGap: 20,
                /*max: 3500,*/
                splitLine: {
                    lineStyle: {
                        color: '#F3F3F3'
                    }
                },
                splitNumber: 7,
                axisLine: {
                    lineStyle: {
                        color: '#9B9B9B'
                    }
                }
            },
            series: [
                {
                    type: 'bar',
                    data: [],
                    barWidth: '50%'
                }

            ],
            color: '#2BC0BE',
            textStyle: {
                fontSize: 12,
                color: '#4A4A4A'
            }
        };
        myChart2.setOption(option2);
        //让图表响应式布局，改变图表尺寸，在容器大小发生改变时需要手动调用
        $(window).resize(function () {
            myChart2.resize();
        });
        //点击日按钮切换数据
        $('#Tday').click(function () {
            myChart2.clear();//清空当前实例，会移除实例中所有的组件和图表
            myChart2.setOption(option3);
            $('#Tday').addClass('active');
            $('#Tmonth').removeClass('active');
        });
        //让图表响应式布局，改变图表尺寸，在容器大小发生改变时需要手动调用
        $(window).resize(function () {
            myChart2.resize();
        });
        $('#Tmonth').click(function () {
            myChart2.clear();//清空当前实例，会移除实例中所有的组件和图表
            myChart2.setOption(option2);
            $('#Tmonth').addClass('active');
            $('#Tday').removeClass('active')
        });
        //改变图表尺寸，在容器大小发生改变时需要手动调用
        $(window).resize(function () {
            myChart2.resize();
        });
        //交易汇总柱状图结束

        //用户增长数折线图开始
        var myChart3 = echarts.init(document.getElementById('main-growth'));
        var option4 = {
            tooltip: {
                /*show:false*/
                trigger: 'axis',
                formatter: function (params, ticket, callback) {
                    // var htmlStr = '<div>';
                    var htmlStr = '';
                    var color = params.color;

                    for (var i = 0; i < params.length; i++) {
                        var param = params[i];
                        var xName = param.name;//x轴的名称
                        var seriesName = param.seriesName;//图例名称
                        var value = param.value;//y轴值
                        var color = param.color;//图例颜色

                        if (i === 0) {
                            htmlStr += xName + '<br/>';//x轴的名称
                        }
                        htmlStr += '<div>';
                        //为了保证和原来的效果一样，这里自己实现了一个点的效果
                        htmlStr += '<span style="margin-right:5px;display:inline-block;width:6px;height:6px;border-radius:3px;background-color:' + color + ';"></span>';

                        //圆点后面显示的文本
                        htmlStr += value + '人';

                        htmlStr += '</div>';
                    }
                    return htmlStr;


                },
                axisPointer: {
                    show: true,
                    type: 'line'
                }
            },
            xAxis: {
                data: [],
                axisLine: {
                    lineStyle: {
                        color: '#9B9B9B'
                    }
                }
            },
            yAxis: {
                name: '人',
                nameTextStyle: {
                    padding: [0, 60, 0, 0]
                },
                /*max:1400,*/
                splitNumber: 7,
                splitLine: {
                    lineStyle: {
                        color: '#F3F3F3'
                    }
                },
                axisLine: {
                    lineStyle: {
                        color: '#9B9B9B'
                    }
                }

            },
            series: [{
                type: 'line',
                data: [],
                symbol: 'circle',
                symbolSize: 6
            }],
            color: '#F5A623',
            textStyle: {
                fontSize: 12,
                color: '#4A4A4A'
            }
        };
        var option5 = {
            tooltip: {
                /*show:false*/
                trigger: 'axis',
                formatter: function (params, ticket, callback) {
                    // var htmlStr = '<div>';
                    var htmlStr = '';
                    var color = params.color;

                    for (var i = 0; i < params.length; i++) {
                        var param = params[i];
                        var xName = param.name;//x轴的名称
                        var seriesName = param.seriesName;//图例名称
                        var value = param.value;//y轴值
                        var color = param.color;//图例颜色

                        if (i === 0) {
                            htmlStr += xName + '<br/>';//x轴的名称
                        }
                        htmlStr += '<div>';
                        //为了保证和原来的效果一样，这里自己实现了一个点的效果
                        htmlStr += '<span style="margin-right:5px;display:inline-block;width:6px;height:6px;border-radius:3px;background-color:' + color + ';"></span>';

                        //圆点后面显示的文本
                        htmlStr += value + '人';

                        htmlStr += '</div>';
                    }
                    return htmlStr;


                },
                axisPointer: {
                    show: true,
                    type: 'line'
                }
            },
            xAxis: {
                type: 'category',
                data: [],
                axisLine: {
                    lineStyle: {
                        color: '#9B9B9B'
                    }
                }
            },
            yAxis: {
                type: 'value',
                name: '人',
                nameTextStyle: {
                    padding: [0, 60, 0, 0]
                },
                /*max:1400,*/
                splitNumber: 7,
                splitLine: {
                    lineStyle: {
                        color: '#F3F3F3'
                    }
                },
                axisLine: {
                    lineStyle: {
                        color: '#9B9B9B'
                    }
                },
            },
            series: [{
                type: 'line',
                data: [1100, 800, 1000, 600, 1350, 400, 400, 100, 1500, 380, 700, 1200],
                smooth: true,
                // showSymbol:false
                symbol: 'circle',
                symbolSize: 6
            }],
            color: '#F5A623',
            textStyle: {
                fontSize: 12,
                color: '#4A4A4A'
            }
        };
        myChart3.setOption(option4);
        $(window).resize(function () {
            myChart3.resize();
        });
        //点击日按钮
        $('#Gday').click(function () {
            myChart3.clear();//清空当前实例，会移除实例中所有的组件和图表
            myChart3.setOption(option5);
            $('#Gday').addClass('active');
            $('#Gmonth').removeClass('active')
        });
        //改变图表尺寸，在容器大小发生改变时需要手动调用
        $(window).resize(function () {
            myChart3.resize();
        });
        // $('#main-growth').children('div').css('display','block');
        //点击月按钮
        $('#Gmonth').click(function () {
            myChart3.clear();//清空当前实例，会移除实例中所有的组件和图表
            myChart3.setOption(option4);
            $('#Gmonth').addClass('active');
            $('#Gday').removeClass('active')
        });
        //改变图表尺寸，在容器大小发生改变时需要手动调用
        $(window).resize(function () {
            myChart3.resize();
        });
        // mouseover 事件
        $('.help-side').mouseenter(function () {
            $(this).css({
                'width': '150px',
                'transition': 'all .5s'
            });
            /* $(this).addClass('wid-h');*/
        });

        // mouseout 事件
        $('.help-side').mouseleave(function () {
            $(this).css({
                'width': '50px'
            });
            /* $(this).removeClass('wid-h');*/
        });
        //点击事件
        $('.help-side').click(function () {
            if ($('.help-box').hasClass('eshow')) {
                // mouseover 事件
                $('.help-side').mouseenter(function () {
                    $(this).css({
                        'width': '150px',
                        'transition': 'all .5s'
                    });
                    /* $(this).addClass('wid-h');*/
                });

                // mouseout 事件
                $('.help-side').mouseleave(function () {
                    $(this).css({
                        'width': '50px'
                    });
                    /* $(this).removeClass('wid-h');*/
                });
                $('.help-box').addClass('ehide');
                $('.help-box').removeClass('eshow');
                // $('.help-side').addClass('wid-h').removeClass('wid-r');
                $('.help-box').animate({
                    height: 'hide'
                });
                $(this).animate({
                    width: '50px'
                })

            } else {
                $('.help-side').off('mouseenter').off('mouseleave');
                $('.help-box').addClass('eshow');
                $('.help-box').removeClass('ehide');
                $(this).animate({
                    width: '240px',
                    transition: ' all 0.5s'
                });
                setTimeout(
                        function () {
                            $('.help-box').animate({
                                height: 'show'
                            });
                        }, 800
                )

            }
        });
        $('.new-side').mouseenter(function () {
            $(this).css({
                'width': '150px',
                'transition': 'all .5s'
            });
        });
        $('.new-side').mouseleave(function () {
            $(this).css({
                'width': '50px'
            });
        });

        $.get('{{route('admin.store.dashboard.getMonthData')}}', function (result) {
            if (result.status) {

                option2.xAxis.data = result.data.monthList;
                option2.series[0].data = result.data.monthTotal;
                myChart2.setOption(option2);

                option3.xAxis.data = result.data.dayList;
                option3.series[0].data = result.data.daysTotal;

                option4.xAxis.data = result.data.monthList;
                option4.series[0].data = result.data.monthUserTotal;
                myChart3.setOption(option4);

                option5.xAxis.data = result.data.dayList;
                option5.series[0].data = result.data.daysUserTotal;


            }
        })

    });

</script>