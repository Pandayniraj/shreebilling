<?php

namespace App\Http\Controllers;

use App\Models\Biom_device;
use App\Models\Biom_employee;
use App\Models\Biom_log;
use App\Models\Holiday;
use App\Mylibs\ZKLibrary;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZKLib\ZKLib;
use Rats\Zkteco\Lib\ZKTeco;


class BiometricController extends Controller
{
    public function connectdevice()
    {
        // $active_device_ip = Biom_device::select('ip_address')->where('isActive', '1')->first();
        // if ($active_device_ip) {
        //     $zk = new ZKLibrary($active_device_ip->ip_address, 4370);
        //     $zk->connect();

        //     return $zk;
        // }
        $active_device_ip = Biom_device::select('ip_address')->where('isActive', '1')->first();

        $zk = new ZKTeco($active_device_ip->ip_address);
      
        $zk->connect();   
         
        //$zk->setTime(date('Y-m-d H:i:s'));
        return $zk;
        dd('NO ANY DEVICE CONNECTED');
    }

    public function deviceInfo()
    {
        $active_device_ip = Biom_device::select('ip_address')->where('isActive', '1')->first();
        $zk = new ZKLib($active_device_ip->ip_address);
        $zk->connect();

        return ['name'=>$zk->getDeviceName(), 'serial_number'=>$zk->getSerialNumber()];
    }

    public function index()
    {
        $device_ = Biom_device::where('isActive', '1')->first();
        $bio_employee = Biom_employee::where('device_id', $device_->id)->get();

        return view('admin.BioMetric.index', compact('bio_employee', 'device_'));
    }

    public function machineList()
    {
        $availableMachine = Biom_device::all();

        return view('admin.BioMetric.machinelist', compact('availableMachine'));
    }

    public function ActivateDevice(Request $request)
    {
        $device_id = $request->device_id;
        DB::update("update biom_devices set isActive = (case when id = '$device_id' then '1' else '0' end)");
        Flash::success('Device Activated');

        return redirect('/admin/biometricmachine');
    }

    public function ImportDevice()
    {
        return view('admin.BioMetric.ImportDeviceForms');
        // $deviceInfo=$this->deviceInfo();
    // $index=0;
    // try{
    // $device=['device_name'=>$deviceInfo['name'],'serial_number'=>$deviceInfo['serial_number'],'ip_address'=>env('DEVICE1_IP')];
    // Biom_device::create($device);
    // $index++;
    // }
    // catch(\Illuminate\Database\QueryException $ex){

    // }
    // finally{
    //  return Redirect::back()->with('message',$index . " Record Added");
    // }
    }

    public function ImportDevice1(Request $request)
    {
        Biom_device::create($request->all());

        return redirect('/admin/biometricmachine');
    }

    public function TimeHistory()
    {
        $BioUsers = Biom_employee::all();
        $id = null;

        return view('admin.BioMetric.timeHistory', compact('BioUsers', 'id'));
    }

