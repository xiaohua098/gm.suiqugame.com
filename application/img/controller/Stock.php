<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Stock extends Com{
	public   function    stock(){
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
           return  $this->stockList($data);
        }
        // 是否为 POST 请求
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->exportList($data);
        }
        // // // 是否为 PUT 请求
        // // if (Request::instance()->isPut()){
        // //     $data=Request::instance()->param();
        // //    return  $this->recordEdit($data);
        // // }
        // // 是否为 DELETE 请求
        // if (Request::instance()->isDelete()){
        //    $data=Request::instance()->param();
        //    return  $this->noticeDel($data);
        // };
        
	}

	public  function stockList($data){
		$param=$data;
        $model=new pub;

        if(empty($data['pagesize'])){
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']));
            return renderJson('10001','参数不能为空');
        }
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];

        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
            $start=date('Y-m-d',$data['start_time']);
            $end=date('Y-m-d',$data['end_time']);
            $total=Db::table('stock_total')->where('add_time','between ',[$start,$end])->count();
            if($offset == 0){
                $record=Db::table('stock_total')->where('add_time','between ',[$start,$end])->order('add_time','desc')->limit($pagesize)->select();
                // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
                return renderJson('1','',['record'=>$record,'total'=>$total]);
            }
            $temple=Db::table('stock_total')->where('add_time','between ',[$start,$end])->order('add_time','desc')->limit($offset)->select();
            $tid=array_pop($temple);
            $record=Db::table('stock_total')->where('add_time','between ',[$start,$end])->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
        
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        //非时间段查询
        $total=Db::table('stock_total')->count();
        if($offset == 0){
            $record=Db::table('stock_total')->order('add_time','desc')->limit($pagesize)->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $temple=Db::table('stock_total')->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $record=Db::table('stock_total')->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
	}

    //导出请求
    public  function   exportList($data){
        $param=$data;
        $model=new pub;

        //时间段查询
        if(isset($data['start_time']) && isset($data['end_time']) && $data['start_time'] && $data['end_time']){
            $start=date('Y-m-d',$data['start_time']);
            $end=date('Y-m-d',$data['end_time']);
            $total=Db::table('stock_total')->where('add_time','between ',[$start,$end])->count();
            $record=Db::table('stock_total')->where('add_time','between ',[$start,$end])->order('add_time','desc')->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('stock_total')->count();
        $record=Db::table('stock_total')->order('add_time','desc')->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }
	

   
}
