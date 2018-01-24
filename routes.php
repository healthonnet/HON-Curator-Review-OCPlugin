<?php

/*Route::resource('api/v1/servicre',
    'AhmadFatoni\ApiGenerator\Controllers\API\ServiceController',
    ['except' => ['destroy', 'create', 'edit']]);*/

Route::get('api/v1/service/{id}',
    ['as' => 'api/v1/service.show', 'uses' => 'Hon\Honcuratorreview\Controllers\API\ServiceController@show'])->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
