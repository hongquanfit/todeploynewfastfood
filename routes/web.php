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

Route::get('/', 'FE\HomeController@index');
Route::get('/details/{info?}', 'FE\DetailsController@show');

Route::get('/search/{by?}/{id?}/', 'FE\HomeController@searchByType');
Route::get('/find/{name?}/', 'FE\HomeController@findItem');
Route::get('/show/{type?}/{rate?}/{status?}', 'FE\HomeController@showAll');
//login
Route::get('/login', 'LoginController@showLogin');
Route::post('/login', 'LoginController@login')->name('doLogin');
Route::get('/logout', 'LoginController@logout');
//register
Route::get('/register', 'LoginController@register');
Route::post('/doRegister', 'LoginController@doRegister')->name('doReg');
//go back
Route::get('/nothavepermission', function(){
	return view('welcome');
});
Route::get('/goback', function(){
	return redirect('/');
});
Route::group(['prefix' => 'admin', 'middleware' => 'isAdmin'], function(){
    //type
    Route::group(['prefix' => 'type'], function(){
    	Route::get('/', 'Admin\TypeController@getType');
    	Route::post('/editType','Admin\TypeController@doEdit');
    	Route::post('/sort', 'Admin\TypeController@sort');
    	Route::post('/detectID', 'Admin\TypeController@detectID');
    	Route::post('/confirmdelete', 'Admin\TypeController@confirmDelete')->name('type.confirm');
    });

    Route::group(['prefix' => 'food'], function(){
        Route::get('/', 'Admin\FoodController@getListFood');
        Route::post('/editname', 'Admin\FoodController@editName');
        Route::post('/changeAvatar', 'Admin\FoodController@changeAvatar');
        Route::post('/edittype', 'Admin\FoodController@editType');
        Route::post('/changeStatus', 'Admin\FoodController@changeStatus');
        Route::get('/sort/{type?}/{rate?}/{status?}', 'Admin\FoodController@sortBy');
        Route::post('/getAddress', 'Admin\FoodController@getAddress');
        Route::post('/editAddress', 'Admin\FoodController@editAddress');
        Route::get('/delItem/{foodId?}/{adrId?}', 'Admin\FoodController@deleteItem');
        Route::get('/getFoodNutrition/{id?}', 'Admin\FoodController@getFoodNutrition');
        Route::post('/changeNutrition', 'Admin\FoodController@changeNutrition')->name('admin.food.changeNutrition');
    });

    Route::group(['prefix' => 'setup'], function(){
        Route::get('/', 'Admin\SetupController@index');
    });

});
//Frontend
Route::group(['prefix' => 'user', 'middleware' => 'isAdmin'], function(){
    Route::post('/suggestfood', 'FE\HomeController@doSuggest')->name('suggest');
    Route::post('/rating', 'FE\RatingController@rateFood');
    Route::post('/addFavorite', 'FE\RatingController@addFavorite');
    Route::post('/addAdr', 'FE\DetailsController@addAddress')->name('user.addAdr');
    Route::post('/addComment', 'FE\DetailsController@addComment');
    Route::get('/detectIngredient/{name?}', 'FE\HomeController@detectIngredient');
    Route::post('/comment', 'FE\RatingController@comment');
});
