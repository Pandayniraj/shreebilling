@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')


<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
<link href="/bower_components/tags/css/jquery.tagit.css" rel="stylesheet" type="text/css" />
<link href="/bower_components/tags/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">

    <h1>
        <img src="{{ $user->image?'/images/profiles/'.$user->image:$user->avatar }}" alt="User Image"  style="border-radius: 50%;max-width: 100%;height: 40px;width: 40px;
        margin-top: 5px;
        ">
 
      <span style="position: absolute;margin-top: 4px;">&nbsp;{{ $page_title ?? 'Page Title' }}</span>

        

    </h1>
 <p>
   <span style="margin-left: 45px;margin-top: -20px;position: absolute;">
    {{ $user->department->deptname??'' }},<small> {{ $user->designation->designations??''}}</small>
   </span>
 </p>
</section>


@include('admin.userdetails.emp_overview')

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body ">
                <form method="post" action="/admin/users/{{$user_detail->user_id}}/detail/{{$user_detail->id}}/update" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="nav-tabs-custom" id="tabs">
                        
                         <ul class="nav nav-tabs bg-success">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Basic Information</a></li>
                            <li><a href="#tab_2" data-toggle="tab" aria-expanded="true">Service Information</a></li>
                            <li ><a href="#tab_education" data-toggle="tab" aria-expanded="true">Education</a></li>
                            <li ><a href="#tab_work_experience" data-toggle="tab" aria-expanded="true">Work Experience</a></li>
                            <li ><a href="#tab_direct_deposit" data-toggle="tab" aria-expanded="true">Direct Deposit</a></li>
                            <li ><a href="#tab_employee_document" data-toggle="tab" aria-expanded="true">Employee Documents</a></li>
                           {{--  <li><a href="#tab_3" data-toggle="tab" aria-expanded="true">Disabled Details</a></li> --}}
                           <li>
                               <a  href="#employement_detail"  data-toggle="tab" aria-expanded="true">Employement</a>
                           </li>
                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane" id='employement_detail'>
                                <div class="row">

                                <div class="col-md-12">
                                    <div class="row">
                                    <div class="col-md-6">
                                        Employment
                                    </div>
                                    <div class="col-md-6">
                                        <a href="/admin/add_employment/{{ $user->id }}" class="btn  btn-primary btn-sm btn-social" title="Add employment" style="float: right;" data-toggle="modal" data-target="#modal_dialog">
                                          <i class="fa fa-plus"></i>  Add employment
                                        </a>
                                    </div>
                                    </div>


                                </div>

                                <div class="col-md-12">
                                    <table class="table table-striped">
                                    

                                        <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Organization</th>
                                            <th>Desgination</th>
                                            <th>Department</th>
                                            <th>Supervisor</th> 
                                            <th>Branch</th>
                                            <th>Action</th>
                                        </tr>    
                                        </thead>

                                        <tbody>
                                            @if(isset($employement_details))
                                            @foreach($employement_details as $key=>$ed)
                                            <tr>
                                            <td>@if($ed->is_current)
                                                <span class="label label-success">Current</span>
                                                @else
                                                <span class="label label-default">Past</span>
                                                @endif
                                            </td>
                                            <td>{{ $ed->organization->organization_name }}</td>
                                            <td>{{ $ed->designation->designations }}</td>
                                            <td>{{ $ed->department->deptname }}</td>

                                            <td>{{ $ed->firstLineManger->first_name }} {{ $ed->firstLineManger->last_name }}</td>
                                            <td>{{ $ed->work_station }}</td>
                                  
                                                
                                            <td>
                                            @if ( $ed->isEditable() )
                                                <a href="{!! route('admin.edit_employment', $ed->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-edit editable"></i></a>
                                            @else
                                                <i class="fa fa-edit text-muted" title="{{ trans('admin/users/general.error.cant-be-deleted') }}"></i>
                                            @endif
                                            </td>
                                                </tr>
                                            @endforeach
                                            @endif
                                        </tbody>


                                    </table>


                                </div>


                                </div>
                            </div>






                            <div class="tab-pane active" id="tab_1">

                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="control-label">Father's Name</label>
                                        <input type="text" name="father_name" placeholder="Father's Name" id="father_name" value="{{$user_detail->father_name}}" class="form-control input-sm input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Mother's Name</label>
                                        <input type="text" name="mother_name" placeholder="Mother's Name" id="mother_name" value="{{$user_detail->mother_name}}" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">ID Proof</label>
                                        <input type="file" name="id_proof">

                                        @if($user_detail->id_proof != '')
                                        <label>Current Id Proof: </label><br />
                                        <img style="width:120px;height:100px;" src="{{ '/id_proof/'.$user_detail->id_proof }}">
                                        @endif
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Blood Group</label>
                                        {!! Form::select('blood_group',['B+'=>'B+','A+'=>'A+','A-'=>'A-','B-'=>'B-','AB-'=>'AB-','AB+'=>'AB+','O+','O-'],
                                        $user_detail->blood_group,['class'=>'form-control input-sm','id'=>'blood_group']) !!}
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Present Address</label>
                                        <input type="text" name="present_address" placeholder="Present Address" id="present_address" value="{{$user_detail->present_address}}" class="form-control input-sm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Permanent Address</label>
                                        <input type="text" name="permanent_address" placeholder="Permanent Address" id="permanent_address" value="{{$user_detail->permanent_address}}" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Citizenship</label>
                                        <input type="text" name="citizenship_num" placeholder="Citizenship  Number" id="present_address" value="{{$user_detail->citizenship_num}}" class="form-control input-sm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Gender</label>
                                        <select name="gender" class="form-control input-sm">
                                            <option value="">Select Gender</option>
                                            <option value="Male" @if($user_detail->gender == "Male") selected @endif>Male</option>
                                            <option value="Female" @if($user_detail->gender == "Female") selected @endif>Female</option>
                                        </select>
                                    </div>
                                </div>
                                <h3>More Info</h3>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Marital Status</label>
                                        <select name="marital_status" class="form-control input-sm">
                                            <option value="">Select Status</option>
                                            <option value="sigle" @if($user_detail->marital_status == "sigle") selected @endif>Single</option>
                                            <option value="Married" @if($user_detail->marital_status == "Married") selected @endif>Married</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Education</label>
                                        <input type="text" name="education" placeholder="Education" id="education" value="{{$user_detail->education}}" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Skills</label>
                                        <input type="text" name="skills" placeholder="Skills" id="skills" value="{{$user_detail->skills}}" class="form-control input-sm">
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="control-label">Food</label>
                                        <select name="food" class="form-control input-sm">
                                            <option value="">Select Food</option>
                                            <option value="Veg" @if($user_detail->food == "Veg") selected @endif>Veg</option>
                                            <option value="Non-Veg" @if($user_detail->food == "Non-Veg") selected @endif>Non-Veg</option>
                                        </select>
                                    </div>


                                    <div class="col-md-4">
                                        <label class="control-label">Nationality</label>
                                        {!! Form::select('nationality', ["Afghanistan"=>"Afghanistan",
                                        "Albania"=>"Albania",
                                        "Algeria"=>"Algeria",
                                        "American Samoa"=>"American Samoa",
                                        "Andorra"=>"Andorra",
                                        "Angola"=>"Angola",
                                        "Anguilla"=>"Anguilla",
                                        "Antigu"=>"Antigu",
                                        "Argentina"=>"Argentina",
                                        "Armenia"=>"Armenia",
                                        "Aruba"=>"Aruba",
                                        "Australia"=>"Australia",
                                        "Austria"=>"Austria",
                                        "Azerbaijan"=>"Azerbaijan",
                                        "Bahamas"=>"Bahamas",
                                        "Bahrain"=>"Bahrain",
                                        "Bangladesh"=>"Bangladesh",
                                        "Barbados"=>"Barbados",
                                        "Belarus"=>"Belarus",
                                        "Belgium"=>"Belgium",
                                        "Belize"=>"Belize",
                                        "Benin"=>"Benin",
                                        "Bermuda"=>"Bermuda",
                                        "Bhutan"=>"Bhutan",
                                        "Bolivia"=>"Bolivia",
                                        "Bosnia And Hercegovina"=>"Bosnia And Hercegovina",
                                        "Botswana"=>"Botswana",
                                        "Brazil"=>"Brazil",
                                        "British Virgin Islands"=>"British Virgin Islands",
                                        "Brunei"=>"Brunei",
                                        "Bulgaria"=>"Bulgaria",
                                        "Burkina fao"=>"Burkina fao",
                                        "Burundi"=>"Burundi",
                                        "Cambodia"=>"Cambodia",
                                        "Cameroon"=>"Cameroon",
                                        "Canada"=>"Canada",
                                        "Capeverde"=>"Capeverde",
                                        "Cayman Islands"=>"Cayman Islands",
                                        "Central African Republic"=>"Central African Republic",
                                        "Chad"=>"Chad",
                                        "Chile"=>"Chile",
                                        "China"=>"China",
                                        "Colombia"=>"Colombia",
                                        "Comoros"=>"Comoros",
                                        "Congo"=>"Congo",
                                        "Costa Rica"=>"Costa Rica",
                                        "Croatia"=>"Croatia",
                                        "Cuba"=>"Cuba",
                                        "Cyprus"=>"Cyprus",
                                        "Czech Republic"=>"Czech Republic",
                                        "Denmark"=>"Denmark",
                                        "Djibouti"=>"Djibouti",
                                        "Dominca"=>"Dominca",
                                        "Dominican Republic"=>"Dominican Republic",
                                        "Ecuador"=>"Ecuador",
                                        "Egypt"=>"Egypt",
                                        "El Salvador"=>"El Salvador",
                                        "Equatorial Guinea"=>"Equatorial Guinea",
                                        "Eritrea"=>"Eritrea",
                                        "Estonia"=>"Estonia",
                                        "Ethiopia"=>"Ethiopia",
                                        "Falkland Islands"=>"Falkland Islands",
                                        "Fiji"=>"Fiji",
                                        "Finland"=>"Finland",
                                        "France"=>"France",
                                        "Gabon"=>"Gabon",
                                        "Gambia"=>"Gambia",
                                        "Georgia"=>"Georgia",
                                        "Germany"=>"Germany",
                                        "Ghana"=>"Ghana",
                                        "Greece"=>"Greece",
                                        "Greenland"=>"Greenland",
                                        "Grenada"=>"Grenada",
                                        "Guam"=>"Guam",
                                        "Guatemala"=>"Guatemala",
                                        "Guinea"=>"Guinea",
                                        "Guinea-bissau"=>"Guinea-bissau",
                                        "Guyana"=>"Guyana",
                                        "Haiti"=>"Haiti",
                                        "Honduras"=>"Honduras",
                                        "Hungary"=>"Hungary",
                                        "Iceland"=>"Iceland",
                                        "India"=>"India",
                                        "Indonesia"=>"Indonesia",
                                        "Iran"=>"Iran",
                                        "Iraq"=>"Iraq",
                                        "Ireland"=>"Ireland",
                                        "Israel"=>"Israel",
                                        "Italy"=>"Italy",
                                        "Jamaica"=>"Jamaica",
                                        "Japan"=>"Japan",
                                        "Jordan"=>"Jordan",
                                        "Kazakhstan"=>"Kazakhstan",
                                        "Kenya"=>"Kenya",
                                        "Kiribati"=>"Kiribati",
                                        "Kuwait"=>"Kuwait",
                                        "Laos"=>"Laos",
                                        "Latvia"=>"Latvia",
                                        "Lebanon"=>"Lebanon",
                                        "Lesotho"=>"Lesotho",
                                        "Liberia"=>"Liberia",
                                        "Libya"=>"Libya",
                                        "Liechtenstein"=>"Liechtenstein",
                                        "Lithuania"=>"Lithuania",
                                        "Luxembourg"=>"Luxembourg",
                                        "Macedonia"=>"Macedonia",
                                        "Madagascar"=>"Madagascar",
                                        "Malawi"=>"Malawi",
                                        "Malaysia"=>"Malaysia",
                                        "Maldives"=>"Maldives",
                                        "Mali"=>"Mali",
                                        "Malta"=>"Malta",
                                        "Marshall Islands"=>"Marshall Islands",
                                        "Mauritania"=>"Mauritania",
                                        "Mauritius"=>"Mauritius",
                                        "Mexico"=>"Mexico",
                                        "Micronesia"=>"Micronesia",
                                        "Moldova"=>"Moldova",
                                        "Monaco"=>"Monaco",
                                        "Mongolia"=>"Mongolia",
                                        "Morocco"=>"Morocco",
                                        "Mozambique"=>"Mozambique",
                                        "Myanmar"=>"Myanmar",
                                        "Namibia"=>"Namibia",
                                        "Nauru"=>"Nauru",
                                        "Nepal"=>"Nepal",
                                        "Netherlands"=>"Netherlands",
                                        "New Zealand"=>"New Zealand",
                                        "Nicaragua"=>"Nicaragua",
                                        "Niger"=>"Niger",
                                        "Nigeria"=>"Nigeria",
                                        "North Korea"=>"North Korea",
                                        "Norway"=>"Norway",
                                        "Oman"=>"Oman",
                                        "Pakistan"=>"Pakistan",
                                        "Palau"=>"Palau",
                                        "Panama"=>"Panama",
                                        "Papua New Guinea"=>"Papua New Guinea",
                                        "Paraguay"=>"Paraguay",
                                        "Peru"=>"Peru",
                                        "Philippines" => "Philippines",
                                        "Poland"=>"Poland",
                                        "Portugal"=>"Portugal",
                                        "Qatar"=>"Qatar",
                                        "Romania"=>"Romania",
                                        "Russia"=>"Russia",
                                        "Rwanda"=>"Rwanda",
                                        "San Marino"=>"San Marino",
                                        "Sao Tome And Principe"=>"Sao Tome And Principe",
                                        "Saudi Arabia"=>"Saudi Arabia",
                                        "Senegal"=>"Senegal",
                                        "Serbia"=>"Serbia",
                                        "Seychelles"=>"Seychelles",
                                        "Sierra Leone"=>"Sierra Leone",
                                        "Singapore"=>"Singapore",
                                        "Slovakia"=>"Slovakia",
                                        "Slovenia"=>"Slovenia",
                                        "Solomon Islands"=>"Solomon Islands",
                                        "Somalia"=>'Somalia',
                                        "outh Africa"=>"South Africa",
                                        "South Korea"=>"South Korea",
                                        "Spain"=>"Spain",
                                        "Sri Lanka"=>"Sri Lanka",
                                        "Sudan"=>"Sudan",
                                        "Suriname"=>"Suriname",
                                        "Swaziland"=>"Swaziland",
                                        "Sweden"=>"Sweden",
                                        "Switzerland"=>"Switzerland",
                                        "Syria"=>"Syria",
                                        "Taiwan"=>"Taiwan",
                                        "Tajikistan"=>"Tajikistan",
                                        "Tanzania"=>"Tanzania",
                                        "Thailand"=>"Thailand",
                                        "Togo"=>"Togo",
                                        "Tonga"=>"Tonga",
                                        "Trinidad And Tobago"=>"Trinidad And Tobago",
                                        "Tunisia"=>"Tunisia",
                                        "Turkey"=>"Turkey",
                                        "Turkmenistan"=>"Turkmenistan",
                                        "Tuvalu"=>"Tuvalu",
                                        "Uganda"=>"Uganda",
                                        "Ukraine"=>"Ukraine",
                                        "United Arab Emirates"=>"United Arab Emirates",
                                        "United Kingdom"=>"United Kingdom",
                                        "United States Of America"=>"United States Of America",
                                        "Uruguay"=>"Uruguay",
                                        "Uzbekistan"=>"Uzbekistan",
                                        "Vanuatu"=>"Vanuatu",
                                        "Venezuela"=>"Venezuela",
                                        "Viet Nam"=>"Viet Nam",
                                        "Zambia"=>"Zambia",
                                        "Zimbabwe"=>"Zimbabwe"],$user_detail->nationality, ['class' => 'form-control input-sm ']) !!}
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Driver's License Number</label>
                                        <input type="text" name="license_number" placeholder="License Number" id="license_number" value="{{$user_detail->license_number}}" class="form-control input-sm">
                                    </div>

                                </div>


                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="control-label">Job Title</label>
                                        <input type="text" name="job_title" placeholder="Job Title" id="job_title" value="{{$user_detail->job_title}}" class="form-control input-sm">
                                    </div>

                                     <div class="col-md-4">
                                        <label class="control-label">Employment Type</label>
                                        <select name="employemnt_type" class="form-control input-sm">
                                              <option value="">Select Employement Type</option>
                                            <option value="permanent" @if($user_detail->employemnt_type == 'permanent') selected @endif>Permanent</option>
                                            <option value="probation" @if($user_detail->employemnt_type == 'probation') selected @endif>Probationary</option>
                                            <option value="contract" @if($user_detail->employemnt_type == 'contract') selected @endif>Contractual</option>
                                            <option value="part-time" @if($user_detail->employemnt_type == 'part-time') selected @endif>Part Time</option>
                                            <option value="tempo" @if($user_detail->employemnt_type == 'tempo') selected @endif>Temporary</option>

                                            <option value="dispatch" @if($user_detail->employemnt_type == 'dispatch') selected @endif>Dispatched</option>
                                            <option value="volunteer" @if($user_detail->employemnt_type == 'volunteer') selected @endif>Volunteer</option>

                                             <option value="short" @if($user_detail->employemnt_type == 'short') selected @endif>Short-term</option>
                                            <option value="consult" @if($user_detail->employemnt_type == 'consult') selected @endif>Consultant</option>

                                            <option value="outsource" @if($user_detail->employemnt_type == 'outsource') selected @endif>Outsourced</option>

                                            
                                            
                                        </select>
                                    </div>

                                     <div class="col-md-4">
                                        <label class="control-label">Ethnicity</label>
                                        <select name="ethnicity" class="form-control input-sm">
                                            <option value="">Select Ethnicity</option>
                                            <option value="permanent" @if($user_detail->employemnt_type == 'brahmin') selected @endif>Brahmin</option>
                                            <option value="probation" @if($user_detail->employemnt_type == 'chhetri') selected @endif>Chhetri</option>
                                            <option value="contract" @if($user_detail->employemnt_type == 'janajati') selected @endif>Janajati</option>
                                            <option value="part-time" @if($user_detail->employemnt_type == 'dalit') selected @endif>Dalit</option>
                                            <option value="tempo" @if($user_detail->employemnt_type == 'madhesi') selected @endif>Madhesi</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Resume</label>
                                        <input type="file" name="resume">
                                        @if($user_detail->resume != '')
                                        <label>Current Resume: </label><br />
                                        <img style="width:120px;height:100px;" src="{{ '/resume/'.$user_detail->resume }}">
                                        @endif
                                    </div>

                                </div>

                                <h4> Emergency Contact </h4>

                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="control-label">Emergency Name</label>
                                        <input type="text" name="emergency_contact_name" placeholder="Emergency Name" id="emergency_contact_name" value="{{$user_detail->emergency_contact_name}}" class="form-control input-sm ">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Relationship</label>
                                        <input type="text" name="relationship" placeholder="Relationship" id="relationship" value="{{$user_detail->relationship}}" class="form-control input-sm ">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Mobile</label>
                                        <input type="text" name="mobile" placeholder="Mobile" id="mobile" value="{{$user_detail->mobile}}" class="form-control input-sm">
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="control-label">Work Phone</label>
                                        <input type="text" name="work_phone" placeholder="Work Phone" id="work_phone" value="{{$user_detail->work_phone}}" class="form-control input-sm ">
                                    </div>

                                </div>

                                <h4> Dependants</h4>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMoreDependants" style="float: left;">
                                            <i class="fa fa-plus"></i> <span>Add More Dependants</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="row InputsWrapper1" style="margin: 5px;">

                                    <table>
                                        @foreach($user_dependents as $lm)
                                        <tr>
                                            <td style="padding-right:5px;">
                                                <label class="control-label">Name.</label>
                                                <input type="text" name="dependents_name[]" id="dependents_name" value="{{ $lm->name }}" class="form-control input-sm input-sm" placeholder="Dependants Name.">
                                            </td>
                                            <td style="padding-right:5px;">
                                                <label class="control-label">Relationship</label>
                                                <input type="text" name="dependents_relationship[]" id="dependents_relationship" value="{{ $lm->relationship }}" class="form-control input-sm input-sm" placeholder="Relationship">
                                            </td>
                                            <td style="padding-right:5px;">
                                                <label class="control-label">DOB</label>
                                                <input type="date" name="dependents_dob[]" id="dependents_dob" placeholder="Date of Birth" value="{{ $lm->dob }}" class="form-control input-sm input-sm">
                                            </td>
                                            <td style="position: relative;"><a href="javascript::void(1);" style="position:absolute; top:26px;">
                                                    <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                    </table>

                                </div>

                                <div id="orderFields1" style="display: none;">
                                    <table class="table">
                                        <tbody id="more-tr1">
                                            <tr>
                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Name.</label>
                                                    <input type="text" name="dependents_name[]" id="dependents_name" value="{{ old('dependents_name') }}" class="form-control input-sm input-sm" placeholder="Dependants Name.">
                                                </td>
                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Relationship</label>
                                                    <input type="text" name="dependents_relationship[]" id="dependents_relationship" value="{{ old('mob_phone2') }}" class="form-control input-sm input-sm" placeholder="Relationship">
                                                </td>
                                                <td style="padding-right:5px;">
                                                    <label class="control-label">DOB</label>
                                                    <input type="date" name="dependents_dob[]" id="dependents_dob" placeholder="Date of Birth" value="{{ old('home_phone') }}" class="form-control input-sm input-sm">
                                                </td>
                                                <td style="position: relative;"><a href="javascript::void(1);" style="position:absolute; top:26px;">
                                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                    </a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                

                                <div id="orderFields2" style="display: none;">
                                    <table class="table">
                                        <tbody id="more-tr2">
                                            <tr>
                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Level.</label>
                                                    <input type="text" name="education_level[]" id="education_level" value="{{ old('education_level') }}" class="form-control input-sm input-sm" placeholder="Level">
                                                </td>

                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Institute</label>
                                                    <input type="text" name="education_institute[]" id="education_institute" value="{{ old('education_institute') }}" class="form-control input-sm input-sm" placeholder="Institute">
                                                </td>

                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Major</label>
                                                    <input type="text" name="education_major[]" id="education_major" placeholder="Major" value="{{ old('education_major') }}" class="form-control input-sm input-sm">
                                                </td>

                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Year</label>
                                                    <input type="text" name="education_year[]" id="education_year" placeholder="Year" value="{{ old('education_year') }}" class="form-control input-sm input-sm">
                                                </td>

                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Score</label>
                                                    <input type="text" name="education_score[]" id="education_score" placeholder="Score" value="{{ old('education_score') }}" class="form-control input-sm input-sm">
                                                </td>

                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Start Date</label>
                                                    <input type="date" name="education_start_date[]" id="education_start_date" placeholder="Start Date" value="{{ old('education_start_date') }}" class="form-control input-sm input-sm">
                                                </td>

                                                <td style="padding-right:5px;">
                                                    <label class="control-label">End Date</label>
                                                    <input type="date" name="education_end_date[]" id="education_end_date" placeholder="End Date" value="{{ old('education_end_date') }}" class="form-control input-sm input-sm">
                                                </td>

                                                <td style="position: relative;"><a href="javascript::void(1);" style="position:absolute; top:26px;">
                                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                    </a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>




                             
                            </div>
                        

                           {{--  <div class="tab-pane" id='tab_education'>
                                <div class="row">
                                    <div class="col-md-12">
                                    </div>
                                </div>
                            </div> --}}


                            <div class="tab-pane" id='tab_direct_deposit'>
                                <div class="row">
                                    <div class="col-md-12">
                                           <h4> Direct Deposit</h4>

                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="control-label">Amount</label>
                                        <input type="text" name="amount" placeholder="Amount" id="amount" value="{{$user_detail->amount}}" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Bank Account No</label>
                                        <input type="text" name="bank_account_no" placeholder="Bank Account No" id="bank_account_no" value="{{$user_detail->bank_account_no}}" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Bank Account Branch</label>
                                        <input type="text" name="bank_account_branch" placeholder="Bank Account Branch" id="homepage" value="{{$user_detail->bank_account_branch}}" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Bank Name</label>
                                        <input type="text" name="bank_name" placeholder="Bank Name" id="bank_name" value="{{$user_detail->bank_name}}" class="form-control input-sm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Bank Account Name</label>
                                        <input type="text" name="bank_account_name" placeholder="Bank Account Name" id="bank_account_name" value="{{$user_detail->bank_account_name}}" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Routing Num</label>
                                        <input type="text" name="routing_num" placeholder="Rounting Rum" id="routing_num" value="{{$user_detail->routing_num}}" class="form-control input-sm">
                                    </div>

                                        </div>
                                    </div>
                                </div>
                            </div>



                             <div class="tab-pane" id='tab_work_experience'>
                                <div class="row">
                                    <div class="col-md-12">

                                <h4> Work Experience</h4>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMoreExperience" style="float: left;">
                                            <i class="fa fa-plus"></i> <span>Add More Work Experience</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="row InputsWrapper3" style="margin: 5px;">

                                    <table>
                                        @foreach($user_work_experience as $lm)
                                        <tr>
                                            <td style="padding-right:5px;">
                                                <label class="control-label">Company.</label>
                                                <input type="text" name="work_company[]" id="work_company" value="{{ $lm->company }}" class="form-control input-sm input-sm" placeholder="Company">
                                            </td>

                                            <td style="padding-right:5px;">
                                                <label class="control-label">Job Title</label>
                                                <input type="text" name="work_title[]" id="work_title" value="{{ $lm->job_title }}" class="form-control input-sm input-sm" placeholder="Job Title">
                                            </td>

                                            <td style="padding-right:5px;">
                                                <label class="control-label">From</label>
                                                <input type="date" name="work_from[]" id="work_from" placeholder="From" value="{{ $lm->date_from }}" class="form-control input-sm input-sm">
                                            </td>

                                            <td style="padding-right:5px;">
                                                <label class="control-label">To</label>
                                                <input type="date" name="work_to[]" id="work_to" placeholder="To" value="{{ $lm->date_to }}" class="form-control input-sm input-sm">
                                            </td>

                                            <td style="padding-right:5px;">
                                                <label class="control-label">Comment</label>
                                                <input type="text" name="work_comment[]" id="work_comment" placeholder="Comment" value="{{ $lm->comment }}" class="form-control input-sm input-sm">
                                            </td>

                                            <td style="position: relative;"><a href="javascript::void(1);" style="position:absolute; top:26px;">
                                                    <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                    </table>

                                </div>

                                <div id="orderFields3" style="display: none;">
                                    <table class="table">
                                        <tbody id="more-tr3">
                                            <tr>
                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Company.</label>
                                                    <input type="text" name="work_company[]" id="work_company" value="{{ old('work_company') }}" class="form-control input-sm input-sm" placeholder="Company">
                                                </td>

                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Job Title</label>
                                                    <input type="text" name="work_title[]" id="work_title" value="{{ old('work_title') }}" class="form-control input-sm input-sm" placeholder="Job Title">
                                                </td>

                                                <td style="padding-right:5px;">
                                                    <label class="control-label">From</label>
                                                    <input type="date" name="work_from[]" id="work_from" placeholder="From" value="{{ old('work_from') }}" class="form-control input-sm input-sm">
                                                </td>

                                                <td style="padding-right:5px;">
                                                    <label class="control-label">To</label>
                                                    <input type="date" name="work_to[]" id="work_to" placeholder="To" value="{{ old('work_to') }}" class="form-control input-sm input-sm">
                                                </td>

                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Comment</label>
                                                    <input type="text" name="work_comment[]" id="work_comment" placeholder="Comment" value="{{ old('work_comment') }}" class="form-control input-sm input-sm">
                                                </td>

                                                <td style="position: relative;"><a href="javascript::void(1);" style="position:absolute; top:26px;">
                                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                    </a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                                    </div>
                                </div>
                            </div>


                             <div class="tab-pane" id='tab_education'>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4> Education</h4>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMoreEducations" style="float: left;">
                                            <i class="fa fa-plus"></i> <span>Add More Education</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="row InputsWrapper2" style="margin: 5px;">

                                    <table>
                                        @foreach($user_education as $lm)
                                        <tr>
                                            <td style="padding-right:5px;">
                                                <label class="control-label">Level.</label>
                                                <input type="text" name="education_level[]" id="education_level" value="{{ $lm->level }}" class="form-control input-sm input-sm" placeholder="Level">
                                            </td>

                                            <td style="padding-right:5px;">
                                                <label class="control-label">Institute</label>
                                                <input type="text" name="education_institute[]" id="education_institute" value="{{ $lm->institute }}" class="form-control input-sm input-sm" placeholder="Institute">
                                            </td>

                                            <td style="padding-right:5px;">
                                                <label class="control-label">Major</label>
                                                <input type="text" name="education_major[]" id="education_major" placeholder="Major" value="{{ $lm->major }}" class="form-control input-sm input-sm">
                                            </td>

                                            <td style="padding-right:5px;">
                                                <label class="control-label">Year</label>
                                                <input type="text" name="education_year[]" id="education_year" placeholder="Year" value="{{ $lm->year }}" class="form-control input-sm input-sm">
                                            </td>

                                            <td style="padding-right:5px;">
                                                <label class="control-label">Score</label>
                                                <input type="text" name="education_score[]" id="education_score" placeholder="Score" value="{{ $lm->score }}" class="form-control input-sm input-sm">
                                            </td>

                                            <td style="padding-right:5px;">
                                                <label class="control-label">Start Date</label>
                                                <input type="date" name="education_start_date[]" id="education_start_date" placeholder="Start Date" value="{{ $lm->start_date }}" class="form-control input-sm input-sm">
                                            </td>

                                            <td style="padding-right:5px;">
                                                <label class="control-label">End Date</label>
                                                <input type="date" name="education_end_date[]" id="education_end_date" placeholder="End Date" value="{{ $lm->end_date }}" class="form-control input-sm input-sm">
                                            </td>

                                            <td style="position: relative;"><a href="javascript::void(1);" style="position:absolute; top:26px;">
                                                    <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                    </table>

                                </div>



                                    </div>
                                </div>
                            </div>



                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Join Date</label>
                                        <input type="text" name="join_date" placeholder="Join Date" id="join_date" value="{{$user_detail->join_date}}" class="form-control input-sm datepicker">
                                    </div>
                                    {{-- @php $getcurrentEmpStatus = $employement_details->where('')->first();  @endphp 
                                    @if() --}}

                                    <div class="col-md-4">
                                        <label class="control-label">Contract Start Date</label>
                                        <input type="text" name="contract_start_date" placeholder="Contract Start Date" id="contract_start_date" value="{{$user_detail->contract_start_date}}" class="form-control input-sm datepicker">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Contract End Date</label>
                                        <input type="text" name="contract_end_date" placeholder="Contract End Date" id="contract_end_date" value="{{$user_detail->contract_end_date}}" class="form-control input-sm datepicker">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Date Of Probation</label>
                                        <input type="text" name="date_of_probation" placeholder="Date Of Probation" id="date_of_probation" value="{{$user_detail->date_of_probation}}" class="form-control input-sm datepicker">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Date Of Permanent</label>
                                        <input type="text" name="date_of_permanent" placeholder="Date Of Permanent" id="date_of_permanent" value="{{$user_detail->date_of_permanent}}" class="form-control input-sm datepicker">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Last Promotion Date</label>
                                        <input type="text" name="last_promotion_date" placeholder="Last Promotion Date" id="last_promotion_date" value="{{$user_detail->last_promotion_date}}" class="form-control input-sm datepicker">
                                    </div>
                                    @if(!$user->enabled)
                                    <div class="col-md-4">
                                        <label class="control-label">Resgination Date</label>
                                        <input type="text" name="resgination_date" 
                                        placeholder="Resgination Date" id="resgination_date" value="{{$user_detail->resgination_date}}" class="form-control input-sm datepicker">
                                    </div>
                                    @endif


                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Last Transfer Date</label>
                                        <input type="text" name="last_transfer_date" placeholder="Last Transfer Date" id="last_transfer_date" value="{{$user_detail->last_transfer_date}}" class="form-control input-sm datepicker">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Date Of Retirement</label>
                                        <input type="text" name="date_of_retirement" placeholder="Date Of Retirement" id="date_of_retirement" value="{{$user_detail->date_of_retirement}}" class="form-control input-sm datepicker">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Working Status</label>
                                        <select name="working_status" class="form-control input-sm">
                                            <option value="">Select Working Status</option>
                                            <option value="retired" @if($user_detail->working_status == 'retired') selected @endif>Retired</option>
                                            <option value="working" @if($user_detail->working_status == 'working') selected @endif>Working</option>
                                            <option value="hold" @if($user_detail->working_status == 'hold') selected @endif>Hold</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane" id="tab_3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Team</label>
                                        <input placeholder="Team" id="team" value="{{$team_name}}" class="form-control input-sm" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Organization</label>
                                        <input placeholder="Organization" id="organization" value="{{$user->organization->organization_name??''}}" class="form-control input-sm" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Department</label>
                                        <input placeholder="Department" id="department" value="{{$user->department->deptname ??''}}" class="form-control input-sm" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Designation</label>
                                        <input placeholder="Designation" id="designation" value="{{$user->designation->designations??''}}" class="form-control input-sm" readonly>
                                    </div>
                                </div>

                            </div>

