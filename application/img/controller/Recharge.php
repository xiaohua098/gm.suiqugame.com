<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Recharge extends Com{
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

        
	}

	public  function rechargeList($data){
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

        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
            $start=date('Y-m-d',$data['start_time']);
            $end=date('Y-m-d',$data['end_time']);
            $total=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->count();
            if($offset>$total){
                //写入日志
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
            }
            
            $record=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->order('add_time','desc')->limit($offset,$pagesize)->select();
        
            //写入日志
            $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('recharge_total')->count();
        if($offset>$total){
                //写入日志
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
            }
        
        $record=Db::table('recharge_total')->order('add_time','desc')->limit($offset,$pagesize)->select();
        //写入日志
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['record'=>$record,'total'=>$total]);
	}

    //导出请求
    public  function   exportList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;

        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
            $start=date('Y-m-d',$data['start_time']);
            $end=date('Y-m-d',$data['end_time']);
            $total=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->count();
            $record=Db::table('recharge_total')->where('add_time','between ',[$start,$end])->order('add_time','desc')->select();
            //写入日志
            $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('recharge_total')->count();
        $record=Db::table('recharge_total')->order('add_time','desc')->select();
        //写入日志
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }
}
