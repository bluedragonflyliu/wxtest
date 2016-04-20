<?php 
	//header('Content-Type:xlsx/text');
	date_default_timezone_set("PRC");
	require_once './classes/PHPExcel.php';
	$limit		= $_GET['limit'];
	$skip 		= $_GET['skip'];
	$sortfield  = $_GET['sortfield'];
	$order 		= (Integer)$_GET['order'];
	switch ($sortfield) {
		case 0:
			$sort = array('name'=>$order);
			break;
		case 1:
			$sort = array('MERCHANT.show_name'=>$order);
			break;
	}
	$objPHPExcel = new PHPExcel(); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	//创建人  
	//$objPHPExcel->getProperties()->setCreator("MaartenBalliauw");  
	//最后修改人  
	//$objPHPExcel->getProperties()->setLastModifiedBy("MaartenBalliauw");  
	//标题  
	$objPHPExcel->getProperties()->setTitle("Office2007 XLSX Test Document");  
	//题目  
	$objPHPExcel->getProperties()->setSubject("Office2007 XLSX Test Document");  
	//描述  
	$objPHPExcel->getProperties()->setDescription("Testdocument for Office 2007 XLSX, generated using PHPclasses.");  
	//关键字  
	$objPHPExcel->getProperties()->setKeywords("office2007 openxml php");  
	//种类  
	$objPHPExcel->getProperties()->setCategory("Testresult file");  

	//设置当前的sheet  
	$objPHPExcel->setActiveSheetIndex(0);  
	  
	//设置sheet的name  
	$objPHPExcel->getActiveSheet()->setTitle('Simple');  

	$m = new MongoClient();
	$db = $m->selectDB('zhangxindb');
	$collec = $db->selectCollection('agents');
	$criteria = array('enable'=>true,'type'=>'MERCHANT');
	$fields =array('name'=> true, 'MERCHANT.bonus_points'=> true, 'MERCHANT.show_name' =>true, 'phone'=>true, 'email'=>true, 'addr'=>true);
	
	$cursor=$collec->find($criteria,$fields)->sort($sort)->skip($skip)->limit($limit);
	$count =10;
	$i =2;
		
		$objPHPExcel->getActiveSheet()->setCellValue('A'. 1, '名字');
		$objPHPExcel->getActiveSheet()->setCellValue('B'. 1, '地区');
		$objPHPExcel->getActiveSheet()->setCellValue('C'. 1, '店铺');
		$objPHPExcel->getActiveSheet()->setCellValue('D'. 1, '手机号');
		$objPHPExcel->getActiveSheet()->setCellValue('E'. 1, '邮箱');
		$objPHPExcel->getActiveSheet()->setCellValue('F'. 1, '薪币');

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8); //设置行宽
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(64); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(32); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(24); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14); 
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true); //加粗
		$objPHPExcel->getActiveSheet()->getStyle('A:F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
	foreach ($cursor as $key => $value) {
		/*echo '<pre>';
		var_dump($value);*/
		
		$objPHPExcel->getActiveSheet()->setCellValue('A'. $i, $value['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'. $i, $value['addr']);  
		$objPHPExcel->getActiveSheet()->setCellValue('C'. $i, $value['MERCHANT']['show_name']);  
		$objPHPExcel->getActiveSheet()->setCellValue('D'. $i, $value['phone']); 
		$objPHPExcel->getActiveSheet()->setCellValue('E'. $i, $value['email']);
		$objPHPExcel->getActiveSheet()->setCellValue('F'. $i, $value['MERCHANT']['bonus_points']);  
		//$objPHPExcel->getActiveSheet()->setCellValue('F'. $i, date('Y/m/d H:i:s',$value['create_ts']->{'sec'}));
		$i++; 	  
	}
	//$objWriter->save('PHPExcel.xlsx');
	$outputFileName ='PHPExcel.xlsx';
	header ( 'Pragma:public');
	header ( 'Expires:0');
	header ( 'Cache-Control:must-revalidate,post-check=0,pre-check=0');
	header ( 'Content-Type:application/force-download');
	header ( 'Content-Type:application/vnd.ms-excel');
	header ( 'Content-Type:application/octet-stream');
	header ( 'Content-Type:application/download');
	header ( 'Content-Disposition:attachment;filename='. $outputFileName );
	header ( 'Content-Transfer-Encoding:binary');
	$objWriter->save ( 'php://output');
?>