<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Manager extends Com{
     
    public function  manager(){
        $model=new pub;
        $param=Request::instance()->param();
        $flag=$this->flag;
        if($flag == '1'){
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10007','message'=>'token为空或者token已经过期']));
            return renderJson('10007','token为空或者token已经过期');
        }
        if($flag == '2'){
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'101','message'=>'违法操作']));
            return renderJson('101','违法操作');
        }
        if (Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->managerList($data);
        }
        // 是否为 POST 请求
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->managerAdd($data);
        }
        // 是否为 PUT 请求
        // 修改密码
        if (Request::instance()->isPut()){
            $data=Request::instance()->param();
           return  $this->modifyPwd($data);
        }
        // 是否为 DELETE 请求
        //分配角色
        if (Request::instance()->isDelete()){
            $data=Request::instance()->param();
           return  $this->managerRole($data);
        };
        
       
    }

    //添加管理员
    public function managerAdd($data){
        $param=$data;
        $model=new pub;

            if(empty($data['name']) || empty($data['pwd']) ){
                // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']));
                return renderJson('10001','参数不能为空');
            }
            $res1=Db::name('manager')->where('name',$data['name'])->find();
            if($res1){
                // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10005','message'=>'该用户名已存在']));
                return renderJson('10005','该用户名已存在');
            }
            $data1['name']=$data['name'];
            $data1['pwd']=sha1('suiqu_'.$data['pwd']);
            $data1['add_time']=$data1['upd_time']=time();
            $res=Db::name('manager')->insert($data1);
            if($res){
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
                return renderJson('1','');
            }
             // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'添加失败']));
            return renderJson('10000','添加失败');
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

    

    //修改密码
    public function  modifyPwd($data){
        $param=$data;
        $model=new pub;
            if($data['new_pwd'] != $data['re_pwd']){
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'两次输入密码不一致']));
                return  renderJson('10001','两次输入密码不一致');
            }
            $mid=$this->mid;
            $res=Db::name('manager')->where('id',$mid)->find();
            if($res['pwd'] != sha1('suiqu_'.$data['old_pwd'])){
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10006','message'=>'原密码不正确']));
               return  renderJson('10006','原密码不正确');
            }
            $res1=Db::name('manager')->where('id',$mid)->update(['pwd'=>sha1('suiqu_'.$data['new_pwd'])]);
            if($res1){
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
               return renderJson('1','');
            }
             // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'密码修改失败']));
            return renderJson('10000','密码修改失败');
    }

    //分配角色
    public  function   managerRole($data){
         $param=$data;
        $model=new pub;
        if(isset($data['id']) && isset($data['role_id']) && is_numeric($data['id']) && is_numeric($data['role_id'])){
            $res=Db::table('manager')->where('id',$data['id'])->update(['role_id'=>$data['role_id']]);
            if($res){
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
                return renderJson('1');
            }else{
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'角色分配失败']));
                return renderJson('10000','角色分配失败');
            }
        }
        // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']));
    return  renderJson('10001','参数不合法');
    }

    //GM用户列表
    public  function  managerList($data){
        $param=$data;
        $model=new pub;
        if(isset($data['pagesize']) &&  isset($data['offset']) && $data['pagesize']){
            $offset=$data['offset'];
            $pagesize=$data['pagesize'];
            if($offset == 0){
                $record=Db::table('manager')->order('add_time','desc')->limit($pagesize)->select();
                    return renderJson('1','',['record'=>$record,'total'=>$total]);
            }
            $temple=Db::table('manager')->order('add_time','desc')->limit($offset)->select();
            $tid=array_pop($temple);
            $record=Db::table('manager')->order('add_time','desc')->limit($pagesize)->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        if($isset($data['id']) && $data['id']){
            $record=Db::table('manager')->where('id',$data['id'])->find();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record]);
        }
        
        // //写入日志
       // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']));
        return renderJson('10001','参数不能为空');

    }
     
}
