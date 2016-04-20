<?php 
require_once('./Myapi.php');
$Myapi = new Myapi();
$accessToken = $Myapi->accessToken;
$media_id = $_POST['media_id'];
$kv = new SaeKV();
// 初始化SaeKV对象
$kv->init(); 
$kv->set('media_id',$media_id);
// 要存在你服务器哪个位置？

	$targetName = './wxpic/'.$value.'.jpg';

	$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$accessToken}&media_id={$serverId}";
	$ch  = curl_init();
	$fp  = fopen( $targetName, 'wb' );
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_FILE, $fp );
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_exec( $ch );
	curl_close( $ch );
	fclose( $fp );

	echo '0';
?>