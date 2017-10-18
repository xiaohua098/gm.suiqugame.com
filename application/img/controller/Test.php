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

    $count=consite('count');
    var_dump($count);
	}
}





#Please enter the commit message for your changes. Lines starting
#with '#' will be ignored, and an empty message aborts the commit.
# On branch master
# Your branch is up-to-date with 'origin/master'.
#
# Changes to be committed:
new file:   application/.htaccess
new file:   application/command.php
new file:   application/common.php
new file:   application/config.php
new file:   application/database.php
new file:   application/extra/queue.php
new file:   application/img/controller/Auth.php
new file:   application/img/controller/Card.php
new file:   application/img/controller/Com.php
new file:   application/img/controller/Com_old.php
new file:   application/img/controller/Index.php
new file:   application/img/controller/Index_old.php
new file:   application/img/controller/Manager.php
new file:   application/img/controller/Message.php
new file:   application/img/controller/Role.php
new file:   application/img/controller/Test.php
new file:   application/img/model/pub.php
new file:   application/route.php
new file:   application/tags.php
new file:   build.php
new file:   composer.json
new file:   composer.lock
new file:   extend/.gitignore
new file:   phpunit.xml
new file:   server.php
new file:   tests/ExampleTest.php
new file:   tests/TestCase.php
new file:   think
new file:   thinkphp/.gitignore
new file:   thinkphp/.htaccess
new file:   thinkphp/.travis.yml
new file:   thinkphp/CONTRIBUTING.md
new file:   thinkphp/LICENSE.txt
new file:   thinkphp/README.md
new file:   thinkphp/base.php
new file:   thinkphp/codecov.yml
new file:   thinkphp/composer.json
new file:   thinkphp/console.php
new file:   thinkphp/convention.php
new file:   thinkphp/helper.php
new file:   thinkphp/lang/zh-cn.php
new file:   thinkphp/library/think/App.php
new file:   thinkphp/library/think/Build.php
new file:   thinkphp/library/think/Cache.php
new file:   thinkphp/library/think/Collection.php
new file:   thinkphp/library/think/Config.php
new file:   thinkphp/library/think/Console.php
new file:   thinkphp/library/think/Controller.php
new file:   thinkphp/library/think/Cookie.php
new file:   thinkphp/library/think/Db.php
new file:   thinkphp/library/think/Debug.php
new file:   thinkphp/library/think/Env.php
new file:   thinkphp/library/think/Error.php
new file:   thinkphp/library/think/Exception.php
new file:   thinkphp/library/think/File.php
new file:   thinkphp/library/think/Hook.php
new file:   thinkphp/library/think/Lang.php
new file:   thinkphp/library/think/Loader.php
new file:   thinkphp/library/think/Log.php
new file:   thinkphp/library/think/Model.php
new file:   thinkphp/library/think/Paginator.php
new file:   thinkphp/library/think/Process.php
new file:   thinkphp/library/think/Request.php
new file:   thinkphp/library/think/Response.php
new file:   thinkphp/library/think/Route.php
new file:   thinkphp/library/think/Session.php
new file:   thinkphp/library/think/Template.php
new file:   thinkphp/library/think/Url.php
new file:   thinkphp/library/think/Validate.php
new file:   thinkphp/library/think/View.php
new file:   thinkphp/library/think/cache/Driver.php
new file:   thinkphp/library/think/cache/driver/File.php
new file:   thinkphp/library/think/cache/driver/Lite.php
new file:   thinkphp/library/think/cache/driver/Memcache.php
new file:   thinkphp/library/think/cache/driver/Memcached.php
new file:   thinkphp/library/think/cache/driver/Redis.php
new file:   thinkphp/library/think/cache/driver/Sqlite.php
new file:   thinkphp/library/think/cache/driver/Wincache.php
new file:   thinkphp/library/think/cache/driver/Xcache.php
new file:   thinkphp/library/think/config/driver/Ini.php
new file:   thinkphp/library/think/config/driver/Json.php
new file:   thinkphp/library/think/config/driver/Xml.php
new file:   thinkphp/library/think/console/Command.php
new file:   thinkphp/library/think/console/Input.php
new file:   thinkphp/library/think/console/LICENSE
new file:   thinkphp/library/think/console/Output.php
new file:   thinkphp/library/think/console/bin/README.md
new file:   thinkphp/library/think/console/bin/hiddeninput.exe
new file:   thinkphp/library/think/console/command/Build.php
new file:   thinkphp/library/think/console/command/Clear.php
new file:   thinkphp/library/think/console/command/Help.php
new file:   thinkphp/library/think/console/command/Lists.php
new file:   thinkphp/library/think/console/command/Make.php
new file:   thinkphp/library/think/console/command/make/Controller.php
new file:   thinkphp/library/think/console/command/make/Model.php
new file:   thinkphp/library/think/console/command/make/stubs/controller.plain.stub
new file:   thinkphp/library/think/console/command/make/stubs/controller.stub
new file:   thinkphp/library/think/console/command/make/stubs/model.stub
new file:   thinkphp/library/think/console/command/optimize/Autoload.php
new file:   thinkphp/library/think/console/command/optimize/Config.php
new file:   thinkphp/library/think/console/command/optimize/Route.php
new file:   thinkphp/library/think/console/command/optimize/Schema.php
new file:   thinkphp/library/think/console/input/Argument.php
new file:   thinkphp/library/think/console/input/Definition.php
new file:   thinkphp/library/think/console/input/Option.php
new file:   thinkphp/library/think/console/output/Ask.php
new file:   thinkphp/library/think/console/output/Descriptor.php
new file:   thinkphp/library/think/console/output/Formatter.php
new file:   thinkphp/library/think/console/output/Question.php
new file:   thinkphp/library/think/console/output/descriptor/Console.php
new file:   thinkphp/library/think/console/output/driver/Buffer.php
new file:   thinkphp/library/think/console/output/driver/Console.php
new file:   thinkphp/library/think/console/output/driver/Nothing.php
new file:   thinkphp/library/think/console/output/formatter/Stack.php
new file:   thinkphp/library/think/console/output/formatter/Style.php
new file:   thinkphp/library/think/console/output/question/Choice.php
new file:   thinkphp/library/think/console/output/question/Confirmation.php
new file:   thinkphp/library/think/controller/Rest.php
new file:   thinkphp/library/think/controller/Yar.php
new file:   thinkphp/library/think/db/Builder.php
new file:   thinkphp/library/think/db/Connection.php
new file:   thinkphp/library/think/db/Query.php
new file:   thinkphp/library/think/db/builder/Dblib.php
new file:   thinkphp/library/think/db/builder/Mysql.php
new file:   thinkphp/library/think/db/builder/Pgsql.php
new file:   thinkphp/library/think/db/builder/Sqlite.php
new file:   thinkphp/library/think/db/builder/Sqlsrv.php
new file:   thinkphp/library/think/db/connector/Dblib.php
new file:   thinkphp/library/think/db/connector/Mysql.php
new file:   thinkphp/library/think/db/connector/Pgsql.php
new file:   thinkphp/library/think/db/connector/Sqlite.php
new file:   thinkphp/library/think/db/connector/Sqlsrv.php
new file:   thinkphp/library/think/db/connector/pgsql.sql
new file:   thinkphp/library/think/db/exception/BindParamException.php
new file:   thinkphp/library/think/db/exception/DataNotFoundException.php
new file:   thinkphp/library/think/db/exception/ModelNotFoundException.php
new file:   thinkphp/library/think/debug/Console.php
new file:   thinkphp/library/think/debug/Html.php
new file:   thinkphp/library/think/exception/ClassNotFoundException.php
new file:   thinkphp/library/think/exception/DbException.php
new file:   thinkphp/library/think/exception/ErrorException.php
new file:   thinkphp/library/think/exception/Handle.php
new file:   thinkphp/library/think/exception/HttpException.php
new file:   thinkphp/library/think/exception/HttpResponseException.php
new file:   thinkphp/library/think/exception/PDOException.php
new file:   thinkphp/library/think/exception/RouteNotFoundException.php
new file:   thinkphp/library/think/exception/TemplateNotFoundException.php
new file:   thinkphp/library/think/exception/ThrowableError.php
new file:   thinkphp/library/think/exception/ValidateException.php
new file:   thinkphp/library/think/log/driver/File.php
new file:   thinkphp/library/think/log/driver/Socket.php
new file:   thinkphp/library/think/log/driver/Test.php
new file:   thinkphp/library/think/model/Collection.php
new file:   thinkphp/library/think/model/Merge.php
new file:   thinkphp/library/think/model/Pivot.php
new file:   thinkphp/library/think/model/Relation.php
new file:   thinkphp/library/think/model/relation/BelongsTo.php
new file:   thinkphp/library/think/model/relation/BelongsToMany.php
new file:   thinkphp/library/think/model/relation/HasMany.php
new file:   thinkphp/library/think/model/relation/HasManyThrough.php
new file:   thinkphp/library/think/model/relation/HasOne.php
new file:   thinkphp/library/think/model/relation/MorphMany.php
new file:   thinkphp/library/think/model/relation/MorphTo.php
new file:   thinkphp/library/think/model/relation/OneToOne.php
new file:   thinkphp/library/think/paginator/driver/Bootstrap.php
new file:   thinkphp/library/think/process/Builder.php
new file:   thinkphp/library/think/process/Utils.php
new file:   thinkphp/library/think/process/exception/Failed.php
new file:   thinkphp/library/think/process/exception/Timeout.php
new file:   thinkphp/library/think/process/pipes/Pipes.php
new file:   thinkphp/library/think/process/pipes/Unix.php
new file:   thinkphp/library/think/process/pipes/Windows.php
new file:   thinkphp/library/think/response/Json.php
new file:   thinkphp/library/think/response/Jsonp.php
new file:   thinkphp/library/think/response/Redirect.php
new file:   thinkphp/library/think/response/View.php
new file:   thinkphp/library/think/response/Xml.php
new file:   thinkphp/library/think/session/driver/Memcache.php
new file:   thinkphp/library/think/session/driver/Memcached.php
new file:   thinkphp/library/think/session/driver/Redis.php
new file:   thinkphp/library/think/template/TagLib.php
new file:   thinkphp/library/think/template/driver/File.php
new file:   thinkphp/library/think/template/taglib/Cx.php
new file:   thinkphp/library/think/view/driver/Php.php
new file:   thinkphp/library/think/view/driver/Think.php
new file:   thinkphp/library/traits/controller/Jump.php
new file:   thinkphp/library/traits/model/SoftDelete.php
new file:   thinkphp/library/traits/think/Instance.php
new file:   thinkphp/logo.png
new file:   thinkphp/phpunit.xml
new file:   thinkphp/start.php
new file:   thinkphp/tpl/default_index.tpl
new file:   thinkphp/tpl/dispatch_jump.tpl
new file:   thinkphp/tpl/page_trace.tpl
new file:   thinkphp/tpl/think_exception.tpl
new file:   vendor/.gitignore
#
#Untracked files:
#public/
#runtime/
#
#
#It took 7.67 seconds to enumerate untracked files. 'status -uno'
#may speed it up, but you have to be careful not to forget to add
#new files yourself (see 'git help status').
#
#
#
#
#
#
#
#
#
#
#
#
#
#
#














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