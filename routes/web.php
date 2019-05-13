<?php

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




//Auth::routes();
Route::post('/', 'CoursesController@index');
Route::post('/register', 'Auth\RegisterController@create');
Route::post('/login', 'Auth\LoginController@authenticate');
Route::get('/logout', function () {
    Auth::logout();
    return 'logout_OK';
});
//授業番号を保存
Route::post('/update', 'AuthCoursesController@update');
Route::post('/course', 'AuthCoursesController@show_course');
//時間割データーを保存
Route::post('/store', 'AuthCoursesController@upload_detail');
Route::get('/detail', 'AuthCoursesController@show_detail');


/*
Route::get('/home2', function(){
    return view('welcome');
})->name('home');

//Route::get('/home', 'HomeController@index');
