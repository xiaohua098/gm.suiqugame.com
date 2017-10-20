<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Recharge extends Com{
	public   function    Recharge(){
		 $flag=$this->flag;
        // var_dump($flag);exit;
        if($flag){
             return renderJson('10001','token为空或者token已经过期');
        }
        //充值统计
        if (Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->rechargeList($data);
        }
        //充值统计导出 
        // 是否为 POST 请求
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->exportList($data);
        }
        //获取某个用户的充值记录  时间戳
        // 是否为 PUT 请求
        if(Request::instance()->isPut()){
            $data=Request::instance()->param();
           return  $this->userRecharge($data);
        }
        //导出某个用户的充值记录  时间戳
        // 是否为 DELETE 请求
        if (Request::instance()->isDelete()){
           $data=Request::instance()->param();
           return  $this->exportUser($data);
        };

        $param=$data;
        $model=new pub;
        //写入日志
        $data=Request::instance()->param();
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'101','message'=>'违法操作']));
        return renderJson('101','违法操作');
	}

	public  function rechargeList($data){
		$param=$data;
        $model=new pub;

        if(empty($data['pagesize'])){
            return renderJson('10001','参数不能为空');
        }
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];

        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time'])){
            $start=$data['start_time'];
            $end=$data['end_time'];
            $total=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->count();
            if($offset == 0){
                $record=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->order('add_time','desc')->limit($pagesize)->select();
                return renderJson('1','',['record'=>$record,'total'=>$total]);
            }
            $temple=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->order('add_time','desc')->limit($offset)->select();
            $tid=array_pop($temple);
            $record=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
        
            //写入日志
            $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->count();
        if($offset == 0){
            $record=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->order('add_time','desc')->limit($pagesize)->select();
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $temple=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $record=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
        //写入日志
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
	}

    //导出请求
    public  function   exportList($data){
        $param=$data;
        $model=new pub;

        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time'])){
            $start=$data['start_time'];
            $end=$data['end_time'];
            $total=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->count();
            $record=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->order('add_time','desc')->select();
            //写入日志
            $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('recharge_total')->count();
        $record=Db::table('recharge_total')->order('add_time','desc')->select();
        //写入日志
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }



    //获取某用户充值记录
    public  function  userRecharge($data){
       $param=$data;
       $model=new pub;
        if(empty($data['pagesize']) || empty($data['uid'])){
            return renderJson('10001','参数不能为空');
        }
        $uid=$data['uid'];
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];

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
        if(isset($data['start_time']) && isset($data['end_time'])){
            $start=$data['start_time'];
            $end=$data['end_time'];
            $total=Db::table('order_info')->where('create_time','between ',[$start,$end])->where('union_id',$union_id)->count();
            if($offset == 0){
                $record=Db::table('order_info')->field('union_id','money','amount','code','state','create_time')->where('create_time','between ',[$start,$end])->where('union_id',$union_id)->order('create_time','desc')->limit($pagesize)->select();
                //获取用户昵称和代理等级
                foreach($record as $k => $v) {
                    $record[$k]['uid']=$uid;
                    $record[$k]['nickname']=$nickname;
                    if($level){
                        $record[$k]['level']=$level;
                    }
                }
                return renderJson('1','',['record'=>$record,'total'=>$total]);
            }
            $temple=Db::table('order_info')->where('create_time','between ',[$start,$end])->where('union_id',$union_id)->order('create_time','desc')->limit($offset)->select();
            $tid=array_pop($temple);
            $record=Db::table('order_info')->where('create_time','between',[$start,$end])->where('union_id',$union_id)->where('id','<=',$tid['id'])->order('create_time','desc')->limit($pagesize)->select();
            //获取用户昵称和代理等级
             foreach($record as $k => $v) {
                    $record[$k]['uid']=$uid;
                    $record[$k]['nickname']=$nickname;
                    if($level){
                        $record[$k]['level']=$level;
                    }
                }
            //写入日志
            $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('order_info')->where('create_time','between ',[$start,$end])->where('union_id',$union_id)->count();
        if($offset == 0){
            $record=Db::table('order_info')->where('create_time','between ',[$start,$end])->where('union_id',$union_id)->order('create_time','desc')->limit($pagesize)->select();
            //获取用户昵称和代理等级
             foreach($record as $k => $v) {
                    $record[$k]['uid']=$uid;
                    $record[$k]['nickname']=$nickname;
                    if($level){
                        $record[$k]['level']=$level;
                    }
                }
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $temple=Db::table('order_info')->where('create_time','between ',[$start,$end])->where('union_id',$union_id)->order('create_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $record=Db::table('order_info')->where('create_time','between ',[$start,$end])->where('union_id',$union_id)->where('id','<=',$tid['id'])->order('create_time','desc')->limit($pagesize)->select();
        //获取用户昵称和代理等级
             foreach($record as $k => $v) {
                    $record[$k]['uid']=$uid;
                    $record[$k]['nickname']=$nickname;
                    if($level){
                        $record[$k]['level']=$level;
                    }
                }
        //写入日志
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }



    //导出某用户充值记录
    public  function  exportUser($data){
       $param=$data;
       $model=new pub;

       if(empty($data['uid'])){
            return renderJson('10001','参数不能为空');
        }
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
        if(isset($data['start_time']) && isset($data['end_time'])){
            $start=$data['start_time'];
            $end=$data['end_time'];
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
            $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
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
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }


	
}