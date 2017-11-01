<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class UserRecharge extends Com{
	public   function    recharge(){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$model=new pub;
        $param=Request::instance()->param();
        $flag=$this->flag;
        if($flag == '1'){
            //写入日志
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10007','message'=>'token为空或者token已经过期']),$url);
            return renderJson('10007','token为空或者token已经过期');
        }
        if($flag == '2'){
            //写入日志
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'101','message'=>'违法操作']),$url);
            return renderJson('101','违法操作');
        }
        //获取某个用户的充值记录  时间戳
        // 是否为 GET请求
        if(Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->userRecharge($data);
        }
        //导出某个用户的充值记录  时间戳
        // 是否为 DELETE 请求
        if (Request::instance()->isPost()){
           $data=Request::instance()->param();
           return  $this->exportUser($data);
        };  
	}

    //获取某用户充值记录
    public  function  userRecharge($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
       $param=$data;
       $model=new pub;
        if(empty($data['pagesize'])){
            //写入日志
            $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']),$url);
            return renderJson('10001','参数不能为空');
        }
         $offset=$data['offset'];
        $pagesize=$data['pagesize'];
        if(isset($data['uid']) && $data['uid']){
                $uid=$data['uid'];
                $agent=Db::table('account')->field('union_id')->where('mssql_account_id',$uid)->find();
                  if(empty($agent)){
                    $user=Db::connect('db2')->table('AccountsInfo')->where('UserID',$uid)->find();
                    $union_id=$user['unionid'];
                    $nickname=$user['NickName'];
                  }else{
                    $union_id=$agent['union_id'];
                    $nickname=$agent['nickname'];
                    $level=$agent['level'];
                  }

                //时间段查询
                if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
                    $start=date('Y-m-d H:i:s',$data['start_time']);
                    $end=date('Y-m-d H:i:s',$data['end_time']);
                    $total=Db::table('order_info')->where('create_time','between ',[$start,$end])->where('union_id',$union_id)->count();
                     if($offset>$total){
                        //写入日志
                        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
                    return renderJson('10001','参数不合法');
                    }
                    
                    $record=Db::table('order_info')->where('create_time','between',[$start,$end])->where('union_id',$union_id)->order('create_time','desc')->limit($offset,$pagesize)->select();
                    //获取用户昵称和代理等级
                     foreach($record as $k => $v) {
                            $record[$k]['uid']=$uid;
                            $record[$k]['nickname']=$nickname;
                            if($level){
                                $record[$k]['level']=$level;
                            }
                        }
                    //写入日志
                    $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                    return renderJson('1','',['record'=>$record,'total'=>$total]);
                }

                $total=Db::table('order_info')->where('union_id',$union_id)->count();
                 if($offset>$total){
                        //写入日志
                    $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
                    return renderJson('10001','参数不合法');
                    }
                
                $record=Db::table('order_info')->where('union_id',$union_id)->order('create_time','desc')->limit($offset,$pagesize)->select();
                //获取用户昵称和代理等级
                     foreach($record as $k => $v) {
                            $record[$k]['uid']=$uid;
                            $record[$k]['nickname']=$nickname;
                            if($level){
                                $record[$k]['level']=$level;
                            }
                        }
                // //写入日志
                // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        //非用户
        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
                    $start=date('Y-m-d H:i:s',$data['start_time']);
                    $end=date('Y-m-d H:i:s',$data['end_time']);
                    $total=Db::table('order_info')->where('create_time','between ',[$start,$end])->count();
                     if($offset>$total){
                        //写入日志
                        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
                    return renderJson('10001','参数不合法');
                    }
                    
                    $record=Db::table('order_info')
                            ->alias('a')
                                ->join('account b','a.union_id=b.union_id','LEFT')
                                ->field('b.mssql_account_id as uid,a.union_id,a.money,a.amount,a.code,a.state,a.create_time,b.nickname,b.level')
                            ->where('a.create_time','between',[$start,$end])
                            ->order('a.create_time','desc')
                            ->limit($offset,$pagesize)->select();
                    //写入日志
                    $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                    return renderJson('1','',['record'=>$record,'total'=>$total]);
                }

                $total=Db::table('order_info')->count();
                 if($offset>$total){
                        //写入日志
                $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
                    return renderJson('10001','参数不合法');
                    }
                
                $record=Db::table('order_info')
                        ->alias('a')
                        ->join('account b','a.union_id=b.union_id','LEFT')
                        ->field('b.mssql_account_id as uid,a.union_id,a.money,a.amount,a.code,a.state,a.create_time,b.nickname,b.level')
                       ->order('a.create_time','desc')
                       ->limit($offset,$pagesize)->select();
                //写入日志
                $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('1','',['record'=>$record,'total'=>$total]);
       
    }



    //导出某用户充值记录
    public  function  exportUser($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
       $param=$data;
       $model=new pub;

       if(isset($data['uid']) && $data['uid']){
              $uid=$data['uid'];
                $agent=Db::table('account')->field('union_id')->where('mssql_account_id',$uid)->find();
                if(empty($agent)){
                    $user=Db::connect('db2')->table('AccountsInfo')->where('UserID',$uid)->find();
                    $union_id=$user['unionid'];
                    $nickname=$user['NickName'];
                }else{
                    $union_id=$agent['union_id'];
                    $nickname=$agent['nickname'];
                    $level=$agent['level'];
                }
                //时间段查询
                if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
                    $start=date('Y-m-d H:i:s',$data['start_time']);
                    $end=date('Y-m-d H:i:s',$data['end_time']);
                    $total=Db::table('order_info')->where('create_time','between ',[$start,$end])->where('union_id',$union_id)->count();
                    $record=Db::table('order_info')->where('create_time','between ',[$start,$end])->where('union_id',$union_id)->where('id','<=',$tid['id'])->order('create_time','desc')->select();
                    //获取用户昵称和代理等级
                     foreach($record as $k => $v) {
                            $record[$k]['uid']=$uid;
                            $record[$k]['nickname']=$nickname;
                            if($level){
                                $record[$k]['level']=$level;
                            }
                        }
                    //写入日志
                    $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                    return renderJson('1','',['record'=>$record,'total'=>$total]);
                }

                $total=Db::table('order_info')->where('union_id',$union_id)->count();
                
                $record=Db::table('order_info')->where('union_id',$union_id)->order('create_time','desc')->select();
                //获取用户昵称和代理等级
                     foreach($record as $k => $v) {
                            $record[$k]['uid']=$uid;
                            $record[$k]['nickname']=$nickname;
                            if($level){
                                $record[$k]['level']=$level;
                            }
                        }
                //写入日志
                $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        //非用户
        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
                    $start=date('Y-m-d H:i:s',$data['start_time']);
                    $end=date('Y-m-d H:i:s',$data['end_time']);
                    $total=Db::table('order_info')->where('create_time','between ',[$start,$end])->count();
                     
                    $record=Db::table('order_info')
                            ->alias('a')
                                ->join('account b','a.union_id=b.union_id','LEFT')
                                ->field('b.mssql_account_id as uid,a.union_id,a.money,a.amount,a.code,a.state,a.create_time,b.nickname,b.level')
                            ->where('a.create_time','between',[$start,$end])
                            ->order('a.create_time','desc')
                            ->select();
                    //写入日志
                    $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                    return renderJson('1','',['record'=>$record,'total'=>$total]);
                }

                $total=Db::table('order_info')->count();
                
                $record=Db::table('order_info')
                        ->alias('a')
                        ->join('account b','a.union_id=b.union_id','LEFT')
                        ->field('b.mssql_account_id as uid,a.union_id,a.money,a.amount,a.code,a.state,a.create_time,b.nickname,b.level')
                       ->order('a.create_time','desc')
                       ->select();
                //写入日志
                $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('1','',['record'=>$record,'total'=>$total]);
        
    }


	
}
