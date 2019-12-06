<?php
/**
 * @Description:  验证码接口
 * @Author: gosea
 * @Date:   2015-04-20 15:42:30
 * @Last Modified by:   gosea
 * @Last Modified time: 2017-09-01 10:11:14
 */
namespace Common\Api;

class SecurityApi {
	/**
	 * 发送短信验证码
	 * @param  string $phone手机号码
	 * @return boolen
	 */
	/**
	 * 发送验证码[短信|Email]
	 * @param  string  $address 手机号或邮箱地址
	 * @param  boolen $flag    0手机, 1邮箱
	 * @return [type]           [description]
	 * ----------------------------------------------------------------
	 * 使用方法：
	 * use Common\Api\SecurityApi;
	 * //SecurityApi::setUserId(用户ID);//如果是登录用户必须指定，末登录忽略本条
	 * $result = SecurityApi::sendCode('手机号/邮箱', '验证码对应场景', 类型（0手机|1邮箱）);
	 * ----------------------------------------------------------------
	 */
	public static $uid = 0;

	public static function setUserId($uid) {
		self::$uid = intval($uid);
	}
	public static function sendCode($address, $way = 'common', $flag = 0) {
		$result = array(
			'status' => 0, //状态　0:失败,1:成功
			'info' => '', //提示信息
		);
		$send_interval = C('CODE_SEND_INTERVAL'); //间隔时间
		$send_expire = C('CODE_SEND_EXPIRE'); //发送后有效时长
		$web_url = C('CFG_WEBURL');
		$web_name = C('CFG_WEBNAME');

		if (empty($send_expire)) {
			$result['info'] = '未配置验证码有效时长';
			return $result;
		}
		if (empty($send_expire)) {
			$result['info'] = '未配置激活邮件有效时长';
			return $result;
		}
		if (empty($web_url)) {
			$result['info'] = '未配置网站域名';
			return $result;
		}

		//可以增加时间判断，只给一定时间段后，才能再次发送
		$send_last_time = intval(session('send_last_time')); //用session，还是cookies

		if ($send_interval && (time() - $send_last_time) < $send_interval) {
			$result['status'] = 0;
			$result['info'] = $send_interval . '秒后再尝试发送';
			return $result;
		}

		$template_name = 'com_code1_1'; //对应短信模板名称--要转成模板编码(程序自动转)
		$email_template_name = 'com_code1_1'; //邮件模板
		switch ($way) {
		case 'register':
			$template_name = 'reg_code1_1';
			$email_template_name = $template_name;
			break;
		case 'login':
			$template_name = 'login_code1_1';
			$email_template_name = 'com_code1_1';
			break;
		case 'getpwd':
			$template_name = 'getpwd_code1_1';
			$email_template_name = 'com_code1_1';
			break;
		default:
			$template_name = 'com_code1_1';
			$email_template_name = $template_name;
			break;
		}

		//验证码发送
		$code = get_random(6, '1234567890'); //产生数字
		$ret = $flag == 0 ? self::sendSms($address, $template_name, array('code' => $code)) : self::sendMailByTpl($address, $email_template_name, array('code' => $code, 'siteurl' => $web_url, 'sitename' => $web_name));

		if ($ret['status']) {
			// 发送成功
			session('send_last_time', time()); //最后发送时间
			//保存到session,cookie,database
			$data = array(
				'user_id' => self::$uid,
				'type' => $flag, //类型：手机,邮箱
				'code' => $code, //激活码
				'expire' => time() + $send_expire, //有效期
				'address' => $address, //地址
				// 'update_time'    => 0,//激活时间
			);
			$id = M('ActiveCode')->add($data);
			//成功
			if ($id) {
				//设置其他已经发送的验证码为过期
				M('ActiveCode')->where(array('id' => array('NEQ', $id), 'type' => $flag, 'address' => $address, 'update_time' => 0))->setField('expire', 0);
			} else {
				$result['status'] = 0;
				$result['info'] = '发送成功,更新失败';
				return $result;
			}

			$result['status'] = 1;
			$result['info'] = $code;

		} else {
			$result['status'] = 0;
			$result['info'] = $ret['info'];
		}

		return $result;
	}

