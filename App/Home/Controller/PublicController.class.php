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
 * @Last Modified time: 2017-11-26 10:12:46
 */
namespace Home\Controller;
use Common\Api\SecurityApi;

class PublicController extends HomeCommonController {

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

		if (!IS_POST) {
			exit();
		}

		$furl = I('furl', '', 'htmlspecialchars,trim');
		if (empty($furl) || strpos($furl, 'register') || strpos($furl, 'login') || strpos($furl, 'logout') || strpos($furl, 'activate') || strpos($furl, 'sendActivate')) {
			$furl = U(MODULE_NAME . '/Member/index');

		}

		$email = I('email', '', 'htmlspecialchars,trim');
		$phone_flag = 0; //邮箱为0，手机为1
		$password = I('password', '');

		$verify = I('vcode', '', 'htmlspecialchars,trim');
		if (C('CFG_VERIFY_LOGIN') == 1 && !check_verify($verify)) {
			$this->error('验证码不正确');
		}

		if ($email == '') {
			$this->error('请输入帐号！', '', array('input' => 'email')); //支持ajax,$this->error(info,url,array);
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !check_phone($email)) {
			$this->error('账号必须为邮箱地址或手机号码！', '', array('input' => 'email')); //支持ajax,$this->error(info,url,array);
		}

		if (check_phone($email)) {
			$phone_flag = 1;
		}

		if (strlen($password) < 4 || strlen($password) > 20) {
			$this->error('密码必须是4-20位的字符！', '', array('input' => 'password'));
		}

		//检测IP黑白名单
		$ip = get_client_ip();
		$stop = check_ip($ip);
		if (!$stop['status']) {
			$this->error('登录失败，原因：' . $stop['info']);
		}

		$where = array('email' => $email);
		if ($phone_flag) {
			$where = array('phone' => $email);
		}

		$user = M('member')->where($where)->find();

		if (!$user || ($user['password'] != get_password($password, $user['encrypt']))) {
			$this->error('账号或密码错误', '', array('input' => 'password'));
		}

		if ($user['is_lock']) {
			$this->error('用户被锁定！', '', array('input' => ''));
		}
		//更新数据库的参数
		$data = array('id' => $user['id'], //保存时会自动为此ID的更新
			'login_time' => date('Y-m-d H:i:s'),
			'login_ip' => get_client_ip(),
			'login_num' => $user['login_num'] + 1,

		);
		//更新数据库
		M('member')->save($data);

		$uf = $user['id'] . '.' . md5($user['password']) . '.' . get_random(6); //检测因子
		session('uid', $user['id']);

		set_cookie(array('name' => 'uid', 'value' => $user['id']));
		set_cookie(array('name' => 'email', 'value' => $user['email']));
		set_cookie(array('name' => 'phone', 'value' => $user['phone']));
		set_cookie(array('name' => 'nickname', 'value' => $user['nickname']));
		set_cookie(array('name' => 'group_id', 'value' => $user['group_id'])); //20140801
		set_cookie(array('name' => 'login_time', 'value' => $user['login_time']));
		set_cookie(array('name' => 'login_ip', 'value' => $user['login_ip']));
		set_cookie(array('name' => 'status', 'value' => $user['status'])); //激活状态
		set_cookie(array('name' => 'verifytime', 'value' => time())); //激活状态

		set_cookie(array('name' => 'uf', 'value' => $uf)); //登录标识

