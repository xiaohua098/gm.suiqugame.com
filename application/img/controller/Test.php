<?php
namespace app\img\controller;
use think\Controller;
use think\Db;
class Test extends Controller{
	public  function   test(){
		// $res=$_SERVER['PHP_SELF'];//card/punch
        //     return renderJson('1','',$res);

		// 启动事务
		Db::startTrans();
		try{
		    Db::table('manager')->where('id',90)->find();
		    // Db::table('manager')->delete();
		    // 提交事务
		    Db::commit();    
		} catch (\Exception $e) {
		    // 回滚事务
		    // Db::rollback();
		    var_dump($e);
		}
	}
}
