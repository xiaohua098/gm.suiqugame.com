<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[Infor]'     => [
//         ':url'   => ['index/Infor', ['method' => 'get'], ['url' => '\w+']],
//         ':name' => ['index/Infor', ['method' => 'post']],
//     ],

//     // '[hello]'     => [
//     //     ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//     //     ':name' => ['index/hello', ['method' => 'post']],
//     // ],

// ];

use think\Route;
// Route::rule('news','index/News/news_list');
// Route::rule('new/:url','index/News/news_show');

//后台登录
Route::rule('login','img/Index/index');

// Route::rule('modify/pwd','img/Manager/modifyPwd');
// Route::rule('manager/add','img/Manager/managerAdd');



Route::rule('manager','img/Manager/manager');

// 房卡发放
// Route::rule('card/punch','img/Card/punchOne');
// Route::rule('card/pay','img/Card/punchAll');

Route::rule('card','img/Card/card');
Route::rule('auth','img/Auth/auth');




Route::rule('notices','img/Message/notices');
Route::rule('horse','img/Message/horse');


//实时统计
Route::rule('census','img/Census/census');
//房卡消耗
Route::rule('expend','img/Expend/expend');
//房卡产出
Route::rule('punch','img/Punch/punch');
//房卡库存
Route::rule('stock','img/Stock/stock');

//充值记录和充值统计
Route::rule('recharge','img/Recharge/recharge');

//玩家
Route::rule('user','img/User/user');
//代理
Route::rule('user','img/User/user');
//代理每日数据
Route::rule('agentdaily','img/Agentdaily/agentdaily');
//代理划卡详情
Route::rule('agentpunch','img/Agentpunch/agentpunch');





//测试
Route::rule('test','img/Test/test');


// Route::rule('auth/list','img/Auth/authList');
// Route::rule('auth/add','img/Auth/authAdd');
// Route::rule('auth/edit','img/Auth/authEdit');
// Route::rule('auth/del','img/Auth/authDel');
// Route::rule('role/list','img/role/roleList'); 
// Route::rule('role/add','img/role/roleAdd'); 
// Route::rule('role/edit','img/role/roleEdit'); 
// Route::rule('role/del','img/role/roledel'); 
// //分配权限
// Route::rule('auth/assign','img/role/roleAssign'); 
// Route::rule('manager/list','img/Manager/managerList'); 
// Route::rule('manager/edit','img/Manager/managerEdit'); 
// Route::rule('manager/add','img/Manager/managerAdd'); 
// Route::rule('manager/del','img/Manager/managerDel');
// Route::rule('manager/pwd','img/Manager/modifyPwd');
// Route::rule('manager/verify','img/Manager/verify');
// //分配角色
// Route::rule('role/assign','img/Manager/managerAssign'); 
// Route::rule('msg/add','img/Message/msgAdd');
// //房卡消耗
// Route::rule('card/expend','img/Card/cardExpend');
// Route::rule('card/punch','img/Card/cardPunch');
// 
// 




//每日统计数据接口

//充值记录统计
Route::rule('total/recharge','img/Statistic/recharge');
//房卡产出统计
Route::rule('total/punch','img/Statistic/punch');
//房卡消耗数统计
Route::rule('total/expend','img/Statistic/expend');
//房卡库存统计
Route::rule('total/stock','img/Statistic/stock');
//代理每日数据
Route::rule('total/daily','img/Statistic/daily');
//玩家和代理统计
Route::rule('total/user','img/Statistic/user');
//总的统计
Route::rule('total/census','img/Statistic/census');


