<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use think\Request;
use app\img\model\pub;  
class Card extends Com{
    public function  card(){
        $flag=$this->flag;
        if($flag){
            return renderJson('10001','token为空或者token已经过期');
        }
        // 是否为 POST 请求
        if(Request::instance()->isPost()){
            $data=Request::instance()->param();
            if($data['type']==1){
                unset($data['type']);
                return  $this->punchOne($data);
            }
           unset($data['type']);
           return  $this->punchAll($data);
        }
        return renderJson('101','违法操作');
    }

    //房卡单人/多人发放
    public function punchOne($data){
            if(empty($data)){
                return renderJson('10001','参数不合法');
            } 
            $uid=trim($data['uid'],',');
            $uid=explode(',',$uid);//$uid='1';是数组
            // var_dump($uid);exit;
            $num=$data['num'];
            $mid=$this->mid;
            $mname=$this->mname;
            $time=time();
            //mysql操作事务
            $my_arr=array();
                foreach ($uid as $k => $v) {
                    $my_arr[$k]['uid']=$v;
                    $my_arr[$k]['add_time']=$time;
                    $my_arr[$k]['mid']=$mid;
                    $my_arr[$k]['mname']=$mname;
                    $my_arr[$k]['num']=$num;
                }
            //判断用户是否存在
            foreach ($uid as $k =>$v) {
                $res=Db::connect('db1')->table('GameScoreInfo')->where('UserId',$v)->find();
                if(!$res){
                    return renderJson('10008','该游戏用户不存在',$v);
                }
            }
            // 启动事务
            Db::startTrans();
            try{
                Db::table('punch_card')->insertAll($my_arr);
                Db::connect('db1')->table('GameScoreInfo')->where('UserId','in',$uid)->setInc('InsureScore', $num);
                // 提交事务
                Db::commit();    
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return renderJson('10002','操作失败');
            }
            return renderJson('1','操作成功');
    }
    //房卡全服发放
    public function punchAll($data){ 
            $num=$data['num'];
            $mid=$this->mid;
            $mname=$this->mname;
            $time=time(); 
            $user=Db::connect('db1')->table('GameScoreInfo')->field('UserID')->select();
            //mysql操作事务
            $my_arr=array();
            foreach ($user as $k => $v) {
                $my_arr[$k]['uid']=$v['UserID'];
                $my_arr[$k]['num']=$num;
                $my_arr[$k]['mname']=$mname;
                $my_arr[$k]['add_time']=$time;
                $my_arr[$k]['mid']=$mid;
            }
            //操作mysql
            // 启动事务
            Db::startTrans();
            try{
                //操作mysql
                Db::table('punch_card')->insertAll($my_arr);
                //操作sqlsrv数据库
                Db::connect('db1')->table('GameScoreInfo')->where('UserID','>',0)->setInc('InsureScore', $num);
                // 提交事务
                Db::commit();    
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return renderJson('10002','操作失败');
            } 
            return renderJson('1','操作成功');
    }             
}