		$this->success('登录成功', $furl, array('input' => ''));
	}

	//退出
	public function logout() {

		$furl = $_SERVER['HTTP_REFERER'];

		if (empty($furl) || strpos($furl, 'register') || strpos($furl, 'login') || strpos($furl, 'activate') || strpos($furl, 'sendActivate')) {
			$furl = U(MODULE_NAME . '/Public/login');

		}

		session('uid', null);
		del_cookie(array('name' => 'uid'));
		del_cookie(array('name' => 'uf'));
		del_cookie(array('name' => 'email'));
		del_cookie(array('name' => 'phone'));
		del_cookie(array('name' => 'nickname'));
		del_cookie(array('name' => 'group_id'));
		del_cookie(array('name' => 'login_time'));
		del_cookie(array('name' => 'login_ip'));
		del_cookie(array('name' => 'status'));
		del_cookie(array('name' => 'verifytime'));

		//$this->redirect(MODULE_NAME.'/Public/login');
		$this->success('安全退出', $furl);
	}

	//自动登录后，js验证，更新积分
	public function loginChk() {

		if (!IS_AJAX) {
			exit();
		}
		$nickname = get_cookie('nickname');
		$furl = '';
		if ($this->_loginChk()) {
			$this->success('已登录', $furl, array('nickname' => $nickname));
		} else {
			$this->error('请登录!', '');
		}

	}

	//登录验证
	public function _loginChk() {
		$result = false;

		$nickname = get_cookie('nickname');
		$login_time = get_cookie('login_time');
		$login_ip = get_cookie('login_ip');
		$verifytime = intval(get_cookie('verifytime')); //上次登录时间
		$nickname = empty($nickname) ? $this->email : $nickname;
		$user_id = intval(session('uid'));

		if (empty($this->uid) || ($user_id > 0 && $user_id != $this->uid) || (!filter_var($this->email, FILTER_VALIDATE_EMAIL) && !check_phone($this->phone)) || empty($this->uf) || strpos($this->uf, '.') === false) {
			session('uid', null);
			del_cookie(array('name' => 'uid'));
			del_cookie(array('name' => 'uf'));
			del_cookie(array('name' => 'email'));
			del_cookie(array('name' => 'phone'));
			del_cookie(array('name' => 'nickname'));
			del_cookie(array('name' => 'group_id'));
			del_cookie(array('name' => 'login_time'));
			del_cookie(array('name' => 'login_ip'));
			del_cookie(array('name' => 'status'));
			del_cookie(array('name' => 'verifytime'));
			return $result;
		}

		if (empty($user_id) || date('Y-m-d', $verifytime) != date('Y-m-d', time())) {

			$user_flag = explode('.', $this->uf);
			$login_flag = true;
			if (count($user_flag) != 3) {
				$login_flag = false;
			}

			if ($login_flag) {
				//检测IP黑白名单
				$ip = get_client_ip();
				$stop = check_ip($ip);
				if (!$stop['status']) {
					$login_flag = false;
				}

			}

			$where = array('id' => $this->uid);
			if (!empty($this->phone)) {
				$where['phone'] = $this->phone;
			} elseif (!empty($this->email)) {
				$where['email'] = $this->email;
			} else {
				$login_flag = false;
			}

			if ($login_flag) {
				$user = M('member')->where($where)->find();
				if (!$user || (md5($user['password']) != $user_flag[1]) || $user['is_lock']) {
					$login_flag = false;
				} else {
					session('uid', $user['id']);
				}

			}

			if (!$login_flag) {
				session('uid', null);
				del_cookie(array('name' => 'uid'));
				del_cookie(array('name' => 'uf'));
				del_cookie(array('name' => 'email'));
				del_cookie(array('name' => 'phone'));
				del_cookie(array('name' => 'nickname'));
				del_cookie(array('name' => 'group_id'));
				del_cookie(array('name' => 'login_time'));
				del_cookie(array('name' => 'login_ip'));
				del_cookie(array('name' => 'status'));
				del_cookie(array('name' => 'verifytime'));
				return $result;
			} else {
				set_cookie(array('name' => 'verifytime', 'value' => time())); //本次状态
			}

		}

		$result = true;
		return $result;
	}

	//注册
	public function register() {
		$member_open = C('CFG_MEMBER_OPEN'); //会员中心是否关闭
		if (!$member_open) {
			exit_msg('会员中心已关闭！');
		}

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

		if (!IS_POST) {
			exit(0);
		}

		$verify = I('vcode', '', 'htmlspecialchars,trim');
		if (C('CFG_VERIFY_REGISTER') == 1 && !check_verify($verify)) {
			$this->error('验证码不正确');
		}
		$datas = I('post.', array());
		if (!empty($datas['email']) && !filter_var($datas['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error('电子邮箱格式不正确！');
		}
		if (!empty($datas['phone']) && !check_phone($datas['phone'])) {
			$this->error('手机号码格式不正确！');
		}
		if (empty($datas['email']) && empty($datas['phone'])) {
			$this->error('手机、邮箱必须要填写一个！');
		}
		if (empty($datas['nickname'])) {
			$this->error('昵称不能为空！');
		}
		if (strlen($datas['password']) < 4 || strlen($datas['password']) > 20) {
			$this->error('密码必须是4-20位的字符！', '', array('input' => 'password'));
		}
		if ($datas['password'] != $datas['rpassword']) {
			$this->error('两次密码不一致！', '', array('input' => 'password'));
		}

		//检测IP黑白名单
		$ip = get_client_ip();
		$stop = check_ip($ip);
		if (!$stop['status']) {
			$this->error('注册失败，原因：' . $stop['info']);
		}

		if (!empty($datas['email'])) {
			$ret = M('member')->where(array('email' => $datas['email']))->getField('id');
			if ($ret) {
				$this->error('邮箱已经存在！');
			}
		}
		if (!empty($datas['phone'])) {
			$ret = M('member')->where(array('phone' => $datas['phone']))->getField('id');
			if ($ret) {
				$this->error('手机号码已经存在！');
			}
		}

		$datas['nickname'] = trim($datas['nickname']);
		$notallowname = explode(',', C('CFG_MEMBER_NOTALLOW'));
		if (in_array($datas['nickname'], $notallowname)) {
			$this->error('此昵称系统禁用，请重新更换一个！');
		}

		$data = array();

		//判断后台是否开始邮件验证
		$data['group_id'] = 2; //注册会员
		/*
	        $mGroup = M('membergroup')->Field('id')->find();
	        if ($mGroup) {
	        $data['group_id'] = $mGroup['id'];
	        }*/

		if (!empty($datas['email'])) {
			$data['email'] = $datas['email'];
		} else {
			$data['email'] = '';
		}
		if (!empty($datas['phone'])) {
			$data['phone'] = $datas['phone'];
		}

		$data['nickname'] = $datas['nickname'];
		//代替自动完成
		$data['reg_time'] = date('Y-m-d H:i:s');
		$passwordinfo = get_password($datas['password']);
		$data['password'] = $passwordinfo['password'];
		$data['encrypt'] = $passwordinfo['encrypt'];

		if ($id = M('member')->add($data)) {
			$msg = '注册会员成功<br/>';

			if (C('CFG_MEMBER_VERIFYEMAIL')) {
				// SecurityApi::setUserId($id);
				// $ret = SecurityApi::sendActivateEmail($email);
				// if ($ret['status']) {
				// 	$msg .= '验证邮件已发送，请尽快查收邮件，激活该帐号';
				// } else {

				// 	$msg .= '验证邮件发送失败，原因：' . $ret['status'];
				// }
			}

			$this->success($msg, U(MODULE_NAME . '/Public/login'));
		} else {
			$this->error('注册失败');
		}

	}

	/**
	 * 激活并绑定邮箱
	 */
	public function activateEmail($email, $code) {
		header("Content-type: text/html; charset=utf-8");
		if (empty($email) || empty($code)) {
			$this->error('参数错误');
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->error('邮箱格式不正确');
		}
		$code_id = SecurityApi::checkCode($email, $code);
		if (!$code_id) {
			$this->error('激活失败或已过期');
		}
		//必须验证用户
		$uid = M('ActiveCode')->where(array('id' => $code_id))->getField('user_id'); //获取用户id
		if (empty($uid)) {
			$this->error('激活失败,用户不存在');
		}
		$result = M('Member')->save(array('id' => $uid, 'email' => $email, 'status' => 1));
		if ($result !== false) {
			SecurityApi::activateCode($code_id); //设置已激活
			//如果登录设置cookie值
			$_old_email = get_cookie('email');
			if (empty($_old_email)) {
				set_cookie(array('name' => 'email', 'value' => $user['email']));
			}

			$this->success('激活操作成功，请重新登录！', U(MODULE_NAME . '/Public/login'));
		} else {
			exit('激活绑定邮箱失败，请重试');
		}

	}

	/**
	 * 发送验证码
	 * @param  string  $address 手机|或邮箱
	 * @param  integer $type    类型|0手机，1邮箱
	 * @param  boolean $way     验证码对应场景：register|注册,login|登录验证,getpwd|找回密码,common|公用
	 * @return json
	 */
	public function sendCode($address, $type = 0, $way = 'common') {

		$data = array('status' => 0, 'info' => 'error');

		if (!IS_POST || !IS_AJAX) {
			$this->ajaxReturn($data); //可以直接使用error返回,自动识别ajax
		}

		//手机验证码
		if ($type == 0) {
			//手机号码不正确
			if (!check_phone($address)) {
				$data['status'] = 0;
				$data['info'] = '手机号码不正确';
				$this->ajaxReturn($data);
			}
			$where = array('phone' => $address);
			//注册时验证
			if ($way == 'register' && M('Member')->where($where)->find()) {
				$data['status'] = 0;
				$data['info'] = '手机号码已经存在';
				$this->ajaxReturn($data);
			}

		} else {
			if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
				$data['status'] = 0;
				$data['info'] = '邮箱格式不正确';
				$this->ajaxReturn($data);
			}
			//注册时验证
			$where = array('email' => $address);
			if ($way == 'register' && M('Member')->where($where)->find()) {
				$data['status'] = 0;
				$data['info'] = '邮箱已经存在';
				$this->ajaxReturn($data);
			}
		}
		//检测IP黑白名单
		$ip = get_client_ip();
		$stop = check_ip($ip);
		if (!$stop['status']) {
			$data['status'] = 0;
			$data['info'] = '发送失败，原因：' . $stop['info'];
			$this->ajaxReturn($data);
		}

		SecurityApi::setUserId($this->uid); //指定uid
		$ret = SecurityApi::sendCode($address, $way, $type);
		$data['status'] = $ret['status'];
		$data['info'] = $ret['status'] ? '发送成功,请查收' : $ret['info']; //成功返回的是验证码，所以不能直接赋值

		$this->ajaxReturn($data);

	}

	/**
	 * 发送验证码--带图片验证码
	 * @param  string  $address 手机|或邮箱
	 * @param  integer $type    类型|0手机，1邮箱
	 * @param  boolean $way     验证码对应场景：register|注册,login|登录验证,getpwd|找回密码,common|公用
	 * @return json
	 */
	public function sendCodeByValidate($address, $verifycode = '', $type = 0, $way = 'common') {

		$data = array('status' => 0, 'info' => 'error');
		if (!IS_POST || !IS_AJAX) {
			$this->ajaxReturn($data); //可以直接使用error返回,自动识别ajax
		}

		if (strlen($verifycode) < 4) {
			$data['info'] = '图片验证码不正确' . $verifycode;
			$this->ajaxReturn($data);
		}

		if (!check_verify($verifycode, 'verifySendCode')) {
			$data['info'] = '图片验证码不正确!';
			$this->ajaxReturn($data);
		}

		//手机验证码
		if ($type == 0) {
			//手机号码不正确
			if (!check_phone($address)) {
				$data['status'] = 0;
				$data['info'] = '手机号码不正确';
				$this->ajaxReturn($data);
			}
			$where = array('phone' => $address);
			//注册时验证
			if ($way == 'register' && M('Member')->where($where)->find()) {
				$data['status'] = 0;
				$data['info'] = '手机号码已经存在';
				$this->ajaxReturn($data);
			}

		} else {
			if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
				$data['status'] = 0;
				$data['info'] = '邮箱格式不正确';
				$this->ajaxReturn($data);
			}
			//注册时验证
			$where = array('email' => $address);
			if ($way == 'register' && M('Member')->where($where)->find()) {
				$data['status'] = 0;
				$data['info'] = '邮箱已经存在';
				$this->ajaxReturn($data);
			}
		}
		//检测IP黑白名单
		$ip = get_client_ip();
		$stop = check_ip($ip);
		if (!$stop['status']) {
			$data['status'] = 0;
			$data['info'] = '发送失败，原因：' . $stop['info'];
			$this->ajaxReturn($data);
		}
		SecurityApi::setUserId($this->uid); //指定uid
		$ret = SecurityApi::sendCode($address, $way, $type);
		$data['status'] = $ret['status'];
		$data['info'] = $ret['status'] ? '发送成功,请查收' : $ret['info']; //成功返回的是验证码，所以不能直接赋值

		$this->ajaxReturn($data);

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

		$mode = get_cfg_value('ONLINE_CFG_MODE');

		if ($mode != 1) {
			return '';
		}

		$style = get_cfg_value('ONLINE_CFG_STYLE');
		$style = empty($style) ? 'blue' : $style;
		$qq = get_cfg_value('ONLINE_CFG_QQ');
		$wangwang = get_cfg_value('ONLINE_CFG_WANGWANG');
		$phone = get_cfg_value('ONLINE_CFG_PHONE');
		$css_view = 'xyh-online-view';
		$style_view = 'display:block;';
		$style_panel = 'visibility:hidden;'; //panel style

		if (empty($qq)) {
			$qq = array();
		}

		if (empty($wangwang)) {
			$wangwang = array();
		}
		$qq_param = get_cfg_value('ONLINE_CFG_QQ_PARAM');
		$wangwang_param = get_cfg_value('ONLINE_CFG_WANGWANG_PARAM');
		//位置
		$_h_margin = get_cfg_value('ONLINE_CFG_H') == 2 ? 0 : get_cfg_value('ONLINE_CFG_H_MARGIN'); //水平
		$_v_margin = get_cfg_value('ONLINE_CFG_V') == 2 ? 0 : get_cfg_value('ONLINE_CFG_V_MARGIN');
		$_divM = 0;
		//页面中间
		if (get_cfg_value('ONLINE_CFG_H') == 2 && get_cfg_value('ONLINE_CFG_V') == 2) {
			$css_view = 'xyh-online-view xyh-online-align-middle';
		} elseif (get_cfg_value('ONLINE_CFG_H') == 2) {
			if (get_cfg_value('ONLINE_CFG_V') == 0) {
				$css_view = 'xyh-online-view xyh-online-align-top';
				$style_view .= 'top:' . $_v_margin . 'px;';
			} else if (get_cfg_value('ONLINE_CFG_V') == 1) {
				$css_view = 'xyh-online-view xyh-online-align-bottom';
				$style_view .= 'bottom:' . $_v_margin . 'px;';
			}

		} elseif (get_cfg_value('ONLINE_CFG_V') == 2) {
			if (get_cfg_value('ONLINE_CFG_H') == 0) {
				$css_view = 'xyh-online-view xyh-online-align-left';
				$style_view .= 'left:' . $_h_margin . 'px;';
			} else if (get_cfg_value('ONLINE_CFG_H') == 1) {
				$css_view = 'xyh-online-view';
				$style_view .= 'right:' . $_h_margin . 'px;';
			}
		} else if (get_cfg_value('ONLINE_CFG_H') == 0) {
			$css_view = 'xyh-online-view xyh-online-align-left';
			$style_view .= 'left:' . $_h_margin . 'px;';
			if (get_cfg_value('ONLINE_CFG_V') == 0) {
				$style_view .= 'top:' . $_v_margin . 'px; margin-top:0;';
			} else if (get_cfg_value('ONLINE_CFG_V') == 1) {
				$style_view .= 'bottom:' . $_v_margin . 'px; margin-top:0;top:auto;';
				$style_panel .= 'bottom: 0; top:auto;';
			}

		} else if (get_cfg_value('ONLINE_CFG_H') == 1) {
			$css_view = 'xyh-online-view';
			$style_view .= 'right:' . $_h_margin . 'px;';
			if (get_cfg_value('ONLINE_CFG_V') == 0) {
				$style_view .= 'top:' . $_v_margin . 'px; margin-top:0; top:auto;';
			} else if (get_cfg_value('ONLINE_CFG_V') == 1) {
				$style_view .= 'bottom:' . $_v_margin . 'px; margin-top:0;top:auto;';
				$style_panel .= 'bottom: 0; top:auto;';
			}
		}

		$js_path = __ROOT__ . '/Data';

		$str = <<<str
		if(typeof jQuery != 'undefined'){
			$(function(){
				$('#XYHOnlineView .xyh-online-tool').mouseover(function(event) {
					$('#XYHOnlineView .xyh-online-panel').css('visibility', 'visible');
				})
				$('#XYHOnlineView').mouseleave(function(event) {
					$('#XYHOnlineView .xyh-online-panel').css('visibility', 'hidden');
				});
				$('#XYHOnlineView .xyh-online-panel-close').click(function(event) {
					$('#XYHOnlineView .xyh-online-panel').css('visibility', 'hidden');
				});
			});

		} else {
			console.log('没有jquery，在线客服开启失败!')
		}


	document.write('<link href="{$js_path}/static/js_plugins/online/{$style}.css?2017" rel="stylesheet" type="text/css" />');
	document.write('<div id="XYHOnlineView" class="{$css_view}" style="{$style_view}">');
	document.write('<div class="xyh-online-box">');
	document.write('<div class="xyh-online-tool"><span>咨询与建议</span></div>');
	document.write('<div class="xyh-online-panel" style="{$style_panel}">');
	document.write('<div class="xyh-online-panel-content">');
	document.write('<div class="xyh-online-panel-close"></div>');
	document.write('<dl>');
	document.write('<dd class="title">马上在线沟通</dd>');
str;
		$str .= "document.write('<dd>');";
		foreach ($qq as $k => $_qq):
			$k = isset($k) ? $k : '点击这里给我发消息';
			$str .= "document.write('" . str_replace(array('[客服号]', '[客服说明]', "\r\n", "'"), array($_qq, $k, '', "\'"), $qq_param) . "');";
		endforeach;

		foreach ($wangwang as $k => $_wangwang):
			$k = isset($k) ? $k : '点击这里给我发消息';
			$str .= "document.write('" . str_replace(array('[客服号]', '[客服说明]', "\r\n", "'"), array($_wangwang, $k, '', "\'"), $wangwang_param) . "');";
		endforeach;

		$str .= "document.write('</dd>');";

		if (get_cfg_value('ONLINE_CFG_PHONE_ON') == 1) {
			$str .= 'document.write(\'<dd class="title">热线电话</dd>\');';
			$str .= 'document.write(\'<dd>\');';
			foreach ($phone as $k => $_phone):
				$str .= 'document.write(\'<span class="xyh-online-item"><em class="xyh-online-ico-tel">&nbsp;</em><a href="tel:' . $_phone . '">' . (empty($k) ? '' : $k . '：') . $_phone . '</a></span>\');';
			endforeach;
			$str .= 'document.write(\'</dd>\');';

		}

		if (get_cfg_value('ONLINE_CFG_GUESTBOOK_ON') == 1) {

			$str .= 'document.write(\'<dd class="title">建议或留言</dd>\');';
			$str .= 'document.write(\'<dd><span class="xyh-online-item"><em class="xyh-online-ico-book">&nbsp;</em><a href="' . U('Guestbook/index') . '" target="_blank">给我们留言</a></span></dd>\');';
		}

		$str .= "document.write('</dl>');";
		$str .= "document.write('</div>');";
		$str .= "document.write('</div>');";
		$str .= "document.write('</div>');";
		$str .= "document.write('</div>');";
		echo $str;

	}

}
