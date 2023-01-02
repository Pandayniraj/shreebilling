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
                <form method="post" action="{{route('admin.airport.store')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Code</label>
                            <div class="input-group">
                                <input type="text" name="code" class="form-control" placeholder="Code" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Name</label>
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="Name" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">City Code</label>
                            <div class="input-group">
                                <input type="text" name="cityCode" class="form-control" placeholder="City Code" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">City Name</label>
                            <div class="input-group">
                                <input type="text" name="cityName" class="form-control" placeholder="City Name" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Country Name</label>
                            <div class="input-group">
                                {!! Form::select('countryName', ["Afghanistan"=>"Afghanistan",
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
                                "Zimbabwe"=>"Zimbabwe"],'Nepal', ['class' => 'form-control','style'=>'width:400px;']) !!}
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">County Code</label>
                            <div class="input-group">
                                <input type="text" name="countryCode" class="form-control" placeholder="County Code" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Time Zone</label>
                            <div class="input-group">
                                <input type="text" name="timezone" class="form-control" placeholder="Time Zone" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Latitude</label>
                            <div class="input-group">
                                <input type="text" name="lat" class="form-control" placeholder="Latitude" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Longitude</label>
                            <div class="input-group">
                                <input type="text" name="lon" class="form-control" placeholder="Longitude" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">City</label>
                            <div class="input-group">
                                <select name="city" class="form-control" style="width:400px;">
                                    <option value="true">true</option>
                                    <option value="false">false</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                                <a href="{!! route('admin.airport.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
