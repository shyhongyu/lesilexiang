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
 * @Date:   2019-10-01 10:00:00
 * @Last Modified by:   gosea
 * @Last Modified time: 2019-10-01 10:00:00
 */
namespace Manage\Controller;

class AdminLogController extends CommonController {

	public function index() {

		$keyword = I('keyword', '', 'htmlspecialchars,trim'); //关键字

		$where = array();
		if (!empty($keyword)) {
			$where['title'] = array('LIKE', "%{$keyword}%");
		}

		$count = M('AdminLog')->where($where)->count();
		$page = new \Common\Lib\Page($count, 10);
		$page->rollPage = 7;
		$page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
		$limit = $page->firstRow . ',' . $page->listRows;
		$list = M('AdminLog')->where($where)->order('id desc')->limit($limit)->select();

		$this->assign('page', $page->show());
		$this->assign('vlist', $list);
		$this->assign('type', '管理员日志列表');
		$this->assign('keyword', $keyword);

		$this->display();
	}

	//详情
	public function detail() {
		//当前控制器名称
		$id = I('id', 0, 'intval');
		if (IS_POST) {
			$this->editPost();
			exit();
		}
		$info = M('AdminLog')->find($id);
		if (empty($info)) {
			p('记录不存在');
			exit();
		}

		$this->assign('vo', $info);
		$this->display();
	}

	//彻底删除
	public function del() {

		$before_time = date('Y-m-d 00:00:00', strtotime('-3 month'));

		$where = array('operate_time' => array('lt', $before_time));

		if (false !== M('AdminLog')->where($where)->delete()) {
			$this->success('删除成功', U('index'));
		} else {
			$this->error('删除失败');
		}
	}

}
