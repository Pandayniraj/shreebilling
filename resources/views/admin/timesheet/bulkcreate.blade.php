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

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Bulk add Employee TimeSheet</strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/bulkadd/timesheet/create" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Select Project<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required name="project_id" id="department" class="form-control select_box">
                                <option value="">Select Projects</option>
                                @foreach($projects as $dk => $dv)
                                <option value="{{ $dv->id }}" @if(isset($project_id) && $project_id == $dv->id) selected="selected" @endif>{{ $dv->name }}</option>
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
@if(isset($users))
<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <form id="form" role="form" enctype="multipart/form-data" 
                action="{!! route('admin.bulkadd.timesheet.save') !!}" method="post" class="form-horizontal form-groups-bordered">
                   <div class="box-tools">
                <label class="control-label" style=" float: left;">Date:&nbsp;</label>
            <div class="input-group input-group-sm hidden-xs" style="width: 150px; float: left;">
                  <input type="text"class="form-control datepicker" placeholder="Date" required="" id='timesheetdate'
                 >

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa  fa-calendar"></i></button>
                  </div>
                </div>
                  <label class="control-label" style=" float: left;">Start Time:&nbsp;</label>
            <div class="input-group input-group-sm hidden-xs" style="width: 150px; float: left;">
                  <input type="text"class="form-control datetimepicker" placeholder="Time From" required="" id='timesheetstartime' value="8:00"
                 >
                     <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa  fa-hourglass-1 (alias)"></i></button>
                  </div>
                </div>
                  <label class="control-label" style=" float: left;">End Time:&nbsp;</label>
            <div class="input-group input-group-sm hidden-xs" style="width: 150px; float: left;">
                  <input type="text"class="form-control datetimepicker" placeholder="Time To" required="" id='timesheetendtime' value="17:00"
                 >

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa   fa-hourglass-3 (alias)"></i></button>
                  </div>
                </div>
                  <label class="control-label" style=" float: left;">Activity:&nbsp;</label>
                      <div class="input-group input-group-sm hidden-xs" style="width: 150px; float: left;">
                  {!! Form::select('', $activity, null, ['class' => 'form-control select_box','id'=>'timesheetactivity', 'placeholder' => 'Please Select']) !!}
                </div>
                <div class="input-group input-group-sm hidden-xs" style="width: 150px; float: right;">
                  <input type="text" name="date_submitted" class="form-control datepicker" placeholder="Submited Date" required="" 
                 >

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa  fa-calendar"></i></button>
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
                            <th class="col-sm-2">Time From</th>
                            <th class="col-sm-2">Time To</th>
                            <th class="col-sm-2">Activity</th>
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
                                        <input type="text" name="time_from[]" class="form-control input-sm datetimepicker starttime" required="" placeholder="Start Time" value="8:00">                                 
                                    </td>
                                       <td class="col-sm-2">
                                        <input type="text" name="time_to[]" class="form-control input-sm datetimepicker endtime" required="" placeholder="End Time" value="17:00">                                 
                                    </td>
                                    <td class="col-sm-2">
                                    {!! Form::select('activity_id[]', $activity, null, ['class' => 'form-control select_box timesheetactivity', 'placeholder' => 'Please Select']) !!}
                                    </td>
                                    <td class="col-sm-1">
                                        <a href="javascript::void()" title="remove employee"><i class="remove-this fa fa-trash deletable"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                @if(count($users) > 0)
                  <div class="row" style="margin-top: -10px;margin-left: 0px">
                     <div class="col-md-6">
                        <label for="inputEmail3" class="control-label">
                        Comments
                        </label>
                          <textarea class="form-control" name="comments" id="comments" placeholder="Comments">{!! \Request::old('comments') !!}</textarea>
                        </div>
                  </div><br>
                <button  type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-save (alias)"></i>&nbsp; Save
              </button>
              @endif
              <a href="/admin/timesheet/" type="button" class="btn btn-default btn-lg" >
                <i class="fa fa-close"></i>&nbsp; Cancel
              </a>
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
          allowInputToggle: true
        });
    $('.datetimepicker').datetimepicker({  
      format: 'HH:mm',
      sideBySide: true
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
</script>
@endsection
