<?php
	//页面输出中文 
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
	//包含连接数据库的代码
	//include "./conn.inc.php";
	require_once './Myapi.php';
	//创建上传类的对象
	require_once './upload.php';
	$upfile = new upload();
	$Myapi  = new Myapi();

	$m 	    = new MongoClient(); // 连接
	$db     = $m->selectDB("liufuwx");
	$dbcol  = $db->selectCollection('media');
	//如果用户提交上传
	if(isset($_POST['dosubmit'])) {

		//设置上传的类型
		//$up->set("allowtype", array('jpg','jpeg', 'mp3', 'mp4','amr'));
		if (!empty($_FILES['res'])){
			$file = $_FILES['res'];		
		} else {
			$file = null;
		}
		
		//开始上传
		if(!empty($file)) {
			//获取上传后的名子
			$info = $upfile->fileUp($file);
			$upfile->createPic($info['id']);
			//获取access_token
			$access_token = $Myapi->access_token;
			//$access_token='p3ARMw7gkpaTXhARmg-r-ujknYE-RVWGgry6MibSis0_k-7SnksThJ6fTDVVCtXQ4daOSIkDk2WT1_gnVT_P8zfs3yNS-R76rLXoa4iGp_MEHBdAFAADX';
			//通过这个接口上传到公众号上
			$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type={$_POST['rtype']}";

			//上传公众号一定要使用绝对路径
			$filepath = dirname(__FILE__)."/uploads/".$info['filename'];
			//形成上传的数据
			$filedata = array("media"=>"@".$filepath);
			//调用公众号接口，上传
			$result = $Myapi->httpsRequest($url, $filedata);
			if ($result['output']) {
				$data = json_decode($result['output'], true);
			}

			if ($_POST['rtype']=="thumb") {
				//如上传的是缩略图，处理一下
				$data['media_id']=$data['thumb_media_id'];
			}
			if ($result['output']) {
			$mediaData  = array('filename'=>$info['filename'], 'rtype'=>$data['type'],'file_id' =>$info['id'],'media_id'=>$data['media_id'], 'created_at'=>new MongoDate($data['created_at']));
			//将入到本地数据库
			$dbcol->insert($mediaData);
			}
		}
	}

	//根据用户选择设置从数据库查询条件，如果没有选择则返回全部信息
/*	if(!empty($_GET['type'])) {
		$criteria = array('rtype' =>$_GET['type']);	
		$cursor = $dbcol->find($criteria)->sort(array('created_at'=>1));
	} else {
		$cursor = $dbcol->find()->sort(array('created_at'=>1));
	}
	
	$media = iterator_to_array($cursor);
	echo '<h1>媒体列表:</h1>';
	echo '<p><a href="up.php?type=image">图片</a> | ';
	echo '<a href="up.php?type=voice">语言</a> | ';
	echo '<a href="up.php?type=video">视频</a> | ';
	echo '<a href="up.php?type=thumb">缩略图</a> |';
	echo '<a href="up.php?type=news">图文</a></p>';
	echo '<table border="1" width="80%">';
	echo '<tr>';
	echo '<th>ID</th> <th>文件名</th> <th>类型</th> <th>media_id</th> <th>上传时间</th>';
	echo '</tr>';
	//形成表格
	while($media) {
		echo '<tr>';
		echo '<td>'.$media['id'].'</td>';
		echo '<td>';
		if($media['rtype']=="image" || $media['rtype']=="thumb") {
			echo '<img height="100" src="./uploads/'.$media['filename'].'">';	
		}else{
			echo $media['filename'];
		}
		echo '</td>';
		echo '<td>'.$media['rtype'].'</td>';
		echo '<td>'.$media['media_id'].'</td>';
		echo '<td>'.date("Y-m-d", $media['created_at']).'</td>';
		echo '</tr>';	
	}
	echo '</table>'; */

?>
<br>上传的多媒体文件有格式和大小限制，如下： <br>
<p>
图片（image）: 1M，支持JPG格式  <br>
语音（voice）：2M，播放长度不超过60s，支持AMR\MP3格式 <br> 
视频（video）：10MB，支持MP4格式 <br>
缩略图（thumb）：64KB，支持JPG格式 <br>
</p>
<?php if(empty($_GET['type'])){
	$_GET['type'] = 'image';
	}
	?>
<form action="./up.php?type=<?php echo $_GET['type']; ?>" method="post" enctype="multipart/form-data">
	上传文件： 
	<input type="file" name="res">
	<select name="rtype">
		<option value="image">图片</option>
		<option value="voice">语音</option>
		<option value="video">视频</option>
		<option value="thumb">缩略图</option>
	</select>
	<input type="submit" name="dosubmit" value="上传">
</form>

<a target="_blank" href="upnews.php?num=2">添加图文</a>