<div class="tab-pane" id='tab_employee_document'>
   <div class="row">
      <div class="col-md-12">
         <div class="row">
            <div class="col-md-6">
               
               <table class="table">
                  <tbody>
                     <tr class="multipleDivDocs">
                     <tr class="form_contents">
                        <td class="moreroomtd">
                           <div class="form-group">
                              <label for="inputEmail3" class="col-sm-6 control-label ">
                              Doc.
                              </label>
                           </div>
                        </td>
                        <td>
                           <input type="text" name="document_name" placeholder="Document Name" id="document_name" class="form-control input-sm">
                        </td>
                        <td>
                           <input type="file" name="file" id='document_file'>
                        </td>
                        <td>
                           <button class="btn btn-primary btn-xs" type="button" id='upload_file'>Upload</button>
                        </td>
                     </tr>
                     @foreach($user_documents as $doc)

                     <tr>
                        <td class="moreroomtd">
                           <div class="form-group">
                              <label for="inputEmail3" class="col-sm-6 control-label ">
                              Curr. Doc.
                              </label>
                           </div>
                        </td>
                        <td>
                           <input type="text" value="{{$doc->document_name}}" id="document_name" class="form-control input-sm" readonly>
                        </td>
                        <td>
                           <a href="/userdocument/{{$doc->file}}" target="blank">{{substr($doc->file,13)}}</a><br>
                           <i class="date">{!! \Carbon\Carbon::createFromTimeStamp(strtotime($doc->created_at))->diffForHumans().' by '.$doc->user->first_name !!}</i>
                        </td>
                        <td>
                           <a data-target="#modal_dialog" data-toggle="modal" href="/admin/userdocument/confirm-delete-file/{{$doc->id}}" style="width: 10%;">
                           <i class="btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                           </a>
                        </td>
                     </tr>
                     @endforeach
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
</div>

  



        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::submit( 'Update', ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="/admin/employee/directory" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>

                    <a href="/admin/userdetail/{{$user_detail->id}}/pdf" class='btn btn-default'>Download PIS</a>

                </div>
            </div>
        </div>

    </form>      

 <form method="post" enctype="multipart/form-data" action="/admin/usersdocument/{{$user_detail->user_id}}/detail/{{$user_detail->id}}/update" 
            id='moreDocForm' style="display: none">
            {{ csrf_field() }}
            <div id='more_doc_field'></div>
         
        </form>

    </div>
</div>
@endsection

@section('body_bottom')

<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>



<script type="text/javascript">
    $(document).ready(function() {
        $("#skills").tagit();
    });

</script>

<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
            //inline: true,
            //format: 'YYYY-MM-DD',
            format: 'YYYY-MM-DD'
            , sideBySide: true
            , allowInputToggle: true
        });
    });

