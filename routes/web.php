<?php

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

Auth::routes(['register' => false]);
Route::get('/', 'API\DashboardController@redirectDashboard')->name('welcomemain');

//Forgot Password
Route::post('resetPassword', 'Auth\ForgotPasswordController@resetPassword')->name('resetPassword');
Route::get('verifyEmail','API\DistributorController@verifyEmail')->name('verifyEmail');
Route::get('verifyTreatmentEmail','API\TreatmentCenterController@verifyTreatmentEmail')->name('verifyTreatmentEmail');
Route::post('contact-detail-register','ContactController@storeContact');

/* Role and permission  */
Route::get('assign-permission/{roleId?}','API\DashboardController@assignPermissionToRole')->name('assign-permission');
Route::post('assign-permission/','API\DashboardController@updatePermissionToRole')->name('assign-permission');
/* Role and permission  */

// System Admin Access role
Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard','API\DashboardController@index')->name('home');
    Route::get('system-activity-list','API\DashboardController@index')->name('system-activity-list');
    Route::post('updatePassword', 'API\UserController@updatePassword')->name('updatePassword');
    Route::get('change_password', 'API\UserController@changePasswordView')->name('change_password');
    Route::post('change-password', 'API\UserController@changePassword')->name('changepassword');
    Route::post('checkExistingPassword', 'API\UserController@checkExistingPassword')->name('checkExistingPassword');
    Route::post('checkCurrentPassword', 'API\UserController@checkCurrentPassword')->name('checkCurrentPassword');
    Route::post('/edit/profile','API\UserController@editProfile');
    Route::post('uniqueuseremailData','API\UserController@uniqueuseremail');
    Route::get('roles-permission','HomeController@rolesandpermission');
    Route::get('audit-list','HomeController@getAuditLogData')->name('audit-list')->middleware('role:system administrator|ema service support|distributor principal|distributor service');
    Route::get('view-audit-detail','HomeController@getAuditLogDetail')->name('view-audit-detail')->middleware('role:system administrator|ema service support|distributor principal|distributor service');
    Route::post('register-user','API\UserController@register')->name('register-user');
    Route::get('users-list','API\UserController@getUserList')->name('users-list')->middleware('role:system administrator|ema service support|ema analyst');
    Route::post('/suspend/{userId}','API\UserController@suspendUser')->name('suspend-user')->middleware('role:system administrator|ema service support|treatment centre manager|distributor principal|distributor service');
    Route::post('/release/{userId}','API\UserController@releaseUser')->name('release-user')->middleware('role:system administrator|ema service support|treatment centre manager|distributor principal|distributor service');
    Route::get('/show-user/{id}', 'API\UserController@show')->name('show-user')->middleware('role:system administrator|ema service support|ema analyst');
    Route::post('/force/{userId}','API\UserController@forceLogoutUser')->name('force-logout')->middleware('role:system administrator|ema service support|treatment centre manager|distributor principal|distributor service|distributor sales');
    Route::post('/update-user/{id}', 'API\UserController@update')->name('update-user')->middleware('role:system administrator');
    Route::post('/reset_password/{userId}','API\UserController@resetPasswordUser')->name('reset_password')->middleware('role:system administrator|ema service support|treatment centre manager|distributor principal|distributor sales|distributor service');
    Route::get('/ema_users','API\UserController@index')->name('ema_users')->middleware('role:system administrator|ema service support|ema analyst');
    Route::get('/ema_users/edit/{id}','API\UserController@edit')->name('user-edit')->middleware('role:system administrator');
    Route::delete('user/delete/{id}', 'API\UserController@destroy')->name('user-delete')->middleware('role:system administrator|treatment centre manager|distributor principal');
    Route::post('/check-user-exists','API\UserController@checkUsernameIsExists')->name('check-user-exists');


    /*treatment center APIS*/
    Route::get('/treatment-centre-list','API\TreatmentCenterController@treatmentcenterList')->name('treatment-centre-list')->middleware('role:system administrator|ema analyst|ema service support|distributor principal|distributor service|distributor sales');
    Route::post('/get-treatment-centre-details','API\TreatmentCenterController@gettreatmentCenterDetails')->name('get-treatment-center-details')->middleware('role:system administrator|ema analyst|ema service support|distributor principal|distributor service|distributor sales|treatment centre manager');
    Route::post('/add-treatment-centre','API\TreatmentCenterController@addTeatmentCenter')->name('add-treatment-center')->middleware('role:system administrator|distributor principal');
    Route::post('/update-treatment-centre','API\TreatmentCenterController@updateTeatmentCenter')->name('update-treatment-center')->middleware('role:system administrator|treatment centre manager|distributor principal|distributor service|distributor sales');
    Route::post('/delete-treatment-centre','API\TreatmentCenterController@deleteTeatmentCenter')->name('delete-treatment-center')->middleware('role:system administrator');
    Route::post('/suspend-treatment-centre','API\TreatmentCenterController@suspendTeatmentCenter')->name('suspend-treatment-center')->middleware('role:system administrator|ema service support');
    Route::post('/check-value-exists','API\TreatmentCenterController@checkValueIsExists')->name('check-value-exists');
    Route::get('/view-treatment-centre/{id}','API\TreatmentCenterController@viewTreatmentCenterAssosiatedData')->name('view-treatment-center')->middleware('role:system administrator|ema analyst|ema service support|treatment centre manager|distributor principal|distributor service|distributor sales');
    Route::get('/list-treatment-centre-Principal/{id}','API\TreatmentCenterController@getListOfTreatmentCentePrincipal')->name('list-treatment-center-Principal')->middleware('role:system administrator|ema analyst|ema service support|treatment centre manager|distributor principal|distributor service|distributor sales');
    Route::post('/release-treatment-centre','API\TreatmentCenterController@releaseTeatmentCenter')->name('release-treatment-center');
    Route::get('/treatmentcentre-file-upload/{id}','API\TreatmentCenterController@uploadTreatmentCenterFile')->name('treatmentcentre-file-upload')->middleware('role:system administrator|distributor principal|distributor sales|treatment centre manager|ema analyst');
    Route::get('/list-treatmentcentre-file','API\TreatmentCenterController@listtreatmentCentreFiles')->name('list-treatmentcentre-file')->middleware('role:system administrator|ema analyst|treatment centre manager|distributor principal|distributor sales');
    Route::post('/save-treatmentcentrefile-upload','API\TreatmentCenterController@saveTreatmentCenterFile')->name('save-treatmentcentrefile-upload')->middleware('role:system administrator|ema analyst|distributor principal|distributor sales|treatment centre manager|ema analyst');

    Route::post('unique-treamentcenter-code','API\TreatmentCenterController@treatmentcenterUniqueCode');
    Route::get('treatment-data-details/{centreId}/{jsonID}','API\TreatmentCenterController@treatmentDataDetails')->name('treatment-data-details')->where('centreId', '[0-9]+')->where('jsonID','[0-9]+');
    /*treatment center APIS*/

    /*Hydracool SPR and Units APIS*/
    Route::post('/add-hydracoolsrp','API\HydracoolSrpController@addHydracoolSrp')->name('add-hydracoolsrp')->middleware('role:system administrator|ema service support|distributor principal');
    Route::post('/update-hydracoolsrp','API\HydracoolSrpController@updateHydracoolSrp')->name('update-hydracoolsrp')->middleware('role:system administrator|ema service support|distributor principal');
    Route::get('/get-hydracoolsrp-list','API\HydracoolSrpController@getHydraCoolSRPList')->name('get-hydracoolsrp-list')->middleware('role:system administrator');
    Route::post('/add-non-ema-hydracoolsrp','API\HydracoolSrpController@addNonEmaHydracoolSrp')->name('add-non-ema-hydracoolsrp')->middleware('role:system administrator|ema service support|distributor principal');
    Route::post('/check-serialnumber','API\HydracoolSrpController@checkSerialNumberUnique')->name('check-serialnumber');
    Route::post('/get-srpunit-by-serialnumber','API\HydracoolSrpController@getSrpUnitsbySerialNumber')->name('get-srpunit-by-serialnumber');
    Route::post('/get-hydracoolsrp-details','API\HydracoolSrpController@getHydracoolSrpDetails')->name('get-hydracoolsrp-details')->middleware('role:system administrator|ema analyst|ema service support|treatment centre manager|distributor principal|distributor service|distributor sales');
    Route::post('/suspend-hydracoolsrp','API\HydracoolSrpController@suspendHydracoolSrp')->name('suspend-hydracoolsrp')->middleware('role:system administrator');
    Route::post('/release-hydracoolsrp','API\HydracoolSrpController@releaseHydracoolSrp')->name('release-hydracoolsrp')->middleware('role:system administrator');
    Route::post('/delete-hydracoolsrp','API\HydracoolSrpController@deleteHydracoolSrp')->name('delete-hydracoolsrp')->middleware('role:system administrator|ema service support');
    Route::post('check-handsetValueUnique','API\HydracoolSrpController@checkHandsetSerialNumberValueUnique')->name('check-handsetValueUnique');
    Route::post('uniqueserialnumber','API\HydracoolSrpController@uniqueSerialNumber')->name('uniqueserialnumber');
    Route::post('getAllHydraCoolSrp','API\HydracoolSrpController@getAllHydraCoolSrp')->name('getAllHydraCoolSrp');
    /*Hydracool SPR and Units APIS*/

    /*add treatment center users principal/sales*/
    Route::post('/add-treatmentcentre-user','API\TreatmentCenterUsersController@addTeatmentCenterUser')->name('add-treatmentcenter-user')->middleware('role:system administrator|treatment centre manager|distributor principal');
    Route::get('/view-treatmentcentre-user','API\TreatmentCenterUsersController@listTeatmentCenterUser')->name('view-treatmentcenter-user');
    Route::post('/update-treatmentcentre-user','API\TreatmentCenterUsersController@updateTeatmentCenterUser')->name('update-treatmentcenter-user')->middleware('role:system administrator|treatment centre manager|distributor principal');
    Route::post('/delete-treatmentcentre-user','API\TreatmentCenterUsersController@deleteTeatmentCenterUser')->name('delete-treatmentcenter-user')->middleware('role:system administrator');
    Route::post('/suspend-treatmentcentre-user','API\TreatmentCenterUsersController@suspendTeatmentCenterUser')->name('suspend-treatmentcenter-user')->middleware('role:system administrator');
    Route::post('/release-treatmentcentre-user','API\TreatmentCenterUsersController@releaseTeatmentCenterUser')->name('release-treatmentcenter-user')->middleware('role:system administrator');
    /*add treatment center users principal/sales*/


    /* Distributor routes */
    Route::post('/add-distributor','API\DistributorController@addDistributor')->name('add-distributor')->middleware('role:system administrator');
    Route::get('/distributor','API\DistributorController@getDistributorData')->name('distributor')->middleware('role:system administrator|distributor principal|ema analyst|ema service support');
    Route::post('/update-distributor','API\DistributorController@updateDistributor')->name('update-distributor')->middleware('role:system administrator|distributor principal');
    Route::get('/delete-distributor/{id}','API\DistributorController@deleteDistributor')->name('delete-distributor')->middleware('role:system administrator');
    Route::get('/suspend-distributor/{id}','API\DistributorController@suspendDistributor')->name('suspend-distributor')->middleware('role:system administrator|ema service support');
    Route::get('/release-distributor/{id}','API\DistributorController@releaseDistributor')->name('release-distributor')->middleware('role:system administrator|ema service support');

    Route::post('uniqueDistributorCode','API\DistributorController@distributorCode');

    Route::get('distributor-list/{internal_id}','API\DistributorController@distributorView')->name('distributor-list')->middleware('role:system administrator|ema analyst|ema service support|distributor principal|distributor service|distributor sales');
    Route::post('/add-distributor-user','API\DistributorUsersController@addDistributorUser')->name('add-distributor-user')->middleware('role:system administrator|distributor principal');
    Route::get('/view-distributor-user','API\DistributorUsersController@listDistributorUser')->name('view-distributor-user')->middleware('role:system administrator|ema analyst|ema service support|distributor principal|distributor service|distributor sales');
    Route::get('/show-distributor-user/{id}','API\DistributorUsersController@show')->middleware('role:system administrator|ema analyst|distributor principal|distributor sales|ema service support|distributor service');
    Route::post('/uniqueuseremail','API\DistributorUsersController@uniqueUserEmail');
    Route::post('/uniqueusername','API\DistributorUsersController@uniqueUserName');
    Route::get('/edit-distributor-user','API\DistributorUsersController@getDistributorUser')->middleware('role:system administrator|distributor principal|');
    Route::post('/update-distributor-user','API\DistributorUsersController@updateDistributorUser')->middleware('role:system administrator|distributor principal|distributor service');
    Route::post('/delete-distributor-user','API\DistributorUsersController@deleteDistributorUser')->middleware('role:system administrator');
    Route::post('/forcelogout/{id}','API\DistributorUsersController@forceLogout')->middleware('role:system administrator');
    Route::get('/distributors-treatmentcenter-list','API\DistributorUsersController@treatmentCenter')->middleware('role:system administrator|ema analyst|ema service support|distributor principal|distributor service|distributor sales');
    Route::get('show/treatmentCenter/{id}','API\DistributorUsersController@treatementcentreData');
    /* Distributor routes */

    /* Device routes*/
    Route::get('/devices','API\DeviceController@hydracoolSrpList')->name('devices')->middleware('role:system administrator|ema analyst|ema service support|treatment centre manager|distributor principal|distributor service|distributor sales');
    /* Device routes*/

    /* Session Route */
    Route::get('/settings','API\CoreSettingController@editCoreSetting')->name('settings');
    Route::post('/updatecoresetting','API\CoreSettingController@updateCoreSetting')->name('updatecoresetting');
    /*Session Route*/



    /* Media library routes*/
    Route::get('/media-library','API\MediaLibraryController@mediaLibraryList')->name('media-library')->middleware('role:system administrator|ema analyst|ema service support|treatment centre manager|distributor principal|distributor service|distributor sales');
    Route::post('/save-media-library','API\MediaLibraryController@saveMediaLibraryData')->name('save-media-library')->middleware('role:system administrator');
    Route::get('/download', 'API\MediaLibraryController@downloadMediaFile')->name('download')->middleware('role:system administrator|ema analyst|ema service support|treatment centre manager|distributor principal|distributor service|distributor sales');
    Route::post('/delete-media-library', 'API\MediaLibraryController@deleteMediaLibraryData')->name('delete-media-library')->middleware('role:system administrator');


    Route::get('emailTemplate', 'API\EmailTemplatesController@emailTemplate')->name('emailTemplate');
    Route::post('createEmailTemplate', 'API\EmailTemplatesController@createEmailTemplate')->name('createEmailTemplate');
    Route::post('get-emailTemplate-details','API\EmailTemplatesController@getEmailTempalteDetails')->name('get-emailTemplate-details');
    Route::post('delete-emailTemplate','API\EmailTemplatesController@deleteEmailTemplate')->name('delete-emailTemplate');
    Route::post('checkExistingSlug', 'API\EmailTemplatesController@checkExistingSlug')->name('checkExistingSlug');

    Route::get('SMSTemplate', 'API\SMSTemplatesController@SMSTemplate')->name('SMSTemplate');
    Route::post('createSMSTemplate', 'API\SMSTemplatesController@createSMSTemplate')->name('createSMSTemplate');
    Route::post('get-SMSTemplate-details','API\SMSTemplatesController@getSMSTemplateDetails')->name('get-SMSTemplate-details');
    Route::post('delete-SMSTemplate','API\SMSTemplatesController@deleteSMSTemplate')->name('delete-SMSTemplate');
    Route::post('checkExistingSMSSlug', 'API\SMSTemplatesController@checkExistingSMSSlug')->name('checkExistingSMSSlug');

    /* Cosmetic deliveries data */
        Route::post('add-cosmeticdata', 'API\CosmeticDeliveriesController@addCosmeticData')->name('add-cosmeticdata')->middleware('role:system administrator|distributor principal|distributor service|distributor sales');
        Route::get('get-cosmeticdata/{id}', 'API\CosmeticDeliveriesController@getCosmeticDeliveries')->name('get-cosmeticdata');
        Route::post('view-cosmeticdata', 'API\CosmeticDeliveriesController@getCosmeticDeliveriesDetails')->name('view-cosmeticdata')->middleware('role:system administrator|distributor principal|distributor service|distributor sales|ema analyst|ema service support');
        Route::post('update-cosmeticdata', 'API\CosmeticDeliveriesController@updateCosmeticData')->name('update-cosmeticdata')->middleware('role:system administrator|distributor principal|distributor service|distributor sales');
        Route::post('delete-cosmeticdata','API\CosmeticDeliveriesController@deleteCosmeticData')->name('delete-cosmeticdata')->middleware('role:system administrator|distributor principal|distributor service|distributor sales');
    /* Cosmetic deliveries data */

    /* Diagnostic data */
    Route::get('diagnostic-data','API\DiagnosticController@diagnosticData')->name('diagnosticData');
    Route::post('diagnostic-details','API\DiagnosticController@diagnosticDetails')->name('diagnosticDetails');
    Route::get('/diagnostic-data-dashboard','API\DiagnosticController@diagnosticDataDashboard')->name('diagnosticDataDashboard');
    Route::get('/show-diagnostic-data/{id}','API\DiagnosticController@viewdiagnosticData')->name('show-diagnostic-data');
    Route::post('add-diagnostic-comment','API\DiagnosticController@addDiagnosticComment')->name('add-diagnostic-comment');
    Route::get('get-diagnostic-comment','API\DiagnosticController@getDiagnosticCommentsList')->name('get-diagnostic-comment');

    /* Treatment data  */
        Route::post('/get-treatment-data','API\TreatmentCenterController@getTreatmentJsonData')->name('get-treatment-data');
        Route::post('/get-treatmentjson-data','API\TreatmentCenterController@editTreatmentJsonData')->name('get-treatmentjson-data');
    /* Treatment data  */

});

Route::get('/settimezone','API\DashboardController@setTimeZone')->name('settimezone');

Route::get('logout',function(){
    return redirect()->route('login');
});

/*Route::get('help', function () {
    return view('help');
});*/
Route::get('help','ContactController@help')->name('help');

/* Report route start */
    Route::get('treatmentcentre-customer-number','ReportController@reportTreatmentByCustomerNumber')->name('treatmentcentre-customer-number');
/* Report route start */

