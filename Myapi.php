<?php 
class Myapi
{
	private $corpId = "wxa02e8b27847a7e96";
	private $Secret = 'd4624c36b6795d1d99dcf0547af5443d';
	private $dcl 	= null;
	public  $access_token = null;

	public function __construct()
	{
		$m 	 = new MongoClient(); // 连接
		$db  = $m->selectDB("liufuwx");
		$this->dcl = $db->selectCollection('web_token');
		$this->GetToken();
	}
	public function httpsRequest( $url, $data=null )
	{	
		$ret = array();
		$ch  = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		if(!empty( $data ))
		{	
			curl_setopt( $ch, CURLOPT_POST,1 );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		}

		$output = curl_exec( $ch );
		$ret['output'] = $output;
		$ret['error']  = curl_errno($ch);
		curl_close( $ch );
		return $ret;
	}

	public function httpRequest( $url, $data=null )
	{	
		$ret = array();
		$ch  = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		if(!empty( $data ))
		{	
			curl_setopt( $ch, CURLOPT_POST,1 );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		}

		$output = curl_exec( $ch );
		$ret['output'] = $output;
		$ret['error']  = curl_errno($ch);
		curl_close( $ch );
		return $ret;
	}
	private function GetToken()
	{
		
		$dbRes = $this->dcl->findOne();
		if (empty($dbRes)){
			$begin_time = 0;
		} else {
			$_id = $dbRes['_id'];
			$begin_time = $dbRes['begin_time']->{'sec'};
		}
		if (empty($this->access_token) || (time()+30-$begin_time) > 7200) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->corpId . "&secret=" .$this->Secret;
			$res = $this->httpRequest($url);
			if ($res['error']) 
			{ 
			    print "Error: " . $res['error']; 
			} else { 
			    // Show me the result 
				$result 	  = json_decode($res['output'], true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
				$begin_time	  =	new MongoDate(time());
				$expires	  = $result['expires_in'];
				$this->access_token = $result['access_token'];
				if (empty($_id)) {
					$this->dcl->insert(array('begin_time'=> $begin_time, 'access_token'=>$this->access_token));
				} else {
					$criteria = array('_id' => $_id);
					$data = array('begin_time'=>$begin_time, 'access_token'=>$this->access_token);
					$this->dcl->update($criteria, array('$set'=>$data));
				}
				
				
			} 
			
		} else {
			$this->access_token = $dbRes['access_token'];
			//echo ($this->access_token).'<br />';
		}
	}

}
	
?>