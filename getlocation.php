<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>通过经纬度获取用户的地理位置</title>
</head>
<body>
	<?php
        function httpRequest($url){
            $ret = array();
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch, CURLOPT_TIMEOUT_MS, 2000 );
            $output = curl_exec( $ch );
            $ret['output'] = $output;
            $ret['error']  = curl_errno($ch);
            curl_close( $ch );
            return $ret;
        }
        if( !isset($_GET['start']) ){
        	$start =0;
        } else{
        	$start =$_GET['start'];
        }

        $m        = new MongoClient();
        $db       = $m->selectDB('zhangxindb');
        $dbcollec = $db->selectCollection('agents');
        $fields     = array('location' => true, 'addr'=>true, '_id'=>false);
        $criterial  = array('type' =>'MERCHANT',);
        if (empty($_GET['total'])) {
       		$cursor = $dbcollec->find($criterial, $fields);
        	$total = $cursor->count();
       	} else {
       		$total = $_GET['total'];
       	}
        $cursor = $dbcollec->find($criterial, $fields)->skip($start*10)->limit(10);
        $total = $cursor->count();
        //$value = $dbcollec->findOne($criterial, $fields);
       
        $points = array();
        $dbcollec = $db->selectCollection('location');
  		
       	set_time_limit(120);// 通过set_time_limit(0)可以让程序无限制的执行下去
		$interval=1;// 每隔1秒运行
		foreach ($cursor as $key => $value) {
			if(empty($value['location'])) {
                continue;
            }
            $location =implode(',', $value['location']);
            //http://api.map.baidu.com/geocoder/v2/?ak=E4805d16520de693a3fe707cdc962045&callback=renderReverse&location=39.983424,116.322987&output=xml&pois=1 
            $url='http://api.map.baidu.com/geocoder/v2/?ak=LnLr9NYKzPXS2X8sPhgNhnRw&callback=renderReverse&output=xml&pois=1&location='.$location;
            $res=httpRequest($url);
            if($res['error'] === 0){
                $postStr = $res['output'];
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                
                $cityCode = $postObj->result->cityCode;
                $country = $postObj->result->addressComponent->country;
                $province = $postObj->result->addressComponent->province;
                $city = $postObj->result->addressComponent->city;
                $addr=$country.$province.$city;
               	
                $fields = array('count' => true);
                $criterial = array('cityCode'=>$cityCode);
                $info = $dbcollec ->findOne($criterial, $fields);

                if(!empty($info)){
                	$count = $info['count'];
                    $criterial = array('_id' =>$info['_id']);
                    $data = array('addr'=>$addr, 'cityCode'=>$cityCode, 'count'=>$count+1);
                    $dbcollec->update($criterial, $data);
                }else{
                    $data = array('addr'=>$addr, 'cityCode'=>$cityCode, 'count'=>1);
                    $dbcollec->insert($data);
                }   
            }
		   	sleep($interval);
		   	echo $key.$addr.'----'.$value['addr'].'<br>';
		}
		echo $start.'<br>';
		$start +=1;
		if(ceil($total/10)>$start) {
			echo '<script type="text/javascript">window.location.href="./getlocation.php?total='.$total.'&start='.$start.'"</script>';
		}
    ?>
</body>
</html>