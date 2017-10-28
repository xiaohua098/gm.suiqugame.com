<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Census extends Com{
	public   function    Census(){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
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
        if (Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->censusList($data);
        }
        
        
	}

	public  function censusList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$param=$data;
        $model=new pub;
		$record=Db::table('census')->order('create_time desc')->find();
		// //写入日志
  //       $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'101','message'=>'']),$url);
		return renderJson('1','',['record'=>$record]);
	}

    
	
}
