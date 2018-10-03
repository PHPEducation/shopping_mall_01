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

Route::get('/', function () {
    return view('welcome');
});
Route::group(['namespace' => 'Admin'], function () {
    Route::group(['prefix' => 'login', 'middleware' => 'CheckLogedIn'], function () {
        Route::get('/', 'LoginController@getLogin')->name('login');
        Route::post('/', 'LoginController@postLogin');
    });
    Route::get('logout', 'HomeController@getLogout');
    Route::group(['prefix' => 'admin', 'middleware' => 'CheckLogedOut'], function() {
        Route::get('home', 'HomeController@getHome');
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', 'CategoryController@getAllCategories')->name('category');
            Route::post('/', 'CategoryController@postAllCategories');
            Route::get('edit/{id}', 'CategoryController@getEditCategories')->name('editcategory');
            Route::post('edit/{id}', 'CategoryController@postEditCategories');
            Route::get('delete/{id}', 'CategoryController@getDeleteCategories');
        });
    });
});
