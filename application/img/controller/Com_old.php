<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
class Com extends Controller{
	//检查用户是否登陆
    public function _initialize(){
      // if(!Session::get('mid')){
      //     // $this->redirect('/public/index.php/login',302);
      // }
      
      

        //判断是否越权访问
        // $url=Request::instance()->url();
        // $url=explode('/',$url);
        // // var_dump($url);exit;
        // if(count($url) != 3){
        // $this->redirect('/login',302);
        // }
    //     $url=$url[1].'/'.$url[2];
    //     // $url=ltrim($url,'/');
    //     // $url=rtrim($url,'.html');
    //     $now_path=$url;
    //     // 从session中获取所有可访问的 模块-控制器-方法字符串
    //     $all_path = Session::get('paths');
    //     $path_arr = explode(',', $all_path);
    //     // dump($path_arr);
    //     // dump($now_path);exit;
    //     // 如果当前访问的 模块-控制器-路径不在 允许的范围中，则跳转到登录界面
    //     if(!in_array($now_path, $path_arr)){
    //        $this->redirect('/login',302);
    //     }
    }
}