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
	    var URL = '/xyhai.php?s=/Demand';
	    var APP	 = '/xyhai.php?s=';
	    var SELF='/xyhai.php?s=/Demand/index';
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

    <div class="row margin-botton">
        <div class="col-sm-6 column">
            <div class="btn-group btn-group-md">
                <!-- <button class="btn btn-primary" type="button" onclick="goUrl('<?php echo U('add');?>')"><em class="glyphicon glyphicon-plus-sign"></em> 我要留言</button> -->
                <!-- <button class="btn btn-default" type="button" onclick="doGoBatch('<?php echo U('audit');?>')"><em class="glyphicon glyphicon-ok-circle"></em> 审核</button> -->
                <button class="btn btn-default" type="button" onclick="doConfirmBatch('<?php echo U('del', array('batchFlag' => 1));?>', '确实要删除选择项吗？')"><em class="glyphicon glyphicon-remove-circle"></em> 批量删除</button>
            </div>
        </div>        
    </div>

    <div class="row">
        <div class="col-lg-12">
            <form action="" method="post" id="form_do" name="form_do">
                <div class="table-responsive">
                    <table class="table table-hover xyh-table-bordered-out">
                        <thead>
                            <tr class="active">
                                <th><input type="checkbox" id="check"></th>
                                <th>编号</th>
                                <th>姓名</th>
                                <th>电话</th>
                                <th>邮箱</th>
                                <th>留言</th>
                                <!-- <th>审核</th> -->
                                <th class="text-right">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($vlist)): foreach($vlist as $key=>$v): ?><tr>
                            <td><input type="checkbox" name="key[]" value="<?php echo ($v["id"]); ?>"></td>
                            <td><?php echo ($v["id"]); ?></td>
                            <td><?php echo ($v["username"]); ?>
                                <!-- <p>
                                <strong>姓名：</strong><?php if($v['user_id']): ?><a href="<?php echo ($v["user_id"]); ?>" target="_blank"><?php echo ($v["username"]); ?></a><?php else: echo ($v["username"]); endif; ?>
                                <strong>来自：</strong><?php echo ($v["ip"]); ?> <strong>电话：</strong><?php echo ($v["tel"]); ?> <strong>QQ：</strong><?php echo ($v["qq"]); ?><br/>
                                </p>
                                <p>
                                <strong>留言：</strong><?php echo ($v["content"]); ?> [<?php echo ($v["post_time"]); ?>]<br/>
                                </p> -->
                                <!-- <?php if(!empty($v['reply_time'])): ?><div style="border: 1px solid #ccc; background-color: #fcffd0;padding: 5px;">
                                <strong>回复：</strong><?php echo ($v["reply"]); ?> [<?php echo ($v["reply_time"]); ?>]
                                </div><?php endif; ?> -->
                             </td>  
                             <td><?php echo ($v["tel"]); ?></td> 
                             <td><?php echo ($v["email"]); ?></td> 
                             <td><?php echo ($v["content"]); ?></td>           
                            <!-- <td class="">
                                <?php if(!empty($v['status'])): ?><strong class="text-success">已审核</strong><?php else: ?><strong class="text-muted"><i>未审核</i></strong><?php endif; ?>
                                <?php if(!empty($v['private_flag'])): ?><strong class="text-info"><i>悄悄话</i></strong><?php endif; ?>
                    
                            </td>                     -->
                            <td class="text-right">                                
                                <!-- <a href="<?php echo U('reply',array('id' => $v['id']), '');?>" class="label label-success">回复</a> -->
                                <a href="javascript:;" onclick="toConfirm('<?php echo U('del',array('id' => $v['id']), '');?>', '确实要删除吗？')" class="label label-danger">删除</a>
                    
                            </td>
                        </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>

            <div class="row clearfix">
                <div class="col-md-12 column">
                    <div class="xyh-page">
                        <?php echo ($page); ?>
                    </div>
                </div>
            </div>

            
        </div>
    </div>

    
			
	</div>	
</body>
</html>