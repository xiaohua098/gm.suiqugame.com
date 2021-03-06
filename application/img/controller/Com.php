<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
// //指定允许其他域名访问  
// header('Access-Control-Allow-Origin:*');  
// // 响应类型  
// header('Access-Control-Allow-Methods:*');  
// // 响应头设置  
// header('Access-Control-Allow-Headers:content-type,token');  
class Com extends Controller{
    protected $mid='';
    protected $flag='';
    protected $mname='';
    protected $path='';
    //检查用户是否登陆
    public function _initialize(){
        //管理员访问路径
        $method=Request::instance()->method();
        $path=trim($_SERVER['PATH_INFO'],'/').'/'.strtolower($method); 
        $title=Db::table('auth')->where('path',$path)->value('title'); 
        $this->path= $title;
       // $key="b23a7a34ae6d11e79e6e185e0f8afcbe";
       $key="jfdksajfkl;dsajfkdjsaklfdajffdsafdsfdsfdsfdsklfdsafdsafdsafdsdsajlkfdsa";
        $token =isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : '';
        if($token == ''){
            $this->flag='1';
            $this->mid=0;
            $this->mname='未知';
            return false;
        }
        $jwt = new \Firebase\JWT\JWT();
        $decoded =$jwt::decode($token,$key,array('HS256'));
        if(!is_object($decoded)){
            $this->flag='1';
            $this->mid=0;
            $this->mname='未知';
            return  false;
        }else{
            $arr = json_decode(json_encode($decoded), true);
            //判断用户登陆是否过期
            if($arr['expire_time']<time()){
                $this->flag='1';
                $this->mid=$arr['mid'];
                $this->mname=$arr['mname'];
                return false;
            }
             
         //判断是否是超级管理员
        $is_admin=Db::table('manager')->where('id',$arr['mid'])->value('is_admin');
        if($is_admin==1)
        {
            $this->mid=$arr['mid'];
            $this->mname=$arr['mname'];
            $this->flag='';
            return true;
        } 


        //判断是否越权访问
        $role_id=$arr['role_id'];
        $res=Db::table('role')->where('id',$role_id)->value('paths');
        $paths=explode(',',$res);
        $pathList=Db::table('auth')->field('path')->where('id','in',$paths)->select();
        $pathsList=array_column($pathList,'path');

        // 如果当前访问的 模块-控制器-路径不在 允许的范围中，则跳转到登录界面
        if(!in_array($path,$pathsList)){
           $this->flag='2';
           $this->mid=$arr['mid'];
           $this->mname=$arr['mname'];
           return false;
        }
            $this->mid=$arr['mid'];
            $this->mname=$arr['mname'];
            $this->flag='';

        } 
        
    }


}