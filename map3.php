<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="./css/style.css" />
    <style type="text/css">
        body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
        #allmap{width:100%;height:500px;}
        p{margin-left:5px; font-size:14px;}
    </style>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=LnLr9NYKzPXS2X8sPhgNhnRw"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="./js/map/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="./js/map/Mapv.js"></script>
    <script type="text/javascript" src="./js/map/example.js"></script>
    <title>向地图添加工具</title>
</head>
<body>
    <div id="allmap"></div>
    <p></p>
    <?php
        $m        = new MongoClient();
        $db       = $m->selectDB('zhangxindb');
        $dbcollec = $db->selectCollection('location');
        $fields     = array('addr' => true, 'count'=>false);
        $criterial  = array();
        $cursor = $dbcollec->find();
        $citys = array();
        foreach($cursor as $key=>$value){
            $addr = substr($value['addr'], 6);
            $citys[$addr]=$value['count'];
        }

        $cityNames = json_encode($citys);
       
    ?>
</body>
</html>
<script type="text/javascript">

var bmap = new BMap.Map("allmap");    
    var  mapStyle ={ 
        features: ["road", "building","water","land"],//隐藏地图上的poi
        style : "dark"  //设置地图风格为高端黑
    }
    bmap.setMapStyle(mapStyle);
bmap.centerAndZoom(new BMap.Point(107.803119, 35.528658), 7); // 初始化地图,设置中心点坐标和地图级别

    // 第一步创建mapv示例
    var mapv = new Mapv({
        drawTypeControl: true,
        map: bmap  // 百度地图的map实例
    });
    var cityNames = <?php echo $cityNames?>;
    var data = [];

    var cityLoad = { //判断加载完成的城市边界数据
    }

    function getBoundary(cityname){
        var bdary = new BMap.Boundary();
        bdary.get(cityname, function(rs){ // 异步加载
            cityLoad[cityname] = true;
            var boundary = rs.boundaries[0];
            boundary = boundary.split(";");
            for (var i = 0; i < boundary.length; i++) {
                boundary[i] = boundary[i].split(",");
            }
            data.push({
                geo: boundary,
                count: cityNames[cityname]
            });
            if (isAllComplete()) {
                allLoadCallback();
            }
        });
    }

    /**
     * 是否全部加载完成
     */
    function isAllComplete() {
        for (var key in cityNames) {
            if (!cityLoad[key]) {
                return false;
            }
        }
        return true;
    }

    for (var key in cityNames) {
        getBoundary(key);
    }

    function allLoadCallback() {
        console.log(data);

        var layer = new Mapv.Layer({
            zIndex: 1,
            mapv: mapv,
            dataType: 'polygon',
            data: data,
            drawType: 'intensity',
            drawOptions: {
                //strokeStyle: 'yellow',
                //lineWidth: 1,
                max: 100,
                label: { // 显示label文字
                    show: true, // 是否显示
                    font: "11px", // 设置字号
                    minZoom: 7, // 最小显示的级别
                    fillStyle: 'rgba(255, 255, 255, 1)' // label颜色
                },
                gradient: {
                    '0.01': 'blue',
                    '0.1': 'cyan',
                    '0.2': 'lime',
                    '0.3': 'yellow',
                    '1.0': 'red'
                },
            }
        });

    }




</script>
