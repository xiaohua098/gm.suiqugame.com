<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Agentdaily extends Com{
    public function  agentdaily(){
        $flag=$this->flag;
        if($flag){
             return renderJson('10007','token为空或者token已经过期');
        }
        //获取代理列表
        if (Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->dailyList($data);
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


   public  function dailyList($data){
        $param=$data;
        $model=new pub;

        if(empty($data['pagesize']) ||  empty($data['uid'])){
            return renderJson('10001','参数不能为空');
        }
        $uid=$data['uid'];
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];

        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time'])){
            $start=$data['start_time'];
            $end=$data['end_time'];
            $total=Db::table('agent_daily')->where('uid',$uid)->where('add_time','between ',[$start,$end])->count();
            if($offset == 0){
                $record=Db::table('agent_daily')->where('uid',$uid)->where('add_time','between ',[$start,$end])->order('add_time','desc')->limit($pagesize)->select();
                return renderJson('1','',['record'=>$record,'total'=>$total]);
            }
            $temple=Db::table('agent_daily')->where('uid',$uid)->where('add_time','between ',[$start,$end])->order('add_time','desc')->limit($offset)->select();
            $tid=array_pop($temple);
            $record=Db::table('agent_daily')->where('uid',$uid)->where('add_time','between ',[$start,$end])->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
        
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('agent_daily')->where('uid',$uid)->where('add_time','between ',[$start,$end])->count();
        if($offset == 0){
            $record=Db::table('agent_daily')->where('uid',$uid)->order('add_time','desc')->limit($pagesize)->select();
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $temple=Db::table('agent_daily')->where('uid',$uid)->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $record=Db::table('agent_daily')->where('uid',$uid)->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
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
        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time'])){
            $start=$data['start_time'];
            $end=$data['end_time'];
            $total=Db::table('agent_daily')->where('uid',$uid)->where('add_time','between',[$start,$end])->count();
            $record=Db::table('agent_daily')->where('uid',$uid)->where('add_time','between',[$start,$end])->order('add_time','desc')->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $total=Db::table('agent_daily')->where('uid',$uid)->count();
        $record=Db::table('agent_daily')->where('uid',$uid)->order('add_time','desc')->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'','data'=>['record'=>$record,'total'=>$total]]));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }

}