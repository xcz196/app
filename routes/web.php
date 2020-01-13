<?php
/*
 * @Author: 落叶
 * @Date: 2020-01-02 15:06:48
 * @LastEditTime : 2020-01-11 10:39:40
 * @LastEditors  : Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \app\routes\web.php
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//登录
Route::prefix('login')->namespace('Admin')->group(function () {
    Route::get('login', function () {
        return view('admin.login.login');
    });
//    loginVerify
    Route::post('login_do',"LoginController@login_do");
});

// index
Route::prefix('index')->middleware('loginVerify')->namespace('Admin')->group(function () {
    Route::get('index', function () {
        return view('admin.index.index');
    });
});