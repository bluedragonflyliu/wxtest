<?php
	$m 	 = new MongoClient(); // 连接
	$db  = $m->selectDB("liufuwx");
	//$dcl = $db->selectCollection('web_token');
	$dcl = $db->selectCollection('time');
	$atime = array('time'=>new MongoDate(time()));
	//$dcl->insert($atime);
	//$dcl->drop();
	$data=$dcl->findOne();
	//$res= $dcl ->insert(array('name' =>'liufu', 'age'=>'30'));
	//$data = $dcl->findOne();
	echo $data['time']->{'sec'};
?>