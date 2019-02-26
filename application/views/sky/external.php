
<!-- external javascript -->
<script type="text/javascript" src="<?php echo $base_url;?>assets/js/jquery-1_11_1_min.js"></script>
<script src="<?php echo $base_url;?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- library for cookie management -->
<script src="<?php echo $base_url;?>assets/js/jquery.cookie.js"></script>
<!-- calender plugin -->
<script src='<?php echo $base_url;?>assets/bower_components/moment/min/moment.min.js'></script>
<script src='<?php echo $base_url;?>assets/bower_components/fullcalendar/dist/fullcalendar.min.js'></script>
<!-- data table plugin -->
<script src='<?php echo $base_url;?>assets/js/jquery.dataTables.min.js'></script>

<!-- select or dropdown enhancer -->
<script src="<?php echo $base_url;?>assets/bower_components/chosen/chosen.jquery.min.js"></script>
<!-- plugin for gallery image view -->
<script src="<?php echo $base_url;?>assets/bower_components/colorbox/jquery.colorbox-min.js"></script>
<!-- notification plugin -->
<script src="<?php echo $base_url;?>assets/js/jquery.noty.js"></script>
<!-- library for making tables responsive -->
<script src="<?php echo $base_url;?>assets/bower_components/responsive-tables/responsive-tables.js"></script>
<!-- tour plugin -->
<script src="<?php echo $base_url;?>assets/bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js"></script>
<!-- star rating plugin -->
<script src="<?php echo $base_url;?>assets/js/jquery.raty.min.js"></script>
<!-- for iOS style toggle switch -->
<script src="<?php echo $base_url;?>assets/js/jquery.iphone.toggle.js"></script>
<!-- autogrowing textarea plugin -->
<script src="<?php echo $base_url;?>assets/js/jquery.autogrow-textarea.js"></script>
<!-- multiple file upload plugin -->
<script src="<?php echo $base_url;?>assets/js/jquery.uploadify-3.1.min.js"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="<?php echo $base_url;?>assets/js/jquery.history.js"></script>
<!-- application script for Charisma demo -->
<script src="<?php echo $base_url;?>assets/js/charisma.js"></script>

<script>
  $( function() {
	$("#datepicker").datepicker({
		  //日期格式	
		  dateFormat: 'yy-mm-dd',			
		  //可使用下拉式選單 - 月份
		  changeMonth : true,
		  //可使用下拉式選單 - 年份
		  changeYear : true,
		  //設定 下拉式選單月份 在 年份的後面
		  showMonthAfterYear : true
		});		
	$('#datepicker').val('<?php echo $datepicker; ?>');	
	$('#users').val('<?php echo $users; ?>');	
		//設定中文語系
		$.datepicker.regional['zh-TW'] = {
		   dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
		   dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"],
		   monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
		   monthNamesShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
		   prevText: "上月",
		   nextText: "次月",
		   weekHeader: "週"
		};
		//將預設語系設定為中文
		$.datepicker.setDefaults($.datepicker.regional["zh-TW"]);	
		
		
  } );
  </script>
  
	<script>
		function showPage()
		 {
			 this.query_form.submit();
		 }
		function clear_value() {
			$('#datepicker').val('');
			$('#users').val('');
		}							 
	</script>