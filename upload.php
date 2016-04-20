<?php
    class upload{
        private $collection = null;
        public function __construct() {
            $conn = new MongoClient(); 
            $db   = $conn->selectDB('liufufs');  // 选择数据库
            $this->collection = $db->getGridFS('media'); // 选择集合，相等于选择数据表
        }
        public function fileUp($file) {
            // 上传图片
            if (isset($file)) {
                // 保存新上传的文件
                $size   = $file['size'];
                $md5    = md5_file($file['tmp_name']);
                $exists = $this->collection->findOne(array('md5' => $md5,'length' => $size), array('md5'));
                if (empty($exists)) {
                    //$this->collection->storeUpload('upfile');
                    // 或修改为如下代码，并存入一些自定义参数 
                    $filename    = date('Ymd',time()).$file['name'];
                    $filetype    = $file['type'];
                    $tmpfilepath = $file['tmp_name'];
                    $id = $this->collection->storeFile($tmpfilepath, array('filename' => $filename, 'filetype' => $filetype));   
                } else {
                    unlink($file['tmp_name']);
                }
            }
            $ret = array('filename'=>$filename, 'id'=>$id);
     		return $ret;
        }
    
        public function reviewPic($id) {
            if (empty($id)) {
               exit('缺少参数图片的id');
            }
            // 索引图片文件
            $image = $this->collection->findOne(array('_id' => new MongoID($id)));
            // 设定文档类型，显示图片
            $img_bytes = $image->getBytes();
            include_once 'thumb.php';

            if (empty($_GET['w'])){
                $w = 100;
            } else {
                $w = intval($_GET['w']);
            }

            Thumb::maxWidth($img_bytes, $w);
        
        }

        public function createPic($id) {
            if (empty($id)) {
               exit('缺少参数图片的id');
            }
            // 索引图片文件
            $image = $this->collection->findOne(array('_id' => $id));
            $imageinfo = $image->{'file'};
            $imageName = $imageinfo['filename'];
            // 设定文档类型，显示图片
            $img_bytes = $image->getBytes();
            include_once 'thumb.php';

            if (empty($_GET['w'])){
                $w = 200;
            } else {
                $w = intval($_GET['w']);
            }

            Thumb::maxWidth($img_bytes, $w, $imageName);
            return $imageName;        
        }

        public function removePic($filename) {
            if (empty($filename)) {
               exit('缺少参数图片的名字');
            }
            unlink('./uploads/'.$filename);
            return 1;        
        }

        public function delPic($id){
            if (empty($id)) {
               exit('缺少参数图片的id');
            } 
            $s = $this->collection->remove(array('_id' => new MongoID($id)));
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }

        /*public function getList(){
            $cursor = $collection->find();
            foreach ($cursor as $obj) {
                return '<p><a href="?img=' . $obj->file['id'] . '&w=800"><img src="?img=' . $obj->file['id'] . '" border="0" /></a><a href="?del=' . $obj->file['id'] . '">删除</a></p>';
            }
        }*/
    }
?>