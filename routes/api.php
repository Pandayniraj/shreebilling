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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::any('user/login', 'Mobile\APILoginController@login');
Route::get('product', 'Mobile\FetchController@product');
Route::get('status', 'Mobile\FetchController@status');
Route::get('lead_type', 'Mobile\FetchController@lead_type');
Route::get('getcalllogs', 'Mobile\FetchController@getcallLogs');
Route::any('leadtask', 'Mobile\FetchController@leadTask');
Route::get('leads', 'Mobile\FetchController@Leads');
Route::get('countTask', 'Mobile\FetchController@countTask');
Route::get('sendUser', 'Mobile\FetchController@sendUser');
Route::get('exportContact', 'Mobile\FetchController@sendContact');
Route::get('allTask', 'Mobile\FetchController@allTask');
Route::get('clockin', 'Mobile\ClockController@clockin');
Route::get('clockout', 'Mobile\ClockController@clockout');
Route::get('clocking_status', 'Mobile\ClockController@clocking_status');
Route::get('addTask', 'Mobile\LeadData@addTask');
Route::get('importContact', 'Mobile\LeadData@importContact');
Route::get('completeTask', 'Mobile\LeadData@completeTask');
Route::get('addcalllogs', 'Mobile\LeadData@addcallLogs');
Route::any('postenquiry', 'Mobile\LeadData@PostLead');

Route::any('support', 'Mobile\LeadData@supportCase');
Route::get('viewsupport/{id}', 'Mobile\FetchController@showSupport');
Route::get('closesupport/{id}', 'Mobile\LeadData@closeSupport');
Route::get('sendprojecttaskcat', 'Mobile\FetchController@projectTaskCat');
Route::get('sendproject', 'Mobile\FetchController@projectID');
Route::any('projecttask', 'Mobile\LeadData@projectTask');
Route::get('showprojecttask/{id}', 'Mobile\FetchController@showProjectTask');
Route::get('closeproject-task/{id}', 'Mobile\LeadData@closeProjectTask');

//cases api
Route::get('cases/dashboard/{token}', 'Mobile\CasesApiController@dashboard');
Route::get('cases/viewjob/{token}/{type}', 'Mobile\CasesApiController@viewjob');
Route::get('cases/searchjob/{token}/{type}', 'Mobile\CasesApiController@searchjob');
Route::get('cases/productdetails/{token}/{jobid}', 'Mobile\CasesApiController@productdetails');
Route::get('cases/productmodel/{token}/{product_id}', 'Mobile\CasesApiController@productmodel');
Route::get('cases/productserialnum/{token}/{model_id}', 'Mobile\CasesApiController@productserialnum');
Route::get('cases/updateProducts/{token}/{jobid}', 'Mobile\CasesApiController@updateProducts');
Route::get('cases/sparedetails/{token}/{jobid}', 'Mobile\CasesApiController@sparedetails');
Route::get('cases/addspare/{token}', 'Mobile\CasesApiController@addspare');
Route::get('cases/sparedetails/delete/{token}/{spareid}', 'Mobile\CasesApiController@sparedestroy');
Route::any('cases/updatestatus/{token}/{jobid}', 'Mobile\CasesApiController@updatestatus');
Route::get('cases/viewstatus/{token}/{jobid}', 'Mobile\CasesApiController@viewstatus');
Route::get('cases/createjob/{token}', 'Mobile\CasesApiController@createjob');
Route::get('cases/storejob/{token}', 'Mobile\CasesApiController@storejob');
Route::get('cases/tracklocation', 'Mobile\CasesApiController@trackloaction');
Route::get('cases/monitorlocation/{token}', 'Mobile\CasesApiController@monitorlocation');

//hrm api

Route::get('hrmdashboard/{token}/{user_id}', 'Mobile\HrmApiController@dashboard');
Route::get('leaveindex/{token}/{user_id}', 'Mobile\HrmApiController@leaveindex');
Route::post('leave/{token}', 'Mobile\HrmApiController@postleave');
Route::get('payrollindex/{token}/{user_id}', 'Mobile\HrmApiController@payrollindex');
Route::get('attendancereport/{token}/{user_id}/{date_in}', 'Mobile\HrmApiController@attendencehistory');
Route::get('holiday/{token}/{date_in}', 'Mobile\HrmApiController@holiday');
Route::get('tarvelrequest/info/{token}/{user_id}', 'Mobile\HrmApiController@travelrequest');
Route::get('tarvelrequest/create/{token}', 'Mobile\HrmApiController@create_travelrequest');
Route::any('tarvelrequest/store/{token}/', 'Mobile\HrmApiController@store_travelrequest');

//chat api

Route::post('chatindex/{token}', 'Mobile\ChatApiController@chatIndex');

Route::post('chathistroy/{token}', 'Mobile\ChatApiController@chatHistory');
Route::post('sendmessage/{token}', 'Mobile\ChatApiController@sendMessage');
Route::post('moremessage/{token}', 'Mobile\ChatApiController@moreMessage');
Route::get('typing/{token}', 'Mobile\ChatApiController@typing');
