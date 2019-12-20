<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=no'/> 
<title><?php echo C('CFG_WEBTITLE');?></title>
<meta name="keywords" content="<?php echo C('CFG_KEYWORDS');?>" />
<meta name="description" content="<?php echo C('CFG_DESCRIPTION');?>" />
<link href="/Public/Mobile/default/css/base.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/Mobile/default/js/touchslider.js"></script>
<script type="text/javascript" src="/Public/Mobile/default/js/comm_fun.js"></script>
</head>
<body>
<!--顶部开始-->
<div id="body_margins">
<div id="header">
  <div class="logo"><img src="/Public/Mobile/default/images/logo.png"></div>
  <div class="navBtn">
  <div class="navArea">
  <a class="nvali" href="<?php echo U('Index/index');?>">网站首页</a>
  <?php
 $_typeid = intval('0'); if($_typeid == -1) $_typeid = I('cid', 0, 'intval'); $_navlist = get_category(1); if($_typeid == 0) { $_navlist = \Common\Lib\Category::toLayer($_navlist); }else { $_navlist = \Common\Lib\Category::toLayer($_navlist, 'child', $_typeid); } foreach($_navlist as $autoindex => $navlist): $navlist['url'] = get_url($navlist); if($autoindex == 3 || $autoindex == 7): ?></div><div class="navArea"><?php endif; ?>
  <a href='<?php echo ($navlist["url"]); ?>'><?php echo ($navlist["name"]); ?></a><?php endforeach;?>
  </div>
  </div>
</div>

<div class="swipe" style="width:100%;overflow:hidden;">
  <ul id='slider'>
	<li style="display: table-cell; padding: 0px; margin: 0px; float: left; vertical-align: top;"><a href="#"><img src="/Public/Mobile/default/images/pic/top_pic1.jpg" /></a></li>
	<li style="display: table-cell; padding: 0px; margin: 0px; float: left; vertical-align: top; display:none;"><a href="#"><img src="/Public/Mobile/default/images/pic/top_pic2.jpg"/></a></li>
  </ul>
</div>

<!--简介-->
<dl class="box">
  <dt class="title"><a href="#" class="more arr-round arr-round-blue"></a>公司简介</dt><dd class="intro-box">
    <?php
 $block = get_block('Introduction'); $block_content = ''; if ($block) { if ($block['block_type'] == 2) { if (!0) { $block_content = '<img src="'. $block['content'] .'" />'; }else { $block_content = $block['content']; } }else { if(0) { $block_content = str2sub(strip_tags($block['content']), 0, 0); }else { $block_content = $block['content']; } } } echo $block_content; ?>
  </dd>
</dl>

<!--产品-->
<dl class="box">
  <dt class="title">
  <a href="#" class="more arr-round arr-round-blue"></a>公司产品 </dt>
  <dd class="">
  <ul class="img-list"> 
  <?php
 $_typeid = '-1'; $_keyword = ''; $_arcid = ""; if($_typeid == -1) $_typeid = I('get.cid', 0, 'intval'); $where = array('product.delete_status' => 0,'cate_status' => array('LT',2)); if (!empty($_typeid)) { $_typeid_arr = explode(',',$_typeid); $ids_arr = array(); foreach($_typeid_arr as $_typeid_key => $_typeid_val) { $_typeid_val = intval($_typeid_val); if (empty($_typeid_val)) { continue; } $_typeid_ids = \Common\Lib\Category::getChildsId(get_category(10), $_typeid_val, true); $ids_arr = array_merge($ids_arr,$_typeid_ids); } $ids_arr = array_unique($ids_arr); if (!empty($ids_arr)) { $where['product.cid'] = array('IN',$ids_arr); } } if (0 > 0 && 0 > 0) { $where['product.point'] = array('between',array(0,0)); }else if (0 > 0) { $where['product.point'] = array('EGT', 0); }else if (0 > 0) { $where['product.point'] = array('ELT', 0); } if(0 == 1) { $where['product.audit_status'] = 0; } else if(0 == 2) { } else { $where['product.audit_status'] = 1; } if ($_keyword != '') { $where['product.title'] = array('like','%'.$_keyword.'%'); } if (!empty($_arcid)) { $where['product.id'] = array('IN', $_arcid); } if (0 > 0) { $where['_string'] = 'product.flag & 0 = 0 '; } if (0 > 0) { $count = D2('ArcView','product')->where($where)->count(); $ename = I('e', '', 'htmlspecialchars,trim'); if (!empty($ename) && C('URL_ROUTER_ON') == true) { $param['p'] = I('p', 1, 'intval'); $param_action = '/'.$ename; }else { $param = array(); $param_action = ''; } $thisPage = new \Common\Lib\Page($count, 0, $param, $param_action); $thisPage->rollPage = 5; $thisPage->setConfig('theme'," %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%"); $limit = $thisPage->firstRow. ',' .$thisPage->listRows; $page = $thisPage->show(); }else { $limit = "3"; } $_prolist = D2('ArcView','product')->nofield('content,picture_urls')->where($where)->order("id ASC")->limit($limit)->select(); if (empty($_prolist)) { $_prolist = array(); } foreach($_prolist as $autoindex => $prolist): $_jumpflag = ($prolist['flag'] & B_JUMP) == B_JUMP? true : false; $prolist['url'] = get_content_url($prolist['id'], $prolist['cid'], $prolist['ename'], $_jumpflag, $prolist['jump_url']); if(0) $prolist['title'] = str2sub($prolist['title'], 0, 0); if(0) $prolist['description'] = str2sub($prolist['description'], 0, 0); if(isset($prolist['picture_urls'])) $prolist['picture_urls'] = get_picture_array($prolist['picture_urls']); ?><li class="loading">
  <a href="<?php echo ($prolist["url"]); ?>"><div><img style="float:left;" src="<?php echo ($prolist["litpic"]); ?>" alt="<?php echo ($prolist["title"]); ?>"></div><p><?php echo ($prolist["title"]); ?></p></a> 
  </li><?php endforeach;?> 	
  
	<div style="clear:both"></div>
  </ul>
  </dd>
