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
                // $power=Db::name('role')->where('id',$res['role_id'])->get();
                // $powers=explode(',',$power['powers']);
                session::set(['name'=>$res['name']]);
                session::set(['mid'=>$res['id']]);
                // session::set(['role_id'=>$res['role_id']]);
                // session::set(['power'=>$powers]);
              return renderJson('200','登录成功!');
            }else{
              return renderJson('100','登录密码错误!');
            }
        }
        Session::flush();
        return renderJson('101','越权访问失败！');
    }
    //后台退出
    public function out(){
      Session::flush();
      return renderJson('200','退出成功!');
    }

    
}