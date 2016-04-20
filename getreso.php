<?php 
require_once './Myapi.php';
$Myapi = new Myapi();
$access_token = 'jBCcKzkxw5MKxjXJ3RQwiahMals2225pFWhj1KUs8gMJ46etXFuL6V845DinA5LN6ItUgTN-bxeJpu30etlTi9Qa_0U0s13gO5YekRHRIhYFJPfADAVZF';
$url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$access_token;
$res = $Myapi->httpsRequest($url);
if($res['error']==0){

}



?>