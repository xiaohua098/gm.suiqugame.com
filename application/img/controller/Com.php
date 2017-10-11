<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
class Com extends Controller{
    protected $mid='';
	//检查用户是否登陆
    public function _initialize(){
       $key = "b23a7a34ae6d11e79e6e185e0f8afcbe";
        $token = isset($_POST['token']) ? $_POST['token'] : '';
        if($token == ''){
             $this->redirect('/public/index.php/login',302);
        }
        // $str="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOjEwNTAsInVzZXJuYW1lIjoiYmFieSJ9.r7KLs6Z-HaUw52qZzKrE8hiuAPy-KGVZXuE4QXZodo8";
        $jwt = new \Firebase\JWT\JWT();
        $decoded =$jwt::decode($str, $key, array('HS256'));
        if(!is_object($decoded)){
           $this->redirect('/public/index.php/login',302);
        }else{
            $arr = json_decode(json_encode($decoded), true);
            // var_dump($arr);
            // echo '<hr />';
            // var_dump(time());
            //判断用户登陆是否过期
            if($arr['expire_time']<time()){
                $this->redirect('/public/index.php/login',302);
            }
            $this->mid=$arr['mid'];
        }
      
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