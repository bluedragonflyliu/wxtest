<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>test</title>
</head>
<body>
<?php 
	
	$info = & $_POST;
	echo '<pre>';
	var_dump($info);
	var_dump($_POST);
	$info['prov'] = '加州';
	echo '<hr />';
	var_dump($info);
	var_dump($_POST);
?>	
</body>
</html>
