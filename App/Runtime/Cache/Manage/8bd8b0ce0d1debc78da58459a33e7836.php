<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
	<title>后台</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link rel="stylesheet" type="text/css" href="/Data/static/bootstrap/3.3.5/css/bootstrap.min.css" media="screen">	
	<link rel='stylesheet' type="text/css" href="/App/Manage/View/Public/css/main.css?20191001" />
	<!-- 头部css文件|自定义  -->
	

	<script type="text/javascript" src="/Data/static/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="/Data/static/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
		<script src="/Data/static/js/html5shiv.min.js"></script>
		<script src="/Data/static/js/respond.min.js"></script>
    <![endif]-->
	
	<script type="text/javascript" src="/App/Manage/View/Public/js/jquery.form.min.js"></script>
	<script type="text/javascript" src="/Data/static/jq_plugins/layer/layer.js"></script>
	<script language="JavaScript">
	    <!--
	    var URL = '/xyhai.php?s=/Personal';
	    var APP	 = '/xyhai.php?s=';
	    var SELF='/xyhai.php?s=/Personal/index';
	    var PUBLIC='/App/Manage/View/Public';
	    var data_path = "/Data";
		var tpl_public = "/App/Manage/View/Public";
	    //-->
	</script>
	<script type="text/javascript" src="/App/Manage/View/Public/js/common.js?20191001"></script> 
	<!-- 头部js文件|自定义 -->
	
</head>
<body>
	<div class="xyh-content">
		
	<div class="row">
		<div class="col-lg-12">
			<h3 class="page-header"><em class="glyphicon glyphicon-cloud-upload"></em> 
			<?php echo ($type); ?>
		    </h3>
		</div>
		
	</div>


	<div class="row">
		<div class="col-lg-12">

				<form method='post' class="form-horizontal" id="form_do" name="form_do" action="<?php echo U('index');?>">					
					<div class="form-group">
						<label for="inputName" class="col-sm-2 control-label">用户名</label>
						<div class="col-sm-9">
							<p class="form-control-static"><?php echo (session('yang_adm_username')); ?></p>		
						</div>
					</div>				
					<div class="form-group">
						<label for="inputName" class="col-sm-2 control-label">最后登录时间</label>
						<div class="col-sm-9">
							<p class="form-control-static"><?php echo ($vo["login_time"]); ?></p>		
						</div>
					</div>				
					<div class="form-group">
						<label for="inputName" class="col-sm-2 control-label">最后登录IP</label>
						<div class="col-sm-9">
							<p class="form-control-static"><?php echo ($vo["login_ip"]); ?></p>		
						</div>
					</div>

					<div class="form-group">
						<label for="inputRealname" class="col-sm-2 control-label">真实姓名</label>
						<div class="col-sm-9">
							<input type="text" name="realname" id="inputRealname" value="<?php echo ($vo["realname"]); ?>" class="form-control" placeholder="真实姓名" />
						</div>
					</div>	


					<div class="form-group">
						<label for="inputEmail" class="col-sm-2 control-label">E-mail</label>
						<div class="col-sm-9">
							<input type="text" name="email" id="inputEmail" value="<?php echo ($vo["email"]); ?>" class="form-control" placeholder="email" />
						</div>
					</div>				

					<div class="row margin-botton-large">
						<div class="col-sm-offset-2 col-sm-9">
							<input type="hidden" name="uid" value="<?php echo ($vo["id"]); ?>" />
							<div class="btn-group">							
								<button type="submit" class="btn btn-primary"> <i class="glyphicon glyphicon-saved"></i>
									保存
								</button>
							</div>
						</div>
					</div>
				</form>
	
		</div>
	</div>

		



			
	</div>	
</body>
</html>