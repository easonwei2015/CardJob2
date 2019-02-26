<div class="ch-container">
    <div class="row">
        
    <div class="row">
        <div class="col-md-12 center login-header">   
			<h1>玉里慈濟醫院工作卡上傳系統</h1>
        </div>
    </div>

    <div class="row">
        <div class="well col-md-5 center login-box">
            <div class="alert alert-info">
                <h2><?php echo $error_msg;?></h2>
            </div>
            <form class="form-horizontal" action="<?php echo $index_base_url;?>login" method="post" >
                <fieldset>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                        <input name = 'Username' type="text" class="form-control" placeholder="帳號">
                    </div>
                    <div class="clearfix"></div><br>

                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
                        <input name = 'password' type="password" class="form-control" placeholder="密碼">
                    </div>
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <p class="center col-md-5">
                        <button onclick="query_data()" class="btn btn-primary">登入</button>
                    </p>
					<p>最佳瀏覽效果請使用IE9以上版本 或 Chrome瀏覽網頁</p>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<script>
	function query_data(){
		var msg = '';
		if (document.getElementById("account").value == ''){
			msg = msg + '請輸入帳號\n';
		}
		if (document.getElementById("password").value == ''){
			msg = msg + '請輸入密碼';
		}
		if(msg !=''){
			alert(msg);
			return false;
		}
		document.forms["login"].submit();
	}
</script>
</div>