@extends('layouts.master')

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {{-- Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body ">
                <form method="post" action="{{route('admin.airline.update',$airline->id)}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Name</label>
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="Name" required="" value="{{$airline->name}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Iata Desi</label>
                            <div class="input-group">
                                <input type="text" name="iata_desi" class="form-control" placeholder="Eg: 14,111,444" value="{{$airline->iata_desi}}" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">3 Digit Code</label>
                            <div class="input-group">
                                <input type="text" name="digit_code_3" class="form-control" placeholder="3 Digit Code" value="{{$airline->digit_code_3}}" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Country</label>
                            <div class="input-group">
                                {!! Form::select('country', ["Afghanistan"=>"Afghanistan",
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
                                "Burkina Faso"=>"Burkina Faso",
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
                                "Zimbabwe"=>"Zimbabwe"],$airline->country, ['class' => 'form-control label-default','style'=>'width:400px;']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Thumbnail</label>
                            <div class="input-group">
                                <input type="file" name="thumbnail" class="form-control">
                            </div>
                        </div>
                        @if($airline->thumbnail)
                        <img src="/airline/{{$airline->thumbnail}}" width="100px;">
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 form-group">
                            <label class="control-label">Adjustment Adult Amount</label>
                            <div class="input-group">
                                <input type="text" name="adjust_adult_amount" class="form-control" placeholder="Adjustment Adult Amount" value="{{$airline->adjust_adult_amount}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 form-group">
                            <label class="control-label">Adjustment Child Amount</label>
                            <div class="input-group">
                                <input type="text" name="adjust_child_amount" class="form-control" placeholder="Adjustment Child Amount" value="{{$airline->adjust_child_amount}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 form-group">
                            <label class="control-label">Adjustment Infant Amount</label>
                            <div class="input-group">
                                <input type="text" name="adjust_infant_amount" class="form-control" placeholder="Adjustment Infant Amount" value="{{$airline->adjust_infant_amount}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-3 col-sm-12 form-group">
                            <label class="control-label">Adjustment Total Amount</label>
                            <div class="input-group">
                                <input type="text" name="user_total_commission" class="form-control" placeholder="Adjustment Total Amount" value="{{$airline->user_total_commission}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 form-group">
                            <label class="control-label">Adjustment Agent Adult Amount</label>
                            <div class="input-group">
                                <input type="text" name="agent_adult_adjust_amount" class="form-control" placeholder="Adjustment Agent Adult Amount" value="{{$airline->agent_adult_adjust_amount}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 form-group">
                            <label class="control-label">Adjustment Agent Child Amount</label>
                            <div class="input-group">
                                <input type="text" name="agent_child_adjust_amount" class="form-control" placeholder="Adjustment Agent Child Amount" value="{{$airline->agent_child_adjust_amount}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 form-group">
                            <label class="control-label">Adjustment Agent Infant Amount</label>
                            <div class="input-group">
                                <input type="text" name="agent_infant_adjust_amount" class="form-control" placeholder="Adjustment Agent Infant Amount" value="{{$airline->agent_infant_adjust_amount}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 form-group">
                            <label class="control-label">Adjustment Agent Total Amount</label>
                            <div class="input-group">
                                <input type="text" name="agent_total_commission" class="form-control" placeholder="Adjustment Agent Total Amount" value="{{$airline->agent_total_commission}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3 col-sm-12 form-group">
                            <label class="control-label">Airline Comissions</label>
                            <div class="input-group">
                                <input type="number" step="any" name="airline_commission" class="form-control" placeholder="Airline Total Comissions" 
                                value="{{$airline->airline_commission}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                                <a href="{!! route('admin.airline.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
