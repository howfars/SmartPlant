
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
$result_tem = $conn->query('SELECT * FROM erjishuju ORDER BY dat DESC LIMIT 0,1');
$result_tem_arr = array();   //存储数组
$rows=mysqli_fetch_array($result_tem,MYSQL_ASSOC);   //$rows是数组
  if ($rows['tanju'] or $rows['baijuan'] or $rows['fugen'] or $rows['shichangduan'])
  {$mystatus=100;}
  else {$mystatus=90;}

?>







<!DOCTYPE html>
<html style="height: 100%">
<head>
    <meta charset="utf-8">
</head>
<body style="height: 100%; margin: 0">
<div id="container" style="height: 100%"></div>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-stat/ecStat.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/dataTool.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/china.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/world.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=6yCx5G3aac5VaK7FqGLxG7yiD7Bn12fO"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/bmap.min.js"></script>
<script type="text/javascript">
    var dom = document.getElementById("container");
    var myChart = echarts.init(dom);
var mystatus=90;
mystatus=<?=$mystatus?>;
    var points = [
        [116.3633220899, 39.9648887255,90],
        [116.3646408012, 39.9711423879,100],
        [116.3712283065, 39.9716028707,100],
        [116.3715618360, 39.9662045027,mystatus],
        [116.3792670599, 39.9709793607,90],
    ];
    var option = {

        bmap: {
            center: [116.3732059419, 39.9657127712],
            zoom: 16,
            roam: true,
            enableMapClick: false,
            mapStyle: {
                styleJson: [{
                    "featureType": "all",
                    "elementType": "all",
                    "stylers": {
                        "lightness": 47,
                        "saturation": -100
                    }
                }, {
                    "featureType": "highway",
                    "elementType": "geometry.fill",
                    "stylers": {
                        "color": "#ffffff"
                    }
                }, {
                    "featureType": "poi",
                    "elementType": "labels.icon",
                    "stylers": {
                        "visibility": "off"
                    }
                }, {
                    "featureType": "road",
                    "elementType": "labels",
                    "stylers": {
                        "visibility": "off"
                    }
                }]
            }
        },
        title: {
            text: "植物环境状态概览",
            left: 'center',
            top: 5,
            backgroundColor:"rgba(255,255,255,0.8)",
            textStyle:{
                color:"#2B98DC",
                fontWeight:"bold"
            }
        },
        visualMap: {
            type: 'piecewise',
            show: true,
            bottom: '10',
            left:"10",
            orient: 'vertical',
            backgroundColor:"rgba(255,255,255,0.8)",

           // seriesIndex: 0,
           // calculable: true,
           /* pieces: [{
                max: 5000,
            }, {
                max: 8000,
            },{
                max: 12000,
            },{
                max: 15000,
            }, {
                max: 20000,
            }, {
                min: 20000,

            }],*/
           pieces:[{min:100},
               {max:60},
               {max:80}],
            inRange: {
                color: ['#5AB1EF', '#D87A80']
                //color: ['green', 'red']
                //color:['lightskyblue', 'red']
            }
        },
        series: [{
            type: 'heatmap',
            coordinateSystem: 'bmap',
            data: points,
            minOpacity: 0.5,
            pointSize: 12,
            blurSize: 0
        }]

    }

    myChart.setOption(option);
    setTimeout(init, 0)


    function init() {
        initMap();
    }
    function getMap() {
        return myChart.getModel().getComponent('bmap').getBMap();
    }
    function initMap() {
        var top_left_navigation = new BMap.NavigationControl({
            type: BMAP_NAVIGATION_CONTROL_SMALL
        });
        var map = getMap();

        //map.centerAndZoom("苏州", 13);
        map.addControl(top_left_navigation);
        map.disableDoubleClickZoom();
        return map;
    }
</script>







</body>
</html>