<?php 
/**
*
*wechat basic callback
*
*/
require_once dirname(__FILE__).'/../common/GlobalDefine.php';
class weChatCallBack{
	protected $_postObject;
	protected $_fromUserName;
	protected $_toUserName;
	protected $_createTime;
	protected $_msgType;
	protected $_msgId;
	protected $_time;

	public function getToUserName() {
		return $this->_toUserName;
	}

	protected function makeHint($hint) {
		$resultStr = sprintf (HINT_TPL, $this->_fromUserName, $this->_toUserName,$this->_time, 'text', $hint);
		return $resultStr;
	}
	public function init($postObj){
		$this->_postObject = $postObj;
		if($this->_postObject == false){
			return false;
		}
		$this->_fromUserName = (string)trim($this->_postObject->_fromUserName)
	}


}



?>