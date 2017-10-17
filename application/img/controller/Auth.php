<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Auth extends Com{
    public function  auth(){
        $flag=$this->flag;
        // var_dump($flag);exit;
        if($flag){
             return renderJson('10001','token为空或者token已经过期');
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
        return renderJson('101','违法操作');
    }


    //权限列表
    public function authList($data){
        // var_dump($this->path);exit;
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
            return renderJson('1','',['auth'=>$auth,'total'=>$total]);
        }
        $temple=Db::table('auth')->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $auth=Db::table('auth')->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
        
        $res=$model->saveRecord($this->mid,$this->mname,$this->path,json_encode($data),json_encode(['code'=>'1','message'=>'','data'=>['auth'=>$auth,'total'=>$total]]));
        
        if(!$res){
            return renderJson('10010','日志写入错误');
        }
        return renderJson('1','',['auth'=>$auth,'total'=>$total]);
    }
    //添加权限
    public function authAdd(){
        if(Request::instance()->isPost()){
            $data=Request::instance()->post();
            if(empty($data['title'])){
                return renderJson('100','参数不合法');
            }
            $data['add_time']=$data['upd_time']=time();
            $res=Db::name('auth')->insert($data);
            if($res){
                return renderJson('200','添加成功');
            }
            return renderJson('100','添加失败');
        }
        $auth=Db::name('Auth')->where('pid',0)->select();
        return renderJson('200','',$auth);
    }
    //修改权限
    public function authEdit(){
        if(Request::instance()->isGet()){
            $data=Request::instance()->param();
            if(empty($data) || !is_numeric($data['id'])){
                return renderJson('100','参数不合法');
            }
            $id=$data['id'];
            $res=Db::name('auth')->find($id);
            if($res){
                return renderJson('200','',$res);
            }
            return renderJson('100','该权限不存在！');
        }
        $data=Request::instance()->post();
        if(empty($data['title']) || empty($data['id'])){
            return renderJson('100','参数不能为空');
        }
        $data['upd_time']=time();
        $res=Db::name('auth')->where('id',$data['id'])->update($data);
        if($res){
            return renderJson('200','修改成功');
        }
    }
    //删除权限
    public function authDel(){
        if(Request::instance()->isGet()){
            $data=Request::instance()->param();
            $id=$data['id'];
            $res=Db::name('auth')->where('id',$id)->delete($data);
            if($res){
                return renderJson('200','删除成功');
            }
            return renderJson('100','删除失败');
        }
        return renderJson('-100','违法操作!');
    }

}
