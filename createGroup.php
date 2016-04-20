<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>doCreateGroup</title>
</head>
<body>
<?php 
if(isset($_POST['gName'])){
	$gName = $_POST['gName'];
	echo '<br />';
	require_once('./Myapi.php');
	$api = new Myapi();
	$access_token = $api->access_token;

	$url = 'https://api.weixin.qq.com/cgi-bin/groups/create?access_token='.$access_token;
	$data = '{"group":{	"name":"'.$gName.'"}}';
	$res = $api->httpsRequest($url, $data);
	var_dump($res);
}

?>
</body>
</html>