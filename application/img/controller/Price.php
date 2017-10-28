<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use think\Request;
use app\img\model\pub;  
class Price extends Com{
    public function  price(){
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
           return  $this->priceList($data);
        }
        //导出某用户消耗房卡记录
        if(Request::instance()->isPut()){
            $data=Request::instance()->param();
           return  $this->priceEdit($data);
        }

       
    }

    
    public function priceList($data){ 
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
            $res=Db::table('agent_preset')->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',$res);
    }  

    public  function  priceEdit($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        if(isset($data['level']) && isset($data['price']) && $data['price']){
            if($data['level']<0 || $data['level']>3 || abs($data['ptice'])>2147483647 || $data['price']<0 ){
                // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
                return renderJson('10001','参数不合法');
            }
            $res=Db::table('agent_preset')->update(['level'=>$data['level'],'price'=>$data['price'],'update_time'=>time()]);
            if($res){
                // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('1');
            }
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10000','message'=>'操作失败']),$url);
            return renderJson('10000','操作失败');
        }
        // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
        return renderJson('10001','参数不合法');
    }

    

}