</script>

<script>
    $("#addMoreDocuments").on("click", function() {
        $(".InputsWrapper4").after($('#orderFields4 #more-tr4').html());
    });

    $(document).on('click', '.remove-this', function() {
        $(this).parent().parent().parent().remove();
    });

</script>

<script>
    $("#addMoreExperience").on("click", function() {
        $(".InputsWrapper3").after($('#orderFields3 #more-tr3').html());
    });

    $(document).on('click', '.remove-this', function() {
        $(this).parent().parent().parent().remove();
    });

</script>

<script>
    $("#addMoreEducations").on("click", function() {
        $(".InputsWrapper2").after($('#orderFields2 #more-tr2').html());
    });

    $(document).on('click', '.remove-this', function() {
        $(this).parent().parent().parent().remove();
    });

</script>

<script>
    $("#addMoreDependants").on("click", function() {
        $(".InputsWrapper1").after($('#orderFields1 #more-tr1').html());
    });

    $(document).on('click', '.remove-this', function() {
        $(this).parent().parent().parent().remove();
    });

$('#upload_file').click(function(){

    if($('.form_contents #document_name').val() == ''){



        alert("Doc name is required");

        return;
    }

     if($('.form_contents #document_file').val() == ''){



        alert("Doc file is required");

        return;
    }
    





    $('form#moreDocForm #more_doc_field').html($('#tab_employee_document .form_contents'));
    



    setTimeout(function(){
         $('form#moreDocForm').submit();

    },100);



});

 $(document).on('hidden.bs.modal', '#modal_dialog', function(e) {
        $('#modal_dialog .modal-content').html('');
    });

</script>

@endsection
