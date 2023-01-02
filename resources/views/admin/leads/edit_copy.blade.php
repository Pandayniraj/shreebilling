@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<style>
    select { width:200px !important; }
label {
    font-weight: 600 !important;
}

 .intl-tel-input { width: 100%; }
 .intl-tel-input .iti-flag .arrow {border: none;}

</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title or "Page Title" }}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

          
    <div class='row'>
        <div class='col-md-12'>
            <div class="box">
				<div class="box-body">
                {!! Form::model( $lead, ['route' => ['admin.leads.update', $lead->id], 'method' => 'PATCH', 'id' => 'form_edit_lead'] ) !!}
                <div class="content col-md-9">
                     <h3> Basic Details</h3>
                	<div class="row">

                    <div class="col-md-4">
                            <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">
                                {!! Form::label('title', trans('admin/leads/general.columns.title')) !!}
                              </label>
                              <div class="col-sm-10">
                                {!! Form::select('title', ['Mr'=>'Mr', 'Miss'=>'Miss', 'Mrs'=>'Mrs', 'Ms'=>'Ms','Others'=>'Others'], null, ['class' => 'form-control']) !!}
                              </div>
                            </div>
                        </div>
                   
                    

                      <div class="col-md-4">
                         <div class="form-group">  
                            <div class="input-group ">
                                <input type="text" name="company_id" placeholder="Business Name" id="company_id" value="{{ $lead->company->name }}" class="form-control" pattern="[a-zA-Z0-9]+" required>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-building"></i></a>
                                </div>
                            </div>
                        </div>    
                      </div> 

                         <div class="col-md-4">
                        <div class="form-group">  
                         <label class="control-label col-sm-4">Follow up</label>
                         <div class="input-group ">
                        <input type="text" name="target_date" id="target_date" value="{{ $lead->target_date }}" placeholder="Next Action Date" class="form-control datepicker" required="required">
                        <div class="input-group-addon">
                            <a href="#"><i class="far fa-calendar-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>
                  </div>
                    <div class="row">

                      <div class="col-md-4">
                          <div class="form-group">  
                           <label class="control-label col-sm-3">Value</label>
                    <div class="input-group ">
                        <input type="text" name="price_value" placeholder="Expected Company Revenue" id="price_value" value="{{$lead->price_value}}" class="form-control" required="required">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-credit-card"></i></a>
                        </div>
                    </div>
                </div>
                        </div>



                         <div class="col-md-4">
                          <div class="form-group">  
                <label class="control-label col-sm-3">Sector</label>
                    <div class="input-group ">
                        <input type="text" name="sector" placeholder="business area" id="sector" value="{{ old('sector') }}" class="form-control" required="required">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-tasks"></i></a>
                        </div>
                    </div>
                </div>
                        </div>

                    </div> 
                     <div class="row">


                          <div class="col-md-4">
                       <div class="form-group">  
                <label class="control-label col-sm-3">Address</label>
                    <div class="input-group ">
                        <input type="text" name="address_line_1" id="address_line_1" value="{{ $lead->address_line_1 }}" class="form-control" required="">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-map"></i></a>
                        </div>
                    </div>
                </div></div>
                      

                        <div class="col-md-4">
                          <div class="form-group">  
                <label class="control-label col-sm-3">City</label>
                    <div class="input-group ">
                        <input type="text" name="city" placeholder="city" id="city" value="{{ $lead->city }}" class="form-control" required="required">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-map"></i></a>
                        </div>
                    </div>
                </div>
                        </div>

                     

                      <div class="col-md-4">
                          <div class="form-group">  
                         <label class="control-label col-sm-3">Country</label>
                          <div class="input-group ">
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
                        "Zimbabwe"=>"Zimbabwe"],'Nepal', ['class' => 'form-control label-default']) !!}
                        
                    </div>
                         </div>
                        </div>

                    </div>

                    <h3> Contact Details</h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4">Home Phone</label>
                                <div class="input-group col-sm-6">
                                {!! Form::text('home_phone', null, ['class' => 'form-control']) !!}
                            </div>
                            </div>
                         </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4">Address</label>
                                <div class="input-group col-sm-6">
                              {!! Form::text('address_line_1', null, ['class' => 'form-control']) !!}
                          </div>
                          </div>
                       </div>
                    </div>
                    

                      <div class="row">


                        <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-sm-4">Price Value</label>
                                <div class="input-group col-sm-6">
                              {!! Form::text('price_value', null, ['class' => 'form-control', 'id'=>'price_value']) !!}
                          </div>
                          </div>
                        </div>


                        <div class="col-md-6">
                        <div class="form-group">
                        <label class="col-sm-4">Sector</label>
                                <div class="input-group col-sm-6">
                            {!! Form::text('sector', null, ['class' => 'form-control', 'id' => 'sector']) !!}
                        </div>
                    </div>
                      </div>

                    </div>

                    <div class="row">


                     <div class="col-md-6">
                        <div class="form-group">
                        <label class="col-sm-4">Website</label>
                                <div class="input-group col-sm-6">
                            <div class="input-group ">
                                {!! Form::text('homepage', null, ['class' => 'form-control', 'id' => 'homepage']) !!}
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-home"></i></a>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                          <label class="col-sm-4">Description</label>
                                <div class="input-group col-sm-6">
                          <textarea class="form-control useMCE" name="description" id="description" rows="3">{!! $lead->description !!}</textarea>
                        </div></div>


                    </div>

                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-sm-4">Position</label>
                                <div class="input-group col-sm-6">
                              {!! Form::text('position', null, ['class' => 'form-control', 'id'=>'position']) !!}
                          </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                         <div class="form-group">
                              <label class="col-sm-4">Organization</label>
                                <div class="input-group col-sm-6">
                              {!! Form::text('organization', null, ['class' => 'form-control', 'id'=>'organization']) !!}
                          </div>
                          </div>
                      </div>
                        
                    </div>
                    <div class="row">
                         <div class="col-md-6">
                         <div class="form-group">
                              <label class="col-sm-4">DOB</label>
                         <div class="input-group col-sm-6">
                                {!! Form::text('dob', null, ['class' => 'form-control datepicker', 'id' => 'dob']) !!}
                                <div class="input-group-addon">
                                    <a href="#"><i class="far fa-calendar-alt"></i></a>
                                </div>
                            </div></div></div>

                    	<div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! '<input type="hidden" name="enabled" value="1">' !!}
                                        {!! Form::checkbox('enabled', '1', $lead->enabled) !!} {{ trans('general.status.enabled') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- /.content -->

                <div class="content col-md-3">
                	<div class="form-group">
                        {!! Form::label('rating', trans('admin/leads/general.columns.rating')) !!}

                        {!! Form::select('rating', ['acquired'=>'Acquired', 'active'=>'Active', 'failed'=>'Failed', 'blacklist'=>'Blacklist'], $lead->rating, ['class' => 'form-control label-default']) !!}
                    </div>
                	<div class="form-group">
                        {!! Form::label('course_id', trans('admin/leads/general.columns.course_name')) !!}
                        {!! Form::select('course_id', $courses, $lead->course_id, ['class' => 'form-control label-default']) !!}
                    </div>


                       <div class="form-group">  
                
                    <div class="col-sm-10">
                        {!! Form::text('custom_product', null, ['class' => 'form-control','placeholder'=>'custom product']) !!}
                    </div>
                </div>
                




                    <div class="form-group">
                        {!! Form::label('communication_id', trans('admin/leads/general.columns.communication_name')) !!}
                        {!! Form::select('communication_id', $communications, $lead->communication_id, ['class' => 'form-control label-default']) !!}
                    </div>


                    <div class="form-group">
                    	{!! Form::label('status_id', trans('admin/leads/general.columns.status_id')) !!}
                    	{!! Form::select('status_id', $lead_status, $lead->status_id, ['class' => 'form-control label-default']) !!}
                    </div>

                    <div class="form-group">
                      {!! Form::label('email_opt_out', trans('admin/leads/general.columns.email_opt_out')) !!}
                       {!! Form::select('email_opt_out', ['1'=>'Yes', '0'=>'No'], $lead->email_opt_out, ['class' => 'form-control input-xs label-default']) !!}
                    </div>


                    <div class="form-group">
                        {!! Form::label('user_id', trans('admin/leads/general.columns.user')) !!}
                        {!! Form::select('user_id', $users, $lead->user_id, ['class' => 'form-control label-default', '']) !!}
                    </div>



                      <div class="form-group">
                            {!! Form::label('lead_type_id', trans('admin/leads/general.columns.lead_type')) !!}
                            @if(!null == Request::get('type'))
                              @if(Request::get('type') == 'target')
                              {!! Form::select('lead_type_id', ['1'=>'Target', '2'=>'Leads','4'=>'Customer','5'=>'Contact','6'=>'Agent'], $lead->lead_type_id, ['class' => 'form-control']) !!}
                              @elseif(Request::get('type') == 'leads')
                              {!! Form::select('lead_type_id', ['2'=>'Leads','4'=>'Customer','5'=>'Contact','6'=>'Agent'], $lead->lead_type_id, ['class' => 'form-control']) !!}
                              @elseif(Request::get('type') == 'customer')
                              {!! Form::select('lead_type_id', [ '4'=>'Customer','5'=>'Contact','6'=>'Agent'], $lead->lead_type_id, ['class' => 'form-control']) !!}
                              @endif
                              <input type="hidden" name="type" value="{{ Request::get('type') }}">
                            @else
                            {!! Form::select('lead_type_id', $lead_types, $lead->lead_type_id, ['class' => 'form-control']) !!}
                            <input type="hidden" name="type" value="leads">
                            @endif
                      </div>



                         

                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                        <a href="{!! route('admin.leads.index') !!}?type={{\Request::get('type')}}" class='btn btn-danger'>{{ trans('general.button.cancel') }}</a>
                    </div>
                </div>

                {!! Form::close() !!}
            	</div><!-- /.box-body -->
        	</div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script>
	$(function() {
		$('#dob').datetimepicker({
				//inline: true,
				//format: 'YYYY-MM-DD',
				format: 'DD-MM-YYYY',
				sideBySide: true
			});
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#contact_id").autocomplete({
          source: "/admin/getContacts",
          minLength: 1
    });

    $( "#city" ).autocomplete({
      source: "/admin/getCities",
      minLength: 1
    });

	});
</script>
 <script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        });

      });
</script>
@endsection
