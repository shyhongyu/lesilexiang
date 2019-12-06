<?php
/**
 *　                  oooooooooooo
 *
 *                  ooooooooooooooooo
 *                       o
 *                      o
 *                     o        o
 *                    oooooooooooo
 *
 *         ～～         ～～         　　～～
 *       ~~　　　　　~~　　　　　　　　~~
 * ~~～~~～~~　　　~~~～~~～~~～　　　~~~～~~～~~～
 * ·······              ~~XYHCMS~~            ·······
 * ·······  闲看庭前花开花落 漫随天外云卷云舒 ·······
 * ·············     www.xyhcms.com     ·············
 * ··················································
 * ··················································
 *
 * @Author: gosea <gosea199@gmail.com>
 * @Date:   2014-06-21 10:00:00
 * @Last Modified by:   gosea
 * @Last Modified time: 2017-11-26 09:32:33
 */
namespace Mobile\Controller;

class PublicController extends MobileCommonController {

	public function login() {

		$member_open = C('CFG_MEMBER_OPEN'); //会员中心是否关闭
		if (!$member_open) {
			exit_msg('会员中心已关闭！');
		}

		$furl = $_SERVER['HTTP_REFERER'];
		if (IS_POST) {
			$this->loginPost();
			exit();
		}
		$this->assign('furl', $furl);
		$this->assign('title', '用户登录');
		$this->display();
	}

	public function loginPost() {

		A('Home/Public')->loginPost();
	}

	//退出
	public function logout() {

		A('Home/Public')->logout();
	}

	//自动登录后，js验证，更新积分
	public function loginChk() {

		if (!IS_AJAX) {
			exit();
		}

		$nickname = get_cookie('nickname');
		$furl = '';
		if (A('Home/Public')->_loginChk()) {
			$this->success('已登录', $furl, array('nickname' => $nickname));
		} else {
			$this->error('请登录!', '');
		}

	}

	//注册
	public function register() {

		if (IS_POST) {
			$this->registerPost();
			exit();
		}

		$this->assign('title', '用户注册');
		$this->display();
	}

	//兼容v1.5之前的注册提交
	public function registerHandle() {
		$this->register();
	}

	//注册
	public function registerPost() {

		A('Home/Public')->registerPost();

	}

	/**
	 * 激活并绑定邮箱
	 */
	public function activateEmail($email, $code) {
		A('Home/Public')->activateEmail($email, $code);

	}

	/**
	 * 发送验证码
	 * @param  string  $address 手机|或邮箱
	 * @param  integer $type    类型|0手机，1邮箱
	 * @param  boolean $way     验证码对应场景：register|注册,login|登录验证,getpwd|找回密码,common|公用
	 * @return json
	 */
	public function sendCode($address, $type = 0, $way = 1) {

		A('Home/Public')->sendCode($address, $type, $way);

	}

	/**
	 * 发送验证码--带图片验证码
	 * @param  string  $address 手机|或邮箱
	 * @param  integer $type    类型|0手机，1邮箱
	 * @param  boolean $way     验证码对应场景：register|注册,login|登录验证,getpwd|找回密码,common|公用
	 * @return json
	 */
	public function sendCodeByValidate($address, $verifycode = '', $type = 0, $way = 1) {
		A('Home/Public')->sendCodeByValidate($address, $verifycode, $type, $way);

	}

	//增加点击数
	public function click() {
		$id = I('id', 0, 'intval');
		$table_name = I('tn', '');
		if (!preg_match('/^[a-zA-Z0-9_]+$/', $table_name)) {
			$table_name = '';
		}
		if (C('HTML_CACHE_ON') == true) {
			echo 'document.write(' . get_click($id, $table_name) . ')';
		} else {
			echo get_click($id, $table_name);
		}

	}

	//证码码
	public function verify() {
		$verify = new \Think\Verify();
		$verify->entry(1);
	}

	//online
	public function online() {

		A('Home/Public')->online();
	}

}
