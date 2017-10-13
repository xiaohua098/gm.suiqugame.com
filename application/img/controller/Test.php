<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
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
        $res=Db::table('punch_card')->insert(['uid'=>0,'mid'=>2,'mname'=>'admin','num'=>111,'add_time'=>time()]);
        var_dump($res);
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
	}
}
