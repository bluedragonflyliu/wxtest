<?php 
require_once('./Myapi.php');
$m 	    = new MongoClient(); // 连接
$db     = $m->selectDB("liufuwx");
$dbcol  = $db->selectCollection('media');

$res = $dbcol->findOne();
$Myapi = new Myapi();
$access_token = $Myapi->access_token;
//$access_token='p3ARMw7gkpaTXhARmg-r-ujknYE-RVWGgry6MibSis0_k-7SnksThJ6fTDVVCtXQ4daOSIkDk2WT1_gnVT_P8zfs3yNS-R76rLXoa4iGp_MEHBdAFAADX';
$url  = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$res['media_id'];
$info = $Myapi->httpRequest($url);

if ($info['error']==0){
	header('Content-type:image/jpeg');
	echo ($info['output']);
}
?>