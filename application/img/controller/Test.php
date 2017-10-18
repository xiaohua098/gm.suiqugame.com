<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use think\Request;  
use think\Response;
// // 指定允许其他域名访问  
// header('Access-Control-Allow-Origin:*');  
// // 响应类型  
// header('Access-Control-Allow-Methods:*');  
// // 响应头设置  
// header('Access-Control-Allow-Headers:content-type,token'); 
class Test extends Controller{
	public  function   test(){   
		// // 启动事务
		// Db::startTrans();
		// try{
		//     Db::table('punch_card')->insert(['mname'=>'admin','mid'=>11,'num'=>11,'uid'=>222,'add_time'=>time()]);
		//     Db::connect('db2')->table('SystemStatusInfo')->where('StayusName','YJMJ_Notice')->update(['StatusValue'=>'1']);
		//     // 提交事务
		//     Db::commit();    
		// } catch (\Exception $e) {
		//     // 回滚事务
		//     Db::rollback();
		//     return renderJson('10001','事务执行失败');
		// }



		 // var_dump($_SERVER['HTTP_TOKEN']);
        // var_dump($this->mid);
        // var_dump($this->flag);
        // // exit;
        //  $uid='1';
        //  $uid=explode(',',$uid);
        //  // var_dump($uid);exit;
        // $res=Db::table('punch_card')->insert(['uid'=>0,'mid'=>2,'mname'=>'admin','num'=>111,'add_time'=>time()]);
        // var_dump($res);
        // 
        // 
        // 
       // https://code.aliyun.com/suiqu/jymj-gm-server.git
       // 
       // 
       // 
       // $data1=array();
       //      $data1['StatusName']='YJMJ_Notice';
       //      $data1['StatusValue']='1';
       //      $data1['StatusString']='shgfgsfgdhjfhjd';
       //      $res3=Db::connect('db2')->table('SystemStatusInfo')->insert($data1);
       //      
       //      
      // $method=Request::instance()->method();     
      // $path=ltrim($_SERVER['PATH_INFO'],'/').strtolower($method);
      // var_dump($path);
      // // 
      // // 
       
      // var_dump($_SERVER);
      // 
      // Db::connect('db1')->table('GameScoreInfo')->where('UserId','in',[1000])->setInc('InsureScore',5);
      // 
    
      // //操作记录
      // $operate_arr=array();
      // $operate_arr['mid']=$this->mid;
      // $operate_arr['mname']=$this->mname;
      // $operate_arr['ip']=$_SERVER['REMOTE_ADDR'];
      // $operate_arr['path']=$this->path;
      // $operate_arr['param']=$data;
      // $operate_arr['code']=;
      // $operate_arr['add_time']=time();
      // $operate_arr['response']=$result;
       
      // var_dump($ip);
      // $my_arr=array(); 
      // for($i=0;$i<1000;$i++){
      //     $my_arr[$i]['mname']='admin';
      //     $my_arr[$i]['mid']='3';
      //     $my_arr[$i]['uid']=$i+1;
      //     $my_arr[$i]['add_time']=time();
      //     $my_arr[$i]['num']='5';
      // }
      // var_dump($my_arr);exit;
      // $my_arr=array(array('mname'=>'hong','mid'=>1,'num'=>3,'uid'=>1,'add_time'=>time()),array('mname'=>'hong','mid'=>2,'num'=>3,'uid'=>3,'add_time'=>time()));
      // var_dump($my_arr);exit;
      // $res=Db::table('punch_card')->insertAll($my_arr);
      // var_dump($res);
      // 
      // 
      // $url=Request::instance()->url();
      // var_dump($url);
// $url = 'http://t.cn/h5mwx';
// $headers = get_headers($url, TRUE);
// print_r($headers);
















# Please enter the commit message for your changes. Lines starting
# with '#' will be ignored, and an empty message aborts the commit.
# On branch master
# Your branch is up-to-date with 'origin/master'.
#
# Changes to be committed:
 new file:   public/.htaccess
 new file:   public/.user.ini
 new file:   public/1.htm
 new file:   public/favicon.ico
 new file:   public/game.com.conf
 new file:   public/index.php
 new file:   public/robots.txt
 new file:   public/router.php
 new file:   public/static/.gitignore
 new file:   public/test.php
 new file:   public/ueditor/php/upload/image/20170405/1491364533286591.jpg
 new file:   public/ueditor/php/upload/image/20170405/1491364585464140.jpg
 new file:   public/ueditor/php/upload/image/20170405/1491369218908142.jpg
 new file:   public/ueditor/php/upload/image/20170407/1491538052110019.jpg
 new file:   public/ueditor/php/upload/image/20170407/1491538119561349.jpg
 new file:   runtime/.gitignore
#
