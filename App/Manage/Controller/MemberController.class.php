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
 * @Last Modified time: 2017-10-06 22:48:29
 */
namespace Manage\Controller;

class MemberController extends CommonController {

	public function index() {

		$keyword = I('keyword', '', 'htmlspecialchars,trim'); //关键字
		$where   = array('member.id' => array('gt', 0));
		if (!empty($keyword)) {
			if (strpos($keyword, '@')) {
				$where['member.email'] = $keyword;
			} elseif (check_phone($keyword)) {
				$where['member.phone'] = $keyword;
			} else {
				$where['member.nickname'] = $keyword;
			}
		}

		$count = D('MemberView')->where($where)->count();

		$page           = new \Common\Lib\Page($count, 10);
		$page->rollPage = 7;
		$page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
		$limit = $page->firstRow . ',' . $page->listRows;
		$list  = D('MemberView')->nofield('password,encrypt')->where($where)->order('member.id desc')->limit($limit)->select();

		$this->assign('page', $page->show());
		$this->assign('vlist', $list);
		$this->assign('keyword', $keyword);
		$this->assign('type', '会员列表');
		$this->display();
	}
	//添加
	public function add() {
		//当前控制器名称
		$actionName = strtolower(CONTROLLER_NAME);
		if (IS_POST) {
			$this->addPost();
			exit();
		}
		$list = M('MemberGroup')->where(array('id' => array('gt', 1)))->select();
		$this->assign('vlist', $list);
		$this->display();
	}

	//
	public function addPost() {
		$data = I('post.');

		if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error('电子邮箱格式不正确！');
		}
		if (!empty($data['phone']) && !check_phone($data['phone'])) {
			$this->error('手机号码格式不正确！');
		}
		if (empty($data['email']) && empty($data['phone'])) {
			$this->error('手机、邮箱必须要填写一个！');
		}
		if (empty($data['nickname'])) {
			$this->error('昵称不能为空！');
		}
		if (empty($data['group_id'])) {
			$this->error('请选择会员组！');
		}
		if (strlen($data['password']) < 4 || strlen($data['password']) > 20) {
			$this->error('密码必须是4-20位的字符！');
		}
		if (!empty($data['email'])) {
			$ret = M('member')->where(array('email' => $data['email']))->getField('id');
			if ($ret) {
				$this->error('邮箱已经存在！');
			}
		}
		if (!empty($data['phone'])) {
			$ret = M('member')->where(array('phone' => $data['phone']))->getField('id');
			if ($ret) {
				$this->error('手机号码已经存在！');
			}
		}

		$passwordinfo     = get_password($data['password']);
		$data['reg_time'] = date('Y-m-d H:i:s');
		$data['password'] = $passwordinfo['password'];
		$data['encrypt']  = $passwordinfo['encrypt'];