</dl>

<!--新闻-->
<dl class="box">
  <dt class="title">推荐阅读</dt>
</dl>
<dl class="box">
  <dd class="">
  <ul class="info-list">
  <?php
 $_typeid = '0'; $_keyword = ''; $_arcid = ""; if($_typeid == -1) $_typeid = I('get.cid', 0, 'intval'); $where = array('article.delete_status' => 0,'cate_status' => array('LT',2)); if (!empty($_typeid)) { $_typeid_arr = explode(',',$_typeid); $ids_arr = array(); foreach($_typeid_arr as $_typeid_key => $_typeid_val) { $_typeid_val = intval($_typeid_val); if (empty($_typeid_val)) { continue; } $_typeid_ids = \Common\Lib\Category::getChildsId(get_category(10), $_typeid_val, true); $ids_arr = array_merge($ids_arr,$_typeid_ids); } $ids_arr = array_unique($ids_arr); if (!empty($ids_arr)) { $where['article.cid'] = array('IN',$ids_arr); } } if (0 > 0 && 0 > 0) { $where['article.point'] = array('between',array(0,0)); }else if (0 > 0) { $where['article.point'] = array('EGT', 0); }else if (0 > 0) { $where['article.point'] = array('ELT', 0); } if(0 == 1) { $where['article.audit_status'] = 0; } else if(0 == 2) { } else { $where['article.audit_status'] = 1; } if ($_keyword != '') { $where['article.title'] = array('like','%'.$_keyword.'%'); } if (!empty($_arcid)) { $where['article.id'] = array('IN', $_arcid); } if (0 > 0) { $where['_string'] = 'article.flag & 0 = 0 '; } if (0 > 0) { $count = D2('ArcView','article')->where($where)->count(); $ename = I('e', '', 'htmlspecialchars,trim'); if (!empty($ename) && C('URL_ROUTER_ON') == true) { $param['p'] = I('p', 1, 'intval'); $param_action = '/'.$ename; }else { $param = array(); $param_action = ''; } $thisPage = new \Common\Lib\Page($count, 0, $param, $param_action); $thisPage->rollPage = 5; $thisPage->setConfig('theme'," %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%"); $limit = $thisPage->firstRow. ',' .$thisPage->listRows; $page = $thisPage->show(); }else { $limit = "6"; } $_artlist = D2('ArcView','article')->nofield('content')->where($where)->order("point DESC,id DESC")->limit($limit)->select(); if (empty($_artlist)) { $_artlist = array(); } foreach($_artlist as $autoindex => $artlist): $_jumpflag = ($artlist['flag'] & B_JUMP) == B_JUMP? true : false; $artlist['url'] = get_content_url($artlist['id'], $artlist['cid'], $artlist['ename'], $_jumpflag, $artlist['jump_url']); if(17) $artlist['title'] = str2sub($artlist['title'], 17, 0); if(0) $artlist['description'] = str2sub($artlist['description'], 0, 0); ?><li><a href="<?php echo ($artlist["url"]); ?>" title="<?php echo ($artlist["title"]); ?>"><h5><?php echo (str2sub($artlist["title"],20)); ?></h5><div class="summary"></div></a></li><?php endforeach;?>
  </ul>
  </dd>
</dl>
<!--tag-->
<dl class="box">
  <dt class="title">热门标签</dt>
