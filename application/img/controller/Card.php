<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use think\Request;
use app\img\model\pub;  
class Card extends Com{
    public function  card(){
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
        //某用户消耗房卡记录
        if(Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->cardList($data);
        }
        //导出某用户消耗房卡记录
        if(Request::instance()->isPut()){
            $data=Request::instance()->param();
           return  $this->exportList($data);
        }

        
    }

    //房卡单人/多人发放
    public function punchOne($data){
        $param=$data;
        $model=new pub;
            if(empty($data)){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']));
                return renderJson('10001','参数不合法');
            } 

            $uid=trim($data['uid'],',');
            $uid=explode(',',$uid);//$uid='1';是数组
            // var_dump($uid);exit;
            $num=$data['num'];
            $abs_num=abs($num);
            if($abs_num>2147483647){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']));
                return renderJson('10001','参数不合法');
            }
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
                    // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10008','message'=>$v.'该游戏用户不存在']));
                    return renderJson('10008','该游戏用户不存在',$v);
                }
            }
            //减房卡时判断用户剩余房卡
            if($num<0){
                foreach($uid as $k1=>$v1){
                    $card_num=Db::connect('db1')->table('GameScoreInfo')->where('UserID',$v1)->column('InsureScore');
                    if($card_num<$abs_num){
                        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'减房卡数量大于用户房卡剩余数量']));
                        return renderJson('10001','减房卡数量大于用户房卡剩余数量');
                    }
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
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10002','message'=>'操作失败']));
                return renderJson('10002','操作失败');
            }
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','');
    }
    //房卡全服发放
    public function punchAll($data){ 
        $param=$data;
        $model=new pub;
            $num=$data['num'];
            $abs_num=abs($num);
            if($abs_num>2147483647){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']));
                return renderJson('10001','参数不合法');
            }
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
            //减房卡时判断用户剩余房卡
            if($num<0){
                foreach($user as $k1=>$v1){
                    $card_num=Db::connect('db1')->table('GameScoreInfo')->where('UserID',$v1['uid'])->column('InsureScore');
                    if($card_num<$abs_num){
                        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'减房卡数量大于用户房卡剩余数量']));
                        return renderJson('10001','减房卡数量大于用户房卡剩余数量');
                    }
                }
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
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10002','message'=>'操作失败']));
                return renderJson('10002','操作失败');
            } 
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','');
    }  

    public  function  cardList(){
        $param=$data;
        $model=new pub;
        if(empty($data['uid']) && empty($data['phone'])){
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']));
            return renderJson('10001','参数不合法');
        }
       if(empty($data['pagesize'])){
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']));
            return renderJson('10001','参数不能为空');
        }
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];

        
        if($data['uid']){
            $uid=$data['uid'];
            $phone=Db::table('account')->where('mssql_account_id',$data['uid'])->column('phone');
        }
        if($data['phone']){
                $phone=$data['phone'];
                $uid=Db::table('account')->where('phone',$data['phone'])->column('mssql_account_id');
        }
        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
            $start=date('Y-m-d H:i:s',$data['start_time']);
            $end=date('Y-m-d H:i:s',$data['end_time']);
            $total=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->where('CostDate','between ',[$start,$end])->count();
            if($offset == 0){
                $record=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->where('CostDate','between ',[$start,$end])->order('CostDate','desc')->limit($pagesize)->select();
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
                return renderJson('1','',['record'=>$record,'total'=>$total]);
            }
            $temple=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->where('CostDate','between ',[$start,$end])->order('CostDate','desc')->limit($offset)->select();
            $tid=array_pop($temple);
            $record=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->where('CostDate','between ',[$start,$end])->where('id','<=',$tid['id'])->order('CostDate','desc')->limit($pagesize)->select();
        
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->count();
        if($offset == 0){
            $record=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->order('CostDate','desc')->limit($pagesize)->select();
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $temple=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->order('CostDate','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $record=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->where('id','<=',$tid['id'])->order('CostDate','desc')->limit($pagesize)->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
        
    } 


    public  function  exportList(){
        $param=$data;
        $model=new pub;
        
        if(isset($data['uid']) ||  isset($data['phone'])){
            if(isset($data['uid'])){
                $uid=$data['uid'];
                $phone=Db::table('account')->where('mssql_account_id',$data['uid'])->column('phone');
            }
            if(isset($data['phone'])){
                $phone=$data['phone'];
                $uid=Db::table('account')->where('phone',$data['phone'])->column('mssql_account_id');
            }
           if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
            $start=date('Y-m-d H:i:s',$data['start_time']);
            $end=date('Y-m-d H:i:s',$data['end_time']);
            $total=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->where('CostDate','between ',[$start,$end])->count();
            $record=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->where('CostDate','between ',[$start,$end])->where('id','<=',$tid['id'])->order('CostDate','desc')->select();
        
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->count();
        
        $record=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$uid)->order('CostDate','desc')->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']));
        return renderJson('10001','参数不合法');
    }


}