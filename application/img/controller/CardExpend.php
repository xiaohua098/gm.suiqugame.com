<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class CardExpend extends Com{
    public function  cardexpend(){
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
        //获取代理列表
        if (Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->cardList($data);
        }

        // 导出数据
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->exportList($data);
        }
       
    }


   public  function  cardList($data){
    $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        if(empty($data['uid']) && empty($data['phone'])){
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
        }
       if(empty($data['pagesize'])){
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']),$url);
            return renderJson('10001','参数不能为空');
        }
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];

        
        if(isset($data['uid']) && $data['uid']){
            $uid=$data['uid'];
            $phone=Db::table('account')->where('mssql_account_id',$data['uid'])->value('phone');
        }
        if(isset($data['phone']) && $data['phone']){
            $phone=$data['phone'];
            $uid=Db::table('account')->where('phone',$data['phone'])->value('mssql_account_id');
        }
        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
            $start=date('Y-m-d H:i:s',$data['start_time']);
            $end=date('Y-m-d H:i:s',$data['end_time']);
            $total=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',1)->whereOr('CostFrom',2)->where('UserID',$uid)->where('CostDate','between ',[$start,$end])->count();
            if($offset>$total){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
            }
            if($offset == 0){
                $record=Db::connect('db3')->table('RecordPrivateCost')
                ->where('CostFrom',1)
                ->whereOr('CostFrom',2)
                ->where('UserID',$uid)
                ->where('CostDate','between ',[$start,$end])
                ->order('CostDate','desc')
                ->limit($pagesize)->select();
                //游戏名称和电话号码
                foreach ($record as $k => $v) {
                    $record[$k]['phone']=$phone;
                    $record[$k]['KindName']=Db::connect('db4')->table('GameKindItem')->where('KindID',$v['KindID'])->value('KindName');
                }
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('1','',['record'=>$record,'total'=>$total]);
            }
            $temple=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',1)->whereOr('CostFrom',2)->where('UserID',$uid)->where('CostDate','between ',[$start,$end])->order('CostDate','desc')->limit($offset)->select();
            $tid=array_pop($temple);
            $record=Db::connect('db3')->table('RecordPrivateCost')
                  ->where('CostFrom',1)
                  ->whereOr('CostFrom',2)
                  ->where('UserID',$uid)
                  ->where('CostDate','between ',[$start,$end])
                  ->where('RecordID','<=',$tid['RecordID'])
                  ->order('CostDate','desc')
                  ->limit($pagesize)->select();
            //游戏名称和电话号码
                foreach ($record as $k => $v) {
                    $record[$k]['phone']=$phone;
                    $record[$k]['KindName']=Db::connect('db4')->table('GameKindItem')->where('KindID',$v['KindID'])->value('KindName');
                }
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',1)->whereOr('CostFrom',2)->where('UserID',$uid)->count();
        if($offset>$total){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
            }
        if($offset == 0){
            $record=Db::connect('db3')->table('RecordPrivateCost')
                    ->where('CostFrom',1)
                    ->whereOr('CostFrom',2)
                    ->where('UserID',$uid)
                    ->order('CostDate','desc')
                    ->limit($pagesize)->select();
                    //游戏名称和电话号码
                foreach ($record as $k => $v) {
                    $record[$k]['phone']=$phone;
                    $record[$k]['KindName']=Db::connect('db4')->table('GameKindItem')->where('KindID',$v['KindID'])->value('KindName');
                }
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $temple=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',1)->whereOr('CostFrom',2)->where('UserID',$uid)->order('CostDate','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $record=Db::connect('db3')->table('RecordPrivateCost')
              ->where('CostFrom',1)
              ->whereOr('CostFrom',2)
              ->where('UserID',$uid)
              ->where('RecordID','<=',$tid['RecordID'])
              ->order('CostDate','desc')
              ->limit($pagesize)->select();
              //游戏名称和电话号码
                foreach ($record as $k => $v) {
                    $record[$k]['phone']=$phone;
                    $record[$k]['KindName']=Db::connect('db4')->table('GameKindItem')->where('KindID',$v['KindID'])->value('KindName');
                }
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['record'=>$record,'total'=>$total]);
        
    } 


    public  function  exportList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        
        if(isset($data['uid']) ||  isset($data['phone'])){
            if(isset($data['uid'])){
                $uid=$data['uid'];
                $phone=Db::table('account')->where('mssql_account_id',$data['uid'])->value('phone');
            }
            if(isset($data['phone'])){
                $phone=$data['phone'];
                $uid=Db::table('account')->where('phone',$data['phone'])->value('mssql_account_id');
            }
           if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
            $start=date('Y-m-d H:i:s',$data['start_time']);
            $end=date('Y-m-d H:i:s',$data['end_time']);
            $total=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',1)->whereOr('CostFrom',2)->where('UserID',$uid)->where('CostDate','between ',[$start,$end])->count();
            $record=Db::connect('db3')->table('RecordPrivateCost')
                   ->where('CostFrom',1)
                   ->whereOr('CostFrom',2)
                   ->where('UserID',$uid)
                   ->where('CostDate','between ',[$start,$end])
                   ->where('RecordID','<=',$tid['RecordID'])
                   ->order('CostDate','desc')->select();
            //游戏名称和电话号码
                foreach ($record as $k => $v) {
                    $record[$k]['phone']=$phone;
                    $record[$k]['KindName']=Db::connect('db4')->table('GameKindItem')->where('KindID',$v['KindID'])->value('KindName');
                }
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',1)->whereOr('CostFrom',2)->where('UserID',$uid)->count();
        
        $record=Db::connect('db3')->table('RecordPrivateCost')
                ->where('CostFrom',1)
                ->whereOr('CostFrom',2)
                ->where('UserID',$uid)
                ->order('CostDate','desc')->select();
                //游戏名称和电话号码
                foreach ($record as $k => $v) {
                    $record[$k]['phone']=$phone;
                    $record[$k]['KindName']=Db::connect('db4')->table('GameKindItem')->where('KindID',$v['KindID'])->value('KindName');
                }
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
        return renderJson('10001','参数不合法');
    }


}