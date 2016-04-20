<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content='width=device-width,init-scale=1'>
  <title></title>
   <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
  <link rel="stylesheet" href="./css/style.css">
</head>
<body ontouchstart="">
<?php
require_once "jssdk.php";
$CorpID = 'wxa02e8b27847a7e96';
$Secret = 'd4624c36b6795d1d99dcf0547af5443d';
$jssdk  = new JSSDK($CorpID, $Secret);
$signPackage = $jssdk->GetSignPackage();

?>
<div class="wxapi_container">

    <div class="lbox_close wxapi_form">
      <h3 id="menu-basic">基础接口</h3>
      <span class="desc">判断当前客户端是否支持指定JS接口</span>
      <button class="btn btn_primary" id="checkJsApi">checkJsApi</button>


      <h3 id="menu-image">图像接口</h3>
      <span class="desc">拍照或从手机相册中选图接口</span>
      <button class="btn btn_primary" id="chooseImage">chooseImage</button>
      <span class="desc">预览图片接口</span>
      <button class="btn btn_primary" id="previewImage">previewImage</button>
      <span class="desc">上传图片接口</span>
      <button class="btn btn_primary" id="uploadImage">uploadImage</button>
      <span class="desc">下载图片接口</span>
      <button class="btn btn_primary" id="downloadImage">downloadImage</button>


      <h3 id="menu-location">地理位置接口</h3>
      <span class="desc">使用微信内置地图查看位置接口</span>
      <button class="btn btn_primary" id="openLocation">openLocation</button>
      <span class="desc">获取地理位置接口</span>
      <button class="btn btn_primary" id="getLocation">getLocation</button>
      <h3 id="menu-scan">微信扫一扫</h3>
      <span class="desc">调起微信扫一扫接口</span>
      <button class="btn btn_primary" id="scanQRCode0">scanQRCode(微信处理结果)</button>
      <button class="btn btn_primary" id="scanQRCode1">scanQRCode(直接返回结果)</button>

    </div>
  </div>
<script src="./js/jweixin-1.0.0.js"></script>
<script src="./js/jquery-1.8.3.min.js"></script>


</body>

<script>

  wx.config({
    debug: true,
    appId: "<?php echo $signPackage['appId'];?>",
    timestamp: "<?php echo $signPackage['timestamp'];?>",
    nonceStr: "<?php echo $signPackage['nonceStr'];?>",
    signature: "<?php echo $signPackage['signature'];?>",
    jsApiList: [
        'checkJsApi',
       
        'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage',
        'getNetworkType',
        'openLocation',
        'getLocation',
        'hideOptionMenu',
        'showOptionMenu',
        'closeWindow',
        'scanQRCode',
        'chooseWXPay',
        'openProductSpecificView',
        'addCard',
        'chooseCard',
        'openCard'
      ]

  });
wx.ready(function () {
  $('#openLocation').click(function(){
    wx.openLocation({
    latitude: 39.912833, // 纬度，浮点数，范围为90 ~ -90 
    longitude: 116.404024, // 经度，浮点数，范围为180 ~ -180。
    name: '天安门广场', // 位置名
    address: '北京市天安门广场', // 地址详情说明
    scale: 12, // 地图缩放级别,整形值,范围从1~28。默认为最大
    infoUrl: 'www.baidu.com' // 在查看位置界面底部显示的超链接,可点击跳转
    });
  });


  $('#getLocation').click(function(){
    wx.getLocation({
        type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
        success: function (res) {
          var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
          var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
          var speed = res.speed; // 速度，以米/每秒计
          var accuracy = res.accuracy; // 位置精度
          alert('当前纬度:'+latitude+'\r\n当前经度:'+longitude+'\r\n当前速度:'+speed+'\r\n位置精度:'+accuracy);

        }
    });
  }); 
 
  var images = {
    localId: [],
    serverId: []
  }
  $('#chooseImage').click(function(){
 
    wx.chooseImage({
      success: function (res) {
        images.localId = res.localIds;
        alert('已选择 ' + res.localIds.length + ' 张图片');
      }
     });
  });

  $('#uploadImage').click(function(){
 if (images.localId.length == 0) {
      alert('请先使用 chooseImage 接口选择图片');
      return;
    }
    var i = 0, length = images.localId.length;
    images.serverId = [];
    function upload() {
      wx.uploadImage({
        localId: images.localId[i],
        success: function (res) {
          i++;
          //alert('已上传：' + i + '/' + length);
          images.serverId.push(res.serverId);
              $.ajax({
                    url:'http://ipalm.sinaapp.com/upload.php',
                    data:{media_id:res.serverId},
                    type:'POST',
                    success:function(data){
                      if(data=='0'){
                        alert('上传成功！');
                      }
                      
                    },
                  });
          if (i < length) {
            upload();
          }
        },
        fail: function (res) {
          alert(JSON.stringify(res));
        }
      });
    }
    upload();
  });
});
 
</script>
</html>
