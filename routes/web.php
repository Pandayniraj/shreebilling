<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Authentication routes...
Auth::routes();

// // Password reset link request routes...
Route::get('password/email', ['as' => 'recover_password',        'uses' => 'Auth\PasswordController@getEmail']);
Route::post('password/email', ['as' => 'recover_passwordPost',    'uses' => 'Auth\PasswordController@postEmail']);
// Password reset routes...
Route::get('password/reset/{token}', ['as' => 'reset_password',          'uses' => 'Auth\PasswordController@getReset']);
Route::post('password/reset', ['as' => 'reset_passwordPost',      'uses' => 'Auth\PasswordController@postReset']);

// Online Application Form
Route::get('enquiry', ['as' => 'enquiry',          'uses' => 'ApplicationController@form']);
Route::post('post_enquiry', ['as' => 'post_enquiry',    'uses' => 'ApplicationController@postEnquiry']);
Route::get('enquiry_thankyou', ['as' => 'enqiry_thankyou',          'uses' => 'ApplicationController@thankyou']);

// Online Ticket Form
Route::get('ticket', ['as' => 'ticket',          'uses' => 'ApplicationController@ticket']);
Route::post('post_ticket', ['as' => 'post_ticket',    'uses' => 'ApplicationController@postTicket']);
Route::get('ticket_thankyou', ['as' => 'ticket_thankyou',          'uses' => 'ApplicationController@ticketThankyou']);

// Online Job Application Form

Route::get('getClients', ['as' => 'getclients', 'uses' => 'ClientsController@get_client']);

Route::get('list_job', ['as' => 'list_job',      'uses' => 'ApplicationController@listJob']);
Route::get('apply_job/{id}', ['as' => 'apply_job',     'uses' => 'ApplicationController@applyJob']);
Route::get('apply_form/{designation_id}/{job_circular_id}', ['as' => 'apply_form',          'uses' => 'ApplicationController@jobApplyForm']);
Route::post('post_job', ['as' => 'post_job',      'uses' => 'ApplicationController@postJobApplication']);
Route::get('job_thankyou', ['as' => 'job_thankyou',  'uses' => 'ApplicationController@jobThankyou']);

// Registration terms
Route::get('faust', ['as' => 'faust',                   'uses' => 'FaustController@index']);

// Application routes...
Route::get('/', ['as' => 'backslash',   'uses' => 'HomeController@index']);
Route::get('welcome', ['as' => 'welcome',     'uses' => 'HomeController@welcome']);

// // Clock

Route::get('clockin', ['as' => 'clockin',   'uses' => 'ShiftAttendanceController@clockin']);
Route::get('clockout', ['as' => 'clockout',   'uses' => 'ShiftAttendanceController@clockout']);

//  only authenticated users can verify the device with associated IP address.
Route::group(['middleware' => ['auth']], function () {
    Route::get('/authorize/{token}', [
        'name' => 'Authorize Login',
        'as' => 'authorize.device',
        'uses' => 'Auth\AuthorizeController@verify',
    ]);

    Route::post('/authorize/resend', [
        'name' => 'Authorize',
        'as' => 'authorize.resend',
        'uses' => 'Auth\AuthorizeController@resend',
    ]);
});
//iptracker

