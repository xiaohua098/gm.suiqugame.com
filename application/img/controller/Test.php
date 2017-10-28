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

    // $start=date('Y-m-d 00:00:00',time()-3600*24);
    // $end=date('Y-m-d 23:59:59',time()-3600*24);
   
    // $res=Db::connect('db3')->table('RecordPrivateCost')->where('CostDate','>',date('Y-m-d',strtotime('2017-10-22 23:59:59')))->select();
    // var_dump($res);
     
   //  $data['content']='测试paoma';
   // $data['add_time']=time();
   //          $data['mid']=3;
   //          $data['mname']='要你命三千';
   //          $data['type']=2;
   //          $data['is_del']=1;
   //          $temple=strip_tags($data['content']);
   //          $temple=str_replace('&nbsp;', '  ', $temple);
   //          $data['title']=mb_substr($temple,0,15,'utf-8').'...';
   //          // $res=Db::table('message')->insert($data);
   //          // var_dump($res);
            
   //          // $res=Db::connect('db2')->table('SystemStatusInfo')->where('StatusName','JYMJ_Paoma')->delete();
   //          // var_dump($res);
   //          // $data1=array();
   //          $data1['StatusName']='JYMJ_Paoma';
   //          $data1['StatusValue']='1';
   //          $data1['StatusString']=$data['content'];
   //          // $res=Db::connect('db2')->table('SystemStatusInfo')->insert($data1);
   //          // var_dump($res);
   //          // 
   //          $res=Db::table('message')->where('type',2)->update(['is_del'=>0]);
   //          var_dump($res);
   // $v['UserID']=5860;
   // $v['NickName']='haha';
   // $v['unionid']='ol96N1M065wsnDWyDnA_kna8c8EY';
   // $v['RegisterDate']='2017-10-22 00:09:07.570';
   //  $data['uid']=$v['UserID'];
   //  $data['nickname']=$v['NickName'];
   //    $data['register_time']=$v['RegisterDate'];
   //    $s_card=Db::connect('db1')->table('GameScoreInfo')->where('UserID',$v['UserID'])->value('InsureScore');
   //    $data['s_card']=$s_card ? $s_card : 0;
   //    $recharge=Db::table('order_info')->where('state',__STATE__)->where('union_id',$v['unionid'])->sum('money');
   //    $data['recharge']=$recharge ? $recharge : 0;
   //    $punch_num=Db::table('transfer_log')->where('from_union_id',$v['unionid'])->sum('amount');
   //    $punch_num=$punch_num ? $punch_num : 0;
   //    $game_num=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',1)->whereOr('CostFrom',2)->where('UserID',$v['UserID'])->sum('CostValue');
   //    $game_num =$game_num ? $game_num : 0;
   //    $data['x_card']=$game_num+$punch_num;
   //    $data['daily']=($game_num+$punch_num)/round(strtotime(date('Y-m-d',time()))-strtotime(date('Y-m-d',strtotime($v['RegisterDate']))))*24*3600;//代理和玩家不一样(登录天数)注册天数
   //    var_dump($data);
   //    
   
  

  // sqlsrv和mysql链表查询
  // $record=Db::connect('db3')->table('RecordPrivateCost')
  //               ->alias('a')
  //               ->connect('db5')
  //               ->join('account b','a.UserID=b.mssql_account_id','LEFT')
  //               // ->connect('db4')
  //               // ->join('GameKindItem c','a.KindID=c.KindID','LEFT')
  //               // ->field('a.*,b.phone,c.KindName')
  //               ->where('a.CostFrom',1)
  //               ->whereOr('a.CostFrom',2)
  //               ->order('a.CostDate','desc')
  //               ->limit(1)
  //               ->select();
  // $record=Db::query("select a.*,b.phone,c.KindName from db3.dbo.RecordPrivateCost as a left join db5.dbo.account as b on a.UserID=b.mssql_account_id left join db4.dbo.GameKindItem as c on a.KindID=c.KindID");
  
// $subsql = Db::connect('db5')->table('account')->buildSql();
// $record=Db::connect('db3')->table('RecordPrivateCost')
//         ->alias('a')
//         ->join([$subsql=>'b'], 'a.UserID=b.mssql_account_id')
//         ->field('a.*,b.phone')
//         ->fetchSql(true)
//         ->select();
//     // $record=Db::query("select * from db3.dbo.RecordPrivateCost order by CostDate desc limit 3 ");
//   var_dump($record);
//   
//   
//   





// $user=Db::connect('db2')->table('AccountsInfo')->field('UserID,unionid,NickName,RegisterDate,LastLogonDate')->select();
  Db::connect('db2')->table('AccountsInfo')->field('UserID ,unionid ,NickName ,RegisterDate as ,LastLogonDate ')->where('UserID','<',100)->chunk(20, function($users) {
      foreach ($users as $v) {
          $s_card=Db::connect('db1')->table('GameScoreInfo')->where('UserID',$v['UserID'])->value('InsureScore');
          $users[]['s_card']=$s_card ? $s_card : 0;
          $recharge=Db::table('order_info')->where('state',__STATE__)->where('union_id',$v['unionid'])->sum('money');
          $users[]['recharge']=$recharge ? $recharge : 0;
          $punch_num=Db::table('transfer_log')->where('from_union_id',$v['unionid'])->sum('amount');
          $punch_num=$punch_num ? $punch_num : 0;
          $game_num=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',1)->whereOr('CostFrom',2)->where('UserID',$v['UserID'])->sum('CostValue');
          $game_num =$game_num ? $game_num : 0;
          $users[]['x_card']=$game_num+$punch_num;
      }
       Db::table('user')->insertAll($users);
  });
      

}








}