    public function showAttendence(Request $request)
    {
        $id = $request->emp_id;
        $date_in = $request->date_in;
        $date_start = $date_in.'-01';
        $totaldays = \Carbon\Carbon::parse($date_start)->daysInMonth;
        $date_end = $date_in.'-'.$totaldays;
        $datetime = DB::select("select DISTINCT(DATE(datetime)) as date FROM biom_logs where machine_userid='$id' AND Date(datetime) >= '$date_start' AND Date(datetime)<='$date_end' ORDER BY date DESC");
        $datetime = json_decode(json_encode($datetime), true);
        $history = [];
        foreach ($datetime as $key) {
            $date = $key['date'];
            $attendence_rec = DB::select("select biom_logs.id , biom_logs.machine_userid,biom_logs.verification_mode ,biom_logs.datetime, biom_devices.ip_address,biom_employees.name from biom_logs LEFT JOIN biom_employees ON biom_logs.machine_userid = biom_employees.machine_userid
            LEFT JOIN biom_devices ON biom_devices.id = biom_logs.device_id
            where biom_logs.machine_userid='$id' and DATE(datetime) ='$date' ORDER BY biom_logs.created_at limit 4");
            $attendence_rec = json_decode(json_encode($attendence_rec), true);
            array_push($history, $attendence_rec);
        }
        $BioUsers = Biom_employee::all();

        return view('admin.BioMetric.timeHistory', compact('BioUsers', 'history', 'date_in', 'id'));
    }

    public function showAllAttendence()
    {
        $attendence_rec = DB::select('select biom_logs.id , biom_logs.machine_userid ,biom_logs.verification_mode,biom_logs.datetime, biom_employees.name from biom_logs LEFT JOIN biom_employees ON biom_logs.machine_userid = biom_employees.machine_userid ');
        $attendence_rec = json_decode(json_encode($attendence_rec), true);

        return view('admin.zktattendence.showattendence', compact('attendence_rec'));
    }

    public function ImportEmployee()
    {
        $zk = $this->connectdevice();
        $device_id = Biom_device::select('id')->where('isActive', '1')->first();
        $bio_employee = $zk->getUser();
        $index = 0;
        foreach ($bio_employee as $key) {
            if (! (Biom_employee::where('device_id', $device_id->id)->where('machine_userid', $key[0])->exists())) {
                $employee = ['machine_userid'=>$key[0], 'name'=>$key[1], 'privilege'=>$key[2], 'device_id'=>$device_id->id];
                Biom_employee::create($employee);
                $index++;
            }
        }
        if ($index == 0) {
            Flash::warning('No any new employee to import');
        } else {
            Flash::success($index.' Record imported in employee tables');
        }

        return redirect('admin/biometric');
    }

    public function ImportAttendence(Request $request)
    {
        $zk = $this->connectdevice();

        $device_id = Biom_device::select('id')->where('isActive', '1')->first();
        $bio_attendence = $zk->getAttendance();
        
        dd($bio_attendence);
        $index = 0;
        foreach ($bio_attendence as $key) {
            if (! (Biom_log::where('device_id', $device_id->id)->where('machine_attendence_id', $key[0])->exists())) {
                $attendence = ['machine_attendence_id'=>$key[0], 'machine_userid'=>$key[1], 'datetime'=>$key[3], 'device_id'=>$device_id->id, 'verification_mode'=>$key[2]];
                Biom_log::create($attendence);
                $index++;
            }
        }
        if ($index == 0) {
            Flash::warning('No any new Record to import');
        } else {
            Flash::success($index.' Record imported in attendence tables');
        }

        return redirect('admin/biometric');
    }

    public function AttendanceReportIndex()
    {
        $page_title = 'Attendance';
        $page_description = 'Attendance Report';
        $attendance = null;
        $users = Biom_employee::all();

        return view('admin.BioMetric.attendancereport', compact('page_title', 'page_description', 'attendance', 'users'));
    }

    public function AttendanceReport(Request $request)
    {
        $page_title = 'Attendance';
        $page_description = 'Attendance Report';
        //dd($request->all());
        $date_in = $request->date_in;
        $attendance = Biom_employee::all();
        //dd($attendance);
        $holidays = Holiday::select('event_name', 'start_date', 'end_date')->where('start_date', '>=', $date_in.'-01')->where('end_date', '<=', $date_in.'-32')->get();
        $users = Biom_employee::all();

        return view('admin.BioMetric.attendancereport', compact('page_title', 'page_description', 'users', 'date_in', 'attendance', 'holidays', 'users'));
    }

    public function addUser(){

        $zk = $this->connectdevice();

        $users = \App\User::where('enabled','1')->get();
        // $user = [
        //     'userid'=>2,
        //     'name'=>"suman thapa",
        //     'role'=>0,
        //     'password'=>'123',

        // ];
        // $zk->setUser(2,2,'suman thapa',123,0);
        // dd($zk->getUser());
        // dd($zk->serialNumber());


        return view('admin.BioMetric.add_user',compact('users'));


    }

    public function storeUser(Request $request){

        $user = $request->user_id;
        $user  = \App\User::find($user);
        $zk = $this->connectdevice();

        $id = $user->id;

        $userid  = $user->id;

        $name = $user->first_name .' '.$user->last_name;

        $password = $request->password; 
        
        $zk->setUser(
             $id , $userid ,$name ,$password
        );
    


        \Flash::success("USER IMPORTED");


        return redirect()->back();


    }

    public function editDevice($id){

       $device = Biom_device::find($id);

       return view('admin.BioMetric.edit_device',compact('device'));


    }

    public function updateDevice( Request $request ,$id){

       $device = Biom_device::find($id);

       $attributes = $request->all();

       $device->update($attributes);

       Flash::success("DEVICE UPDATED");

       return redirect()->back();



    }
}
