<?php 
$kv = new SaeKV();
// 初始化SaeKV对象
$kv->init();
$token=$kv->get('token');
$signature=$kv->get('signature');
$tmpStr=$kv->get('tmpStr');
$echoStr=$kv->get('echoStr');
$bool=$kv->get('bool');
$reply=$kv->get('reply');
$ticket=$kv->get('jsapi_ticket');
$access_token = $kv->get('access_token');
$media_id = $kv->get('media_id');
/*echo $token.'<hr/>';
echo $signature.'<hr/>';
echo $tmpStr.'<hr/>';
echo '<h1>'.$echoStr.'</h1><hr/>';
echo $bool.'<hr/>';
echo $reply.'<hr/>';*/

$mem = new Memcache;
$mem->connect();

//保存数据
$key = $mem->get('key');
var_dump($media_id);
/*var_dump($key);
echo '<hr />'.$ticket.'<hr>';
echo '<hr />'.$access_token.'<hr>';*/

?>