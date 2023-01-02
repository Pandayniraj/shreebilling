<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UserImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */


    public function updateFix($userdata){


        foreach ($userdata as $key => $value) {
            $usernameArr = explode(' ', $value['employee_name']);
            $username = strtolower(implode('.',$usernameArr));  
            $user = \App\User::where('username',$username)->first();
            $userdetails = \App\Models\UserDetail::where('user_id',$user->id)->first();
            $userdetailsAtrr['join_date'] = $value['doj'];
            $userdetailsAtrr['date_of_birth'] =  $user->dob;
            $userdetailsAtrr['nationality'] = 'Nepali';
            $userdetailsAtrr['mobile'] = $value['mobile'];
            $userdetails->update($userdetailsAtrr);
        }
        dd("DONE");
    }
    public function handle()
    {

        $path = storage_path().'/userlist.xlsx';
      
        $data = \Excel::toCollection(new \App\Exports\ExcelImport(), $path)->toArray();
        $userdata = $data[1];
        $this->updateFix($userdata);
        foreach ($userdata as $key => $value) {
            
            $usernameArr = explode(' ', $value['employee_name']);
            $username = strtolower(implode('.',$usernameArr));  
            $deparments = \App\Models\Department::where('deptname','LIKE',$value['department_name'])->first();

            if(!$deparments){
                $deparments = $this->create_department($value['department_name']);
                $deparments->departments_id = $deparments->id;
            }
            
            $deginationName = explode('-', $value['designation']);

            $degination = \App\Models\Designation::where('designations','LIKE',$deginationName[0])
                        ->where('departments_id',$deparments->departments_id)
                        ->first();

            if(!$degination ){

              try{
                  $degination = $this->create_degination($deginationName[0],$deparments->departments_id);
                  $degination->designations_id = $degination->id;
  
              }catch(\Exception $e){
                dd($e);
              }
                

            }
            
            $data = [
                'first_name'=>$usernameArr[0],
                'last_name'=>$usernameArr[1],
                'username'=>$username,
                'email'=>$value['email'] ??  $username.'@gninepal.com',
                'password'=>\Hash::make('gni2021'),
                'auth_type'=>'internal',
                'enabled'=>'1',
                'int_designation'=>$value['internal_designation'],
                'org_id'=>'1',
                'dob'=>$value['dob'],
                'work_station'=>$value['work_station'],
                'departments_id'=>$deparments->departments_id,
                'designations_id'=>$degination->designations_id,
                'phone'=>$value['mobile'],
                'position'=>$value['position'],
                'first_line_manager'=>$value['first_line_manager'],
                'second_line_manager'=>$value['second_line_manager']??'',
                'division'=>$value['division_name'],
            ];

            try{
            $user = \App\User::create($data);

             $user->forceRole('users');
             $user->forceRole('profile-managers');
             $user->forceRole('clockin-clockout');
             $user->forceRole('chat-manager');
             $user->forceRole('leave-manager');
             $user->forceRole('attendance');
             $user->forceRole('calendar');

             $this->adduserDetails($user,$value);  
            }catch(\Exception $e){
                dd($user,$e);
            }
    
        }


        $this->createLedger();
      
        return 0;
    }


    public function adduserDetails($user,$value){


        $data = [
            'user_id'=>$user->id,
            'marital_status'=>$value['marital_status'],
            'gender'=>$value['gender'],
            'ethnicity'=>$value['ethnicity'],
            'join_date'=>$value['join_date'],

        ];

        \App\Models\UserDetail::create($data);


    }

    public function create_department($name){



        $dept = \App\Models\Department::create(

            [

                'deptname'=>$name,
                'org_id'=>'1',

            ]

        );

        return $dept;



    }

    public function create_degination($name,$dept){


        $desg =   \App\Models\Designation::create(['departments_id' => $dept, 
                    'designations' => $name,
                    'org_id'=>'1' ]);

        return $desg;

    }

        public static function getNextCodeLedgers($id)
    {
        $group_data = \App\Models\COAgroups::find($id);
        $group_code = $group_data->code;

        $q = \App\Models\COALedgers::where('group_id', $id)->where('org_id', \Auth::user()->org_id)->where('code', '!=', 'null')->get();

        if ($q) {
            $last = $q->last();
            $last = $last->code;
            $l_array = explode('-', $last);
            $new_index = end($l_array);
            $new_index += 1;
            $new_index = sprintf('%04d', $new_index);

            return $group_code.'-'.$new_index;
        } else {
            return $group_code.'-0001';
        }
    }



        private function PostLedgers($name, $org_id)
    {
        $detail = new \App\Models\COALedgers();
        $detail->group_id = \FinanceHelper::get_ledger_id('USER_LEDGER_GROUP', $org_id);

        $detail->org_id = $org_id;
        $detail->user_id = \Auth::user()->id;
        $detail->org_id = $org_id;
        $detail->code = $this->getNextCodeLedgers(\FinanceHelper::get_ledger_id('USER_LEDGER_GROUP', $org_id), $org_id);
        $detail->name = $name;
        $detail->op_balance_dc = 'D';
        $detail->op_balance = 0.00;
        $detail->notes = $name;
        $detail->ledger_type = 'Staff Group';
        $detail->staff_or_company_id = \FinanceHelper::get_ledger_id('USER_LEDGER_GROUP', $org_id);

        if ($request->type == 1) {
            $detail->type = $request->type;
        } else {
            $detail->type = 0;
        }
        if ($request->reconciliation == 1) {
            $detail->type = $request->reconciliation;
        } else {
            $detail->reconciliation = 0;
        }
        $detail->save();

        return $detail->id;
    }


    public function createLedger(){

        $userData = \App\User::whereNotIn('id',['1','2'])->get();
        foreach ($userData as $key => $user) {
            
            $full_name = $user->first_name . ' ' . $user->last_name;

            $_ledgers = $this->PostLedgers($full_name, $user->org_id);
            // Run the update method to set enabled status and roles membership.
            $attributes['ledger_id'] = $_ledgers;

            $user->update($attributes);
        }

        dd("VAYO LEDGERs PANI");
    }






}
