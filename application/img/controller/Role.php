<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Role extends Com{
    public function  role(){
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
           return  $this->roleList($data);
        }
        // 是否为 POST 请求
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->roleAdd($data);
        }
        // 是否为 PUT 请求
        if (Request::instance()->isPut()){
            $data=Request::instance()->param();
           return  $this->roleEdit($data);
        }
        // 是否为 DELETE 请求
        if (Request::instance()->isDelete()){
           $data=Request::instance()->param();
           return  $this->roleDel($data);
        };
        
    }
    //角色列表
    public function roleList(){
        $param=$data;
        $model=new pub;

        if(isset($data['id']) && is_numeric($data['id'])){
            $role=Db::table('role')->where('id',$data['id'])->find();
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',[0=>$role]);
        }
        if(empty($data['pagesize'])){
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']));
            return renderJson('10001','参数不能为空');
        }
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];
        $total=Db::table('role')->count();
        if($offset == 0){
            $role=Db::table('role')->order('add_time','desc')->limit($pagesize)->select();
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['role'=>$role,'total'=>$total]);
        }
        $temple=Db::table('role')->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $role=Db::table('role')->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
        return renderJson('1','',['role'=>$role,'total'=>$total]);
    }
    //添加角色
    public function roleAdd($data){
        $param=$data;
        $model=new pub;
        if(empty($data['title'])){
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
                return renderJson('10001','参数不能为空');
        }
        $data['add_time']=$data['upd_time']=time();
        $res=Db::name('role')->insert($data);
        if($res){
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'添加成功']));
            return renderJson('1','添加成功');
        }
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'添加失败']));
        return renderJson('10001','添加失败');
    }
    
    //分配权限
    public function roleEdit($data){
        $param=$data;
        $model=new pub;

        if(isset($data['type']) && $data['type']=='get'){
            $auth=Db::table('auth')->select();
           //  //写入日志
           // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['auth'=>$auth]);
        }
        if(empty($data['id']) || empty($data['paths'])){
        //      //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']));
            return renderJson('10001','参数不合法');
        }
        $data['upd_time']=time();
        $res=Db::name('role')->where('id',$data['id'])->update($data);
        if($res){
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','');
        }
        //  //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'修改失败']));
        return renderJson('10000','修改失败');

    }

    //删除角色
    public function roleDel($data){
        $param=$data;
        $model=new pub;
        if(empty($data) || !is_numeric($data['id'])){
        //      //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']));
            return renderJson('10001','参数不合法');
        }
        $res=Db::name('role')->where('id',$data['id'])->delete();
       if($res){
        //  //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','');
        }
        //  //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'删除失败']));
        return renderJson('10000','删除失败');
    }

    
}
