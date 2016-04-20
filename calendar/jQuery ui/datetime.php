<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery UI 日期选择器（Datepicker） - 默认功能</title>
  
  <script src="./js/jquery-2.0.3.min.js"></script>
  <script src="./js/jquery-ui.min.js"></script>
  <!-- <script src="./js/bootstrap-datepicker.js"></script> -->
  <!-- <link rel="stylesheet" href="./style.css"> -->
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/bootstrap-timepicker.css">
  <link rel="stylesheet" href="./css/jquery-ui.min.css">
  <link rel="stylesheet" href="./css/font-awesome.min.css">
  <style>
	#ui-datepicker-div{
		top:30px;
	}

  </style>
  <script>
  $(function() {
    $( "#datepicker" ).datepicker({
    	numberOfMonths: 1,//显示月份个数
      	showButtonPanel: true,
      	showOtherMonths: true,
      	selectOtherMonths: true,
      	changeMonth: true,
      	changeYear: true,
      	showAnim:'fadeIn',//显示动画效果 "show"(默认)"slideDown"滑下"fadeIn"淡入"blind" 百叶窗特效"bounce"(UI 反弹特效)"clip"(UI 剪辑特效)"drop"(UI 降落特效)"fold"(UI 折叠特效)"slide"滑动特效)
      	dateFormat:'yy-mm-dd',//时间显示格式
      });
  	$( "#from" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat:'yy-mm-dd',
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#to" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat:'yy-mm-dd',
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });

  });
  </script>
</head>
<body>
 
	<div class="col-sm-6">
		<h3 class="header blue lighter smaller">
			<i class="icon-calendar-empty smaller-90"></i>
			Datepicker
		</h3>

		<div class="row">
			<div class="col-xs-6">
				<div class="input-group input-group-sm">
					<input type="text" id="datepicker" class="form-control" />
					<span class="input-group-addon">
						<i class="icon-calendar"></i>
					</span>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6">
				<div class="input-group input-group-sm">
					<input type="text" id="from" class="form-control" name="from"/>
					<span class="input-group-addon">
						<i class="icon-calendar"></i>
					</span>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6">
				<div class="input-group input-group-sm">
					<input type="text" id="to" class="form-control" name="to"/>
					<span class="input-group-addon">
						<i class="icon-calendar"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
 
 
</body>
</html>