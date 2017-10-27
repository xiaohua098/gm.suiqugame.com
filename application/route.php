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

//后台登录
Route::rule('login','img/Index/index');


//GM后台用户
Route::rule('manager','img/Manager/manager');
// 房卡发放
Route::rule('card','img/Card/card');
//权限
Route::rule('auth','img/Auth/auth');
//角色
Route::rule('role','img/Role/role');
//修改房卡价格
Route::rule('price','img/Price/price');
//公告和跑马灯
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
Route::rule('agent','img/Agent/agent');
//代理每日数据
Route::rule('agentdaily','img/Agentdaily/agentdaily');
//代理划卡详情
Route::rule('agentpunch','img/Agentpunch/agentpunch');
//操作日志
Route::rule('operate','img/Operate/operate');




//测试
Route::rule('test','img/Test/test');


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


