<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>坐标逆查询</title>
</head>
<body>
	<?php 
	/*	function httpRequest($url){
			$ret = array();
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			
			$output = curl_exec( $ch );
			$ret['output'] = $output;
			$ret['error']  = curl_errno($ch);
			curl_close( $ch );
			return $ret;
		}
		$url='http://api.map.baidu.com/geocoder/v2/?ak=LnLr9NYKzPXS2X8sPhgNhnRw&callback=renderReverse&location=42.550563,128.995829&output=xml&pois=1';
		$res=httpRequest($url);
		if($res['error'] === 0){
			$postStr = $res['output'];
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			//var_dump($postObj);
			var_dump($postObj->result->cityCode);
			$country = $postObj->result->addressComponent->country;
			$province = $postObj->result->addressComponent->province;
			$city = $postObj->result->addressComponent->city;
			var_dump($country.$province.$city);
		}*/


        $m        = new MongoClient();
        $db       = $m->selectDB('zhangxindb');
        $dbcollec = $db->selectCollection('location');
        $fields     = array('addr' => true, 'count'=>false);
        $criterial  = array();
        $cursor = $dbcollec->find();
        $citys = array();
        foreach($cursor as $key=>$value){
            $citys[$value['addr']]=$value['count'];
        }

        $data = json_encode($citys);
       
    	echo $data;
	?>
</body>
</html>