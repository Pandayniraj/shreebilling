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
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Bulk add Employee Shifts</strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="{{ route('admin.shift.bulk.create') }}" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Select Project<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required name="project_id" id="department" class="form-control select_box">
                                <option value="">Select Projects</option>
                                @foreach($projects as $dk => $dv)
                                <option value="{{ $dv->id }}" @if($project_id == $dv->id) selected="selected" @endif>{{ $dv->name }}</option>
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
                <form id="form" role="form" enctype="multipart/form-data" 
                action="{!! route('admin.shift.bulk.store') !!}" method="post" class="form-horizontal form-groups-bordered">
                   <div class="box-tools">
               {{--  <label class="control-label" style=" float: left;">Date:&nbsp;</label>
            <div class="input-group input-group-sm hidden-xs" style="width: 150px; float: left;">
                  <input type="text"class="form-control datepicker" placeholder="Date" required="" id='timesheetdate'
                 >

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa  fa-calendar"></i></button>
                  </div>
                </div> --}}
                  <label class="control-label" style=" float: left;">Shift Name:&nbsp;</label>
                      <div class="input-group input-group-sm hidden-xs" style="width: 150px; float: left;">
                  {!! Form::select('', $shifts, null, ['class' => 'form-control select_box','id'=>'shift_time', 'placeholder' => 'Please Select']) !!}
                 
                </div>
              {{--   <div class="input-group input-group-sm hidden-xs" style="width: 150px; float: right;">
                  <input type="text" name="date_submitted" class="form-control datepicker" placeholder="Submited Date" required="" 
                 >

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa  fa-calendar"></i></button>
                  </div>
                </div> --}}
              </div>
             {{ csrf_field() }}
                    <table id="" class="table table-bordered std_table">
                        <thead>
                        <tr>
                            <th class="col-sm-2">Employee Name</th>
                            <th class="col-sm-2">Designation</th>
                            <th class="col-sm-2">Shift Name</th>
                            <th class="col-sm-2">Map From</th>
                            <th class="col-sm-2">Map To</th>
                        </tr>
                        </thead>
                            <tbody>
                                @foreach($users as $uv)
                                <tr>
                            <td class="col-sm-2">{{ $uv->first_name.' '.$uv->last_name }}(#{{$uv->id}})
                                <input type="hidden" name="user_id[]" value="{{$uv->id}}">
                            </td>
                                    <td class="col-sm-2">{{ $uv->designation->designations }}</td>
                                    <td class="col-sm-2">
                                       {!! Form::select('shift_id[]', $shifts, null, ['class' => 'form-control select_box shift_time', 'placeholder' => 'Please Select','style'=>'width:250px']) !!}                                
                                    </td>
                                       <td class="col-sm-2">
                                        <input type="text" name="map_from_date[]" class="form-control input-sm datepicker" required="" placeholder="Map from Date" value={{ date('Y-m-d') }}>                                 
                                    </td>
                                       <td class="col-sm-2">
                                        <input type="text" name="map_to_date[]" class="form-control input-sm datepicker" required="" placeholder="Map To Date" >                                 
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
              <a href="/admin/shifts/maps" type="button" class="btn btn-default btn-lg" >
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
$('.select_box').select2({ width: 'resolve' });
    $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD', 
          sideBySide: true,
          allowInputToggle: true
        });
});

$(document).on('click', '.remove-this', function () {
    $(this).parent().parent().parent().remove();
});
$('#shift_time').change(function(){
    $('.select_box').each(function(){
        $(this).select2('destroy');
    })
    let val = $(this).val();
    $('.shift_time').val(val);
    $('.select_box').select2();
});
$('#timesheetdate').on('dp.change',function(){
    $('.timesheetdate').val($(this).val());
})
</script>
@endsection