</dl>
<dl class="box">
  <dd class="">
  <ul class="info-list">
  <?php
 $_typeid = intval('0'); $_arcid = intval('0'); $_keyword = ''; $where = array(); if (!empty($_typeid)) { $where['ti.cid'] = $_typeid; } if (!empty($_typeid) && !empty($_arcid)) { $where['ti.arc_id'] = $_arcid; } if ($_keyword != '') { $where['t.tag_name'] = array('like','%'.$_keyword.'%'); } if (0 > 0) { $count = M('Tag')->alias('t')->field('t.tag_name,t.id,t.num,t.hit')->join('INNER JOIN __TAG_INDEX__ ti ON ti.tag_id = t.id')->where($where)->count('DISTINCT t.tag_name'); $ename = I('e', '', 'htmlspecialchars,trim'); if (!empty($ename) && C('URL_ROUTER_ON') == true) { $param['p'] = I('p', 1, 'intval'); $param_action = '/'.$ename; }else { $param = array(); $param_action = ''; } $thisPage = new \Common\Lib\Page($count, 0, $param, $param_action); $thisPage->rollPage = 5; $thisPage->setConfig('theme'," %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%"); $limit = $thisPage->firstRow. ',' .$thisPage->listRows; $page = $thisPage->show(); }else { $limit = "10"; } $_taglist = M('Tag')->alias('t')->distinct(true)->field('t.tag_name,t.id,t.num,t.hit')->join('INNER JOIN __TAG_INDEX__ ti ON ti.tag_id = t.id')->where($where)->order("sort ASC,id DESC")->limit($limit)->select(); if (empty($_taglist)) { $_taglist = array(); } foreach($_taglist as $autoindex => $taglist): if(C('URL_ROUTER_ON') == true) { $taglist['url'] = U('Tag/'.$taglist['tag_name'],''); }else { $taglist['url'] = U('Tag/shows', array('tname'=> $taglist['tag_name'])); } ?><li><a href="<?php echo ($taglist["url"]); ?>" class="tag-item"><?php echo ($taglist["tag_name"]); ?></a></li><?php endforeach;?>
  </ul>
  </dd>
</dl>

<!--友情-->
<dl class="box">
  <dt class="title">友情连接</dt>
</dl>
<dl class="box">
  <dd style="padding: 10px;">
    <a href="https://www.yuntaos.com">网站建设</a>&nbsp;
    <?php
 $_type = intval('-1'); if ($_type == 0) { $where = array('is_check'=> 0); }else if ($_type == 1) { $where = array('is_check'=> 1); } else { $where = array('id' => array('gt',0)); } if (0 > 0) { $count = M('link')->where($where)->count(); $thisPage = new \Common\Lib\Page($count, 0); $thisPage->rollPage = 5; $thisPage->setConfig('theme'," %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%"); $limit = $thisPage->firstRow. ',' .$thisPage->listRows; $page = $thisPage->show(); }else { $limit = "20"; } $_flink = M('link')->where($where)->order("sort ASC")->limit($limit)->select(); if (empty($_flink)) { $_flink = array(); } foreach($_flink as $autoindex => $flink): ?><a href="<?php echo ($flink["url"]); ?>" target="_blank"><?php echo ($flink["name"]); ?></a>&nbsp;<?php endforeach;?>
  </dd>
</dl>

<div class="askbar">
<a href="tel:<?php echo C('CFG_PHONE');?>" rel="nofollow">电话咨询</a><a href="tel:<?php echo C('CFG_PHONE');?>" rel="nofollow">在线咨询</a>
</div>

<!--联系我们-->
<dl class="box">
  <dt class="title"><a href="#" class="more arr-round arr-round-blue"></a>联系我们</dt><dd class="intro-box">
    <p>【公司地址】：<?php echo C('CFG_ADDRESS');?><br/>
      【公司网站】：<?php echo C('CFG_WEBURL');?><br/>
    【咨询热线】：<a href="tel:<?php echo C('CFG_PHONE');?>" class="btn-blue"><?php echo C('CFG_PHONE');?></a></p>
  </dd>
</dl>
<!--友情链接-->
<div class="copyright">
<p><?php echo C('CFG_POWERBY');?>  <a href="http://www.xyhcms.com/" title="行云海CMS">Power by XYHCMS</a> <?php echo C('CFG_STATS');?></p>
</div>
<ul id="fixed-foot">
  <li class="border-left-none"><a class="tel" href="tel:<?php echo C('CFG_PHONE');?>"><div><span class="icon_bg_tel"></span><span>电话咨询</span></div></a></li>
  <li><a href="tel:<?php echo C('CFG_PHONE');?>" rel="nofollow"><div><span class="icon_bg_swt"></span><span>在线咨询</span></div></a></li>
</ul>
<script language="javascript"></script>
<script type="text/javascript">
var t1=new TouchSlider('slider',{'auto':true, begin:0, speed:1000, timeout:3000, direction:'left'});
</script>
</div>
</body>
</html>