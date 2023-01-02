<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Knowledge;
use App\Models\Lead;
use App\User;
use Excel;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
/**
FOR ONLINE ENQUIRY

 **/
class ExcelController extends Controller
{
    /**
     * @var Contact
     */
    public function __construct(Contact $contact)
    {
        parent::__construct();
        $this->contact = $contact;
    }

    public function index()
    {
        Audit::log(Auth::user()->id, 'Excel', 'Import Export the contacts excel file.');

        $page_title = 'Import Export Contact';
        $page_description = 'Import Export the Contact Excel File';

        return view('admin.excel.importExport', compact('page_title', 'page_description'));
    }

    public function downloadExcel($type)
    {
        $data = Contact::where('org_id', Auth::user()->org_id)->get()->toArray();

        return \Excel::download(new \App\Exports\ExcelExport($data), "contacts.{$type}");
    }

    public function importExcel()
    {
        if (Input::hasFile('import_file')) {
  
            $data = $data = \Excel::toCollection(new \App\Exports\ExcelImport(), \Request::file('import_file'));

            if (! empty($data) && $data->count()) {
                $data = $data->first()->toArray();
                foreach ($data as $key => $value) {
                    $value = (object) $value;
                    $insert[] = ['client_id' => $value->client_id, 'salutation' => $value->salutation, 'full_name' => $value->full_name, 'position' => $value->position, 'department' => $value->department, 'email_1' => trim($value->email_1, urlencode('%A0')), 'email_2' => trim($value->email_2, urlencode('%A0')), 'phone' => $value->phone, 'landline' => $value->landline, 'address' => $value->address, 'city' => $value->city, 'postcode' => $value->postcode, 'country' => $value->country, 'website' => $value->website, 'facebook' => $value->facebook, 'enabled' => $value->enabled, 'created_at' => date('Y-m-d H:i:s'), 'org_id'=>$value->org_id];
                }
                if (! empty($insert)) {
                    Contact::insert($insert);

                    Flash::success('Contact Record Insert successfully.');

                    return redirect()->back();
                }
            }
        }

        Flash::success('Sorry no file is selected to import contacts.');

        return redirect()->back();
    }

    public function leads()
    {
        Audit::log(Auth::user()->id, 'Excel', 'Import Export the leads excel file.');

        $page_title = 'Import Export Leads';
        $page_description = 'Import Export the Leads Excel File';

        return view('admin.excel.importExportLeads', compact('page_title', 'page_description'));
    }

    public function downloadExcelLeads($type)
    {
        $data = Lead::select('title', 'name', 'mob_phone', 'home_phone', 'email', 'description', 'product_id', 'campaign_id', 'city', 'country')
                    ->where('org_id', Auth::user()->org_id)
                    ->get()
                    ->toArray();

        return \Excel::download(new \App\Exports\ExcelExport($data), "leads.{$type}");
    }

    public function importExcelLeads()
    {
        if (Input::hasFile('import_file')) {
            $path = Input::file('import_file')->getRealPath();
            $data = $data = \Excel::toCollection(new \App\Exports\ExcelImport(), Input::file('import_file'));

            if (! empty($data) && $data->count()) {
                $data = $data->first()->toArray();
                foreach ($data as $key => $value) {
                    $value = (object) $value;
                    $insert[] = [
                                    //default values
                                    'lead_type_id' 		=> '3',
                                    'org_id' 			=> '1',
                                    'enabled' 			=> '1',
                                    'user_id' 			=> '1',
                                    'created_at' 		=> date('Y-m-d H:i:s'),
                                    //excel sheet user values
                                    'title' 			=> $value->title,
                                    'name' 				=> $value->name,
                                    'mob_phone' 		=> $value->mob_phone,
                                    'home_phone' 		=> $value->home_phone,
                                    'email' 			=> trim($value->email, urlencode('%A0')),
                                    'description'		=> $value->description,
                                    'product_id' 		=> $value->product_id,
                                    'campaign_id' 		=> $value->campaign_id,
                                    'city' 				=> $value->city,
                                    'country' 			=> $value->country,

                                ];
                }
                if (! empty($insert)) {
                    Lead::insert($insert);

                    Flash::success('Lead Record Insert successfully.');

                    return redirect()->back();
                }
            }
        }

        Flash::success('Sorry no file is selected to import leads.');

        return redirect()->back();
    }

    public function indexClients()
    {
        Audit::log(Auth::user()->id, 'Excel', 'Import Export the contacts excel file.');

        $page_title = 'Import Export Contact';
        $page_description = 'Import Export the Contact Excel File';

        return view('admin.excel.importExportClient', compact('page_title', 'page_description'));
    }

