<?php 
require_once('./Myapi.php');
$Myapi = new Myapi();
$access_token = $Myapi->access_token;

$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
$data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}';
$res = $Myapi->httpsRequest($url, $data);
$ticketStr = json_decode($res['output'],true);
if(!empty($ticketStr['ticket'])){
	$codeUrl = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticketStr['ticket'];
	$qrcode = $Myapi->httpsRequest($codeUrl);
	header('Content-type:image/jpeg');
	
	echo ($qrcode['output']);
}else{
	echo '获取失败！';
}
?>