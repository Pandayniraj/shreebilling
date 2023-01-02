<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HrCalandarController extends Controller
{

	

	public function holidays($start_date,$end_date){


		$holidayData = \App\Models\Holiday::where("start_date",'>=',$start_date)
				->where('end_date','<=',$end_date)->orderBy('start_date','asc')->get();

		$data = [];
		foreach ($holidayData as $key => $value) {

		$data [] =[	
			'title'=>'<i class="fa  fa-calendar-o"></i> '.$value->event_name,
			'start'=> $value->start_date,
			'end'=> $value->end_date,
			'url' => '#',
			'backgroundColor'=>'#2196F3',
			'id'=>$value->id,
			'description'=>$value->description
		];
		}

		return $data;
	}


	public function birthdays($start_date){

		$month = date('m',strtotime($start_date));
		$year = date('Y',strtotime($start_date));
		$birthdayData = \App\User::orderBy('id', 'desc')->whereMonth("dob",$month)
					->get();

		$data = [];
		foreach ($birthdayData as $key => $value) {
		
			$data [] =[	
				'title'=>'<i class="fa  fa-birthday-cake"></i> '.$value->first_name.' '.$value->last_name,
				'start'=>  $year.'-'.date('m-d',strtotime($value->dob)),
				'end'=>  $year.'-'.date('m-d',strtotime($value->dob)),
				'url' => '#',
				'backgroundColor'=>'#F44336',
				'id'=>$value->id,
				'description'=>'Happy Birthday'
			];
		}
	
		return $data;
	}

	public function work_aniversary($start_date){
		$month = date('m',strtotime($start_date));
		$year = date('Y',strtotime($start_date));

		$birthdayData = \App\Models\UserDetail::whereMonth("join_date",$month)
					->orderBy('join_date', 'desc')
					->get();

		$data = [];
		foreach ($birthdayData as $key => $value) {
			$user = $value->user;
			$data [] =[	
				'title'=>'<i class="fa  fa-calendar"></i> '.$user->first_name.' '.$user->last_name,
				'start'=>  $year.'-'.date('m-d',strtotime($value->join_date)),
				'end'=>  $year.'-'.date('m-d',strtotime($value->join_date)),
				'url' => '#',
				'backgroundColor'=>'#4CAF50',
				'id'=>$value->id,
				'description'=>'Happy Aniversary'
			];
		}
		
		return $data;
	}


	public function leave($start_date,$end_date){

		
		$leavedata = \App\Models\LeaveApplication::where('application_status', '2')->where('leave_start_date', '>=', $start_date)->where('leave_end_date', '<=', $end_date)
			->orderBy('leave_start_date','asc')
			->get();
		$data  = [];
		foreach ($leavedata as $key => $value) {
			$user = $value->user;



			$data [] = [

				'title'=>'<i class="fa fa-hourglass"></i> '.$user->first_name.' '.$value->partOfDay(),
				'start'=> $value->leave_start_date,
				'end'=> $value->leave_end_date,
				'url' => '#',
				'backgroundColor'=>'#3F51B5',
				'id'=>$value->id,
				'description'=>'On Leave',
			];

		}

		return $data;

	}




	public function leaveByUser($start_date,$end_date,$user_id){

		
		$leavedata = \App\Models\LeaveApplication::where('leave_start_date', '>=', $start_date)->where('leave_end_date', '<=', $end_date)
			->where('user_id',$user_id)
			->orderBy('leave_start_date','asc')
			->get();
		
		$data  = [];
		
		$user = $leavedata->first()->user ?? '';


		foreach ($leavedata as $key => $value) {
	
			if($value->application_status == 1){
				$color = 'orange';
				$leave_status = '(Pending)';
			}elseif ($value->application_status == 2) {
				$color = 'green';
				$leave_status = '(Accepted)';
			}else{
				$color = 'red';
				$leave_status = '(Rejected)';
			}

			$data [] = [

				'title'=>'<i class="fa fa-hourglass"></i> '.$value->partOfDay().' '.$leave_status,
				'start'=> $value->leave_start_date,
				'end'=> $value->leave_end_date,
				'url' => '#',
				'backgroundColor'=>$color,
				'id'=>$value->id,
				'description'=>'On Leave',
			];

		}
		//dd($data);

		return $data;

	}




    public function index(){

    	if(\Request::get('date_range')){

    		$start_date = date('Y-m-01',strtotime(\Request::get('date_range')));

    		$end_date = date('Y-m-t',strtotime(\Request::get('date_range')));

    	}else{
	    	$start_date = date('Y-m-01');
			$end_date = date('Y-m-t');

    	}
    	$page_title = 'Hr Board';
    	
		$hrData =[];
		$leaveData = $this->leave( $start_date,$end_date );
		$holidayData =  $this->holidays( $start_date,$end_date );
		$birthdayData =  $this->birthdays( $start_date );
		$work_aniversaryData = $this->work_aniversary($start_date);
		$hrData = array_merge($leaveData,$hrData);			
		$hrData = array_merge($holidayData,$hrData);	
		$hrData = array_merge($birthdayData,$hrData);		
		$hrData = array_merge($work_aniversaryData,$hrData);	

    	return view('hrcalandar',compact('hrData','leaveData','holidayData','birthdayData','work_aniversaryData','start_date','page_title'));

    }

    public  function  getUserAttendence($uid,$start_date,$end_date){


    	
    	$begin = new \DateTime($start_date);
		$end = new \DateTime($end_date);
		$end = $end->modify( '+1 day' ); 
		$interval =  new \DateInterval('P1D');
		$period = new \DatePeriod($begin, $interval, $end);


    	$userAttendace = \App\Models\ShiftAttendance::where('date','>=',$start_date)
    					->where('date','<=',$end_date)->where('user_id',$uid)->orderBy('date','asc')->get();

    	$hrData = [];

    	$attendaceData = [];
	    foreach ($period as $dt) {
	   		
	    	$date = $dt->format('Y-m-d');

	    	$current_attendance = $userAttendace->where('date',$date)->sortBy('time');

	    	foreach ($current_attendance as $key => $value) {
	    		
	    		if($value->is_adjusted){
	    			$color= '#F44336';
	    		}elseif( $key % 2 == 0){ //out
	    			$color='#009688';
	    		}else{
	    			$color = '#03A9F4';
	    		}

		    	$attendaceData [] = [

					'title'=>'<i class="fa  fa-clock-o"></i>&nbsp;'.date('H:i:s',strtotime($value->time)),
					'start'=> $value->date,
					'end'=> $value->date,
					'url' => '#',
					'backgroundColor'=>$color,
					'id'=>$value->id,
					'description'=>'',
				];


	    	}


		}

		$hrData = array_merge($attendaceData,$hrData);


		$leavedata = $this->leaveByUser($start_date,$end_date,$uid);

		$hrData = array_merge($leavedata,$hrData);

		$holidayData =  $this->holidays( $start_date,$end_date );

		$hrData = array_merge($holidayData,$hrData);


		return $hrData;


    } 



    public function attendaceCalendar($uid){


    	if(\Request::get('date_range')){

    		$start_date = date('Y-m-01',strtotime(\Request::get('date_range')));

    		$end_date = date('Y-m-t',strtotime(\Request::get('date_range')));

    	}else{
	    	$start_date = date('Y-m-01');
			$end_date = date('Y-m-t');

    	}

    	$hrData = $this->getUserAttendence($uid,$start_date,$end_date);

		$page_title = 'User Attedance';
		$user = \App\User::find($uid);
		$form_action = route('admin.attendace.calandar',$user->id);

		return view('admin.users.attedance_calendar',compact('hrData','start_date','user','page_title','form_action'));
    }


    public function showMyAttendace(){

    	if(\Request::get('date_range')){

    		$start_date = date('Y-m-01',strtotime(\Request::get('date_range')));

    		$end_date = date('Y-m-t',strtotime(\Request::get('date_range')));

    	}else{
	    	$start_date = date('Y-m-01');
			$end_date = date('Y-m-t');

    	}
    	
    	$uid = \Auth::user()->id;
    	
    	$user = \App\User::find($uid);

    	$hrData = $this->getUserAttendence($uid,$start_date,$end_date);

    	$page_title = 'My Attedance';

    	$form_action = route('admin.my_attendance');

    	return view('admin.users.attedance_calendar',compact('hrData','start_date','user','page_title','form_action'));

    }


   




}
