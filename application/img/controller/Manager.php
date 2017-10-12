<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
class Manager extends Com{
    // //后台管理员列表
    // public function managerList(){
    //     $count=10;
    //     $res=Db::name('Manager')->paginate($count);
    //     $page=$res->render();
    //     $data=array('manager'=>$res,'page'=>$page);
    //     if($res){
    //         return renderJson('200','',$data);
    //     }
    //     return renderJson('100','获取失败');
    // }
    //添加管理员
    public function managerAdd(){
       if(Request::instance()->isPost()){
            $data=Request::instance()->post();
            if(count($data) != 2){
                return renderJson('10001','参数不合法！');
            }
            if(empty($data['name']) || empty($data['pwd']) ){
                return renderJson('10001','参数不能为空');
            }
            $data['pwd']=sha1('suiqu_'.$data['pwd']);
            $data['add_time']=$data['upd_time']=time();
            $res=Db::name('manager')->insert($data);
            if($res){
                return renderJson('1','添加成功');
            }
            return renderJson('10000','添加失败');
        }
        return renderJson('101','违法操作');
    }
    // //修改管理员
    // public function managerEdit(){
    //     if(Request::instance()->isGet()){
    //         $data=Request::instance()->param();
    //         if(empty($data)){
    //             return renderJson('100','参数不能为空');
    //         }
    //         if(!is_numeric($data['id'])){
    //             return renderJson('100','参数不合法');
    //         }
    //         $id=$data['id'];
    //         $res=Db::name('manager')->where('id',$id)->find();
    //         if($res){
    //             return renderJson('200','',$res);
    //         }
    //         return renderJson('100','该管理员不存在！');
    //     }
    //     $data=Request::instance()->post();
    //     $data['upd_time']=time();
    //     $res=Db::name('manager')->where('id',$data['id'])->update($data);
    //     if($res){
    //         return renderJson('200','修改成功');
    //     }
    // }
    // //删除管理员
    // public function managerDel(){
    //     if(Request::instance()->isGet()){
    //         $data=Request::instance()->param();
    //         if(empty($data)){
    //             return renderJson('100','参数不能为空');
    //         }
    //         if(!is_numeric($data['id'])){
    //             return renderJson('100','参数不合法');
    //         }
    //         $id=$data['id'];
    //         $res=Db::name('manager')->where('id',$id)->delete($data);
    //         if($res){
    //             return renderJson('200','删除成功');
    //         }
    //         return renderJson('10000','删除失败');
    //     }
    //     return renderJson('101','违法操作!');
    // }

    // //分配角色
    // public function managerAssign(){
    //     if(Request::instance()->isGet()){
    //         $data=Request::instance()->param();
    //         if(empty($data)){
    //             return renderJson('100','参数不能为空');
    //         }
    //         if(!is_numeric($data['id'])){
    //             return renderJson('100','参数不合法');
    //         }
    //         $id=$data['id'];
    //         $role=Db::name('role')->select();
    //         $result['id']=$id;
    //         $result['role']=$role;
    //         return renderJson('200','',$result);
    //     }
    //     $data=Request::instance()->post();
    //     if(empty($data)){
    //         return renderJson('10001','参数不能为空');
    //     }
    //     $data['upd_time']=time();
    //    $res=Db::name('manager')->update($data);
    //    if($res){
    //      return renderJson('200','分配角色成功');
    //    }
    //     return renderJson('10000','分配角色失败');
    // }

    //修改密码
    public function  modifyPwd(){
        if(Request::instance()->isPost()){
            $data=Request::instance()->post();
            if($data['new_pwd'] != $data['re_pwd']){
                return  renderJson('10001','两次输入密码不一致');
            }
            $mid=$this->mid;
            $res=Db::name('manager')->where('id',$mid)->find();
            if($res['pwd'] != sha1('suiqu_'.$data['old_pwd'])){
               return  renderJson('10001','原密码不正确');
            }
            $res1=Db::name('manager')->where('id',$mid)->update(['pwd'=>sha1('suiqu_'.$data['new_pwd'])]);
            if($res1){
               return renderJson('1','密码修改成功');
            }
            return renderJson('10000','密码修改失败');
        }

        return renderJson('101','违法操作');
    }

    // //短信验证 
    // public function verifi(){
    //   //短信验证
    //     include('../api/TopSdk.php');
    //     if(Request::instance()->isPost()){
    //      $phone=Request::instance()->post('phone');
    //      }
    //       $products='个人';
    //      if(empty($phone)){
    //        return renderJson('100','参数不合法');
    //      }
    //     $code=rand(1000,9999);
    //     $c=new \TopClient;
    //     $c->appkey ='23696713';
    //     $c->secretKey ='fff78fe6e47b19adf7c1b807ba55221d';
    //     $c->format = 'json';
    //     $req = new \AlibabaAliqinFcSmsNumSendRequest;
    //     $req->setExtend($code);
    //     Session::set('sms_code',$code);
    //     $req->setSmsType("normal");
    //     $req->setSmsFreeSignName("定制神器");
    //     $req->setSmsParam("{\"code\":\"$code\",\"product\":\"$products\"}");
    //     $req->setRecNum($phone);
    //     $req->setSmsTemplateCode("SMS_52125510 ");
    //     $resp = $c->execute($req);
    //     if(!empty($resp->result->success)){
    //     return $resp->result->success;
    //     }else{
    //       return renderJson('107','短信发送失败');
    //   }
    //     }
    //   return renderJson('200','短信发送成功');
    // }
}
