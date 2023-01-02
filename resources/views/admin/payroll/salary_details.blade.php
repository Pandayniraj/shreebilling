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

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                    Manage or change Salary Template
                    <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p>Change Salary for staffs. This will affect Salary amount</p>

          {{ TaskHelper::topSubMenu('topsubmenu.payroll')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Manage Salary</strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/payroll/manage_salary_details" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Select Department<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required name="departments_id" id="department" class="form-control select_box">
                                <option value="">Select Department</option>
                                @foreach($departments as $dk => $dv)
                                <option value="{{ $dv->departments_id }}" @if(isset($departments_id) && $departments_id == $dv->departments_id) selected="selected" @endif>{{ $dv->deptname }}</option>
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
                <form id="form" role="form" enctype="multipart/form-data" action="/admin/payroll/store_salary_details" method="post" class="form-horizontal form-groups-bordered">
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
                                    <td class="col-sm-4">{{ $uv->first_name.' '.$uv->last_name }}</td>
                                    <td class="col-sm-4">{{ $uv->designation->designations }}</td>
                                    <td class="col-sm-4">
                                        <?php $payroll = \PayrollHelper::getEmployeePayroll($uv->id); ?>
                                        <input type="hidden" name="user_id[]" value="{{ $uv->id }}">
                                        <select name="salary_template_id[]" class="form-control select_box">
                                            <option value="">Select Salary Template</option>
                                            @foreach($salaryTemplates as $stv)
                                            <option value="{{ $stv->salary_template_id }}" @if($payroll->salary_template_id == $stv->salary_template_id) selected @endif>{{ $stv->salary_grade }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                    </table>
                    <div class="col-sm-8"></div>
                    <div class="col-sm-4 row mt-lg pull-right" style="margin-top: 10px;">
                        <input type="hidden" name="departments_id" value="{{ $departments_id }}">

                        <button id="salery_btn" type="submit" class="btn btn-primary btn-block">Update</button>
                         <a href="/admin/payroll/manage_salary_details" class="btn btn-default btn-block">Cancel</a>
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
