<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class Agent extends Com{
    public function  user(){
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
        //获取代理列表
        if (Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->agentList($data);
        }

        // 导出数据
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->exportList($data);
        }
        // 修改姓名和电话(玩家和代理共用)
        if (Request::instance()->isPut()){
            $data=Request::instance()->param();
           return  $this->agentEdit($data);
        }
        
        // // 封禁
        // if (Request::instance()->isDelete()){
        //    $data=Request::instance()->param();
        //    return  $this->userDel($data);
        // };

        
    }


    public  function agentList($data){
        $param=$data;
        $model=new pub;

        //游戏ID查询
        if(isset($data['uid'])){
            $total=Db::table('user')->where('level','>',0)->where('uid',$data['uid'])->count();
            $record=Db::table('user')->where('level','>',0)->where('uid',$data['uid'])->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        //电话查询
        if(isset($data['phone'])){
            $total=Db::table('user')->where('level','>',0)->where('phone',$data['phone'])->count();
            $record=Db::table('user')->where('level','>',0)->where('phone',$data['phone'])->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        if(empty($data['pagesize'])){
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']));
            return renderJson('10001','参数不能为空');
        }
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];

        $total=Db::table('user')->where('level','>',0)->count();
        if($offset == 0){
            $record=Db::table('user')->where('level','>',0)->order('add_time','desc')->limit($pagesize)->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $temple=Db::table('user')->where('level','>',0)->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $record=Db::table('user')->where('level','>',0)->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }

    //导出请求
    public  function   exportList($data){
        $param=$data;
        $model=new pub;

        //游戏ID查询
        if(isset($data['uid'])){
            $total=Db::table('user')->where('level','>',0)->where('uid',$data['uid'])->count();
            $record=Db::table('user')->where('level','>',0)->where('uid',$data['uid'])->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        //电话查询
        if(isset($data['phone'])){
            $total=Db::table('user')->where('level','>',0)->where('phone',$data['phone'])->count();
            $record=Db::table('user')->where('level','>',0)->where('phone',$data['phone'])->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('user')->count();
        $record=Db::table('user')->where('level','>',0)->order('add_time','desc')->limit($pagesize)->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }



    //修改姓名和电话(玩家和代理共用)
    public   function  agentEdit($data){
        $model=new pub;
        $param=$data;
        if(isset($data['uid']) && isset($data['phone']) && isset($data['realname']))){
            $res=Db::table('Account')->where('phone',$data['phone'])->find();
            if($res){
            //     //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10005','message'=>'该电话号码已注册！']));
                return renderJson('10005','该电话号码已注册！');
            }
            //获取用户之前的代理等级
            $res2=Db::table('account')->where('mssql_account_id',$data['uid'])->find();
            if(empty($res2)){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10008','message'=>'该用户未登录公众号']));
                return  renderJson('10008','该用户未登录公众号！');  
            }
            
            $res3=Db::table('account')->where('mssql_account_id',$data['uid'])->update([
                                'phone'=>$data['phone'],
                                'real_name'=>$data['real_name'],
                                'update_time'=>time(),   //修改姓名和电话时间
            if(empty($res3)){
                // //写入日志
                //     $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'操作失败']));
                    return renderJson('10002','操作失败');
            }                  
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']));
            return renderJson('1','');
        }
       
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']));
        return renderJson('10001','参数不合法');
        
    }
}