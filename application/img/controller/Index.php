<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
// // 指定允许其他域名访问  
// header('Access-Control-Allow-Origin:*');  
// // 响应类型  
// header('Access-Control-Allow-Methods:*');  
// // 响应头设置  
// header('Access-Control-Allow-Headers:x-requested-with,content-type,token'); 

class Index extends Controller{
    //后台登陆
    public function index(){
         if(Request::instance()->isPost()){
            $data=Request::instance()->post();
            $res=Db::name('manager')->where('name',$data['name'])->find();
            if(empty($res)){
              return  renderJson('10001','该账号不存在！');
            }
            if($res['pwd'] == sha1('suiqu_'.$data['pwd'])){
                //JWT加密
                $key = "b23a7a34ae6d11e79e6e185e0f8afcbe";
                $token = array(
                'mid' => $res['id'],
                'mname' => $res['name'],
                'expire_time'=>time()+7200,
                );
                $jwt = new \Firebase\JWT\JWT();
                $token=$jwt::encode($token, $key);     
              return renderJson('1','登录成功!',$token);
            }else{
              return renderJson('10000','登录密码错误!');
            }
        }
        return renderJson('101','越权访问失败！');
    } 
}