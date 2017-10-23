<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Agentdaily extends Com{
    public function  agentpunch(){
        $flag=$this->flag;
        if($flag){
             return renderJson('10007','token为空或者token已经过期');
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
        // // 修改姓名和电话(玩家和代理共用)
        // if (Request::instance()->isPut()){
        //     $data=Request::instance()->param();
        //    return  $this->agentEdit($data);
        // }
        
        // // 封禁
        // if (Request::instance()->isDelete()){
        //    $data=Request::instance()->param();
        //    return  $this->userDel($data);
        // };

        $param=$data;
        $model=new pub;
        // //写入日志
        // $data=Request::instance()->param();
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'101','message'=>'违法操作']));
        return renderJson('101','违法操作');
    }


   public  function punchList($data){
        $param=$data;
        $model=new pub;

        if(empty($data['pagesize']) ||  empty($data['uid'])){
            return renderJson('10001','参数不能为空');
        }
        $uid=$data['uid'];
        $agent=Db::table('account')->field('union_id')->where('mssql_account_id',$uid)->find();
        $from_union_id=$agent['union_id'];
        $from_nickname=$agent['nickname'];
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];

        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time'])){
            $start=$data['start_time'];
            $end=$data['end_time'];
            $total=Db::table('transfer_log')->where('from_union_id',$from_union_id)->where('create_time','between ',[$start,$end])->count();
            if($offset == 0){
                $record=Db::table('transfer_log')->field('amount','to_union_id','create_time')->where('from_union_id',$from_union_id)->where('create_time','between ',[$start,$end])->order('create_time','desc')->limit($pagesize)->select();
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
                return renderJson('1','',['record'=>$record,'total'=>$total]);
            }
            $temple=Db::table('transfer_log')->where('from_union_id',$from_union_id)->where('create_time','between ',[$start,$end])->order('create_time','desc')->limit($offset)->select();
            $tid=array_pop($temple);
            $record=Db::table('transfer_log')->where('from_union_id',$from_union_id)->where('create_time','between ',[$start,$end])->where('id','<=',$tid['id'])->order('create_time','desc')->limit($pagesize)->select();
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
            //写入日志
            $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('transfer_log')->where('from_union_id',$from_union_id)->where('create_time','between ',[$start,$end])->count();
        if($offset == 0){
            $record=Db::table('transfer_log')->where('from_union_id',$from_union_id)->where('create_time','between ',[$start,$end])->order('create_time','desc')->limit($pagesize)->select();
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
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $temple=Db::table('transfer_log')->where('from_union_id',$from_union_id)->where('create_time','between ',[$start,$end])->order('create_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $record=Db::table('transfer_log')->where('from_union_id',$from_union_id)->where('create_time','between ',[$start,$end])->where('id','<=',$tid['id'])->order('create_time','desc')->limit($pagesize)->select();
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
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }




    //导出请求
    public  function   exportList($data){
        $param=$data;
        $model=new pub;
         if(empty($data['uid'])){
            return renderJson('10001','参数不能为空');
        }
        $uid=$data['uid'];
        $agent=Db::table('account')->field('union_id')->where('mssql_account_id',$uid)->find();
        $from_union_id=$agent['union_id'];
        $from_nickname=$agent['nickname'];
        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time'])){
            $start=$data['start_time'];
            $end=$data['end_time'];
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
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $total=Db::table('transfer_log')->where('from_union_id',$from_union_id)->count();
        $record=Db::table('transfer_log')->where('from_union_id',$from_union_id)->order('create_time','desc')->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }

}