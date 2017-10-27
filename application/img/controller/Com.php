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
       // $key="b23a7a34ae6d11e79e6e185e0f8afcbe";
       $key="jfdksajfkl;dsajfkdjsaklfdajffdsafdsfdsfdsfdsklfdsafdsafdsafdsdsajlkfdsa";
        $token =isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : '';
        // var_dump(renderJson('10001','token值',$token));exit;
        if($token == ''){
            $this->flag='1';
            return false;
        }
        $jwt = new \Firebase\JWT\JWT();
        $decoded =$jwt::decode($token,$key,array('HS256'));
        if(!is_object($decoded)){
            $this->flag='1';
            return  false;
        }else{
            $arr = json_decode(json_encode($decoded), true);
            //判断用户登陆是否过期
            if($arr['expire_time']<time()){
                $this->flag='1';
                return false;
            }
            //管理员访问路径
        //// $method=Request::instance()->method();
        // $path=ltrim($_SERVER['PATH_INFO'],'/').strtolower($method); 
        // $title=Db::table('auth')->where('path',$path)->value('title'); 
        // $this->path= $title; 
         //判断是否是超级管理员
        //if($arr['is_admin']==1)
        //{
            // $this->mid=$arr['mid'];
            // $this->mname=$arr['mname'];
            // $this->flag='';
            //return true;
        //} 
        //判断是否越权访问
        //$paths=$res['paths'];
    //     // 如果当前访问的 模块-控制器-路径不在 允许的范围中，则跳转到登录界面
    //     if(!in_array($path,$paths)){
    //        $this->flag='2';
    //        return false;
    //     }
            $this->mid=$arr['mid'];
            $this->mname=$arr['mname'];
            $this->flag='';

        }
      

        
        
    }


}