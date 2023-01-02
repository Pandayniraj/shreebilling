@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title !!}
                <small>{!! $page_description !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

  <div class='row'>
        <div class='col-md-12'>
            <div class="box">
                <div class="box-body">
                   <form action="{{route('admin.shiftAttendance.mark_attendance')}}" method="post" 
                   id='markattendenceForm'>  

                    {{ csrf_field() }}
            
                <div class="content col-md-9">
                   <div class="row">
                        <input type="hidden" id='locations' name="locations">
                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-3">Employee Name</label>
                              <div class="col-md-9">
                                <select name="employee_id" class="form-control searchable" id="employee_id" required>
                                  <option value="">Please Select</option>
                                  @foreach($users as $id=>$name)
                                    <option value="{{$id}}">{{$name}}(#{{$id}})</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">  
                           <label class="control-label col-sm-3">Date</label>
                            <div class="input-group ">
                              <input type="text" name="date" placeholder="Date" id="date" value="" class="form-control datepicker date-toggle" required="required">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-credit-card"></i></a>
                                </div>
                            </div>
                          </div>
                        </div>  

                   </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-3">Time</label>
                                <div class="input-group ">
                                   <input type="text" name="time" placeholder="Time From" id="time" value="{{date('H:i')}}" class="form-control datetimepicker" required="required">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-clock-o"></i></a>
                                    </div>
                              </div>
                            </div>
                        </div> 

                    <div class="row">
                       <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-3">Shift</label>
                              <div class="col-md-9">
                                <select name="shift_id" class="form-control" id="shift_id" required>
                                  <option value="">Please Select</option>
                                  @foreach($shift as $key=>$value)
                                    <option value="{{$value->id}}">{{$value->shift_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                        </div>
                    </div>


                    </div> 

                    <div class="row">

                     {{--  <div class="row">
                         <div class="col-md-6">
                              <div class="form-group">  
                                <label class="control-label col-sm-3">Clock Type</label>
                                <div class="col-md-9">
                                  <input type="text" name="clock_type" readonly="" id='clock_type' class="form-control bg-olive" value="">
                                </div>
                              </div>
                          </div>
                      </div> --}}

                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-3">Clock Type</label>
                                <div class="input-group ">
                                   <input type="text" name="clock_type" placeholder="Time Status" id="clock_type"  class="form-control" readonly="">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa  fa-line-chart"></i></a>
                                    </div>
                              </div>
                            </div>
                        </div> 

                    </div>

                                        
               
                  <div class="row">
                     <div class="col-md-12">
                        <label for="inputEmail3" class="control-label">
                        Comments
                        </label>
                          <textarea class="form-control" name="comments" id="comments" placeholder="Comments">{!! \Request::old('comments') !!}</textarea>
                        </div>
                  </div>

                </div><!-- /.content -->

                <div class="col-md-12">
                    <div class="form-group">
                       <input type="submit" value="Mark Attendence" class="btn btn-primary">
                   {{--      <a href="{!! route('admin.activity.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a> --}}
                    </div>
                 </div>

                  </form>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

@section('body_bottom')
@include('partials._date-toggle')
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

    <script type="text/javascript">
      $('.date-toggle').nepalidatetoggle()
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD', 
          sideBySide: true,
          allowInputToggle: true,
          widgetPositioning: {
                    vertical: 'bottom'
                }
        });

      });
</script>

<script type="text/javascript">
         $(document).ready(function() {
    $('.searchable').select2({theme:'bootstrap'});
});
    $(function() {
  $('.datetimepicker').datetimepicker({
      
      format: 'HH:mm',
      sideBySide: true
    });
});

function getClockStatus(){

  let empid = $('#employee_id').val();

  let date = $('#date').val();
   $('#shift_id').val('');
  $('#clock_type').val("");

  if(empid.trim() == ''){
   
    return false;
  }

  if(date.trim() == ''){

    return false;
  }

   $.get(`/admin/shift_mark_attendance/${empid}/view_status?date=${date}`,function(response){
      
    $('#clock_type').val(response.message);
    $('#shift_id').val(response.shift);
    if(response.nextOp == 'In'){
      $('#clock_type').attr('class','form-control bg-olive');
    }else{
      $('#clock_type').attr('class','form-control bg-maroon');
    }

  }).fail(function(){
    alert("Failed To Load");
  });
}


$('#date').on('dp.change',function(){


  getClockStatus();
});
$('#employee_id , .date-toggle').change(function(){

 getClockStatus();

 
});
$(document).on('click','.day',function(){

   getClockStatus();
})

  const options = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 0
};

var user_id = null;


function getLocation() {

    return new Promise((resolve,reject) => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                return resolve(position);
            }, function(err) {
                return reject(err);
            });

        } else {
            return reject(false);
        }
    })

}

$('#markattendenceForm').submit(function(evt){
  evt.preventDefault();
  getLocation().then(response=>{
      let  crd = response.coords;
      let location = JSON.stringify({lat: crd.latitude,long: crd.longitude});
      $('#markattendenceForm #locations').val(location);
      console.log(location);
      $('#markattendenceForm').get(0).submit();
  }).catch(err=>{
      console.log('err');
      $('#markattendenceForm').get(0).submit();
  });

})






</script>

 @endsection

