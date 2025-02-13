<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'/> 
<title>跳转提示</title>

<style type="text/css">
body{background:#fff;padding:0px;margin: 0px;font-family: 'Microsoft YaHei'; color: #333;}
input,button{ border:solid 1px #dcdcdc;height:30px; line-height:30px; padding:3px 6px;color:#999;background:#fff; vertical-align: middle;}
select{border:solid 1px #ccc;}
img{border:none;}
a{ text-decoration: none;color:#3361AD;}
.system-message{ padding:10pxx; }
.system-message .jump{ padding-top: 10px; font-size:14px;}
.system-message .success,.system-message .error{ line-height: 1.6em; font-size: 18px;word-break:break-all; }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}


.xyh_box{background:#e9f8ff;width:400px;height:auto;border:solid 7px #57c0ff;border-radius:5px;padding:0px;position: absolute;left: 50%;top: 50%;overflow: hidden;display:block;margin:-150px 0 0 -200px;}
.xyh_box .xyh_box_content{padding:20px;}
.xyh_box .title{font-size:16px;font-weight:bold;height: 36px; line-height: 36px; padding: 0px 0px 0px 10px; overflow:hidden;  color:#ffffff;background-color:#2b9ee4;
background-image: -moz-linear-gradient(top , #2b9ee4, #4fb7fe );
background-image: -webkit-linear-gradient(top , #2b9ee4, #4fb7fe );
background-image: -o-linear-gradient(top , #2b9ee4, #4fb7fe );
background-image: -ms-linear-gradient(top , #2b9ee4, #4fb7fe );
background-image: linear-gradient(top , #2b9ee4, #4fb7fe );}
.space,.space30{display:block; float:right; width:10px; background-color:#57c0ff; height:100%; margin-left:5px;}
.space30{width:30px;}

.error{font-size: 12px; color: #ff3300;}
.error a{color: #ff3300;}

</style>
</head>
<body>
<!--顶部开始-->
<div>

	<div class="xyh_box">
		<div class="title">XYHCMS 提示<span class="space30"></span><span class="space"></span><span class="space"></span><span class="space"></span></div>
		<div class="xyh_box_content">	
			
			<div class="p10">
			<div class="system-message">
			<?php if(isset($message)): ?><p class="success">√ <?php echo($message); ?></p>
			<?php else: ?>
			<p class="error">× <?php echo($error); ?></p><?php endif; ?>
			<p class="detail"></p>
			<p class="jump">
			页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
			</p>
			</div>
			<script type="text/javascript">
			(function(){
			var wait = document.getElementById('wait'),href = document.getElementById('href').href;
			var interval = setInterval(function(){
				var time = --wait.innerHTML;
				if(time <= 0) {
					location.href = href;
					clearInterval(interval);
				};
			}, 1000);
			})();
			</script>
			</div>
		
		</div>
	</div>

</div>
</body>
</html>