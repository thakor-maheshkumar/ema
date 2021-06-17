<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'API\UserController@login');
Route::middleware(['auth:api','role:system administrator'])->post('register', 'API\UserController@register');
Route::middleware(['auth:api'])->put('/update-user/{id}', 'API\UserController@update')->name('update-user');
Route::middleware(['auth:api'])->delete('user/delete/{id}', 'API\UserController@destroy');
Route::middleware(['auth:api'])->get('user/softdeleted', 'API\UserController@softDeleted');


Route::get('details', 'API\UserController@details');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->post('/logout','API\UserController@logout');


/*api for capture thingstram autit log*/
	//Route::post('audit-log', 'API\UserController@addAuditLog')->name('audit-log');
	Route::post('audit-log', 'ContactController@addAuditLog')->name('audit-log');
/*api for capture thingstram autit log*/

/* API to get the treatment centre and hydraCoolSRP unit details */
	Route::post('get-treatmentcentre-details', 'API\TreatmentCenterController@getTreatmentCentreDetails')->name('get-treatmentcentre-details');
/* API to get the treatment centre and hydraCoolSRP unit details */


/*treatment center APIS*/
	Route::middleware('auth:api')->post('/add-treatment-center','API\TreatmentCenterController@addTeatmentCenter')->name('add-treatment-center');
	Route::middleware('auth:api')->post('/update-treatment-center','API\TreatmentCenterController@updateTeatmentCenter')->name('update-treatment-center');
	Route::middleware('auth:api')->post('/delete-treatment-center','API\TreatmentCenterController@deleteTeatmentCenter')->name('delete-treatment-center');
	Route::middleware('auth:api')->post('/suspend-treatment-center','API\TreatmentCenterController@suspendTeatmentCenter')->name('suspend-treatment-center');
	Route::middleware('auth:api')->post('/check-value-exists','API\TreatmentCenterController@suspendTeatmentCenter')->name('check-value-exists');
	Route::middleware('auth:api')->post('/check-value-exists','API\TreatmentCenterController@checkValueIsExists')->name('check-value-exists');
/*treatment center APIS*/

/* Session Management APIS */
	Route::middleware('auth:api')->post('add-coresetting','API\CoreSettingController@store');
	Route::middleware('auth:api')->get('show-coresetting/{id?}','API\CoreSettingController@show');
	Route::middleware('auth:api')->post('/update-coresetting/{id}','API\CoreSettingController@update');
	Route::middleware('auth:api')->get('/delete-coresetting/{id}','API\CoreSettingController@destroy');
/* Session Management APIS */

/*Hydracool SPR and Units APIS*/
	Route::middleware('auth:api')->post('/add-hydracoolsrp','API\HydracoolSrpController@addHydracoolSrp')->name('add-hydracoolsrp');
	Route::middleware('auth:api')->post('/update-hydracoolsrp','API\HydracoolSrpController@updateHydracoolSrp')->name('update-hydracoolsrp');
	Route::middleware('auth:api')->get('/get-hydracoolsrp-list','API\HydracoolSrpController@getHydraCoolSRPList')->name('get-hydracoolsrp-list');
	Route::middleware('auth:api')->post('/add-non-ema-hydracoolsrp','API\HydracoolSrpController@addNonEmaHydracoolSrp')->name('add-non-ema-hydracoolsrp');
	Route::middleware('auth:api')->post('/check-serialnumber','API\HydracoolSrpController@checkSerialNumberUnique')->name('check-serialnumber');
/*Hydracool SPR and Units APIS*/


/* Distributor APIS */
	Route::middleware('auth:api')->post('/add-distributor','API\DistributorController@addDistributor')->name('add-distributor');
	Route::middleware('auth:api')->get('/view-distributor/{id?}','API\DistributorController@viewDistributor')->name('view-distributor');
	Route::middleware('auth:api')->post('/update-distributor','API\DistributorController@updateDistributor')->name('update-distributor');
	Route::middleware('auth:api')->get('/delete-distributor/{id}','API\DistributorController@deleteDistributor')->name('delete-distributor');
/* Distributor APIS */


/*add treatment center users principal/sales*/
	Route::middleware('auth:api')->post('/add-treatmentcenter-user','API\TreatmentCenterUsersController@addTeatmentCenterUser')->name('add-treatmentcenter-user');
	Route::middleware('auth:api')->get('/view-treatmentcenter-user','API\TreatmentCenterUsersController@listTeatmentCenterUser')->name('view-treatmentcenter-user');
	Route::middleware('auth:api')->post('/update-treatmentcenter-user','API\TreatmentCenterUsersController@updateTeatmentCenterUser')->name('update-treatmentcenter-user');
	Route::middleware('auth:api')->post('/delete-treatmentcenter-user','API\TreatmentCenterUsersController@deleteTeatmentCenterUser')->name('delete-treatmentcenter-user');
/*add treatment center users principal/sales*/


/*add treatment center users principal/sales*/
	Route::middleware('auth:api')->post('/add-treatmentcenter-user','API\TreatmentCenterUsersController@addTeatmentCenterUser')->name('add-treatmentcenter-user');
	Route::middleware('auth:api')->get('/view-treatmentcenter-user','API\TreatmentCenterUsersController@listTeatmentCenterUser')->name('view-treatmentcenter-user');
	Route::middleware('auth:api')->post('/update-treatmentcenter-user','API\TreatmentCenterUsersController@updateTeatmentCenterUser')->name('update-treatmentcenter-user');
	Route::middleware('auth:api')->post('/delete-treatmentcenter-user','API\TreatmentCenterUsersController@deleteTeatmentCenterUser')->name('delete-treatmentcenter-user');
/*add treatment center users principal/sales*/

	Route::middleware('auth:api')->post('/add-distributor-user','API\DistributorUsersController@addDistributorUser')->name('add-distributor-user');

	Route::middleware('auth:api')->post('/view-distributor-user','API\DistributorUsersController@listDistributorUser')->name('view-distributor-user');

	Route::middleware('auth:api')->post('/update-distributor-user','API\DistributorUsersController@updateDistributorUser')->name('update-distributor-user');

	Route::middleware('auth:api')->post('/delete-distributor-user','API\DistributorUsersController@deleteDistributorUser')->name('delete-distributor-user');

	/* Lambda API */
    Route::post('add-jsonData', 'LambdaController@addJsonData')->name('add-jsonData');
	/* Lambda API */

	/* Send Email API */
    Route::post('sendemail', 'LambdaController@sendemail')->name('sendemail');
	/* Send Email API */