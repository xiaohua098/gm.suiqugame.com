<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class AgentPunch extends Com{
    public function  agentpunch(){
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
           return  $this->punchList($data);
        }

        // 导出数据
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->exportList($data);
        }
       
    }


   public  function punchList($data){
    $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;

        if(empty($data['pagesize']) ||  empty($data['uid'])){
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']),$url);
            return renderJson('10001','参数不能为空');
        }
        $uid=$data['uid'];
        $agent=Db::table('account')->field('union_id')->where('mssql_account_id',$uid)->find();
        $from_union_id=$agent['union_id'];
        $from_nickname=$agent['nickname'];
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];

        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
            $start=date('Y-m-d',$data['start_time']);
            $end=date('Y-m-d',$data['end_time']);
            $total=Db::table('transfer_log')->where('from_union_id',$from_union_id)->where('create_time','between ',[$start,$end])->count();
                if($offset>$total){
                    // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
                return renderJson('10001','参数不合法');
                }
            $record=Db::table('transfer_log')->where('from_union_id',$from_union_id)->where('create_time','between ',[$start,$end])->order('create_time','desc')->limit($offset,$pagesize)->select();
            //获取对象ID、昵称、级别
                if(!empty($record)){
                    foreach ($record as $k => $v) {
                        $res=Db::table('account')->where(['union_id'],$v['to_union_id'])->find();
                        if($res){
                            $record[$k]['to_uid']=$res['mssql_account_id'];
                            $record[$k]['to_nickname']=$res['nick_name'];
                            $record[$k]['to_level']=$res['level'];
                        }else{
                           $res1=Db::connect('db2')->table('AccountsInfo')->where('unionid',$v['to_union_id'])->find();
                           $record[$k]['to_uid']=$res['UserID'];
                           $record[$k]['to_nickname']=$res['NickName'];
                           $record[$k]['to_level']=0; 
                        }
                        $record[$k]['from_uid']=$uid;
                        $record[$k]['from_nickname']=$from_nickname;
                    }
                }
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        //非时间段查询
        $total=Db::table('transfer_log')->where('from_union_id',$from_union_id)->count();
        if($offset>$total){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
            }
        
        $record=Db::table('transfer_log')->where('from_union_id',$from_union_id)->order('create_time','desc')->limit($offset,$pagesize)->select();
        //获取对象ID、昵称、级别
                if(!empty($record)){
                    foreach ($record as $k => $v) {
                        $res=Db::table('account')->where(['union_id'],$v['to_union_id'])->find();
                        if($res){
                            $record[$k]['to_uid']=$res['mssql_account_id'];
                            $record[$k]['to_nickname']=$res['nick_name'];
                            $record[$k]['to_level']=$res['level'];
                        }else{
                           $res1=Db::connect('db2')->table('AccountsInfo')->where('unionid',$v['to_union_id'])->find();
                           $record[$k]['to_uid']=$res['UserID'];
                           $record[$k]['to_nickname']=$res['NickName'];
                           $record[$k]['to_level']=0; 
                        }
                        $record[$k]['from_uid']=$uid;
                        $record[$k]['from_nickname']=$from_nickname;
                    }
                }
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }




    //导出请求
    public  function   exportList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
         if(empty($data['uid'])){
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']),$url);
            return renderJson('10001','参数不能为空');
        }
        $uid=$data['uid'];
        $agent=Db::table('account')->field('union_id')->where('mssql_account_id',$uid)->find();
        $from_union_id=$agent['union_id'];
        $from_nickname=$agent['nickname'];
        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time']  && $data['end_time']){
            $start=date('Y-m-d',$data['start_time']);
            $end=date('Y-m-d',$data['end_time']);
            $total=Db::table('transfer_log')->where('from_union_id',$from_union_id)->where('create_time','between',[$start,$end])->count();
            $record=Db::table('transfer_log')->where('from_union_id',$from_union_id)->where('create_time','between',[$start,$end])->order('create_time','desc')->select();
            //获取对象ID、昵称、级别
                if(!empty($record)){
                    foreach ($record as $k => $v) {
                        $res=Db::table('account')->where(['union_id'],$v['to_union_id'])->find();
                        if($res){
                            $record[$k]['to_uid']=$res['mssql_account_id'];
                            $record[$k]['to_nickname']=$res['nick_name'];
                            $record[$k]['to_level']=$res['level'];
                        }else{
                           $res1=Db::connect('db2')->table('AccountsInfo')->where('unionid',$v['to_union_id'])->find();
                           $record[$k]['to_uid']=$res['UserID'];
                           $record[$k]['to_nickname']=$res['NickName'];
                           $record[$k]['to_level']=0; 
                        }
                        $record[$k]['from_uid']=$uid;
                        $record[$k]['from_nickname']=$from_nickname;
                    }
                }
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $total=Db::table('transfer_log')->where('from_union_id',$from_union_id)->count();
        $record=Db::table('transfer_log')->where('from_union_id',$from_union_id)->order('create_time','desc')->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),url);
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }

}