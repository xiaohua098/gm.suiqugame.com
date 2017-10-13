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
	//检查用户是否登陆
    public function _initialize(){
       $key = "b23a7a34ae6d11e79e6e185e0f8afcbe";
        $token = isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : '';
        // var_dump(renderJson('10001','token值',$token));
        if($token == ''){
            $this->flag='1';
            return false;
            // $json= renderJson('10001','token不能为空');
            // var_dump($json);
            // die;
            // $this->redirect('http://192.168.0.8:8090/public/index.php/login');
        }
        $jwt = new \Firebase\JWT\JWT();
        $decoded =$jwt::decode($token, $key, array('HS256'));
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
            $this->mid=$arr['mid'];
            $this->mname=$arr['mname'];
            $this->flag='';
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