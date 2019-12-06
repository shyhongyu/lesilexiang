<?php
//邮件模板配置文件
return array(
	'com_code1_1' => array(
		'title' => '[{$sitename}]--会员身份验证',
		'txt' => '<p>尊敬的用户，你的邮箱验证码是：{$code}。</p>
<p>1、为了保障您的安全，请不要将以上验证码告诉任何人，本站工作人员不会向您索取验证码。</p>
<p>2、如果本次验证码并非您本人申请，请忽略本邮件。</p>
<p></p>
<p>此邮件由系统发送，请勿直接回复。<a href="{$siteurl}" target="_blank">[{$sitename}]</a></p>',
	),
	'reg_code1_1' => array(
		'title' => '[{$sitename}]--注册验证',
		'txt' => '<p>尊敬的用户，你正在注册本站会员，你的邮箱验证码是：{$code}。</p>
<p>1、为了保障您的安全，请不要将以上验证码告诉任何人，本站工作人员不会向您索取验证码。</p>
<p>2、如果本次验证码并非您本人申请，请忽略本邮件。</p>
<p></p>
<p>此邮件由系统发送，请勿直接回复。<a href="{$siteurl}" target="_blank">[{$sitename}]</a></p>',
	),
	'activate_code1_1' => array(
		'title' => '[{$sitename}]--邮箱身份验证',
		'txt' => '<p>您好：</p><p>感谢您使用{$sitename}，请点击一下链接激活/绑定你的邮箱</p>
<p><a href="{$aturl}" target="_blank">{$aturl}</a></p>
<p>(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)</p>
<p>此邮件由系统发送，请勿直接回复。</p>	',
	),
);