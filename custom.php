<?php
require_once './Myapi.php';
$Myapi = new Myapi();
//获取客服信息
$access_token = '37YneslW3fEZjRrqFBV1bqb5hIWiyUNZRfdZf9E7JpI_XljcTuX0QZepR9iKQeuPbiYKH7KcTYRwaSgCQogzUP7uXLdtq3zVG1gwkQ31pKkFKWdABAUZL';
/*
$url = "https://api.weixin.qq.com/customservice/msgrecord/getrecord?access_token=$access_token";
$res = $Myapi -> httpsRequest($url); 
var_dump($res);
if($res['error']==0){
	var_dump($res['output']);
}*/

//$url = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token='.$access_token;
$url = 'https://api.weixin.qq.com/customservice/kfaccount/add?access_token='.$access_token;
$data = ' {
    "kf_account" : "test1@test",
    "nickname" : "客服1",
    "password" : "96e79218965eb72c92a549dd5a330112"
 }';
$res = $Myapi -> httpsRequest($url,$data); 
if ($res['error']===0){
	var_dump($res['output']);	
}
?>