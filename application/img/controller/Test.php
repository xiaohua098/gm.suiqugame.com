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

    
   //统计列表的时间查询  //年月日
    // $res=Db::table('test')->where('add_time','between',['2017-10-18 ','2017-10-19 '])->select();
    // var_dump($res);
    

    //记录列表的时间查询  //时间戳
    // $res=Db::table('test')->where('create_time','between',[strtotime('2017-10-18 00:00:00'),strtotime('2017-10-19 23:59:59')])->select();
    // var_dump($res);
     
    



    // //定时充值统计数据
    // $data=array();
    // $data['num']=Db::table('order_info')->whereTime('create_time','yesterday')->count();
    // $data['people']=Db::table('order_info')->whereTime('create_time','yesterday')->group('union_id')->count();
    // $data['card']=Db::table('order_info')->whereTime('create_time','yesterday')->sum('amount');
    // $data['money']=Db::table('order_info')->whereTime('create_time','yesterday')->sum('money');
    // $data['create_time']=time();
    // $data['add_time']=date('Y-m-d',time()-3600*24);
    // $res=Db::table('recharge_total')->insert($data);
    // if($res){
    //   return renderJson('1','');
    // }
    



    // $start=date('Y-m-d',time()-3600*24); 
    // $res=Db::connect('db3')->table('RecordPrivateCost')->where('CostData','between',[$start.'00:00:00',$start.'23.59.59'])->count();
    // var_dump($res);
     
     
    
    // $record=Db::table('test')->select();
    // foreach ($record as $k => $v) {
    //   $res=Db::table('test')->where('id',$v['id'])->find();
    //     if($res){
    //       $record[$k]['realname']=$res['name'];
    //     }else{
    //       $record[$k]['realname']='admin';
    //     }
    // }
    // var_dump($record);
    
    // $param=Request::instance()->param();
    // if(empty($param['name'])){
    //   echo '未传该参数';
    // }else{
    //   var_dump($param);
    // }
    // 
    // $record=Db::table('test')->field('id')->select();
    // $ids = array_column($record, 'id');

    // $res=Db::table('test')->where('name','xiaohua')->order('create_time desc')->limit(1)->column('create_time');//得到的是数组
    // var_dump($res);
    // 
    // 
    $data1=[
    ['name'=>'xiaohua','num'=>90,'add_time'=>'2017-10-20','create_time'=>time(),'uid'=>5],
    ['name'=>'xiaohua','num'=>90,'add_time'=>'2017-10-20','create_time'=>time(),'uid'=>4],
    ] 
    Db::table('test')->
    
    
  }
}




