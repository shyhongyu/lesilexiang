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
 * @Last Modified time: 2017-10-18 17:54:18
 */
namespace Manage\Controller;

class BadIpController extends CommonController {

	public function index() {

		$keyword = I('keyword', '', 'htmlspecialchars,trim'); //关键字

		$where = array();
		if (!empty($keyword)) {
			$where['ip'] = array('LIKE', "%{$keyword}%");
		}

		$count = M('BadIp')->where($where)->count();
		$page = new \Common\Lib\Page($count, 10);
		$page->rollPage = 7;
		$page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
		$limit = $page->firstRow . ',' . $page->listRows;
		$list = M('BadIp')->where($where)->order('id desc')->limit($limit)->select();

		$this->assign('page', $page->show());
		$this->assign('vlist', $list);
		$this->assign('type', 'IP黑名单列表');
		$this->assign('keyword', $keyword);

		$this->display();
	}
	//添加
	public function add() {
		if (IS_POST) {
			$this->addPost();
			exit();
		}
		$this->display();
	}

	//
	public function addPost() {

		//M验证
		$data = I('post.');

		$data['forever_flag'] = I('forever_flag', 'intval');
		$data['status'] = I('status', 'intval');
		$data['expire_time'] = I('expire_time', '');
		$data['update_time'] = date('Y-m-d H:i:s');
		if ($data['forever_flag']) {
			unset($data['expire_time']);
		} elseif (empty($data['expire_time'])) {
			$this->error('临时IP必须指定结束日期');
		}

		$validate = array(
			array('ip', 'require', 'IP不能为空！'),
			array('ip', '', 'IP已经存在！', 0, 'unique', 1),
		);

		$db = M('BadIp');
		if (!$db->validate($validate)->create()) {
			$this->error($db->getError());
		}

		if ($id = $db->add($data)) {
			$this->success('添加成功', U('index'));
		} else {
			$this->error('添加失败');
		}
	}

	//编辑文章
	public function edit() {
		//当前控制器名称
		$id = I('id', 0, 'intval');
		if (IS_POST) {
			$this->editPost();
			exit();
		}

		$this->assign('vo', M('BadIp')->find($id));
		$this->display();
	}

	//修改文章处理
	public function editPost() {

		//M验证
		$data = I('post.');
		$id = $data['id'] = I('id', 0, 'intval');
		$data['forever_flag'] = I('forever_flag', 'intval');
		$data['status'] = I('status', 'intval');
		$data['expire_time'] = I('expire_time', '');
		$data['update_time'] = date('Y-m-d H:i:s');

		if ($data['forever_flag']) {
			unset($data['expire_time']);
		} elseif (empty($data['expire_time'])) {
			$this->error('临时IP必须指定结束日期');
		}

		if (empty($data['ip'])) {
			$this->error('IP不能为空！');
		}
		if (M('BadIp')->where(array('ip' => $data['ip'], 'id' => array('NEQ', $data['id'])))->find()) {
			$this->error('IP已经存在！');
		}

		$db = M('BadIp');
		if (!$db->validate($validate)->create()) {
			$this->error($db->getError());
		}

		if (false !== M('BadIp')->save($data)) {
			//attachment index 图片入库

			$this->success('修改成功', U('index'));
		} else {

			$this->error('修改失败');
		}

	}

	public function setStatus() {
		$data = array();
		$data['id'] = I('id', 0, 'intval');
		$data['status'] = I('status', 0, 'intval');
		$data['status'] = $data['status'] == 1 ? 1 : 0;
		if (empty($data['id'])) {
			$this->error('参数错误');
		}

		if (false !== M('BadIp')->save($data)) {
			$this->success('状态更新成功', U('index'));
		} else {
			$this->error('状态更新失败');
		}

	}

	//彻底删除
	public function del() {

		$id = I('id', 0, 'intval');
		$batchFlag = I('get.batchFlag', 0, 'intval');
		//批量删除
		if ($batchFlag) {
			$this->delBatch();
			return;
		}

		if (M('BadIp')->delete($id)) {
			$this->success('彻底删除成功', U('BadIp/index'));
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

		if (M('BadIp')->where($where)->delete()) {
			$this->success('彻底删除成功', U('BadIp/index'));
		} else {
			$this->error('彻底删除失败');
		}
	}

}
