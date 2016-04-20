<?php 
	$m 	 = new MongoClient(); // 连接
	/*$dbs = $m->listDBs();
	echo '<pre>';
	print_r($dbs);
	echo '<hr/>';
	$db  = $m->selectDB("liufuwx");
	$mediaColl = $db->selectCollection('media'); 
	$cur = $mediaColl->find();
	
    foreach ($cur as $p) {
    	var_dump($p);
	}
	echo '<hr />';
	$db2 = $m->selectDB('liufufs');
	$mediaColl2 = $db2->getGridFS('media');
	$image = $mediaColl2->find();
	foreach ($image as $p) {
    	var_dump($p);
	}

	$dcl = $db->selectCollection('web_token');
	var_dump($dcl->findOne());
	echo '</pre>';*/

	$db = $m->selectDB('androboxdb');

	$collections = $db->listCollections();

	foreach ($collections as $collection) {
    	$colle = strstr((String)$collection,'sev_logs');
    	
    	if($colle){
    		echo $colle.'<br/>';
    		$res = $db-> selectCollection($colle)->drop();

    		if($res['ok']==1){
    			echo 'drop '.$colle.' is ok!<br />';
    		}
    	}

	}
?>