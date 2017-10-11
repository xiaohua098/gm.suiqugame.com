<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request; 
class Card extends Com{
    //房卡单人/多人发放
    public function punchOne(){ 
      if(Request::instance()->isPost()){
            $data=Request::instance()->post();
            $uid=explode(',',$data['uid']);//$uid='1';是数组
            $num=$data['num'];
            $mid=$this->mid;
            $time=time();
            foreach($uid as $v){
                 //操作sqlsrv数据库
                $res2=Db::connect('db1')->table('GameScoreInfo')->where('UserId',$v['UserId'])->setInc('InsureScore', $num);
                if(!$res2){
                    return renderJson('10002','操作失败');//操作sqlsrv失败
                }

                $res1=Db::name('punch_card')->insert(['mid'=>$mid,'add_time'=>$time,'uid'=>$v,'num'=>$num]);
                if(!$res1){
                    return renderJson('10000','操作失败');
                }
            }
            return renderJson('200','操作成功');
        }
        return renderJson('101','违法操作');
    }

    //房卡全服发放
    public function punchAll(){ 
      // if(Request::instance()->isPost()){
      //       $data=Request::instance()->post();
      //       $num=$data['num'];
      //       $mid=$this->mid;
      //       $time=time();
      //       
      //操作sqlsrv数据库
      //           $res2=Db::connect('db1')->table('GameScoreInfo')->where('UserID','>',0)->setInc('InsureScore', $num);
      //           if(!$res2){
      //               return renderJson('10002','操作失败');//操作sqlsrv失败
      //           }
            
      //       $user=Db::connect('db1')->table('GameScoreInfo')->field('UserId')->select();//
      //       foreach($user as $v){

      
                
      //           //操作mysql数据库
      //           $res1=Db::name('punch_card')->insert(['mid'=>$mid,'add_time'=>$time,'uid'=>$v['UserId'],'num'=>$num]);
      //           if(!$res1){
      //               return renderJson('10000','操作失败');
      //           }
      //       }
      //       return renderJson('200','操作成功');
      //   }
      //   return renderJson('101','违法操作');



      //事务处理
        $res1=Db::name('punch_card')->where('id','>',0)->setInc('num',10);
            if(!$res1){
                return renderJson('10000','操作失败');
            }
        return renderJson('200','操作成功');
    }


    

    


                 

}