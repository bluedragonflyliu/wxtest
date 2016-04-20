<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
		body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
		#allmap{width:100%;height:524px;}
		#r-result{width:100%;margin-top:5px;}
		p{margin:5px; font-size:14px;}
	</style>
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=LnLr9NYKzPXS2X8sPhgNhnRw"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>

	<title>添加/删除地图类型、缩略图控件</title>
</head>
<body>
	<?php 
		function getIp()
		{
			if (isset($_SERVER)) {
				if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
					$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
				} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
					$realip = $_SERVER["HTTP_CLIENT_IP"];
				} else {
					$realip = $_SERVER["REMOTE_ADDR"];
				}
			} else {
				if (getenv("HTTP_X_FORWARDED_FOR")) {
					$realip = getenv("HTTP_X_FORWARDED_FOR");
				} else if (getenv("HTTP_CLIENT_IP")) {
					$realip = getenv("HTTP_CLIENT_IP");
				} else {
					$realip = getenv("REMOTE_ADDR");
				}
			}
			return $realip; 
		}
		//$ip = getIp();
		$ip = '220.181.77.192';
		$data = array('ak'=>'951ca576563b8b9506ab8551cbd50973','ip'=>$ip, 'coor'=>'bd09ll');
		$dataStr=http_build_query($data);
		$url = 'http://api.map.baidu.com/location/ip?'.$dataStr;

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
		$res   = curl_exec($ch);
		$info  = json_decode($res,true);
		
		$point = $info['content']['point'];
		//var_dump($point);

	?>
	<div id="allmap"></div>
	<div id="r-result">
		<input type="button" onclick="add_control();" value="添加" />
		<input type="button" onclick="delete_control();" value="删除" />
	</div>
	<p>点击地图类型控件切换普通地图、卫星图、三维图、混合图（卫星图+路网），右下角是缩略图，点击按钮查看效果</p>
</body>
</html>
<script type="text/javascript">
	// 百度地图API功能
	var map = new BMap.Map("allmap");
	var x = <?php echo $point['x'];?>;
	var y = <?php echo $point['y'];?>;
	console.log(x);
	console.log(x);
	map.centerAndZoom(new BMap.Point(parseFloat(x), parseFloat(y)), 14);
	var point = new BMap.Point(parseFloat(x),parseFloat(y));
	var marker = new BMap.Marker(point);  // 创建标注
	map.addOverlay(marker);              // 将标注添加到地图中
	marker.enableDragging(); //标记点可拖动
	//marker.disableDragging(); //标记点不可拖动
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
	var myIcon = new BMap.Icon("./images/myicon1.png", new BMap.Size(60,90));
	map.addEventListener("click",function(e){
		map.clearOverlays();
		var point = new BMap.Point(e.point.lng,e.point.lat);
		var marker = new BMap.Marker(point,{icon:myIcon});  // 创建标注
		marker.enableDragging();
		map.addOverlay(marker);           // 将标注添加到地图中
		marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画

		marker.addEventListener("move",function(e){
			console.log(e.point.lng, e.point.lat);
		});
		map.centerAndZoom(new BMap.Point(e.point.lng, e.point.lat), 14);
	});

</script>
