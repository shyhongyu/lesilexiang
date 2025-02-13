<?php
/**
 * @copyright   Copyright (c) 2014-2016 xyhcms.com All rights reserved.
 * @license     http://www.xyhcms.com/help/1.html
 * @link        http://www.xyhcms.com
 * @author      gosea <gosea199@gmail.com>
 */

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
	die('require PHP > 5.3.0 !');
}

define('APP_DEBUG', true); //是否调试//部署阶段注释或者设为false
define('APP_PATH', "./App/"); //项目路径
define('THINK_PATH', "./Include/");

define('APP_MOBLIE_API_SUB_DOMAIN', false); //是否开启手机站/API二级域名
define('APP_MOBLIE_SUB_DOMAIN_URL', 'm'); //二组二级域名前缀，或者直接指定域名全称m.xx.com
define('APP_API_SUB_DOMAIN_URL', 'api'); //Api二级域名前缀，或者直接指定域名全称api.xx.com

//判断是否安装
if (!file_exists(APP_PATH . 'Common/Conf/db.php')) {
	header('Location:Install/index.php');
	exit();
}

require THINK_PATH . 'ThinkPHP.php'; //加载ThinkPHP框架
