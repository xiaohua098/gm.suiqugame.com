<?php
namespace app\img\model;
use think\Model;
use think\Db;
class pub extends Model{
	public function saveRecord($mid=0,$mname='无',$name,$param,$response,$url=''){
		  $operate_arr=array();
	      $operate_arr['mid']=$mid;
	      $operate_arr['mname']=$mname;
	      $operate_arr['ip']=$_SERVER['REMOTE_ADDR'];
	      $operate_arr['name']=$name;
	      $operate_arr['param']=$param;
	      $operate_arr['add_time']=time();
	      $operate_arr['response']=$response;
	      $operate_arr['url']=$url;
		$res=Db::table('operate_record')->insert($operate_arr);
		return $res;
		
	// return $operate_arr;
	}
}