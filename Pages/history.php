<?php
    function connectDB(){
            $conn = new mysqli('localhost','root','password');
            if (!$conn) {
                die("failed");
            }
            $conn->query("SET NAMES UTF8");
$conn->select_db('StationTest') or die("connot find");
return $conn;
}

function reverse($array){
$size = count($array);

for($i=0;$i<=floor($size/2);$i++){
$b = $array[$size-$i-1];
$array[$size-$i-1] = $array[$i];
$array[$i] = $b;
}
return $array;

}
    $conn = connectDB();

//取之前1小时温度
$result_tem = $conn->query('SELECT Temperature FROM weathertest ORDER BY id DESC LIMIT 0,8');
$result_tem_arr = array();   //存储数组
while($rows=mysqli_fetch_array($result_tem)){      //$rows是数组
settype($rows['Temperature'],'float');          //string变成float
array_push($result_tem_arr,$rows['Temperature']);
//$result_tem_arr=$rows['Temperature'] ;      //提取赋值
}
//echo $result_tem_arr;
$result_tem_arr = reverse($result_tem_arr);
$json_tem = json_encode($result_tem_arr);
//转化为json格式

$result_hum = $conn->query('SELECT Humidity FROM weathertest ORDER BY id DESC LIMIT 0,8');
$result_hum_arr = array();   //存储数组
while($rows=mysqli_fetch_array($result_hum)){      //$rows是数组
    settype($rows['Humidity'],'float');          //string变成float
    array_push($result_hum_arr,$rows['Humidity']);
//$result_tem_arr=$rows['Temperature'] ;      //提取赋值
}
//echo $result_tem_arr;
$result_hum_arr = reverse($result_hum_arr);
$json_hum = json_encode($result_hum_arr);

$result_li = $conn->query('SELECT Light FROM weathertest ORDER BY id DESC LIMIT 0,8');
$result_li_arr = array();   //存储数组
while($rows=mysqli_fetch_array($result_li)){      //$rows是数组
    settype($rows['Light'],'float');          //string变成float
    array_push($result_li_arr,$rows['Light']);
//$result_tem_arr=$rows['Temperature'] ;      //提取赋值
}
//echo $result_tem_arr;
$result_li_arr = reverse($result_li_arr);
$json_li = json_encode($result_li_arr);

$result_moi = $conn->query('SELECT Moisture FROM weathertest ORDER BY id DESC LIMIT 0,8');
$result_moi_arr = array();   //存储数组
while($rows=mysqli_fetch_array($result_moi)){      //$rows是数组
    settype($rows['Moisture'],'float');          //string变成float
    array_push($result_moi_arr,$rows['Moisture']);
//$result_tem_arr=$rows['Temperature'] ;      //提取赋值
}
//echo $result_tem_arr;
$result_moi_arr = reverse($result_moi_arr);
$json_moi = json_encode($result_moi_arr);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>24小时历史数据</title>
</head>
<body>
<div id="mychart_tem" style="width:1000px;height:400px;margin:0 auto"></div>
<div id="mychart_hum" style="width:1000px;height:400px;margin:0 auto"></div>
<div id="mychart_li" style="width:1000px;height:400px;margin:0 auto"></div>
<div id="mychart_moi" style="width:1000px;height:400px;margin:0 auto"></div>

