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

//后台登录和登出
Route::rule('login','img/Index/index');
Route::rule('out','img/Index/out');

Route::rule('modify/pwd','img/Manager/modifyPwd');
Route::rule('manager/add','img/Manager/managerAdd');

// 房卡发放
Route::rule('card/punch','img/Card/punchOne');
Route::rule('card/pay','img/Card/punchAll');




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
