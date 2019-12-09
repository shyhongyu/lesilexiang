<?php
namespace Api\Controller;

use Think\Controller;

class ShowController extends Controller {
	/**
	 * 首页
	 *
	 * @return void
	 */	
	public function index() {
		$id = $id = I('id', 0, 'intval'); 
		$where = ['cid'=>$id,'status'=>1];
		$list = M('SearchAll')
			->alias('a')
			->field('a.arc_id,a.title,a.litpic,a.description,a.cid,a.audit_status,c.name as cate_name,c.status,c.seo_title')
			->join('INNER JOIN __CATEGORY__ c ON c.id = a.cid')
			->where($where)
			->select();
		echo json_encode(['state'=>200,'msg'=>'请求成功','data'=>$list]); 

		
	}
	/**
	 * 首页banner
	 *
	 * @return void
	 */
	public function banner(){
		
		$banner = M('abc_detail')->field('content,url')->select();
		echo json_encode(['state'=>200,'msg'=>'请求成功','data'=>$banner]); 

	}

	
}
