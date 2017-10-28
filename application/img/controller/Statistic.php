<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
use \think\Request;
use think\Session;
class Statistic extends Controller{
	//充值记录
	public  function   recharge(){
	    $data=array();
	    $num=Db::table('order_info')->where('state',__STATE__)->whereTime('create_time','yesterday')->count();
	    $data['num']= $num ? $num : 0;
	    $people=Db::table('order_info')->where('state',__STATE__)->whereTime('create_time','yesterday')->group('union_id')->count();
	    $data['people']=$people ? $people : 0;
	    $card=Db::table('order_info')->where('state',__STATE__)->whereTime('create_time','yesterday')->sum('card_amount');
	    $data['card']=$card ? $card : 0;
	    $money=Db::table('order_info')->where('state',__STATE__)->whereTime('create_time','yesterday')->sum('money');//以分为单位
	    $data['money']=$money ? $money : 0;
	    $data['create_time']=time();
	    $data['add_time']=date('Y-m-d',time()-3600*24);
	    $res=Db::table('recharge_total')->insert($data);
	    if($res){
	      return renderJson('1',''); 
	    }
	    return renderJson('10000','操作失败');
	}
	//房卡产出
	public  function  punch(){
		//昨天的开始时间和结束时间
		$start=date('Y-m-d 00:00:00',time()-3600*24);
    	$end=date('Y-m-d 23:59:59',time()-3600*24);
		$data=array();
		$sys=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',0)->whereOr('CostFrom',3)->where('CostDate','between',[$start,$end])->sum('CostValue');//系统赠送todo
		$data['sys']=abs($sys) ? abs($sys) : 0;//系统赠送todo
		$gm=Db::table('punch_card')->whereTime('add_time','yesterday')->sum('num');
		$data['gm']=$gm ? $gm : 0;
		$recharge=Db::table('order_info')->where('state',__STATE__)->whereTime('create_time','yesterday')->sum('amount');
		$data['recharge']=$recharge ? $recharge : 0;
		$data['total']=$data['sys']+$data['gm']+$data['recharge'];
		$data['create_time']=time();
	    $data['add_time']=date('Y-m-d',time()-3600*24);
	    $res=Db::table('punch_total')->insert($data);
	    if($res){
	      return renderJson('1');
	    }
	    return renderJson('10000','操作失败');
	}

	//房卡消耗数
	public  function  expend(){
		//昨天的开始时间和结束时间
		$start=date('Y-m-d 00:00:00',time()-3600*24);
    	$end=date('Y-m-d 23:59:59',time()-3600*24);
		$data=array();
		//房卡消耗游戏种类
		$wKindID=Db::connect('db4')->table('GameKindItem')->where('KindName','十三水')->value('KindID');
		$mKindID=Db::connect('db4')->table('GameKindItem')->where('KindName','缙云麻将')->value('KindID');
		$water=Db::connect('db3')->table('RecordPrivateCost')->where('KindID',$wKindID)->where('CostDate','between',[$start,$end])->sum('CostValue');
		$data['water']=$water ? $water : 0;
		$majiang=Db::connect('db3')->table('RecordPrivateCost')->where('KindID',$mKindID)->where('CostDate','between',[$start,$end])->sum('CostValue');
		$data['majiang']=$majiang ? $majiang : 0;
		$data['total']=$data['water']+$data['majiang'];
		$data['create_time']=time();
	    $data['add_time']=date('Y-m-d',time()-3600*24);
	    $res=Db::table('expend_total')->insert($data);
	    if($res){
	      return renderJson('1','');
	    }
	    return renderJson('10000','操作失败');
	}

	//房卡库存
	public  function  stock(){
		$data=array();
		$agent=Db::table('account')->field('mssql_account_id')->where('level','>',0)->select();
		$uids=array_column($agent,'mssql_account_id');
		$agent_card=Db::connect('db1')->table('GameScoreInfo')->where('UserID','in',$uids)->sum('InsureScore');
		$data['agent_card']=$agent_card ? $agent_card : 0;
		$user_card=Db::connect('db1')->table('GameScoreInfo')->where('UserID','not in',$uids)->sum('InsureScore');
		$data['user_card']=$user_card ? $user_card : 0;
		$data['total']=$data['agent_card']+$data['user_card'];
		$data['create_time']=time();
	    $data['add_time']=date('Y-m-d',time()-3600*24);
	    $res=Db::table('stock_total')->insert($data);
	    if($res){
	      return renderJson('1','');
	    }
	    return renderJson('10000','操作失败');
	}

