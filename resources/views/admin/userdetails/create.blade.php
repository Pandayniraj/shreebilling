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
        {{ $page_title ?? 'Page Title' }}
        <small>{{$page_description ?? 'Page Description'}}</small>
    </h1>

</section>


<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body ">
                <form method="post" action="/admin/users/{{$user_id}}/detail/store" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{-- <h4> Personal Details</h4> --}}
                    <div class="nav-tabs-custom" id="tabs">

                        <ul class="nav nav-tabs bg-danger">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Basic Information</a></li>
                            <li><a href="#tab_2" data-toggle="tab" aria-expanded="true">Service Information</a></li>
                            <li ><a href="#tab_education" data-toggle="tab" aria-expanded="true">Education</a></li>
                            <li ><a href="#tab_work_experience" data-toggle="tab" aria-expanded="true">Work Experience</a></li>
                            <li ><a href="#tab_direct_deposit" data-toggle="tab" aria-expanded="true">Direct Deposit</a></li>
                            <li ><a href="#tab_employee_document" data-toggle="tab" aria-expanded="true">Employee Documents</a></li>
                            <li><a href="#tab_3" data-toggle="tab" aria-expanded="true">Disabled Details</a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Father's Name</label>
                                        <input type="text" name="father_name" placeholder="Father's Name" id="father_name" value="" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Mother's Name</label>
                                        <input type="text" name="mother_name" placeholder="Mother's Name" id="mother_name" value="" class="form-control input-sm">
                                    </div>


                                    <div class="col-md-4">
                                        <label class="control-label">ID Proof</label>
                                        <input type="file" name="id_proof">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Blood Group</label>
                                        {!! Form::select('blood_group',['B+'=>'B+','A+'=>'A+','A-'=>'A-','B-'=>'B-','AB-'=>'AB-','AB+'=>'AB+','O+','O-'],null,['class'=>'form-control input-sm','id'=>'blood_group']) !!}
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Present Address</label>
                                        <input type="text" name="present_address" placeholder="Present Address" id="present_address" value="" class="form-control input-sm">
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
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <h3>More Info</h3>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Marital Status</label>
                                        <select name="marital_status" class="form-control input-sm">
                                            <option value="">Select Status</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Education</label>
                                        <input type="text" name="education" placeholder="Education" id="education" value="" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Skills</label>
                                        <input type="text" name="skills" placeholder="Skills" id="skills" value="" class="form-control input-sm">
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="control-label">Food</label>
                                        <select name="food" class="form-control input-sm">
                                            <option value="">Select Food</option>
                                            <option value="Veg">Veg</option>
                                            <option value="Non-Veg">Non-Veg</option>
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
                                        "Zimbabwe"=>"Zimbabwe"],'Nepal', ['class' => 'form-control input-sm ']) !!}
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Driver's License Number</label>
                                        <input type="text" name="license_number" placeholder="License Number" id="license_number" value="" class="form-control input-sm">
                                    </div>

                                </div>


                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Job Title</label>
                                        <input type="text" name="job_title" placeholder="Job Title" id="job_title" value="" class="form-control input-sm">
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
                                    </div>
                                </div>


                                <h4> Emergency Contact </h4>

                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="control-label">Emergency Name</label>
                                        <input type="text" name="emergency_contact_name" placeholder="Emergency Name" id="emergency_contact_name" value="" class="form-control input-sm ">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Relationship</label>
                                        <input type="text" name="relationship" placeholder="Relationship" id="relationship" value="" class="form-control input-sm ">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Mobile</label>
                                        <input type="text" name="mobile" placeholder="Mobile" id="mobile" value="" class="form-control input-sm">
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="control-label">Work Phone</label>
                                        <input type="text" name="work_phone" placeholder="Work Phone" id="work_phone" value="" class="form-control input-sm ">
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






                        <div class="tab-pane" id='tab_direct_deposit'>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    
                                         <h4> Direct Deposit</h4>

                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="control-label">Amount</label>
                                        <input type="text" name="amount" placeholder="Amount" id="amount" value="" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Bank Name</label>
                                        <input type="text" name="bank_name" placeholder="Bank Name" id="bank_name" value="" class="form-control input-sm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Bank Account Name</label>
                                        <input type="text" name="bank_account_name" placeholder="Bank Account Name" id="bank_account_name" value="" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Bank Account No</label>
                                        <input type="text" name="bank_account_no" placeholder="Bank Account No" id="bank_account_no" value="" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Bank Account Branch</label>
                                        <input type="text" name="bank_account_branch" placeholder="Bank Account Branch" id="bank_account_branch" value="" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Routing Num</label>
                                        <input type="text" name="routing_num" placeholder="Rounting Rum" id="routing_num" value="" class="form-control input-sm">
                                    </div>

                                </div>


                                </div>
                            </div>
                        </div>



                        <div class="tab-pane" id='tab_employee_document'>
                            <div class="row">
                                <div class="col-md-12">
                                    

                               

                                <h4> Employee Documents</h4>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMoreDocuments" style="float: left;">
                                            <i class="fa fa-plus"></i> <span>Add More Employee Documents</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="row InputsWrapper4" style="margin: 5px;">

                                </div>

                                <div id="orderFields4" style="display: none;">
                                    <table class="table">
                                        <tbody id="more-tr4">
                                            <tr>
                                                <td style="padding-right:5px;">
                                                    <label class="control-label">Document Name</label>
                                                    <input type="text" name="document_name[]" id="mobile1" value="{{ old('document_name') }}" class="form-control input-sm" placeholder="Document Name">
                                                </td>
                                                <td style="padding-right:5px;">
                                                    <label class="control-label">File</label>
                                                    <input type="file" name="document_file[]" id="document_file" value="{{ old('document_file') }}" class=" input-sm" placeholder="File">
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



                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Join Date</label>
                                        <input type="text" name="join_date" placeholder="Join Date" id="join_date" value="" class="form-control input-sm datepicker">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Contract Start Date</label>
                                        <input type="text" name="contract_start_date" placeholder="Contract Start Date" id="contract_start_date" value="" class="form-control input-sm datepicker">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Contract End Date</label>
                                        <input type="text" name="contract_end_date" placeholder="Contract End Date" id="contract_end_date" value="" class="form-control input-sm datepicker">
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Date Of Probation</label>
                                        <input type="text" name="date_of_probation" placeholder="Date Of Probation" id="date_of_probation" value="" class="form-control input-sm datepicker">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Date Of Permanent</label>
                                        <input type="text" name="date_of_permanent" placeholder="Date Of Permanent" id="date_of_permanent" value="" class="form-control input-sm datepicker">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Last Promotion Date</label>
                                        <input type="text" name="last_promotion_date" placeholder="Last Promotion Date" id="last_promotion_date" value="" class="form-control input-sm datepicker">
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Last Transfer Date</label>
                                        <input type="text" name="last_transfer_date" placeholder="Last Transfer Date" id="last_transfer_date" value="" class="form-control input-sm datepicker">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Date Of Retirement</label>
                                        <input type="text" name="date_of_retirement" placeholder="Date Of Retirement" id="date_of_retirement" value="" class="form-control input-sm datepicker">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Working Status</label>
                                        <select name="working_status" class="form-control input-sm">
                                            <option value="">Select Working Status</option>
                                            <option value="retired">Retired</option>
                                            <option value="working">Working</option>
                                            <option value="hold">Hold</option>
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
                                        <input placeholder="Organization" id="organization" value="{{$user->organization->organization_name}}" class="form-control input-sm" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Department</label>
                                        <input placeholder="Department" id="department" value="{{$user->department->deptname}}" class="form-control input-sm" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Designation</label>
                                        <input placeholder="Designation" id="designation" value="{{$user->designation->designations}}" class="form-control input-sm" readonly>
                                    </div>
                                </div>

                            </div>

                        </div>


                    </div>

                    {{-- Tab ! close --}}

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="/admin/employee/directory" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>
            </div>
        </div>

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

</script>




@endsection
