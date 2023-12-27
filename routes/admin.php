<?php

use Illuminate\Support\Facades\Route;

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
Define('PAGINATION_COUNT',10);
Route::Group(['middleware'=>'guest:admin','namespace'=>'Admin'], function (){
    Route::get('/login', 'LoginController@getLogin')->name('admin.dashboard');
    Route::post('/login', 'LoginController@Login')->name('admin.login');

});
Route::Group(['middleware'=>'auth:admin','namespace'=>'Admin'], function (){
    Route::get('/','DashboardController@index');

################################ Begin Langauges Routes ############################
    Route::Group(['prefix'=>'languages'], function (){
        Route::get('/','LanguagesController@index')->name('admin.languages');
        Route::get('create','LanguagesController@create')->name('admin.languages.create');
        Route::post('store','LanguagesController@store')->name('admin.languages.store');

        Route::get('edit/{id}','LanguagesController@edit')->name('admin.languages.edit');
        Route::post('update/{id}','LanguagesController@update')->name('admin.languages.update');

        Route::get('delete/{id}','LanguagesController@destroy')->name('admin.languages.delete');
    }) ;


################################ end Langauges Routes ############################


    ################################ Begin Categories Routes ############################
    Route::Group(['prefix'=>'main_categories'], function (){
        Route::get('/','MainCategoriesController@index')->name('admin.maincategories');
        Route::get('create','MainCategoriesController@create')->name('admin.maincategories.create');
        Route::post('store','MainCategoriesController@store')->name('admin.maincategories.store');
        Route::get('edit/{id}','MainCategoriesController@edit')->name('admin.maincategories.edit');
        Route::post('update/{id}','MainCategoriesController@update')->name('admin.maincategories.update');
        Route::get('delete/{id}','MainCategoriesController@destroy')->name('admin.maincategories.delete');
        Route::get('changeStatus/{id}','MainCategoriesController@changeStatus')->name('admin.maincategories.status');
    }) ;
################################ end Langauges Routes ############################

#################################### Begin Vendors Routes ############################

    Route::Group(['prefix'=>'vendors'], function (){
        Route::get('/','VendorsController@index')->name('admin.vendors');
        Route::get('create','VendorsController@create')->name('admin.vendors.create');
        Route::post('store','VendorsController@store')->name('admin.vendors.store');
        Route::get('edit/{id}','VendorsController@edit')->name('admin.vendors.edit');
        Route::post('update/{id}','VendorsController@update')->name('admin.vendors.update');
        Route::get('delete/{id}','VendorsController@destroy')->name('admin.vendors.delete');
        Route::get('changeStatus/{id}','VendorsController@changeStatus')->name('admin.vendors.status');
    }) ;

################################ end Vendors Routes ############################



});
