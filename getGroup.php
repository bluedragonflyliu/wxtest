<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>获得组</title>
</head>
<body>
<?php 

	require_once('./Myapi.php');
	$api = new Myapi();
	$access_token = $api->access_token;

	$url = 'https://api.weixin.qq.com/cgi-bin/groups/get?access_token='.$access_token;
	$res = $api->httpsRequest($url);
	var_dump($res);


?>
</body>
</html>