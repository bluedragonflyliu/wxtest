<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>closure</title>
</head>
<body>
	<?php 
		$out = 'abc';
		
		function fun (){
			//$in.='def';
			echo $out; //php函数内部不能调用函数外部变量
		}
		$out = 'efg';
		fun();
		
	?>
	<script type="text/javascript">
		var out = 'abc';
		function func (){
			console.log(out); //js中函数内部可以调用函数外部的变量
			function func1 (){
				out +=1;
				alert(out);
			}
			func1();
		}
		func();

	</script>
</body>
</html>