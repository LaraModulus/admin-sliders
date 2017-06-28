<?php
use Illuminate\Support\Facades\Route;
Route::group([
    'prefix'     => 'admin/sliders',
    'middleware' => ['admin', 'auth.admin'],
    'namespace'  => 'LaraMod\Admin\Sliders\Controllers',
], function () {
    Route::get('/', ['as' => 'admin.sliders', 'uses' => 'SlidersController@index']);
    Route::get('/form', ['as' => 'admin.sliders.form', 'uses' => 'SlidersController@getForm']);
    Route::post('/form', ['as' => 'admin.sliders.form', 'uses' => 'SlidersController@postForm']);

    Route::get('/delete', ['as' => 'admin.sliders.delete', 'uses' => 'SlidersController@delete']);
    Route::get('/datatable', ['as' => 'admin.sliders.datatable', 'uses' => 'SlidersController@dataTable']);
});