<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;

class Role extends Com{
    //角色列表
    public function roleList(){
        $count=10;
        $res=Db::name('role')->paginate($count);
        $page=$res->render();
        $data=array('role'=>$res,'page'=>$page);
        if($res){
            return renderJson('200','',$data);
        }
        return renderJson('100','获取失败');
    }
    //添加角色
    public function roleAdd(){
        if(Request::instance()->isPost()){
            $data=Request::instance()->post();
            if(empty($data['title']) ){
                return renderJson('100','参数不能为空');
            }
            $data['add_time']=$data['upd_time']=time();
            $res=Db::name('role')->insert($data);
            if($res){
                return renderJson('200','添加成功');
            }
            return renderJson('100','添加失败');
        }
        return renderJson('101','违法操作');
    }
    //修改角色
    public function roleEdit(){
        if(Request::instance()->isGet()){
            $data=Request::instance()->param();
            if(empty($data)){
                return renderJson('100','参数不能为空');
            }
            if(!is_numeric($data['id'])){
                return renderJson('100','参数不合法');
            }
            $id=$data['id'];
            $res=Db::name('role')->where('id',$id)->find();
            if($res){
                return renderJson('200','',$res);
            }
            return renderJson('100','该角色不存在！');
        }
        $data=Request::instance()->post();
        $data['upd_time']=time();
        $res=Db::name('role')->where('id',$data['id'])->update($data);
        if($res){
            return renderJson('200','修改成功');
        }
    }
    //删除角色
    public function roleDel(){
        if(Request::instance()->isGet()){
            $data=Request::instance()->param();
            if(empty($data)){
                return renderJson('100','参数不能为空');
            }
            if(!is_numeric($data['id'])){
                return renderJson('100','参数不合法');
            }
            $id=$data['id'];
            $res=Db::name('role')->where('id',$id)->delete($data);
            if($res){
                return renderJson('200','删除成功');
            }
            return renderJson('100','删除失败');
        }
        return renderJson('100','违法操作!');
    }

    //分配权限
    public function roleAssign(){
        if(Request::instance()->isGet()){
            $data=Request::instance()->param();
            if(empty($data)){
                return renderJson('100','参数不能为空');
            }
            if(!is_numeric($data['id'])){
                return renderJson('100','参数不合法');
            }
            $id=$data['id'];
            $auth=Db::name('auth')->select();
            $result['id']=$id;
            $result['auth']=$auth;
            return renderJson('200','',$result);
        }
        $data=Request::instance()->post();
        if(empty($data)){
            return renderJson('100','参数不能为空');
        }
        // $data['powers']=implode(',',$data['powers']);
        $data['upd_time']=time();
        $res=Db::name('role')->where('id',$data['id'])->update($data);
        if($res){
            return renderJson('200','分配权限成功');
        }
    }
}
