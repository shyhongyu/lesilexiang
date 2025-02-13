<?php
return array(
	//'配置项'=>'配置值'
	'MODULE_ALLOW_LIST'     => array('Home', 'Mobile', 'Api'), //模块列表
	'MODULE_DENY_LIST'      => array('Common', 'Manage'),
	'DEFAULT_MODULE'        => 'Home', //默认分组

	//'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
	//'LANG_LIST'  => 'zh-cn', // 允许切换的语言列表 用逗号分隔
	'DEFAULT_LANG'          => 'zh-cn', //默认语言
	//'VAR_LANGUAGE'  => 'l', // 默认语言切换变量

	//模板路径(简化模板目录)
	'TMPL_FILE_DEPR'        => '_', // 控制器_方法.html, 控制器/方法.html[默认]
	//去掉伪静态后缀
	//'URL_HTML_SUFFIX' => '',
	'TMPL_STRIP_SPACE'      => false, //是否去除模板文件里面的html空格与换行
	'TMPL_TEMPLATE_SUFFIX'  => '.html', //模板后缀

	//显示页面调试信息
	'SHOW_PAGE_TRACE'       => false,

	'TMPL_ACTION_ERROR'     => './Data/resource/system/jump.xyh', // error tpl
	'TMPL_ACTION_SUCCESS'   => './Data/resource/system/jump.xyh', //  success tpl
	'TMPL_EXCEPTION_FILE'   => './Data/resource/system/exception.xyh', //exception tpl

	'APP_SUB_DOMAIN_DEPLOY' => APP_MOBLIE_API_SUB_DOMAIN, // 开启子域名或者IP配置
	'APP_SUB_DOMAIN_RULES'  => array(
		APP_MOBLIE_SUB_DOMAIN_URL => 'Mobile', // m.xx.com域名指向Mobile模块
		APP_API_SUB_DOMAIN_URL    => 'Api', // api.xx.com域名指向Api模块
	),
	// 'APP_DOMAIN_SUFFIX' => '', //域名后缀.net.cn等必须指定

	//加载用户函数
	'LOAD_EXT_FILE'         => 'other',
	//加载其他配置文件
	'LOAD_EXT_CONFIG'       => 'db', //加载扩展配置文件

	//'URL_PATHINFO_DEPR' => '/',//参数之间的分割符号

	'URL_ROUTER_ON'         => false, //开启路由
	//'URL_ROUTE_RULES' => array( //定义路由规则),

	//强制区分大小写
	'URL_CASE_INSENSITIVE'  => false,
	'LOG_RECORD'            => false, // 开启日志记录---正式可以关掉
	'LOG_LEVEL'             => 'EMERG,ALERT,CRIT,ERR,WARN,NOTICE', // 只记录EMERG ALERT CRIT ERR 错误，WARN,NOTICE,INFO,DEBUG,SQL

	'DATA_CACHE_PREFIX'     => 'ZLLzYzq_', //S缓存 随机前缀

);
