<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Census extends Com{
	public   function    Census(){
		 $flag=$this->flag;
        // var_dump($flag);exit;
        if($flag){
             return renderJson('10001','token为空或者token已经过期');
        }
        if (Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->censusList($data);
        }
        // // 是否为 POST 请求
        // if (Request::instance()->isPost()){
        //     $data=Request::instance()->param();
        //    return  $this->noticeAdd($data);
        // }
        // // // 是否为 PUT 请求
        // // if (Request::instance()->isPut()){
        // //     $data=Request::instance()->param();
        // //    return  $this->noticeEdit($data);
        // // }
        // // 是否为 DELETE 请求
        // if (Request::instance()->isDelete()){
        //    $data=Request::instance()->param();
        //    return  $this->noticeDel($data);
        // };
        $param=$data;
        $model=new pub;
        //写入日志
        $data=Request::instance()->param();
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'101','message'=>'违法操作']));
        return renderJson('101','违法操作');
	}

	public  function censusList($data){
		$param=$data;
        $model=new pub;
		$res=Db::table('total_record')->order('create_time desc')->find();
		//写入日志
        $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'101','message'=>'','data'=>['data'=>$res]]));
		return renderJson('1','',['data'=>$res]);
	}
	
}