    public function importExcelClients()
    {
        if (Input::hasFile('import_file')) {
            $path = Input::file('import_file')->getRealPath();
            $data = $data = \Excel::toCollection(new \App\Exports\ExcelImport(), Input::file('import_file'));

            if (! empty($data) && $data->count()) {
                $data = $data->first()->toArray();
                foreach ($data as $key => $value) {
                    $value = (object) $value;
                    if (is_null($value->email)) {
                        $value->email = '';
                    }
                    if (is_null($value->address)) {
                        $value->address = '';
                    }
                    if (is_null($value->contact_person)) {
                        $value->contact_person = '';
                    }
                    if (is_null($value->tel_no)) {
                        $value->tel_no = '';
                    }
                    $insert[] = [
                              'name'=>$value->name,
                              'location'=>$value->location,
                              'phone'=>$value->phone,
                              'vat'=>$value->vat,
                              'email'=>$value->email,
                              'type'=> $value->type,
                              'website' =>$value->website,
                              'stock_symbol' => $value->stock_symbol,
                              'org_id'=>\Auth::user()->org_id,
                              'enabled'=>'1',
                            ];
                }
                if (! empty($insert)) {
                    $client = Client::insert($insert);
                    $lastcreated = Client::orderBy('id', 'desc')->take(count($insert))->get();
                    foreach ($lastcreated as $key => $client) {
                        if ($data[$key]['group_id']) {
                            $group_id = $data[$key]['group_id'];
                        } else {
                            $group = \App\Models\COAgroups::select('id')->where('name', $client->type)->first();
                            $group_id = $group->id;
                        }
                        $_ledgers = \TaskHelper::PostLedgers($client->name, $group_id);
                        $client->update(['ledger_id'=>$_ledgers]);
                    }
                    Flash::success('Contact successfully added');

                    return redirect()->back();
                }
            }
        }
        Flash::success('Sorry no file is selected to import leads.');

        return redirect()->back();
    }

    public function downloadExcelClients($type)
    {
        $data = Client::where('org_id', Auth::user()->org_id)->get()->toArray();

        return \Excel::download(new \App\Exports\ExcelExport($data), "Client.{$type}");
    }

    public function userindex()
    {
        Audit::log(Auth::user()->id, 'Excel', 'Import Export the users excel file.');

        $page_title = 'Import Export Users';
        $page_description = 'Import Export the Users Excel File';

        return view('admin.excel.importExportUser', compact('page_title', 'page_description'));
    }

