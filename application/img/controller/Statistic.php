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
	    $data['num']=Db::table('order_info')->whereTime('create_time','yesterday')->count();
	    $data['people']=Db::table('order_info')->whereTime('create_time','yesterday')->group('union_id')->count();
	    $data['card']=Db::table('order_info')->whereTime('create_time','yesterday')->sum('amount');
	    $data['money']=Db::table('order_info')->whereTime('create_time','yesterday')->sum('money');
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
		$data=array();
		$data['sys']=;//系统赠送
		$data['gm']=Db::table('punch_card')->whereTime('create_time','yesterday')->sum('num');
		$data['recharge']=Db::table('order_info')->whereTime('create_time','yesterday')->sum('amount');
		$data['total']=$data['sys']+$data['gm']+$data['recharge'];
		$data['create_time']=time();
	    $data['add_time']=date('Y-m-d',time()-3600*24);
	    $res=Db::table('punch_total')->insert($data);
	    if($res){
	      return renderJson('1','');
	    }
	    return renderJson('10000','操作失败');
	}

	//房卡消耗数
	public  function  expend(){
		$data=array();
		$data['water']=Db::connect('db3')->table('RecordPrivateCost')->whereTime('CostData','yesterday')->sum('CostValue');
		$data['majiang']=;Db::connect('db3')->table('RecordPrivateCost')->whereTime('CostData','yesterday')->sum('CostValue');
		$data['taotal']=$data['water']+$data['majiang'];
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
		$data['agent_card']=Db::connect('db1')->table('GameScoreInfo')->where('UserID','in',$uids)->sum('InsureScore');
		$data['user_card']=Db::connect('db1')->table('GameScoreInfo')->where('UserID','not in',$uids)->sum('InsureScore');
		$data['total']=$data['agent_card']+$data['user_card'];
		$data['create_time']=time();
	    $data['add_time']=date('Y-m-d',time()-3600*24);
	    $res=Db::table('stock_total')->insert($data);
	    if($res){
	      return renderJson('1','');
	    }
	    return renderJson('10000','操作失败');
	}

	public  function  daily(){
		$data=array();
		$agent=Db::table('account')->field('mssql_account_id')->where('level','>',0)->select();
		$uids=array_column($agent,'mssql_account_id');
		foreach ($uid as $k => $v) {
			$agent=Db::table('account')->field('union_id')->where('mssql_account_id',$v)->find();
			$data[$k]['recharge']=Db::table('order_info')->where('state','>',0)->where('union_id',$agent['union_id'])->whereTime('create_time','yesterday')->sum('money');
			$data[$k]['recharge_num']=Db::table('order_info')->where('state','>',0)->where('union_id',$agent['union_id'])->where('to_union_id','<>',$agent['union_id'])->whereTime('create_time','yesterday')->sum('amount');
			$data[$k]['gm_num']=Db::table('punch_card')->where('uid',$uid)->whereTime('create_time','yesterday')->sum('num');
			$punch_num=Db::table('transfer_log')->where('from_union_id',$agent['union_id'])->whereTime('create_time','yesterday')->sum('amount');
			$data[$k]['punch_num']=$punch_num;
			$game_num=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$v)->whereTime('CostData','yesterday')->sum('CostValue');
			$data[$k]['expend_num']=$punch_num+$game_num;
		}
		$res=Db::tabel('agent_daily')->insert($data);
		if($res){
	      return renderJson('1','');
	    }
	    return renderJson('10000','操作失败');
	}


	//玩家和代理
	public  function  user(){
		$data=array();
		$user=Db::connet('db2')->table('AccountsInfo')->field('UserID','unionid','NickName','RegisterDate','LastLogonDate')->select();
		foreach($user as $k=>$v){
			$data[$k]['uid']=$v['UserID'];
			$data[$k]['nickname']=$v['NickName'];
			$data[$k]['register_time']=$v['RegisterDate'];
			$data[$k]['s_card']=Db::connect('db1')->table('GameScoreInfo')->where('UserID',$v['UserID'])->column('InsureScore');
			$data[$k]['recharge']=Db::table('order_info')->where('state','>',0)->where('union_id',$v['unionid'])->sum('money');
			$punch_num=Db::table('transfer_log')->where('from_union_id',$v['unionid'])->sum('amount');
			$game_num=Db::connect('db3')->table('RecordPrivateCost')->where('UserID',$v['UserID'])->sum('CostValue');
			$data[$k]['x_card']=$game_num+$punch_num;
			$data[$k]['daily']=($game_num+$punch_num)/'登录天数';//代理和玩家不一样
			$data[$k]['water']=;
			$data[$k]['majiang']=;
		}
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
				$recharge_num=Db::table('order_info')->where('state','>',0)->where('union_id',$v2['union_id'])->where('to_union_id','<>',$v2['union_id'])->sum('amount');
				$data[$k]['punch_card']=$punch_num+$recharge_num;
				$create_time=Db::table('transfer_log')->where('from_union_id',$v2['union_id'])->order('create_time desc')->limit(1)->column('create_time');
				if(empty($create_time)){
					$create_time=0;
				}else{
					$create_time=$create_time[0];
				}
				$data[$k]['punch_time']=$create_time;
			}
		}

	}

	//更新数据库
	
	foreach ($data as $k3 => $v3) {
		$res1=Db::table('user')->where('uid',$v3['uid'])->find();
		if($res1){
			Db::table('user')->where('uid',$v3['uid'])->
		}

	}
	



}