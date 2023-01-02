@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />

@section('content')
        <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>


     <div class='row'>
       <div class='col-md-11'>
          <div class="box">
		     <div class="box-body ">
		     	<form method="post" action="/admin/payroll/payfrequency/{{$payfrequency->id}}" enctype="multipart/form-data">  
		     	{{ csrf_field() }}                 
		     	<h4>Pay Frquency</h4>
                  <div class="row">
                    <div class="col-md-8 form-group">
                          <label class="control-label">Fiscal Year</label>
                          <select name='fiscal_year_id' class="form-control">
                            <option value="">Select Fiscal Year</option>
                            @foreach($fiscal_year as $k=>$fy)
                            <option value="{{$fy->id}}"
                              @if($payfrequency->fiscal_year_id == $fy->id )
                              selected=""@endif 
                              >{{$fy->fiscal_year}}</option>
                            @endforeach
                          </select>
                   
                      </div>
                     </div>
                     <div class="row">
                    <div class="col-md-8 form-group">
                          <label class="control-label">Frequency </label>
                   {!! Form::select('frequency',$frequency,$payfrequency->frequency,['class'=>'form-control']) !!}
                      </div>
                     </div>
                      <div class="row">
                      <div class="col-md-8 form-group">
                          <label class="control-label">Name</label>
                            <input type="text" name="name" value="{{$payfrequency->name}}" placeholder="PayFrequency Name.." class="form-control" required="" >
                      </div>
                     </div>
                      <div class="row">
                    <div class="col-md-8 form-group">
                          <label class="control-label">Time Entry Method</label>
                      {!! Form::select('time_entry_method',array('T'=>'Timesheet','W'=>'Web & Mobile','B'=>'BioMetric'),$payfrequency->time_entry_method,['class'=>'form-control']) !!}
                      </div>
                     </div>
                     <div class="row">
	                   	<div class="col-md-8 form-group">
	                   	    <label class="control-label">Check date</label>
                            <input type="text" name="check_date" placeholder="Checking Date" id="{{$payfrequency->check_date}}" value="{{date('Y-m-d')}}" class="form-control datepicker" required="" >
	                   	</div>
                     </div>
                       <div class="row">
                        <div class="col-md-8 form-group">
                          <label class="control-label">Start date</label>
                            <input type="text" name="period_start_date" placeholder="Period start date"  value="{{$payfrequency->period_start_date}}" class="form-control datepicker" >
                      </div>
                     </div>
                       <div class="row">
                  <div class="col-md-8 form-group">
                          <label class="control-label">End date</label>
                            <input type="text" name="period_end_date" placeholder="Period end date" id="place_of_visit" value="{{$payfrequency->period_end_date}}" class="form-control datepicker" >
                      </div>
                     </div>
                    
	               
       <br>
		        <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
	                        {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
	                        <a href="{!! route('admin.payfrequency.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>

	        </form>
		</div>
	</div>
        
   
@endsection

@section('body_bottom')

    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<script type="text/javascript">
	$(function(){
		    $('.datepicker').datetimepicker({
          //inline: true,
          //format: 'YYYY-MM-DD',
          format: 'YYYY-MM-DD', 
              sideBySide: true,
              allowInputToggle: true
        });
	})

 

</script>
@endsection
