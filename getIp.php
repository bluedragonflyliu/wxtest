<?php 
function getIp()
{
	if (isset($_SERVER)) {
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
			$realip = $_SERVER["HTTP_CLIENT_IP"];
		} else {
			$realip = $_SERVER["REMOTE_ADDR"];
		}
	} else {
		if (getenv("HTTP_X_FORWARDED_FOR")) {
			$realip = getenv("HTTP_X_FORWARDED_FOR");
		} else if (getenv("HTTP_CLIENT_IP")) {
			$realip = getenv("HTTP_CLIENT_IP");
		} else {
			$realip = getenv("REMOTE_ADDR");
		}
	}
	return $realip; 
}
/**
*@desc 封装curl的调用接口， get的请求的方式
*/
function curlGetRquest($url, $data, $timeout =5 ){
	if($url == "" || $timeout <=0){
		return false;
	}
	$url = $url. '?' . http_build_query($data);
	echo $url;
	die();
	$con = curl_init((string)$url);
	curl_setopt($con, CURLOPT_HEADER, false);
	curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);
	return curl_exec($con);
} 
$url = 'http://www.baidu.com';
$data = array('a'=>'aa','b'=>'bb');
curlGetRquest($url, $data, $timeout =5 );
?>