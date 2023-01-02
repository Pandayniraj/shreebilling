@extends('layouts.master')
@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
       {{ $page_title ?? "Page Title"}}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    <label>Select Date Type</label>
    <select class="bg-green" id='datetoogles'>
    	<option value="eng">English</option>
    	<option value="nep" @if(\Request::get('date_type') =='nep') selected="" @endif>Nepali</option>
    </select><br>
     Current Leave Year: <strong>{{ TaskHelper::cur_leave_yr()->leave_year}}</strong>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>
<style>
    .required { color: red; }
    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
    }
    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }
</style>
<div class="row"> 
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Employee Leave  Reports</strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="leaverereport-form" role="form" enctype="multipart/form-data" action="/admin/leavereport/" method="GET" 
                class="form-horizontal form-groups-bordered">
                    <input type="hidden" name="date_type" >
                  <div class="form-group">
                        <label for="user_id" class="col-sm-3 control-label">Filter Type<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required name="filter_type" id="filter_type" class="form-control select_box">
                               	<option value="annual_year">Annual Year</option>
                               	<option value="date_range"
                               	@if(\Request::get('filter_type') == 'date_range')
                               	selected="" @endif>Date Range</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="user_id" class="col-sm-3 control-label">Employee</label>

                        <div class="col-sm-5">
                            <select  name="user_id" id="user_id" class="form-control select_box">
                                <option value="">All Employee</option>
                                @foreach($userlists as $uv)
                                <option value="{{ $uv->id }}" @if(\Request::get('user_id') == $uv->id) selected="selected" @endif>{{ $uv->first_name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div id='filter-options'>
                    	
                    </div>
                 


                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5 ">
                            <button type="submit" id="sbtn" class="btn btn-primary">Search</button>
                            @if(\Request::get('filter_type'))
                            <button type="button" id="btn-filter-clear" class="btn btn-danger">Clear</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id='filter-options-choice' style="display: none;">
<div class="form-group" id='date_range'>
    <label for="date_in" class="col-sm-3 control-label">Date<span class="required">*</span></label>
    <div id='dates_options'></div>
    
</div>
<div class="form-group" id='annual_leaves'>
	<label  class="col-sm-3 control-label">Years<span class="required">*</span></label>
		<div class="col-sm-5">
			{!! Form::select('leave_years',$leave_years,\Request::get('leave_years'),['class'=>'form-control annual_year']) !!}
		   
		</div>
</div>

</div>


<div id='nepali_eng_dates' style="display: none;">
	<div id='eng_dates'>
	<div class="col-sm-2">
	        <div class="input-group">
	            <input required="" type="text" class="form-control date_in date-toggle" value="{{ isset($startdate) ? $startdate : '' }}" name="start_date" placeholder="Start Date...">
	            <div class="input-group-addon">
	                <a href="#"><i class="fa fa-calendar"></i></a>
	            </div>
	        </div>
	</div>
	 <div class="col-sm-3">
	    <div class="input-group">
	        <input required="" type="text" class="form-control date_in date-toggle" value="{{ isset($enddate) ? $enddate : '' }}" name="end_date" 
	        placeholder="End Date...">
	        <div class="input-group-addon">
	            <a href="#"><i class="fa fa-calendar"></i></a>
	        </div>
	    </div>
	</div>
	</div>

<div id='nep_dates'>
	<div class="col-sm-2">
	        <div class="input-group">
	            <input required="" type="text" class="form-control nepalidatespicker date-toggle" value="{{ isset($nepstart_date) ? $nepstart_date : '' }}" name="start_date" placeholder="सुरु मिती..."  data-single='true'>
	            <div class="input-group-addon">
	                <a href="#"><i class="fa fa-calendar"></i></a>
	            </div>
	        </div>
	</div>
	 <div class="col-sm-3">
	    <div class="input-group">
	        <input required="" type="text" class="form-control nepalidatespicker date-toggle" value="{{ isset($nepend_date) ? $nepend_date : '' }}" name="end_date" 
	        placeholder="अन्तिम मिती..."  data-single='true'>
	        <div class="input-group-addon">
	            <a href="#"><i class="fa fa-calendar"></i></a>
	        </div>
	    </div>
	</div>
</div>

</div>




<div class='row'>
        <div class='col-md-12'>

        	   <div class="box">
        	   
                                 
						   
                    <div class="box-body">
                    	<div class="">
                    		<table class="table table-hover table-no-border table-striped" id="leads-table">
                                <thead>
                                       <tr class="bg-olive">
                                            <th>Name</th>
                                            <th>Line Manger</th>
                                            @foreach($categories as $cv)
                                            <th title="{{ $cv->leave_category }}">{{ $cv->leave_code }}</th>
                                            @endforeach
                                            <th>Histroy</th>
                                        </tr>
                                </thead>
                                <tbody>
                                	   @foreach($users as $uv)
                                        <tr>
                                            <td>{{ $uv->first_name.' '.$uv->last_name }}</td>
                                            <?php  $lineManager =  $uv->firstLineManger;?>


                                            <td>{{  $lineManager->first_name ?? '' }} {{  $lineManager->last_name ??'' }}</td>
                                            @foreach($categories as $cv)
                                            <td>{{ \TaskHelper::userLeaveALLReport($uv->id, $cv->leave_category_id, $startdate,$enddate) }}
                                            @if($cv->leave_code == env('EARNED_LEAVE_CODE','ERL'))

                                            @elseif($cv->leave_code == env('SICK_LEAVE_CODE','SKL'))

                                            @else
                                                / {{ $cv->leave_quota }}
                                            @endif
                                            </td>
                                            @endforeach
                                            <td>
                            <a href="/admin/allpendingleaves?&leave_status=4&user_id={{ $uv->id }}"><i class="fa fa-calendar" target="_blank"></i></a>
                                            </td>
                                        </tr>
                                       @endforeach
                                </tbody>
                            </table>
                    	</div>
                    </div>

                      <div style="text-align: center;"> {!! $users->appends(\Request::except('page'))->render() !!} </div>
                </div>
        </div>
 </div>
 <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">

<link rel="stylesheet" href="/nepali-date-picker/nepali-date-picker.min.css">
<script type="text/javascript" src="/nepali-date-picker/nepali-date-picker.js"> </script>

<script type="text/javascript">

       $('.select_box').select2({
            theme: 'bootstrap',
        });

    </script>
<script type="text/javascript">

function applynepdate(){
	$('#filter-options #dates_options').html($('#nepali_eng_dates #nep_dates').html());
	$(".nepalidatespicker").nepaliDatePicker();
}
function applyengdate(){
	$('#filter-options #dates_options').html($('#nepali_eng_dates #eng_dates').html());
	$('.date_in').datetimepicker({
		//inline: true,
		format: 'YYYY-MM-DD',
		sideBySide: true
	});
}
function applydatepicker(){
	let val = $('section #datetoogles').val();
	if(val == 'nep'){
		applynepdate();
	}else{
		applyengdate();
	}
}
$(document).on('change','section #datetoogles',function(){
	$('#leaverereport-form input[name=date_type]').val($(this).val());
	applydatepicker();
});

const filter_option = {date_range: false,annual_year: false};
$('#filter_type').change(function(){
	let type = $(this).val();
	if(type == 'date_range'){
		
		$('#filter-options').html($('#filter-options-choice #date_range').clone());

		applydatepicker();
		

	}else{
		$('#filter-options').html($('#filter-options-choice #annual_leaves').clone());
		$('#filter-options select.annual_year').select2({
			theme: 'bootstrap',
		});
	}

});

$('#filter_type').trigger('change');
$('#leaverereport-form input[name=date_type]').val($('section #datetoogles').val());


$("#btn-filter-clear").on("click", function () {
	window.location.href = "{!! url('/') !!}/admin/leavereport";
});



 </script>



@endsection