@extends('layouts.master')
@section('content')

<style>
    .required { color: red; }
    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
    }
    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }
.datetimepicker {
        position: relative;
    }
    .datepicker{
      position: relative;
    }
</style>

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title !!}
                <small>{!! $page_description !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Bulk add Attendence</strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" 
                action="/admin/bulk/mark_attendance" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Select Deparment<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required name="dep_id" id="department" class="form-control select_box">
                                <option value="">Select Department</option>
                                @foreach($department as $dk => $dv)
                                  <option value="{{ $dv->departments_id }}" @if($departments_id == $dv->departments_id) selected="selected" @endif>
                                    {{ $dv->deptname }}
                                  </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <button type="submit" id="sbtn" class="btn btn-primary">Search</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if($users)
<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <form id="markattendenceForm" role="form" enctype="multipart/form-data" 
                action="/admin/bulk/mark_attendance/save" method="post" class="form-horizontal form-groups-bordered" >
                 <input type="hidden" id='locations' name="locations">
                   <div class="box-tools">
                <label class="control-label" style=" float: left;">Date:&nbsp;</label>
            <div class="input-group input-group-sm hidden-xs" style="width: 150px; float: left;">
                  <input type="text"class="form-control datepicker" placeholder="Date" id='timesheetdate'
                 >

                  <div class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa  fa-calendar"></i></button>
                  </div>
                </div>
                  <label class="control-label" style=" float: left;"> Time:&nbsp;</label>
            <div class="input-group input-group-sm hidden-xs" style="width: 150px; float: left;">
                  <input type="text"class="form-control datetimepicker" placeholder="Time From" required="" id='timesheetstartime' value="8:00"
                 >
                     <div class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa  fa-hourglass-1 (alias)"></i></button>
                  </div>
                </div>

                 
                 
                
              </div>
             {{ csrf_field() }}<br><br>
                    <table id="" class="table table-bordered std_table">
                        <thead>
                        <tr>
                            <th class="col-sm-2">Name</th>
                            <th class="col-sm-2">Designation</th>
                            <th class="col-sm-2">Date</th>
                            <th class="col-sm-2">Time</th>
                            <th class="col-sm-2">Clock Type</th>
                            <th class="col-sm-2">Comment</th>
                        </tr>
                        </thead>
                            <tbody>
                                @foreach($users as $uv)
                                <tr>
                            <td class="col-sm-2">{{ $uv->first_name.' '.$uv->last_name }}(#{{$uv->id}})
                                <input type="hidden" name="employee_id[]" value="{{$uv->id}}">
                            </td>
                                    <td class="col-sm-2">{{ $uv->designation->designations }}</td>
                                    <td class="col-sm-2">
                                        <input type="text" name="date[]" class="form-control timesheetdate input-sm datepicker" required="" value="{{date('Y-m-d')}}" placeholder="Working Date">                                 
                                    </td>
                                       <td class="col-sm-2">
                                        <input type="text" name="time[]" class="form-control input-sm datetimepicker starttime" required="" placeholder="Start Time" value="8:00">                                 
                                    </td>
                                       
                                    <td>
                                      <input type="text" name="clock_type[]" readonly="" class="form-control" value="{{ $uv->clocking_status() }}">
                                    </td>
                                    <td>
                                      <input type="text" name="comments[]" class="form-control" value="">
                                    </td>
                                    <td class="col-sm-1">
                                        <a href="javascript::void()" title="remove employee"><i class="remove-this fa fa-trash deletable"></i></a>
                                    </td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                @if(count($users) > 0)
              
                <button  type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-save (alias)"></i>&nbsp; Save
              </button>
              @endif
             {{--  <a href="/admin/timesheet/" type="button" class="btn btn-default btn-lg" >
                <i class="fa fa-close"></i>&nbsp; Cancel
              </a> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endif
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')

<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

<script type="text/javascript">
    $(function() {
$('.select_box').select2();
    $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD', 
          sideBySide: true,
          allowInputToggle: true,
          widgetPositioning: {
                    vertical: 'bottom'
                }
        });
    $('.datetimepicker').datetimepicker({  
      format: 'HH:mm',
      sideBySide: true,
        widgetPositioning: {
                    vertical: 'bottom'
                }
    });
});

$(document).on('click', '.remove-this', function () {
    $(this).parent().parent().parent().remove();
});
$('#timesheetactivity').change(function(){
    $('.select_box').each(function(){
        $(this).select2('destroy');
    })
    let val = $(this).val();
    $('.timesheetactivity').val(val);
    $('.select_box').select2();
});
$('#timesheetdate').on('dp.change',function(){
    $('.timesheetdate').val($(this).val());
});
$('#timesheetstartime').on('dp.change',function(){
   $('.starttime').val($(this).val());
});
$('#timesheetendtime').on('dp.change',function(){
   $('.endtime').val($(this).val());
})

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
