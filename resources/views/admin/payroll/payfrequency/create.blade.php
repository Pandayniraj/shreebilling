@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

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
		     	<form method="post" action="/admin/payroll/payfrequency/create" enctype="multipart/form-data">  
		     	{{ csrf_field() }}                 
		     	<h4>Pay Frquency</h4>
           <div class="row">
                    <div class="col-md-2">
                          <label class="control-label">Date Type</label>
                          <select id='selectdatetype' name='datetype'>
                            <option value="eng">English</option>
                            <option value="nep">Nepali</option>
                          </select>
                   
                      </div>
                     </div>
                  <div class="row">
                    <div class="col-md-8 form-group">
                          <label class="control-label">Fiscal Year</label>
                          <select name='fiscal_year_id' class="form-control">
                            <option value="">Select Fiscal Year</option>
                            @foreach($fiscal_year as $k=>$fy)
                            <option value="{{$fy->id}}"
                              @if($current_fiscal_year->id == $fy->id )
                              selected=""@endif 
                              >{{$fy->fiscal_year}}</option>
                            @endforeach
                          </select>
                   
                      </div>
                     </div>

                      <div class="row">
                      <div class="col-md-8 form-group">
                          <label class="control-label">Name</label>
                            <input type="text" name="name" placeholder="PayFrequency Name.." class="form-control" required="" >
                      </div>
                     </div>
                     <div class="row">
                    <div class="col-md-8 form-group">
                          <label class="control-label">Frequency </label>
                      {!! Form::select('frequency',$frequency,'M',['class'=>'form-control']) !!}
                      </div>
                     </div>

                     <div class="row">
                    <div class="col-md-8 form-group">
                          <label class="control-label">Time Entry Method</label>
                      {!! Form::select('time_entry_method',array('T'=>'Timesheet','W'=>'Web & Mobile','B'=>'BioMetric'),'W',['class'=>'form-control']) !!}
                      </div>
                     </div>
                     <div id="dateselectors">
                     <div class="row">
	                   	<div class="col-md-8 form-group">
	                   	    <label class="control-label">Check date</label>
                            <input type="text" name="check_date" placeholder="Checking Date" id="" value="{{date('Y-m-d')}}" class="form-control datepicker" required="" >
	                   	</div>
                     </div>
                       <div class="row">
                        <div class="col-md-8 form-group">
                          <label class="control-label">Start date</label>
                            <input type="text" name="period_start_date" placeholder="Period start date"  value="" class="form-control datepicker" >
                      </div>
                     </div>
                       <div class="row">
                  <div class="col-md-8 form-group">
                          <label class="control-label">End date</label>
                            <input type="text" name="period_end_date" placeholder="Period end date" id="" value="" class="form-control datepicker" >
                      </div>
                     </div>
                   </div>
                    
	               
       <br>
		        <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
	                        {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
	                        <a href="{!! route('admin.onboard.tasktype') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>

	        </form>
		</div>
	</div>
        
   <div id='englishdate' style="display: none">
       <div class="row">
        <div class="col-md-8 form-group">
            <label class="control-label">Check date</label>
              <input type="text" name="check_date" placeholder="Checking Date" id="" value="{{date('Y-m-d')}}" class="form-control datepicker" required="" >
        </div>
       </div>
         <div class="row">
          <div class="col-md-8 form-group">
            <label class="control-label">Start date</label>
              <input type="text" name="period_start_date" placeholder="Period start date"  value="" class="form-control datepicker" >
        </div>
       </div>
         <div class="row">
    <div class="col-md-8 form-group">
            <label class="control-label">End date</label>
              <input type="text" name="period_end_date" placeholder="Period end date" id="" value="" class="form-control datepicker" >
        </div>
       </div>
   </div>

      <div id='nepalidate' style="display: none">
       <div class="row">
        <div class="col-md-8 form-group">
            <label class="control-label">Check date</label>
              <input type="text" name="check_date" placeholder="Cheque मिति" id=""  class="form-control nepalidatepicker" required="" data-single='true' >
        </div>
       </div>
         <div class="row">
          <div class="col-md-8 form-group">
            <label class="control-label">Start date</label>
              <input type="text" placeholder="सुरु  मिति " class="form-control input-sm nepalidatepicker"  name="period_start_date" data-single='true'>
        </div>
       </div>
         <div class="row">
    <div class="col-md-8 form-group">
            <label class="control-label">End date</label>
                <input type="text" placeholder="अन्तिम मिति" class="form-control input-sm nepalidatepicker" name="period_end_date" data-single='true'>
        </div>
       </div>
   </div>
@endsection

@section('body_bottom')

    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
 <link rel="stylesheet" href="/nepali-date-picker/nepali-date-picker.min.css">
<script type="text/javascript" src="/nepali-date-picker/nepali-date-picker.js"> </script>

<script type="text/javascript">

function setnepalidate(){
          $(".nepalidatepicker").nepaliDatePicker({
        dateFormat: "%y-%m-%d",
        closeOnDateSelect: true
        });
}
function setenglishdate(){
   $('.datepicker').datetimepicker({
          //inline: true,
          //format: 'YYYY-MM-DD',
          format: 'YYYY-MM-DD', 
              sideBySide: true,
              allowInputToggle: true
        });
}

 $('#selectdatetype').change(function(){

  let type = $(this).val();
  if(type =='nep'){
    let html = $('#nepalidate').html();
    $('#dateselectors').html(html);
    setnepalidate();
  }else{
    let html = $('#englishdate').html();
    $('#dateselectors').html(html);
    setenglishdate();
  }

  



 });
  $(function(){
    $('#selectdatetype').val('eng');
    setenglishdate();
  });

</script>
@endsection
