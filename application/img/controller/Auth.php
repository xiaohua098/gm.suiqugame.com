<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
class Auth extends Com{
    //删除权限
    public function authList(){
          return renderJson('100','获取成功');
            // $count=10;
            // $res=Db::name('auth')->paginate($count);
            // $page=$res->render();
            // $data=array('auth'=>$res,'page'=>$page);
            // if($res){
            //     return renderJson('200','',$data);
            // }
            // return renderJson('100','获取失败');
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
