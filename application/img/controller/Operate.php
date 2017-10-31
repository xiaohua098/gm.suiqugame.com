<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use think\Request;
use app\img\model\pub;  
class Operate extends Com{
    public function  operate(){
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
        //某用户消耗房卡记录
        if(Request::instance()->isGet()){
           $data=Request::instance()->param();
           return  $this->operateList($data);
        }
        // 是否为 POST 请求
        if(Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->exportList($data);
        }

       
    }

    //获取操作日志
    public function operateList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        if(empty($data['pagesize'])){
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']),$url);
            return renderJson('10001','参数不能为空');
        }
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];

        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
            $start=$data['start_time'];
            $end=$data['end_time'];
            $total=Db::table('operate_record')->where('add_time','between ',[$start,$end])->count();
            if($offset>$total){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
            }
            
            $record=Db::table('operate_record')->where('add_time','between ',[$start,$end])->order('add_time','desc')->limit($offset,$pagesize)->select();
        
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('operate_record')->count();
        if($offset>$total){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
            }
        
        $record=Db::table('operate_record')->order('add_time','desc')->limit($offset,$pagesize)->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['record'=>$record,'total'=>$total]);    
    }


    //导出操作日志
    public function exportList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
            $start=$data['start_time'];
            $end=$data['end_time'];
            $total=Db::table('operate_record')->where('add_time','between ',[$start,$end])->count();
            $record=Db::table('operate_record')->where('add_time','between ',[$start,$end])->order('add_time','desc')->select();
        
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('operate_record')->count();
        $record=Db::table('operate_record')->order('add_time','desc')->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['record'=>$record,'total'=>$total]);    
    }



}