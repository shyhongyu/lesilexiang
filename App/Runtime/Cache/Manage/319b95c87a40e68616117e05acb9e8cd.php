<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" type="text/css" href="/lslx/Data/static/bootstrap/3.3.5/css/bootstrap.min.css" media="screen"> 
    <link rel='stylesheet' type="text/css" href="/lslx/App/Manage/View/Public/css/main.css" />
    <script type="text/javascript" src="/lslx/Data/static/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="/lslx/Data/static/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="/lslx/Data/static/js/html5shiv.min.js"></script>
        <script src="/lslx/Data/static/js/respond.min.js"></script>
    <![endif]-->
    
    <script type="text/javascript" src="/lslx/App/Manage/View/Public/js/jquery.form.min.js"></script>
    <!-- 头部js文件|自定义 -->
    <script language="JavaScript">
    function returnValue(sfile, stype){  
        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引

        if (stype == 'picture') {
            if (window.parent.selectPicture) {
                window.parent.selectPicture(sfile);
            } else {
                window.parent.xyhUploadFile.sfile =sfile;
            }
            
        } else {
            if (window.parent.selectFile) {
                window.parent.selectFile(sfile);
            } else {
                window.parent.xyhUploadFile.sfile =sfile;
            }
            
        }
        parent.layer.close(index);
    }

    
</script>
</head>
<body>
    <div class="xyh-sub-content">
        <div class="row">
        <div class="col-lg-12">
            <h4 class="page-header"><em class="glyphicon glyphicon-cloud-upload"></em> 
            <?php echo ($type); ?>         
            </h4>
        </div>        
    </div>

    <div class="row">
        <div class="col-lg-12">
            <form action="" method="post" id="form_do" name="form_do">
                    <table class="table table-hover xyh-table-bordered-out">
                        <thead>
                            <tr>
                                <th>文件名称</th>
                                <th>大小</th>
                                <th>修改时间</th>                                
                                <th class="text-right">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($vlist)): foreach($vlist as $key=>$v): ?><tr>
                                <td>
                                 <?php if($v["file_type"] != 1): ?><a href="javascript:returnValue('<?php echo ($v["url"]); ?>', '<?php echo ($stype); ?>');"><?php echo ($v["file_path"]); ?></a>
                                <?php else: ?>
                                <a href="javascript:returnValue('<?php echo ($v["url"]); ?>', '<?php echo ($stype); ?>');"><img src="<?php echo ($v["purl"]); ?>" width="70" title="点击选择原图"></a><?php endif; ?>
                                </td>
                                <td><?php echo ($v["size"]); ?></td>
                                <td><?php echo ($v["upload_time"]); ?></td>
                                <td class="text-right" style="min-width:150px;">
                                <a href="javascript:returnValue('<?php echo ($v["url"]); ?>', '<?php echo ($stype); ?>');" class="label label-success">选择</a> 
                                <?php if(is_array($v['thumb_size'])): $i = 0; $__LIST__ = $v['thumb_size'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:returnValue('<?php echo (get_picture($v["url"],$vo[0],$vo[1])); ?>', '<?php echo ($stype); ?>');" class="label label-success"><?php echo ($vo[0]); ?>X<?php echo ($vo[1]); ?></a>&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
                                </td>
                            </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
            </form>
            
        </div>
        <div class="col-lg-12">
            <div class="xyh-page"><?php echo ($page); ?></div>
        </div>
    </div>
            
    </div>  
</body>
</html>