    public function importusers()
    {
        if (Input::hasFile('import_file')) {
            $path = Input::file('import_file')->getRealPath();
            $data = \Excel::toCollection(new \App\Exports\ExcelImport(), Input::file('import_file'));

            if (! empty($data) && $data->count()) {
                $username_check = [];
                $email_check = [];
                $data = $data->first()->toArray();

                foreach ($data as $key =>  $value) {
                    $value = (object) $value;

                    if (in_array($value->username, $username_check)) {
                        return Redirect::back()->withErrors(['Duplicate entries for username '.$value->username.' In given file']);
                    }
                    if (in_array($value->email, $email_check)) {
                        return Redirect::back()->withErrors(['Duplicate entries for email '.$value->email.' In given file']);
                    }
                    $users = [
                        'first_name'=>$value->first_name,
                        'last_name'=>$value->last_name,
                        'username'=>$value->username,
                        'email' =>$value->email,
                        'password'=>\Hash::make('nepal123'),
                        'enabled'=>'1',
                        'phone' =>$value->phone ? $value->phone : '',
                        'departments_id'=>$value->departments_id ? $value->departments_id : '',
                        'org_id'=>\Auth::user()->id,
                    ];

                    $validator = Validator::make($users, [
                        'username'      => 'required|unique:users',
                        'email' => 'required|unique:users',
                    ],
                    [
                        'username.unique' => $value->username.' Already taken',
                        'email.unique' => $value->email.' Already taken',
                        'username.required' =>'Row '.($key + 1).'has no any usename please check it',
                        'email.required' =>'Row '.($key + 1).'has no any email please check it',
                    ]);
                    if ($validator->fails()) {
                        return Redirect::back()->withErrors(['error'=>$validator->messages()->all()]);
                    }
                    $insert[] = $users;
                    array_push($username_check, $value->username);
                    array_push($email, $value->email);
                }
                if (! empty($insert)) {
                    $users = User::insert($insert);
                    $lastcreated = User::orderBy('id', 'desc')->take(count($insert))->get();
                    foreach ($lastcreated as $key => $user) {
                        $full_name = $users->first_name.' '.$users->last_name;
                        $_ledgers = \TaskHelper::PostLedgers($full_name, \FinanceHelper::get_ledger_id('USER_LEDGER_GROUP'));
                        $attributes['ledger_id'] = $_ledgers;
                        $user->update($attributes);
                    }
                    Flash::success('Users successfully added');

                    return redirect()->back();
                }
            }
        }
        Flash::success('Nothing to import');

        return redirect()->back();
    }
      public function gniUsers(Request $request){

        $requiredDataAll = [];
        $data = User::where('org_id', Auth::user()->org_id)->get();
        foreach ($data as $key => $value) {
            $requiredData = [];
            $requiredData['name'] = $value->getFullNameAttribute();
            $requiredData['username'] = $value->username;
            $userOption = $request->user;
            $userdetailsOptions = $request->user_details;
            $userfieldComputed = $request->computed;
            $userdetails = \App\Models\UserDetail::where('user_id',$value->id)->first();
            foreach ($userOption as $fk => $field) {
                
                if($field == 'designations'){
                    $requiredData['designations'] = $value->designation->designations;
                }
                elseif ($field == 'departments') {

                   $requiredData['departments'] = $value->department->deptname;
                }elseif ($field == 'first_line_manager') {
                    $first_line_manager = \App\User::find($value->first_line_manager);
                    $requiredData['first_line_manager'] =$first_line_manager ? $first_line_manager->getFullNameAttribute():'';
                }
                elseif ($field == 'second_line_manager') {
                    $second_line_manager = \App\User::find($value->second_line_manager);
                    $requiredData['second_line_manager'] = $second_line_manager ? $second_line_manager->getFullNameAttribute() : '';
                }
                else{

                  $requiredData[$field] = $value[$field];   
                }
            }

            foreach ($userdetailsOptions as $fk => $field) {
                
                
              

                $requiredData[$field] = $userdetails[$field];   
                
            }

            foreach ($userfieldComputed as $fk => $field) {
                
            if($field == 'age'){
                $dob = strtotime($value->dob);
                if($dob){
                $datetime1 = new \DateTime($value->dob);
                $datetime2 = new \DateTime(date('Y-m-d'));
                $interval = $datetime1->diff($datetime2);
                
                $requiredData['age'] = $interval->format('%y years %m months %d days');

                 }else{
                    $requiredData['age'] = '';  
                 }

            }elseif ($field == 'tenure') {
                $service_tenure = strtotime($userdetails->join_date);
                if($service_tenure){

                    $datetime1 = new \DateTime($userdetails->join_date);
                    $datetime2 = new \DateTime(date('Y-m-d'));
                    $interval = $datetime1->diff($datetime2);
                    
                    $requiredData['service_tenure'] = $interval->format('%y years %m months %d days');
                }else{
                    $requiredData['service_tenure'] = '';  
                }
            }
                 
                
            }





             $requiredDataAll[] = $requiredData;
        }

        
       // dd($requiredDataAll);
        
        return \Excel::download(new \App\Exports\ExcelExport($requiredDataAll, false), "users.csv");

    }


    public function exportusers($type)
    {
        $data = User::where('org_id', Auth::user()->org_id)->get()->toArray();
        //dd($type);
        return \Excel::download(new \App\Exports\ExcelExport($data, false), "users.{$type}");
    }

    public function knowledgeindex()
    {
        Audit::log(Auth::user()->id, 'Excel', 'Import Export the users excel file.');

        $page_title = 'Import Export Knowledge';
        $page_description = 'Import Export the Knowledge Excel File';

        return view('admin.excel.importExportKnowledge', compact('page_title', 'page_description'));
    }

    public function importknowledge()
    {
        if (Input::hasFile('import_file')) {
            $path = Input::file('import_file')->getRealPath();
            $data = \Excel::toCollection(new \App\Exports\ExcelImport(), Input::file('import_file'));
            if (! empty($data) && $data->count()) {
                $data = $data->first()->toArray();
                foreach ($data as $key => $value) {
                    $value = (object) $value;
                    $insert[] = [
                        'author_id'=> $value->author_id,
                        'cat_id'=>$value->cat_id,
                        'title'=>$value->title,
                        'description'=>$value->description,
                        'body'=>$value->body,
                        'related_case'=>$value->related_case ? $value->related_case : '0',
                        'view_count'=>$value->view_count ? $value->view_count : '0',
                        'enabled'=>$value->enabled ? '1' : '0',
                        'expire_at'=>$value->expire_at ? date('Y-m-d H:i:s', strtotime($value->expire_at)) : '',
                        'org_id'=>\Auth::user()->id,

                    ];
                }
                Knowledge::insert($insert);
                Flash::success('Knowledge base successfully added');

                return redirect()->back();
            }
        }
        Flash::warning('No any data selected');

        return redirect()->back();
    }

    public function exportknowledge($type)
    {
        $data = Knowledge::where('org_id', Auth::user()->org_id)->get()->toArray();

        return \Excel::download(new \App\Exports\ExcelExport($data), 'Knowledge_'.date('Y-m-d').".{$type}");
    }

    public function exportProducts()
    {
        $data = \App\Models\Product::get()->toArray();
        return \Excel::download(new \App\Exports\ExcelExport($data, false), "products");
        // .{$type}
    }
}