	/**
	 * 发送激活邮件[非验证码]，一般1~2天激活失效
	 * @param  string  $address 邮箱地址
	 * @return [type]           [description]
	 * ----------------------------------------------------------------
	 * 使用方法：
	 * use Common\Api\SecurityApi;
	 * SecurityApi::setUserId(用户ID);//必须指定用户ID
	 * $result = SecurityApi::sendActivateEmail('邮箱');
	 * ----------------------------------------------------------------
	 */
	public static function sendActivateEmail($address) {
		$result = array(
			'status' => 0, //状态　0:失败,1:成功
			'info' => '', //提示信息
		);
		$flag = 1; //邮箱
		$send_expire = C('ACTIVATE_SEND_EMAIL_EXPIRE'); //发送后有效时长
		$web_url = C('CFG_WEBURL');
		$web_name = C('CFG_WEBNAME');
		if (empty($send_expire)) {
			$result['info'] = '未配置激活邮件有效时长';
			return $result;
		}
		if (empty($web_url)) {
			$result['info'] = '未配置网站域名';
			return $result;
		}

		if (empty(self::$uid)) {
			$result['info'] = '请指定用户Id';
			return $result;
		}

		//验证码发送
		$code = get_randomstr(11); //产生字符串
		$url = rtrim($web_url, '/') . '/index.php?s=/Public/activateEmail&email=' . $address . '&code=' . $code;

		$ret = self::sendMailByTpl($address, 'activate_code1_1', array('sitename' => $web_name, 'aturl' => $url, 'code' => $code));
		if ($ret['status']) {
			// 发送成功

			//保存到session,cookie,database
			$data = array(
				'user_id' => self::$uid,
				'type' => $flag, //类型：邮箱1
				'code' => $code, //激活码
				'expire' => time() + $send_expire, //有效期
				'address' => $address, //地址
				// 'update_time'    => 0,//激活时间
			);
			$id = M('ActiveCode')->add($data);
			//成功
			if ($id) {
				//设置其他已经发送的验证码为过期
				M('ActiveCode')->where(array('id' => array('NEQ', $id), 'type' => $flag, 'address' => $address, 'update_time' => 0))->setField('expire', 0);
			} else {
				$result['status'] = 0;
				$result['info'] = '发送成功,更新数据失败';
				return $result;
			}

			$result['status'] = 1;
			$result['info'] = $code;

		} else {
			$result['status'] = 0;
			$result['info'] = $ret['info'];
		}

		return $result;
	}

	/**
	 * 检测激活码是否正确
	 * @param  string  $address 手机号或邮箱地址
	 * @param  string  $code    激活码
	 * @param  integer $uid     用户id--可选(注册时不要填写)
	 * @return boolen false失败,成功返回对应id
	 */
	public static function checkCode($address, $code, $uid = 0) {
		if (empty($address) || empty($code)) {
			return false;
		}
		$where = array(
			'address' => $address,
			'code' => $code,
			'expire' => array('GT', time()), //有效期
			'update_time' => 0, //未激活过
		);
		if ($uid) {
			$where['user_id'] = $uid;
		}
		$info = M('ActiveCode')->where($where)->find();
		if ($info) {
			//M('ActiveCode')->where(array('id' => $info['id']))->setField('update_time',time());//设置激活过
			return $info['id'];
		} else {
			return false;
		}
	}

	/**
	 * 设置验证码已经激活过
	 * @param  integer $id 验证码id,可根据上面checkCode获取
	 * @return boolen
	 */
	public static function activateCode($id) {
		if (empty($id)) {
			return false;
		}

		return M('ActiveCode')->where(array('id' => $id))->setField('update_time', time()); //设置激活过
	}

	/**
	 * 发送短信[模板方式]
	 * @param  string|array $phone 手机号码[string:一个手机号，array：多个手机号同时发]
	 * @param  string $cid   模板id
	 * @param  array $param 参数数组 array('参数1', '参数2',...)
	 * @return array   		成功|失败，及其原因 array('status' => 0, 'info' => 'xxx');//status 0失败, 1成功
	 * ----------------------------------------------------------------
	 * 使用方法：
	 * use Common\Api\SecurityApi;
	 * $result = SecurityApi::sendSms('手机号', '模板id', 参数数组);
	 * ----------------------------------------------------------------
	 */
	public static function sendSms($phone, $cid, $param = null) {

		static $sms;
		if (!isset($sms) || !is_object($sms)) {
			//以后可随机选择合适的短信通道。比如一个不通，切换到另一个
			//$sms = new TySmsApi(); //电信天翼--暂时没开通--
			$sms = new AliSmsApi(); //阿里云
		}
		if (is_array($phone)) {
			$info = '|';
			foreach ($phone as $val) {
				$result = $sms->sendSms($val, $cid, $param);
				$info .= $result['status'] . '|';
			}
			$result['info'] .= $info;
			return $result;
		} else {
			return $sms->sendSms($phone, $cid, $param);
		}

	}

