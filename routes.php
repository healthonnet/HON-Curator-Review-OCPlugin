<?php

/*Route::resource('api/v1/servicre',
    'AhmadFatoni\ApiGenerator\Controllers\API\ServiceController',
    ['except' => ['destroy', 'create', 'edit']]);*/

Route::get('api/v1/service/{id}',
    ['as' => 'api/v1/service.show', 'uses' => 'Hon\Honcuratorreview\Controllers\API\ServiceController@show']);


Route::post('api/v1/review/add',
    ['' => '', '']);

Route::resource('api/v1/review',
    'AhmadFatoni\ApiGenerator\Controllers\API\ReviewController',
    ['except' => ['destroy', 'create', 'edit']]);

/*Route::get('api/v1/review/{id}/delete',
    ['as' => 'api/v1/review.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ReviewController@destroy'])
    ->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');*/


Route::post('api/v1/review/create',
    ['as' => 'api/v1/review.create', 'uses' => 'Hon\Honcuratorreview\Controllers\API\ReviewController@store'])
    ->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');