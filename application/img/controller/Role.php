<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Role extends Com{
    public function  role(){
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
    public function roleList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;

        if(isset($data['id']) && is_numeric($data['id'])){
            $role=Db::table('role')->where('id',$data['id'])->find();
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',[0=>$role]);
        }
         $total=Db::table('role')->count();
        if(isset($data['pagesize']) &&  isset($data['offset']) &&  $data['pagesize']){
            $offset=$data['offset'];
            $pagesize=$data['pagesize'];
             if($offset>$total){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
            }
            if($offset == 0){
                $role=Db::table('role')->order('add_time','desc')->limit($pagesize)->select();
            //     //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('1','',['role'=>$role,'total'=>$total]);
            }
            $temple=Db::table('role')->order('add_time','desc')->limit($offset)->select();
            $tid=array_pop($temple);
            $role=Db::table('role')->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['role'=>$role,'total'=>$total]);
        }
        $role=Db::table('role')->order('add_time','desc')->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['role'=>$role,'total'=>$total]);
        
    }
    //添加角色
    public function roleAdd($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        if(empty($data['title'])){
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('10001','参数不能为空');
        }
        $res1=Db::table('role')->where('title',$data['title'])->find();
        if($res1){
             // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10005','message'=>'该角色已存在'),$url);
                return renderJson('10005','该角色已存在');
        }
        $res=Db::name('role')->insert([
            'title'   =>$data['title'],
            'add_time'=>time(),
            'upd_time'=>time(),
            ]);
        if($res){
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'添加成功']),$url);
            return renderJson('1','添加成功');
        }
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'添加失败']),$url);
        return renderJson('10001','添加失败');
    }
    
    //分配权限
    public function roleEdit($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        if(empty($data['id']) || empty($data['paths'])){
        //      //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
        }
        $res=Db::name('role')->where('id',$data['id'])->update([
            'id'=>$data['id'],
            'paths'=>$data['paths'],
            'upd_time'=>time(),
            ]);
        if($res){
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','');
        }
        //  //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'修改失败']),$url);
        return renderJson('10000','修改失败');

    }

    //删除角色
    public function roleDel($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        if(empty($data) || !is_numeric($data['id'])){
        //      //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
        }
        $res=Db::name('role')->where('id',$data['id'])->delete();
       if($res){
        //  //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','');
        }
        //  //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'删除失败']),$url);
        return renderJson('10000','删除失败');
    }

    
}
