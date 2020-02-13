<?php

Route::group(['middleware' => ['jwt.auth']], function ()
{

    Route::resource('leads', 'ApiController', [
        'except'     => ['create', 'edit'],
        'parameters' => ['leads' => 's_leads']
    ]);
});
