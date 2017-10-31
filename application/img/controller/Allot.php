<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Allot extends Com{ 
    public function  allot(){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $model=new pub;
        $param=Request::instance()->param();
        $flag=$this->flag;
        if($flag == '1'){
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10007','message'=>'token为空或者token已经过期']),$url);
            return renderJson('10007','token为空或者token已经过期');
        }
        if($flag == '2'){
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'101','message'=>'违法操作']),$url);
            return renderJson('101','违法操作');
        }
        // 是否为 PUT 请求
        //分配权限
        if (Request::instance()->isPut()){
            $data=Request::instance()->param();
           return  $this->role($data);
        }  
    } 
    //分配角色
    public  function   role($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
         $param=$data;
         $model=new pub;
        if(isset($data['id']) && isset($data['role_id']) && is_numeric($data['id']) && is_numeric($data['role_id'])){
            $res=Db::table('manager')->where('id',$data['id'])->update([
                'role_id'=>$data['role_id'],
                'is_admin'=>0,
                'upd_time'=>time()
                ]);
            if($res){
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('1');
            }else{
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'角色分配失败']),$url);
                return renderJson('10000','角色分配失败');
            }
        }
        // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
        return  renderJson('10001','参数不合法');
    }

   
     
}
