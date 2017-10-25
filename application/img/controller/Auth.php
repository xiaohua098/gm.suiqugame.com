<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Auth extends Com{
    public function  auth(){
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
           return  $this->authList($data);
        }
        // 是否为 POST 请求
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->authAdd($data);
        }
        // 是否为 PUT 请求
        if (Request::instance()->isPut()){
            $data=Request::instance()->param();
           return  $this->authEdit($data);
        }
        // 是否为 DELETE 请求
        if (Request::instance()->isDelete()){
           $data=Request::instance()->param();
           return  $this->authDel($data);
        };

       
    }


    //权限列表
    public function authList($data){
        
        $param=$data;
        $model=new pub;

        if(isset($data['id']) && is_numeric($data['id'])){
            $auth=Db::table('auth')->where('id',$data['id'])->find();
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',[0=>$auth]);
        }
        if(empty($data['pagesize'])){
        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']));
            return renderJson('10001','参数不能为空');
        }


        if(isset($data['pid']) && $data['pid'] == '0'){
             $auth=Db::name('Auth')->where('pid',0)->select();
        //      //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
             return renderJson('1','',['auth'=>$auth]);
        }


    //权限列表
    public function authList($data){
        $param=$data;
        $model=new pub;
        
        if(isset($data['id']) && is_numeric($data['id'])){
            $auth=Db::table('auth')->where('id',$data['id'])->find();
            $auth['content'] = htmlspecialchars_decode($auth['content']);
            return renderJson('1','',[0=>$auth]);
        }
        if(empty($data['pagesize'])){
            return renderJson('10001','参数不能为空');
        }

        $offset=$data['offset'];
        $pagesize=$data['pagesize'];
        $total=Db::table('auth')->count();
        if($offset == 0){
            $auth=Db::table('auth')->order('add_time','desc')->limit($pagesize)->select();

        //     //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['auth'=>$auth,'total'=>$total]);
        }
        $temple=Db::table('auth')->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $auth=Db::table('auth')->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();

        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
        
        $res=$model->saveRecord($this->mid,$this->mname,$this->path,json_encode($data),json_encode(['code'=>'1','message'=>'','data'=>['auth'=>$auth,'total'=>$total]]));
        
        if(!$res){
            return renderJson('10010','日志写入错误');
        }

        return renderJson('1','',['auth'=>$auth,'total'=>$total]);
    }
    //添加权限
    public function authAdd($data){
        $param=$data;
        $model=new pub; 
        if(empty($data['title'])){
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法'));
                return renderJson('10001','参数不合法');
        }
            $data['add_time']=$data['upd_time']=time();
            $res=Db::name('auth')->insert($data);
            if($res){
        //         //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'添加成功']));
                return renderJson('1','');
            }
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'添加失败']));
            return renderJson('10000','添加失败');
    }
    //修改权限
    public function authEdit($data){
        $param=$data;
        $model=new pub;
        if(empty($data['title']) || empty($data['id'])){
           //  //写入日志
           // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']));
            return renderJson('10001','参数不能为空');
        }
        $data['upd_time']=time();
        $res=Db::name('auth')->where('id',$data['id'])->update($data);
        if($res){
           //  //写入日志
           // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','');
        }
         // //写入日志
         //   $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'修改失败']));
            return renderJson('10000','修改失败');
    }
    //删除权限
    public function authDel($data){
        $param=$data;
        $model=new pub;
            if(isset($data['id'] && is_numeric($data['id']))){
                $res=Db::name('auth')->where('id',$data['id'])->delete($data);
                if($res){
           //           //写入日志
           // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
                    return renderJson('1','');
                }
           //       //写入日志
           // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'删除失败']));
                return renderJson('10001','删除失败');
            }
           //   //写入日志
           // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']));
        return renderJson('10001','参数不合法');
    }

}
