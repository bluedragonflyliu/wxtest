<?php 
$kv = new SaeKV();
$kv->init(); 
$result=$kv->get('result');
$error=$kv->get('error');

echo 'result='.$result.'<br />';
echo 'error='.$error;
?>