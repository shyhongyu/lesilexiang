<?php
namespace Api\Controller;

use Think\Controller;

class ShowController extends Controller {
	/**
	 * 列表
	 *
	 * @return void
	 */	
	public function index() {
		$id = I('id', 0, 'intval'); 
		$where = ['c.id'=>$id,'b.delete_status '=>0];
		$list = M('SearchAll')
				 ->alias('a')
				 ->field('a.arc_id,c.name as cate_name,c.seo_title,a.title,b.description,b.content,a.litpic,b.publish_time,a.cid')
				 ->join('INNER JOIN __CATEGORY__ c ON c.id = a.cid')
				 ->join('INNER JOIN __ARTICLE__ b ON b.id = a.arc_id')
				 ->where($where)
				 ->order('b.pubLish_time DESC')
				 ->select();	
		echo json_encode(['state'=>200,'msg'=>'请求成功','data'=>$list]);	
	}


	 /**
	 * 导航
	 *
	 * @return void
	 */
	public function nav() {
		$cid   = I('id', 0, 'intval');
		$cate = D('CategoryView')->field('id,name,seo_title,cat_pic,content')->where('pid = '.$cid)->order('category.sort,category.id')->select();
		echo json_encode(['state'=>200,'msg'=>'请求成功','data'=>$cate]);
	}
	/**
	 * 首页banner
	 *
	 * @return void
	 */
	public function banner(){
		
		$banner = M('abc_detail')->field('id,content,url')->where("status = 1")->select();
		echo json_encode(['state'=>200,'msg'=>'请求成功','data'=>$banner]); 
	}

	/**
	 * 详情页
	 * 
	 */
	public function detail(){
		$id = I('cid', 0, 'intval');//栏目id
		$arc_id = I('arc_id', 0, 'intval');//文章id
		$data = M('article')
			->field('id,title,publish_time,litpic,content')
			->where("id = $arc_id")
			->find();
 		//上一篇
		$where['id']=array('lt',$arc_id);//上一篇ID应该小于当前接收到的ID
		$where['cid']=array('eq',$id);//分类ID
		$front = M('article')->field('id,title,cid')->where($where)->order('id desc')->limit('1')->find();

		//下一篇
		$next['id']=array('gt',$arc_id);//下一篇ID应该大于当前接收到的ID
		$next['cid']=array('eq',$id);//分类ID
		$after = M('article')
				->field('id,title,cid')
				->where($next)
				->order('id asc')
				->limit('1')->find();
 		echo json_encode(['state'=>200,'msg'=>'请求成功','data'=>$data,'front'=>$front,'after'=>$after]); 	}
	/**
	 * 添加
	 */
	public function OnlineStudy(){
			$type = I('type', 0, 'intval');
			$tel = I('tel','', 'htmlspecialchars,rtrim');
			$username = I('username','', 'htmlspecialchars,rtrim');
			$email = I('email','', 'htmlspecialchars,rtrim');
			$weixin = I('weixin','', 'htmlspecialchars,rtrim');
			$content = I('content','', 'htmlspecialchars,rtrim');
			$post_time = date('Y-m-d H:i:s');
			$data = array(
				'tel'=>$tel,
				'username'=>$username,
				'email'=>$email,
				'weixin'=>$weixin,
				'content'=>$content,
				'post_time'=>$post_time,
				'type'=>$type
				);
	    	$res  = M('guestbook')->add($data);
			echo json_encode(['state'=>200,'msg'=>'留言成功','data'=>$res]);
	}
	/**
	 * 顶部图片
	 *
	 * @return void
	 */
	public function pic(){
		$id = I('id',0,'intval');
		$data = M('category')->field('id,cat_pic')->where("id = $id")->find();
		echo json_encode(['state'=>200,'msg'=>'请求成功','data'=>$data]);

	}
	/**
	 * 搜索展示
	 */
	public function search(){

	}
	/**
	 * 课程体系
	 */
	public function course(){
		$pid = I('pid',0,'intval');
		$data = M('category')->order('sort,id')->select();
		$list = $this->recursion($data,$pid);
		echo json_encode(['state'=>200,'msg'=>'请求成功','data'=>$list]);
	}

	public function recursion($arr,$pid=0){
        $list = [];
        foreach ($arr as $v){
            if ($v['pid'] == $pid){
                    $v['son'] = $this->recursion($arr,$v['id']);
               		 $list[] = $v;
           }
       }
        return $list;
	}



   
	
}
