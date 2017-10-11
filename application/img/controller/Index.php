<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
class Index extends Controller{
    //后台登陆
    public function index(){
         if(Request::instance()->isPost()){
            $data=Request::instance()->post();
            $res=Db::name('user')->where('name',$data['name'])->find();
            if(empty($res)){
              return  renderJson('100','该账号不存在！');
            }
            if($res['pwd'] == sha1('suiqu_'.$data['pwd'])){
                //JWT加密
                $key = "b23a7a34ae6d11e79e6e185e0f8afcbe";
                $token = array(
                'mid' => $res['id'],
                'name' => $res['name'],
                'expire_time'=>time()+7200,
                );
                $jwt = new \Firebase\JWT\JWT();
                $token=$jwt::encode($token, $key);     
              return renderJson('200','登录成功!',$token);
            }else{
              return renderJson('100','登录密码错误!');
            }
        }
        return renderJson('101','越权访问失败！');
    }
    //后台退出
    public function out(){
      Session::flush();
      return renderJson('200','退出成功!');
    }

    
}