<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>XYHCMS</title>
<link rel="stylesheet" href="/App/Manage/View/Public/css/login.css?v20160625" />
<script type="text/javascript" src="/Data/static/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/Data/static/jq_plugins/layer/layer.js"></script>
<script type="text/javascript" src="/App/Manage/View/Public/js/login.js?v20160625000"></script>
</head>	
<body>
	<div class="xyh-login">
		<div class="xyh-login-form">	
			<form action="<?php echo U('Login/login');?>" method="post" id="LoginForm">
			<div class="title">	</div>
			<table width="100%">
				<tr>
					<th>帐号:</th>
					<td>
						<input type="username" name="username" class="len220"/>
					</td>
				</tr>
				<tr>
					<th>密码:</th>
					<td>
						<input type="password" class="len220" name="password"/>
					</td>
				</tr>
				<tr>
					<th>验证码:</th>
				  <td>
						<input type="code" class="len220" name="code" autocomplete="off" /></td>
				</tr>
				<tr>
					<th>&nbsp;</th>
				  	<td>
						<img src="<?php echo U('Login/verify',array('id' => 'a_login_1'));?>" data-url="<?php echo U('Login/verify',array('id' => 'a_login_1'));?>" align="absmiddle" id="vcode"  class="vcode" />
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding-left:120px;"><button type="submit">登　　录</button></td>
				</tr>
			</table>
		</form>
		</div>
	</div>

</body>
</html>