<script src="echarts_package/echarts.js"></script>
<script type="text/javascript">
    var myChart_tem = echarts.init(document.getElementById('mychart_tem'));
    var myChart_hum = echarts.init(document.getElementById('mychart_hum'));
    var myChart_li = echarts.init(document.getElementById('mychart_li'));
    var myChart_moi = echarts.init(document.getElementById('mychart_moi'));
    option_tem = {
        title: {
            text: '                        温度',
            textStyle:{        //标题文字格式
                fontSize: 20,
                fontWeight: 'bolder',
                color: 'black'
            }
        },
        grid:{
            show:true,
            y:'3%',

            borderWidth:0
            //backgroundColor:'rgba(255,255,255,0.15)'
        },
        tooltip: {                    //在移动过程中显示数据
            trigger: 'item'
        },
        legend: {               //图例
            show:true,
            data:['气温']
        },
        dataZoom:{
            show:false
            //      type:'inside'
        },
        toolbox: {
            show: true,
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                dataView: {readOnly: false},
                magicType: {type: ['line', 'bar']},
                restore: {},
                saveAsImage: {}
            }
        },
        xAxis:  {   //横坐标  时间,读取系统时间
            show:true,
            type: 'category',
            axisLabel:{      //坐标轴
                textStyle:{
                    color:'black'
                }
            },
            axisLine:{
                lineStyle:{
                    color:'black'
                }
            },
            boundaryGap: false,
            splitLine: {
                show: false
            },
            data:['1小时前','50分钟前','40分钟前','30分钟前','20分钟前','10分钟前','现在']
        },

        yAxis: {
            show:true,
            type: 'value',
            boundaryGap:true,
            scale: true,
            boundaryGap:[0.01,0.01],
            axisLabel: {
                formatter: '{value} °C'     //纵坐标
            },
            splitLine: {
                show: false
            }

        },
        series: [
            {
                name:'温度',
                type:'line',
                label:{
                    normal:{
                        textStyle:{
                            fontSize:28
                        }
                    }
                },
                formatter:'{line}°C',
                itemStyle : {
                    normal: {
                        label : {
                            show: true,     //每个点显示数据
                            position: 'top'
                        },
                        color : '#0066FF'    //修改颜色
                    }
                },
                data:<?=$json_tem?>

            }
        ]
    };
    option_hum = {
        title: {
            text: '                       湿度',
            textStyle:{        //标题文字格式
                fontSize: 20,
                fontWeight: 'bolder',
                color: 'black'
            }
        },
        grid:{
            show:true,
            y:'3%',

            borderWidth:0
            //backgroundColor:'rgba(255,255,255,0.15)'
        },
        tooltip: {                    //在移动过程中显示数据
            trigger: 'item'
        },
        legend: {               //图例
            show:true,
            data:['气温']
        },
        dataZoom:{
            show:false
            //      type:'inside'
        },
        toolbox: {
            show: true,
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                dataView: {readOnly: false},
                magicType: {type: ['line', 'bar']},
                restore: {},
                saveAsImage: {}
            }
        },
        xAxis:  {   //横坐标  时间,读取系统时间
            show:true,
            type: 'category',
            axisLabel:{      //坐标轴
                textStyle:{
                    color:'black'
                }
            },
            axisLine:{
                lineStyle:{
                    color:'black'
                }
            },
            boundaryGap: false,
            splitLine: {
                show: false
            },
            data:['1小时前','50分钟前','40分钟前','30分钟前','20分钟前','10分钟前','现在']
        },

        yAxis: {
            show:true,
            type: 'value',
            boundaryGap:true,
            scale: true,
            boundaryGap:[0.01,0.01],
            axisLabel: {
                formatter: '{value}%'     //纵坐标
            },
            splitLine: {
                show: false
            }

        },
        series: [
            {
                name:'湿度',
                type:'line',
                label:{
                    normal:{
                        textStyle:{
                            fontSize:28
                        }
                    }
                },
                formatter:'{line}%',
                itemStyle : {
                    normal: {
                        label : {
                            show: true,     //每个点显示数据
                            position: 'top'
                        },
                        color : '#0066FF'    //修改颜色
                    }
                },
                data:<?=$json_hum?>

            }
        ]
    };
    option_li = {
        title: {
            text: '                        光照强度',
            textStyle:{        //标题文字格式
                fontSize: 20,
                fontWeight: 'bolder',
                color: 'black'
            }
        },
        grid:{
            show:true,
            y:'3%',

            borderWidth:0
            //backgroundColor:'rgba(255,255,255,0.15)'
        },
        tooltip: {                    //在移动过程中显示数据
            trigger: 'item'
        },
        legend: {               //图例
            show:true,
            data:['气温']
        },
        dataZoom:{
            show:false
            //      type:'inside'
        },
        toolbox: {
            show: true,
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                dataView: {readOnly: false},
                magicType: {type: ['line', 'bar']},
                restore: {},
                saveAsImage: {}
            }
        },
        xAxis:  {   //横坐标  时间,读取系统时间
            show:true,
            type: 'category',
            axisLabel:{      //坐标轴
                textStyle:{
                    color:'black'
                }
            },
            axisLine:{
                lineStyle:{
                    color:'black'
                }
            },
            boundaryGap: false,
            splitLine: {
                show: false
            },
            data:['1小时前','50分钟前','40分钟前','30分钟前','20分钟前','10分钟前','现在']
        },

        yAxis: {
            show:true,
            type: 'value',
            boundaryGap:true,
            scale: true,
            boundaryGap:[0.01,0.01],
            axisLabel: {
                formatter: '{value} '     //纵坐标
            },
            splitLine: {
                show: false
            }

        },
        series: [
            {
                name:'光照强度',
                type:'line',
                label:{
                    normal:{
                        textStyle:{
                            fontSize:28
                        }
                    }
                },
                formatter:'{line}°C',
                itemStyle : {
                    normal: {
                        label : {
                            show: true,     //每个点显示数据
                            position: 'top'
                        },
                        color : '#0066FF'    //修改颜色
                    }
                },
                data:<?=$json_li?>

            }
        ]
    };
    option_moi = {
        title: {
            text: '                        土壤湿度',
            textStyle:{        //标题文字格式
                fontSize: 20,
                fontWeight: 'bolder',
                color: 'black'
            }
        },
        grid:{
            show:true,
            y:'3%',

            borderWidth:0
            //backgroundColor:'rgba(255,255,255,0.15)'
        },
        tooltip: {                    //在移动过程中显示数据
            trigger: 'item'
        },
        legend: {               //图例
            show:true,
            data:['土壤湿度']
        },
        dataZoom:{
            show:false
            //      type:'inside'
        },
        toolbox: {
            show: true,
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                dataView: {readOnly: false},
                magicType: {type: ['line', 'bar']},
                restore: {},
                saveAsImage: {}
            }
        },
        xAxis:  {   //横坐标  时间,读取系统时间
            show:true,
            type: 'category',
            axisLabel:{      //坐标轴
                textStyle:{
                    color:'black'
                }
            },
            axisLine:{
                lineStyle:{
                    color:'black'
                }
            },
            boundaryGap: false,
            splitLine: {
                show: false
            },
            data:['1小时前','50分钟前','40分钟前','30分钟前','20分钟前','10分钟前','现在']
        },

        yAxis: {
            show:true,
            type: 'value',
            boundaryGap:true,
            scale: true,
            boundaryGap:[0.01,0.01],
            axisLabel: {
                formatter: '{value} %'     //纵坐标
            },
            splitLine: {
                show: false
            }

        },
        series: [
            {
                name:'土壤湿度',
                type:'line',
                label:{
                    normal:{
                        textStyle:{
                            fontSize:28
                        }
                    }
                },
                formatter:'{line}',
                itemStyle : {
                    normal: {
                        label : {
                            show: true,     //每个点显示数据
                            position: 'top'
                        },
                        color : '#0066FF'    //修改颜色
                    }
                },
                data:<?=$json_moi?>

            }
        ]
    };


    setInterval(function () {
        myChart_tem.setOption(
            option_tem
        );
        myChart_hum.setOption(option_hum);
        myChart_li.setOption(option_li);
        myChart_moi.setOption(option_moi);
    }, 5000);

</script>
</body>
</html>