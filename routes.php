<?php

/*Route::resource('api/v1/servicre',
    'AhmadFatoni\ApiGenerator\Controllers\API\ServiceController',
    ['except' => ['destroy', 'create', 'edit']]);*/

Route::get('api/v1/service/{id}',
    ['as' => 'api/v1/service.show', 'uses' => 'Hon\Honcuratorreview\Controllers\API\ServiceController@show']);

Route::get('api/v1/domain/',
    ['as' => 'api/v1/service.showByDomain', 'uses' => 'Hon\Honcuratorreview\Controllers\API\ServiceController@showByDomain']);

Route::post('api/v1/review/add',
    ['' => '', '']);

Route::resource('api/v1/review',
    'Hon\Honcuratorreview\Controllers\API\ReviewController',
    ['except' => ['destroy', 'create', 'edit']]);

/*Route::get('api/v1/review/{id}/delete',
    ['as' => 'api/v1/review.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ReviewController@destroy'])
    ->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');*/


Route::post('api/v1/review/create',
    ['as' => 'api/v1/review.create', 'uses' => 'Hon\Honcuratorreview\Controllers\API\ReviewController@store'])
    ->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');

Route::post('api/v1/review/update/{id}',
    ['as' => 'api/v1/review.update', 'uses' => 'Hon\Honcuratorreview\Controllers\API\ReviewController@update'])
    ->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');


Route::post('api/v1/review/get',
    ['as' => 'api/v1/review.get', 'uses' => 'Hon\Honcuratorreview\Controllers\API\ReviewController@get'])
    ->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');