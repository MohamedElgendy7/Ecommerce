<?php

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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


define('PAGINATION_COUNT', 10);
Route::group(
    ['namespace' => 'Admin', 'middleware' => 'auth:admin'],
    function () {
        Route::get('/', 'DashboardController@index')->name('admin.dashboard');

        ######################### Begin Languages Route ########################
        Route::group(['prefix' => 'languages'], function () {
            Route::get('/', 'LanguagesController@index')->name('admin.languages');
            Route::get('create', 'LanguagesController@create')->name('admin.languages.create');
            Route::post('store', 'LanguagesController@store')->name('admin.languages.store');

            Route::get('edit/{id}', 'LanguagesController@edit')->name('admin.languages.edit');
            Route::post('update/{id}', 'LanguagesController@update')->name('admin.languages.update');

            Route::get('delete/{id}', 'LanguagesController@destroy')->name('admin.languages.delete');
        });
        ######################### End Languages Route ########################


        ######################### Begin Main Categoris Routes ########################
        Route::group(['prefix' => 'main_categories'], function () {
            Route::get('/', 'MainCategoriesController@index')->name('admin.maincategories');

            Route::get('create', 'MainCategoriesController@create')->name('admin.maincategories.create');
            Route::post('store', 'MainCategoriesController@store')->name('admin.maincategories.store');

            Route::get('edit/{id}', 'MainCategoriesController@edit')->name('admin.maincategories.edit');
            Route::post('update/{id}', 'MainCategoriesController@update')->name('admin.maincategories.update');

            Route::get('delete/{id}', 'MainCategoriesController@destroy')->name('admin.maincategories.delete');

            Route::get('changeStatus/{id}', 'MainCategoriesController@changeStatus')->name('admin.maincategories.status');
        });
        ######################### End Main Categoris Route ########################


        ######################### Begin vendors Routes ########################
        Route::group(['prefix' => 'vendors'], function () {
            Route::get('/', 'VendorsController@index')->name('admin.vendors');

            Route::get('create', 'VendorsController@create')->name('admin.vendors.create');
            Route::post('store', 'VendorsController@store')->name('admin.vendors.store');

            Route::get('edit/{id}', 'VendorsController@edit')->name('admin.vendors.edit');
            Route::post('update/{id}', 'VendorsController@update')->name('admin.vendors.update');

            Route::get('delete/{id}', 'VendorsController@destroy')->name('admin.vendors.delete');

            Route::get('changeStatus/{id}', 'VendorsController@changeStatus')->name('admin.vendors.status');
        });
        ######################### End vendor Route ########################


        ######################### Begin subcategories Routes ########################
        Route::group(['prefix' => 'sub_categories'], function () {
            Route::get('/', 'SubCategoryController@index')->name('admin.subcategories');

            Route::get('create', 'SubCategoryController@create')->name('admin.subcategories.create');
            Route::post('store', 'SubCategoryController@store')->name('admin.subcategories.store');

            Route::get('edit/{id}', 'SubCategoryController@edit')->name('admin.subcategories.edit');
            Route::post('update/{id}', 'SubCategoryController@update')->name('admin.subcategories.update');

            Route::get('delete/{id}', 'SubCategoryController@destroy')->name('admin.subcategories.delete');

            Route::get('changeStatus/{id}', 'SubCategoryController@changeStatus')->name('admin.subcategories.status');
        });
        ######################### End subcategories Route ########################


    }


);



Route::group(
    ['namespace' => 'admin', 'middleware' => 'guest:admin'],

    function () {

        Route::get('/login', 'LoginController@getLogin')->name('get.admin.login');
        Route::post('/login', 'LoginController@login')->name('admin.login');
    }
);




// Route::get('/', 'HomeController@index')->name('home');