		if ($id = M('member')->add($data)) {
			$this->success('添加成功', U('Member/index'));
		} else {
			$this->error('添加失败');
		}
	}

	//编辑
	public function edit() {
		//当前控制器名称
		$id         = I('id', 0, 'intval');
		$actionName = strtolower(CONTROLLER_NAME);

		if (IS_POST) {
			$this->editPost();
			exit();
		}

		$list = M('MemberGroup')->where(array('id' => array('gt', 1)))->select();
		$vo   = M($actionName)->find($id);

		$this->assign('vlist', $list);
		$this->assign('vo', $vo);
		$this->display();
	}

	//修改文章处理
	public function editPost() {

		$data          = I('post.');
		$id            = $data['id']            = I('id', 0, 'intval');
		$data['email'] = trim($data['email']);

		if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error('电子邮箱格式不正确！');
		}
		if (!empty($data['phone']) && !check_phone($data['phone'])) {
			$this->error('手机号码格式不正确！');
		}
		if (empty($data['email']) && empty($data['phone'])) {
			$this->error('手机、邮箱必须要填写一个！');
		}
		if (empty($data['nickname'])) {
			$this->error('昵称不能为空！');
		}
		if (empty($data['group_id'])) {
			$this->error('请选择会员组！');
		}
		if (!empty($data['email'])) {
			$ret = M('member')->where(array('email' => $data['email'], 'id' => array('neq', $id)))->getField('id');
			if ($ret) {
				$this->error('邮箱已经存在！');
			}
		}
		if (!empty($data['phone'])) {
			$ret = M('member')->where(array('phone' => $data['phone'], 'id' => array('neq', $id)))->getField('id');
			if ($ret) {
				$this->error('手机号码已经存在！');
			}
		}

		$data['group_id'] = I('group_id', 0, 'intval');
		$data['is_lock']  = I('is_lock', 0, 'intval');

		if (!empty($data['password'])) {
			if (strlen($data['password']) < 4 || strlen($data['password']) > 20) {
				$this->error('密码必须是4-20位的字符！');
			}

			$passwordinfo     = I('password', '', 'get_password');
			$data['password'] = $passwordinfo['password'];
			$data['encrypt']  = $passwordinfo['encrypt'];
		} else {
			unset($data['password']); //删除密码，防止被添加
		}

		if (false !== M('member')->save($data)) {
			$this->success('修改成功', U('Member/index'));
		} else {

			$this->error('修改失败');
		}

	}

	//用户资料
	public function person() {
		//当前控制器名称
		$id         = I('id', 0, 'intval');
		$actionName = strtolower(CONTROLLER_NAME);
		if (empty($id)) {
			$this->error('参数错误');
		}

		if (IS_POST) {
			$data['realname'] = I('realname', '', 'htmlspecialchars,trim');
			$data['birthday'] = I('birthday', '0000-00-00');
			$data['sex']      = I('sex', 0, 'intval');
			$data['address']  = I('address', '');
			$data['tel']      = I('tel', '');
			$data['mobile']   = I('mobile', '');
			$data['qq']       = I('qq', '');
			$data['maxim']    = I('maxim', '');

			$data['user_id']     = $id;
			$data['update_time'] = date('Y-m-d H:i:s');
			if (empty($data['realname'])) {
				$this->error('请输入姓名！');
			}
			$new = M('MemberDetail')->where(array('user_id' => $id))->find();

			$result = true;
			if (!$new) {
				$result = M('MemberDetail')->add($data);
			} else {
				$result = M('MemberDetail')->save($data);
			}

			if (false !== $result) {
				$this->success('修改用户资料成功', U('Member/index'));
			} else {

				$this->error('修改用户资料失败');
			}
			exit();
		}

		$userdetail = M('MemberDetail')->where(array('user_id' => $id))->find();
		if (!$userdetail) {
			$userdetail = array(
				'user_id'  => $id,
				'realname' => '',
				'sex'      => 0,
				'birthday' => '1990-1-1',
				'address'  => '',
				'tel'      => '',
				'mobile'   => '',
				'qq'       => '',
				'maxim'    => '',
			);
			$userdetail['new'] = 1;
		} else {
			$userdetail['new'] = 0;
		}

		$this->assign('vo', $userdetail);
		$this->assign('type', '用户基本资料');
		$this->display();
	}

	public function setLock() {
		$data            = array();
		$data['id']      = I('id', 0, 'intval');
		$data['is_lock'] = I('is_lock', 0, 'intval');
		$data['is_lock'] = $data['is_lock'] == 1 ? 1 : 0;
		if (empty($data['id'])) {
			$this->error('参数错误');
		}

		if (false !== M('member')->save($data)) {
			$this->success('用户状态更新成功', U('index'));
		} else {
			$this->error('用户状态更新失败');
		}

	}

	//彻底删除
	public function del() {

		$id        = I('id', 0, 'intval');
		$batchFlag = I('get.batchFlag', 0, 'intval');
		//批量删除
		if ($batchFlag) {
			$this->delBatch();
			return;
		}

		if (M('member')->delete($id)) {
			M('MemberDetail')->delete($id);
			$this->success('彻底删除成功', U('Member/index'));
		} else {
			$this->error('彻底删除失败');
		}
	}

	//批量彻底删除
	public function delBatch() {

		$idArr = I('key', 0, 'intval');
		if (!is_array($idArr)) {
			$this->error('请选择要彻底删除的项');
		}
		$where = array('id' => array('in', $idArr));

		if (M('member')->where($where)->delete()) {
			M('MemberDetail')->where(array('user_id' => array('in', $idArr)))->delete();
			$this->success('彻底删除成功', U('Member/index'));
		} else {
			$this->error('彻底删除失败');
		}
	}

}
