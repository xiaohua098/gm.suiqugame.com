<?php
namespace app\img\controller;
use think\Controller;
class Test extends Controller{
	public  function   test(){
		return renderJson('200','url路径访问成功！');
	}
}
