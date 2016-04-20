<?php 
header('content-type:text/html;charset=utf-8');
function getIp() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	   return $_SERVER['HTTP_CLIENT_IP'];
	} else if (!empty($_SERVER['HTTP_X_FORVARDED_FOR'])) {
	   return $_SERVER['HTTP_X_FORVARDED_FOR'];
	} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
	   return $_SERVER['REMOTE_ADDR'];
	} else {
	   return "未知IP";
	}
}
echo getIp();
?>