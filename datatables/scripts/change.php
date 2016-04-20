<?php 
$arr=explode('|', $_POST['id']);
$value = $_POST['value'];
if($arr[0]=='show_name'){
	$fields = array('$set'=>array('MERCHANT.'.$arr[0]=>$value));
}else{
	$fields = array('$set'=>array($arr[0]=>$value));
}

$old_value = $arr[2];
$id = new MongoId($arr[1]);
$criteria = array('_id'=>$id);
 $m   =  new MongoClient();
 $db  = $m->selectDB('zhangxindb');
 $dcl = $db->selectCollection('agents');

 $res = $dcl->update($criteria, $fields);
 if($res['ok']==1){
 	echo $value;
 }else{
 	echo $old_value;
 }
?>