<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
use app\img\model\pub;

class Message extends Com{
    public function  notices(){
        $flag=$this->flag;
        // var_dump($flag);exit;
        if($flag){
             return renderJson('10001','token为空或者token已经过期');
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
        return renderJson('101','违法操作');
    }

    public function  horse(){
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
        // // 是否为 PUT 请求
        // if (Request::instance()->isPut()){
        //     $data=Request::instance()->param();
        //    return  $this->horseAdd($data);
        // }
        // 是否为 DELETE 请求
        if (Request::instance()->isDelete()){
            $data=Request::instance()->param();
           return  $this->horseDel($data);
        };
        return renderJson('101','违法操作');
    }

    public function  noticeAdd($data){
        	//关闭原来的公告
        	$res=Db::table('message')->where('type',1)->where('is_del',1)->find();
        	if($res){
        		Db::table('message')->where('type',1)->update(['is_del'=>0]);
        	}
        	$data['add_time']=time();
            $data['mid']=$this->mid;
        	$data['mname']=$this->mname;
            $data['type']=1;
        	$data['is_del']=1;
        	$res1=Db::table('message')->insert($data);
            $m_id = Db::table('message')->getLastInsID();
        	if(!$res1){
        		return renderJson('10000','操作失败');
        	}
        	//操作sqlsrv
                Db::connect('db2')->table('SystemStatusInfo')->where('StatusName','like','YJMJ_Notice%')->update(['StatusValue'=>'0']);
            $data1=array();
            $data1['StatusName']='YJMJ_Notice_'.$m_id;
            $data1['StatusValue']='1';
            $data1['StatusString']=$data['content'];
            $res3=Db::connect('db2')->table('SystemStatusInfo')->insert($data1);
        	if(!$res3){
                return renderJson('10002','操作失败');
            }
        	return renderJson('1','操作成功');
    }
    public  function  noticeDel($data){
             if(!is_numeric($data['id'])){
                return renderJson('10001','参数不合法');
            }
            $id=$data['id'];
            $res=Db::table('message')->where('id',$id)->update(['is_del'=>0]);
            if(!$res){
                return renderJson('10000','操作失败');
            }
            $res1=Db::connect('db2')->table('SystemStatusInfo')->where('StatusName','like','YJMJ_Notice%')->update(['StatusValue'=>'0']);
            if(!$res1){
                return renderJson('10002','操作失败');
            }
            return renderJson('1','操作成功');
    }
    public  function  noticeList($data){
        // return renderJson('1','',$data);
        if(isset($data['type']) && $data['type'] == 0){
            $notice=Db::table('message')->where('type',1)->where('is_del',1)->find();
            return renderJson('1','',[0=>$notice]);
        }
        if(isset($data['id']) && is_numeric($data['id'])){
            $notice=Db::table('message')->field('content','title')->where('id',$data['id'])->find();
            $notice['content'] = htmlspecialchars_decode($notice['content']);
            return renderJson('1','',[0=>$notice]);
        }

        if(empty($data['pagesize'])){
            return renderJson('10001','参数不能为空');
        }
        $offset=$data['offset'];
        $pagesize=$data['pagesize'];
        $total=Db::table('message')->where('type',1)->count();
        if($offset == 0){
            $notice=Db::table('message')->where('type',1)->order('add_time','desc')->limit($pagesize)->select();
            return renderJson('1','',['notice'=>$notice,'total'=>$total]);
        }
        $temple=Db::table('message')->where('type',1)->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $notice=Db::table('message')->where('type',1)->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
        return renderJson('1','',['notice'=>$notice,'total'=>$total]);
    }


    public  function  horseAdd($data){
            //关闭原来的跑马灯
            $res=Db::table('message')->where('type',2)->where('is_del',1)->find();
            if($res){
                Db::table('message')->where('type',2)->update(['is_del'=>0]);
            }
            $data=Request::instance()->post();
            $data['add_time']=time();
            $data['mid']=$this->mid;
            $data['mname']=$this->mname;
            $data['type']=2;
            $data['is_del']=1;
            $temple=strip_tags($data['content']);
            $temple=str_replace('&nbsp;', '  ', $temple);
            $data['title']=mb_substr($temple,0,15,'utf-8').'...';
            $res1=Db::table('message')->insert($data);
            $m_id = Db::name('message')->getLastInsID();
            if(!$res1){
                return renderJson('10000','操作失败');
            }
            //操作sqlsrv
            
                Db::connect('db2')->table('SystemStatusInfo')->where('StatusName','like','YJMJ_Paoma%')->update(['StatusValue'=>'0']);
        
            $data1=array();
            $data1['StatusName']='YJMJ_Paoma_'.$m_id;
            $data1['StatusValue']='1';
            $data1['StatusString']=$data['content'];
            $res3=Db::connect('db2')->table('SystemStatusInfo')->insert($data1);
            
            if(!$res3){
                return renderJson('10002','操作失败');
            }
            return renderJson('1','操作成功');
    }


     public  function  horseDel($data){
             if(!is_numeric($data['id'])){
                return renderJson('10001','参数不合法');
            }
            $id=$data['id'];
            $res=Db::table('message')->where('id',$id)->update(['is_del'=>0]);
            if(!$res){
                return renderJson('10000','操作失败');
            }
            $res1=Db::connect('db2')->table('SystemStatusInfo')->where('StatusName','like','YJMJ_Paoma%')->update(['StatusValue'=>'0']);
            if(!$res1){
                return renderJson('10002','操作失败');
            }
            return renderJson('1','操作成功');
    }


    public  function  horseList($data){
         if(isset($data['type']) && $data['type'] == 0 ){
            $horse=Db::table('message')->where('type',2)->where('is_del',1)->find();
            return renderJson('1','',[0=>$horse]);
        }
        if(isset($data['id']) &&　is_numeric($data['id'])){
            $horse=Db::table('message')->field('content','title')->where('id',$data['id'])->find();
            
            $horse['content'] = htmlspecialchars_decode($horse['content']);
            return renderJson('1','',[0=>$horse]);
        } 
        
        if(empty($data['pagesize'])){
            return renderJson('10001','参数不能为空');
        }

        $offset=$data['offset'];
        $pagesize=$data['pagesize'];
        $total=Db::table('message')->where('type',2)->count();

        if($offset == 0){
            $horse=Db::table('message')->where('type',2)->order('add_time','desc')->limit($pagesize)->select();
            return renderJson('1','',['horse'=>$horse,'total'=>$total]);
        }

        $temple=Db::table('message')->where('type',2)->order('add_time','desc')->limit($offset)->select();
        $tid=array_pop($temple);
        $horse=Db::table('message')->where('type',2)->where('id','<=',$tid['id'])->order('add_time','desc')->limit($pagesize)->select();
        return renderJson('1','',['horse'=>$horse,'total'=>$total]);
    }
    
}