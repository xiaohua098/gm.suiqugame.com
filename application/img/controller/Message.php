<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;

class Message extends Com{
    public function  notices(){
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
        if (Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->noticeList($data);
        }
        // 是否为 POST 请求
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->noticeAdd($data);
        }
        // // 是否为 PUT 请求
        // if (Request::instance()->isPut()){
        //     $data=Request::instance()->param();
        //    return  $this->noticeEdit($data);
        // }
        // 是否为 DELETE 请求
        if (Request::instance()->isDelete()){
           $data=Request::instance()->param();
           return  $this->noticeDel($data);
        };
      
    }

    public function  horse(){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $model=new pub;
        $param=Request::instance()->param();
        $flag=$this->flag;
        if($flag){
            return renderJson('10001','token为空或者token已经过期');
        }
        if (Request::instance()->isGet()){
            $data=Request::instance()->param();
           return  $this->horseList($data);
        };
        // 是否为 POST 请求
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
           return  $this->horseAdd($data);
        }
        
        // 是否为 DELETE 请求
        if (Request::instance()->isDelete()){
            $data=Request::instance()->param();
           return  $this->horseDel($data);
        };
        return renderJson('101','违法操作');
    }

    public function  noticeAdd($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;

        if(isset($data['content']) && isset($data['title']) && $data['content']  && $data['title']){

        $data2['add_time']=time();
        $data2['mid']=$this->mid;
        $data2['mname']=$this->mname;
        $data2['type']=1;
        $data2['is_del']=1;
        $data2['title']=$data['title'];
        $data2['content']=$data['content'];
        $data1=array();
        $data1['StatusName']='YZ_NEWS_TXT_1';
        $data1['StatusValue']='1';
        $data1['StatusString']=$data['content'];
        
        // 启动事务
            Db::startTrans();
            try{
                //关闭原来的公告
                Db::table('message')->where('type',1)->update(['is_del'=>0]);
                //操作mysql
                Db::table('message')->insert($data2);
                //操作sqlsrv
                Db::connect('db2')->table('SystemStatusInfo')->where('StatusName','YZ_NEWS_TXT_1')->delete();
                Db::connect('db2')->table('SystemStatusInfo')->insert($data1);
                // 提交事务
                Db::commit();    
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10002','message'=>'操作失败']),$url);
                return renderJson('10002','操作失败');
            }
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','');


        }
       // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
       return  renderJson('10001','参数不合法');
        	
    }
    public  function  noticeDel($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
             if(!is_numeric($data['id'])){
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
                return renderJson('10001','参数不合法');
            }
            $id=$data['id'];

            // 启动事务
            Db::startTrans();
            try{
               
                //操作mysql
               Db::table('message')->where('id',$id)->update(['is_del'=>0]);
                //操作sqlsrv
               Db::connect('db2')->table('SystemStatusInfo')->where('StatusName','YZ_NEWS_TXT_1')->delete();
         
                // 提交事务
                Db::commit();    
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10002','message'=>'操作失败']),$url);
                return renderJson('10002','操作失败');
            }
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','');

    }
    public  function  noticeList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
        if(isset($data['type']) && $data['type'] == 0){
            $notice=Db::table('message')->where('type',1)->where('is_del',1)->find();
             // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',[0=>$notice]);
        }
        if(isset($data['id']) && is_numeric($data['id'])){
            $notice=Db::table('message')->field('content,title')->where('id',$data['id'])->find();
            $notice['content'] = htmlspecialchars_decode($notice['content']);
             // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',[0=>$notice]);
        }

        if(empty($data['pagesize'])){
             // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']),$url);
            return renderJson('10001','参数不能为空');
        }
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];
        $total=Db::table('message')->where('type',1)->count();
        if($offset>$total){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
            }
        if($offset == 0){
            $notice=Db::table('message')->where('type',1)->order('add_time','desc')->limit($pagesize)->select();
             // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['notice'=>$notice,'total'=>$total]);
        }
        $temple=Db::table('message')->where('type',1)->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $notice=Db::table('message')->where('type',1)->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
         // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['notice'=>$notice,'total'=>$total]);
    }


    public  function  horseAdd($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;

        if(isset($data['content']) && $data['content']){

            $data2['add_time']=time();
            $data2['mid']=$this->mid;
            $data2['mname']=$this->mname;
            $data2['type']=2;
            $data2['is_del']=1;
            $temple=strip_tags($data['content']);
            $temple=str_replace('&nbsp;', '  ', $temple);
            $data2['title']=mb_substr($temple,0,15,'utf-8').'...';
            $data2['content']=$data['content'];
        $data1=array();
            $data1['StatusName']='JYMJ_Notice';
            $data1['StatusValue']='1';
            $data1['StatusString']=$data['content'];
            
         // 启动事务
            Db::startTrans();
            try{
                //关闭原来的跑马灯
                Db::table('message')->where('type',2)->update(['is_del'=>0]);
                //操作mysql
                Db::table('message')->insert($data2);
                //操作sqlsrv
                Db::connect('db2')->table('SystemStatusInfo')->where('StatusName','JYMJ_Notice')->delete();
                Db::connect('db2')->table('SystemStatusInfo')->insert($data1);
                // 提交事务
                Db::commit();    
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10002','message'=>'操作失败']),$url);
                return renderJson('10002','操作失败');
            }
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','');


        }
        // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
        return  renderJson('10001','参数不合法');
    }


     public  function  horseDel($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
             if(!is_numeric($data['id'])){
                 // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
                return renderJson('10001','参数不合法');
            }
            $id=$data['id'];


             // 启动事务
            Db::startTrans();
            try{
               
                //操作mysql
               Db::table('message')->where('id',$id)->update(['is_del'=>0]);
                //操作sqlsrv
               Db::connect('db2')->table('SystemStatusInfo')->where('StatusName','JYMJ_Notice')->delete();
         
                // 提交事务
                Db::commit();    
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10002','message'=>'操作失败']),$url);
                return renderJson('10002','操作失败');
            }
            // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','');

    }


    public  function  horseList($data){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $param=$data;
        $model=new pub;
         if(isset($data['type']) && $data['type'] == 0 ){
            $horse=Db::table('message')->where('type',2)->where('is_del',1)->find();
             // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',[0=>$horse]);
        }
        if(isset($data['id']) &&　is_numeric($data['id'])){
            $horse=Db::table('message')->field('content,title')->where('id',$data['id'])->find();
            
            $horse['content'] = htmlspecialchars_decode($horse['content']);
             // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',[0=>$horse]);
        } 
        
        if(empty($data['pagesize'])){
             // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不能为空']),$url);
            return renderJson('10001','参数不能为空');
        }

        $offset=$data['offset'];
        $pagesize=$data['pagesize'];
        $total=Db::table('message')->where('type',2)->count();
        if($offset>$total){
                // //写入日志
        // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'10001','message'=>'参数不合法']),$url);
            return renderJson('10001','参数不合法');
            }
        if($offset == 0){
            $horse=Db::table('message')->where('type',2)->order('add_time','desc')->limit($pagesize)->select();
             // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
            return renderJson('1','',['horse'=>$horse,'total'=>$total]);
        }

        $temple=Db::table('message')->where('type',2)->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $horse=Db::table('message')->where('type',2)->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
         // //写入日志
            // $model->saveRecord($this->mid,$this->mname,$this->path,json_encode($param),json_encode(['code'=>'1','message'=>'']),$url);
        return renderJson('1','',['horse'=>$horse,'total'=>$total]);
    }
    
}