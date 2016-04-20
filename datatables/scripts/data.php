<?php
 $m   =  new MongoClient();
 $db  = $m->selectDB('zhangxindb');
 $dcl = $db->selectCollection('agents');

//获取Datatables发送的参数 必要
$draw = $_GET['draw'];//这个值作者会直接返回给前台

//排序
$order_column = $_GET['order']['0']['column'];//那一列排序，从0开始
$order_dir = $_GET['order']['0']['dir'];//ase desc 升序或者降序
if ($order_dir = 'ase') {
    $order_dir = 1;
} else {
    $order_dir = -1;
}
//拼接排序sql
$order = "";
//echo $order_column;
//echo $order_dir;
if(isset($order_column)){
    $i = intval($order_column);
    switch($i){
        case 'name':
            $order = array('name'=>$order_dir);
        break;
        case 'show_name':
            $order = array('MERCHANT.show_name'=>$order_dir);
        break;
        case 'addr':
            $order = array('addr'=>$order_dir);
        break;
        case 'phone':
            $order = array('phone'=>$order_dir);
        break;
        case 'email':
            $order = array('email'=>$order_dir);
        break;
        case 'bonus_points':
            $order = array('MERCHANT.bonus_points'=>$order_dir);
        break;
        default:
            $order = '';
    }
}
//搜索
$search = $_GET['search']['value'];//获取前台传过来的过滤条件

//分页
$skip = (int)$_GET['start'];//从多少开始
$limit = (int)$_GET['length'];//数据长度

//条件过滤后记录数 必要
$recordsFiltered = 0;
$criteral = array('type'=>'MERCHANT'); 
$fields = array('name'=>true, 'MERCHANT.show_name'=>true, 'addr'=>true, 'phone'=>true, 'email'=>true, 'MERCHANT.bonus_points'=>true,'_id' => true);
//表的总记录数 必要

$recordsTotal = $dcl->find($criteral,$fields)->count();
//定义过滤条件查询过滤后的记录数sql
$where = array();
if(strlen($search)>0){
    $criteral = array('type'=>'MERCHANT','$or'=>array(array('name' => new MongoRegex("/$search/i")),array('MERCHANT.show_name'=>new MongoRegex("/$search/")),array('phone'=>new MongoRegex("/$search/")),array('email'=>new MongoRegex("/$search/"))));
    $recordsFilteredResult = $dcl->find($criteral,$fields)->count();

}else{
    $recordsFiltered = $recordsTotal;
}

$infos = array();
//如果有搜索条件，按条件过滤找出记录
$dataResult = $dcl->find($criteral,$fields)->sort($order)->skip($skip)->limit($limit);

foreach ($dataResult as $value) {
 $obj = array(
                'name'          => $value['name'],
                'show_name'     => $value['MERCHANT']['show_name'],
                'addr'          => $value['addr'],
                'phone'         => $value['phone'],
                'email'         => $value['email'],
                'bonus_points'  => $value['MERCHANT']['bonus_points'],
                'id'            => $value['_id']->{'$id'},
               );
    array_push($infos,$obj);
    
    //var_dump($infos);
}

echo json_encode(array(
    "draw" => intval($draw),
    "recordsTotal" => intval($recordsTotal),
    "recordsFiltered" => intval($recordsFiltered),
    "data" => $infos
));
