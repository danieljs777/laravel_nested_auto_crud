<?php

// ManutencaoTabela
Route::group(['middleware' => ['auth']], function ()
{
    Route::name('leads.')->group(function ()
    {
        Route::post('/download', 'WebController@download')->name('download');
        Route::get('/', 'WebController@index');
        Route::get('/novo', 'WebController@novo')->name('novo');
        Route::get('/edit/{param?}', 'WebController@edit')->name('edit');
        Route::get('/{route}/{id?}', 'WebController@service')->name('service')
                ->where(['route' => '^(lista|novo|historico|edit)$', 'id' => '[0-9]+']);
    });
});


