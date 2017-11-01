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
        // // 查询多条
        //  Db::connect('db5')->table('user')
        //     ->where(['id' => 3])
        //      ->selectOrFail();
      try{
         // 查询单条
         Db::connect('db5')->table('user')->where('id',3)
             ->findOrFail();
      }catch (\Exception $e){
          var_dump($e);exit;
      }
      
  }






}




