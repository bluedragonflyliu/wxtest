<?php
$m 	= new MongoClient(); // 连接
$db = $m->selectDB("liufuwx");
$dcl = $db->selectCollection('web_token');