<?php
/**
 * @Description:  阿里云短信验证码发送类
 * @Author: gosea
 * @Date:   2017-08-30 01:01:01
 * @Last Modified by:   gosea
 * @Last Modified time: 2017-08-30 23:48:54
 */
namespace Common\Api;
class AliSmsApi {

	public $error; // 保存错误信息
	private $access_key_id = ''; // Access Key ID
	private $access_key_secret = ''; // Access Access Key Secret
	private $sign_name = ''; // 签名
	private $send_url = ''; // 发送地址
	public function __construct($cofig = array()) {

		$sdk = C('SMS_SDK_ALI'); // 配置参数
		if (empty($sdk['APP_KEY']) || empty($sdk['APP_SECRET']) || empty($sdk['SIGN_NAME']) || empty($sdk['SEND_URL'])) {
			E('请配置您申请的APP_KEY、APP_SECRET、SIGN_NAME和SEND_URL');
		} else {
			$this->access_key_id = $sdk['APP_KEY'];
			$this->access_key_secret = $sdk['APP_SECRET'];
			$this->sign_name = $sdk['SIGN_NAME'];
			$this->send_url = $sdk['SEND_URL'];
		}

	}
	private function percentEncode($string) {
		$string = urlencode($string);
		$string = preg_replace('/\+/', '%20', $string);
		$string = preg_replace('/\*/', '%2A', $string);
		$string = preg_replace('/%7E/', '~', $string);
		return $string;
	}
	/**
	 * 签名
	 *
	 * @param unknown $parameters
	 * @param unknown $access_key_secret
	 * @return string
	 */
	private function computeSignature($parameters, $access_key_secret) {
		ksort($parameters);
		$canonicalizedQueryString = '';
		foreach ($parameters as $key => $value) {
			$canonicalizedQueryString .= '&' . $this->percentEncode($key) . '=' . $this->percentEncode($value);
		}
		$stringToSign = 'GET&%2F&' . $this->percentencode(substr($canonicalizedQueryString, 1));
		$signature = base64_encode(hash_hmac('sha1', $stringToSign, $access_key_secret . '&', true));
		return $signature;
	}
	/**
	 * 发送短信[模板方式]
	 * @param  string $phone 手机号码
	 * @param  string $cid   模板id
	 * @param  array $param 参数数组 array('参数1', '参数2',...)
	 * @return array        成功|失败，及其原因 array('status' => 0, 'info' => 'xxx');//status 0失败, 1成功
	 * ----------------------------------------------------------------
	 * 使用方法：
	 * use Common\Api\AliSmsApi;
	 * $sms = new AliSmsApi();
	 * $result = $sms->sendSms('手机号', '模板id', 参数数组);
	 * ----------------------------------------------------------------
	 */
	public function sendSms($phone, $cid, $param = null) {
		$result = array(
			'status' => 0, //状态　0:失败,1:成功
			'info' => '', //提示信息
		);

		$sub_param = array();
		if (is_array($param)) {
			$sub_param = $param;
		}

		$api_cids = C('SMS_SDK_TPL_ID'); //模板id数组

		//手机号码不正确
		if (!check_phone($phone)) {
			$result['info'] = '手机号码不正确';
			return $result;
		}
		if (empty($cid)) {
			$result['info'] = '短信模板没有指定';
			return $result;
		}
		//不存在模板id对应的字符串
		if (!isset($api_cids[$cid])) {
			$result['info'] = '短信模板不存在';
			return $result;
		}

		$params = array( //此处作了修改
			'SignName' => $this->sign_name,
			'Format' => 'JSON',
			'Version' => '2017-05-25',
			'AccessKeyId' => $this->access_key_id,
			'SignatureVersion' => '1.0',
			'SignatureMethod' => 'HMAC-SHA1',
			'SignatureNonce' => uniqid(),
			'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
			'Action' => 'SendSms',
			'TemplateCode' => $api_cids[$cid],
			'PhoneNumbers' => $phone,
			//'TemplateParam' => '{"code":"' . $verify_code . '"}'
			'TemplateParam' => json_encode($sub_param), //更换为自己的实际模版
		);

		//p($params);die;
		// 计算签名并把签名结果加入请求参数
		$params['Signature'] = $this->computeSignature($params, $this->access_key_secret);
		// 发送请求
		//$url = 'http://dysmsapi.aliyuncs.com/?' . http_build_query($params);
		$url = rtrim($this->send_url, '/') . '/?' . http_build_query($params);

		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		// $res = curl_exec($ch);
		// curl_close($ch);
		//
		/*发送POST请求*/
		$res = $this->curl_get($url);
		// {"Message":"187871270011 invalid mobile number", "RequestId":"115949E6-3131-4410-99ED-FF60DF9753D7": "Code":"isv.MOBILE_NUMBER_ILLEGAL"}
		// {"[Message":"OK", "RequestId":"A3F61874-5AFF-43B5-9801-FD3F217A5334",    "BizId":"252706204107909475^0", "Code":" OK"}

		$res = json_decode($res, true);
		if ($res['Code'] == 'OK') {
			$result['status'] = 1;
			$result['info'] = '短信发送成功';
		} else {
			$result['status'] = 0;
			$result['info'] = '短信发送失败.' . $res['Message'];
		}
		return $result;

	}
	/**
	 * 获取详细错误信息
	 *
	 * @param unknown $status
	 */
	public function getErrorMessage($status) {
		// 阿里云的短信
		// https://api.alidayu.com/doc2/apiDetail?spm=a3142.7629140.1.19.SmdYoA&apiId=25450
		$message = array(
			// 'OK'                              => '请求成功',
			'isp.RAM_PERMISSION_DENY' => 'RAM权限DENY',
			'isv.OUT_OF_SERVICE' => '业务停机',
			'isv.PRODUCT_UN_SUBSCRIPT' => '未开通云通信产品',
			'isv.PRODUCT_UNSUBSCRIBE' => '产品未开通',
			'isv.ACCOUNT_NOT_EXISTS' => '账户不存在',
			'isv.ACCOUNT_ABNORMAL' => '账户异常',
			'isv.SMS_TEMPLATE_ILLEGAL' => '短信模板不合法',
			'isv.SMS_SIGNATURE_ILLEGAL' => '短信签名不合法',
			'isv.INVALID_PARAMETERS' => '参数异常',
			'isp.SYSTEM_ERROR' => '系统错误',
			'isv.MOBILE_NUMBER_ILLEGAL' => '非法手机号',
			'isv.MOBILE_COUNT_OVER_LIMIT' => '手机号码数量超过限制',
			'isv.TEMPLATE_MISSING_PARAMETERS' => '模板缺少变量',
			'isv.BUSINESS_LIMIT_CONTROL' => '业务限流',
			'isv.INVALID_JSON_PARAM' => 'JSON参数不合法，只接受字符串值',
			'isv.BLACK_KEY_CONTROL_LIMIT' => '黑名单管控',
			'isv.PARAM_LENGTH_LIMIT' => '参数超出长度限制',
			'isv.PARAM_NOT_SUPPORT_URL' => '不支持URL',
			'isv.AMOUNT_NOT_ENOUGH' => '账户余额不足',
		);
		if (isset($message[$status])) {
			return $message[$status];
		}
		return $status;
	}

	//curl post
	function curl_post($url = '', $postdata = '', $options = array()) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查false
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在true
		if (!empty($options)) {
			curl_setopt_array($ch, $options);
		}
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	//get
	function curl_get($url = '', $options = array()) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查false
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在true
		if (!empty($options)) {
			curl_setopt_array($ch, $options);
		}
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}