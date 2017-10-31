<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Manager extends Com{
     
    public function  manager(){
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
        }
        
       
    }

    //添加管理员
    public function managerAdd($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
            if(isset($data['name']) && isset($data['pwd']) && isset($data['is_admin']) && $data['name'] && $data['pwd']){

                $res1=Db::name('manager')->where('name',$data['name'])->find();
                if($res1){
                    // //写入日志
                // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10005','message'=>'该用户名已存在']),$url);
                    return renderJson('10005','该用户名已存在');
                }
                $data1['is_admin']=$data['is_admin'];
                $data1['name']=$data['name'];
                $data1['pwd']=sha1('suiqu_'.$data['pwd']);
                $data1['add_time']=$data1['upd_time']=time();
                $res=Db::name('manager')->insert($data1);
                if($res){
                    // //写入日志
                // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                    return renderJson('1','');
                }
                 // //写入日志
                // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'添加失败']),$url);
                return renderJson('10000','添加失败');

            }

            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
                return renderJson('10001','参数不合法');
    }
    

    //修改密码
    public function  modifyPwd($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        var_dump($data);exit;
        $param=$data;
        $model=new pub;
        if(empty($data['new_pwd']) || empty($data['re_pwd']) || empty($data['old_pwd'])){
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
        }
            if($data['new_pwd'] != $data['re_pwd']){
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'两次输入密码不一致']),$url);
                return  renderJson('10001','两次输入密码不一致');
            }
            $mid=$this->mid;
            $res=Db::name('manager')->where('id',$mid)->find();
            if($res['pwd'] != sha1('suiqu_'.$data['old_pwd'])){
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10006','message'=>'原密码不正确']),$url);
               return  renderJson('10006','原密码不正确');
            }
            $res1=Db::name('manager')->where('id',$mid)->update(['pwd'=>sha1('suiqu_'.$data['new_pwd'])]);
            if($res1){
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
               return renderJson('1','');
            }
             // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'密码修改失败']),$url);
            return renderJson('10000','密码修改失败');
    }

    

    //GM用户列表
    public  function  managerList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        $total=Db::table('manager')->count();
        if(isset($data['pagesize']) &&  isset($data['offset']) && $data['pagesize'] && $data['offset']<=$total){
            $offset=$data['offset'];
            $pagesize=$data['pagesize'];
            
            $record=Db::table('manager')
                    ->field('a.*,b.title as role')
                    ->alias('a')
                    ->join('role b','a.role_id=b.id','LEFT')
                    ->order('a.add_time','desc')->limit($offset,$pagesize)->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        if(isset($data['id']) && $data['id']){
            $record=Db::table('manager')
                    ->field('a.*,b.title as role')
                    ->alias('a')
                    ->join('role b','a.role_id=b.id','LEFT')
                    ->where('a.id',$data['id'])->find();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record]);
        }
        // //写入日志
       // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
        return renderJson('10001','参数不合法');

    }
     
}
