<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Auth extends Com{
    public function  auth(){
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
        }

       
    }

    //权限列表
    public function authList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        
        if(isset($data['id']) && is_numeric($data['id'])){
            $auth=Db::table('auth')->where('id',$data['id'])->find();
            //     //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$auth]);
        }
        $total=Db::table('auth')->count();
        if(isset($data['offset']) && isset($data['pagesize']) && $data['pagesize']  && $data['offset'] <= $total){
            $offset=$data['offset'];
            $pagesize=$data['pagesize'];
            if($offset == 0){
                $auth=Db::table('auth')->order('add_time','desc')->limit($pagesize)->select();

            //     //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('1','',['auth'=>$auth,'total'=>$total]);
            }
            $temple=Db::table('auth')->order('add_time','desc')->limit($offset)->select();
            $tid=array_pop($temple);
            $auth=Db::table('auth')->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();

            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            
            return renderJson('1','',['auth'=>$auth,'total'=>$total]);
        }
        $total=Db::table('auth')->count();
        $auth=Db::table('auth')->order('add_time','desc')->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['auth'=>$auth,'total'=>$total]); 
    }
    //添加权限
    public function authAdd($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub; 
        if(empty($data['title']) || empty($data['path'])){
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法'),$url);
                return renderJson('10001','参数不合法');
        }
        $res1=Db::table('auth')->where('title',$data['title'])->find();
        $res2=Db::table('auth')->where('path',$data['path'])->find();

        if($res1 || $res2){
             // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10005','message'=>'该权限已存在'),$url);
                return renderJson('10005','该权限已存在');
        }
            $res=Db::table('auth')->insert([
                'title'=>$data['title'],
                'path'=>$data['path'],
                'pid'=>$data['pid'],
                'is_show'=>$data['is_show'],
                'add_time'=>time(),
                'upd_time'=>time(),
            ]);
            if($res){
        //         //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'添加成功']),$url);
                return renderJson('1','');
            }
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'添加失败']),$url);
            return renderJson('10000','添加失败');
    }
    //修改权限
    public function authEdit($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        if(empty($data['title']) || empty($data['id']) || empty($data['path'])){
           //  //写入日志
           // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
        }
        $res1=Db::table('auth')->where('id','<>',$data['id'])->where('title',$data['title'])->find();
        $res2=Db::table('auth')->where('id','<>',$data['id'])->where('path',$data['path'])->find();

        if($res1 || $res2){
             // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10005','message'=>'该权限已存在'),$url);
                return renderJson('10005','该权限已存在');
        }
        $res=Db::name('auth')->where('id',$data['id'])->update([
            'upd_time'=>time(),
            'path'    =>$data['path'],
            'is_show' =>$data['is_show'],
            'title'   =>$data['title'],
            'pid'     =>$data['pid']
            ]);
        if($res){
           //  //写入日志
           // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','');
        }
         // //写入日志
         //   $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'修改失败']),$url);
            return renderJson('10000','修改失败');
    }
    //删除权限
    public function authDel($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
            if(isset($data['id']) && is_numeric($data['id'])){
                $res=Db::name('auth')->where('id',$data['id'])->delete($data);
                if($res){
                      //写入日志
           // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                    return renderJson('1','');
                }
           //       //写入日志
           // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'删除失败']),$url);
                return renderJson('10001','删除失败');
            }
           //   //写入日志
           // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
        return renderJson('10001','参数不合法');
    }

}
