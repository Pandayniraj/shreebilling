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
                    <strong>Manage TimeSheet Salary</strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/assign_timesheet_salary" method="post" class="form-horizontal form-groups-bordered">
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
                action="{!! route('admin.assign_timesheet_salary.store') !!}" method="post" class="form-horizontal form-groups-bordered">
                <input type="hidden" name="project_id" value="{{$project_id}}">
                    {{ csrf_field() }}
                    <table id="" class="table table-bordered std_table">
                        <thead>
                        <tr>
                            <th class="col-sm-4">Name</th>
                            <th class="col-sm-4">Designation</th>
                            <th class="col-sm-4">Salary Template</th>
                        </tr>
                        </thead>
                            <tbody>
                                <?php $flag = 0; ?>
                                @foreach($users as $uv)
                                <tr>
                                    <td class="col-sm-4">{{ $uv->first_name.' '.$uv->last_name }}(#{{$uv->id}})</td>
                                    <td class="col-sm-4">{{ $uv->designation->designations }}</td>
                                    <td class="col-sm-4">
                                        <?php $payroll = \PayrollHelper::getTimeSheetSalaryDetails($uv->id); 

                                        ?>
                                        <input type="hidden" name="user_id[]" value="{{ $uv->id }}">
                                        <select name="salary_template_id[]" class="form-control select_box">
                                            <option value="">Select Salary Template</option>
                                            @foreach($salaryTemplates as $stv)
                                            <option value="{{ $stv->id }}" @if($payroll->salary_template_id == $stv->id) selected @endif>{{ $stv->salary_grade }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                    </table>
                    <div class="col-sm-8"></div>
                    <div class="col-sm-4 row mt-lg pull-right" style="margin-top: 10px;">
                        <input type="hidden" name="departments_id" value="{{ $departments_id ?? ''}}">
                        <button id="salery_btn" type="submit" class="btn btn-primary btn-block">Update</button>
                    </div>
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

        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
