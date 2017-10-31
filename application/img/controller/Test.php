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
	public   function   test(){
         // $record=Db::connect('db3')->table('RecordPrivateCost')
         //          ->where('CostFrom',1)
         //          ->whereOr('CostFrom',2)
         //          ->where('UserID',$uid)
         //          ->where('CostDate','between ',[$start,$end])
         //          ->where('RecordID','<=',$tid['RecordID'])
         //          ->order('CostDate','desc')
         //          ->limit($pagesize)->select();
         //    //游戏名称和电话号码
         //        foreach ($record as $k => $v) {
         //            $record[$k]['phone']=$phone;
         //            $record[$k]['KindName']=Db::connect('db4')->table('GameKindItem')->where('KindID',$v['KindID'])->value('KindName');
         //        }
         // $subsql = Db::connect('db4')->table('GameKindItem')->field('KindID,KindName')->buildSql();
         // $record=Db::connect('db3')->table('RecordPrivateCost')
         //          ->alias('a')
         //          ->join([$subsql=>'b'],'a.KindID=b.KindID','LEFT')
         //          ->field('a.*,b.KindName')
         //          ->where('a.CostFrom',1)
         //          ->whereOr('a.CostFrom',2)
         //          // ->where('a.UserID',$uid)
         //          // ->where('a.CostDate','between ',[$start,$end])
         //          // ->where('a.RecordID','<=',$tid['RecordID'])
         //          // ->order('a.CostDate','desc')
         //          // ->limit($pagesize)
         //          ->limit(2)
         //          ->select();
                
         //  // $record=Db::connect('db3')->query("select a.*,b.KindName from RecordPrivateCost a left join GameKindItem b on a.KindID=b.KindID  order by  CostDate desc ");
         //    var_dump($record);
         //    
         $res=Db::connect('db5')->table('transfer_log')->where('uid',1)->limit(1,2)->select();
         var_dump($res);


  }






}




