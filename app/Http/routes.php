<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/login', [
    'uses' => 'UserController@getLogin',
    'middleware' => 'guest',
    'as' => 'login'
]);

Route::post('/login', [
    'uses' => 'UserController@postLogin',
    'middleware' => 'guest',
    'as' => 'login'
]);

Route::get('/password/email', [
    'uses' => 'Auth\PasswordController@getEmail',
    'as' => 'auth.password'
]);

Route::post('/password/email', [
    'uses' => 'Auth\PasswordController@postEmail',
    'as' => 'auth.password'
]);

Route::get('password/reset/{token}', [
    'uses' => 'Auth\PasswordController@getReset',
    'as' => 'auth.reset'
]);

Route::post('password/reset', [
    'uses' => 'Auth\PasswordController@postReset',
    'as' => 'auth.reset'
]);

Route::get('/logout', [
    'uses' => 'UserController@getLogout',
    'middleware' => 'auth',
    'as' => 'logout'
]);

Route::get('/admin/home', [
    'uses' => 'AdminController@getAdminHome',
    'middleware' => 'roles',
    'roles' => '1',
    'as' => 'admin.home'
]);

Route::get('/register', [
    'uses' => 'UserController@getRegister',
    'middleware' => 'guest',
    'as' => 'register'
]);

Route::post('/register', [
    'uses' => 'UserController@postRegister',
    'middleware' => 'guest',
    'as' => 'register'
]);

//---------------------

Route::get('/users_admin', function () {
    return view('admin.users_admin');
})->name('admin.users_admin');

Route::get('/changepassword_admin', function () {
    return view('admin.changepassword_admin');
})->name('admin.changepassword_admin');
