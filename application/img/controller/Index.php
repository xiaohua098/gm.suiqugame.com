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
            $res=Db::table('manager')->where('name',$data['name'])->find();
            if(empty($res)){
              return  renderJson('10001','该账号不存在！');
            }
            if($res['pwd'] == sha1('suiqu_'.$data['pwd'])){
                //判断是否是超级管理员
                //if($res['is_admin']==1)
                //{
                    //$paths='1';
                    
                //}else{
                //   $paths=Db::table('role')->field('path')where('id',$res['role_id'])->find();
                //   $paths=explod(',',$paths['paths']);
                //}                
                //JWT加密
                $key = "jfdksajfkl;dsajfkdjsaklfdajffdsafdsfdsfdsfdsklfdsafdsafdsafdsdsajlkfdsa";
                // $key = "b23a7a34ae6d11e79e6e185e0f8afcbe";
                $token = array(
                'mid' => $res['id'],
                'mname' => $res['name'],
                'expire_time'=>time()+24*3600*7,
                //'is_admin'=>$res['is_admin'],
                //'paths'=>$paths,
                );
                $jwt = new \Firebase\JWT\JWT();
                $token=$jwt::encode($token, $key);

                //登录记录
                $login_arr=array();
                $login_arr['mid']=$res['id'];
                $login_arr['mname']=$res['name'];
                $login_arr['ip']=$_SERVER['REMOTE_ADDR'];
                $login_arr['add_time']=time();
                $login_arr['expire_time']=date('Y-m-d H:i:s',time()+24*3600*7);
                $login_arr['token']=$token;
                // var_dump($login_arr);exit;
                $res1=Db::table('login_record')->insert($login_arr);
                if(!$res1){
                    return renderJson('10000','操作失败');
                }


                //$login_arr=array();
                //$login_arr['mid']=$res['id'];
                //$login_arr['mname']=$res['name'];
                //$login_arr['ip']=$_SERVER['REMOTE_ADDR'];
                //$login_arr['add_time']=time();
                //Db::table('login_record')->insert($login_arr);
                  
              return renderJson('1','登录成功!',$token);
            }else{
              return renderJson('10000','登录密码错误!');
            }
        }
        return renderJson('101','越权访问失败！');
    } 
}