// Routes in this group must be authorized.
Route::group(['middleware' =>  ['iptracker' , 'authorize']], function () {
    // Application routes...
    Route::get('dashboard', ['as' => 'saldashboard',          'uses' => 'DashboardController@salesboard']);
    Route::get('hrboard', ['as' => 'hrboard',          'uses' => 'HrBoardController@hrBoard']);
    Route::get('user/profile', ['as' => 'user.profile',       'uses' => 'UsersController@profile']);
    Route::patch('user/profile', ['as' => 'user.profile.patch', 'uses' => 'UsersController@profileUpdate']);
    Route::get('home', ['as' => 'home',        'uses' => 'HomeController@index']);


    // Site administration section
    Route::group(['prefix' => 'admin'], function () {
        Route::get('attendance/adjust_adjustment/modal', ['as' => 'admin.adjust_adjustment.detail_modal',   'uses' => 'ShiftAttendanceController@attendanceAdjustModal']);
        Route::get('attendance/adjust_adjustment/list', ['as' => 'admin.adjust_adjustment.list',   'uses' => 'ShiftAttendanceController@adjustmentlist']);
        Route::post('attendance_request_calender/create', ['as' => 'admin.adjust_adjustment_calender.store',   'uses' => 'ShiftAttendanceController@attendanceRequestCalender']);
        //ticket routes

        Route::get('post_liker/{id}',['as'=>'admin.post_liker.details','uses'=>'HomeController@viewLiker']);
        // AdjustmentReason
        Route::get('adjustment-reason',['as'=>'admin.adjustment-reason.index','uses'=>'AdjustmentReasonController@index']);
        Route::get('adjustment-reason/create',['as'=>'admin.adjustment-reason.create','uses'=>'AdjustmentReasonController@create']);
        Route::post('adjustment-reason/create',['as'=>'admin.adjustment-reason.store','uses'=>'AdjustmentReasonController@store']);
        Route::get('adjustment-reason/edit/{id}',['as'=>'admin.adjustment-reason.edit','uses'=>'AdjustmentReasonController@edit']);
        Route::post('adjustment-reason/edit/{id}',['as'=>'admin.adjustment-reason.edit','uses'=>'AdjustmentReasonController@update']);
        Route::get('adjustment-reason/confirm-delete/{id}',['as'=>'admin.adjustment-reason.confirm-delete','uses'=>'AdjustmentReasonController@deleteModal']);
        Route::get('adjustment-reason/delete/{id}',['as'=>'admin.adjustment-reason.delete','uses'=>'AdjustmentReasonController@destroy']);
        Route::get('adjustment-reason/show/{id}',['as'=>'admin.adjustment-reason.show','uses'=>'AdjustmentReasonController@show']);

        Route::get('hrcalandar',['as'=>'admin.hrcalandar','uses'=>'HrCalandarController@index']);

        Route::get('attendace/{id}/calandar',['as'=>'admin.attendace.calandar','uses'=>'HrCalandarController@attendaceCalendar']);

        Route::post('news_feeds',['as'=>'admin.news_feeds.create','uses'=>'HomeController@postFeeds']);

        Route::get('newsfeeds_like_dislike/{pid}/{type}',['as'=>'admin.news_feeds.like_dislike','uses'=>'HomeController@postdislikelikes']);

        Route::post('newsfeeds_comment/{pid}',['as'=>'admin.news_feeds.newsfeeds_comment','uses'=>'HomeController@post_comments']);

        Route::get('all-news-feed-comments/{id}','HomeController@viewallcomment');

        Route::get('remove-feed-comment/{id}','HomeController@removeComment');

        Route::get('remove-news-feeds/{id}','HomeController@removenews');



        Route::get('ticket', ['as' => 'admin.ticket.index', 'uses' => 'TicketController@index']);

        Route::get('ticket/create', ['as' => 'admin.ticket.create', 'uses' => 'TicketController@create']);

        Route::post('ticket/create', ['as' => 'admin.ticket.store', 'uses' => 'TicketController@store']);

        Route::get('ticket/edit/{id}', ['as' => 'admin.ticket.edit', 'uses' => 'TicketController@edit']);

        Route::get('ticket/show/{id}', ['as' => 'admin.ticket.show', 'uses' => 'TicketController@show']);

        Route::post('ticket/edit/{id}', ['as' => 'admin.ticket.update', 'uses' => 'TicketController@update']);

        Route::get('ticket/confirmdelete/{id}', ['as' => 'admin.ticket.confirm-delete', 'uses' => 'TicketController@getModalDelete']);
        Route::get('ticket/delete/{id}', ['as' => 'admin.ticket.delete', 'uses' => 'TicketController@destroy']);

        Route::get('ticket/delete-file/{id}', ['as' => 'admin.ticket.delete-file', 'uses' => 'TicketController@destroyFile']);

        Route::post('ticket/sendResponse', ['as' => 'admin.ticket.sendResponse', 'uses' => 'TicketController@sendResponse']);

        Route::get('cust_tickets/list/{id}', ['as' => 'admin.cust_ticket.index', 'uses' => 'TicketController@cust_index']);


        //dartachalani

        Route::get('darta', ['as' => 'admin.darta.index', 'uses' => 'DartaController@index']);
        Route::get('darta/create', ['as' => 'admin.darta.create', 'uses' => 'DartaController@create']);
        Route::post('darta/create', ['as' => 'admin.darta.store', 'uses' => 'DartaController@store']);

        Route::get('darta/edit/{id}', ['as' => 'admin.darta.edit', 'uses' => 'DartaController@edit']);

        Route::post('darta/edit/{id}', ['as' => 'admin.darta.update', 'uses' => 'DartaController@update']);

        Route::get('darta/delete-file/{id}', ['as' => 'admin.darta.delete-file', 'uses' => 'DartaController@destroyFile']);

        Route::get('darta/confirmdelete/{id}', ['as' => 'admin.darta.confirm-delete', 'uses' => 'DartaController@getModalDelete']);

        Route::get('darta/delete/{id}', ['as' => 'admin.darta.delete', 'uses' => 'DartaController@destroy']);

        //chalani roputes

        Route::get('chalani', ['as' => 'admin.chalani.index', 'uses' => 'ChalaniController@index']);

        Route::get('chalani/create', ['as' => 'admin.chalani.create', 'uses' => 'ChalaniController@create']);
        Route::post('chalani/create', ['as' => 'admin.chalani.store', 'uses' => 'ChalaniController@store']);

        Route::get('chalani/edit/{id}', ['as' => 'admin.chalani.edit', 'uses' => 'ChalaniController@edit']);

        Route::post('chalani/edit/{id}', ['as' => 'admin.chalani.update', 'uses' => 'ChalaniController@update']);

        Route::get('chalani/delete-file/{id}', ['as' => 'admin.chalani.delete-file', 'uses' => 'ChalaniController@destroyFile']);

        Route::get('chalani/confirmdelete/{id}', ['as' => 'admin.chalani.confirm-delete', 'uses' => 'ChalaniController@getModalDelete']);

        Route::get('ticket/delete/{id}', ['as' => 'admin.chalani.delete', 'uses' => 'ChalaniController@destroy']);

        //shift attendance report

        Route::get('shiftAttendance', ['as' => 'admin.shiftAttendance.index', 'uses' => 'ShiftAttendanceController@filter_report']);
        Route::post('shiftAttendance', ['as' => 'admin.shiftAttendance.filter', 'uses' => 'ShiftAttendanceController@filter_reportShow']);

        Route::get('shiftAttendance/{type}/download', ['as' => 'admin.shiftAttendance.download', 'uses' => 'ShiftAttendanceController@download_report']);

        Route::get('shiftAttendance/{user_id}/{shift}/{start_date}', ['as' => 'admin.shiftAttendance.filter.user', 'uses' => 'ShiftAttendanceController@filter_reportByUser']);

        Route::post('shiftAttendanceFix/{attendance_id}', 'ShiftAttendanceController@fixattendance');

        Route::get('timeHistory', ['as' => 'admin.shiftAttendance.timeHistory', 'uses' => 'ShiftAttendanceReportController@timeHistory']);

        Route::post('timeHistory', ['as' => 'admin.shiftAttendance.timeHistoryShow', 'uses' => 'ShiftAttendanceReportController@timeHistoryShow']);

        Route::get('attendanceReports', ['as' => 'admin.shiftAttendance.attendanceReports', 'uses' => 'ShiftAttendanceReportController@attendanceReport']);

        Route::post('attendanceReports', ['as' => 'admin.shiftAttendance.attendanceReportShow', 'uses' => 'ShiftAttendanceReportController@attendanceReportShow']);

        Route::get('attendanceReports/{type}/download', ['as' => 'admin.shiftAttendance.download_report', 'uses' => 'ShiftAttendanceReportController@download_report']);

        Route::get('shift_mark_attendance', ['as' => 'admin.shiftAttendance.mark_attendance', 'uses' => 'MarkShiftAttendanceController@create']);

        Route::post('shift_mark_attendance', ['as' => 'admin.shiftAttendance.mark_attendance_store', 'uses' => 'MarkShiftAttendanceController@store']);

        Route::get('shift_mark_attendance/bulk', ['as' => 'admin.shiftAttendance.mark_attendance.bulk', 'uses' => 'MarkShiftAttendanceController@createBulk']);

        Route::post('shift_mark_attendance/bulk', ['as' => 'admin.shiftAttendance.mark_attendance.bulknext', 'uses' => 'MarkShiftAttendanceController@createBulkNext']);

        Route::post('shift_mark_attendance/bulk/save', ['as' => 'admin.shiftAttendance.mark_attendance.save', 'uses' => 'MarkShiftAttendanceController@createBulkSave']);

        Route::get('shift_mark_attendance/{empId}/view_status', 'MarkShiftAttendanceController@viewStatus');

        //user Team routes
        Route::get('teams', ['as' => 'admin.teams.index', 'uses' => 'TeamController@index']);
        Route::get('teams/create', ['as' => 'admin.teams.create', 'uses' => 'TeamController@create']);
        Route::post('teams/create', ['as' => 'admin.teams.store', 'uses' => 'TeamController@store']);
        Route::get('teams/edit/{id}', ['as' => 'admin.teams.edit', 'uses' => 'TeamController@edit']);
        Route::post('teams/edit/{id}', ['as' => 'admin.teams.update', 'uses' => 'TeamController@update']);
        Route::get('teams/confirm-delete{id}', ['as' => 'admin.teams.confirm-delete', 'uses' => 'TeamController@getModalDelete']);
        Route::get('teams/delete/{id}', ['as' => 'admin.teams.delete', 'uses' => 'TeamController@destroy']);

        Route::get('users/teams/{id}', ['as' => 'admin.users.teamsadd', 'uses' => 'TeamController@addUser']);
        Route::post('users/teams/{id}', ['as' => 'admin.users.teams', 'uses' => 'TeamController@postUser']);

        Route::get('users/teams/confirm-delete/{id}', ['as' => 'admin.users.teams.confirm-delete', 'uses' => 'TeamController@removeTeamMemberModal']);
        Route::get('users/teams/destroy/{id}', ['as' => 'admin.users.teams.delete', 'uses' => 'TeamController@removeTeamMember']);

        //Requestmanagement Routes

        Route::get('employeeRequest', ['as' => 'admin.employeeRequest.index', 'uses' => 'RequestManagementController@index']);
        Route::get('employeeRequest/create', ['as' => 'admin.employeeRequest.create', 'uses' => 'RequestManagementController@create']);
        Route::post('employeeRequest/create', ['as' => 'admin.employeeRequest.store', 'uses' => 'RequestManagementController@store']);

        Route::get('employeeRequest/edit/{id}', [
            'as' => 'admin.employeeRequest.edit',
            'uses' => 'RequestManagementController@edit',
        ]);
        Route::post('employeeRequest/edit/{id}', [
            'as' => 'admin.employeeRequest.update',
            'uses' => 'RequestManagementController@update',
        ]);

        Route::get('employeeRequest/confirm-delete{id}', ['as' => 'admin.employeeRequest.confirm-delete', 'uses' => 'RequestManagementController@getModalDelete']);
        Route::get('employeeRequest/delete/{id}', ['as' => 'admin.employeeRequest.delete', 'uses' => 'RequestManagementController@destroy']);

        Route::get('employeeRequest/accept_reject/{id}', ['as' => 'admin.employeeRequest.accept_reject', 'uses' => 'RequestManagementController@accept_reject']);
        Route::post('employeeRequest/accept_reject/{id}', ['as' => 'admin.employeeRequest.accept_rejectpost', 'uses' => 'RequestManagementController@accept_rejectPost']);

        // City Master
        Route::get('cities',['as'=>'admin.city.index','uses'=>'CitymasterController@index']);
        Route::get('city/create',['as'=>'admin.city.create','uses'=>'CitymasterController@create']);
        Route::post('city/create',['as'=>'admin.city.store','uses'=>'CitymasterController@store']);
        Route::get('city/edit/{id}',['as'=>'admin.city.edit','uses'=>'CitymasterController@edit']);
        Route::post('city/edit/{id}',['as'=>'admin.city.edit','uses'=>'CitymasterController@update']);
        Route::get('city/confirm-delete/{id}',['as'=>'admin.city.confirm-delete','uses'=>'CitymasterController@deleteModal']);
        Route::get('city/delete/{id}',['as'=>'admin.city.delete','uses'=>'CitymasterController@destroy']);
        Route::get('city/show/{id}',['as'=>'admin.city.show','uses'=>'CitymasterController@show']);


         //promotion and barcode

        Route::get('product/promotion/{id}/create', ['as' => 'admin.product.promotion.create', 'uses' => 'ProductController@promotionCreate']);
        Route::get('products/barcode/{id}/create', ['as' => 'admin.product.barcode.create', 'uses' => 'ProductController@barcodeCreate']);

        Route::post('products/barcode/{id}/post', ['as' => 'admin.product.barcode.post', 'uses' => 'ProductController@barcodePost']);

        Route::get('/products/barcode/getprintproduct', ['as' => 'admin.product.getproduct.ajax', 'uses' => 'ProductController@getPrintProduct']);
         Route::get('product/int-purch/{id}', ['as' => 'admin.products.int_purch', 'uses' => 'ProductController@int_purch']);
        Route::put('product/int-purchUpdate/{id}', ['as' => 'admin.products.int_purch_update', 'uses' => 'ProductController@int_purch_update']);



 // Product Type Master

        Route::post('producttypemaster/enableSelected',          ['as' => 'admin.producttypemaster.enable-selected',  'uses' => 'ProductTypeMasterController@enableSelected']);
        Route::post('producttypemaster/disableSelected',         ['as' => 'admin.producttypemaster.disable-selected', 'uses' => 'ProductTypeMasterController@disableSelected']);
        Route::get('producttypemaster/search',                  ['as' => 'admin.producttypemaster.search',           'uses' => 'ProductTypeMasterController@searchByName']);
        Route::post('producttypemaster/getInfo',                 ['as' => 'admin.producttypemaster.get-info',         'uses' => 'ProductTypeMasterController@getInfo']);
        Route::post('producttypemaster',                         ['as' => 'admin.producttypemaster.store',            'uses' => 'ProductTypeMasterController@store']);
        Route::get('producttypemaster',                         ['as' => 'admin.producttypemaster.index',            'uses' => 'ProductTypeMasterController@index']);
        Route::get('producttypemaster/create',                  ['as' => 'admin.producttypemaster.create',           'uses' => 'ProductTypeMasterController@create']);
        Route::get('producttypemaster/{Id}',                ['as' => 'admin.producttypemaster.show',             'uses' => 'ProductTypeMasterController@show']);
        Route::patch('producttypemaster/{Id}',                ['as' => 'admin.producttypemaster.patch',            'uses' => 'ProductTypeMasterController@update']);
        Route::put('producttypemaster/{Id}',                ['as' => 'admin.producttypemaster.update',           'uses' => 'ProductTypeMasterController@update']);
        Route::delete('producttypemaster/{Id}',                ['as' => 'admin.producttypemaster.destroy',          'uses' => 'ProductTypeMasterController@destroy']);
        Route::get('producttypemaster/{Id}/edit',           ['as' => 'admin.producttypemaster.edit',             'uses' => 'ProductTypeMasterController@edit']);
        Route::get('producttypemaster/{Id}/confirm-delete', ['as' => 'admin.producttypemaster.confirm-delete',   'uses' => 'ProductTypeMasterController@getModalDelete']);
        Route::get('producttypemaster/{Id}/delete',         ['as' => 'admin.producttypemaster.delete',           'uses' => 'ProductTypeMasterController@destroy']);
        Route::get('producttypemaster/{Id}/enable',         ['as' => 'admin.producttypemaster.enable',           'uses' => 'ProductTypeMasterController@enable']);
        Route::get('producttypemaster/{Id}/disable',        ['as' => 'admin.producttypemaster.disable',          'uses' => 'ProductTypeMasterController@disable']);
        //production routes
        Route::get('production/product-unit-index', ['as' => 'admin.production.product-unit-index', 'uses' => 'ProductUnitController@productUnitIndex']);
        Route::get('production/product-unit', ['as' => 'admin.production.product-unit', 'uses' => 'ProductUnitController@productUnit']);
        Route::post('production/product-unit', ['as' => 'admin.production.products-unit', 'uses' => 'ProductUnitController@storeProductUnit']);
        Route::get('production/edit-produnit/{id}', ['as' => 'admin.production.edit-produnit', 'uses' => 'ProductUnitController@editproductUnit']);
        Route::post('production/edit-produnit/{id}', ['as' => 'admin.production.updateprodUnit', 'uses' => 'ProductUnitController@updateprodUnit']);
        Route::get('production/confirm-delete-produnit/{id}', ['as' => 'admin.production.confirm-delete-produnit', 'uses' => 'ProductUnitController@deleteproductUnitModal']);
        Route::get('production/delete-produnit/{id}', ['as' => 'admin.productunit.delete-produnit', 'uses' => 'ProductUnitController@deleteproductUnit']);

        Route::get('product-location/index', ['as' => 'admin.product-location.index', 'uses' => 'ProductsLocationController@index']);
        Route::get('product-location/create', ['as' => 'admin.product-location.create', 'uses' => 'ProductsLocationController@create']);
        Route::post('product-location/create', ['as' => 'admin.product-location.store', 'uses' => 'ProductsLocationController@store']);
        Route::get('product-location/show/{id}', ['as' => 'admin.product-location.show', 'uses' => 'ProductsLocationController@show']);
        Route::get('product-location/edit/{id}', ['as' => 'admin.product-location.edit', 'uses' => 'ProductsLocationController@edit']);
        Route::post('product-location/edit/{id}', ['as' => 'admin.product-location.update', 'uses' => 'ProductsLocationController@update']);
        Route::get('product-location/delete-confirm/{id}', ['as' => 'admin.product-location.delete-confirm', 'uses' => 'ProductsLocationController@deleteModal']);
        Route::get('product-location/delete/{id}', ['as' => 'admin.product-location.delete', 'uses' => 'ProductsLocationController@destroy']);

        //performace route
        Route::get('performance/indicator', ['as' => 'admin.performance.indicator', 'uses' => 'PerformanceController@index']);
        Route::get('performance/create-performance-indicator', ['as' => 'admin.performance.create-performance-indicator', 'uses' => 'PerformanceController@createIndicator']);
        Route::post('performance/performance-indicator', ['as' => 'admin.performance.performance-indicator', 'uses' => 'PerformanceController@store']);
        Route::get('performance/show-performance-indicator/{id}', ['as' => 'admin.performance.show-performance-indicator', 'uses' => 'PerformanceController@showIndicator']);
        Route::get('performance/edit-performance-indicator/{id}', ['as' => 'admin.performance.edit-performance-indicator', 'uses' => 'PerformanceController@editIndicator']);
        Route::post('performance/edit-performance-indicator/{id}', ['as' => 'admin.performance.update-performance-indicator', 'uses' => 'PerformanceController@updateIndicator']);
        Route::get('performance/confirm-delete-indicator/{id}', ['as' => 'admin.performance.confirm-delete-indicator', 'uses' => 'PerformanceController@getIndicatorDelete']);
        Route::get('performance/delete-indicator/{id}', ['as' => 'admin.performance.delete-indicator', 'uses' => 'PerformanceController@deleteIndicator']);

        Route::get('performance/appraisal', ['as' => 'admin.performance.appraisal', 'uses' => 'PerformanceController@appraisalIndex']);
        Route::get('performance/giveappraisal', ['as' => 'admin.performance.giveappraisal', 'uses' => 'PerformanceController@userAppraisal']);
        Route::post('performance/create-appeaisal', ['as' => 'admin.performance.create-appeaisal', 'uses' => 'PerformanceController@userAppraisalCreate']);
        Route::get('performance/show-appeaisal/{id}', ['as' => 'admin.performance.show-appeaisal', 'uses' => 'PerformanceController@showAppraisal']);
        Route::get('performance/edit-appeaisal/{id}', ['as' => 'admin.performance.edit-appeaisal', 'uses' => 'PerformanceController@editAppraisal']);
        Route::post('performance/edit-appeaisal/{id}', ['as' => 'admin.performance.update-appeaisal', 'uses' => 'PerformanceController@updateAppeasial']);
        Route::get('performance/confirm-delete-appeaisal/{id}', ['as' => 'admin.performance.confirm-delete-appeaisal', 'uses' => 'PerformanceController@getappeaisalDelete']);
        Route::get('performance/delete-appeaisal/{id}', ['as' => 'admin.performance.delete-appeaisal', 'uses' => 'PerformanceController@deleteAppeaisal']);

        Route::get('performance/report', ['as' => 'admin.performance.reportindex', 'uses' => 'PerformanceController@ReportIndex']);

        Route::get('performance/report1/{id}', ['as' => 'admin.performance.report', 'uses' => 'PerformanceController@Report']);
        //performance route ends here

        //event controller

        Route::get('events', 'EventController@index');
        Route::get('addevent', ['as' => 'addevent', 'uses' => 'EventController@create']);
        Route::post('addevent', ['as' => 'addpostevent', 'uses' => 'EventController@store']);
        Route::get('editevent/{id}', ['as' => 'editevent', 'uses' => 'EventController@edit']);
        Route::post('editevent/{id}', ['as' => 'editpostevent', 'uses' => 'EventController@update']);
        Route::get('confirm-delete/{id}', ['as' => 'confirm-delete', 'uses' => 'EventController@getModalDelete']);
        Route::get('delete-event/{id}', ['as' => 'delete-event', 'uses' => 'EventController@destroy']);
        Route::get('event-venues', 'EventController@showVenue');
        Route::get('add-venue', ['as' => 'add-venue', 'uses' => 'EventController@createVenues']);
        Route::post('add-venue', ['as' => 'post-venue', 'uses' => 'EventController@storeVenues']);
        Route::get('edit-venue/{id}', ['as' => 'edit-venue', 'uses' => 'EventController@editVenues']);
        Route::post('edit-venue/{id}', ['as' => 'edpost-venue', 'uses' => 'EventController@updateVenues']);
        Route::get('confirm-delete-venue/{id}', ['as' => 'confirm-delete-venue', 'uses' => 'EventController@getVenueDelete']);
        Route::get('delete-venue/{id}', ['as' => 'delete-venue', 'uses' => 'EventController@destroyVenue']);
        Route::get('event-space', 'EventController@showSpace');
        Route::get('add-event-space', ['as' => 'add-event-space', 'uses' => 'EventController@createSpace']);
        Route::post('add-event-space', ['as' => 'addpost-event-space', 'uses' => 'EventController@storeSpace']);
        Route::get('edit-event-space/{id}', ['as' => 'edit-event-space', 'uses' => 'EventController@editSpace']);
        Route::post('edit-event-space/{id}', ['as' => 'update-event-space', 'uses' => 'EventController@updateSpace']);
        Route::get('confirm-delete-space/{id}', ['as' => 'confirm-delete-space', 'uses' => 'EventController@getspaceDelete']);
        Route::get('delete-space/{id}', ['as' => 'delete-space', 'uses' => 'EventController@destroySpace']);


        Route::get('users/assign_empid/{id}',['as'=>'admin.assign_empid','uses'=>'UsersController@assignid']);

        Route::post('users/assign_empid/{id}',['as'=>'admin.store_assign_empid','uses'=>'UsersController@store_emp_id']);
        // For Activity Time Sheet

        Route::get('activity', ['as' => 'admin.activity.index', 'uses' => 'TimeSheetController@activityIndex']);
        Route::get('activity/create', ['as' => 'admin.activity.create', 'uses' => 'TimeSheetController@activityCreate']);
        Route::post('activity', ['as' => 'admin.activity.save', 'uses' => 'TimeSheetController@activitySave']);
        Route::get('activity/{id}/edit', ['as' => 'admin.activity.edit', 'uses' => 'TimeSheetController@activityEdit']);
        Route::post('activity/{id}', ['as' => 'admin.activity.update', 'uses' => 'TimeSheetController@activityUpdate']);
        Route::get('activity/{id}/confirm-delete', ['as' => 'admin.activity.confirm-delete',   'uses' => 'TimeSheetController@getModalDeleteActivity']);
        Route::get('activity/{id}/delete', ['as' => 'admin.activity.delete',           'uses' => 'TimeSheetController@destroyActivity']);

        // For Time Sheet

        Route::get('timesheet', ['as' => 'admin.timesheet.index', 'uses' => 'TimeSheetController@timesheetIndex']);
        Route::get('timesheet/create', ['as' => 'admin.timesheet.create', 'uses' => 'TimeSheetController@timesheetCreate']);
        Route::post('timesheet', ['as' => 'admin.timesheet.save', 'uses' => 'TimeSheetController@timesheetSave']);
        Route::get('timesheet/{id}/edit', ['as' => 'admin.timesheet.edit', 'uses' => 'TimeSheetController@timesheetEdit']);
        Route::post('timesheet/{id}', ['as' => 'admin.timesheet.update', 'uses' => 'TimeSheetController@timesheetUpdate']);
        Route::get('timesheet/{id}/confirm-delete', ['as' => 'admin.timesheet.confirm-delete',   'uses' => 'TimeSheetController@getModalDeleteTimeSheet']);
        Route::get('timesheet/{id}/delete', ['as' => 'admin.timesheet.delete',           'uses' => 'TimeSheetController@destroyTimeSheet']);

        Route::get('bulkadd/timesheet', ['as' => 'admin.bulkadd.timesheet', 'uses' => 'TimeSheetController@bulkindex']);
        Route::post('bulkadd/timesheet/create', ['as' => 'admin.bulkadd.timesheet.create', 'uses' => 'TimeSheetController@bulkcreate']);
        Route::post('bulkadd/timesheet/save', ['as' => 'admin.bulkadd.timesheet.save', 'uses' => 'TimeSheetController@bulkstore']);

        Route::get('timesheet/{emp_id}/show', ['as' => 'admin.timesheet.show', 'uses' => 'TimeSheetController@timesheetShow']);

        Route::get('timesheet/attendancereport/view', ['as' => 'admin.timesheet.attendancereport', 'uses' => 'TimeSheetController@attendanceReport']);

        Route::post('timesheet/attendancereport/view', ['as' => 'admin.timesheet.postattendancereport', 'uses' => 'TimeSheetController@attendanceReportDetails']);

        //timesheetsalary
        Route::get('timesheetsalary', ['as' => 'admin.timesheetsalary.index', 'uses' => 'TimeSheetSalaryController@index']);

        Route::get('timesheetsalary/create', ['as' => 'admin.timesheetsalary.create', 'uses' => 'TimeSheetSalaryController@create']);
        Route::post('timesheetsalary/create', ['as' => 'admin.timesheetsalary.store', 'uses' => 'TimeSheetSalaryController@store']);
        Route::get('timesheetsalary/enable/{id}', ['as' => 'admin.timesheetsalary.enable', 'uses' => 'TimeSheetSalaryController@enabledisable']);
        Route::get('timesheetsalary/edit/{id}', ['as' => 'admin.timesheetsalary.edit', 'uses' => 'TimeSheetSalaryController@edit']);
        Route::post('timesheetsalary/update/{id}', ['as' => 'admin.timesheetsalary.update', 'uses' => 'TimeSheetSalaryController@update']);

        Route::get('timesheetsalary/delete/{id}', ['as' => 'admin.timesheetsalary.destroy', 'uses' => 'TimeSheetSalaryController@destroy']);
        Route::get('assign_timesheet_salary/', ['as' => 'admin.assign_timesheet_salary.show', 'uses' => 'TimeSheetSalaryController@managesalary']);
        Route::post('assign_timesheet_salary/', ['as' => 'admin.assign_timesheet_salary.assign', 'uses' => 'TimeSheetSalaryController@managesalary_details']);
        Route::post('assign_timesheet_salary/store', ['as' => 'admin.assign_timesheet_salary.store', 'uses' => 'TimeSheetSalaryController@managesalary_details_post']);

        // For Cost Center

        Route::get('costcenter', ['as' => 'admin.costcenter.index', 'uses' => 'TimeSheetController@costcenterIndex']);
        Route::get('costcenter/create', ['as' => 'admin.costcenter.create', 'uses' => 'TimeSheetController@costcenterCreate']);
        Route::post('costcenter', ['as' => 'admin.costcenter.save', 'uses' => 'TimeSheetController@costcenterSave']);
        Route::get('costcenter/{id}/edit', ['as' => 'admin.costcenter.edit', 'uses' => 'TimeSheetController@costcenterEdit']);
        Route::post('costcenter/{id}', ['as' => 'admin.costcenter.update', 'uses' => 'TimeSheetController@costcenterUpdate']);
        Route::get('costcenter/{id}/confirm-delete', ['as' => 'admin.costcenter.confirm-delete',   'uses' => 'TimeSheetController@getModalDeleteCostCenter']);
        Route::get('costcenter/{id}/delete', ['as' => 'admin.costcenter.delete',           'uses' => 'TimeSheetController@destroyCostCenter']);

        Route::get('cash_in_out',['as'=>'admin.cash_in_out','uses'=>'CashInOutController@cash']);
        Route::get('daybook', ['as' => 'admin.daybook', 'uses' => 'CashInOutController@daybook']);
        Route::get('gl', ['as' => 'admin.gl', 'uses' => 'CashInOutController@gl']);

        //posoutlet
        Route::get('pos-outlets/index', ['as' => 'admin.pos-outlets.index', 'uses' => 'PosOutletsController@index']);
        Route::get('pos-outlets/create', ['as' => 'admin.pos-outlets.create', 'uses' => 'PosOutletsController@create']);
        Route::post('pos-outlets', ['as' => 'admin.hotel.pos-outlets.store', 'uses' => 'PosOutletsController@store']);
        Route::get('pos-outlets/{id}/edit', ['as' => 'admin.pos-outlets.edit', 'uses' => 'PosOutletsController@edit']);
        Route::post('pos-outlets/{id}/update', ['as' => 'admin.pos-outlets.update', 'uses' => 'PosOutletsController@update']);
        Route::get('pos-outlets/{id}', ['as' => 'admin.hotel.pos-outlets.confirm-delete', 'uses' => 'PosOutletsController@getModalDelete']);
        Route::get('pos-outlets-delete/{id}', ['as' => 'admin.pos-outlets.delete', 'uses' => 'PosOutletsController@destroy']);

        /// add user

        Route::get('/pos-outlets/{id}/adduser', ['as' => 'admin.hotel.pos-outlets.adduser', 'uses' => 'PosOutletsController@addUser']);
        Route::post('/pos-outlets/{id}/adduser', ['as' => 'admin.hotel.pos-outlets.postuser', 'uses' => 'PosOutletsController@postUser']);

        Route::get('pos-outlets/{id}/confirm-delete/adduser', ['as' => 'admin.hotel.pos-outlets.confirm-delete.adduser', 'uses' => 'PosOutletsController@getModalDeleteUser']);
        Route::get('pos-outlet/{id}/delete/adduser', ['as' => 'admin.pos-outlets.adduser.delete', 'uses' => 'PosOutletsController@destroyUser']);


     //BiometricController
        Route::get('biometric', 'BiometricController@index');
        Route::get('biometricmachine', 'BiometricController@machineList');
        Route::get('/biometricattendence', 'BiometricController@showAttendence');
        Route::get('/ImportEmployee', ['as' => 'ImportEmployee', 'uses' => 'BiometricController@ImportEmployee']);
        Route::get('/importdevice', ['as' => 'importdevice', 'uses' => 'BiometricController@ImportDevice']);

        Route::get('/editdevice/{id}', ['as' => 'editdevice', 'uses' => 'BiometricController@editDevice']);
        Route::post('/editdevice/{id}', ['as' => 'updatedevice', 'uses' => 'BiometricController@updateDevice']);

        Route::get('/device/confirm-delete/{id}', ['as' => 'device.confirm-delete', 'uses' => 'BiometricController@getModalDelete']);


        Route::post('/ImportDevice1', ['as' => 'ImportDevice1', 'uses' => 'BiometricController@ImportDevice1']);
        Route::get('/ImportAttendence', ['as' => 'ImportAttendence', 'uses' => 'BiometricController@ImportAttendence']);
        Route::get('/ActivateDevice', ['as' => 'ActivateDevice', 'uses' => 'BiometricController@ActivateDevice']);
        Route::get('/showAllAttendence', ['as' => 'showAllAttendence', 'uses' => 'BiometricController@showAllAttendence']);
        Route::get('/biometrictimehistory', ['as' => 'biometrictimehistory', 'uses' => 'BiometricController@TimeHistory']);
        Route::get('attendancereport', ['as' => 'attendancereportindex', 'uses' => 'BiometricController@AttendanceReportIndex']);
        Route::post('attendancereport', ['as' => 'attendancereport', 'uses' => 'BiometricController@AttendanceReport']);


        Route::get('/biometric/adduser', ['as' => 'adduserAttendence', 'uses' => 'BiometricController@addUser']);

        Route::post('/biometric/adduser', ['as' => 'biometric.add.user', 'uses' => 'BiometricController@storeUser']);

        //salesboard
        Route::get('salesboard', ['as' => 'dashboard', 'uses' => 'SalesBoardController@index']);

        // User routes
        Route::post('users/enableSelected', ['as' => 'admin.users.enable-selected',  'uses' => 'UsersController@enableSelected']);
        Route::post('users/disableSelected', ['as' => 'admin.users.disable-selected', 'uses' => 'UsersController@disableSelected']);
        Route::get('users/search', ['as' => 'admin.users.search',           'uses' => 'UsersController@searchByName']);
        Route::get('users/list', ['as' => 'admin.users.list',             'uses' => 'UsersController@listByPage']);
        Route::post('users/getInfo', ['as' => 'admin.users.get-info',         'uses' => 'UsersController@getInfo']);
        Route::post('users', ['as' => 'admin.users.store',            'uses' => 'UsersController@store']);
        Route::get('users', ['as' => 'admin.users.index',            'uses' => 'UsersController@index']);
        Route::get('users/create', ['as' => 'admin.users.create',           'uses' => 'UsersController@create']);
        Route::get('users/{userId}', ['as' => 'admin.users.show',             'uses' => 'UsersController@show']);
        Route::patch('users/{userId}', ['as' => 'admin.users.patch',            'uses' => 'UsersController@update']);
        Route::put('users/{userId}', ['as' => 'admin.users.update',           'uses' => 'UsersController@update']);
        Route::delete('users/{userId}', ['as' => 'admin.users.destroy',          'uses' => 'UsersController@destroy']);
        Route::get('users/{userId}/edit', ['as' => 'admin.users.edit',             'uses' => 'UsersController@edit']);
        Route::get('users/{userId}/confirm-delete', ['as' => 'admin.users.confirm-delete',   'uses' => 'UsersController@getModalDelete']);
        Route::get('users/{userId}/delete', ['as' => 'admin.users.delete',           'uses' => 'UsersController@destroy']);
        Route::get('users/{userId}/enable', ['as' => 'admin.users.enable',           'uses' => 'UsersController@enable']);
        Route::get('users/{userId}/disable', ['as' => 'admin.users.disable',          'uses' => 'UsersController@disable']);
        Route::get('users/{userId}/replayEdit', ['as' => 'admin.users.replay-edit',      'uses' => 'UsersController@replayEdit']);

        Route::get('users/ajax/GetDesignation', ['as' => 'admin.users.ajaxGetDesignation',      'uses' => 'UsersController@ajaxGetDesignation']);

        Route::post('users/ajax_user_update', ['as' => 'admin.users.ajax_user_update',      'uses' => 'UsersController@ajaxUserUpdate']);

        Route::get('usersbydep/{depid}', 'UsersController@getuserByDep');
        //user import export
        Route::get('downloadExcelusers', ['as' => 'admin.users.importexport.index', 'uses' => 'ExcelController@userindex']);

        Route::get('user_reports_gni', ['as' => 'admin.users.report.gni', 'uses' => 'ExcelController@gniUsers']);

        Route::post('downloadExcelusers', ['as' => 'admin.users.importexport.store', 'uses' => 'ExcelController@importusers']);
        Route::get('downloadExcelusers/{type}', ['as' => 'admin.users.importexport.export', 'uses' => 'ExcelController@exportusers']);

        //User Directory
        Route::get('employee/directory', ['as' => 'admin.users.user-directory',            'uses' => 'UsersController@directory']);

        // User Details Routes

        Route::get('users/{id}/detail', ['as' => 'admin.users.detail.create',      'uses' => 'UserDetailController@create']);
        Route::post('users/{id}/detail/store', ['as' => 'admin.user.detail.store',      'uses' => 'UserDetailController@store']);
        Route::get('users/{id}/detail/{detail_id}/edit', ['as' => 'admin.user.detail.edit',      'uses' => 'UserDetailController@edit']);

        Route::post('users/{id}/detail/{detail_id}/update', ['as' => 'admin.user.detail.update',      'uses' => 'UserDetailController@update']);

        Route::post('usersdocument/{id}/detail/{detail_id}/update', ['as' => 'admin.user.document.update',      'uses' => 'UserDetailController@userdocument']);

        Route::get('userdocument/confirm-delete-file/{id}', ['as' => 'admin.userdocument.confirm-delete-file', 'uses' => 'UserDetailController@confirmDeleteFile']);

        Route::get('userdocument/delete-file/{id}', ['as' => 'admin.userdocument.delete-file', 'uses' => 'UserDetailController@DeleteFile']);

        Route::get('userdetail/{id}/pdf', ['as' => 'admin.userdetail.pdf', 'uses' => 'UserDetailController@pdf']);

          //Requisition

         Route::get('requisition', ['as' => 'admin.requisition.index',            'uses' => 'RequisitionController@index']);
        Route::get('requisition/create', ['as' => 'admin.requisition.create',            'uses' => 'RequisitionController@create']);
        Route::post('requisition/', ['as' => 'admin.requisition.store',            'uses' => 'RequisitionController@store']);
        Route::get('requisition/{Id}', ['as' => 'admin.requisition.show',             'uses' => 'RequisitionController@show']);
        Route::get('requisition/approve/{Id}', ['as' => 'admin.requisition.approve',             'uses' => 'RequisitionController@approve']);
        Route::get('requisition/{Id}/edit', ['as' => 'admin.requisition.edit',             'uses' => 'RequisitionController@edit']);
        Route::post('requisition/{Id}', ['as' => 'admin.requisition.update',             'uses' => 'RequisitionController@update']);
        Route::get('requisition/{id}/confirm-delete', ['as' => 'admin.requisition.confirm-delete',   'uses' => 'RequisitionController@getModalDelete']);
        Route::get('requisition/{id}/delete', ['as' => 'admin.requisition.delete',           'uses' => 'RequisitionController@destroy']);
        Route::get('getStockUnit', ['as' => 'admin.getStockUnit',            'uses' => 'RequisitionController@getStockUnit']);



  //Goods Receipt Note
        Route::get('grn', ['as' => 'admin.grn.index', 'uses' => 'GrnController@index']);
        Route::get('grn/create', ['as' => 'admin.grn.create', 'uses' => 'GrnController@create']);
        Route::post('grn', ['as' => 'admin.grn.store', 'uses' => 'GrnController@store']);
        Route::get('grn/{id}', ['as' => 'admin.grn.show', 'uses' => 'GrnController@show']);

        Route::get('grn/{id}/edit', ['as' => 'admin.grn.edit',    'uses' => 'GrnController@edit']);

        Route::post('grn/{id}', ['as' => 'admin.grn.update',    'uses' => 'GrnController@update']);

        Route::get('grn/{id}/confirm-delete', ['as' => 'admin.grn.confirm-delete',   'uses' => 'GrnController@getModalDelete']);
        Route::get('grn/{id}/delete', ['as' => 'admin.grn.delete',           'uses' => 'GrnController@destroy']);

        Route::get('grn/post/{id}', ['as' => 'admin.grn.post',  'uses' => 'GrnController@post']);
        Route::get('grn/print/{id}', ['as' => 'admin.grn.print',  'uses' => 'GrnController@print']);
        Route::get('grn/pdf/{id}', ['as' => 'admin.grn.generatePDF',   'uses' => 'GrnController@pdf']);

        // Role routes
        Route::post('roles/enableSelected', ['as' => 'admin.roles.enable-selected',  'uses' => 'RolesController@enableSelected']);
        Route::post('roles/disableSelected', ['as' => 'admin.roles.disable-selected', 'uses' => 'RolesController@disableSelected']);
        Route::get('roles/search', ['as' => 'admin.roles.search',           'uses' => 'RolesController@searchByName']);
        Route::post('roles/getInfo', ['as' => 'admin.roles.get-info',         'uses' => 'RolesController@getInfo']);
        Route::post('roles', ['as' => 'admin.roles.store',            'uses' => 'RolesController@store']);
        Route::get('roles', ['as' => 'admin.roles.index',            'uses' => 'RolesController@index']);
        Route::get('roles/create', ['as' => 'admin.roles.create',           'uses' => 'RolesController@create']);
        Route::get('roles/{roleId}', ['as' => 'admin.roles.show',             'uses' => 'RolesController@show']);
        Route::patch('roles/{roleId}', ['as' => 'admin.roles.patch',            'uses' => 'RolesController@update']);
        Route::put('roles/{roleId}', ['as' => 'admin.roles.update',           'uses' => 'RolesController@update']);
        Route::delete('roles/{roleId}', ['as' => 'admin.roles.destroy',          'uses' => 'RolesController@destroy']);
        Route::get('roles/{roleId}/edit', ['as' => 'admin.roles.edit',             'uses' => 'RolesController@edit']);
        Route::get('roles/{roleId}/confirm-delete', ['as' => 'admin.roles.confirm-delete',   'uses' => 'RolesController@getModalDelete']);
        Route::get('roles/{roleId}/delete', ['as' => 'admin.roles.delete',           'uses' => 'RolesController@destroy']);
        Route::get('roles/{roleId}/enable', ['as' => 'admin.roles.enable',           'uses' => 'RolesController@enable']);
        Route::get('roles/{roleId}/disable', ['as' => 'admin.roles.disable',          'uses' => 'RolesController@disable']);
        // Menu routes
        Route::post('menus', ['as' => 'admin.menus.save',             'uses' => 'MenusController@save']);
        Route::get('menus', ['as' => 'admin.menus.index',            'uses' => 'MenusController@index']);
        Route::get('menus/getData/{menuId}', ['as' => 'admin.menus.get-data',         'uses' => 'MenusController@getData']);
        Route::get('menus/{menuId}/confirm-delete', ['as' => 'admin.menus.confirm-delete',   'uses' => 'MenusController@getModalDelete']);
        Route::get('menus/{id}/delete', ['as' => 'admin.menus.delete',           'uses' => 'MenusController@destroy']);
        // Modules routes
        Route::get('modules', ['as' => 'admin.modules.index',                'uses' => 'ModulesController@index']);
        Route::get('modules/{slug}/initialize', ['as' => 'admin.modules.initialize',           'uses' => 'ModulesController@initialize']);
        Route::get('modules/{slug}/confirm-uninitialize', ['as' => 'admin.modules.confirm-uninitialize', 'uses' => 'ModulesController@getModalUninitialize']);
        Route::get('modules/{slug}/uninitialize', ['as' => 'admin.modules.uninitialize',         'uses' => 'ModulesController@uninitialize']);
        Route::get('modules/{slug}/enable', ['as' => 'admin.modules.enable',               'uses' => 'ModulesController@enable']);
        Route::get('modules/{slug}/disable', ['as' => 'admin.modules.disable',              'uses' => 'ModulesController@disable']);
        Route::post('modules/enableSelected', ['as' => 'admin.modules.enable-selected',      'uses' => 'ModulesController@enableSelected']);
        Route::post('modules/disableSelected', ['as' => 'admin.modules.disable-selected',     'uses' => 'ModulesController@disableSelected']);
        Route::get('modules/optimize', ['as' => 'admin.modules.optimize',             'uses' => 'ModulesController@optimize']);
        // Permission routes
        Route::get('permissions/generate', ['as' => 'admin.permissions.generate',         'uses' => 'PermissionsController@generate']);
        Route::post('permissions/enableSelected', ['as' => 'admin.permissions.enable-selected',  'uses' => 'PermissionsController@enableSelected']);
        Route::post('permissions/disableSelected', ['as' => 'admin.permissions.disable-selected', 'uses' => 'PermissionsController@disableSelected']);
        Route::post('permissions', ['as' => 'admin.permissions.store',            'uses' => 'PermissionsController@store']);
        Route::get('permissions', ['as' => 'admin.permissions.index',            'uses' => 'PermissionsController@index']);
        Route::get('permissions/create', ['as' => 'admin.permissions.create',           'uses' => 'PermissionsController@create']);
        Route::get('permissions/{permissionId}', ['as' => 'admin.permissions.show',             'uses' => 'PermissionsController@show']);
        Route::patch('permissions/{permissionId}', ['as' => 'admin.permissions.patch',            'uses' => 'PermissionsController@update']);
        Route::put('permissions/{permissionId}', ['as' => 'admin.permissions.update',           'uses' => 'PermissionsController@update']);
        Route::delete('permissions/{permissionId}', ['as' => 'admin.permissions.destroy',          'uses' => 'PermissionsController@destroy']);
        Route::get('permissions/{permissionId}/edit', ['as' => 'admin.permissions.edit',             'uses' => 'PermissionsController@edit']);
        Route::get('permissions/{permissionId}/confirm-delete', ['as' => 'admin.permissions.confirm-delete',   'uses' => 'PermissionsController@getModalDelete']);
        Route::get('permissions/{permissionId}/delete', ['as' => 'admin.permissions.delete',           'uses' => 'PermissionsController@destroy']);
        Route::get('permissions/{permissionId}/enable', ['as' => 'admin.permissions.enable',           'uses' => 'PermissionsController@enable']);
        Route::get('permissions/{permissionId}/disable', ['as' => 'admin.permissions.disable',          'uses' => 'PermissionsController@disable']);
        // Route routes
        Route::get('routes/load', ['as' => 'admin.routes.load',             'uses' => 'RoutesController@load']);
        Route::post('routes/enableSelected', ['as' => 'admin.routes.enable-selected',  'uses' => 'RoutesController@enableSelected']);
        Route::post('routes/disableSelected', ['as' => 'admin.routes.disable-selected', 'uses' => 'RoutesController@disableSelected']);
        Route::post('routes/savePerms', ['as' => 'admin.routes.save-perms',       'uses' => 'RoutesController@savePerms']);
        Route::get('routes/search', ['as' => 'admin.routes.search',           'uses' => 'RoutesController@searchByName']);
        Route::post('routes/getInfo', ['as' => 'admin.routes.get-info',         'uses' => 'RoutesController@getInfo']);
        Route::post('routes', ['as' => 'admin.routes.store',            'uses' => 'RoutesController@store']);
        Route::get('routes', ['as' => 'admin.routes.index',            'uses' => 'RoutesController@index']);
        Route::get('routes/create', ['as' => 'admin.routes.create',           'uses' => 'RoutesController@create']);
        Route::get('routes/{routeId}', ['as' => 'admin.routes.show',             'uses' => 'RoutesController@show']);
        Route::patch('routes/{routeId}', ['as' => 'admin.routes.patch',            'uses' => 'RoutesController@update']);
        Route::put('routes/{routeId}', ['as' => 'admin.routes.update',           'uses' => 'RoutesController@update']);
        Route::delete('routes/{routeId}', ['as' => 'admin.routes.destroy',          'uses' => 'RoutesController@destroy']);
        Route::get('routes/{routeId}/edit', ['as' => 'admin.routes.edit',             'uses' => 'RoutesController@edit']);
        Route::get('routes/{routeId}/confirm-delete', ['as' => 'admin.routes.confirm-delete',   'uses' => 'RoutesController@getModalDelete']);
        Route::get('routes/{routeId}/delete', ['as' => 'admin.routes.delete',           'uses' => 'RoutesController@destroy']);
        Route::get('routes/{routeId}/enable', ['as' => 'admin.routes.enable',           'uses' => 'RoutesController@enable']);
        Route::get('routes/{routeId}/disable', ['as' => 'admin.routes.disable',          'uses' => 'RoutesController@disable']);
        // Audit routes
        Route::get('audit', ['as' => 'admin.audit.index',             'uses' => 'AuditsController@index']);
        Route::get('audit/purge', ['as' => 'admin.audit.purge',             'uses' => 'AuditsController@purge']);
        Route::get('audit/{auditId}/replay', ['as' => 'admin.audit.replay',            'uses' => 'AuditsController@replay']);
        Route::get('audit/{auditId}/show', ['as' => 'admin.audit.show',              'uses' => 'AuditsController@show']);
        // Error routes
        Route::get('errors', ['as' => 'admin.errors.index',             'uses' => 'ErrorsController@index']);
        Route::get('errors/purge', ['as' => 'admin.errors.purge',             'uses' => 'ErrorsController@purge']);
        Route::get('errors/{errorId}/show', ['as' => 'admin.errors.show',              'uses' => 'ErrorsController@show']);
        // Settings routes
        Route::post('settings', ['as' => 'admin.settings.store',            'uses' => 'SettingsController@store']);
        Route::get('settings', ['as' => 'admin.settings.index',            'uses' => 'SettingsController@index']);
        Route::get('settings/load', ['as' => 'admin.settings.load',             'uses' => 'SettingsController@load']);
        Route::get('settings/create', ['as' => 'admin.settings.create',           'uses' => 'SettingsController@create']);
        Route::get('settings/{settingKey}', ['as' => 'admin.settings.show',             'uses' => 'SettingsController@show']);
        Route::patch('settings/{settingKey}', ['as' => 'admin.settings.patch',            'uses' => 'SettingsController@update']);
        Route::put('settings/{settingKey}', ['as' => 'admin.settings.update',           'uses' => 'SettingsController@update']);
        Route::delete('settings/{settingKey}', ['as' => 'admin.settings.destroy',          'uses' => 'SettingsController@destroy']);
        Route::get('settings/{settingKey}/edit', ['as' => 'admin.settings.edit',             'uses' => 'SettingsController@edit']);
        Route::get('settings/{settingKey}/confirm-delete', ['as' => 'admin.settings.confirm-delete',   'uses' => 'SettingsController@getModalDelete']);
        Route::get('settings/{settingKey}/delete', ['as' => 'admin.settings.delete',           'uses' => 'SettingsController@destroy']);

        //imported rootes

        // Projects routes
        Route::post('projects/enableSelected', ['as' => 'admin.projects.enable-selected',  'uses' => 'ProjectsController@enableSelected']);
        Route::post('projects/disableSelected', ['as' => 'admin.projects.disable-selected', 'uses' => 'ProjectsController@disableSelected']);
        Route::get('projects', ['as' => 'admin.projects.index',            'uses' => 'ProjectsController@index']);
        Route::get('/old_projects', ['as' => 'admin.projects.old_projects',            'uses' => 'ProjectsController@old_projects']);

        Route::get('projects/tasks/{userId}', ['as' => 'admin.projects.projectTaskByUser',  'uses' => 'ProjectsController@projectTaskByUser']);

        Route::get('projects/tasks-status/{status}', ['as' => 'admin.projects.projectTaskByStatus', 'uses' => 'ProjectsController@projectTaskByStatus']);

        Route::resource('datatables', 'ProjectsController', [
            'anyData'  => 'datatables.data',
            'index' => 'datatables',
        ]);



        Route::post('tasksubcat',                         ['as' => 'admin.tasksubcat.store',            'uses' => 'TaskSubCatController@store']);
        Route::get('tasksubcat',                         ['as' => 'admin.tasksubcat.index',            'uses' => 'TaskSubCatController@index']);
        Route::get('tasksubcat/create',                         ['as' => 'admin.tasksubcat.create',            'uses' => 'TaskSubCatController@create']);

         Route::post('tasksubcat/{id}',                         ['as' => 'admin.tasksubcat.update',            'uses' => 'TaskSubCatController@update']);

         Route::get('tasksubcat/{id}/edit',                         ['as' => 'admin.tasksubcat.edit',            'uses' => 'TaskSubCatController@edit']);

         Route::get('tasksubcat/{id}/confirm-delete', ['as' => 'admin.tasksubcat.confirm-delete',   'uses' => 'TaskSubCatController@getModalDelete']);
         Route::get('tasksubcat/{id}/delete',         ['as' => 'admin.tasksubcat.delete',           'uses' => 'TaskSubCatController@destroy']);

              Route::get('tasks/ajax/GetSubCat', ['as' => 'admin.tsks.ajaxGetSubCat',      'uses' => 'ProjectTaskController@ajaxGetSubCat']);



        Route::get('projects/create', ['as' => 'admin.projects.create',           'uses' => 'ProjectsController@create']);
        Route::post('projects', ['as' => 'admin.projects.store',            'uses' => 'ProjectsController@store']);
        Route::get('projects/{leadId}', ['as' => 'admin.projects.show',             'uses' => 'ProjectsController@show']);
        Route::patch('projects/{leadId}', ['as' => 'admin.projects.patch',            'uses' => 'ProjectsController@update']);
        Route::put('projects/{leadId}', ['as' => 'admin.projects.update',           'uses' => 'ProjectsController@update']);
        Route::delete('projects/{leadId}', ['as' => 'admin.projects.destroy',          'uses' => 'ProjectsController@destroy']);
        Route::get('projects/{leadId}/edit', ['as' => 'admin.projects.edit',             'uses' => 'ProjectsController@edit']);
        Route::get('projects/{leadId}/confirm-delete', ['as' => 'admin.projects.confirm-delete',   'uses' => 'ProjectsController@getModalDelete']);
        Route::get('projects/{leadId}/delete', ['as' => 'admin.projects.delete',           'uses' => 'ProjectsController@destroy']);
        Route::get('projects/{leadId}/enable', ['as' => 'admin.projects.enable',           'uses' => 'ProjectsController@enable']);
        Route::get('projects/{leadId}/disable', ['as' => 'admin.projects.disable',          'uses' => 'ProjectsController@disable']);

        Route::post('ajaxTaskUpdate', ['as' => 'admin.ajaxTaskUpdate', 'uses' => 'ProjectTaskController@ajaxTaskUpdate']);
        Route::post('ajaxTaskPeopleUpdate', ['as' => 'admin.ajaxTaskPeopleUpdate', 'uses' => 'ProjectTaskController@ajaxTaskPeopleUpdate']);

        Route::get('projectboard', ['as' => 'projectboard', 'uses' => 'ProjectboardController@index']);
        Route::get('calendar', ['as' => 'calendar', 'uses' => 'CalendarController@index']);

        //Project Calendar
        Route::get('/project/calendar', ['as' => 'admin.project.calendar', 'uses' => 'ProjectCalendarController@index']);
        Route::get('/projects/filter/monthly', ['as' => 'admin.project.filter', 'uses' => 'ProjectsController@filterbydate']);
        Route::get('backlogs/tasks/{id}/', ['as' => 'admin.backlogs.tasks',          'uses' => 'ProjectTaskController@backlogsTasks']);

        // job sheet
        Route::get('job-sheet',['as'=>'admin.job-sheet.index','uses'=>'JobsheetController@index']);
        Route::get('job-sheet/create',['as'=>'admin.job-sheet.create','uses'=>'JobsheetController@create']);
        Route::get('job-sheet/search-customer',['as'=>'admin.job-sheet.search-customer','uses'=>'JobsheetController@searchCustomer']);
        Route::get('job-sheet/find-customer',['as'=>'admin.job-sheet.find.customer','uses'=>'JobsheetController@findCustomer']);
        Route::post('job-sheet/create',['as'=>'admin.job-sheet.store','uses'=>'JobsheetController@store']);
        Route::get('job-sheet/edit/{id}',['as'=>'admin.job-sheet.edit','uses'=>'JobsheetController@edit']);
        Route::get('job-sheet/print/{id}',['as'=>'admin.job-sheet.print','uses'=>'JobsheetController@print']);
        Route::post('job-sheet/edit/{id}',['as'=>'admin.job-sheet.edit','uses'=>'JobsheetController@update']);
        Route::post('job-sheet/repair-status/{id}',['as'=>'admin.repair.status','uses'=>'JobsheetController@repairStatus']);
        Route::get('job-sheet/confirm-delete/{id}',['as'=>'admin.job-sheet.confirm-delete','uses'=>'JobsheetController@deleteModal']);
        Route::get('job-sheet/delete/{id}',['as'=>'admin.job-sheet.delete','uses'=>'JobsheetController@destroy']);
        Route::get('job-sheet/show/{id}',['as'=>'admin.job-sheet.show','uses'=>'JobsheetController@show']);

        //tasks modal
        Route::get('project_tasks/create/modals/{projectid}', ['as' => 'admin.project_task.modals',           'uses' => 'ProjectTaskController@openmodal']);
        Route::post('project_tasks/store/modals', ['as' => 'admin.project_task.postmodals',           'uses' => 'ProjectTaskController@postmodal']);

        //LEADS ROUTES

        Route::get('leads/SMSReport', ['as' => 'leads.receive-sms-report', 'uses' => 'LeadsController@receiveSMSReport']);

        // Search
        Route::get('search/leads', ['as' => 'admin.search.leads', 'uses' => 'LeadsController@search']);

        // Import Export Excel Files - Leads
        Route::get('importExportLeads', ['as' => 'admin.import-export.leads',            'uses' => 'ExcelController@leads']);

        Route::get('downloadExcelLeads/{type}', ['as' => 'admin.import-export.downloadExcelLeads', 'uses' => 'ExcelController@downloadExcelLeads']);
        Route::post('importExcelLeads', ['as' => 'admin.import-export.importExcelLeads', 'uses' => 'ExcelController@importExcelLeads']);

        Route::get('downloadexcelfilter', ['as' => 'admin.import-export.downloadexcelfilter', 'uses' => 'LeadsController@DownloadExcelFilter']);

        // For autocomplete of company
        Route::get('getdata', ['as' => 'admin.getdata', 'uses' => 'LeadsController@get_company']);
        // For autocomplete of company
        Route::get('getLeads', ['as' => 'admin.getLeads', 'uses' => 'TasksController@getLeads']);

        // report routes
        Route::post('reports/enableSelected', ['as' => 'admin.contacts.rptenable-selected',  'uses' => 'ContactsController@enableSelected']);
        Route::post('contacts/disableSelected', ['as' => 'admin.contacts.disable-selected', 'uses' => 'ContactsController@disableSelected']);

        Route::get('reports', ['as' => 'admin.reports.index',            'uses' => 'ReportsController@index']);

        Route::get('reports/leads_today', ['as' => 'admin.reports.reports_leads_today',      'uses' => 'ReportsController@leadsToday']);

        Route::get('reports/leads_by_status', ['as' => 'admin.reports.reports_leads_by_status',           'uses' => 'ReportsController@leadsByStatus']);
        Route::post('reports/post_leads_by_status', ['as' => 'admin.reports.reports_post_leads_by_status',           'uses' => 'ReportsController@postLeadsByStatus']);



        Route::post('leadsMutidelete',['as'=>'admin.leads.leadsMutidelete','uses'=>'LeadsController@confirmMultiDelete']);


        Route::get('reports/converted_leads', ['as' => 'admin.reports.reports_converted_leads',           'uses' => 'ReportsController@convertedLeads']);
        Route::post('reports/post_converted_leads', ['as' => 'admin.reports.reports_post_converted_leads',           'uses' => 'ReportsController@postConvertedLeads']);

        Route::get('reports/all_activities', ['as' => 'admin.reports.reports_all_activities',           'uses' => 'ReportsController@allActivities']);
        Route::get('reports/today_call_activities', ['as' => 'admin.reports.reports_today_calls',           'uses' => 'ReportsController@todayCallActivities']);
        Route::get('reports/all_contacts', ['as' => 'admin.reports.reports_all_contacts',           'uses' => 'ReportsController@allContacts']);
        Route::get('reports/all_clients', ['as' => 'admin.reports.reports_all_clients',           'uses' => 'ReportsController@allClients']);
        Route::get('payrollreports/monthly_report', ['as' => 'admin.payrollreports.monthly_report',           'uses' => 'ReportsController@payrollMonthlyReport']);

        Route::post('payrollreports/post_monthly_report', ['as' => 'admin.payrollreports.post_monthly_report', 'uses' => 'ReportsController@postPayrollMonthlyReport']);

        //pay frequency
        Route::get('payroll/payfrequency', ['as' => 'admin.payfrequency.index', 'uses' => 'PayFrequencyController@index']);
        Route::get('payroll/payfrequency/create', ['as' => 'admin.payfrequency.create', 'uses' => 'PayFrequencyController@create']);
        Route::post('payroll/payfrequency/create', ['as' => 'admin.payfrequency.store', 'uses' => 'PayFrequencyController@store']);
        Route::get('payroll/payfrequency/{id}', ['as' => 'admin.payfrequency.edit', 'uses' => 'PayFrequencyController@edit']);
        Route::post('payroll/payfrequency/{id}', ['as' => 'admin.payfrequency.update', 'uses' => 'PayFrequencyController@update']);
        Route::get('payroll/payfrequency/confirm-delete/{id}', ['as' => 'admin.payfrequency.confirmdelete', 'uses' => 'PayFrequencyController@getModalDelete']);
        Route::get('payroll/payfrequency/delete/{id}', ['as' => 'admin.payfrequency.delete', 'uses' => 'PayFrequencyController@destroy']);

        Route::get('payroll/payfrequency/view_timecard/{id}', ['as' => 'admin.payfrequency.view_timecard', 'uses' => 'PayFrequencyController@view_timecard']);
        Route::get('payroll/payfrequency/view_salarylist/{id}', ['as' => 'admin.payfrequency.view_salarylist', 'uses' => 'PayFrequencyController@view_salarylist']);

        // Send SMS
        Route::get('sendsms', ['as' => 'admin.sendsms', 'uses' => 'LeadsController@sendsms']);
        Route::get('sms/send', ['as' => 'admin.send_sms',      'uses' => 'SmsController@index']);
        Route::post('sms/postSend', ['as' => 'admin.post_send_sms',      'uses' => 'SmsController@postSend']);

        // Send SMS
        Route::post('leads/sendSMS', ['as' => 'admin.leads.send-sms', 'uses' => 'LeadsController@sendSMS']);
        Route::post('leads/sendLeadSMS', ['as' => 'admin.leads.send-lead-sms', 'uses' => 'LeadsController@sendLeadSMS']);

        // Lead routes
        Route::post('leads/enableSelected', ['as' => 'admin.leads.enable-selected',  'uses' => 'LeadsController@enableSelected']);
        Route::post('leads/disableSelected', ['as' => 'admin.leads.disable-selected', 'uses' => 'LeadsController@disableSelected']);
        Route::get('leads/search', ['as' => 'admin.leads.search',           'uses' => 'LeadsController@searchByName']);
        Route::post('leads/getInfo', ['as' => 'admin.leads.get-info',         'uses' => 'LeadsController@getInfo']);
        Route::post('leads', ['as' => 'admin.leads.store',            'uses' => 'LeadsController@store']);
        Route::get('leads', ['as' => 'admin.leads.index',            'uses' => 'LeadsController@index']);
        Route::get('today_followed', ['as' => 'admin.leads.today_followed',   'uses' => 'LeadsController@today_followed']);
        Route::get('leads/create', ['as' => 'admin.leads.create',           'uses' => 'LeadsController@create']);
        Route::get('leads/create/modal', ['as' => 'admin.leads.modal',           'uses' => 'LeadsController@createModal']);
        Route::post('leads/store/modal', ['as' => 'admin.leads.postmodal',           'uses' => 'LeadsController@postModal']);

        Route::get('leads/{leadId}', ['as' => 'admin.leads.show',             'uses' => 'LeadsController@show']);

        Route::post('leads/{leadId}/storelogo', ['as' => 'admin.leads.storelogo',             'uses' => 'LeadsController@storeLogo']);

        Route::patch('leads/{leadId}', ['as' => 'admin.leads.patch',            'uses' => 'LeadsController@update']);
        Route::put('leads/{leadId}', ['as' => 'admin.leads.update',           'uses' => 'LeadsController@update']);
        Route::delete('leads/{leadId}', ['as' => 'admin.leads.destroy',          'uses' => 'LeadsController@destroy']);
        Route::get('leads/{leadId}/edit', ['as' => 'admin.leads.edit',             'uses' => 'LeadsController@edit']);
        Route::get('leads/{leadId}/confirm-delete', ['as' => 'admin.leads.confirm-delete',   'uses' => 'LeadsController@getModalDelete']);
        Route::get('leads/{leadId}/delete', ['as' => 'admin.leads.delete',           'uses' => 'LeadsController@destroy']);
        Route::get('leads/{leadId}/enable', ['as' => 'admin.leads.enable',           'uses' => 'LeadsController@enable']);
        Route::get('leads/{leadId}/disable', ['as' => 'admin.leads.disable',          'uses' => 'LeadsController@disable']);

        Route::get('getcontactinfo', ['as' => 'admin.leads.getcontactinfo', 'uses' => 'LeadsController@getcontactinfo']);

        Route::get('todays_action', ['as' => 'admin.todays_action.disable',          'uses' => 'LeadsController@todays_action']);
        Route::get('overdue_leads', ['as' => 'admin.overdue_leads.disable',          'uses' => 'LeadsController@overdue_leads']);

        Route::post('ajax_lead_status', ['as' => 'admin.ajax_lead_status', 'uses' => 'LeadsController@ajaxLeadStatus']);
        Route::post('ajax_task_status', ['as' => 'admin.ajax_task_status', 'uses' => 'TasksController@ajaxTaskStatus']);

        Route::post('ajax_lead_rating', ['as' => 'admin.ajax_lead_rating', 'uses' => 'LeadsController@ajaxLeadRating']);

        // For autocomplete of Cities
        Route::get('getCities', ['as' => 'admin.getCities', 'uses' => 'LeadsController@getCities']);

        Route::get('mail/{taskId}/show-offerlettermodal', ['as' => 'admin.mail.show-offerlettermodal',   'uses' => 'MailController@getOfferLetterModalMail']);

        Route::post('mail/{taskId}/send-offerlettermodal', ['as' => 'admin.mail.send-offerlettermodal',           'uses' => 'MailController@postOfferLetterModalMail']);

        Route::get('mail/{taskId}/show-unsuccessfulapplicationmodal', ['as' => 'admin.mail.show-unsuccessfulapplicationmodal',   'uses' => 'MailController@getUnsuccessAppModalMail']);
        Route::post('mail/{taskId}/send-unsuccessfulapplicationmodal', ['as' => 'admin.mail.send-unsuccessfulapplicationmodal',           'uses' => 'MailController@postUnsuccessAppModalMail']);

        Route::get('mail/{taskId}/show-pendingmodal', ['as' => 'admin.mail.show-pendingmodal',   'uses' => 'MailController@getPendingModalMail']);
        Route::post('mail/{taskId}/send-pendingmodal', ['as' => 'admin.mail.send-pendingmodal',           'uses' => 'MailController@postPendingModalMail']);


        Route::get('edit-my-profile',['as'=>'admin.user.edit-my-profile','uses'=>'UserDetailController@edit_profile']);

        Route::get('create-my-profile',['as'=>'admin.user.cr-my-profile','uses'=>'UserDetailController@create']);
         Route::get('my_attendance/calandar',['as'=>'admin.my_attendance','uses'=>'HrCalandarController@showMyAttendace']);

        Route::get('emp_hirarchy/{user_id}','UsersController@emphirarchy');

        Route::get('get_emp_hirarchy/{user_id}','UsersController@getemphierachy');
        Route::get('my_emp_hirarchy','UsersController@myhirarchy');
        Route::get('add_employment/{user_id}','UserDetailController@addemployment');
        Route::post('add_employment/{user_id}','UserDetailController@storeemployment');
        Route::get('edit_employment/{id}',['as'=>'admin.edit_employment' ,
            'uses'=>'UserDetailController@editemployment']);

        Route::post('edit_employment/{id}',['as'=>'admin.post_employment' ,
            'uses'=>'UserDetailController@updateemployment']);


        //campaigns
        Route::get('campaigns/index', ['as' => 'admin.campaigns.index',             'uses' => 'CampaignsController@index']);
        Route::get('campaigns/create', ['as' => 'admin.campaigns.create',             'uses' => 'CampaignsController@create']);
        Route::post('campaigns/create', ['as' => 'admin.campaigns.store',             'uses' => 'CampaignsController@store']);
        Route::get('campaigns/edit/{id}', ['as' => 'admin.campaigns.edit',             'uses' => 'CampaignsController@edit']);
        Route::post('campaigns/edit/{id}', ['as' => 'admin.campaigns.update',             'uses' => 'CampaignsController@update']);
        Route::get('campaigns/show/{id}', ['as' => 'admin.campaigns.show',             'uses' => 'CampaignsController@show']);
        Route::get('campaigns/confirm-delete/{id}', ['as' => 'admin.campaigns.confirm-delete',             'uses' => 'CampaignsController@getModalDelete']);
        Route::get('campaigns/delete/{id}', ['as' => 'admin.campaigns.delete', 'uses' => 'CampaignsController@destroy']);

        //onboarding
        Route::get('onboard/tasktype', ['as' => 'admin.onboard.tasktype', 'uses' => 'Onboarding\TaskTypeController@index']);
        Route::get('onboard/tasktype/create', ['as' => 'admin.onboard.tasktype.create', 'uses' => 'Onboarding\TaskTypeController@create']);
        Route::post('onboard/tasktype/store', ['as' => 'admin.onboard.tasktype.store', 'uses' => 'Onboarding\TaskTypeController@store']);
        Route::get('onboard/tasktype/edit/{id}', ['as' => 'admin.onboard.tasktype.edit', 'uses' => 'Onboarding\TaskTypeController@edit']);
        Route::post('onboard/tasktype/edit/{id}', ['as' => 'admin.onboard.tasktype.update', 'uses' => 'Onboarding\TaskTypeController@update']);
        Route::get('onboard/tasktype/show/{id}', ['as' => 'admin.onboard.tasktype.show', 'uses' => 'Onboarding\TaskTypeController@show']);
        Route::get('onboard/tasktype/confirm-delete/{id}', ['as' => 'admin.onboard.tasktype.confirm-delete', 'uses' => 'Onboarding\TaskTypeController@getModalDelete']);
        Route::get('onboard/tasktype/delete/{id}', ['as' => 'admin.onboard.tasktype.delete', 'uses' => 'Onboarding\TaskTypeController@destroy']);

        Route::get('onboard/events', ['as' => 'admin.onboard.events', 'uses' => 'Onboarding\EventsController@index']);
        Route::get('onboard/events/create', ['as' => 'admin.onboard.events.create', 'uses' => 'Onboarding\EventsController@create']);
        Route::post('onboard/events/store', ['as' => 'admin.onboard.events.store', 'uses' => 'Onboarding\EventsController@store']);
        Route::get('onboard/events/edit/{id}', ['as' => 'admin.onboard.events.edit', 'uses' => 'Onboarding\EventsController@edit']);
        Route::post('onboard/events/edit/{id}', ['as' => 'admin.onboard.events.update', 'uses' => 'Onboarding\EventsController@update']);
        Route::get('onboard/events/show/{id}', ['as' => 'admin.onboard.events.show', 'uses' => 'Onboarding\EventsController@show']);
        Route::get('onboard/events/confirm-delete/{id}', ['as' => 'admin.onboard.events.confirm-delete', 'uses' => 'Onboarding\EventsController@getModalDelete']);
        Route::get('onboard/events/delete/{id}', ['as' => 'admin.onboard.events.delete', 'uses' => 'Onboarding\EventsController@destroy']);

        Route::get('onboard/task', ['as' => 'admin.onboard.task', 'uses' => 'Onboarding\TaskController@index']);
        Route::get('onboard/task/create', ['as' => 'admin.onboard.task.create', 'uses' => 'Onboarding\TaskController@create']);
        Route::post('onboard/task/store', ['as' => 'admin.onboard.task.store', 'uses' => 'Onboarding\TaskController@store']);
        Route::get('onboard/task/edit/{id}', ['as' => 'admin.onboard.task.edit', 'uses' => 'Onboarding\TaskController@edit']);
        Route::post('onboard/task/edit/{id}', ['as' => 'admin.onboard.task.update', 'uses' => 'Onboarding\TaskController@update']);
        Route::get('onboard/task/show/{id}', ['as' => 'admin.onboard.task.show', 'uses' => 'Onboarding\TaskController@show']);
        Route::get('onboard/task/confirm-delete/{id}', ['as' => 'admin.onboard.task.confirm-delete', 'uses' => 'Onboarding\TaskController@getModalDelete']);
        Route::get('onboard/task/delete/{id}', ['as' => 'admin.onboard.task.delete', 'uses' => 'Onboarding\TaskController@destroy']);
        Route::get('onboard/task/tasktype/{id}', 'Onboarding\TaskController@getTaskinfo');

        // Task routes
        Route::post('tasks/enableSelected', ['as' => 'admin.tasks.enable-selected',  'uses' => 'TasksController@enableSelected']);
        Route::post('tasks/disableSelected', ['as' => 'admin.tasks.disable-selected', 'uses' => 'TasksController@disableSelected']);
        Route::get('tasks/search', ['as' => 'admin.tasks.search',           'uses' => 'TasksController@searchByName']);
        Route::post('tasks/getInfo', ['as' => 'admin.tasks.get-info',         'uses' => 'TasksController@getInfo']);
        Route::post('tasks', ['as' => 'admin.tasks.store',            'uses' => 'TasksController@store']);
        Route::get('tasks', ['as' => 'admin.tasks.index',            'uses' => 'TasksController@index']);

        Route::get('tasks/getAllTasks', ['as' => 'admin.tasks.allData',            'uses' => 'TasksController@allData']);

        Route::get('tasks/create', ['as' => 'admin.tasks.create',           'uses' => 'TasksController@create']);
        Route::get('tasks/{taskId}', ['as' => 'admin.tasks.show',             'uses' => 'TasksController@show']);
        Route::patch('tasks/{taskId}', ['as' => 'admin.tasks.patch',            'uses' => 'TasksController@update']);
        Route::put('tasks/{taskId}', ['as' => 'admin.tasks.update',           'uses' => 'TasksController@update']);
        Route::delete('tasks/{taskId}', ['as' => 'admin.tasks.destroy',          'uses' => 'TasksController@destroy']);
        Route::get('tasks/{taskId}/edit', ['as' => 'admin.tasks.edit',             'uses' => 'TasksController@edit']);
        Route::get('tasks/{taskId}/confirm-delete', ['as' => 'admin.tasks.confirm-delete',   'uses' => 'TasksController@getModalDelete']);
        Route::get('tasks/{taskId}/delete', ['as' => 'admin.tasks.delete',           'uses' => 'TasksController@destroy']);
        Route::get('tasks/{taskId}/enable', ['as' => 'admin.tasks.enable',           'uses' => 'TasksController@enable']);
        Route::get('tasks/{taskId}/disable', ['as' => 'admin.tasks.disable',          'uses' => 'TasksController@disable']);

        // For autocomplete of contact
        Route::get('getContacts', ['as' => 'admin.getcontacts', 'uses' => 'ContactsController@get_contact']);
        // For autocomplete of Clients
        Route::get('getClients', ['as' => 'admin.getclients', 'uses' => 'ClientsController@get_client']);

        // Send mail from Lead Detail
        Route::get('mail/{taskId}/show-mailmodal', ['as' => 'admin.mail.show-mailmodal',   'uses' => 'MailController@getModalMail']);
        Route::post('mail/{taskId}/send-mail-modal', ['as' => 'admin.mail.send-mail-modal',           'uses' => 'MailController@postModalMail']);
        // Send mail from Quotation Detail
        Route::get('mail/quotation/{taskId}/show-mailmodal', ['as' => 'admin.mail.quotation.show-mailmodal',   'uses' => 'MailController@getModalMailQuotation']);
        Route::post('mail/quotation/{taskId}/send-mail-modal', ['as' => 'admin.mail.quotation.send-mail-modal',           'uses' => 'MailController@postModalMailQuotation']);

        Route::get('mail/inbox', ['as' => 'admin.mail.inbox',   'uses' => 'MailController@inbox']);
        Route::get('mail/reloadinbox', ['as' => 'admin.mail.reloadinbox',   'uses' => 'MailController@reloadinbox']);
        Route::get('mail/sent', ['as' => 'admin.mail.sent',   'uses' => 'MailController@sent']);
        Route::get('mail/show/{mailid}', ['as' => 'admin.mail.show',   'uses' => 'MailController@show']);
        Route::post('mail/reply', ['as' => 'admin.mail.reply',   'uses' => 'MailController@reply']);
        Route::get('mail/attachment/{filename}', ['as' => 'admin.mail.attachment',   'uses' => 'MailController@attachment']);
        Route::get('mail/{mailId}/confirm-delete', ['as' => 'admin.mail.confirm-delete',   'uses' => 'MailController@getModalDelete']);
        Route::get('mail/{mailId}/delete', ['as' => 'admin.mail.delete',           'uses' => 'MailController@destroy']);
        Route::get('mail/sent_attachment/{filename}', ['as' => 'admin.mail.sentattachment',   'uses' => 'MailController@sent_attachment']);
        Route::get('mail/from_lead/{mailId}', ['as' => 'admin.mail.mail_from_lead',           'uses' => 'MailController@showModalLeadMail']);

        // Send the sms to all product/lead by course and status
        Route::get('mail/send-bulk-sms', ['as' => 'admin.mail.send-bulk-sms',           'uses' => 'SmsController@getBulkSMSForm']);
        Route::post('mail/post-send-bulk-sms', ['as' => 'admin.mail.post-send-bulk-sms',           'uses' => 'SmsController@postBulkSMS']);

        // Send the email to all product/lead by course and status
        Route::get('mail/send-bulk-email', ['as' => 'admin.mail.send-bulk-email',           'uses' => 'MailController@getBulkMailForm']);
        Route::post('mail/post-send-bulk-email', ['as' => 'admin.mail.post-send-bulk-email',           'uses' => 'MailController@postBulkMail']);

        Route::get('mail/send-bulk-email-all', ['as' => 'admin.mail.send-bulk-email-all',           'uses' => 'MailController@getBulkMailAllForm']);
        Route::post('mail/post-send-bulk-email-all', ['as' => 'admin.mail.post-send-bulk-email-all',           'uses' => 'MailController@postBulkMailAll']);

        Route::get('mail/send-bulk-email-all', ['as' => 'admin.mail.send-bulk-email-all',           'uses' => 'MailController@getBulkMailAllForm']);
        Route::post('mail/post-send-bulk-email-all', ['as' => 'admin.mail.post-send-bulk-email-all',           'uses' => 'MailController@postBulkMailAll']);

        Route::post('post-send-bulk-email/loadTemplate', ['as' => 'admin.mail.bulk-template',           'uses' => 'MailController@loadTemplate']);

        Route::get('email/getLeadsTotal', ['as' => 'admin.email.getLeadsTotal',           'uses' => 'MailController@getLeadsTotal']);

        //bulkmail logs
        Route::get('bulkmail/logs', ['as' => 'admin.mail.bulklogs',           'uses' => 'MailController@mailLogIndex']);

        // Send bulk email to all contacts
        Route::get('mail/send-bulk-email-contact', ['as' => 'admin.mail.send-bulk-email-contact',           'uses' => 'MailController@getBulkEmailContact']);
        Route::post('mail/post-send-bulk-email-contact', ['as' => 'admin.mail.post-send-bulk-email-contact',           'uses' => 'MailController@postBulkMailContact']);

        Route::post('group_select_clients', ['as' => 'admin.group_select_clients', 'uses' => 'SmsController@get_group_select_clients']);

        Route::post('client_select_contacts', ['as' => 'admin.client_select_contacts', 'uses' => 'SmsController@get_client_select_contacts']);

        // Send the sms to all contacts
        Route::get('mail/send-bulk-sms-contact', ['as' => 'admin.send-bulk-sms-contact',           'uses' => 'SmsController@getBulkSMSContact']);
        Route::post('mail/post-send-bulk-sms-contact', ['as' => 'admin.post-send-bulk-sms-contact',           'uses' => 'SmsController@postBulkSMSContact']);

        // Product routes
        Route::post('products/enableSelected', ['as' => 'admin.products.enable-selected',  'uses' => 'ProductController@enableSelected']);
        Route::post('products/disableSelected', ['as' => 'admin.products.disable-selected', 'uses' => 'ProductController@disableSelected']);
        Route::post('products/multipledelete', ['as' => 'admin.products.multipledelete', 'uses' => 'ProductController@multipledelete']);


        Route::get('products/search', ['as' => 'admin.products.search',           'uses' => 'ProductController@searchByName']);
        Route::post('products/getInfo', ['as' => 'admin.products.get-info',         'uses' => 'ProductController@getInfo']);
        Route::post('products', ['as' => 'admin.products.store',            'uses' => 'ProductController@store']);
        Route::get('products', ['as' => 'admin.products.index',            'uses' => 'ProductController@index']);
        Route::get('products/create', ['as' => 'admin.products.create',           'uses' => 'ProductController@create']);
        Route::get('products/{courseId}', ['as' => 'admin.products.show',             'uses' => 'ProductController@show']);
        Route::patch('products/{courseId}', ['as' => 'admin.products.patch',            'uses' => 'ProductController@update']);
        Route::put('products/{courseId}', ['as' => 'admin.products.update',           'uses' => 'ProductController@update']);
        Route::delete('products/{courseId}', ['as' => 'admin.products.destroy',          'uses' => 'ProductController@destroy']);
        Route::get('products/{courseId}/edit', ['as' => 'admin.products.edit',             'uses' => 'ProductController@edit']);
        Route::get('products/{courseId}/confirm-delete', ['as' => 'admin.products.confirm-delete',   'uses' => 'ProductController@getModalDelete']);
        Route::get('products/{courseId}/delete', ['as' => 'admin.products.delete',           'uses' => 'ProductController@destroy']);
        Route::get('products/{courseId}/enable', ['as' => 'admin.products.enable',           'uses' => 'ProductController@enable']);
        Route::get('products/{courseId}/disable', ['as' => 'admin.products.disable',          'uses' => 'ProductController@disable']);

        Route::get('product/stocks_count', ['as' => 'admin.products.stocks_count', 'uses' => 'ProductController@stocks_count']);
        Route::get('product/stocks_count/delete/{id}', ['as' => 'admin.products.stocks_count.delete', 'uses' => 'ProductController@delete_stockmoves_product']);

        Route::get('product/stock_adjustment', ['as' => 'admin.products.stock_adjustment', 'uses' => 'ProductController@stock_adjustment']);

        Route::get('product/statement', ['as' => 'admin.products.statement', 'uses' => 'ProductController@product_statement']);
        Route::get('product/stock-ledger', ['as' => 'admin.products.stock-ledger', 'uses' => 'ProductController@stockLedger']);
        Route::get('product/stock-ledger/view', ['as' => 'admin.products.stock-ledger-view', 'uses' => 'ProductController@showStockLedger']);

        Route::get('stockentries', ['as' => 'admin.products.stocks_entries', 'uses' => 'ProductController@stocks_entries']);
        Route::get('stockentry_detail/{id}', ['as' => 'admin.products.stockentry_detail', 'uses' => 'ProductController@stockentry_detail']);

        // Cases routes
        Route::post('cases/enableSelected', ['as' => 'admin.cases.enable-selected',  'uses' => 'CasesController@enableSelected']);
        Route::post('cases/disableSelected', ['as' => 'admin.cases.disable-selected', 'uses' => 'CasesController@disableSelected']);
        Route::get('cases/search', ['as' => 'admin.cases.search',           'uses' => 'CasesController@searchByName']);
        Route::post('cases/getInfo', ['as' => 'admin.cases.get-info',         'uses' => 'CasesController@getInfo']);
        Route::post('cases', ['as' => 'admin.cases.store',            'uses' => 'CasesController@store']);

        Route::get('cases', ['as' => 'admin.cases.index',            'uses' => 'CasesController@index']);

        Route::get('cases/create', ['as' => 'admin.cases.create',           'uses' => 'CasesController@create']);
        Route::get('cases/{caseId}', ['as' => 'admin.cases.show',             'uses' => 'CasesController@show']);
        Route::patch('case/{caseId}', ['as' => 'admin.cases.patch',            'uses' => 'CasesController@update']);
        Route::put('cases/{caseId}', ['as' => 'admin.cases.update',           'uses' => 'CasesController@update']);
        Route::delete('case/{caseId}', ['as' => 'admin.cases.destroy',          'uses' => 'CasesController@destroy']);
        Route::get('cases/{caseId}/edit', ['as' => 'admin.cases.edit',             'uses' => 'CasesController@edit']);
        Route::get('cases/{caseId}/confirm-delete', ['as' => 'admin.cases.confirm-delete',   'uses' => 'CasesController@getModalDelete']);
        Route::get('cases/{caseId}/delete', ['as' => 'admin.cases.delete',           'uses' => 'CasesController@destroy']);
        Route::get('cases/{caseId}/enable', ['as' => 'admin.cases.enable',           'uses' => 'CasesController@enable']);
        Route::get('cases/{caseId}/disable', ['as' => 'admin.cases.disable',          'uses' => 'CasesController@disable']);

        // casses template category

        Route::get('casescategory', ['as' => 'admin.casescategory.index',            'uses' => 'CasesCategoryController@index']);
        Route::get('casescategory/create', ['as' => 'admin.casescategory.create',            'uses' => 'CasesCategoryController@create']);
        Route::post('casescategory/store', ['as' => 'admin.casescategory.store',            'uses' => 'CasesCategoryController@store']);
        Route::get('casescategory/{id}/edit', ['as' => 'admin.casescategory.edit',            'uses' => 'CasesCategoryController@edit']);
        Route::post('casescategory/{id}/update', ['as' => 'admin.casescategory.update',            'uses' => 'CasesCategoryController@update']);

        Route::get('casescategory/{caseId}/confirm-delete', ['as' => 'admin.casescategory.confirm-delete',   'uses' => 'CasesCategoryController@getModalDelete']);
        Route::get('casescategory/{caseId}/delete', ['as' => 'admin.casescategory.delete',           'uses' => 'CasesCategoryController@destroy']);

        Route::get('casecategorytemplate', ['as' => 'admin.casecategorytemplate.info', 'uses' => 'CasesController@casecategorytemplate']);

        Route::get('casesdashboard', ['as' => 'admin.cases.dashboard', 'uses' => 'CasesDashBoardController@index']);
        // casses sub template category

        Route::get('casessubcategory', ['as' => 'admin.casessubcategory.index',            'uses' => 'CasesSubCategoryController@index']);
        Route::get('casessubcategory/create', ['as' => 'admin.casessubcategory.create',            'uses' => 'CasesSubCategoryController@create']);
        Route::post('casessubcategory/store', ['as' => 'admin.casessubcategory.store',            'uses' => 'CasesSubCategoryController@store']);
        Route::get('casessubcategory/{id}/edit', ['as' => 'admin.casessubcategory.edit',            'uses' => 'CasesSubCategoryController@edit']);
        Route::post('casessubcategory/{id}/update', ['as' => 'admin.casessubcategory.update',            'uses' => 'CasesSubCategoryController@update']);
        Route::get('casessubcategory/{caseId}/confirm-delete', ['as' => 'admin.casessubcategory.confirm-delete',   'uses' => 'CasesSubCategoryController@getModalDelete']);
        Route::get('casessubcategory/{caseId}/delete', ['as' => 'admin.casessubcategory.delete',           'uses' => 'CasesSubCategoryController@destroy']);

        //folders
        Route::get('folders', ['as' => 'admin.folders.index', 'uses' => 'FolderController@index']);
        Route::get('folders/create', ['as' => 'admin.folders.create', 'uses' => 'FolderController@create']);
        Route::post('folders/create', ['as' => 'admin.folders.store', 'uses' => 'FolderController@store']);
        Route::get('folders/edit/{id}', ['as' => 'admin.folders.edit', 'uses' => 'FolderController@edit']);
        Route::post('folders/edit/{id}', ['as' => 'admin.folders.update', 'uses' => 'FolderController@update']);
        Route::get('folders/confirm-delete{id}', ['as' => 'admin.folders.confirm-delete', 'uses' => 'FolderController@getModalDelete']);
        Route::get('folders/delete/{id}', ['as' => 'admin.folders.delete', 'uses' => 'FolderController@destroy']);
        //Doc category
        Route::get('doc_category', ['as' => 'admin.doc_category.index', 'uses' => 'DocCategoriesController@index']);
        Route::get('doc_category/create', ['as' => 'admin.doc_category.create', 'uses' => 'DocCategoriesController@create']);
        Route::post('doc_category/create', ['as' => 'admin.doc_category.store', 'uses' => 'DocCategoriesController@store']);
        Route::get('doc_category/edit/{id}', ['as' => 'admin.doc_category.edit', 'uses' => 'DocCategoriesController@edit']);
        Route::post('doc_category/edit/{id}', ['as' => 'admin.doc_category.update', 'uses' => 'DocCategoriesController@update']);
        Route::get('doc_category/confirm-delete{id}', ['as' => 'admin.doc_category.confirm-delete', 'uses' => 'DocCategoriesController@getModalDelete']);
        Route::get('doc_category/delete/{id}', ['as' => 'admin.doc_category.delete', 'uses' => 'DocCategoriesController@destroy']);
        //sticky notes
        Route::get('stickynote', ['as' => 'admin.stickynote.index', 'uses' => 'StickyNoteController@index']);
        Route::post('stickynote/store', ['as' => 'admin.stickynote.store', 'uses' => 'StickyNoteController@store']);
        Route::post('stickynote/destroy/{id}', ['as' => 'admin.stickynote.destroy', 'uses' => 'StickyNoteController@destroy']);
        // Proposal routes
        Route::post('proposal/enableSelected', ['as' => 'admin.proposal.enable-selected',  'uses' => 'ProposalController@enableSelected']);
        Route::post('proposal/disableSelected', ['as' => 'admin.proposal.disable-selected', 'uses' => 'ProposalController@disableSelected']);
        Route::get('proposal/search', ['as' => 'admin.proposal.search',           'uses' => 'ProposalController@searchByName']);
        Route::post('proposal/getInfo', ['as' => 'admin.proposal.get-info',         'uses' => 'ProposalController@getInfo']);
        Route::post('proposal', ['as' => 'admin.proposal.store',            'uses' => 'ProposalController@store']);

        Route::get('proposal', ['as' => 'admin.proposal.index',            'uses' => 'ProposalController@index']);

        Route::get('proposal/create', ['as' => 'admin.proposal.create',           'uses' => 'ProposalController@create']);
        Route::get('proposal/{proposalId}', ['as' => 'admin.proposal.show',             'uses' => 'ProposalController@show']);
        Route::patch('proposal/{proposalId}', ['as' => 'admin.proposal.patch',            'uses' => 'ProposalController@update']);
        Route::put('proposal/{proposalId}', ['as' => 'admin.proposal.update',           'uses' => 'ProposalController@update']);
        Route::delete('proposal/{proposalId}', ['as' => 'admin.proposal.destroy',          'uses' => 'ProposalController@destroy']);
        Route::get('proposal/{proposalId}/edit', ['as' => 'admin.proposal.edit',             'uses' => 'ProposalController@edit']);
        Route::get('proposal/{proposalId}/confirm-delete', ['as' => 'admin.proposal.confirm-delete',   'uses' => 'ProposalController@getModalDelete']);
        Route::get('proposal/{proposalId}/delete', ['as' => 'admin.proposal.delete',           'uses' => 'ProposalController@destroy']);
        Route::get('proposal/{proposalId}/enable', ['as' => 'admin.proposal.enable',           'uses' => 'ProposalController@enable']);
        Route::get('proposal/{proposalId}/disable', ['as' => 'admin.proposal.disable',          'uses' => 'ProposalController@disable']);

        Route::get('proposal/print/{id}', ['as' => 'admin.proposal.print',  'uses' => 'ProposalController@printInvoice']);
        Route::get('proposal/generatePDF/{id}', ['as' => 'admin.proposal.generatePDF',   'uses' => 'ProposalController@generatePDF']);
        // Send Email of Proposal from Detail Page
        Route::get('proposalMail/{proposalId}/show-mailmodal', ['as' => 'admin.proposalMail.show-mailmodal', 'uses' => 'ProposalController@getModalMail']);
        Route::post('proposalMail/{proposalId}/send-mail-modal', ['as' => 'admin.proposalMail.send-mail-modal', 'uses' => 'ProposalController@postModalMail']);
        // Ajax upload the template
        Route::post('proposal/loadTemplate', ['as' => 'admin.loadTemplate', 'uses' => 'ProposalController@loadTemplate']);
        Route::get('proposal/copy/{id}', ['as' => 'admin.proposal.copy',  'uses' => 'ProposalController@copyDoc']);

        Route::get('order/copy/{id}', ['as' => 'admin.proposal.copydoc',  'uses' => 'OrdersController@copyDoc']);

        Route::get('debtors_lists/ageing', ['as' => 'admin.debtors_lists.ageing', 'uses' => 'PosAnalysisController@ageingView']);

        Route::get('debtors_lists', ['as' => 'admin.debtors_lists', 'uses' => 'PosAnalysisController@debtors_lists']);

        Route::get('debtors_pay/{id}', ['as' => 'admin.debtors_pay', 'uses' => 'PosAnalysisController@debtorsPay']);
        Route::get('creditors_pay/{id}', ['as' => 'admin.creditors_pay', 'uses' => 'PosAnalysisController@creditorsPay']);

        Route::post('debtors_pay', ['as' => 'admin.debtors_pay-subm', 'uses' => 'PosAnalysisController@debtorsPaySubmit']);
        Route::post('creditors_pay', ['as' => 'admin.creditors_pay-subm', 'uses' => 'PosAnalysisController@creditorsPaySubmit']);

        Route::get('creditors_lists/ageing', ['as' => 'admin.creditors_lists.ageing', 'uses' => 'PosAnalysisController@creditorageingView']);

        Route::get('creditors_lists', ['as' => 'admin.creditors_lists', 'uses' => 'PosAnalysisController@creditors_lists']);

        // Documents routes
        Route::post('documents/enableSelected', ['as' => 'admin.documents.enable-selected',  'uses' => 'DocumentController@enableSelected']);
        Route::post('documents/disableSelected', ['as' => 'admin.documents.disable-selected', 'uses' => 'DocumentController@disableSelected']);
        Route::get('documents/search', ['as' => 'admin.documents.search',           'uses' => 'DocumentController@searchByName']);
        Route::get('documents/getInfo', ['as' => 'admin.documents.get-info',         'uses' => 'DocumentController@getInfo']);
        Route::post('documents', ['as' => 'admin.documents.store',            'uses' => 'DocumentController@store']);

        Route::get('documents', ['as' => 'admin.documents.index',            'uses' => 'DocumentController@index']);

        Route::get('documents/create', ['as' => 'admin.documents.create',           'uses' => 'DocumentController@create']);
        Route::get('documents/{documentId}', ['as' => 'admin.documents.show',             'uses' => 'DocumentController@show']);
        Route::patch('document/{documentId}', ['as' => 'admin.documents.patch',            'uses' => 'DocumentController@update']);
        Route::put('documents/{documentId}', ['as' => 'admin.documents.update',           'uses' => 'DocumentController@update']);
        Route::delete('document/{documentId}', ['as' => 'admin.documents.destroy',          'uses' => 'DocumentController@destroy']);
        Route::get('documents/{documentId}/edit', ['as' => 'admin.documents.edit',             'uses' => 'DocumentController@edit']);
        Route::get('documents/{documentId}/confirm-delete', ['as' => 'admin.documents.confirm-delete',   'uses' => 'DocumentController@getModalDelete']);
        Route::get('documents/{documentId}/delete', ['as' => 'admin.documents.delete',           'uses' => 'DocumentController@destroy']);
        Route::get('documents/{documentId}/enable', ['as' => 'admin.documents.enable',           'uses' => 'DocumentController@enable']);
        Route::get('documents/{documentId}/disable', ['as' => 'admin.documents.disable',          'uses' => 'DocumentController@disable']);

        // Bugs routes
        Route::post('bugs/enableSelected', ['as' => 'admin.bugs.enable-selected',  'uses' => 'BugsController@enableSelected']);
        Route::post('bugs/disableSelected', ['as' => 'admin.bugs.disable-selected', 'uses' => 'BugsController@disableSelected']);
        Route::get('bugs/search', ['as' => 'admin.bugs.search',           'uses' => 'BugsController@searchByName']);
        Route::post('bugs/getInfo', ['as' => 'admin.bugs.get-info',         'uses' => 'BugsController@getInfo']);
        Route::post('bugs', ['as' => 'admin.bugs.store',            'uses' => 'BugsController@store']);

        Route::get('bugs', ['as' => 'admin.bugs.index',            'uses' => 'BugsController@index']);

        Route::get('bugs/create', ['as' => 'admin.bugs.create',           'uses' => 'BugsController@create']);
        Route::get('bugs/{bugId}', ['as' => 'admin.bugs.show',             'uses' => 'BugsController@show']);
        Route::patch('bug/{bugId}', ['as' => 'admin.bugs.patch',            'uses' => 'BugsController@update']);
        Route::put('bugs/{bugId}', ['as' => 'admin.bugs.update',           'uses' => 'BugsController@update']);
        Route::delete('bug/{bugId}', ['as' => 'admin.bugs.destroy',          'uses' => 'BugsController@destroy']);
        Route::get('bugs/{bugId}/edit', ['as' => 'admin.bugs.edit',             'uses' => 'BugsController@edit']);
        Route::get('bugs/{bugId}/confirm-delete', ['as' => 'admin.bugs.confirm-delete',   'uses' => 'BugsController@getModalDelete']);
        Route::get('bugs/{bugId}/delete', ['as' => 'admin.bugs.delete',           'uses' => 'BugsController@destroy']);
        Route::get('bugs/{bugId}/enable', ['as' => 'admin.bugs.enable',           'uses' => 'BugsController@enable']);
        Route::get('bugs/{caseId}/disable', ['as' => 'admin.bugs.disable',          'uses' => 'BugsController@disable']);

        // Sales Accounting Routes

        Route::get('trading-boards',['as'=>'admin.trading-boards','uses'=>'TradingBoardController@index']);


        Route::get('orders/trash', ['as' => 'admin.salesaccounttrash.index',     'uses' => 'OrdersTrashController@index']);


        Route::get('sales/list', ['as' => 'admin.salesaccount.index',            'uses' => 'SalesAccountController@invoiceindex']);
        Route::get('payment/list', ['as' => 'admin.paymentaccount.index',          'uses' => 'SalesAccountController@paymentindex']);
        Route::get('order/list', ['as' => 'admin.quotationaccount.index',            'uses' => 'SalesAccountController@quotationindex']);

        Route::get('sales/create', ['as' => 'admin.salesaccount.create',           'uses' => 'SalesAccountController@invoicecreate']);
        Route::get('payment/create/{orderid}/{invoiceid}', ['as' => 'admin.paymentaccount.create',           'uses' => 'SalesAccountController@paymentcreate']);
        Route::get('order/create', ['as' => 'admin.quotationaccount.create',           'uses' => 'SalesAccountController@quotationcreate']);

        Route::get('sales/edit/{orderId}', ['as' => 'admin.salesaccount.edit',             'uses' => 'SalesAccountController@invoiceedit']);
        Route::get('payment/edit/{orderId}', ['as' => 'admin.paymentaccount.edit',             'uses' => 'SalesAccountController@paymentedit']);
        Route::get('order/edit/{orderId}', ['as' => 'admin.quotationaccount.edit',              'uses' => 'SalesAccountController@quotationedit']);

        Route::post('sales', ['as' => 'admin.salesaccount.store',            'uses' => 'SalesAccountController@invoicestore']);
        Route::post('payment', ['as' => 'admin.paymentaccount.store',            'uses' => 'SalesAccountController@paymentstore']);
        Route::post('order', ['as' => 'admin.quotationaccount.store',            'uses' => 'SalesAccountController@quotationstore']);

        Route::post('sales/{orderId}', ['as' => 'admin.salesaccount.update',           'uses' => 'SalesAccountController@invoiceUpdate']);
        Route::post('payment/{orderId}', ['as' => 'admin.paymentaccount.update',           'uses' => 'SalesAccountController@paymentUpdate']);
        Route::post('order/{orderId}', ['as' => 'admin.quotationaccount.update',           'uses' => 'SalesAccountController@quotationUpdate']);

        Route::get('invoice/show/{orderid}/{invoiceid}', ['as' => 'admin.salesaccount.show',           'uses' => 'SalesAccountController@invoiceShow']);
      
        Route::get('payment/show/{orderid}/{paymentid}', ['as' => 'admin.paymentaccount.show',           'uses' => 'SalesAccountController@paymentShow']);
        Route::get('quotation/show/{orderid}', ['as' => 'admin.quotationaccount.show',           'uses' => 'SalesAccountController@quotationShow']);

        Route::get('sales/print/{orderid}/{invoiceid}', ['as' => 'admin.salesaccount.print',  'uses' => 'SalesAccountController@printInvoice']);
        Route::get('payment/print/{id}', ['as' => 'admin.paymentaccount.print',  'uses' => 'SalesAccountController@printPayment']);
        Route::get('quotation/print/{id}', ['as' => 'admin.quotationaccount.print',  'uses' => 'SalesAccountController@printQuotation']);

        Route::get('sales/generatePDF/{orderid}/{invoiceid}', ['as' => 'admin.salesaccount.generatePDF',   'uses' => 'SalesAccountController@generatePDFInvoice']);
        Route::get('payment/generatePDF/{id}', ['as' => 'admin.paymentaccount.generatePDF',   'uses' => 'SalesAccountController@generatePDFPayment']);
        Route::get('quotation/generatePDF/{id}', ['as' => 'admin.quotationaccount.generatePDF',   'uses' => 'SalesAccountController@generatePDFQuotation']);

        // Orders routes
        Route::post('orders/enableSelected', ['as' => 'admin.orders.enable-selected',  'uses' => 'OrdersController@enableSelected']);
        Route::post('orders/disableSelected', ['as' => 'admin.orders.disable-selected', 'uses' => 'OrdersController@disableSelected']);
        Route::get('orders/search', ['as' => 'admin.orders.search',           'uses' => 'OrdersController@searchByName']);
        Route::post('orders/getInfo', ['as' => 'admin.orders.get-info',         'uses' => 'OrdersController@getInfo']);
        Route::post('orders', ['as' => 'admin.orders.store',            'uses' => 'OrdersController@store']);
        Route::get('orders', ['as' => 'admin.orders.index',            'uses' => 'OrdersController@index']);
        Route::get('orders/create', ['as' => 'admin.orders.create',           'uses' => 'OrdersController@create']);
        Route::get('orders/{orderId}', ['as' => 'admin.orders.show',             'uses' => 'OrdersController@show']);
        Route::patch('order/{orderId}', ['as' => 'admin.orders.patch',            'uses' => 'OrdersController@update']);
        Route::put('orders/{orderId}', ['as' => 'admin.orders.update',           'uses' => 'OrdersController@update']);
        Route::delete('order/{orderId}', ['as' => 'admin.orders.destroy',          'uses' => 'OrdersController@destroy']);
        Route::get('orders/{orderId}/edit', ['as' => 'admin.orders.edit',             'uses' => 'OrdersController@edit']);
        Route::get('orders/{orderId}/confirm-delete', ['as' => 'admin.orders.confirm-delete',   'uses' => 'OrdersController@getModalDelete']);
        Route::get('orders/{orderId}/delete', ['as' => 'admin.orders.delete',           'uses' => 'OrdersController@destroy']);

        Route::get('order/renewals', ['as' => 'admin.orders.renewals',           'uses' => 'OrdersController@renewals']);

        Route::get('/orders/convert_to_pi/{id}', ['as' => 'admin.orders.convert_to_pi', 'uses' => 'OrdersController@convertToPI']);
        Route::get('/orders/convert_to_pi_confirm/{id}', ['as' => 'admin.orders.convert_to_pi-confirm', 'uses' => 'OrdersController@ConfirmconvertToPI']);


        // For Orders
        Route::any('products/GetProductDetailAjax/{productId}', ['uses' => 'OrdersController@getProductDetailAjax', 'as' => 'admin.products.GetProductDetailAjax']);
        Route::post('multiple_orders', ['uses' => 'OrdersController@store', 'as' => 'admin.multiple_orders.store']);
        Route::get('order/print/{id}', ['as' => 'admin.orders.print',  'uses' => 'OrdersController@printInvoice']);
        Route::get('order/generatePDF/{id}', ['as' => 'admin.order.generatePDF',   'uses' => 'OrdersController@generatePDF']);

        Route::post('ajax_order_status', ['as' => 'admin.ajax_order_status', 'uses' => 'PurchaseSalePaymentController@ajaxSaleStatus']);


        Route::get('orders/payments/list', ['as' => 'admin.orders.paymentslist',  'uses' => 'OrdersController@paymentlist']);

        Route::get('orders/payment_term/update/{id}', ['as' => 'admin.order.payment_term.update',  'uses' => 'OrdersController@paymentTermUpdate']);

        Route::get('orders/payment_term/emi_list', ['as' => 'admin.order.payment_term.emi_list',  'uses' => 'OrdersController@paymentTermList']);

        Route::get('sales/paymentslist', ['as' => 'admin.sales.paymentslist',  'uses' => 'OrdersController@SalesPayment']);

        Route::get('getpanno/{id}', ['as' => 'admin.getpanno', 'uses' => 'PurchaseController@getPanNUM']);

        Route::get('/purchase/confirm-purchase-modal/{id}', ['as' => 'admin.confirm-purchase-modal', 'uses' => 'PurchaseController@ConfirmPurchaseModel']);

        Route::get('/purchase/confirm-purchase/{id}', ['as' => 'admin.confirm-purchase', 'uses' => 'PurchaseController@ConfirmPurchase']);

        Route::get('/purchase/confirm-post-to-po/{id}', ['as' => 'admin.confirm-post-to-po', 'uses' => 'PurchaseController@ConfirmPostToPO']);

        Route::get('/purchase-book', ['as' => 'admin.purchase-book', 'uses' => 'PurchaseController@PurchaseBook']);
        Route::get('/purchase-book-bymonth/{month}', ['as' => 'admin.purchase-book-bymonth', 'uses' => 'PurchaseController@PurchaseBookByMonths']);

        Route::get('purchase/duepayment', ['as' => 'purchase.sendmail', 'uses' => 'PurchaseController@duePayment']);
        Route::get('purchase/sendmail/{id}', ['as' => 'purchase.sendmail', 'uses' => 'PurchaseController@sendMail']);

        Route::get('/sales-book/', ['as' => 'admin.sales-book', 'uses' => 'SalesAccountController@salesBook']);
        Route::get('/sales-book-bymonth/{month}', ['as' => 'admin.sales-book-bymonth', 'uses' => 'SalesAccountController@SalesBookByMonths']);

        //for sales payment

        Route::get('payment/orders/{id}', ['as' => 'admin.payment.orders.list',     'uses' => 'PurchaseSalePaymentController@SalePaymentlist']);

        Route::get('payment/orders/{id}/create', ['as' => 'admin.payment.orders.create',    'uses' => 'PurchaseSalePaymentController@SalePaymentcreate']);

        Route::post('payment/orders/{id}', ['as' => 'admin.payment.orders.store',    'uses' => 'PurchaseSalePaymentController@SalePaymentpost']);

        Route::get('payment/orders/{id}/edit/{paymentid}', ['as' => 'admin.payment.orders.edit',    'uses' => 'PurchaseSalePaymentController@SalePaymentedit']);

        Route::post('payment/orders/{id}/{payment_id}', ['as' => 'admin.payment.orders.update',    'uses' => 'PurchaseSalePaymentController@SalePaymentUpdate']);

        Route::get('payment/orders/{id}/confirm-delete', ['as' => 'admin.payment.orders.confirm-delete',   'uses' => 'PurchaseSalePaymentController@getModalDeleteSalePayment']);

        Route::get('payment/orders/{id}/delete', ['as' => 'admin.payment.orders.delete',           'uses' => 'PurchaseSalePaymentController@destroySalePayment']);
//for trash invoice
Route::get('invoicetrash', ['as' => 'admin.invoicetrash.index', 'uses' => 'InvoiceTrashController@index']);



        // For Invoice
        //niraj invoice validation
        Route::get('invoice/AjaxValidation/{productid}',  ['as' => 'admin.invoice.validation',           'uses' => 'InvoiceController@ajaxvalidation']);
        //end
        Route::get('invoice1', ['as' => 'admin.invoice.index', 'uses' => 'InvoiceController@index']);
        Route::get('invoice/detailindex', ['as' => 'admin.invoice.detailindex', 'uses' => 'InvoiceController@detailindex']);
        Route::get('invoice/detail/{id}', ['as' => 'admin.invoice.detail', 'uses' => 'InvoiceController@detail']);
        Route::get('invoice/create', ['as' => 'admin.invoice.create', 'uses' => 'InvoiceController@create']);

        Route::post('invoice/create', ['as' => 'admin.invoice.store', 'uses' => 'InvoiceController@store']);

        Route::get('invoice1/{id}', ['as' => 'admin.invoice.show', 'uses' => 'InvoiceController@show']);

        Route::get('invoice/void/{id}', ['as' => 'admin.salesaccount.void', 'uses' => 'InvoiceController@invoiceVoid']);
        Route::get('invoice/duepayment', ['as' => 'admin.invoice.duepayment',   'uses' => 'InvoiceController@duepayment']);


        Route::post('invoice/void/{id}', ['as' => 'admin.salesaccount.mkvoid', 'uses' => 'InvoiceController@MakeVoid']);
        Route::get('invoice/{orderId}/confirm-invoice', ['as' => 'admin.invoice.confirm-invoice',   'uses' => 'InvoiceController@getModalConverttoInvoice']);

        Route::post('invoice/{id}', ['as' => 'admin.invoice.postOrdertoInvoice',            'uses' => 'InvoiceController@postOrdertoInvoice']);

        Route::get('invoice/{id}/change', ['as' => 'admin.invoice.change', 'uses' => 'InvoiceController@postOrdertoInvoice']);
        Route::get('invoice/print/{id}', ['as' => 'admin.invoice.print',  'uses' => 'InvoiceController@printInvoice']);
        Route::get('invoice/generatePDF/{id}', ['as' => 'admin.invoice.generatePDF',   'uses' => 'InvoiceController@generatePDF']);
        Route::get('invoice/payment/{id}', ['as' => 'admin.invoice.payment',   'uses' => 'InvoiceController@makepayment']);
        Route::get('payment/invoice/{id}/create', ['as' => 'admin.payment.invoice.create',    'uses' => 'InvoiceController@invoicePaymentcreate']);
        Route::post('payment/invoice/{id}/create', ['as' => 'admin.payment.invoice.pstcreate',    'uses' => 'InvoiceController@InvoicePaymentPost']);
        Route::get('payment/invoice/{id}/show', ['as' => 'admin.payment.invoice.show',    'uses' => 'InvoiceController@invoicePaymentshow']);

        Route::get('taxinvoice/renewals', ['as' => 'admin.tax-invoice.renewals',           'uses' => 'InvoiceController@renewals']);

        // For Purchase
        Route::get('purchase/AjaxValidation/{productid}',  ['as' => 'admin.purchase.validation',           'uses' => 'PurchaseController@ajaxvalidation']);
        Route::get('purchase', ['as' => 'admin.purchase.index',            'uses' => 'PurchaseController@index']);
        Route::get('purchase/detailindex', ['as' => 'admin.purchase.detailindex',            'uses' => 'PurchaseController@detailindex']);
        Route::get('purchase/detail/{orderId}', ['as' => 'admin.purchase.detail',            'uses' => 'PurchaseController@detail']);

        Route::get('purchase/create', ['as' => 'admin.purchase.create',           'uses' => 'PurchaseController@create']);
        Route::post('purchase', ['as' => 'admin.purchase.store',            'uses' => 'PurchaseController@store']);
        Route::get('purchase/{orderId}/edit', ['as' => 'admin.purchase.edit',             'uses' => 'PurchaseController@edit']);
        Route::post('purchase/reference-validation', 'PurchaseController@referenceValidation');

        Route::post('ajax_purchase_status', ['as' => 'admin.ajax_purchase_status', 'uses' => 'PurchaseSalePaymentController@ajaxPurchaseStatus']);

        Route::get('purchase/paymentslist', ['as' => 'admin.purch.paymentslist',  'uses' => 'PurchaseController@PurchasePayment']);

        //for purchase payment

        Route::get('payment/purchase/{id}', ['as' => 'admin.payment.purchase.list',     'uses' => 'PurchaseSalePaymentController@PurchasePaymentlist']);
        Route::get('payment/purchase/{id}/create', ['as' => 'admin.payment.purchase.create',    'uses' => 'PurchaseSalePaymentController@PurchasePaymentcreate']);
        Route::post('payment/purchase/{id}', ['as' => 'admin.payment.purchase.store',    'uses' => 'PurchaseSalePaymentController@PurchasePaymentpost']);
        Route::get('payment/purchase/{id}/edit/{paymentid}', ['as' => 'admin.payment.purchase.edit',    'uses' => 'PurchaseSalePaymentController@PurchasePaymentedit']);
        Route::post('payment/purchase/{id}/{payment_id}', ['as' => 'admin.payment.purchase.update',    'uses' => 'PurchaseSalePaymentController@PurchasePaymentUpdate']);
        Route::get('payment/purchase/{id}/confirm-delete', ['as' => 'admin.payment.purchase.confirm-delete',   'uses' => 'PurchaseSalePaymentController@getModalDelete']);
        Route::get('payment/purchase/{id}/delete', ['as' => 'admin.payment.purchase.orders.delete',           'uses' => 'PurchaseSalePaymentController@destroy']);

        // Knowledge routes
        Route::post('knowledge/enableSelected', ['as' => 'admin.knowledge.enable-selected',  'uses' => 'KnowledgeController@enableSelected']);
        Route::post('knowledge/disableSelected', ['as' => 'admin.knowledge.disable-selected', 'uses' => 'KnowledgeController@disableSelected']);
        Route::get('knowledge/search', ['as' => 'admin.knowledge.search',           'uses' => 'KnowledgeController@searchByName']);
        Route::post('knowledge/getInfo', ['as' => 'admin.knowledge.get-info',         'uses' => 'KnowledgeController@getInfo']);
        Route::post('knowledge', ['as' => 'admin.knowledge.store',            'uses' => 'KnowledgeController@store']);

        Route::get('knowledge', ['as' => 'admin.knowledge.index',            'uses' => 'KnowledgeController@index']);
        Route::get('knowledge/cat/{cat_id}', ['as' => 'admin.knowledge.category',            'uses' => 'KnowledgeController@category']);

        Route::get('knowledge/create', ['as' => 'admin.knowledge.create',           'uses' => 'KnowledgeController@create']);
        Route::get('knowledge/{knowledgeId}', ['as' => 'admin.knowledge.show',             'uses' => 'KnowledgeController@show']);
        Route::patch('knowledge/{knowledgeId}', ['as' => 'admin.knowledge.patch',            'uses' => 'KnowledgeController@update']);
        Route::put('knowledge/{knowledgeId}', ['as' => 'admin.knowledge.update',           'uses' => 'KnowledgeController@update']);
        Route::delete('knowledge/{knowledgeId}', ['as' => 'admin.knowledge.destroy',          'uses' => 'KnowledgeController@destroy']);
        Route::get('knowledge/{knowledgeId}/edit', ['as' => 'admin.knowledge.edit',             'uses' => 'KnowledgeController@edit']);
        Route::get('knowledge/{knowledgeId}/confirm-delete', ['as' => 'admin.knowledge.confirm-delete',   'uses' => 'KnowledgeController@getModalDelete']);
        Route::get('knowledge/{knowledgeId}/delete', ['as' => 'admin.knowledge.delete',           'uses' => 'KnowledgeController@destroy']);
        Route::get('knowledge/{knowledgeId}/enable', ['as' => 'admin.knowledge.enable',           'uses' => 'KnowledgeController@enable']);
        Route::get('knowledge/{knowledgeId}/disable', ['as' => 'admin.knowledge.disable',          'uses' => 'KnowledgeController@disable']);

        //Transfer Leads
        Route::get('transfer_lead/{lead_id}', ['as' => 'admin.lead.transfer',           'uses' => 'LeadsController@transferLead']);
        Route::post('transfer_lead/post-transfer', ['as' => 'admin.lead.transfer-update',         'uses' => 'LeadsController@postTransferLead']);

        //Convert Leads
        Route::get('convert_lead/{lead_id}', ['as' => 'admin.lead.convert',           'uses' => 'LeadsController@convertLead']);
        Route::post('convert_lead/post-transfer', ['as' => 'admin.lead.convert-update',         'uses' => 'LeadsController@postConvertLead']);

        Route::get('convert_lead_clients/{lead_id}', ['as' => 'admin.lead.convert_lead_clients', 'uses' => 'LeadsController@convertToClients']);
        Route::get('convert_lead_clients_confirm/{lead_id}', ['as' => 'admin.lead.convert_lead_clients-confirm', 'uses' => 'LeadsController@ConfirmconvertToClients']);

        //Query Leads
        Route::get('lead_query_list/{lead_id}', ['as' => 'admin.lead.query_list',           'uses' => 'LeadsController@listingquery']);

        Route::get('lead_query/{lead_id}', ['as' => 'admin.lead.query',           'uses' => 'LeadsController@queryLead']);

        Route::post('lead_query/create/{lead_id}', ['as' => 'admin.lead.query-create',         'uses' => 'LeadsController@postQueryLead']);

        Route::get('lead_query/edit/{query_id}', ['as' => 'admin.lead.query_edit',           'uses' => 'LeadsController@queryLeadEdit']);

        Route::post('lead_query/update/{query_id}', ['as' => 'admin.lead.query-update',         'uses' => 'LeadsController@updateQueryLead']);

        Route::get('lead_query/confirm-delete/{query_id}', ['as' => 'admin.lead.query-confirm-delete',   'uses' => 'LeadsController@getModalQueryDelete']);

        Route::delete('lead_query/{query_id}', ['as' => 'admin.lead.query-destroy',          'uses' => 'LeadsController@querydestroy']);
        Route::get('lead_query/{query_id}/delete', ['as' => 'admin.lead.query-delete',           'uses' => 'LeadsController@querydestroy']);

        // Knowledge Category routes
        Route::post('knowledgecat/enableSelected', ['as' => 'admin.knowledgecat.enable-selected',  'uses' => 'KnowledgecatController@enableSelected']);
        Route::post('knowledgecat/disableSelected', ['as' => 'admin.knowledgecat.disable-selected', 'uses' => 'KnowledgecatController@disableSelected']);
        Route::get('knowledgecat/search', ['as' => 'admin.knowledgecat.search',           'uses' => 'KnowledgecatController@searchByName']);
        Route::post('knowledgecat/getInfo', ['as' => 'admin.knowledgecat.get-info',         'uses' => 'KnowledgecatController@getInfo']);
        Route::post('knowledgecat', ['as' => 'admin.knowledgecat.store',            'uses' => 'KnowledgecatController@store']);

        Route::get('knowledgecat', ['as' => 'admin.knowledgecat.index',            'uses' => 'KnowledgecatController@index']);

        Route::get('knowledgecat/create', ['as' => 'admin.knowledgecat.create',           'uses' => 'KnowledgecatController@create']);
        Route::get('knowledgecat/{knowledgeCatId}', ['as' => 'admin.knowledgecat.show',             'uses' => 'KnowledgecatController@show']);
        Route::patch('knowledgecat/{knowledgeCatId}', ['as' => 'admin.knowledgecat.patch',            'uses' => 'KnowledgecatController@update']);
        Route::put('knowledgecat/{knowledgeCatId}', ['as' => 'admin.knowledgecat.update',           'uses' => 'KnowledgecatController@update']);
        Route::delete('knowledgecat/{knowledgeCatId}', ['as' => 'admin.knowledgecat.destroy',          'uses' => 'KnowledgecatController@destroy']);
        Route::get('knowledgecat/{knowledgeCatId}/edit', ['as' => 'admin.knowledgecat.edit',             'uses' => 'KnowledgecatController@edit']);
        Route::get('knowledgecat/{knowledgeCatId}/confirm-delete', ['as' => 'admin.knowledgecat.confirm-delete',   'uses' => 'KnowledgecatController@getModalDelete']);
        Route::get('knowledgecat/{knowledgeCatId}/delete', ['as' => 'admin.knowledgecat.delete',           'uses' => 'KnowledgecatController@destroy']);
        Route::get('knowledgecat/{knowledgeCatId}/enable', ['as' => 'admin.knowledgecat.enable',           'uses' => 'KnowledgecatController@enable']);
        Route::get('knowledgecat/{knowledgeCatId}/disable', ['as' => 'admin.knowledgecat.disable',          'uses' => 'KnowledgecatController@disable']);

        // communication routes
        Route::post('communication/enableSelected', ['as' => 'admin.communication.enable-selected',  'uses' => 'CommunicationController@enableSelected']);
        Route::post('communication/disableSelected', ['as' => 'admin.communication.disable-selected', 'uses' => 'CommunicationController@disableSelected']);
        Route::get('communication/search', ['as' => 'admin.communication.search',           'uses' => 'CommunicationController@searchByName']);
        Route::post('communication/getInfo', ['as' => 'admin.communication.get-info',         'uses' => 'CommunicationController@getInfo']);
        Route::post('communication', ['as' => 'admin.communication.store',            'uses' => 'CommunicationController@store']);
        Route::get('communication', ['as' => 'admin.communication.index',            'uses' => 'CommunicationController@index']);
        Route::get('communication/create', ['as' => 'admin.communication.create',           'uses' => 'CommunicationController@create']);
        Route::get('communication/{communicationId}', ['as' => 'admin.communication.show',             'uses' => 'CommunicationController@show']);
        Route::patch('communication/{communicationId}', ['as' => 'admin.communication.patch',            'uses' => 'CommunicationController@update']);
        Route::put('communication/{communicationId}', ['as' => 'admin.communication.update',           'uses' => 'CommunicationController@update']);
        Route::delete('communication/{communicationId}', ['as' => 'admin.communication.destroy',          'uses' => 'CommunicationController@destroy']);
        Route::get('communication/{communicationId}/edit', ['as' => 'admin.communication.edit',             'uses' => 'CommunicationController@edit']);
        Route::get('communication/{communicationId}/confirm-delete', ['as' => 'admin.communication.confirm-delete',   'uses' => 'CommunicationController@getModalDelete']);
        Route::get('communication/{communicationId}/delete', ['as' => 'admin.communication.delete',           'uses' => 'CommunicationController@destroy']);
        Route::get('communication/{communicationId}/enable', ['as' => 'admin.communication.enable',           'uses' => 'CommunicationController@enable']);
        Route::get('communication/{communicationId}/disable', ['as' => 'admin.communication.disable',          'uses' => 'CommunicationController@disable']);

        // organization routes
        Route::post('organization/enableSelected', ['as' => 'admin.organization.enable-selected',  'uses' => 'OrganizationController@enableSelected']);
        Route::post('organization/disableSelected', ['as' => 'admin.organization.disable-selected', 'uses' => 'OrganizationController@disableSelected']);
        Route::get('organization/search', ['as' => 'admin.organization.search',           'uses' => 'OrganizationController@searchByName']);
        Route::post('organization/getInfo', ['as' => 'admin.organization.get-info',         'uses' => 'OrganizationController@getInfo']);
        Route::post('organization', ['as' => 'admin.organization.store',            'uses' => 'OrganizationController@store']);
        Route::get('organization', ['as' => 'admin.organization.index',            'uses' => 'OrganizationController@index']);
        Route::get('organization/create', ['as' => 'admin.organization.create',           'uses' => 'OrganizationController@create']);
        Route::get('organization/{organizationId}', ['as' => 'admin.organization.show',             'uses' => 'OrganizationController@show']);
        Route::patch('organization/{organizationId}', ['as' => 'admin.organization.patch',            'uses' => 'OrganizationController@update']);
        Route::put('organization/{organizationId}', ['as' => 'admin.organization.update',           'uses' => 'OrganizationController@update']);
        Route::delete('organization/{organizationId}/delete', ['as' => 'admin.organization.destroy',          'uses' => 'OrganizationController@destroy']);
        Route::get('organization/{organizationId}/edit', ['as' => 'admin.organization.edit',             'uses' => 'OrganizationController@edit']);
        Route::get('organization/{organizationId}/confirm-delete', ['as' => 'admin.organization.confirm-delete',   'uses' => 'OrganizationController@getModalDelete']);
        Route::get('organization/{organizationId}/delete', ['as' => 'admin.organization.delete',           'uses' => 'OrganizationController@destroy']);
        Route::get('organization/{organizationId}/enable', ['as' => 'admin.organization.enable',           'uses' => 'OrganizationController@enable']);
        Route::get('organization/{organizationId}/disable', ['as' => 'admin.organization.disable',          'uses' => 'OrganizationController@disable']);

        Route::post('/ajax_org', ['as' => 'admin.organization.ajaxorg',   'uses' => 'OrganizationController@ajaxUpdateOrgDropdown']);

        // LeadTypes routes
        Route::post('leadtypes/enableSelected', ['as' => 'admin.leadtypes.enable-selected',  'uses' => 'LeadTypesController@enableSelected']);
        Route::post('leadtypes/disableSelected', ['as' => 'admin.leadtypes.disable-selected', 'uses' => 'LeadTypesController@disableSelected']);
        Route::get('leadtypes/search', ['as' => 'admin.leadtypes.search',           'uses' => 'LeadTypesController@searchByName']);
        Route::post('leadtypes/getInfo', ['as' => 'admin.leadtypes.get-info',         'uses' => 'LeadTypesController@getInfo']);
        Route::post('leadtypes', ['as' => 'admin.leadtypes.store',            'uses' => 'LeadTypesController@store']);
        Route::get('leadtypes', ['as' => 'admin.leadtypes.index',            'uses' => 'LeadTypesController@index']);
        Route::get('leadtypes/create', ['as' => 'admin.leadtypes.create',           'uses' => 'LeadTypesController@create']);
        Route::get('leadtypes/{leadtypeId}', ['as' => 'admin.leadtypes.show',             'uses' => 'LeadTypesController@show']);
        Route::patch('leadtypes/{leadtypeId}', ['as' => 'admin.leadtypes.patch',            'uses' => 'LeadTypesController@update']);
        Route::put('leadtypes/{leadtypeId}', ['as' => 'admin.leadtypes.update',           'uses' => 'LeadTypesController@update']);
        Route::delete('leadtypes/{leadtypeId}', ['as' => 'admin.leadtypes.destroy',          'uses' => 'LeadTypesController@destroy']);
        Route::get('leadtypes/{leadtypeId}/edit', ['as' => 'admin.leadtypes.edit',             'uses' => 'LeadTypesController@edit']);
        Route::get('leadtypes/{leadtypeId}/confirm-delete', ['as' => 'admin.leadtypes.confirm-delete',   'uses' => 'LeadTypesController@getModalDelete']);
        Route::get('leadtypes/{leadtypeId}/delete', ['as' => 'admin.leadtypes.delete',           'uses' => 'LeadTypesController@destroy']);
        Route::get('leadtypes/{leadtypeId}/enable', ['as' => 'admin.leadtypes.enable',           'uses' => 'LeadTypesController@enable']);
        Route::get('leadtypes/{leadtypeId}/disable', ['as' => 'admin.leadtypes.disable',          'uses' => 'LeadTypesController@disable']);

        // Lead Notes Routes
        // Set the notes for the lead - Ajax
        Route::post('leadnotes', ['as' => 'admin.leadnotes.store',            'uses' => 'LeadNotesController@store']);
        Route::get('leadnotes/{leadnoteId}/confirm-delete', ['as' => 'admin.leadnotes.confirm-delete',   'uses' => 'LeadNotesController@getModalDelete']);
        Route::get('leadnotes/{leadnoteId}/delete', ['as' => 'admin.leadnotes.delete',           'uses' => 'LeadNotesController@destroy']);

        // Lead Files Routes
        // Set the file for the lead - Ajax
        Route::post('leadfiles', ['as' => 'admin.leadfiles.store',            'uses' => 'LeadFilesController@store']);
        Route::get('leadfiles/{leadfileId}/confirm-delete', ['as' => 'admin.leadfiles.confirm-delete',   'uses' => 'LeadFilesController@getModalDelete']);
        Route::get('leadfiles/{leadfileId}/delete', ['as' => 'admin.leadfiles.delete',           'uses' => 'LeadFilesController@destroy']);

        // LeadStatus routes
        Route::post('leadstatus/enableSelected', ['as' => 'admin.leadstatus.enable-selected',  'uses' => 'LeadStatusController@enableSelected']);
        Route::post('leadstatus/disableSelected', ['as' => 'admin.leadstatus.disable-selected', 'uses' => 'LeadStatusController@disableSelected']);
        Route::get('leadstatus/search', ['as' => 'admin.leadstatus.search',           'uses' => 'LeadStatusController@searchByName']);
        Route::post('leadstatus/getInfo', ['as' => 'admin.leadstatus.get-info',         'uses' => 'LeadStatusController@getInfo']);
        Route::post('leadstatus', ['as' => 'admin.leadstatus.store',            'uses' => 'LeadStatusController@store']);
        Route::get('leadstatus', ['as' => 'admin.leadstatus.index',            'uses' => 'LeadStatusController@index']);
        Route::get('leadstatus/create', ['as' => 'admin.leadstatus.create',           'uses' => 'LeadStatusController@create']);
        Route::get('leadstatus/{leadstatusId}', ['as' => 'admin.leadstatus.show',             'uses' => 'LeadStatusController@show']);
        Route::patch('leadstatus/{leadstatusId}', ['as' => 'admin.leadstatus.patch',            'uses' => 'LeadStatusController@update']);
        Route::put('leadstatus/{leadstatusId}', ['as' => 'admin.leadstatus.update',           'uses' => 'LeadStatusController@update']);
        Route::delete('leadstatus/{leadstatusId}', ['as' => 'admin.leadstatus.destroy',          'uses' => 'LeadStatusController@destroy']);
        Route::get('leadstatus/{leadstatusId}/edit', ['as' => 'admin.leadstatus.edit',             'uses' => 'LeadStatusController@edit']);
        Route::get('leadstatus/{leadstatusId}/confirm-delete', ['as' => 'admin.leadstatus.confirm-delete',   'uses' => 'LeadStatusController@getModalDelete']);
        Route::get('leadstatus/{leadstatusId}/delete', ['as' => 'admin.leadstatus.delete',           'uses' => 'LeadStatusController@destroy']);
        Route::get('leadstatus/{leadstatusId}/enable', ['as' => 'admin.leadstatus.enable',           'uses' => 'LeadStatusController@enable']);
        Route::get('leadstatus/{leadstatusId}/disable', ['as' => 'admin.leadstatus.disable',          'uses' => 'LeadStatusController@disable']);

        // Fiscalyear routes
        Route::post('fiscalyear/enableSelected', ['as' => 'admin.fiscalyear.enable-selected',  'uses' => 'FiscalyearController@enableSelected']);
        Route::post('fiscalyear/disableSelected', ['as' => 'admin.fiscalyear.disable-selected', 'uses' => 'FiscalyearController@disableSelected']);
        Route::get('fiscalyear/search', ['as' => 'admin.fiscalyear.search',           'uses' => 'FiscalyearController@searchByName']);
        Route::post('fiscalyear/getInfo', ['as' => 'admin.fiscalyear.get-info',         'uses' => 'FiscalyearController@getInfo']);
        Route::post('fiscalyear', ['as' => 'admin.fiscalyear.store',            'uses' => 'FiscalyearController@store']);
        Route::get('fiscalyear', ['as' => 'admin.fiscalyear.index',            'uses' => 'FiscalyearController@index']);
        Route::get('fiscalyear/create', ['as' => 'admin.fiscalyear.create',           'uses' => 'FiscalyearController@create']);
        Route::get('fiscalyear/{fiscalyearId}', ['as' => 'admin.fiscalyear.show',             'uses' => 'FiscalyearController@show']);
        Route::patch('fiscalyear/{fiscalyearId}', ['as' => 'admin.fiscalyear.patch',            'uses' => 'FiscalyearController@update']);
        Route::put('fiscalyear/{fiscalyearId}', ['as' => 'admin.fiscalyear.update',           'uses' => 'FiscalyearController@update']);
        Route::delete('fiscalyear/{fiscalyearId}', ['as' => 'admin.fiscalyear.destroy',          'uses' => 'FiscalyearController@destroy']);
        Route::get('fiscalyear/{fiscalyearId}/edit', ['as' => 'admin.fiscalyear.edit',             'uses' => 'FiscalyearController@edit']);
        Route::get('fiscalyear/{fiscalyearId}/confirm-delete', ['as' => 'admin.fiscalyear.confirm-delete',   'uses' => 'FiscalyearController@getModalDelete']);
        Route::get('fiscalyear/{fiscalyearId}/delete', ['as' => 'admin.fiscalyear.delete',           'uses' => 'FiscalyearController@destroy']);
        Route::get('fiscalyear/{fiscalyearId}/enable', ['as' => 'admin.fiscalyear.enable',           'uses' => 'FiscalyearController@enable']);
        Route::get('fiscalyear/{fiscalyearId}/disable', ['as' => 'admin.fiscalyear.disable',          'uses' => 'FiscalyearController@disable']);

        //leave year
        Route::get('leaveyear', ['as' => 'admin.leaveyear.index',            'uses' => 'LeaveYearController@index']);
        Route::get('leaveyear/create', ['as' => 'admin.leaveyear.create',           'uses' => 'LeaveYearController@create']);
        Route::post('leaveyear', ['as' => 'admin.leaveyear.store',            'uses' => 'LeaveYearController@store']);
        Route::get('leaveyear/{leaveyearId}/edit', ['as' => 'admin.leaveyear.edit',             'uses' => 'LeaveYearController@edit']);
        Route::patch('leaveyear/{leaveyearId}', ['as' => 'admin.leaveyear.update',           'uses' => 'LeaveYearController@update']);
        Route::get('leaveyear/{leaveyearId}/confirm-delete', ['as' => 'admin.leaveyear.confirm-delete',   'uses' => 'LeaveYearController@getModalDelete']);
        Route::get('leaveyear/{leaveyearId}/delete', ['as' => 'admin.leaveyear.delete',           'uses' => 'LeaveYearController@destroy']);
        Route::post('leaveyear/enableSelected', ['as' => 'admin.leaveyear.enable-selected',  'uses' => 'LeaveYearController@enableSelected']);
        // Product Category routes
        Route::post('productcats/enableSelected', ['as' => 'admin.productcats.enable-selected',  'uses' => 'ProductCatsController@enableSelected']);
        Route::post('productcats/disableSelected', ['as' => 'admin.productcats.disable-selected', 'uses' => 'ProductCatsController@disableSelected']);
        Route::get('productcats/search', ['as' => 'admin.productcats.search',           'uses' => 'ProductCatsController@searchByName']);
        Route::post('productcats/getInfo', ['as' => 'admin.productcats.get-info',         'uses' => 'ProductCatsController@getInfo']);
        Route::post('productcats', ['as' => 'admin.productcats.store',            'uses' => 'ProductCatsController@store']);
        Route::get('productcats', ['as' => 'admin.productcats.index',            'uses' => 'ProductCatsController@index']);
        Route::get('productcats/create', ['as' => 'admin.productcats.create',           'uses' => 'ProductCatsController@create']);
        Route::get('productcats/{productcatsId}', ['as' => 'admin.productcats.show',             'uses' => 'ProductCatsController@show']);
        Route::patch('productcats/{productcatsId}', ['as' => 'admin.productcats.patch',            'uses' => 'ProductCatsController@update']);
        Route::put('productcats/{productcatsId}', ['as' => 'admin.productcats.update',           'uses' => 'ProductCatsController@update']);
        Route::delete('productcats/{productcatsId}', ['as' => 'admin.productcats.destroy',          'uses' => 'ProductCatsController@destroy']);
        Route::get('productcats/{productcatsId}/edit', ['as' => 'admin.productcats.edit',             'uses' => 'ProductCatsController@edit']);
        Route::get('productcats/{productcatsId}/confirm-delete', ['as' => 'admin.productcats.confirm-delete',   'uses' => 'ProductCatsController@getModalDelete']);
        Route::get('productcats/{productcatsId}/delete', ['as' => 'admin.productcats.delete',           'uses' => 'ProductCatsController@destroy']);
        Route::get('productcats/{productcatsId}/enable', ['as' => 'admin.productcats.enable',           'uses' => 'ProductCatsController@enable']);
        Route::get('productcats/{productcatsId}/disable', ['as' => 'admin.productcats.disable',          'uses' => 'ProductCatsController@disable']);

        // LeadEnquiryMode routes
        Route::post('leadenquirymode/enableSelected', ['as' => 'admin.leadenquirymode.enable-selected',  'uses' => 'LeadEnquiryModeController@enableSelected']);
        Route::post('leadenquirymode/disableSelected', ['as' => 'admin.leadenquirymode.disable-selected', 'uses' => 'LeadEnquiryModeController@disableSelected']);
        Route::get('leadenquirymode/search', ['as' => 'admin.leadenquirymode.search',           'uses' => 'LeadEnquiryModeController@searchByName']);
        Route::post('leadenquirymode/getInfo', ['as' => 'admin.leadenquirymode.get-info',         'uses' => 'LeadEnquiryModeController@getInfo']);
        Route::post('leadenquirymode', ['as' => 'admin.leadenquirymode.store',            'uses' => 'LeadEnquiryModeController@store']);
        Route::get('leadenquirymode', ['as' => 'admin.leadenquirymode.index',            'uses' => 'LeadEnquiryModeController@index']);
        Route::get('leadenquirymode/create', ['as' => 'admin.leadenquirymode.create',           'uses' => 'LeadEnquiryModeController@create']);
        Route::get('leadenquirymode/{leadenquirymodeId}', ['as' => 'admin.leadenquirymode.show',             'uses' => 'LeadEnquiryModeController@show']);
        Route::patch('leadenquirymode/{leadenquirymodeId}', ['as' => 'admin.leadenquirymode.patch',            'uses' => 'LeadEnquiryModeController@update']);
        Route::put('leadenquirymode/{leadenquirymodeId}', ['as' => 'admin.leadenquirymode.update',           'uses' => 'LeadEnquiryModeController@update']);
        Route::delete('leadenquirymode/{leadenquirymodeId}', ['as' => 'admin.leadenquirymode.destroy',          'uses' => 'LeadEnquiryModeController@destroy']);
        Route::get('leadenquirymode/{leadenquirymodeId}/edit', ['as' => 'admin.leadenquirymode.edit',             'uses' => 'LeadEnquiryModeController@edit']);
        Route::get('leadenquirymode/{leadenquirymodeId}/confirm-delete', ['as' => 'admin.leadenquirymode.confirm-delete',   'uses' => 'LeadEnquiryModeController@getModalDelete']);
        Route::get('leadenquirymode/{leadenquirymodeId}/delete', ['as' => 'admin.leadenquirymode.delete',           'uses' => 'LeadEnquiryModeController@destroy']);
        Route::get('leadenquirymode/{leadenquirymodeId}/enable', ['as' => 'admin.leadenquirymode.enable',           'uses' => 'LeadEnquiryModeController@enable']);
        Route::get('leadenquirymode/{leadenquirymodeId}/disable', ['as' => 'admin.leadenquirymode.disable',          'uses' => 'LeadEnquiryModeController@disable']);

        //purchase routes
        Route::get('expenses', ['as' => 'admin.expenses.index',            'uses' => 'ExpensesController@index']);
        Route::get('expenses/create', ['as' => 'admin.expenses.create',           'uses' => 'ExpensesController@create']);
        Route::get('expenses/{courseId}', ['as' => 'admin.expenses.show',             'uses' => 'ExpensesController@show']);
        Route::post('expenses', ['as' => 'admin.expenses.store',            'uses' => 'ExpensesController@store']);
        Route::get('expenses/{courseId}/edit', ['as' => 'admin.expenses.edit',             'uses' => 'ExpensesController@edit']);
        Route::patch('expenses/{courseId}', ['as' => 'admin.expenses.patch',            'uses' => 'ExpensesController@update']);
        Route::put('expenses/{courseId}', ['as' => 'admin.expenses.update',           'uses' => 'ExpensesController@update']);
        Route::get('expenses/{courseId}/confirm-delete', ['as' => 'admin.expenses.confirm-delete',   'uses' => 'ExpensesController@getModalDelete']);
        Route::get('expenses/{courseId}/delete', ['as' => 'admin.expenses.delete',           'uses' => 'ExpensesController@destroy']);
        Route::get('expenses/showModal/{courseId}', ['as' => 'admin.expenses.showModal',             'uses' => 'ExpensesController@showModal']);
        Route::get('download/expenses/{type}/index', 'DownloadIndexController@downloadExpense');
        Route::post('expenses/postCreateModal', ['as' => 'admin.expenses.postModal',             'uses' => 'ExpensesController@postCreateModal']);
        Route::get('purchasetrash', ['as' => 'admin.purchase.trash',            'uses' => 'PurchaseTrashController@index']);

        Route::get('purchase', ['as' => 'admin.purchase.index',            'uses' => 'PurchaseController@index']);
        Route::get('purchase/create', ['as' => 'admin.purchase.create',           'uses' => 'PurchaseController@create']);
        Route::post('purchase', ['as' => 'admin.purchase.store',            'uses' => 'PurchaseController@store']);
        Route::get('purchase/{orderId}', ['as' => 'admin.purchase.show',             'uses' => 'PurchaseController@show']);
        Route::get('purchase/{orderId}/edit', ['as' => 'admin.purchase.edit',             'uses' => 'PurchaseController@edit']);
        Route::patch('purchase/{orderId}', ['as' => 'admin.purchase.patch',            'uses' => 'PurchaseController@update']);
        Route::put('purchase/{orderId}', ['as' => 'admin.purchase.update',           'uses' => 'PurchaseController@update']);
        Route::get('purchase/{orderId}/confirm-delete', ['as' => 'admin.purchase.confirm-delete',   'uses' => 'PurchaseController@getModalDelete']);
        Route::get('purchase/{orderId}/delete', ['as' => 'admin.purchase.delete',           'uses' => 'PurchaseController@destroy']);
        Route::post('purchase/reference-validation', 'PurchaseController@referenceValidation');
        Route::get('purchase/print/{id}', ['as' => 'admin.purchase.print',  'uses' => 'PurchaseController@printInvoice']);
        Route::get('purchase/generatePDF/{id}', ['as' => 'admin.purchase.generatePDF',   'uses' => 'PurchaseController@generatePDF']);

        // Attendance
        Route::get('attendance_report', ['as' => 'admin.attendance_report', 'uses' => 'AttendanceController@index']);
        Route::post('attendance_report', ['as' => 'admin.attendance_report_show', 'uses' => 'AttendanceController@show']);

        Route::get('attendance_report/detail', ['as' => 'admin.attendance_report.detail', 'uses' => 'AttendanceController@detailreport']);
        Route::post('attendance_report/detail', ['as' => 'admin.attendance_report.shdetail', 'uses' => 'AttendanceController@detailreportShow']);

        Route::get('attendance_report/print/{department}/{date_in}', ['as' => 'admin.attendance_report.print',  'uses' => 'AttendanceController@printAttendance']);
        Route::get('attendance_report/generatePDF/{department}/{date_in}', ['as' => 'admin.attendance_report.generatePDF',   'uses' => 'AttendanceController@generatePDF']);

        Route::get('time_history', ['as' => 'admin.time_history', 'uses' => 'AttendanceController@timeHistory']);
        Route::post('time_history', ['as' => 'admin.time_history_post', 'uses' => 'AttendanceController@timeHistoryPost']);
        Route::get('time_history/print/{user_id}/{date_in}', ['as' => 'admin.time_history.print',  'uses' => 'AttendanceController@printTimeHistory']);
        Route::get('time_history/generatePDF/{user_id}/{date_in}', ['as' => 'admin.time_history.generatePDFTimeHistory',   'uses' => 'AttendanceController@generatePDFTimeHistory']);

        Route::get('time_history/edit_time/{clock_id}', ['as' => 'admin.time_history.edit_time',   'uses' => 'AttendanceController@editTime']);
        Route::post('time_history/update_time/{clock_id}', ['as' => 'admin.time_history.updateTime',   'uses' => 'AttendanceController@updateTime']);

        // Route::get('timechange_request', ['as' => 'admin.timechange_request',   'uses' => 'AttendanceController@timeChangeRequest']);

        // Route::get('timechange_request_modal/{clock_history_id}', ['as' => 'admin.timechange_request_modal',   'uses' => 'AttendanceController@timeRequestModal']);
        // Route::post('timechange_request/{clock_history_id}', ['as' => 'admin.timechange_request.update',   'uses' => 'AttendanceController@updateTimeChangeRequest']);

        Route::get('timechange_request', ['as' => 'admin.timechange_request',   'uses' => 'ShiftAttendanceController@timeChangeRequest']);

        Route::get('timechange_request_modal/{clock_history_id}', ['as' => 'admin.timechange_request_modal',   'uses' => 'ShiftAttendanceController@timeRequestModal']);
        Route::post('timechange_request/{clock_history_id}', ['as' => 'admin.timechange_request.update',   'uses' => 'ShiftAttendanceController@updateTimeChangeRequest']);

        Route::get('importexport/attendance_report', ['as' => 'admin.importexport.imexattendance_report', 'uses' => 'AttendanceController@importexport']);
        Route::post('importexport/attendance_report', ['as' => 'admin.importexport.attendance_report', 'uses' => 'AttendanceController@importexportstore']);
        Route::get('downloadExcel/attendance/{type}', ['as' => 'admin.downloadExcel.attendance', 'uses' => 'AttendanceController@downloadexcel']);

        Route::get('all_attendance_report', ['as' => 'admin.all_attendance_report', 'uses' => 'AttendanceController@allattendanceindex']);
        Route::post('all_attendance_report', ['as' => 'admin.all_attendance_report_show', 'uses' => 'AttendanceController@allattendanceshow']);

        Route::get('mark_attendance', ['as' => 'admin.mark_attendance', 'uses' => 'AttendanceController@markAttendance']);

        Route::get('mark_attendance/checkstatus/{emp_id}', 'AttendanceController@view_status');
        Route::post('mark_attendance', ['as' => 'admin.stmark_attendance', 'uses' => 'AttendanceController@storeMarkAttendance']);

        Route::get('bulk/mark_attendance', ['as' => 'admin.bulk.mark_attendance', 'uses' => 'AttendanceController@bulkAttendance']);
        Route::post('bulk/mark_attendance', ['as' => 'admin.bulk.po_mark_attendance', 'uses' => 'AttendanceController@bulkAttendanceNext']);
        Route::post('bulk/mark_attendance/save', ['as' => 'admin.bulk.mark_attendance.save', 'uses' => 'AttendanceController@bulkAttendanceSave']);
        Route::get('markattendence/{user_id}/clockin', ['as' => 'markattendance.clockin',   'uses' => 'ClockController@markAttendanceClockin']);

        Route::get('markattendence/{user_id}/clockout', ['as' => 'markattendance.clockout',   'uses' => 'ClockController@markAttendanceClickout']);

        // Leave Management
        Route::get('leave_management', ['as' => 'admin.leave_management', 'uses' => 'LeaveManagementController@index']);
        Route::post('leave_management', ['as' => 'admin.saveLeaveManagement', 'uses' => 'LeaveManagementController@store']);
        Route::get('leave_management/detail/{id}', ['as' => 'admin.editLeaveManagement', 'uses' => 'LeaveManagementController@edit']);
        Route::get('leave_management/change_status/{applId}/{status}', ['as' => 'admin.editLeaveManagementChangeStatus', 'uses' => 'LeaveManagementController@changeStatus']);
        Route::post('leave_management/set_action/{applId}/{status}', ['as' => 'admin.setStatusLeaveManagement', 'uses' => 'LeaveManagementController@setStatus']);
        Route::get('leave_management_delete/{id}', ['as' => 'admin.deleteLeaveManagement', 'uses' => 'LeaveManagementController@destroy']);

        Route::get('allpendingleaves', ['as' => 'admin.allpendingleaves', 'uses' => 'LeaveManagementController@allpendingleave']);

        Route::get('leavereport', ['as' => 'admin.leavereport', 'uses' => 'LeaveManagementController@leavereport']);

        Route::get('bulk/leave_management', ['as' => 'admin.bulk.leave_management', 'uses' => 'LeaveManagementController@bulkleave']);

        Route::post('bulk/leave_management', ['as' => 'admin.bulk.getleave_management', 'uses' => 'LeaveManagementController@getbulkleave']);
        Route::post('bulk/leave_management/store', ['as' => 'admin.bulk.stleave_management', 'uses' => 'LeaveManagementController@storebulkleave']);
        // leave category
        Route::get('leavecategory', ['as' => 'admin.leavecategory', 'uses' => 'LeaveCategoryController@index']);
        Route::get('leavecategory/create', ['as' => 'admin.leavecategory.create',            'uses' => 'LeaveCategoryController@create']);
        Route::post('leavecategory/store', ['as' => 'admin.leavecategory.store',            'uses' => 'LeaveCategoryController@store']);
        Route::get('leavecategory/{id}/edit', ['as' => 'admin.leavecategory.edit',            'uses' => 'LeaveCategoryController@edit']);
        Route::post('leavecategory/{id}/update', ['as' => 'admin.leavecategory.update',            'uses' => 'LeaveCategoryController@update']);
        Route::get('leavecategory/{caseId}/confirm-delete', ['as' => 'admin.leavecategory.confirm-delete',   'uses' => 'LeaveCategoryController@getModalDelete']);
        Route::get('leavecategory/{caseId}/delete', ['as' => 'admin.leavecategory.delete',           'uses' => 'LeaveCategoryController@destroy']);

        // Ajax User Leave Check
        Route::get('check_user_available_leave', ['as' => 'admin.checkAvailableLeave', 'uses' => 'LeaveManagementController@checkAvailableLeave']);

        // Department and Designation
        Route::get('departments', ['as' => 'admin.departments.index',   'uses' => 'DepartmentController@index']);
        Route::post('departments/store', ['as' => 'admin.departments.store',   'uses' => 'DepartmentController@store']);
        Route::get('departments/{departments_id}/edit', ['as' => 'admin.departments.edit',   'uses' => 'DepartmentController@edit']);
        Route::post('departments/update/{departments_id}', ['as' => 'admin.departments.updateDepartment',   'uses' => 'DepartmentController@updateDepartment']);
        Route::get('departments/edit/{departments_id}/{designations_id}', ['as' => 'admin.departments.editDesignation',   'uses' => 'DepartmentController@editDesignation']);

        Route::get('departments/delete/{departments_id}', ['as' => 'admin.departments.deleteDepartment',   'uses' => 'DepartmentController@deleteDepartment']);
        Route::get('departments/delete_designation/{designations_id}', ['as' => 'admin.departments.deleteDesignation',   'uses' => 'DepartmentController@deleteDesignation']);

        //Project Calendar
        Route::get('/project/calendar', ['as' => 'admin.project.calendar', 'uses' => 'ProjectCalendarController@index']);

        // Holiday
        Route::get('holiday', ['as' => 'admin.holiday', 'uses' => 'HolidayController@index']);
        Route::post('holiday', ['as' => 'admin.saveHoliday', 'uses' => 'HolidayController@store']);
        Route::post('holiday/{id}', ['as' => 'admin.editHoliday', 'uses' => 'HolidayController@edit']);
        Route::post('holiday_update/{id}', ['as' => 'admin.updateHoliday', 'uses' => 'HolidayController@update']);
        Route::get('holiday_delete/{id}', ['as' => 'admin.deleteHoliday', 'uses' => 'HolidayController@destroy']);
        Route::get('holiday/exportpdf/{year}', ['as' => 'admin.holidays.exportpdf', 'uses' => 'HolidayController@DownloadPdf']);
        Route::get('holiday/exportexcel/{year}', ['as' => 'admin.holidays.exportexcel', 'uses' => 'HolidayController@DownloadExcel']);

        //travel request
        Route::get('tarvelrequest', ['as' => 'admin.tarvelrequest.index', 'uses' => 'TarvelRequestController@index']);
        Route::get('tarvelrequest/create', ['as' => 'admin.tarvelrequest.create', 'uses' => 'TarvelRequestController@create']);
        Route::post('tarvelrequest/create', ['as' => 'admin.tarvelrequest.store', 'uses' => 'TarvelRequestController@store']);
        Route::get('tarvelrequest/edit/{id}', ['as' => 'admin.tarvelrequest.edit', 'uses' => 'TarvelRequestController@edit']);
        Route::post('tarvelrequest/edit/{id}', ['as' => 'admin.tarvelrequest.update', 'uses' => 'TarvelRequestController@update']);
        Route::get('tarvelrequest/show/{id}', ['as' => 'admin.tarvelrequest.show', 'uses' => 'TarvelRequestController@show']);
        Route::get('tarvelrequest/confirm-delete/{id}', ['as' => 'admin.tarvelrequest.confirm-delete', 'uses' => 'TarvelRequestController@getModalDelete']);
        Route::get('tarvelrequest/delete/{id}', ['as' => 'admin.tarvelrequest.delete', 'uses' => 'TarvelRequestController@destroy']);

        Route::get('getClientinfo', ['as' => 'admin.getclient.info', 'uses' => 'ClientsController@get_client_info']);

        // Routes for User Targets
        Route::get('userTarget', ['as' => 'admin.userTarget.index',            'uses' => 'UserTargetController@index']);
        Route::get('userTarget/create', ['as' => 'admin.userTarget.create',           'uses' => 'UserTargetController@create']);
        Route::post('userTarget', ['as' => 'admin.userTarget.store',            'uses' => 'UserTargetController@store']);
        Route::get('userTarget/{leadId}/edit', ['as' => 'admin.userTarget.edit',             'uses' => 'UserTargetController@edit']);
        Route::patch('userTarget/{leadId}', ['as' => 'admin.userTarget.update',           'uses' => 'UserTargetController@update']);
        Route::get('userTarget/{leadId}/confirm-delete', ['as' => 'admin.userTarget.confirm-delete',   'uses' => 'UserTargetController@getModalDelete']);
        Route::get('userTarget/{leadId}/delete', ['as' => 'admin.userTarget.delete',           'uses' => 'UserTargetController@destroy']);
        Route::get('userTarget/summary/{year}', ['as' => 'admin.userTarget.summary', 'uses' => 'UserTargetController@summary']);

        // Salary Template
        Route::get('payroll/salary_template', ['as' => 'admin.payroll.salary_template',   'uses' => 'PayrollController@salaryTemplate']);

        Route::post('payroll/salary_template', ['as' => 'admin.payroll.store_salary_template',   'uses' => 'PayrollController@storeSalaryTemplate']);

        Route::get('payroll/salary_template/{salary_template_id}', ['as' => 'admin.payroll.edit_salary_template',   'uses' => 'PayrollController@editSalaryTemplate']);

        Route::get('payroll/salary_template_details/{salary_template_id}', ['as' => 'admin.payroll.show_salary_template',   'uses' => 'PayrollController@showSalaryTemplate']);

        Route::get('payroll/salary_template_delete/{salary_template_id}', ['as' => 'admin.payroll.destroy_salary_template',   'uses' => 'PayrollController@destroySalaryTemplate']);

        Route::get('payroll/generatePdfSalarytemplate/{training_id}', ['as' => 'admin.payroll.generatePdfSalarytemplate',   'uses' => 'PayrollController@generatePdfSalarytemplate']);

        Route::get('payroll/generatePrintSalarytemplate/{training_id}', ['as' => 'admin.payroll.generatePrintSalarytemplate',   'uses' => 'PayrollController@generatePrintSalarytemplate']);

        // Manage Salary Details
        Route::get('payroll/manage_salary_details', ['as' => 'admin.payroll.manage_salary_details',   'uses' => 'PayrollController@manageSalaryDetails']);
        Route::post('payroll/manage_salary_details', ['as' => 'admin.payroll.list_manage_salary_details',   'uses' => 'PayrollController@listSalaryDetails']);
        Route::get('payroll/manage_salary_details/{departments_id}', ['as' => 'admin.payroll.list_department_salary_details',   'uses' => 'PayrollController@listDepartmentSalaryDetails']);
        Route::post('payroll/store_salary_details', ['as' => 'admin.payroll.store_salary_details',   'uses' => 'PayrollController@storeSalaryDetails']);

        // Employee Salary List
        Route::get('payroll/employee_salary_list', ['as' => 'admin.payroll.employee_salary_list',   'uses' => 'PayrollController@employeeSalaryList']);
        Route::get(
            'payroll/manage_emp_salary_template/{payroll_id}',
            [
                'as' => 'admin.payroll.manage_emp_salary_template',
                'uses' => 'PayrollController@manageEmpSalaryTemp',
            ]
        );
        Route::post(
            'payroll/manage_emp_salary_template/{payroll_id}',
            [
                'as' => 'admin.payroll.pstmanage_emp_salary_template',
                'uses' => 'PayrollController@manageEmpSalaryTempPost',
            ]
        );
        Route::get('payroll/salary_delete/{payroll_id}', ['as' => 'admin.payroll.salary_delete',   'uses' => 'PayrollController@destroySalary']);

        Route::get('payroll/salary_details/{payroll_id}', ['as' => 'admin.payroll.show_salary_details',   'uses' => 'PayrollController@showSalaryDetail']);

        Route::get('payroll/generatePdfSalaryDetail/{payroll_id}', ['as' => 'admin.payroll.generatePdfSalaryDetail',   'uses' => 'PayrollController@generatePdfSalaryDetail']);

        //accounting prefix

        Route::get('accountingPrefix', ['as' => 'admin.accountingPrefix.index', 'uses' => 'AccountingPrefixController@index']);

        Route::get('accountingPrefix/create', ['as' => 'admin.accountingPrefix.create', 'uses' => 'AccountingPrefixController@create']);

        Route::post('accountingPrefix/create', ['as' => 'admin.accountingPrefix.store', 'uses' => 'AccountingPrefixController@store']);

        Route::get('accountingPrefix/edit/{id}', ['as' => 'admin.accountingPrefix.edit', 'uses' => 'AccountingPrefixController@edit']);

        Route::post('accountingPrefix/edit/{id}', ['as' => 'admin.accountingPrefix.update', 'uses' => 'AccountingPrefixController@update']);

        Route::get('accountingPrefix/confirm-delete{id}', ['as' => 'admin.accountingPrefix.confirm-delete', 'uses' => 'AccountingPrefixController@getModalDelete']);

        Route::get('accountingPrefix/delete/{id}', ['as' => 'admin.accountingPrefix.delete', 'uses' => 'AccountingPrefixController@destroy']);

        // Advance Salary
        Route::get('payroll/advance_salary', ['as' => 'admin.advance_salary', 'uses' => 'AdvanceSalaryController@index']);

        Route::post('payroll/advance_salary', ['as' => 'admin.saveAdvanceSalary', 'uses' => 'AdvanceSalaryController@store']);

        Route::post('payroll/advance_salary/{id}', ['as' => 'admin.editAdvanceSalary', 'uses' => 'AdvanceSalaryController@edit']);

        Route::post('payroll/advance_salary_update/{id}', ['as' => 'admin.updateAdvanceSalary', 'uses' => 'AdvanceSalaryController@update']);

        Route::get('payroll/advance_salary_delete/{id}', ['as' => 'admin.deleteAdvanceSalary', 'uses' => 'AdvanceSalaryController@destroy']);

        // Provident Fund
        Route::get('payroll/provident_fund', ['as' => 'admin.provident_fund', 'uses' => 'ProvidentFundController@index']);

        // Over Time
        Route::get('payroll/over_time', ['as' => 'admin.over_time', 'uses' => 'OverTimeController@index']);
        Route::post('payroll/over_time', ['as' => 'admin.saveOverTime', 'uses' => 'OverTimeController@store']);
        Route::post('payroll/over_time/{id}', ['as' => 'admin.editOverTime', 'uses' => 'OverTimeController@edit']);

        Route::post('payroll/over_time_update/{id}', ['as' => 'admin.updateOverTime', 'uses' => 'OverTimeController@update']);
        Route::get('payroll/over_time_delete/{id}', ['as' => 'admin.deleteOverTime', 'uses' => 'OverTimeController@destroy']);

        // Make Payment
        Route::get('payroll/make_payment', ['as' => 'admin.payroll.make_payment.index',   'uses' => 'PaymentController@index']);
        Route::post('payroll/make_payment', ['as' => 'admin.payroll.list_payment',   'uses' => 'PaymentController@listPayment']);
        Route::get('payroll/show_payment_detail/{user_id}/{payment_month}', ['as' => 'admin.payroll.show_payment_detail',   'uses' => 'PaymentController@showPaymentDetail']);
        Route::get('payroll/salary_payment_detail/{salary_payment_id}', ['as' => 'admin.payroll.salary_payment_detail',   'uses' => 'PaymentController@salaryPaymentDetail']);
        Route::get('payroll/make_payment/{user_id}/{departments_id}/{payment_month}', ['as' => 'admin.payroll.make_payment',   'uses' => 'PaymentController@makePayment']);
        Route::post('payroll/make_payment/submit_new_payment', ['as' => 'admin.payroll.submit_new_payment',   'uses' => 'PaymentController@submitNewPayment']);

        // Generate Payslip
        Route::get('payroll/generate_payslip', ['as' => 'admin.payroll.generate_payslip.index',   'uses' => 'PaymentController@generatePaySlip']);
        Route::post('payroll/generate_payslip', ['as' => 'admin.payroll.generate_payslip',   'uses' => 'PaymentController@listPayslip']);
        Route::get('payroll/show_payslip_detail/{user_id}/{payment_month}', ['as' => 'admin.payroll.show_payslip_detail',   'uses' => 'PayslipController@showPaymentDetail']);

        //Run Payroll
        Route::get('payroll/run/step1', ['as' => 'admin.payroll.run_step1',   'uses' => 'PayrollController@run_payroll_step1']);
        Route::post('payroll/run/timecard_review', ['as' => 'admin.payroll.timecard_review',   'uses' => 'PayrollController@run_payroll_timecard_review']);
        Route::post('payroll/run/enter_payroll', ['as' => 'admin.payroll.enter_payroll',   'uses' => 'PayrollController@run_payroll_enter_payroll']);
        Route::post('payroll/run/payroll_summary', ['as' => 'admin.payroll.payroll_summary',   'uses' => 'PayrollController@run_payroll_summary']);
        Route::get('payroll/allowance/{pay_frequency}/{user_id}', 'PayrollController@user_allowance');
        Route::get('payroll/deduction/{pay_frequency}/{user_id}', 'PayrollController@user_deduction');
        Route::get('payroll/bulk/salarydownload/{type}/{payfrequency}', 'PayrollController@dowloadFile');
        Route::get('payroll/run/filter', [
            'as' => 'admin.payroll.filter',
            'uses' => 'PayFrequencyController@run_payroll_filter',
        ]);
        Route::post('payroll/run/filter', [
            'as' => 'admin.payroll.pstfilter',
            'uses' => 'PayFrequencyController@run_payroll_filterPost',
        ]);
        Route::get('payroll/run/userPayfrequecny/{id}', [
            'as' => 'admin.payroll.userPayfrequecny',
            'uses' => 'PayFrequencyController@userPayfrequecny',
        ]);
        //timekeeting setup
        Route::get('timekeep_setup', ['as' => 'admin.attendance.timekeep_setup',   'uses' => 'AttendanceController@timekeep_setup']);
        Route::post('timekeep_setup', ['as' => 'admin.attendance.psttimekeep_setup',   'uses' => 'AttendanceController@timekeep_setup_post']);

        //Employee Award

        Route::get('payroll/employee_award', ['as' => 'admin.payroll.employee_award.index',   'uses' => 'PaymentController@employeeAward']);
        Route::post('payroll/employee_award', ['as' => 'admin.employeeeaward.store', 'uses' => 'PaymentController@employeeAwardStore']);
        Route::get('payroll/employee_award_delete/{id}', ['as' => 'admin.deleteemployeeaward', 'uses' => 'PaymentController@destroyEmployeeAward']);
        Route::post('payroll/employee_award/{id}', ['as' => 'admin.editEmployeeAward', 'uses' => 'PaymentController@editEmployeeAward']);
        Route::post('payroll/employee_award_update/{id}', ['as' => 'admin.updateEmployeeAward', 'uses' => 'PaymentController@updateEmployeeAward']);

        Route::get('payroll/salary_summary', ['as' => 'admin.payroll.salary_summary', 'uses' => 'PayrollController@salary_summary']);
        Route::get('payroll/salary_summary_view', ['as' => 'admin.payroll.salary_summary_view', 'uses' => 'PayrollController@salary_summary_details']);
        Route::get('payroll/salary_summary/{query}/{filter}/{print_type}', ['as' => 'admin.payroll.salary_summary.print', 'uses' => 'PayrollController@salary_summary_print']);

        // Job Application
        Route::get('job_applied', ['as' => 'admin.jobApplication.index',   'uses' => 'JobApplicationController@index']);
        Route::get('job_applied/status/{job_appliactions_id}', ['as' => 'admin.jobApplication.status',   'uses' => 'JobApplicationController@status']);
        Route::post('job_applied/updateStatus/{job_appliactions_id}', ['as' => 'admin.jobApplication.updateStatus',   'uses' => 'JobApplicationController@updateStatus']);
        Route::get('job_applied/show/{job_appliactions_id}', ['as' => 'admin.jobApplication.show',   'uses' => 'JobApplicationController@show']);
        Route::get('job_applied/delete/{job_appliactions_id}', ['as' => 'admin.jobApplication.delete',   'uses' => 'JobApplicationController@delete']);

        // Recruitment Job Posted / Circular
        Route::get('job_posted', ['as' => 'admin.recruitment.index',   'uses' => 'RecruitmentController@index']);
        Route::post('job_posted/save', ['as' => 'admin.recruitment.save',   'uses' => 'RecruitmentController@save']);
        Route::get('job_posted/edit/{job_circular_id}', ['as' => 'admin.recruitment.edit',   'uses' => 'RecruitmentController@edit']);
        Route::get('job_posted/show/{job_circular_id}', ['as' => 'admin.recruitment.show',   'uses' => 'RecruitmentController@show']);
        Route::get('job_posted/delete/{job_circular_id}', ['as' => 'admin.recruitment.delete',   'uses' => 'RecruitmentController@delete']);

        // Announcement
        Route::get('announcements', ['as' => 'admin.announcements.index',   'uses' => 'AnnouncementController@index']);
        Route::post('announcements/save', ['as' => 'admin.announcements.save',   'uses' => 'AnnouncementController@save']);
        Route::get('announcements/edit/{announcements_id}', ['as' => 'admin.announcements.edit',   'uses' => 'AnnouncementController@edit']);
        Route::get('announcements/show/{announcements_id}', ['as' => 'admin.announcements.show',   'uses' => 'AnnouncementController@show']);
        Route::get('announcements/delete/{announcements_id}', ['as' => 'admin.announcements.delete',   'uses' => 'AnnouncementController@delete']);
        Route::get('announcements/print', ['as' => 'admin.announcements.print',  'uses' => 'AnnouncementController@printNow']);
        Route::get('announcements/generatePdf', ['as' => 'admin.announcements.generatePdf',   'uses' => 'AnnouncementController@generatePdf']);

        // read Announcement

        Route::get('close/announcements/{id}', ['as' => 'admin.closeannouncements.index',   'uses' => 'AnnouncementController@ReadAnnouncement']);

        // Projects Category routes
        Route::post('projectscat/enableSelected', ['as' => 'admin.projectscat.enable-selected',  'uses' => 'ProjectscatController@enableSelected']);
        Route::post('projectscat/disableSelected', ['as' => 'admin.projectscat.disable-selected', 'uses' => 'ProjectscatController@disableSelected']);
        Route::get('projectscat/search', ['as' => 'admin.projectscat.search',           'uses' => 'ProjectscatController@searchByName']);
        Route::post('projectscat/getInfo', ['as' => 'admin.projectscat.get-info',         'uses' => 'ProjectscatController@getInfo']);
        Route::post('projectscat', ['as' => 'admin.projectscat.store',            'uses' => 'ProjectscatController@store']);

        Route::get('projectscat', ['as' => 'admin.projectscat.index',            'uses' => 'ProjectscatController@index']);

        Route::get('projectscat/create', ['as' => 'admin.projectscat.create',           'uses' => 'ProjectscatController@create']);
        Route::get('projectscat/{projectsCatId}', ['as' => 'admin.projectscat.show',             'uses' => 'ProjectscatController@show']);
        Route::patch('projectscat/{projectsCatId}', ['as' => 'admin.projectscat.patch',            'uses' => 'ProjectscatController@update']);
        Route::put('projectscat/{projectsCatId}', ['as' => 'admin.projectscat.update',           'uses' => 'ProjectscatController@update']);
        Route::delete('projectscat/{projectsCatId}', ['as' => 'admin.projectscat.destroy',          'uses' => 'ProjectscatController@destroy']);
        Route::get('projectscat/{projectsCatId}/edit', ['as' => 'admin.projectscat.edit',             'uses' => 'ProjectscatController@edit']);
        Route::get('projectscat/{projectsCatId}/confirm-delete', ['as' => 'admin.projectscat.confirm-delete',   'uses' => 'ProjectscatController@getModalDelete']);
        Route::get('projectscat/{projectsCatId}/delete', ['as' => 'admin.projectscat.delete',           'uses' => 'ProjectscatController@destroy']);
        Route::get('projectscat/{projectsCatId}/enable', ['as' => 'admin.projectscat.enable',           'uses' => 'ProjectscatController@enable']);
        Route::get('projectscat/{projectsCatId}/disable', ['as' => 'admin.projectscat.disable',          'uses' => 'ProjectscatController@disable']);

        // For Project Task
        Route::get('project_tasks', ['as' => 'admin.project_task.index',           'uses' => 'ProjectTaskController@index']);

        // Task Category routes
        Route::post('taskscat/enableSelected', ['as' => 'admin.taskscat.enable-selected',  'uses' => 'TaskscatController@enableSelected']);
        Route::post('taskscat/disableSelected', ['as' => 'admin.taskscat.disable-selected', 'uses' => 'TaskscatController@disableSelected']);
        Route::get('taskscat/search', ['as' => 'admin.taskscat.search',           'uses' => 'TaskscatController@searchByName']);
        Route::post('taskscat/getInfo', ['as' => 'admin.taskscat.get-info',         'uses' => 'TaskscatController@getInfo']);
        Route::post('taskscat', ['as' => 'admin.taskscat.store',            'uses' => 'TaskscatController@store']);

        Route::get('taskscat', ['as' => 'admin.taskscat.index',            'uses' => 'TaskscatController@index']);

        Route::get('taskscat/create', ['as' => 'admin.taskscat.create',           'uses' => 'TaskscatController@create']);
        Route::get('taskscat/{tasksCatId}', ['as' => 'admin.taskscat.show',             'uses' => 'TaskscatController@show']);
        Route::patch('taskscat/{tasksCatId}', ['as' => 'admin.taskscat.patch',            'uses' => 'TaskscatController@update']);
        Route::put('taskscat/{tasksCatId}', ['as' => 'admin.taskscat.update',           'uses' => 'TaskscatController@update']);
        Route::delete('taskscat/{tasksCatId}', ['as' => 'admin.taskscat.destroy',          'uses' => 'TaskscatController@destroy']);
        Route::get('taskscat/{tasksCatId}/edit', ['as' => 'admin.taskscat.edit',             'uses' => 'TaskscatController@edit']);
        Route::get('taskscat/{tasksCatId}/confirm-delete', ['as' => 'admin.taskscat.confirm-delete',   'uses' => 'TaskscatController@getModalDelete']);
        Route::get('taskscat/{tasksCatId}/delete', ['as' => 'admin.taskscat.delete',           'uses' => 'TaskscatController@destroy']);
        Route::get('taskscat/{tasksCatId}/enable', ['as' => 'admin.taskscat.enable',           'uses' => 'TaskscatController@enable']);
        Route::get('taskscat/{tasksCatId}/disable', ['as' => 'admin.taskscat.disable',          'uses' => 'TaskscatController@disable']);

        Route::get('projects/search/tasks', ['as' => 'admin.projects.searchprojecttask', 'uses' => 'ProjectTaskController@searchprojecttask']);

        Route::get('projectTask/destroy/{id}/{taskId}','ProjectTaskController@destroyAttachment');

        // Client routes
        Route::post('clients/enableSelected', ['as' => 'admin.clients.enable-selected',  'uses' => 'ClientsController@enableSelected']);
        Route::post('clients/disableSelected', ['as' => 'admin.clients.disable-selected', 'uses' => 'ClientsController@disableSelected']);

        Route::get('clients/modals', ['as' => 'admin.clients.modals', 'uses' => 'ClientsController@showmodal']);
        Route::post('clients/modals', ['as' => 'admin.clients.psmodals', 'uses' => 'ClientsController@postModal']);

        Route::get('clients', ['as' => 'admin.clients.index', 'uses' => 'ClientsController@index']);

        Route::get('customer', ['as' => 'admin.cst.index', 'uses' => 'ClientsController@customers']);
        Route::get('supplier', ['as' => 'admin.suppl.index', 'uses' => 'ClientsController@suppliers']);

        //clients import export
        Route::get('clients/importExport', ['as' => 'admin.import-export-clients.index',            'uses' => 'ExcelController@indexClients']);
        Route::post('clients/importExcelClients', ['as' => 'admin.import-export-clients.store', 'uses' => 'ExcelController@importExcelClients']);
        Route::get('downloadExcelclients/{type}', ['as' => 'admin.downloadExcelclients', 'uses' => 'ExcelController@downloadExcelClients']);
        Route::get('download/products/pdf/index', ['as' => 'admin.download.products.pdf.index', 'uses' => 'DownloadIndexController@productspdf']);

        //customergroups
        Route::get('customergroup', ['as' => 'admin.customergroup.index', 'uses' => 'CustomerGroupController@index']);
        Route::get('customergroup/create', ['as' => 'admin.customergroup.create', 'uses' => 'CustomerGroupController@create']);
        Route::post('customergroup/create', ['as' => 'admin.customergroup.store', 'uses' => 'CustomerGroupController@store']);
        Route::get('customergroup/edit/{id}', ['as' => 'admin.customergroup.edit', 'uses' => 'CustomerGroupController@edit']);
        Route::post('customergroup/edit/{id}', ['as' => 'admin.customergroup.update', 'uses' => 'CustomerGroupController@update']);
        Route::get('customergroup/confirm-delete{id}', ['as' => 'admin.customergroup.confirm-delete', 'uses' => 'CustomerGroupController@getModalDelete']);
        Route::get('customergroup/delete/{id}', ['as' => 'admin.customergroup.delete', 'uses' => 'CustomerGroupController@destroy']);

        Route::resource('datatables', 'ClientsController', [
            'anyData'  => 'datatables.data',
            'index' => 'datatables',
        ]);

        Route::get('clients/create', ['as' => 'admin.clients.create',           'uses' => 'ClientsController@create']);
        Route::post('clients', ['as' => 'admin.clients.store',            'uses' => 'ClientsController@store']);
        Route::get('clients/{leadId}', ['as' => 'admin.clients.show',             'uses' => 'ClientsController@show']);
        Route::patch('clients/{leadId}', ['as' => 'admin.clients.patch',            'uses' => 'ClientsController@update']);
        Route::put('clients/{leadId}', ['as' => 'admin.clients.update',           'uses' => 'ClientsController@update']);
        Route::delete('clients/{leadId}', ['as' => 'admin.clients.destroy',          'uses' => 'ClientsController@destroy']);
        Route::get('clients/{leadId}/edit', ['as' => 'admin.clients.edit',             'uses' => 'ClientsController@edit']);
        Route::get('clients/{leadId}/confirm-delete', ['as' => 'admin.clients.confirm-delete',   'uses' => 'ClientsController@getModalDelete']);
        Route::get('clients/{leadId}/delete', ['as' => 'admin.clients.delete',           'uses' => 'ClientsController@destroy']);
        Route::get('clients/{leadId}/enable', ['as' => 'admin.clients.enable',           'uses' => 'ClientsController@enable']);
        Route::get('clients/{leadId}/disable', ['as' => 'admin.clients.disable',          'uses' => 'ClientsController@disable']);

        // Contact routes
        Route::post('contacts/enableSelected', ['as' => 'admin.contacts.enable-selected',  'uses' => 'ContactsController@enableSelected']);
        Route::post('contacts/disableSelected', ['as' => 'admin.contacts.disable-selected', 'uses' => 'ContactsController@disableSelected']);

        Route::get('contacts', ['as' => 'admin.contacts.index',            'uses' => 'ContactsController@index']);

        Route::resource('datatables', 'ContactsController', [
            'anyData'  => 'datatables.data',
            'index' => 'datatables',
        ]);

        Route::get('contacts/create', ['as' => 'admin.contacts.create',           'uses' => 'ContactsController@create']);
        Route::post('contacts', ['as' => 'admin.contacts.store',            'uses' => 'ContactsController@store']);
        Route::get('contacts/{leadId}', ['as' => 'admin.contacts.show',             'uses' => 'ContactsController@show']);
        Route::patch('contacts/{leadId}', ['as' => 'admin.contacts.patch',            'uses' => 'ContactsController@update']);
        Route::put('contacts/{leadId}', ['as' => 'admin.contacts.update',           'uses' => 'ContactsController@update']);
        Route::delete('contacts/{leadId}', ['as' => 'admin.contacts.destroy',          'uses' => 'ContactsController@destroy']);
        Route::get('contacts/{leadId}/edit', ['as' => 'admin.contacts.edit',             'uses' => 'ContactsController@edit']);
        Route::get('contacts/{leadId}/confirm-delete', ['as' => 'admin.contacts.confirm-delete',   'uses' => 'ContactsController@getModalDelete']);
        Route::get('contacts/{leadId}/delete', ['as' => 'admin.contacts.delete',           'uses' => 'ContactsController@destroy']);
        Route::get('contacts/{leadId}/enable', ['as' => 'admin.contacts.enable',           'uses' => 'ContactsController@enable']);
        Route::get('contacts/{leadId}/disable', ['as' => 'admin.contacts.disable',          'uses' => 'ContactsController@disable']);

        Route::get('contacts/create/modals', ['as' => 'admin.contacts.create.modals',          'uses' => 'ContactsController@createModal']);
        Route::post('contacts/create/modals', ['as' => 'admin.contacts.create.pstmodals',          'uses' => 'ContactsController@store']);

        // Import Export Excel Files - Contacts
        Route::get('importExport', ['as' => 'admin.import-export.index',            'uses' => 'ExcelController@index']);
        Route::get('downloadExcel/{type}', ['as' => 'admin.import-export.downloadExcel', 'uses' => 'ExcelController@downloadExcel']);
        Route::post('importExcel', ['as' => 'admin.import-export.importExcel', 'uses' => 'ExcelController@importExcel']);

        // Import Export Excel Files - Leads
        Route::get('importExportLeads', ['as' => 'admin.import-export.leads',            'uses' => 'ExcelController@leads']);
        Route::get('downloadExcelLeads/{type}', ['as' => 'admin.import-export.downloadExcelLeads', 'uses' => 'ExcelController@downloadExcelLeads']);
        Route::post('importExcelLeads', ['as' => 'admin.import-export.importExcelLeads', 'uses' => 'ExcelController@importExcelLeads']);

        Route::get('downloadexcelfilter', ['as' => 'admin.import-export.downloadexcelfilter', 'uses' => 'LeadsController@DownloadExcelFilter']);

        // Training
        Route::get('trainings', ['as' => 'admin.trainings.index',   'uses' => 'TrainingController@index']);
        Route::post('trainings/save', ['as' => 'admin.trainings.save',   'uses' => 'TrainingController@save']);
        Route::get('trainings/edit/{training_id}', ['as' => 'admin.trainings.edit',   'uses' => 'TrainingController@edit']);
        Route::get('trainings/show/{training_id}', ['as' => 'admin.trainings.show',   'uses' => 'TrainingController@show']);
        Route::get('trainings/delete/{training_id}', ['as' => 'admin.trainings.delete',   'uses' => 'TrainingController@delete']);
        Route::get('trainings/print', ['as' => 'admin.trainings.print',  'uses' => 'TrainingController@printNow']);
        Route::get('trainings/generatePdf', ['as' => 'admin.trainings.generatePdf',   'uses' => 'TrainingController@generatePdf']);
        Route::get('trainings/generatePdf/{training_id}', ['as' => 'admin.trainings.generateSinglePdf',   'uses' => 'TrainingController@generateSinglePdf']);

        // Stock
        Route::get('stock/category', ['as' => 'admin.stock.category',   'uses' => 'StockController@category']);
        Route::post('stock/save_category', ['as' => 'admin.stock.save_category',   'uses' => 'StockController@saveCategory']);
        Route::get('stock/category/{cat}/{subCat}', ['as' => 'admin.stock.editSubCategory',   'uses' => 'StockController@editSubCategory']);
        Route::get('stock/category/{stock_category_id}', ['as' => 'admin.stock.editCategory',   'uses' => 'StockController@editCategory']);
        Route::post('stock/category/{stock_category_id}', ['as' => 'admin.stock.updateCategory',   'uses' => 'StockController@updateCategory']);

        Route::get('stock/delete_stock_category/{stock_category_id}', ['as' => 'admin.stock.deleteCategory',   'uses' => 'StockController@deleteCategory']);
        Route::get('stock/delete_stock_sub_category/{subCat}', ['as' => 'admin.stock.deleteSubCategory',   'uses' => 'StockController@deleteSubCategory']);

        Route::get('stock/list', ['as' => 'admin.stock.list',   'uses' => 'StockController@lists']);
        Route::get('stock/list/{stock_id}', ['as' => 'admin.stock.editStock',   'uses' => 'StockController@editStock']);
        Route::post('stock/save_stock', ['as' => 'admin.stock.save_stock',   'uses' => 'StockController@saveStock']);
        Route::get('stock/delete_stock/{stock_id}', ['as' => 'admin.stock.delete_stock',   'uses' => 'StockController@deleteStock']);

        Route::get('stock/history', ['as' => 'admin.stock.history',   'uses' => 'StockController@history']);
        Route::post('stock/history', ['as' => 'admin.stock.history_list',   'uses' => 'StockController@history']);

        Route::get('stock/assign', ['as' => 'admin.stock.assign',   'uses' => 'StockController@assign']);
        //Ajax
        Route::get('stock/getStockBySubCategory', ['as' => 'admin.stock.getStockBySubCategory',   'uses' => 'StockController@getStockBySubCategory']);
        Route::get('stock/getStockQuantity', ['as' => 'admin.stock.getStockQuantity',   'uses' => 'StockController@getStockQuantity']);

        Route::post('stock/save_assign', ['as' => 'admin.stock.save_assign',   'uses' => 'StockController@saveAssign']);
        Route::get('stock/delete_assign_stock/{assign_item_id}', ['as' => 'admin.stock.assign_item_id',   'uses' => 'StockController@deleteAssign']);
        Route::get('stock/printAssign', ['as' => 'admin.stock.printAssign',  'uses' => 'StockController@printAssign']);
        Route::get('stock/generateAssignPdf', ['as' => 'admin.stock.generateAssignPdf',   'uses' => 'StockController@generateAssignPdf']);

        Route::get('stock/assign_report', ['as' => 'admin.stock.assign_report',   'uses' => 'StockController@assignReport']);
        Route::post('stock/assign_report', ['as' => 'admin.stock.assign_report_list',   'uses' => 'StockController@assignReport']);
        Route::get('stock/printAssign/{user_id}', ['as' => 'admin.stock.printUserAssign',  'uses' => 'StockController@printUserAssign']);
        Route::get('stock/generateAssignPdf/{user_id}', ['as' => 'admin.stock.generateUserAssignPdf',   'uses' => 'StockController@generateUserAssignPdf']);

        Route::get('stock/report', ['as' => 'admin.stock.report',   'uses' => 'StockController@report']);
        Route::post('stock/report', ['as' => 'admin.stock.report_list',   'uses' => 'StockController@reportList']);
        Route::get('stock/printAssign/{start_date}/{end_date}', ['as' => 'admin.stock.printUserAssignByDate',  'uses' => 'StockController@printUserAssignBydate']);
        Route::get('stock/generateAssignPdf/{start_date}/{end_date}', ['as' => 'admin.stock.generateUserAssignPdfByDate',   'uses' => 'StockController@generateUserAssignPdfByDate']);

        // assets return

        Route::get('stock/return', ['as' => 'admin.stock.return',   'uses' => 'StockController@return']);
        Route::post('stock/save_return', ['as' => 'admin.stock.save_return',   'uses' => 'StockController@saveReturn']);
        Route::get('stock/delete_return_stock/{return_item_id}', ['as' => 'admin.stock.return_item_id',   'uses' => 'StockController@deleteReturn']);
        Route::get('stock/printReturn', ['as' => 'admin.stock.printReturn',  'uses' => 'StockController@printReturn']);
        Route::get('stock/generateReturnPdf', ['as' => 'admin.stock.generateReturnPdf',   'uses' => 'StockController@generateReturnPdf']);

        // For Project Task
        Route::get('project_tasks', ['as' => 'admin.project_task.index',           'uses' => 'ProjectTaskController@index']);
        // For Project Task
        //    Route::post(  'store_project_task',                           ['as' => 'admin.project_task.store',            'uses' => 'ProjectTaskController@store']);

        Route::get('project_task/create/{projectId}', ['as' => 'admin.project_task.create',             'uses' => 'ProjectTaskController@create']);
        Route::get('task/create/global', ['as' => 'admin.project_task.createglobal', 'uses' => 'ProjectTaskController@createGlobal']);
        Route::post('task/create/store', ['as' => 'admin.project_task.storeglobal', 'uses' => 'ProjectTaskController@storeGlobal']);

        Route::post('project_task/store/{projectId}', ['as' => 'admin.project_task.store',             'uses' => 'ProjectTaskController@store']);
        Route::get('project_task/{taskId}', ['as' => 'admin.project_task.show',             'uses' => 'ProjectTaskController@show']);
        Route::get('project_task/{taskId}/edit', ['as' => 'admin.project_task.edit',             'uses' => 'ProjectTaskController@edit']);
        Route::patch('project_task/{taskId}', ['as' => 'admin.project_task.patch',            'uses' => 'ProjectTaskController@update']);
        Route::put('project_task/{taskId}', ['as' => 'admin.project_task.update',           'uses' => 'ProjectTaskController@update']);

        Route::delete('project_task/{taskId}', ['as' => 'admin.project_task.destroy',          'uses' => 'ProjectTaskController@destroy']);

        Route::get('project_task/{taskId}/confirm-delete', ['as' => 'admin.project_task.confirm-delete',   'uses' => 'ProjectTaskController@getModalDelete']);
        Route::get('project_task/{taskId}/delete', ['as' => 'admin.project_task.delete',           'uses' => 'ProjectTaskController@destroy']);
        Route::get('getUserTagsJson', ['as' => 'admin.getUserTagsJson.store',            'uses' => 'ProjectTaskController@getUserTagsJson']);

        Route::post('post_comment', ['as' => 'admin.post_comment',            'uses' => 'ProjectTaskController@postComment']);

        Route::post('ajax_proj_task_status', ['as' => 'admin.ajax_proj_task_status', 'uses' => 'ProjectTaskController@ajaxProjectTaskStatus']);

        Route::post('ajax_proj_task_order', ['as' => 'admin.ajax_proj_task_order', 'uses' => 'ProjectTaskController@ajaxProjectTaskOrder']);

        Route::get('project_task_activities/{projectid}', ['as' => 'admin.project_task_activities.modals',           'uses' => 'ProjectsController@openactivities']);

        //COA Group && Leadgers
        Route::get('chartofaccounts', ['as' => 'admin.chartofaccounts', 'uses' => 'COAController@index']);

        Route::get('chartofaccounts/create/groups', ['as' => 'admin.chartofaccounts.create.groups', 'uses' => 'COAController@CreateGroups']);

        Route::post('chartofaccounts/store/groups', ['as' => 'admin.chartofaccounts.store.groups', 'uses' => 'COAController@PostGroups']);
        Route::get('chartofaccounts/create/ledgers', ['as' => 'admin.chartofaccounts.create.ledgers', 'uses' => 'COAController@CreateLedgers']);
        Route::post('chartofaccounts/store/ledgers', ['as' => 'admin.chartofaccounts.store.ledgers', 'uses' => 'COAController@PostLedgers']);
        Route::get('chartofaccounts/edit/{id}/groups', ['as' => 'admin.chartofaccounts.edit.groups', 'uses' => 'COAController@EditGroups']);
        Route::post('chartofaccounts/update/{id}/groups', ['as' => 'admin.chartofaccounts.update.groups', 'uses' => 'COAController@UpdateGroups']);
        Route::get('chartofaccounts/edit/{id}/ledgers', ['as' => 'admin.chartofaccounts.edit.ledgers', 'uses' => 'COAController@EditLedgers']);
        Route::post('chartofaccounts/update/{id}/ledgers', ['as' => 'admin.chartofaccounts.update.ledgers', 'uses' => 'COAController@UpdateLedgers']);
        Route::post('chartofaccounts/groups/getNextCode', ['as' => 'admin.chartofaccounts.create.groups.getnextcode', 'uses' => 'COAController@GetNextCode']);
        Route::post('chartofaccounts/ledgers/getNextCode', ['as' => 'admin.chartofaccounts.create.ledgers.getnextcode', 'uses' => 'COAController@getNextCodeLedgers']);
        Route::get('chartofaccounts/{orderId}/groups/confirm-delete', ['as' => 'admin.chartofaccounts.groups.confirm-delete',   'uses' => 'COAController@getModalDeleteGroups']);
        Route::get('chartofaccounts/{orderId}/groups/delete', ['as' => 'admin.chartofaccounts.groups.delete',           'uses' => 'COAController@destroyGroups']);

        Route::get('chartofaccounts/{orderId}/ledgers/confirm-delete', ['as' => 'admin.chartofaccounts.ledgers.confirm-delete',   'uses' => 'COAController@getModalDeleteLedgers']);

        Route::get('chartofaccounts/{orderId}/ledgers/delete', ['as' => 'admin.chartofaccounts.ledgers.delete',           'uses' => 'COAController@destroyLedgers']);

        Route::get('chartofaccounts/detail/{id}/ledgers', ['as' => 'admin.chartofaccounts.detail.ledgers', 'uses' => 'COAController@DetailLedgers']);

        Route::get('chartofaccounts/pdf/{id}', ['as' => 'admin.chartofaccounts.pdf', 'uses' => 'COAController@DownloadPdf']);
        Route::get('chartofaccounts/print/{id}', ['as' => 'admin.chartofaccounts.print', 'uses' => 'COAController@PrintLedgers']);

        //Ledger import export
        Route::get('downloadExcelLedger', ['as' => 'admin.ExcelLedger.index', 'uses' => 'COAController@excelLedger']);
        Route::get('downloadExcelLedger/{type}', ['as' => 'admin.ExcelLedger.export', 'uses' => 'COAController@exportLedger']);
        Route::post('downloadExcelLedger/', ['as' => 'admin.ExcelLedger.store', 'uses' => 'COAController@importLedger']);

//        Route::get('coa/filterbygroups', ['as' => 'admin.filter.coa.groups', 'uses' => 'COAController@filterByGroups']);
//        Route::post('coa/filterbygroups', ['as' => 'admin.filter.coa.groups.detail', 'uses' => 'COAController@filterByGroupPost']);
        Route::get('coa/filterbygroups', ['as' => 'admin.filter.coa.groups', 'uses' => 'COAController@filterByGroupPost']);

        Route::get('ledgers/delete-selected', ['as' => 'admin.ledgers.deleteSelected',   'uses' => 'COAController@deleteSelected']);

        //ledgerSetting
        Route::get('ledgers/settings', ['as' => 'admin.ledgers.setting', 'uses' => 'LedgerSettingController@index']);
        Route::get('ledgers/settings/create', ['as' => 'admin.ledgers.setting.create', 'uses' => 'LedgerSettingController@create']);
        Route::post('ledgers/settings/store', ['as' => 'admin.ledgers.setting.store', 'uses' => 'LedgerSettingController@store']);

        Route::get('ledgers/settings/edit/{id}', ['as' => 'admin.ledgers.setting.edit', 'uses' => 'LedgerSettingController@edit']);
        Route::post('ledgers/settings/update/{id}', ['as' => 'admin.ledgers.setting.update', 'uses' => 'LedgerSettingController@update']);

        Route::get('ledgers/settings/destroy/{id}', ['as' => 'admin.ledgers.setting.destroy', 'uses' => 'LedgerSettingController@destroy']);

        //Ledger Group import export
        Route::get('downloadExcelLedgergroups', ['as' => 'admin.ExcelLedgergroups.index', 'uses' => 'COAController@excelLedgergroups']);
        Route::get('downloadExcelLedgergroups/{type}', ['as' => 'admin.ExcelLedgergroups.export', 'uses' => 'COAController@exportLedgergroups']);
        Route::post('downloadExcelLedgergroups/', ['as' => 'admin.ExcelLedgergroups.store', 'uses' => 'COAController@importLedgergroups']);

        Route::get('chartofaccounts/excel/{id}', 'COAController@downloadExcel');


// view group  details
        Route::get('chartofaccounts/detail/{id}/groups', ['as' => 'admin.chartofaccounts.detail.groups', 'uses' => 'COAController@DetailGroups']);
Route::get('chartofaccounts/groupdetail/pdf/{id}', ['as' => 'admin.chartofaccounts.groupdetail.pdf', 'uses' => 'COAController@DownloadGroupPdf']);

        Route::get('chartofaccounts/groupdetail/print/{id}', ['as' => 'admin.chartofaccounts.groupdetail.print', 'uses' => 'COAController@PrintGroups']);

         Route::get('chartofaccounts/groupdetail/excel/{id}', 'COAController@downloadGroupExcel');
        // Entries
        Route::post('entries/verfied',['as'=>'admin.entry.verified', 'uses'=>'EntryController@entries_verfied']);
        Route::get('entries', ['as' => 'admin.entries.index', 'uses' => 'EntryController@index']);
        Route::get('entries/add/{label}', ['as' => 'admin.entries.add', 'uses' => 'EntryController@Create']);
        Route::get('entries/show/{label}/{id}', ['as' => 'admin.entries.show', 'uses' => 'EntryController@show']);
        Route::post('entries/store', ['as' => 'admin.entries.store.', 'uses' => 'EntryController@store']);
        Route::post('entries/ajaxaddentry', ['as' => 'admin.entries.ajaxAddEntry', 'uses' => 'EntryController@ajaxAddEntry']);
        Route::post('entries/ajaxcl', ['as' => 'admin.entries.ajaxcl', 'uses' => 'EntryController@Ajaxcl']);
        Route::get('entries/edit/{label}/{id}', ['as' => 'admin.entries.edit', 'uses' => 'EntryController@Edit']);
        Route::post('entries/update/{id}', ['as' => 'admin.entries.update', 'uses' => 'EntryController@Update']);
        Route::get('entries/{id}/confirm-delete', ['as' => 'admin.entries.confirm-delete',   'uses' => 'EntryController@getModalEntry']);
        Route::get('entries/{id}/ledgers/delete', ['as' => 'admin.entries.delete', 'uses' => 'EntryController@destroyEntry']);
        Route::get('entries/view-products', ['as' => 'admin.entries.viewProducts', 'uses' => 'EntryController@viewProducts']);

        Route::get('entries/pdf/{label}/{id}', ['as' => 'admin.entries.pdf', 'uses' => 'EntryController@DownloadPdf']);
        Route::get('entries/print/{label}/{id}', ['as' => 'admin.entries.print', 'uses' => 'EntryController@PrintEntry']);
        Route::get('entries/excel/{label}/{id}', ['as' => 'admin.entries.excel', 'uses' => 'EntryController@PurchaseDownloadExcel']);
        Route::get('entries/sampleexcel', ['as' => 'admin.entries.sampleexcel', 'uses' => 'EntryController@SamplePurchaseDownloadExcel']);
        Route::post('ExcelentriesAdd/', ['as' => 'admin.Excelentries.store', 'uses' => 'EntryController@purchaseimportexcel']);
        
        
        //Salary voucher
        Route::get('coa_ledgers_list_salaryvoucher',['as'=>'coa_ledgers_list_salaryvoucher', 'uses'=>'SalaryVoucherController@get_coa_ledger']);
        Route::post('entries/ajaxaddsalaryvoucher', ['as' => 'admin.entries.ajaxAddSalaryVoucher', 'uses' => 'SalaryVoucherController@ajaxAddSalaryVoucher']);
        Route::get('salary-voucher/create', ['as' => 'admin.salary-voucher.add', 'uses' => 'SalaryVoucherController@create']);
        Route::post('salary-voucher/store', ['as' => 'admin.salary-voucher.store.', 'uses' => 'SalaryVoucherController@store']);
        Route::get('salary-voucher', ['as' => 'admin.salary-voucher.index', 'uses' => 'SalaryVoucherController@index']);
        Route::get('salary-voucher/{id}/edit', ['as' => 'admin.salary-voucher.edit', 'uses' => 'SalaryVoucherController@edit']);
        Route::post('salary-voucher/update/{id}', ['as' => 'admin.salary-voucher.update', 'uses' => 'SalaryVoucherController@update']);
        Route::get('salary-voucher/show/{id}', ['as' => 'admin.salary-voucher.show', 'uses' => 'SalaryVoucherController@show']);
        Route::get('salary-voucher/pdf/{id}', ['as' => 'admin.salary-voucher.pdf', 'uses' => 'SalaryVoucherController@DownloadPdf']);
        Route::get('salary-voucher/excel/{id}', ['as' => 'admin.salary-voucher.excel', 'uses' => 'ExcelController@exportSalaryVoucherEntry']);
        Route::get('salary-voucher/print/{id}', ['as' => 'admin.salary-voucher.print', 'uses' => 'SalaryVoucherController@PrintEntry']);

        
         //Bank and Cash voucher
         Route::get('coa_ledgers_list_bandcashvoucher',['as'=>'coa_ledgers_list_bandcashvoucher', 'uses'=>'BankCashVoucherController@get_coa_ledger_list']);
         Route::post('entries/ajaxAddBankCashVoucher', ['as' => 'admin.entries.ajaxAddBankCashVoucher', 'uses' => 'BankCashVoucherController@ajaxAddBankCashVoucher']);
         Route::get('bank-cash-voucher/create', ['as' => 'admin.bank-cash-voucher.add', 'uses' => 'BankCashVoucherController@create']);
         Route::post('bank-cash-voucher/store', ['as' => 'admin.bank-cash-voucher.store.', 'uses' => 'BankCashVoucherController@store']);
         Route::get('bank-cash-voucher', ['as' => 'admin.bank-cash-voucher.index', 'uses' => 'BankCashVoucherController@index']);
         Route::get('bank-cash-voucher/{id}/edit', ['as' => 'admin.bank-cash-voucher.edit', 'uses' => 'BankCashVoucherController@edit']);
         Route::post('bank-cash-voucher/update/{id}', ['as' => 'admin.bank-cash-voucher.update', 'uses' => 'BankCashVoucherController@update']);
         Route::get('bank-cash-voucher/show/{id}', ['as' => 'admin.bank-cash-voucher.show', 'uses' => 'BankCashVoucherController@show']);
         Route::get('bank-cash-voucher/pdf/{id}', ['as' => 'admin.bank-cash-voucher.pdf', 'uses' => 'BankCashVoucherController@DownloadPdf']);
         Route::get('bank-cash-voucher/excel/{id}', ['as' => 'admin.bank-cash-voucher.excel', 'uses' => 'ExcelController@exportSalaryVoucherEntry']);
         Route::get('bank-cash-voucher/print/{id}', ['as' => 'admin.bank-cash-voucher.print', 'uses' => 'BankCashVoucherController@PrintEntry']);
        
        
        //Account Reports 

        //ledger Statement
        Route::get('accounts/reports/ledgerstatement', ['as' => 'admin.accounts.reports.ledgerstatement', 'uses' => 'AccountReportController@ViewLedgers']);
        Route::get('accounts/reports/ledger_statement', ['as' => 'admin.accounts.reports.ledgerdetail', 'uses' => 'AccountReportController@DetailLedgers']);

        //ledger Entries
        Route::get('accounts/reports/ledgerentries', ['as' => 'admin.accounts.reports.ledgerentries', 'uses' => 'AccountReportController@listLedgersEntries']);
        Route::post('accounts/reports/ledgerentries', ['as' => 'admin.accounts.reports.psledgerentries', 'uses' => 'AccountReportController@detailLedgersEntries']);

        // Trial Balance
        Route::get('accounts/reports/trialbalance', ['as' => 'admin.accounts.reports.trialbalance.index', 'uses' => 'AccountReportController@trialbalanceindex']);
        Route::get('accounts/reports/trialbalance/excel', ['as' => 'admin.accounts.reports.trialbalance.excel', 'uses' => 'AccountReportController@trialbalanceexcel']);

        //Balance Sheet
        Route::get('accounts/reports/balancesheet', ['as' => 'admin.accounts.reports.balancesheet.index', 'uses' => 'AccountReportController@balancesheetindex']);
        Route::get('accounts/reports/profitloss/excel', ['as' => 'admin.accounts.reports.profitloss.excel', 'uses' => 'AccountReportController@profitlossexcel']);

        //profit loss
        Route::get('accounts/reports/profitloss', ['as' => 'admin.accounts.reports.profitloss.index', 'uses' => 'AccountReportController@profitlossindex']);

        // Tag

        Route::get('tags', ['as' => 'admin.tags.index', 'uses' => 'TagController@index']);
        Route::get('tags/create', ['as' => 'admin.tags.create', 'uses' => 'TagController@create']);
        Route::post('tags/store', ['as' => 'admin.tags.store', 'uses' => 'TagController@store']);
        Route::get('tags/{id}/edit', ['as' => 'admin.tags.edit', 'uses' => 'TagController@edit']);
        Route::post('tags/update/{id}', ['as' => 'admin.tags.update', 'uses' => 'TagController@update']);
        Route::get('tags/{id}/confirm-delete', ['as' => 'admin.tags.confirm-delete',   'uses' => 'TagController@getModalDelete']);
        Route::get('tags/{id}/delete', ['as' => 'admin.tags.delete',           'uses' => 'TagController@destroy']);

        //entrytypes

        Route::get('entrytype', ['as' => 'admin.entrytype.index', 'uses' => 'EntryTypeController@index']);
        Route::get('entrytype/create', ['as' => 'admin.entrytype.create', 'uses' => 'EntryTypeController@create']);
        Route::post('entrytype/store', ['as' => 'admin.entrytype.store', 'uses' => 'EntryTypeController@store']);
        Route::get('entrytype/{id}/edit', ['as' => 'admin.entrytype.edit', 'uses' => 'EntryTypeController@edit']);
        Route::post('entrytype/update/{id}', ['as' => 'admin.entrytype.update', 'uses' => 'EntryTypeController@update']);
        Route::get('entrytype/{id}/confirm-delete', ['as' => 'admin.entrytype.confirm-delete', 'uses' => 'EntryTypeController@getModalDelete']);
        Route::get('entrytype/{id}/delete', ['as' => 'admin.entrytype.delete', 'uses' => 'EntryTypeController@destroy']);

        //Talk Controller
        Route::get('talktests', 'TalkController@tests');
        Route::get('/talk', 'TalkController@index');
        Route::get('talk/{id}', 'TalkController@chatHistory')->name('message.read');
        Route::get('talk/typing/{receiver_id}', 'TalkController@typing');

        // HR Letters routes
        Route::post('hrletter/enableSelected', ['as' => 'admin.hrletter.enable-selected',  'uses' => 'HRLettersController@enableSelected']);
        Route::post('hrletter/disableSelected', ['as' => 'admin.hrletter.disable-selected', 'uses' => 'HRLettersController@disableSelected']);
        Route::get('hrletter/search', ['as' => 'admin.hrletter.search',           'uses' => 'HRLettersController@searchByName']);
        Route::post('hrletter/getInfo', ['as' => 'admin.hrletter.get-info',         'uses' => 'HRLettersController@getInfo']);
        Route::post('hrletter', ['as' => 'admin.hrletter.store',            'uses' => 'HRLettersController@store']);

        Route::get('hrletter', ['as' => 'admin.hrletter.index',            'uses' => 'HRLettersController@index']);

        Route::get('hrletter/create', ['as' => 'admin.hrletter.create',           'uses' => 'HRLettersController@create']);
        Route::get('hrletter/{proposalId}', ['as' => 'admin.hrletter.show',             'uses' => 'HRLettersController@show']);
        Route::patch('hrletter/{proposalId}', ['as' => 'admin.hrletter.patch',            'uses' => 'HRLettersController@update']);
        Route::put('hrletter/{proposalId}', ['as' => 'admin.hrletter.update',           'uses' => 'HRLettersController@update']);
        Route::delete('hrletter/{proposalId}', ['as' => 'admin.hrletter.destroy',          'uses' => 'HRLettersController@destroy']);
        Route::get('hrletter/{proposalId}/edit', ['as' => 'admin.hrletter.edit',             'uses' => 'HRLettersController@edit']);
        Route::get('hrletter/{proposalId}/confirm-delete', ['as' => 'admin.hrletter.confirm-delete',   'uses' => 'HRLettersController@getModalDelete']);
        Route::get('hrletter/{proposalId}/delete', ['as' => 'admin.hrletter.delete',           'uses' => 'HRLettersController@destroy']);
        Route::get('hrletter/{proposalId}/enable', ['as' => 'admin.hrletter.enable',           'uses' => 'HRLettersController@enable']);
        Route::get('hrletter/{proposalId}/disable', ['as' => 'admin.hrletter.disable',          'uses' => 'HRLettersController@disable']);

        Route::get('hrletter/print/{id}', ['as' => 'admin.hrletter.print',  'uses' => 'HRLettersController@printLetter']);
        Route::get('hrletter/copy/{id}', ['as' => 'admin.hrletter.copy',  'uses' => 'HRLettersController@copyDoc']);
        Route::get('hrletter/generatePDF/{id}', ['as' => 'admin.hrletter.generatePDF',   'uses' => 'HRLettersController@generatePDF']);
        // Send Email of Proposal from Detail Page
        Route::get('hrletter/{proposalId}/show-mailmodal', ['as' => 'admin.hrletter.show-mailmodal', 'uses' => 'HRLettersController@getModalMail']);

        Route::post('hrletter/{proposalId}/send-mail-modal', ['as' => 'admin.hrletter.send-mail-modal', 'uses' => 'HRLettersController@postModalMail']);
        // Ajax upload the template
        Route::post('hrletter/loadTemplate', ['as' => 'admin.hrloadTemplate', 'uses' => 'HRLettersController@loadTemplate']);
        //hrletterTemplates
        Route::get('hrlettertemplate', ['as' => 'admin.hrlettertemplate.index', 'uses' => 'HrLetterTemplateController@index']);
        Route::get('hrlettertemplate/create', ['as' => 'admin.hrlettertemplate.create', 'uses' => 'HrLetterTemplateController@create']);
        Route::post('hrlettertemplate/create', ['as' => 'admin.hrlettertemplate.store', 'uses' => 'HrLetterTemplateController@store']);
        Route::get('hrlettertemplate/edit/{id}', ['as' => 'admin.hrlettertemplate.edit', 'uses' => 'HrLetterTemplateController@edit']);
        Route::post('hrlettertemplate/edit/{id}', ['as' => 'admin.hrlettertemplate.update', 'uses' => 'HrLetterTemplateController@update']);
        Route::get('hrlettertemplate/confirm-delete{id}', ['as' => 'admin.hrlettertemplate.confirm-delete', 'uses' => 'HrLetterTemplateController@getModalDelete']);
        Route::get('hrlettertemplate/delete/{id}', ['as' => 'admin.hrlettertemplate.delete', 'uses' => 'HrLetterTemplateController@destroy']);
        //backup
        Route::get('backup/list', 'SettingsController@backupList');
        Route::post('backup/delete/{id}', 'SettingsController@destroyBackup');
        Route::get('backup', 'SettingsController@backupDB');

        Route::get('download/backup/{file_name}', ['as' => 'admin.download.backup', 'uses' => 'SettingsController@downloadbackup']);

        /// Notification

        Route::get('not_viewed_cases', ['as' => 'admin.not_viewed_cases.index', 'uses' => 'HomeController@notViewedCases']);

        Route::get('not_viewed_leads', ['as' => 'admin.not_viewed_leads.index', 'uses' => 'HomeController@NotViewedLeads']);

        Route::get('due_marketing_tasks', ['as' => 'admin.due_marketing_tasks.index', 'uses' => 'HomeController@NotViewedMarketingTasks']);
        Route::get('due_payments', ['as' => 'admin.due_payments.index', 'uses' => 'HomeController@duePayments']);

        //Profile routes
        Route::get('myprofile/show', ['as' => 'admin.myprofile.show', 'uses' => 'ProfilesController@show']);
        Route::get('profile/show/{user_id}', ['as' => 'admin.profile.show', 'uses' => 'ProfilesController@publicProfile']);
        Route::get('myprofile/edit', ['as' => 'admin.myprofile.edit',             'uses' => 'ProfilesController@edit']);
        Route::post('myprofile/update', ['as' => 'admin.myprofile.update',           'uses' => 'ProfilesController@update']);
        Route::get('myprofile/imap', ['as' => 'admin.myprofile.showimap',             'uses' => 'ProfilesController@showimap']);
        Route::get('myprofile/editimap', ['as' => 'admin.myprofile.editimap',             'uses' => 'ProfilesController@editimap']);
        Route::post('myprofile/updateimap', ['as' => 'admin.myprofile.updateimap',       'uses' => 'ProfilesController@updateimap']);

        //GeoLocation
        Route::get('geolocations', 'GeoLocationController@index');
        Route::get('geolocations/filter', ['as' => 'admin.geolocations.filter', 'uses' => 'GeoLocationController@filter']);
        Route::get('geolocations/monitor', 'GeoLocationController@monitorlocation');

        Route::get('finance/dashboard', ['as' => 'admin.finance.dashboard', 'uses' => 'FinanceBoardController@index']);

        Route::get('bank', ['as' => 'admin.bank.index', 'uses' => 'BankController@index']);
        Route::get('bank/create', ['as' => 'admin.bank.create', 'uses' => 'BankController@create']);
        Route::post('bank/store', ['as' => 'admin.bank.store', 'uses' => 'BankController@store']);
        Route::get('bank/{id}/edit', ['as' => 'admin.bank.edit', 'uses' => 'BankController@edit']);
        Route::get('bank/{id}/show', ['as' => 'admin.bank.show', 'uses' => 'BankController@show']);
        Route::post('bank/{id}/update', ['as' => 'admin.bank.update', 'uses' => 'BankController@update']);
        Route::get('bank/{id}/confirmdelete', ['as' => 'admin.bank.confirmdelete', 'uses' => 'BankController@getModalDelete']);
        Route::get('bank/{id}/delete', ['as' => 'admin.bank.delete', 'uses' => 'BankController@destroy']);

        Route::get('bank/{id}/income', ['as' => 'admin.bank.income', 'uses' => 'BankController@createIncome']);
        Route::post('bank/{id}/income', ['as' => 'admin.bank.income.save', 'uses' => 'BankController@saveIncome']);
        Route::get('bank/{id}/income/edit', ['as' => 'admin.bank.income.edit', 'uses' => 'BankController@editIncome']);
        Route::post('bank/{id}/income/edit', ['as' => 'admin.bank.income.update', 'uses' => 'BankController@updateIncome']);
	Route::get('bank/income/add', ['as' => 'admin.bank.income.add', 'uses' => 'BankController@addIncome']);
        Route::post('bank/income/store', ['as' => 'admin.bank.income.store', 'uses' => 'BankController@storeIncome']);

        Route::post('rating', ['as' => 'admin.rating.store', 'uses' => 'RatingController@store']);
        Route::get('rating', ['as' => 'admin.rating.index', 'uses' => 'RatingController@index']);
        Route::get('rating/create', ['as' => 'admin.rating.create', 'uses' => 'RatingController@create']);
        Route::post('rating/{id}', ['as' => 'admin.rating.update', 'uses' => 'RatingController@update']);
        Route::get('rating/{id}/edit', ['as' => 'admin.rating.edit', 'uses' => 'RatingController@edit']);
        Route::get('rating/delete-confirm/{id}', ['as' => 'admin.rating.delete-confirm', 'uses' => 'RatingController@getModalDelete']);
        Route::get('rating/delete{id}', ['as' => 'admin.rating.delete', 'uses' => 'RatingController@destroy']);

        // task stages routes

        Route::post('task/stages', ['as' => 'admin.task.stages.store', 'uses' => 'TaskStageController@store']);
        Route::get('task/stages', ['as' => 'admin.task.stages.index', 'uses' => 'TaskStageController@index']);
        Route::get('task/stages/create', ['as' => 'admin.task.stages.create', 'uses' => 'TaskStageController@create']);
        Route::post('task/stages/{id}', ['as' => 'admin.task.stages.update', 'uses' => 'TaskStageController@update']);
        Route::get('task/stages/{id}/edit', ['as' => 'admin.task.stages.edit', 'uses' => 'TaskStageController@edit']);
        Route::get('task/stages/delete-confirm/{id}', ['as' => 'admin.task.stages.delete-confirm', 'uses' => 'TaskStageController@getModalDelete']);
        Route::get('task/stages/delete{id}', ['as' => 'admin.task.stages.delete', 'uses' => 'TaskStageController@destroy']);

        // shifts routes

        Route::post('shift', ['as' => 'admin.shift.store', 'uses' => 'ShiftController@store']);
        Route::get('shift', ['as' => 'admin.shift.index', 'uses' => 'ShiftController@index']);
        Route::get('shift/create', ['as' => 'admin.shift.create', 'uses' => 'ShiftController@create']);
        Route::post('shift/{id}', ['as' => 'admin.shift.update', 'uses' => 'ShiftController@update']);
        Route::get('shift/{id}/edit', ['as' => 'admin.shift.edit', 'uses' => 'ShiftController@edit']);
        Route::get('shift/delete-confirm/{id}', ['as' => 'admin.shift.delete-confirm', 'uses' => 'ShiftController@getModalDelete']);
        Route::get('shift/delete{id}', ['as' => 'admin.shift.delete', 'uses' => 'ShiftController@destroy']);

        Route::post('ajaxLeadUpdate', 'LeadsController@ajaxLeadUpdate');
        Route::post('/ajaxTaskDescription', 'ProjectTaskController@taskdescription');

        Route::get('shiftcalender', ['as' => 'admin.shift.calender', 'uses' => 'ShiftController@shiftCalender']);
        Route::post('shiftcalender', ['as' => 'admin.shift.pstcalender', 'uses' => 'CalendarController@shiftCalender']);

        // shift maps

        Route::post('shifts/maps', ['as' => 'admin.shift.maps.store', 'uses' => 'ShiftMapController@store']);
        Route::get('shifts/maps', ['as' => 'admin.shift.maps.index', 'uses' => 'ShiftMapController@index']);
        Route::get('shifts/maps/create', ['as' => 'admin.shift.maps.create', 'uses' => 'ShiftMapController@create']);
        Route::post('shifts/maps/{id}', ['as' => 'admin.shift.maps.update', 'uses' => 'ShiftMapController@update']);
        Route::get('shifts/maps/{id}/edit', ['as' => 'admin.shift.maps.edit', 'uses' => 'ShiftMapController@edit']);
        Route::get('shifts/maps/delete-confirm/{id}', ['as' => 'admin.shift.maps.delete-confirm', 'uses' => 'ShiftMapController@getModalDelete']);
        Route::get('shifts/maps/delete/{id}', ['as' => 'admin.shift.maps.delete', 'uses' => 'ShiftMapController@destroy']);
        //shift breaks

        Route::get('shiftsBreaks', ['as' => 'admin.shiftsBreaks.index', 'uses' => 'ShiftBreakController@index']);

        Route::get('shiftsBreaks/create', ['as' => 'admin.shiftsBreaks.create', 'uses' => 'ShiftBreakController@create']);
        Route::post('shiftsBreaks/create', ['as' => 'admin.shiftsBreaks.pstcreate', 'uses' => 'ShiftBreakController@store']);

        Route::get('shiftsBreaks/{id}/edit', ['as' => 'admin.shiftsBreaks.edit', 'uses' => 'ShiftBreakController@edit']);
        Route::post('shiftsBreaks/{id}/edit', ['as' => 'admin.shiftsBreaks.update', 'uses' => 'ShiftBreakController@update']);
        Route::get('shiftsBreaks/delete-confirm/{id}', ['as' => 'admin.shiftsBreaks.delete-confirm', 'uses' => 'ShiftBreakController@getModalDelete']);
        Route::get('shifts/maps/delete{id}', ['as' => 'admin.shiftsBreaks.delete', 'uses' => 'ShiftBreakController@destroy']);

        Route::get('projectsgroups/{projectId}', ['as' => 'admin.projects.group', 'uses' => 'ProjectGroupController@index']);
        Route::get('projectsgroups/create/{projectId}', ['as' => 'admin.projects.group.create', 'uses' => 'ProjectGroupController@create']);
        Route::post('projectsgroups/create/{projectId}', ['as' => 'admin.projects.group.pstcreate', 'uses' => 'ProjectGroupController@store']);
        Route::get('projectsgroups/edit/{projectId}/{id}', ['as' => 'admin.projects.group.edit', 'uses' => 'ProjectGroupController@edit']);
        Route::post('projectsgroups/edit/{id}', ['as' => 'admin.projects.group.update', 'uses' => 'ProjectGroupController@update']);

        Route::get('projectsgroups/enable/{id}', ['as' => 'admin.projects.group.enabledisable', 'uses' => 'ProjectGroupController@enabledisable']);

        Route::get('shifts/bulk/', ['as' => 'admin.shift.bulk.index', 'uses' => 'ShiftMapController@bulkindex']);
        Route::post('shifts/bulk/create', ['as' => 'admin.shift.bulk.create', 'uses' => 'ShiftMapController@bulkcreate']);
        Route::post('shifts/bulk/store', ['as' => 'admin.shift.bulk.store', 'uses' => 'ShiftMapController@bulkstore']);

        Route::get('bank/{id}/income/pdf', ['as' => 'admin.bank.income.pdf', 'uses' => 'BankController@printIncome']);
        Route::get('bank/{id}/income/mail', ['as' => 'admin.bank.income.mail', 'uses' => 'BankController@sendmail']);

        // Route::get('mytimehistory', ['as' => 'admin.mytime.history', 'uses' => 'AttendanceController@myTimeHistory']);
        // Route::post('mytimehistory', ['as' => 'admin.mytime.history.post', 'uses' => 'AttendanceController@myTimeHistoryUpdate']);

        Route::get('mytimehistory', ['as' => 'admin.mytime.history', 'uses' => 'ShiftAttendanceController@myTimeHistory']);

        Route::post('mytimehistory', ['as' => 'admin.mytime.history.post', 'uses' => 'ShiftAttendanceController@myTimeHistoryUpdate']);

        Route::post('update_my_attendance', ['as' => 'admin.mytime.update_my_attendance', 'uses' => 'ShiftAttendanceController@myTimeHistoryStore']);

        Route::get('talk/sync/message/{id}', 'TalkController@ajaxSyncMessage');
        Route::get('talk/popover/message', 'TalkController@getPopoverMessage');

        Route::get('all/attendance_report/print/{date_in}', ['as' => 'admin.all.attendance_report.print',  'uses' => 'AttendanceController@printAllAttendance']);

        Route::get('all/attendance_report/generatePDF/{date_in}', ['as' => 'admin.all.attendance_report.generatePDF',   'uses' => 'AttendanceController@generateAllPDF']);

        Route::get('country', 'LeadsController@getcountry');
        Route::get('cities/{country}', 'LeadsController@getcity');

        Route::get('product/stocks_by_location', ['as' => 'admin.products.stocks_by_location', 'uses' => 'ProductController@stocks_by_location']);

        Route::post('product/stocks_by_location', ['as' => 'admin.products.stocks_by_location.post', 'uses' => 'ProductController@stocks_by_location_post']);

        Route::get('product/stock_adjustment', ['as' => 'admin.products.stock_adjustment', 'uses' => 'ProductController@stock_adjustment']);
        Route::post('product/stock_adjustment', ['as' => 'admin.products.stock_adjustment.store', 'uses' => 'ProductController@stock_adjustment_store']);

        Route::get('product/stock_adjustment/create', ['as' => 'admin.products.stock_adjustment.create', 'uses' => 'ProductController@stock_adjustment_create']);
        Route::get('product/stock_adjustment/{id}/edit', ['as' => 'admin.products.stock_adjustment.edit', 'uses' => 'ProductController@stock_adjustment_edit']);

        Route::post('product/stock_adjustment/{id}', ['as' => 'admin.products.stock_adjustment.update', 'uses' => 'ProductController@stock_adjustment_update']);

        Route::get('product/stock_adjustment/{id}/confirm-delete', ['as' => 'admin.products.stock_adjustment.confirm-delete',   'uses' => 'ProductController@stock_adjustment_getModalDelete']);
        Route::get('product/stock_adjustment/{id}/delete', ['as' => 'admin.products.stock_adjustment.delete',           'uses' => 'ProductController@stock_adjustment_destroy']);

         Route::get('products/excel/import-export', ['as' => 'admin.products.import-export',  'uses' => 'ProductController@inportExportView']);
        Route::get('products/downloadExcel', ['as' => 'admin.products.importexport.export', 'uses' => 'ExcelController@exportProducts']);
        Route::post('products/uploadExcel', ['as' => 'admin.budget.importexport.store', 'uses' => 'ExcelController@importProducts']);

        Route::get('product/stocks_overview', ['as' => 'admin.product.stocks_overview',  'uses' => 'ProductController@stocksOverview']);
        //Credit Note

        Route::get('credit_note/orders/show/{ordId}', ['as' => 'admin.credit_note.show', 'uses' => 'InvoiceController@showcreditnote']);
        Route::get('credit_note/orders/print/{ordId}/{type}', ['as' => 'admin.credit_note.print', 'uses' => 'InvoiceController@printcreditnote']);

        Route::get('creditnote', ['as' => 'admin.creditnote.index', 'uses' => 'CreditNoteController@index']);
        Route::get('creditnote/create', ['as' => 'admin.creditnote.create', 'uses' => 'CreditNoteController@create']);
        Route::post('creditnote', ['as' => 'admin.creditnote.store', 'uses' => 'CreditNoteController@store']);
        Route::get('creditnote/{id}', ['as' => 'admin.creditnote.show', 'uses' => 'CreditNoteController@show']);

        Route::get('creditnote/{id}/edit', ['as' => 'admin.creditnote.edit',    'uses' => 'CreditNoteController@edit']);

        Route::post('creditnote/{id}', ['as' => 'admin.creditnote.update',    'uses' => 'CreditNoteController@update']);

        Route::get('creditnote/{id}/confirm-delete', ['as' => 'admin.creditnote.confirm-delete',   'uses' => 'CreditNoteController@getModalDelete']);
        Route::get('creditnote/{id}/delete', ['as' => 'admin.creditnote.delete',           'uses' => 'CreditNoteController@destroy']);

        Route::get('creditnote/print/{id}', ['as' => 'admin.creditnote.print',  'uses' => 'CreditNoteController@printCreditNote']);
        Route::get('creditnote/generatePDF/{id}', ['as' => 'admin.creditnote.generatePDF',   'uses' => 'CreditNoteController@generatePDF']);

        Route::get('getInvoiceId', ['as' => 'admin.getInvoiceId', 'uses' => 'CreditNoteController@getInvoiceId']);

        Route::get('getInvoiceInfo', ['as' => 'admin.getInvoiceInfo', 'uses' => 'CreditNoteController@getInvoiceInfo']);
        Route::get('getbillinfo', ['as' => 'admin.getbillinfo', 'uses' => 'CreditNoteController@getbillinfo']);
        

        // WareHouse Stock Transfer

        Route::post('location/stocktransfer', ['as' => 'admin.location.stocktransfer.store',            'uses' => 'LocationStockTransferController@store']);
        Route::get('location/stocktransfer', ['as' => 'admin.location.stocktransfer.index',            'uses' => 'LocationStockTransferController@index']);
        Route::get('location/stocktransfer/create', ['as' => 'admin.location.stocktransfer.create',            'uses' => 'LocationStockTransferController@create']);

        Route::get('location/stocktransfer/{Id}', ['as' => 'admin.location.stocktransfer.show',             'uses' => 'LocationStockTransferController@show']);

        Route::get('location/stocktransfer/{Id}/edit', ['as' => 'admin.location.stocktransfer.edit',             'uses' => 'LocationStockTransferController@edit']);
        Route::post('location/stocktransfer/{Id}', ['as' => 'admin.location.stocktransfer.update',             'uses' => 'LocationStockTransferController@update']);

        Route::get('location/stocktransfer/{id}/confirm-delete', ['as' => 'admin.location.stocktransfer.confirm-delete',   'uses' => 'LocationStockTransferController@getModalDelete']);
        Route::get('location/stocktransfer/{id}/delete', ['as' => 'admin.location.stocktransfer.delete',           'uses' => 'LocationStockTransferController@destroy']);

        Route::get('getStockAvailability', ['as' => 'admin.getStockAvailability',            'uses' => 'LocationStockTransferController@getStockAvailability']);

        Route::get('location/stocktransfer/pdf/{id}', ['as' => 'admin.location.stocktransfer.pdf',            'uses' => 'LocationStockTransferController@pdf']);
        Route::get('location/stocktransfer/print/{id}', ['as' => 'admin.location.stocktransfer.print',            'uses' => 'LocationStockTransferController@print']);

        Route::get('email/downloadExcel', ['as' => 'admin.email.downloadExcel',           'uses' => 'MailController@downloadExcel']);

        Route::get('campaigns/bulk-mail/{id}',      ['as' => 'admin.campaigns.bulk-mail', 'uses' => 'CampaignsController@getbulkmail']);
        Route::post('campaigns/bulk-mail/{id}',     ['as' => 'admin.campaigns.pstbulk-mail', 'uses' => 'CampaignsController@postbulkmail']);

        Route::get('invoice/edit/{id}',             ['as' => 'admin.invoice.edit',              'uses' => 'InvoiceController@edit']);
        Route::post('invoice/edit/{id}',            ['as' => 'admin.invoice.update',            'uses' => 'InvoiceController@update']);
        Route::get('invoice/{id}/confirm-delete',   ['as' => 'admin.invoice.confirm-delete',    'uses' => 'InvoiceController@getModalDelete']);
        Route::get('invoice/{id}/delete',           ['as' => 'admin.invoice.delete',            'uses' => 'InvoiceController@destroy']);


        //niraj
        Route::get('externalsales', ['as' => 'admin.externalsales.index', 'uses' => 'ExternalSalesController@index']);
        Route::get('externalsales/create', ['as' => 'admin.externalsales.create', 'uses' => 'ExternalSalesController@create']);
        Route::post('externalsales/store', ['as' => 'admin.externalsales.store', 'uses' => 'ExternalSalesController@store']);
        Route::get('externalsales/edit/{id}',             ['as' => 'admin.externalsales.edit',              'uses' => 'ExternalSalesController@edit']);
        Route::post('externalsales/edit/{id}',            ['as' => 'admin.externalsales.update',            'uses' => 'ExternalSalesController@update']);
        Route::get('externalsales/print/{id}',             ['as' => 'admin.externalsales.print',              'uses' => 'ExternalSalesController@print']);
        Route::get('externalsales/{id}/confirm-delete',   ['as' => 'admin.externalsales.confirm-delete',    'uses' => 'ExternalSalesController@getModalDelete']);
        Route::get('externalsales/{id}/delete',           ['as' => 'admin.externalsales.delete',            'uses' => 'ExternalSalesController@destroy']);
        // Route::get('externalsales/excel', ['as' => 'admin.externalsales.excel', 'uses' => 'ExternalSalesController@excel']);
        //niraj-dispatchsheet
        Route::get('daily/dispatch/report', ['as' => 'admin.dispatch.index', 'uses' => 'DeliveryNoteController@dispatchreport']);
        Route::get('deliverysheet/excel', ['as' => 'admin.dispatch.excel',  'uses' => 'DeliveryNoteController@dispatchreportExcel']);
        // Supplier return
        Route::get('supplierreturn', ['as' => 'admin.supplierreturn.index', 'uses' => 'SupplierReturnController@index']);
        Route::get('supplierreturn/create', ['as' => 'admin.supplierreturn.create', 'uses' => 'SupplierReturnController@create']);
        Route::post('supplierreturn', ['as' => 'admin.supplierreturn.store', 'uses' => 'SupplierReturnController@store']);
        Route::get('supplierreturn/{id}', ['as' => 'admin.supplierreturn.show', 'uses' => 'SupplierReturnController@show']);

        Route::get('supplierreturn/{id}/edit', ['as' => 'admin.supplierreturn.edit',    'uses' => 'SupplierReturnController@edit']);

        Route::post('supplierreturn/{id}', ['as' => 'admin.supplierreturn.update',    'uses' => 'SupplierReturnController@update']);

        Route::get('supplierreturn/{id}/confirm-delete', ['as' => 'admin.supplierreturn.confirm-delete',   'uses' => 'SupplierReturnController@getModalDelete']);
        Route::get('supplierreturn/{id}/delete', ['as' => 'admin.supplierreturn.delete',           'uses' => 'SupplierReturnController@destroy']);

        Route::get('supplierreturn/print/{id}', ['as' => 'admin.supplierreturn.print',  'uses' => 'SupplierReturnController@print']);
        Route::get('supplierreturn/pdf/{id}', ['as' => 'admin.supplierreturn.generatePDF',   'uses' => 'SupplierReturnController@pdf']);

        Route::get('getPurchaseBillId', ['as' => 'admin.getPurchaseBillId', 'uses' => 'SupplierReturnController@getPurchaseBillId']);

        Route::get('getPurchaseBillInfo', ['as' => 'admin.getPurchaseBillInfo', 'uses' => 'SupplierReturnController@getPurchaseBillInfo']);
        //new route--niraj
        // Route::get('grn/create', ['as' => 'admin.grn.create', 'uses' => 'GrnController@create']);
        // Route::get('grn', ['as' => 'admin.grn.index', 'uses' => 'GrnController@index']);
        Route::get('deliverynote', ['as' => 'admin.deliverynote.index', 'uses' => 'DeliveryNoteController@index']);
        Route::get('deliverynote/create', ['as' => 'admin.deliverynote.create', 'uses' => 'DeliveryNoteController@create']);
        Route::post('deliverynote/store', ['as' => 'admin.deliverynote.store', 'uses' => 'DeliveryNoteController@store']);
        Route::get('deliverynote/edit/{id}', ['as' => 'admin.deliverynote.edit', 'uses' => 'DeliveryNoteController@edit']);
        Route::post('deliverynote/update/{id}', ['as' => 'admin.deliverynote.store', 'uses' => 'DeliveryNoteController@update']);
        Route::get('getSalesBillId', ['as' => 'admin.getSalesBillId', 'uses' => 'DeliveryNoteController@getSalesBillId']);
        Route::get('getSalesBillInfo', ['as' => 'admin.getSalesBillInfo', 'uses' => 'DeliveryNoteController@getSalesBillInfo']);
        Route::get('deliverynote/print/{id}', ['as' => 'admin.deliverynote.print',  'uses' => 'DeliveryNoteController@print']);
        Route::post('deliverynote/sales', ['as' => 'admin.deliverynote.sales',  'uses' => 'DeliveryNoteController@comparisionreport']);
        Route::get('deliverynote/excel', ['as' => 'admin.deliverynote.excel',  'uses' => 'DeliveryNoteController@comparisionreport']);


        Route::get('deliverynote/{id}/confirm-delete', ['as' => 'admin.deliverynote.confirm-delete',   'uses' => 'DeliveryNoteController@getModalDelete']);
        Route::get('deliverynote/{id}/delete', ['as' => 'admin.deliverynote.delete',           'uses' => 'DeliveryNoteController@destroy']);
        // download index
        Route::get('download/orders/pdf/index', ['as' => 'admin.download.orders.pdf.index', 'uses' => 'DownloadIndexController@orderpdf']);

        Route::get('download/orders/excel/index', ['as' => 'admin.download.orders.excel.index', 'uses' => 'DownloadIndexController@orderexcel']);

        Route::get('download/purchase/pdf/index', ['as' => 'admin.download.purchase.pdf.index', 'uses' => 'DownloadIndexController@purchasepdf']);

        Route::get('download/purchase/excel/index', ['as' => 'admin.download.purchase.excel.index', 'uses' => 'DownloadIndexController@purchaseexcel']);

        Route::get('expenses/{courseId}/confirm-delete', ['as' => 'admin.expenses.confirm-delete', 'uses' => 'ExpensesController@getModalDelete']);

        Route::get('expenses/{courseId}/delete', ['as' => 'admin.expenses.delete', 'uses' => 'ExpensesController@destroy']);

        Route::get('getProductName', ['as' => 'admin.getProdcutName', 'uses' => 'ProductController@getProductName']);

        Route::get('getComponentProduct', ['as' => 'admin.getComponentProduct', 'uses' => 'ProductController@getComponentProduct']);

        // bill of materials

        Route::get('billofmaterials', ['as' => 'admin.billofmaterials.index', 'uses' => 'BOMController@index']);
        Route::get('billofmaterials/create', ['as' => 'admin.billofmaterials.create', 'uses' => 'BOMController@create']);
        Route::post('billofmaterials', ['as' => 'admin.billofmaterials.store', 'uses' => 'BOMController@store']);
        Route::get('billofmaterials/{id}', ['as' => 'admin.billofmaterials.show', 'uses' => 'BOMController@show']);

        Route::get('billofmaterials/{id}/edit', ['as' => 'admin.billofmaterials.edit',    'uses' => 'BOMController@edit']);

        Route::post('billofmaterials/{id}', ['as' => 'admin.billofmaterials.update',    'uses' => 'BOMController@update']);

        Route::get('billofmaterials/{id}/confirm-delete', ['as' => 'admin.billofmaterials.confirm-delete',   'uses' => 'BOMController@getModalDelete']);
        Route::get('billofmaterials/{id}/delete', ['as' => 'admin.billofmaterials.delete',           'uses' => 'BOMController@destroy']);

        Route::get('getComponentProductInfo', ['as' => 'admin.getComponentProductInfo',           'uses' => 'BOMController@getComponentProductInfo']);

        Route::get('billofmaterials/print/{id}', ['as' => 'admin.billofmaterials.print',  'uses' => 'BOMController@print']);
        Route::get('billofmaterials/pdf/{id}', ['as' => 'admin.billofmaterials.generatePDF',   'uses' => 'BOMController@pdf']);

        Route::post('product/{courseId}/model', ['as' => 'admin.product.model', 'uses' => 'ProductModelsController@addProdModel']);

        Route::get('product/confirm-delete-prod-model/{modelId}', ['as' => 'admin.confirm-delete-prod-model', 'uses' => 'ProductModelsController@confirmdeleteProdmodel']);

        Route::get('product/delete-prod-model/{modelId}', ['as' => 'admin.delete-prod-model', 'uses' => 'ProductModelsController@deleteProdmodel']);

        Route::post('product/{courseId}/serial_num', ['as' => 'admin.product.serial_num', 'uses' => 'ProductModelsController@addProdserialnum']);

        Route::get('product/confirm-delete-prod-serial_num/{modelId}', ['as' => 'admin.confirm-delete-prod-serial_num', 'uses' => 'ProductModelsController@confirmdeleteProdserialnum']);

        Route::get('product/delete-prod-serial_num/{modelId}', ['as' => 'admin.delete-prod-serial_num', 'uses' => 'ProductModelsController@deleteProdserialnum']);

        Route::post('cases/product_info/{id}', ['as' => 'admin.cases.product_info', 'uses' => 'CasesController@productinfo']);
        Route::post('cases/model_serial_no/{id}', ['as' => 'admin.cases.model_serial_no', 'uses' => 'CasesController@productserialnum']);

        Route::get('dealer', ['as' => 'admin.dealers.index', 'uses' => 'ClientsController@dealer']);

        //assembly

        Route::get('assembly', ['as' => 'admin.assembly.index', 'uses' => 'AssemblyController@index']);
        Route::get('assembly/create', ['as' => 'admin.assembly.create', 'uses' => 'AssemblyController@create']);
        Route::post('assembly', ['as' => 'admin.assembly.store', 'uses' => 'AssemblyController@store']);
        Route::get('assembly/{id}', ['as' => 'admin.assembly.show', 'uses' => 'AssemblyController@show']);

        Route::get('assembly/{id}/edit', ['as' => 'admin.assembly.edit',    'uses' => 'AssemblyController@edit']);

        Route::post('assembly/{id}', ['as' => 'admin.assembly.update',    'uses' => 'AssemblyController@update']);

        Route::get('assembly/{id}/confirm-delete', ['as' => 'admin.assembly.confirm-delete',   'uses' => 'AssemblyController@getModalDelete']);
        Route::get('assembly/{id}/delete', ['as' => 'admin.assembly.delete',           'uses' => 'AssemblyController@destroy']);

        Route::get('/getComponentProductInfo/assembly', ['as' => 'admin.assembly.getComponentProductInfo',           'uses' => 'AssemblyController@getComponentProductInfo']);

        Route::get('/getProductName/assembly', ['as' => 'admin.assembly.getProdcutName', 'uses' => 'AssemblyController@getProductName']);
        Route::get('/getComponentProduct/assembly', ['as' => 'admin.assembly.getComponentProduct', 'uses' => 'AssemblyController@getComponentProduct']);

        Route::get('assembly/print/{id}', ['as' => 'admin.assembly.print',  'uses' => 'AssemblyController@print']);
        Route::get('assembly/pdf/{id}', ['as' => 'admin.assembly.generatePDF',   'uses' => 'AssemblyController@pdf']);

        //google sync

        Route::get('googlesync/oauth', ['as' => 'oauthCallback', 'uses' => 'GoogleCalendarController@oauth']);
        Route::get('googlesync/lead', ['as' => 'lead.oauthCallback', 'uses' => 'GoogleCalendarController@syncLeadTaskWithGoogle']);
        Route::get('googlesync/projecttask', ['as' => 'projecttask.oauthCallback', 'uses' => 'GoogleCalendarController@syncProjectTaskWithGoogle']);

        Route::get('invoice/posttoird/{id}', ['as' => 'admin.invoice.posttoird', 'uses' => 'InvoiceController@postInvoicetoIRD']);
        Route::get('invoice/return/fromird', ['as' => 'admin.invoice.returnfromird', 'uses' => 'InvoiceController@returnfromird']);
        Route::post('invoice/return/fromird', ['as' => 'admin.invoice.returnfromird.post', 'uses' => 'InvoiceController@returnfromirdpost']);
        Route::get('invoice/return/sales', ['as' => 'admin.invoice.return.sales', 'uses' => 'InvoiceController@returnsales']);
        Route::get('invoice/return/sales/list', ['as' => 'admin.invoice.return.sales.list', 'uses' => 'InvoiceController@returnsaleslist']);

        // materalized view
        Route::get('invoice/sales/materalize', ['as' => 'admin.invoice.materalize', 'uses' => 'InvoiceController@materializeview']);
        Route::get('invoice/sales/materalize/result', ['as' => 'admin.invoice.materalize.result', 'uses' => 'InvoiceController@materializeviewresult']);

        Route::get('documents/userlist/{id}', ['as' => 'admin.documents.userlist', 'uses' => 'DocumentController@userlist']);
        Route::post('documents/share/{id}', ['as' => 'admin.documents.share', 'uses' => 'DocumentController@docsharepost']);

        Route::get('migrate-data/{table}',['as'=>'admin.migrateData','uses'=>'FiscalyearController@migrateData']);

    }); // End of ADMIN group

    //Talk Ajax
    Route::group(['prefix' => 'ajax', 'as' => 'ajax::'], function () {

        Route::post('message/send', 'TalkController@ajaxSendMessage')->name('message.new');
        Route::post('message/seen/{cid}', 'TalkController@ajaxSeenMessage')->name('message.seen');
        Route::post('message/more/{id}/{start}/{end}', 'TalkController@moreMessage')->name('message.start');
        Route::delete('message/delete/{id}', 'TalkController@ajaxDeleteMessage')->name('message.delete');
    });

    // Uncomment to enable Rapyd datagrid.
    // require __DIR__.'/rapyd.php';
}); // end of AUTHORIZE middleware group