	//代理每日数据
	public  function  daily(){
		//昨天的开始时间和结束时间
		$start=date('Y-m-d 00:00:00',time()-3600*24);
    	$end=date('Y-m-d 23:59:59',time()-3600*24);
		$data=array();
		$agent=Db::table('account')->field('mssql_account_id')->where('level','>',0)->select();
		// var_dump($agent);exit;
		$uids=array_column($agent,'mssql_account_id');
		foreach ($uids as $k => $v) {
			$agent=Db::table('account')->field('union_id')->where('mssql_account_id',$v)->find();
			//充值金额
			$recharge=Db::table('order_info')->where('state',__STATE__)->where('union_id',$agent['union_id'])->whereTime('create_time','yesterday')->sum('money');
			$data[$k]['recharge']= $recharge ? $recharge : 0;
			//充值划卡数量（代充）
			$recharge_num=Db::table('order_info')->where('state',__STATE__)->where('union_id',$agent['union_id'])->where('to_union_id','<>',$agent['union_id'])->whereTime('create_time','yesterday')->sum('card_amount');
			$data[$k]['recharge_num']= $recharge_num ? $recharge_num : 0;
			//GM划卡数量
			$gm_num=Db::table('punch_card')->where('uid',$v)->whereTime('add_time','yesterday')->sum('num');
		    $data[$k]['gm_num']= $gm_num ? $gm_num : 0;
			
			//划卡数量（对外）
			$punch_num=Db::table('transfer_log')->where('from_union_id',$agent['union_id'])->whereTime('create_time','yesterday')->sum('amount');
			$data[$k]['punch_num']= $punch_num ? $punch_num : 0;
			//游戏消耗房卡数量
			$game_num=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$v)->where('CostFrom',1)->whereOr('CostFrom',2)->where('CostDate','between',[$start,$end])->sum('CostValue');
			$data[$k]['expend_num']= $game_num ? $game_num : 0;
			$data[$k]['add_time']=date('Y-m-d',time()-3600*24);
			$data[$k]['create_time']=time();
			$data[$k]['uid']=$v;
		}
		$res=Db::table('agent_daily')->insertAll($data);
		if($res){
	      return renderJson('1','');
	    }
	    return renderJson('10000','操作失败');
	}


	// //玩家和代理数据统计
	// public  function  user(){
	// 	$data=array();
	// 	$user=Db::connect('db2')->table('AccountsInfo')->field('UserID,unionid,NickName,RegisterDate,LastLogonDate')->select();
	// 	$wKindID=Db::connect('db4')->table('GameKindItem')->where('KindName','十三水')->value('KindID');
	// 	$mKindID=Db::connect('db4')->table('GameKindItem')->where('KindName','缙云麻将')->value('KindID');
	// 	$wConfig=Db::connect('db4')->table('PrivateInfo')->where('KindID',$wKindID)->find();
	// 	    $wCon[0]['k']=$wConfig['PlayCout1'];
	// 	    $wCon[0]['v']=$wConfig['PlayCost1'];
	// 	    $wCon[1]['k']=$wConfig['PlayCout2'];
	// 	    $wCon[1]['v']=$wConfig['PlayCost2'];
	// 	    $wCon[2]['k']=$wConfig['PlayCout3'];
	// 	    $wCon[2]['v']=$wConfig['PlayCost3'];
	// 	    $wCon[3]['k']=$wConfig['PlayCout4'];
	// 	    $wCon[3]['v']=$wConfig['PlayCost4'];
	// 	$mConfig=Db::connect('db4')->table('PrivateInfo')->where('KindID',$mKindID)->find();
	// 	    $mCon[0]['k']=$mConfig['PlayCout1'];
	// 	    $mCon[0]['v']=$mConfig['PlayCost1'];
	// 	    $mCon[1]['k']=$mConfig['PlayCout2'];
	// 	    $mCon[1]['v']=$mConfig['PlayCost2'];
	// 	    $mCon[2]['k']=$mConfig['PlayCout3'];
	// 	    $mCon[2]['v']=$mConfig['PlayCost3'];
	// 	    $mCon[3]['k']=$mConfig['PlayCout4'];
	// 	    $mCon[3]['v']=$mConfig['PlayCost4'];
	// 	    // var_dump($user);exit;
	// 	foreach($user as $k=>$v){
	// 		$data[$k]['uid']=$v['UserID'];
	// 		$data[$k]['nickname']=$v['NickName'];
	// 		$data[$k]['register_time']=$v['RegisterDate'];
	// 		$s_card=Db::connect('db1')->table('GameScoreInfo')->where('UserID',$v['UserID'])->value('InsureScore');
	// 		$data[$k]['s_card']=$s_card ? $s_card : 0;
	// 		$recharge=Db::table('order_info')->where('state',__STATE__)->where('union_id',$v['unionid'])->sum('money');
	// 		$data[$k]['recharge']=$recharge ? $recharge : 0;
	// 		$punch_num=Db::table('transfer_log')->where('from_union_id',$v['unionid'])->sum('amount');
	// 		$punch_num=$punch_num ? $punch_num : 0;
	// 		$game_num=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',1)->whereOr('CostFrom',2)->where('UserID',$v['UserID'])->sum('CostValue');
	// 		$game_num =$game_num ? $game_num : 0;
	// 		$data[$k]['x_card']=$game_num+$punch_num;
	// 		$data[$k]['daily']=($game_num+$punch_num)/round(strtotime(date('Y-m-d',time()))-strtotime(date('Y-m-d',strtotime($v['RegisterDate']))))*24*3600;//代理和玩家不一样(登录天数)注册天数
	// 	}
	// 	var_dump($data);exit;
	// 	foreach ($data as $k1 => $v1) {
	// 		$agent=Db::table('account')->where('mssql_account_id',$v1['uid'])->find();
	// 		if(empty($agent)){
	// 			$data[$k1]['level']=0;
	// 		}else{
	// 			$data[$k1]['phone']=$agent['phone'];
	// 			$data[$k1]['realname']=$agent['real_name'];
	// 			$data[$k1]['level']=$agent['level'];
	// 		}
	// 	}

	// 	foreach ($data as $k2 => $v2) {
	// 		if($v2['level']>0){
	// 			$punch_num=Db::table('transfer_log')->where('from_union_id',$v2['union_id'])->sum('amount');
	// 			$recharge_num=Db::table('order_info')->where('state',__STATE__)->where('union_id',$v2['union_id'])->where('to_union_id','<>',$v2['union_id'])->sum('amount');
	// 			$data[$k2]['punch_card']=$punch_num+$recharge_num;
	// 			$create_time=Db::table('transfer_log')->where('from_union_id',$v2['union_id'])->order('create_time desc')->limit(1)->value('create_time');
	// 			if(empty($create_time)){
	// 				$create_time=0;
	// 			}
	// 			$data[$k2]['punch_time']=$create_time;
	// 		}else{
	// 				//十三水对局
	// 			$wCost=Db::connect('db3')->table('RecordPrivateCost')
	// 					->field('CostValue')
	// 					->where('UserID',$v2['UserID'])
	// 					->where('CostFrom',1)
	// 					->whereOr('CostFrom',2)
	// 					->where('KindID',$wKindID)
	// 					->select();
	// 			$wCost=array_column($wCost,'CostValue');
	// 		    $water=0;
	// 		    foreach($wCost as $value){
	// 		      foreach($wCon as $value1){
	// 		          if($value== $value1['v']){
	// 		            $water+=$value1['k'];
	// 		          }
	// 		      }
	// 		    }
	// 		    $data[$k2]['water']=$water;
	// 			//缙云麻将对局
	// 			$mCost=Db::connect('db3')->table('RecordPrivateCost')
	// 					->field('CostValue')
	// 					->where('UserID',$v['UserID'])
	// 					->where('CostFrom',1)
	// 					->whereOr('CostFrom',2)
	// 					->where('KindID',$mKindID)
	// 					->select();
	// 			$mCost=array_column($mCost,'CostValue');
	// 		    $majiang=0;
	// 		    foreach($mCost as $value2){
	// 		      foreach($mCon as $value3){
	// 		          if($value2== $value3['v']){
	// 		            $majiang+=$value3['k'];
	// 		          }
	// 		      }
	// 		    }
	// 		    $data[$k2]['majiang']=$majiang;
	// 		}
	// 	}
		
	// 	//更新数据库
	// 	foreach ($data as $k3 => $v3) {
	// 		$res1=Db::table('user')->where('uid',$v3['uid'])->find();
	// 		if($res1){
	// 			Db::table('user')->where('id',$res['id'])->update($v3);
	// 		}else{
	// 			Db::table('user')->insert($v3);
	// 		}                       
	// 	}
	// 	return renderJson('1');
	// }
	

	//玩家和代理数据统计
	public  function  user(){
		$data=array();
		$user=Db::connect('db2')->table('AccountsInfo')->field('UserID,unionid,NickName,RegisterDate,LastLogonDate')->select();
		// $wKindID=Db::connect('db4')->table('GameKindItem')->where('KindName','十三水')->value('KindID');
		// $mKindID=Db::connect('db4')->table('GameKindItem')->where('KindName','缙云麻将')->value('KindID');
		// $wConfig=Db::connect('db4')->table('PrivateInfo')->where('KindID',$wKindID)->find();
		//     $wCon[0]['k']=$wConfig['PlayCout1'];
		//     $wCon[0]['v']=$wConfig['PlayCost1'];
		//     $wCon[1]['k']=$wConfig['PlayCout2'];
		//     $wCon[1]['v']=$wConfig['PlayCost2'];
		//     $wCon[2]['k']=$wConfig['PlayCout3'];
		//     $wCon[2]['v']=$wConfig['PlayCost3'];
		//     $wCon[3]['k']=$wConfig['PlayCout4'];
		//     $wCon[3]['v']=$wConfig['PlayCost4'];
		// $mConfig=Db::connect('db4')->table('PrivateInfo')->where('KindID',$mKindID)->find();
		//     $mCon[0]['k']=$mConfig['PlayCout1'];
		//     $mCon[0]['v']=$mConfig['PlayCost1'];
		//     $mCon[1]['k']=$mConfig['PlayCout2'];
		//     $mCon[1]['v']=$mConfig['PlayCost2'];
		//     $mCon[2]['k']=$mConfig['PlayCout3'];
		//     $mCon[2]['v']=$mConfig['PlayCost3'];
		//     $mCon[3]['k']=$mConfig['PlayCout4'];
		//     $mCon[3]['v']=$mConfig['PlayCost4'];
		foreach($user as $k=>$v){
			// $data[$k]['uid']=$v['UserID'];
			// $data[$k]['nickname']=$v['NickName'];
			// $data[$k]['register_time']=$v['RegisterDate'];
			// $data[$k]['union_id']=$v['unionid'];
			$s_card=Db::connect('db1')->table('GameScoreInfo')->where('UserID',$v['UserID'])->value('InsureScore');
			$data[$k]['s_card']=$s_card ? $s_card : 0;
			$recharge=Db::table('order_info')->where('state',__STATE__)->where('union_id',$v['unionid'])->sum('money');
			$data[$k]['recharge']=$recharge ? $recharge : 0;
			$punch_num=Db::table('transfer_log')->where('from_union_id',$v['unionid'])->sum('amount');
			$punch_num=$punch_num ? $punch_num : 0;
			$game_num=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',1)->whereOr('CostFrom',2)->where('UserID',$v['UserID'])->sum('CostValue');
			$game_num =$game_num ? $game_num : 0;
			$data[$k]['x_card']=$game_num+$punch_num;
			// $data[$k]['daily']=($game_num+$punch_num)/round(strtotime(date('Y-m-d',time()))-strtotime(date('Y-m-d',strtotime($v['RegisterDate']))))*24*3600;//代理和玩家不一样(登录天数)注册天数
		}
		var_dump($data);exit;
		foreach ($data as $k1 => $v1) {
			$agent=Db::table('account')->where('mssql_account_id',$v1['uid'])->find();
			if(empty($agent)){
				$data[$k1]['level']=0;
			}else{
				$data[$k1]['phone']=$agent['phone'];
				$data[$k1]['realname']=$agent['real_name'];
				$data[$k1]['level']=$agent['level'];
			}
		}

		foreach ($data as $k2 => $v2) {
			if($v2['level']>0){
				$punch_num=Db::table('transfer_log')->where('from_union_id',$v2['union_id'])->sum('amount');
				$recharge_num=Db::table('order_info')->where('state',__STATE__)->where('union_id',$v2['union_id'])->where('to_union_id','<>',$v2['union_id'])->sum('amount');
				$data[$k2]['punch_card']=$punch_num+$recharge_num;
				$create_time=Db::table('transfer_log')->where('from_union_id',$v2['union_id'])->order('create_time desc')->limit(1)->value('create_time');
				if(empty($create_time)){
					$create_time=0;
				}
				$data[$k2]['punch_time']=$create_time;
			}else{
					//十三水对局
				$wCost=Db::connect('db3')->table('RecordPrivateCost')
						->field('CostValue')
						->where('UserID',$v2['UserID'])
						->where('CostFrom',1)
						->whereOr('CostFrom',2)
						->where('KindID',$wKindID)
						->select();
				$wCost=array_column($wCost,'CostValue');
			    $water=0;
			    foreach($wCost as $value){
			      foreach($wCon as $value1){
			          if($value== $value1['v']){
			            $water+=$value1['k'];
			          }
			      }
			    }
			    $data[$k2]['water']=$water;
				//缙云麻将对局
				$mCost=Db::connect('db3')->table('RecordPrivateCost')
						->field('CostValue')
						->where('UserID',$v['UserID'])
						->where('CostFrom',1)
						->whereOr('CostFrom',2)
						->where('KindID',$mKindID)
						->select();
				$mCost=array_column($mCost,'CostValue');
			    $majiang=0;
			    foreach($mCost as $value2){
			      foreach($mCon as $value3){
			          if($value2== $value3['v']){
			            $majiang+=$value3['k'];
			          }
			      }
			    }
			    $data[$k2]['majiang']=$majiang;
			}
		}
		
		//更新数据库
		foreach ($data as $k3 => $v3) {
			$res1=Db::table('user')->where('uid',$v3['uid'])->find();
			if($res1){
				Db::table('user')->where('id',$res['id'])->update($v3);
			}else{
				Db::table('user')->insert($v3);
			}                       
		}
		return renderJson('1');
	}

	//总的统计
	public  function   census(){
		//GM划卡总数
		$h_gmcard=Db::table('punch_card')->sum('num');
		$sys=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',0)->whereOr('CostFrom',3)->sum('CostValue');//系统赠送todo
		$sys=abs($sys) ? abs($sys) : 0;//系统赠送todo
		$recharge_num=Db::table('order_info')->sum('amount');
		$recharge_num=$recharge_num ? $recharge_num : 0;
		//历史房卡产出数
		$c_card=$h_gmcard+$sys+$recharge_num;
		//历史房卡消耗数
		$x_card=Db::connect('db3')->table('RecordPrivateCost')->where('CostFrom',1)->whereOr('CostFrom',2)->sum('CostValue');
		$x_card=$x_card ? $x_card : 0;
		//当前房卡库存
		$s_card=Db::connect('db1')->table('GameScoreInfo')->sum('InsureScore');
		$s_card=$s_card ? $s_card : 0;
		//历史充值
		$h_recharge=Db::table('order_info')->where('state',__STATE__)->sum('money');
		$h_recharge=$h_recharge ? $h_recharge : 0;
		//今日充值
		$d_recharge=Db::table('order_info')->where('state',__STATE__)->whereTime('create_time','today')->sum('money');
		$d_recharge=$d_recharge  ? $d_recharge : 0;
		//今日GM划卡数
		$d_gmcard=Db::table('punch_card')->whereTime('add_time','today')->sum('num');
		$d_gmcard=$d_gmcard ? $d_gmcard : 0;

		$res=Db::table('census')->insert([
			'h_gmcard'=>$h_gmcard,
			'c_card'=>$c_card,
			'x_card'=>$x_card,
			's_card'=>$s_card,
			'h_recharge'=>$h_recharge,
			'd_recharge'=>$d_recharge,
			'd_gmcard'=>$d_gmcard,
			'create_time'=>time(),
			'add_time'=>date('Y-m-d',time()),
			]);
		if($res){
	      return renderJson('1','');
	    }
	    return renderJson('10000','操作失败');
	}
	

}