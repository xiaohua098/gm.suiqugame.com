<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
class Message extends Controller{

    public function  noticeAdd(){
        if(Request::instance()->isPost()){
        	//关闭原来的公告
        	$res=Db::table('message')->where('type',1)->where('is_del',1)->find();
        	if($res){
        		Db::table('message')->where('type',1)->update(['is_del'=>0]);
        	}
        	$data=Request::instance()->post();
        	$data['add_time']=time();
        	$data['mid']=$this->mid;
        	$data['type']=1;
        	$res1=Db::table('message')->insert($data);
        	if(!$res1){
        		return renderJson('10000','操作失败');
        	}
        	//操作sqlsrv
            $res2=Db::connect('db2')->table('SystemStatusInfo')->where('StayusName','YJMJ_Notice')->where('StatusValue','1')->find();
            if($res2){
                Db::connect('db2')->table('SystemStatusInfo')->where('StayusName','YJMJ_Notice')->update(['StatusValue'=>'0']);
            }
            $data1=array();
            $data1['StayusName']='YJMJ_Notice';
            $data1['StatusValue']='1';
            $data1['StatusString']=$data['content'];
            $res3=Db::connect('db2')->table('SystemStatusInfo')->insert($data1);
            
        	if(!$res3){
                return renderJson('10002','操作失败');
            }
        	return renderJson('1','操作成功');
        }
        return renderJson('101','违法操作');
    }


    public  function  noticeDel(){
        if(Request::instance()->isGet()){
             if(!is_numeric($data['id'])){
                return renderJson('10001','参数不合法');
            }
            $id=$data['id'];
            $res=Db::table('message')->where('id',$id)->update(['is_del',0]);
            if(!$res){
                return renderJson('10000','操作失败');
            }
            $res1=Db::connect('db2')->table('SystemStatusInfo')->where('StayusName','YJMJ_Notice')->update(['StatusValue'=>'0']);
            if(!$res1){
                return renderJson('10002','操作失败');
            }
            return renderJson('1','操作成功');
        }
        return renderJson('101','违法操作');
    }


    public  function  noticeList(){

        $notice=Db::table('message')->where('type',1)->order('add_time','desc')->paginate();
        $page=$notice->render();
        $data=array('notice'=>$notice,'page'=>$page);
        return renderJson('1','',$data);
    }




    public  function  horseAdd(){

        if(Request::instance()->isPost()){
            //关闭原来的跑马灯
            $res=Db::table('message')->where('type',2)->where('is_del',1)->find();
            if($res){
                Db::table('message')->where('type',2)->update(['is_del'=>0]);
            }
            $data=Request::instance()->post();
            $data['add_time']=time();
            $data['mid']=$this->mid;
            $data['type']=2;
            $temple=strip_tags($data['content']);
            $temple=str_replace('&nbsp;', '  ', $temple);
            $data['title']=mb_substr($temple,0,15,'utf-8').'...';
            $res1=Db::table('message')->insert($data);
            if(!$res1){
                return renderJson('10000','操作失败');
            }
            //操作sqlsrv
            $res2=Db::connect('db2')->table('SystemStatusInfo')->where('StayusName','YJMJ_Paoma')->where('StatusValue','1')->find();
            if($res2){
                Db::connect('db2')->table('SystemStatusInfo')->where('StayusName','YJMJ_Paoma')->update(['StatusValue'=>'0']);
            }
            $data1=array();
            $data1['StayusName']='YJMJ_Paoma';
            $data1['StatusValue']='1';
            $data1['StatusString']=$data['content'];
            $res3=Db::connect('db2')->table('SystemStatusInfo')->insert($data1);
            
            if(!$res3){
                return renderJson('10002','操作失败');
            }
            return renderJson('1','操作成功');
        }
        return renderJson('101','违法操作');
    }


     public  function  horseDel(){
        if(Request::instance()->isGet()){
            $data=Request::instance()->param();
             if(!is_numeric($data['id'])){
                return renderJson('10001','参数不合法');
            }
            $id=$data['id'];
            $res=Db::table('message')->where('id',$id)->update(['is_del',0]);
            if(!$res){
                return renderJson('10000','操作失败');
            }
            $res1=Db::connect('db2')->table('SystemStatusInfo')->where('StayusName','YJMJ_Paoma')->update(['StatusValue'=>'0']);
            if(!$res1){
                return renderJson('10002','操作失败');
            }
            return renderJson('1','操作成功');
        }
        return renderJson('101','违法操作');
    }


    public  function  horseList(){

        $horse=Db::table('message')->where('type',2)->order('add_time','desc')->paginate();
        $page=$horse->render();
        $data=array('horse'=>$horse,'page'=>$page);
        return renderJson('1','',$data);
    }


    //点击查看详情
    public function  noticeContent(){
        if(Request::instance()->isGet()){
            $data=Request::instance()->param();
            if(!is_numeric($data['id'])){
                return renderJson('10001','参数不合法');
            }
            $notice=Db::table('message')->field('content','title')->where('id',$data['id'])->find();
            if(!$notice){
                return renderJson('10000','操作失败');
            }
            $notice['content'] = htmlspecialchars_decode($notice['content']);
            return renderJson('1','',$notice);
        }
        return renderJson('101','违法操作');
    }


    //点击查看详情
    public function  horseContent(){
        if(Request::instance()->isGet()){
            $data=Request::instance()->param();
            if(!is_numeric($data['id'])){
                return renderJson('10001','参数不合法');
            }
            $horse=Db::table('message')->field('content','title')->where('id',$data['id'])->find();
            if(!$horse){
                return renderJson('10000','操作失败');
            }
            $horse['content'] = htmlspecialchars_decode($horse['content']);
            return renderJson('1','',$horse);
        }
        return renderJson('101','违法操作');
    }
    
}