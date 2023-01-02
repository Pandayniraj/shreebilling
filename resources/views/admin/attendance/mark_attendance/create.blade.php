@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

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
                   <form action="{{route('admin.mark_attendance')}}" method="post" 
                   id='markattendenceForm'>  

                    {{ csrf_field() }}
            
                <div class="content col-md-9">
                   <div class="row">
                        <input type="hidden" id='locations' name="locations">
                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-3">Employee Name</label>
                              <div class="col-md-9">
                                <select name="employee_id" class="form-control project_id" id="employee_id" required>
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
                                   <input type="text" name="time" placeholder="Time From" id="time" value="8:00" class="form-control datetimepicker" required="required">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-credit-card"></i></a>
                                    </div>
                              </div>
                            </div>
                        </div> 

                    <div class="row">
                       <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-3">Clock Type</label>
                              <div class="col-md-9">
                                <input type="text" name="clock_type" readonly="" id='clock_type' class="form-control">
                             {{--    <select name="clock_type" class="form-control" required>
                                  <option value="in">In</option>
                                  <option value="out">Out</option>
                                </select> --}}
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
    $('.project_id').select2();
});
    $(function() {
  $('.datetimepicker').datetimepicker({
      
      format: 'HH:mm',
      sideBySide: true
    });
});
$('#employee_id').change(function(){
  let empid = $(this).val();
  if(empid.trim() == ''){
    $('#clock_type').val("");
    return false;
  }
  $.get(`/admin/mark_attendance/checkstatus/${empid}`,function(response){
    if(response.status == 'in'){
      $('#clock_type').val('in');
    }else{
      $('#clock_type').val('out');
    }
  }).fail(function(){
    alert("Failed To Load");
  });
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

