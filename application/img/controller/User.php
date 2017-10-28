<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;
class User extends Com{
    public function  user(){
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
        //获取玩家列表
        if (Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->userList($data);
        }

        // 导出数据
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->exportList($data);
        }
        // 提升代理(玩家和代理共用)
        if (Request::instance()->isPut()){
            $data=Request::instance()->param();
           return  $this->userEdit($data);
        }
        
        // // 封禁
        // if (Request::instance()->isDelete()){
        //    $data=Request::instance()->param();
        //    return  $this->userDel($data);
        // };

        
    }
    public  function userList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;

        //游戏ID查询
        if(isset($data['uid'])){
            $total=Db::table('user')->where('uid',$data['uid'])->where('level',0)->count();
            $record=Db::table('user')->where('uid',$data['uid'])->where('level',0)->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        //电话查询
        if(isset($data['phone'])){
            $total=Db::table('user')->where('phone',$data['phone'])->where('level',0)->count();
            $record=Db::table('user')->where('phone',$data['phone'])->where('level',0)->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        if(empty($data['pagesize'])){
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']),$url);
            return renderJson('10001','参数不能为空');
        }
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];

        $total=Db::table('user')->where('level',0)->count();
         if($offset>$total){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
            }
        if($offset == 0){
            $record=Db::table('user')->order('add_time','desc')->where('level',0)->limit($pagesize)->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        $temple=Db::table('user')->where('level',0)->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $record=Db::table('user')->where('id','<=',$tid['id'])->where('level',0)->order('add_time','desc')->limit($pagesize)->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }

    //导出请求
    public  function   exportList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;

        //游戏ID查询
        if(isset($data['uid'])){
            $total=Db::table('user')->where('level',0)->where('uid',$data['uid'])->count();
            $record=Db::table('user')->where('level',0)->where('uid',$data['uid'])->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }
        //电话查询
        if(isset($data['phone'])){
            $total=Db::table('user')->where('level',0)->where('phone',$data['phone'])->count();
            $record=Db::table('user')->where('level',0)->where('phone',$data['phone'])->select();
            // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['record'=>$record,'total'=>$total]);
        }

        $total=Db::table('user')->where('level',0)->count();
        $record=Db::table('user')->where('level',0)->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['record'=>$record,'total'=>$total]);
    }



    //提升代理(玩家和代理共用)
    public   function  userEdit($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $model=new pub;
        $param=$data;
        //玩家升级为代理
        if(isset($data['uid']) && isset($data['phone']) && isset($data['realname']) && isset($data['level'])){
            $res=Db::table('Account')->where('phone',$data['phone'])->find();
            if($res){
            //     //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10005','message'=>'该电话号码已注册']),$url);
                return renderJson('10005','该电话号码已注册！');
            }
            //获取用户之前的代理等级
            $res2=Db::table('account')->field('union_id,level')->where('mssql_account_id',$data['uid'])->find();
            if(empty($res2)){
                // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10008','message'=>'该用户未登录公众号']),$url);
                return  renderJson('10008','该用户未登录公众号');  
            }
            // 启动事务
                Db::startTrans();
                try{
                         Db::table('agent_log')->insert([
                            'union_id'=>$res2['union_id'],
                            'from_level'=>$res2['level'],
                            'to_level'=>$data['level'],
                            'create_time'=>time()
                        ]);

                        Db::table('account')->where('mssql_account_id',$data['uid'])->update([
                                'level'=>$data['level'],
                                'phone'=>$data['phone'],
                                'real_name'=>$data['real_name'],
                                'update_time'=>time(),   //提升为代理时间
                        ]);
                    // 提交事务
                    Db::commit();    
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    // //写入日志
                    // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'操作失败']),$url);
                    return renderJson('10002','操作失败');
                } 
                // //写入日志
                // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('1','');
        }
        //代理升级
        if(isset($data['uid']) && isset($data['level'])){
            //获取用户之前的代理等级
            $res2=Db::table('account')->field('union_id,level')->where('mssql_account_id',$data['uid'])->find();
            if(empty($res2)){
                // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10008','message'=>'该用户未登录公众号']),$url);
                return  renderJson('10008','该用户未登录公众号');  
            }
            // 启动事务
                Db::startTrans();
                try{
                         Db::table('agent_log')->insert([
                            'union_id'=>$res2['union_id'],
                            'from_level'=>$res2['level'],
                            'to_level'=>$data['level'],
                            'create_time'=>time()
                        ]);

                        Db::table('account')->where('mssql_account_id',$data['uid'])->update([
                                'level'=>$data['level'],
                                'update_time'=>time(),   //提升为代理时间
                        ]);
                    // 提交事务
                    Db::commit();    
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    // //写入日志
                    // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'操作失败']),$url);
                    return renderJson('10002','操作失败');
                } 
                // //写入日志
                // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
                return renderJson('1');
        }
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
        return renderJson('10001','参数不合法');
    }
}