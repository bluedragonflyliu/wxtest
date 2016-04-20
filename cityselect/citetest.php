<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
<?php 
	$b =3;
	function aa(& $a){
		$a +=5;
	}
	aa($b);
	echo $b;

	function &bb (){
		static $c=0;
		echo $c;
		return $c;
	}

	$d = &bb();
	$d =5;

	$d = bb();

?>
</body>
</html>
