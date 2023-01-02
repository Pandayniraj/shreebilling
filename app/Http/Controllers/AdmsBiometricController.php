<?php

namespace App\Http\Controllers;

use App\Models\ShiftAttendance;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class AdmsBiometricController extends Controller
{




	  public function biometricattendanceReports(Request $request)
    { 
       $requestData = $request->all();
       $page_title = 'Attendance | history';
       $page_description = 'Filter User By attendance';
       $devices = DB::table('admsDevices')->get();
       $start_date = $request->start_date;
       $end_date = $request->end_date;
       $userpin = $request->userpin;
       $users = \App\User::get();
       if(!empty($request->input('DeviceSerialNo')) && !empty($request->input('start_date')) && !empty($request->input('end_date'))){
          $date1 = date("Y/m/d", strtotime($request->start_date));
          $date2 = date("Y/m/d", strtotime($request->end_date));
          $attendance_data = \App\Helpers\AttendanceHelper::get_adms_data($request->DeviceSerialNo,$date1,$date2);
          // $return_data = $return_data->where('UserPin',$request->userpin)->get(); 
          if(!empty($request->input('userpin'))){
          $return_data  = (collect($attendance_data->Data)->where('UserPin',$userpin));
          }else{ $return_data = collect($attendance_data->Data); }
        
          // dd($return_data);
       }
      return view('admin.adms.index',compact('devices','users','return_data','start_date','end_date','requestData'));
    }


    public function getDeviceStatus()
    {

        // Branch Code
         
            $curl = curl_init();
            curl_setopt_array($curl, array(
              // CURLOPT_URL => 'http://202.51.74.187:8454/api/AdmsEx/GetDeviceByBranch?branchCode=BG',
              CURLOPT_URL => 'http://202.51.74.187:8454/api/AdmsEx/checkDeviceInfo?sn=A8LW203560216',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
              ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);


            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
                // dd($response);
              $result = json_decode($response); 
              dd($result);
          }

    }


    public function downloadDiviceattendance(Request $request)
    {

      $requestData = $request->all();
       $page_title = 'Attendance | history';
       $page_description = 'Filter User By attendance';
       $devices = DB::table('admsDevices')->get();
       $start_date = $request->start_date;
       $end_date = $request->end_date;
       $userpin = $request->userpin;
       $users = \App\User::get();
       if(!empty($request->input('DeviceSerialNo')) && !empty($request->input('start_date')) && !empty($request->input('end_date'))){
          $date1 = date("Y/m/d", strtotime($request->start_date));
          $date2 = date("Y/m/d", strtotime($request->end_date));
          $attendance_data = \App\Helpers\AttendanceHelper::get_adms_data($request->DeviceSerialNo,$date1,$date2);
          // $return_data = $return_data->where('UserPin',$request->userpin)->get(); 
          if(!empty($request->input('userpin'))){
          $return_data  = (collect($attendance_data->Data)->where('UserPin',$userpin));
          }else{ $return_data = collect($attendance_data->Data); }
       }
        return \Excel::download(new \App\Exports\ExcelDiviceAttendanceExport($return_data,$start_date,$end_date,$request->DeviceSerialNo), 'device_attendance.xlsx');
    }

   
}