	/**
	 *发送邮件[模板发送]
	 * @param    string   $address       地址
	 * @param  string $cid   模板id
	 * @param  array $param 参数数组 array('%var%' => '参数1', '%var2%' => '参数2',...),%var% 模板中指定的变量名
	 * @return array   	成功|失败，及其原因 array('status' => 0, 'info' => 'xxx');//status 0失败, 1成功
	 * ----------------------------------------------------------------
	 * 使用方法：
	 * use Common\Api\SecurityApi;
	 * $result = SecurityApi::sendMailByTpl('307299635@qq.com', '模板id', 参数数组);
	 * ----------------------------------------------------------------
	 */
	public static function sendMailByTpl($address, $cid, $param = null) {
		$result = array(
			'status' => 0, //状态　0:失败,1:成功
			'info' => '', //提示信息
		);

		if (empty($cid)) {
			$result['info'] = '邮件模板没有指定';
			return $result;
		}
		//读取模板数组
		$email_tpl = include './Data/resource/email_tpl.php';
		if (!is_array($email_tpl)) {
			$result['info'] = '没有设置邮件模板';
			return $result;
		}

		//不存在模板id对应的字符串
		if (!isset($email_tpl[$cid])) {
			$result['info'] = '模板不存在';
			return $result;
		}

		$tpl = $email_tpl[$cid]; //对应的字符串
		//替换模板中的变量
		if (is_array($param)) {
			foreach ($param as $k => $val) {
				$tpl['title'] = str_replace('{$' . $k . '}', $val, $tpl['title']);
				$tpl['txt'] = str_replace('{$' . $k . '}', $val, $tpl['txt']);
			}
		}

		$ret = self::sendMail($address, $tpl['title'], $tpl['txt']);

		if ($ret['status']) {
// 发送成功

			$result['status'] = 1;
			$result['info'] = '邮件发送成功';

		} else {
			$result['status'] = 0;
			$result['info'] = $ret['info'];
		}

		return $result;
	}

	/**
	 *发送邮件
	 * @param    string   $address       地址
	 * @param    string    $title 标题
	 * @param    string    $message 邮件内容
	 * @param    string $attachment 附件列表
	 * @return array   	成功|失败，及其原因 array('status' => 0, 'info' => 'xxx');//status 0失败, 1成功
	 * ----------------------------------------------------------------
	 * 使用方法：
	 * use Common\Api\SecurityApi;
	 * $result = SecurityApi::sendMail('307299635@qq.com', '标题', '内容');
	 * ----------------------------------------------------------------
	 */
	public static function sendMail($address, $title, $message, $attachment = null) {
		$result = array(
			'status' => 0, //状态　0:失败,1:成功
			'info' => '', //提示信息
		);

		if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
			$result['info'] = '邮箱格式不正确';
			return $result;
		}

		Vendor('PHPMailer.class#phpmailer');

		$mail = new \PHPMailer;
		//$mail->Priority = 3;
		// 设置PHPMailer使用SMTP服务器发送Email
		$mail->IsSMTP();
		// 设置邮件的字符编码，若不指定，则为'UTF-8'
		$mail->CharSet = 'UTF-8';
		$mail->SMTPDebug = 0; // 关闭SMTP调试功能
		$mail->SMTPAuth = true; // 启用 SMTP 验证功能
		$mail->SMTPSecure = 'ssl'; // 使用安全协议
		$mail->IsHTML(true); //body is html

		// 设置SMTP服务器。
		$mail->Host = C('CFG_EMAIL_HOST');
		$mail->Port = C('CFG_EMAIL_PORT') ? C('CFG_EMAIL_PORT') : 25; // SMTP服务器的端口号

		// 设置用户名和密码。
		$mail->Username = C('CFG_EMAIL_FROM'); // C('CFG_EMAIL_LOGINNAME');
		$mail->Password = C('CFG_EMAIL_PASSWORD');

		// 设置邮件头的From字段
		$mail->From = C('CFG_EMAIL_FROM');
		// 设置发件人名字
		$mail->FromName = C('CFG_EMAIL_FROM_NAME') ? C('CFG_EMAIL_FROM_NAME') : C('CFG_EMAIL_FROM');

		// 设置邮件标题
		$mail->Subject = $title;
		// 添加收件人地址，可以多次使用来添加多个收件人
		$mail->AddAddress($address);
		// 设置邮件正文
		$mail->Body = $message;
		// 添加附件
		if (is_array($attachment)) {
			foreach ($attachment as $file) {
				is_file($file) && $mail->AddAttachment($file);
			}
		}
		// 发送邮件。
		if ($mail->Send()) {
			//成功
			$result['status'] = 1;
			$result['info'] = '邮件发送成功';
		} else {
			//失败
			$result['status'] = 0;
			$result['info'] = $mail->ErrorInfo;

		}

		return $result;
	}

}