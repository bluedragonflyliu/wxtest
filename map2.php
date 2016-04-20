<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
        body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
        #allmap{width:100%;height:500px;}
        p{margin-left:5px; font-size:14px;}
    </style>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=LnLr9NYKzPXS2X8sPhgNhnRw"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
    <title>点聚合</title>
</head>
<body>
    <div id="allmap"></div>
    <p>缩放地图，查看点聚合效果</p>
    <?php
        $m        = new MongoClient();
        $db       = $m->selectDB('zhangxindb');
        $dbcollec = $db->selectCollection('agents');
        $fields     = array('location' => true, 'addr'=>true, '_id'=>false);
        $criterial  = array('type' =>'MERCHANT');
        $cursor = $dbcollec->find($criterial, $fields);
        $points = array();
        $i=0;
        while($cursor->hasNext()){

            $res = $cursor->getNext();
            if(empty($res['location'])){
                continue;
            }else{
                $points[] = $res;
            }
        }
        $data = json_encode($points);
        //echo $data;
    ?>
</body>
</html>
<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("allmap");
    map.centerAndZoom(new BMap.Point(116.404, 39.915), 4);
    map.enableScrollWheelZoom();

    var mapType1 = new BMap.MapTypeControl({mapTypes: [BMAP_NORMAL_MAP,BMAP_HYBRID_MAP]});
    var mapType2 = new BMap.MapTypeControl({anchor: BMAP_ANCHOR_TOP_LEFT});

    var overView = new BMap.OverviewMapControl();
    var overViewOpen = new BMap.OverviewMapControl({isOpen:true, anchor: BMAP_ANCHOR_BOTTOM_RIGHT});
    //添加地图类型和缩略图
    function add_control(){
        map.addControl(mapType1);          //2D图，卫星图
        map.addControl(mapType2);          //左上角，默认地图控件
        map.setCurrentCity("北京");        //由于有3D图，需要设置城市哦
        map.addControl(overView);          //添加默认缩略地图控件
        map.addControl(overViewOpen);      //右下角，打开
    }
    //移除地图类型和缩略图
    function delete_control(){
        map.removeControl(mapType1);   //移除2D图，卫星图
        map.removeControl(mapType2);
        map.removeControl(overView);
        map.removeControl(overViewOpen);
    }
    
    var  mapStyle ={ 
        features: ["road", "building","water","land"],//隐藏地图上的poi
        style : "dark"  //设置地图风格为高端黑
    }
    map.setMapStyle(mapStyle);
    add_control();



    var data = <?php echo $data; ?>;

    var MAX = data.length;
    var markers = [];
    var pt = null;
    var i = 0;
    
    var data_info = [];
    var opts = {
                width : 250,     // 信息窗口宽度
                height: 80,     // 信息窗口高度
                title : "店铺信息" , // 信息窗口标题
                enableMessage:true//设置允许信息窗发送短息
               };


    for(var i=0;i<MAX;i++){
        var pt = new BMap.Point(data[i].location.lng, data[i].location.lat);
        var marker = new BMap.Marker(pt);  // 创建标注
        var content = data[i]['addr'];
        markers.push(marker);
        map.addOverlay(marker);               // 将标注添加到地图中
        addClickHandler(content,marker);
    }
    function addClickHandler(content,marker){
        marker.addEventListener("click",function(e){
            openInfo(content,e)}
        );
    }
    function openInfo(content,e){
        var p = e.target;
        var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
        var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象 
        map.openInfoWindow(infoWindow,point); //开启信息窗口
    }
   
    //最简单的用法，生成一个marker数组，然后调用markerClusterer类即可。
    var markerClusterer = new BMapLib.MarkerClusterer(map, {markers:markers});
</script>
