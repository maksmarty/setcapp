<?php 
/*
 * Route Module : Login
 * Descripttion : Route configuation for login module.
 */


Route::api ( ['version' => 'v1' , 'prefix' => 'api' , 'protected' => false ] , function() //'before' => 'checktoken' ,
{

    Route::resource('course', '\App\Modules\Duration\Controllers\DurationController', array('only' => array(/*'index',*/ 'store', /*'show', 'update', 'destroy'*/)));

});
