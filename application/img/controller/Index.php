<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Index extends Controller{
    //后台登陆
    public function index(){
         if(Request::instance()->isPost()){
            $data=Request::instance()->post();
            $model=new pub;
            $param=$data;
            $res=Db::table('manager')->where('name',$data['name'])->find();
            if(empty($res)){
                // //写入日志
        // $model->saveRecord(0,$data['name'],'GM登录',json_encod($param),json_encode(['code'=>'10001','message'=>'该账号不存在']));
              return  renderJson('10001','该账号不存在！');
            }
            if($res['pwd'] == sha1('suiqu_'.$data['pwd'])){
                //判断是否是超级管理员
                //if($res['is_admin']==1)
                //{
                    //$paths='';
                //}else{
                  // $paths=Db::table('role')->where('id',$res['role_id'])->column('paths');
                //   $paths=explod(',',$paths);
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
                    // //写入日志
        // $model->saveRecord($res['id],$res['name'],'添加登录记录',json_encode($param),json_encode(['code'=>'10000','message'=>'操作失败']));
                    return renderJson('10000','操作失败');
                }
               // //写入日志
        // $model->saveRecord($res['id'],$res['name'],'GM登录',json_encode($param),json_encode(['code'=>'1','message'=>'']));   
              return renderJson('1','',$token);
            }else{
                 // //写入日志
        // $model->saveRecord($res['id'],$res['name'],'GM登录',json_encode($param),json_encode(['code'=>'10000','message'=>'登录密码错误']));   

              return renderJson('10000','登录密码错误!');
            }
        }
        $param=Request::instance()->param();
        // //写入日志
        // $model->saveRecord(0,'','GM登录',json_encode($param),json_encode(['code'=>'101','message'=>'越权访问失败']));
        return renderJson('101','越权访问失败！');
    } 
}