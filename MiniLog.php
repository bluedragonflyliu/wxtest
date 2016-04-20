<?php 
class MiniLog {
	private static $_instance; //单例
	private $_path;// 日志路径
	private $_pid; //进程id
	private $_handleArr; //保存不同日志级别文件fd
	private $_user;
	/**
	*构造函数
	*@param $path 日志对象对应的日志目录
	*/
	function __construct($path) {
		$this->_path = $path;
		$this->_pid  = getmypid();
		$this->_user = get_current_user ();
 
	}

	private function __clone(){

	}

	/**
	*单例函数
	*/
	public static function instance($path = './tmp'){
		if(!(self::$_instance instanceof self)) {
			self::$_instance = new self($path);

		}
		return self::$_instance;
	}

	/**
	*根据文件名获取文件的fd
	*@param $fileName 文件名
	*@return 文件的fd 
	*/
	private function getHandle($fileName) {
		if ($this->_handleArr[$fileName]) {
			return $this->_handleArr[$fileName];
		}
		date_default_timezone_set('PRC');
		$nowTime = time();
		$logSuffix = date('Ymd', $nowTime);
		$handle = fopen($this->_path .'/'.$fileName.$logSuffix.".log",'a');
		$this->_handleArr[$fileName] = $handle;
		return $handle;
	}

	/**
	*向文件中写日志
	*@parm $fileName 文件名
	*@parm $message 消息
	*/
	public function log($fileName, $message) {
		$handle = $this->getHandle($fileName);
		//获取当前时间并进行格式化
		$nowTime = time();
		$logPreffix = date('Y-m-d H:i:d', $nowTime);
		fwrite($handle, "[$logPreffix][$this->_pid]['$this->_user']$message\n");
		return true;
	}

	/**
	*析构函数，关闭所有fd
	*/
	function __destruct(){
		foreach ($this->_handleArr as $key => $item) {
			if($item) {
				fclose($item);
			}
		}
	}
}


?>