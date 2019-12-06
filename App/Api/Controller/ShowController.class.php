<?php
namespace Api\Controller;

use Think\Controller;

class ShowController extends Controller {
	//方法：index
	public function index() {
		
		//关于乐思乐享
		$about = M('SearchAll')->alias('a')->field('a.*,c.name as cate_name,c.status as cate_status,c.seo_title')
			->join('INNER JOIN __CATEGORY__ c ON c.id = a.cid')
			->where('cid=13')
			->select();
		//课程展示左侧
		$Course = M('SearchAll')->alias('a')->field('a.*,c.name as cate_name,c.status as cate_status,c.seo_title')
			->join('INNER JOIN __CATEGORY__ c ON c.id = a.cid')
			->where('cid=20')
			->select();
		//课程展示右侧
		$CourseShow = M('SearchAll')->alias('a')->field('a.*,c.name as cate_name,c.status as cate_status,c.seo_title')
			->join('INNER JOIN __CATEGORY__ c ON c.id = a.cid')
			->where('cid=21')
			->select();
		//大赛展示
		$Competition = M('SearchAll')->alias('a')->field('a.*,c.name as cate_name,c.status as cate_status,c.seo_title')
			->join('INNER JOIN __CATEGORY__ c ON c.id = a.cid')
			->where('cid=15')
			->select();
		//证书展示
		$certificate = M('SearchAll')->alias('a')->field('a.*,c.name as cate_name,c.status as cate_status,c.seo_title')
			->join('INNER JOIN __CATEGORY__ c ON c.id = a.cid')
			->where('cid=16')
			->select();
		//新闻动态
		$news = M('SearchAll')->alias('a')->field('a.*,c.name as cate_name,c.status as cate_status,c.seo_title')
			->join('INNER JOIN __CATEGORY__ c ON c.id = a.cid')
			->where('cid=5')
			->select();
		//banner图
		$banner = M('abc_detail')->field('content,url')->select();
			$List = [];
			$List['about'] = $about;
			$List['Course'] = $Course; 
			$List['CourseShow'] = $CourseShow; 
			$List['Competition'] =$Competition;
			$List['certificate'] = $certificate;
			$List['news'] = $news;
			$List['banner'] = $banner;
			
           print_r($List);
	}
}
