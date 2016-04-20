<?php 
$m 	 = new MongoClient();
$myDB = $m->selectDB('liufufs'); 
$res=$myDB->drop();
var_dump($res);
$myDB = $m->selectDB('liufuwx'); 
$res=$myDB->drop();
echo '<hr />';
var_dump($res);
?>