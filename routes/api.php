<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::get('/user/{email}', 'LoginController@user');
Route::middleware('auth:sanctum')->get('/user/{email}', 'LoginController@user');

//測試自定義的Exceptions使用
// Route::post('/login', [LoginController::class, 'login']);
Route::get('/test', function (Request $request) {
    return "test ok";
})->middleware('throttle:5,1');

//登入與二次驗證相關api
Route::post('/login', 'LoginController@login');
Route::post('/register', 'RegisterController@register');
Route::post('/logout', 'LoginController@logout');
Route::post("/google2fa_login", 'LoginController@checkGoogle2faOTP');

//產品清單api
// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', 'ProductController@index');
    Route::post('/products', 'ProductController@store');
    Route::get('/products/{id}', 'ProductController@show');
    Route::patch('/products/{id}', 'ProductController@update');
    Route::delete('/products/{id}', 'ProductController@destroy');
    Route::post('/search', 'ProductController@search');
// });

//GUZZLE api
// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/guzzle', 'GuzzleController@guzzleGet');
    Route::post('/guzzle', 'GuzzleController@guzzlePost');
    Route::get('/guzzle/{id}', 'GuzzleController@guzzleShow');
    Route::patch('/guzzle/{id}', 'GuzzleController@guzzlePatch');
    Route::delete('/guzzle/{id}', 'GuzzleController@guzzleDestroy');
    Route::post('/guzzle_search', 'GuzzleController@guzzleSearch');
// });