<?php

	//获取access_token
	require_once('./Myapi.php');
	$Myapi = new Myapi();
	$access_token = $Myapi->access_token;

	//my_json_decode() 将数组转成json
	function my_json_encode($p, $type="text") {
  		 if (PHP_VERSION >= '5.4') {
     			   $str = json_encode($p, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
   		 } else {
   		    	 switch ($type)
       		    	 {
          			  case 'text':
              			  	isset($p['text']['content']) && ($p['text']['content'] = urlencode($p['text']['content']));
              			 	 break;
       		  	  }
        	 	  $str = urldecode(json_encode($p));
  		  }
 		   return $str;
	}

	//通过扫描二维码添加用户进入指定的组， 能数为一个组的ID, 和这个关注用户的openid
	function adduser( $openid, $groupid=0, $subscribe=true) {
		//使用全局的access_token
		global $access_token;
		//如果参数subscribe=true就移到分组，否则只在本数地加个用户
		if($subscribe) {					
			//接口是移动组的接口， 如果关注时，用指定组的能数，直接将用户分到指定的组中
			$url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token={$access_token}";
			//参数post json 
			$jsonstr = '{"openid":"'.$openid.'","to_groupid":'.$groupid.'}';
			$result = $Myapi->httpsRequest($url, $jsonstr);
		}
		
		//通过自己写的一个函数getUserInfo()获取用户的详细信息
		$user = getUserInfo($openid);
		$user['groupid'] = $groupid;

		$m 	   = new MongoClient(); // 连接
		$db    = $m->selectDB("liufuwx");
		$dbcol = $db->selectCollection('user');

		//如果已经是关注过又取消的，则已经有记录了， 有记录了更新关注字段、组和时间即可
		$criteria = array('openid' =>$openid);
		$res = $dbcol->findOne($criteria);
		//如果根据openid在表中查到有记录，就不要再插入数据
		if($res ) {
			$criteria = $res['_id'];
			$data = array('subscribe'=>'1', 'groupid'=>$groupid, 'subscribe_time'=>$user['subscribe_time']);
			$dbcol->update($criteria, array('$set'=>$data));
		}else{
			//第一次关注时加一条记录
			$data = array(
				'openid' 		 => $user['openid'],
				'groupid'		 => $user['groupid'],
				'subscribe'		 => $user['subscribe'],
				'nickname'		 =>	$user['nickname'],
				'sex'			 => $user['sex'],
				'city'			 =>	$user['city'],
				'country'		 => $user['country'],
				'province'		 => $user['province'],
				'headimgurl'	 => $user['headimgurl'],
				'subscribe_time' => $user['subscribe_time']
				);
			$dbcol->insert($data);
		}
	}


	//获取用户的信息， 参数是openid， 通过openid访问获取用户的接口获取用户的全部信息。
	function getUserInfo($openid) {		
		//$access_token=get_token();
		global $access_token;
		$user = getUserInfo($openid);
		$user['groupid'] = $groupid;

		$m 	 = new MongoClient(); // 连接
		$db  = $m->selectDB("liufuwx");
		$dbcol = $db->selectCollection('user');
			$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
			$result = $Myapi->httpsRequest($url);
	
			$user = json_decode($result, true);
		
			return $user;
	}


	//通过指定openid取销用户表的关注
	function deluser($openid) {
		$m 	   = new MongoClient(); // 连接
		$db    = $m->selectDB("liufuwx");
		$dbcol = $db->selectCollection('user');
		$data  = array('subscribe' =>'0');
		$criteria = array('openid' => $openid);
		$dbcol-> update($criteria, array('$set' => $data));
	}


	//通过指定openid取销用户表的关注
	function modgroup($openid, $groupid) {
		$m 	   = new MongoClient(); // 连接
		$db    = $m->selectDB("liufuwx");
		$dbcol = $db->selectCollection('user');
		$data  = array('groupid' =>$groupid);
		$criteria = array('openid' => $openid);
		$dbcol->update($criteria, array('$set' => $data));
	}
