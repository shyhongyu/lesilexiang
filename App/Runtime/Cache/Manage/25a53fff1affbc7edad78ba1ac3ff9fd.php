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
	    var URL = '/xyhai.php?s=/System';
	    var APP	 = '/xyhai.php?s=';
	    var SELF='/xyhai.php?s=/System/site';
	    var PUBLIC='/App/Manage/View/Public';
	    var data_path = "/Data";
		var tpl_public = "/App/Manage/View/Public";
	    //-->
	</script>
	<script type="text/javascript" src="/App/Manage/View/Public/js/common.js?20191001"></script> 
	<!-- 头部js文件|自定义 -->
	
	<script type="text/javascript" src="/App/Manage/View/Public/js/XYHUploader/XYHUploader.js"></script>
	<script type="text/javascript">
		$(function(){
			var uploadMaxSizeObj = $('input[name="config[CFG_UPLOAD_MAXSIZE]"]');
			if (uploadMaxSizeObj.length >0) {
				uploadMaxSizeObj.after('<div class="alert alert-success">上传大小单位：KB, 1M=1024KB<br />允许上传大小不能大于php.ini中的设置<br/>php.ini中上传限制为：<?php echo ($environment_upload); ?></div>')

			}
		});
	</script>

</head>
<body>
	<div class="xyh-content">
		
	<div class="row">
		<div class="col-lg-12">
			<h3 class="page-header"><em class="glyphicon glyphicon-cloud-upload"></em> 
			网站设置   
		    </h3>
		</div>
		
	</div>
	<div class="row margin-botton">
        <div class="col-md-12 text-right">
            <div class="btn-group btn-group-md">
                 <button class="btn btn-default" type="button" onclick="goUrl('<?php echo U('index');?>')"><em class="glyphicon glyphicon-th-list"></em> 配置项管理</button>
            </div>
        </div>
    </div>


	<div class="row">
		<div class="col-lg-12">
			<div>
			
				<!-- Nav tabs -->			
				<ul class="nav nav-tabs" role="tablist">
					<?php if(is_array($configgroup)): foreach($configgroup as $key=>$v): ?><li role="presentation" <?php if($key == 1): ?>class="active"<?php endif; ?>>
						<a href="#tabConent<?php echo ($key); ?>" aria-controls="tabConent<?php echo ($key); ?>" role="tab" data-toggle="tab"><?php echo ($v); ?></a>
					</li><?php endforeach; endif; ?>
				</ul>

				<form method='post' class="form-horizontal" id="form_do" name="form_do" action="<?php echo U('site');?>">
					<!-- Tab panes -->			
					<div class="tab-content" style="margin-top:20px;">
						<?php if(is_array($vlist)): foreach($vlist as $key=>$v): ?><div role="tabpanel" <?php if($key == 1): ?>class="tab-pane active"<?php else: ?>class="tab-pane"<?php endif; ?> id="tabConent<?php echo ($key); ?>">
							<?php if(is_array($v)): $i = 0; $__LIST__ = $v;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i;?><div class="form-group">
								 <label for="input_<?php echo ($config["name"]); ?>" class="col-sm-2 control-label"><?php echo ($config["title"]); ?></label>
								<div class="col-sm-8">
									<?php switch(parse_config_attr($config['t_value'])): case "text": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["s_value"]); ?>" placeholder="<?php echo ($config["remark"]); ?>"><?php break;?>
									<?php case "radio": $_result=parse_config_attr($config['t_value'], 0);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label class="radio-inline">
	                                            <input type="radio" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($key); ?>" <?php if($config['s_value'] == $key): ?>checked="checked"<?php endif; ?> /> <?php echo ($vo); ?>
	                                        </label><?php endforeach; endif; else: echo "" ;endif; break;?>
									<?php case "checkbox": $_result=parse_config_attr($config['t_value'], 0);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
											    <input type="checkbox" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($key); ?>" <?php if($config['s_value'] == $key): ?>checked="checked"<?php endif; ?>><?php echo ($vo); ?>
											</label><?php endforeach; endif; else: echo "" ;endif; break;?>
									<?php case "select": ?><select name="config[<?php echo ($config["name"]); ?>]" class="form-control">
										<?php $_result=parse_config_attr($config['t_value'], 0);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($config['s_value'] == $key): ?>selected="selected"<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
									</select><?php break;?>
									<?php case "textarea": ?><textarea name="config[<?php echo ($config["name"]); ?>]" class="form-control" rows="5" placeholder="<?php echo ($config["remark"]); ?>"><?php echo ($config["s_value"]); ?></textarea><?php break;?>									
									<?php case "file@ad": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" data-name="<?php echo ($config["name"]); ?>"  value="<?php echo ($config["s_value"]); ?>" placeholder="<?php echo ($config["remark"]); ?>">	
										<script type="text/javascript">
											$(function(){
												$('input[data-name=<?php echo ($config["name"]); ?>').XYHUploader({
													sfile:"abc1", 
													btnName: "水印图片",
													furl:"<?php echo U('Public/upload');?>", 
													burl:"<?php echo U('Public/browseFile', array('stype' => 'ad'));?>",
													show: true,
													thide: true,
													thumflag: false
												});

											});
										</script><?php break;?>
									<?php case "file@ad2": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" data-name="<?php echo ($config["name"]); ?>"  value="<?php echo ($config["s_value"]); ?>" placeholder="<?php echo ($config["remark"]); ?>">	
										<script type="text/javascript">
											$(function(){
												$('input[data-name=<?php echo ($config["name"]); ?>').XYHUploader({
													sfile:"abc1", 
													furl:"<?php echo U('Public/upload');?>", 
													burl:"<?php echo U('Public/browseFile', array('stype' => 'ad'));?>",
													show: true,
													thide: true,
													thumflag: false
												});

											});
										</script><?php break;?>									
									<?php case "file@img": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" data-name="<?php echo ($config["name"]); ?>"  value="<?php echo ($config["s_value"]); ?>" placeholder="<?php echo ($config["remark"]); ?>">	
										<script type="text/javascript">
											$(function(){
												$('input[data-name=<?php echo ($config["name"]); ?>').XYHUploader({
													sfile:"img1", 
													furl:"<?php echo U('Public/upload',array('img_flag' => 1));?>", 
													burl:"<?php echo U('Public/browseFile', array('stype' => 'picture'));?>",
													show: true,
													thide: true,
													thumflag: true
												});

											});
										</script><?php break; endswitch;?>

								</div>
								<div class="col-sm-2">
									<button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="left" title="模板标签调用方法：" data-content="&lt;yang:cfg name='<?php echo ($config["name"]); ?>' /&gt;">
									 <em class="glyphicon glyphicon-flash" aria-hidden="true"></em>
									</button>

									

									<?php if(!empty($config['remark'])): ?><button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="<?php echo ($config["remark"]); ?>" /&gt;">
									 <em class="glyphicon glyphicon-star" aria-hidden="true"></em>
									</button><?php endif; ?>
								</div>
							</div><?php endforeach; endif; else: echo "" ;endif; ?>						
						</div><?php endforeach; endif; ?>

					</div>
					<div class="row">
						<div class="col-sm-offset-2 col-sm-9">
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

		



			
	</div>	
</body>
</html>