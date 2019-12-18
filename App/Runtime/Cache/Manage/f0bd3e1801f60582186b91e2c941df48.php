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
	    var URL = '/xyhai.php?s=/Guestbook';
	    var APP	 = '/xyhai.php?s=';
	    var SELF='/xyhai.php?s=/Guestbook/add';
	    var PUBLIC='/App/Manage/View/Public';
	    var data_path = "/Data";
		var tpl_public = "/App/Manage/View/Public";
	    //-->
	</script>
	<script type="text/javascript" src="/App/Manage/View/Public/js/common.js?20191001"></script> 
	<!-- 头部js文件|自定义 -->
	
<script type="text/javascript" src="/App/Manage/View/Public/js/calendar.config.js"></script>


</head>
<body>
	<div class="xyh-content">
		
	<div class="row">
		<div class="col-lg-12">
			<h3 class="page-header"><em class="glyphicon glyphicon-cloud-upload"></em> 
			回复留言
		    </h3>
		</div>
		
	</div>


	<div class="row">
		<div class="col-lg-12">

				<form method='post' class="form-horizontal" id="form_do" name="form_do" action="<?php echo U('add');?>">	
					<div class="form-group">
						<label for="inputUsername" class="col-sm-2 control-label">姓名</label>
						<div class="col-sm-9">
							<input type="text" name="username" id="inputUsername" class="form-control" placeholder="姓名" />	
						</div>
					</div>
					<div class="form-group">
						<label for="inputTel" class="col-sm-2 control-label">电话</label>
						<div class="col-sm-9">
							<input type="text" name="tel" id="inputTel" class="form-control" placeholder="电话" />				
						</div>
					</div>
					<div class="form-group">
						<label for="inputEmail" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-9">
							<input type="text" name="email" id="inputEmail" class="form-control" placeholder="Email" />
						</div>
					</div>
					
					<div class="form-group">
						<label for="inputQQ" class="col-sm-2 control-label">QQ</label>
						<div class="col-sm-9">
							<input type="text" name="qq" id="inputQQ" class="form-control" placeholder="QQ" />
						</div>
					</div>				


					<div class="form-group">
						<label for="inputContent" class="col-sm-2 control-label">留言</label>
						<div class="col-sm-9">
							<textarea name="content" id="inputContent" class="form-control" rows="6"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-2 control-label">审核</label>
						<div class="col-sm-9">
							<select name="status" class="form-control">
								<option value="0">未审核</option>
								<option value="1" selected="selected">已审核</option>
							</select>
						</div>
					</div>	

					<div class="form-group">
						<label for="" class="col-sm-2 control-label">悄悄话</label>
						<div class="col-sm-9">
							<select name="private_flag" class="form-control">
								<option value="0" selected="selected">否</option>
								<option value="1">是</option>
							</select>
						</div>
					</div>


					<div class="row margin-botton-large">
						<div class="col-sm-offset-2 col-sm-9">
							<div class="btn-group">							
								<button type="submit" class="btn btn-primary"> <i class="glyphicon glyphicon-saved"></i>
									保存
								</button>
								<button type="button" onclick="goUrl('<?php echo U('index');?>')" class="btn btn-default"> <i class="glyphicon glyphicon-chevron-left"></i>
									返回
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