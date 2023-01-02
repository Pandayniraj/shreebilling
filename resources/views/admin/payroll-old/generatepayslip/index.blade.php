@extends('layouts.master')
@section('content')

<style>
    .required { color: red; }
    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
        margin-bottom: 10px;
    }

    .btn-purple, .btn-purple:hover {
        color: #ffffff;
        background-color: #7266ba;
        border-color: transparent;
    }

    .show_print { display: none; }
    .mr, #DataTables_length { margin-right: 10px !important; }
</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Single Generate Payslip
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p> To make a bulk payment please goto <i class="fa fa-play"></i>  Run Payroll </p>

          {{ TaskHelper::topSubMenu('topsubmenu.payroll')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Generate Payslip</strong> 
                </div> 
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/payroll/generate_payslip" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Select Department<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required name="departments_id" id="department" class="form-control select_box">
                                <option value="">Select Department</option>
                                @foreach($departments as $dk => $dv)
                                <option value="{{ $dv->departments_id }}" @if($departments_id == $dv->departments_id) selected="selected" @endif>{{ $dv->deptname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="payment_month" class="col-sm-3 control-label">Month<span class="required"> *</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input required="" type="text" class="form-control payment_month" value="{{ \Request::get('payment_month') ? \Request::get('payment_month') : '' }}" name="payment_month" id="payment_month">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5 ">
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

                <table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="DataTables">
                  <thead>
                     <tr>
                        <td class="text-bold col-sm-1">EMP ID</td>
                        <td class="text-bold">Name</td>
                        <td class="text-bold">Salary Type</td>
                        <td class="text-bold">Basic Salary</td>
                        <td class="text-bold col-sm-1">Net Salary</td>
                        <td class="text-bold">Details</td>
                        <td class="text-bold">Status</td>
                        <td class="text-bold col-sm-2">Action</td>
                     </tr>
                  </thead>
                  <tbody>
                    @foreach($users as $sk => $sv)
                     <tr>
                        <td class="col-sm-1">{{ $sv->user_id }}</td>
                        <td>{{ $sv->first_name.' '.$sv->last_name }}</td>
                        <?php
                            $salary = \App\Models\SalaryPayment::where('user_id', $sv->user_id)->where('payment_month', \Request::get(payment_month))->first();
                        ?>
                        @if(sizeof($salary))
                        <td>{{ $salary->salary_grade }}</td>
                        <td>{{ $salary->gross_salary }}</td>
                        <td>{{ $salary->gross_salary + $salary->total_allowance - $salary->total_deduction - $salary->fine_deduction }}</td>
                        @else
                        <?php $template = \PayrollHelper::getEmployeePayroll($sv->user_id)->template; ?>
                        <td>{{ $template->salary_grade }}</td>
                        <td>{{ $template->basic_salary }}</td>
                        <?php
                            $net_salary = $template->basic_salary;
                            $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id);

                            $deductions = \PayrollHelper::getSalaryDeduction($template->salary_template_id);

                            foreach($allowances as $ak => $av)
                                $net_salary += $av->allowance_value;

                            foreach($deductions as $dk => $dv)
                                $net_salary -= $dv->deduction_value;
                        ?>
                        <td>{{ $net_salary }}</td>
                        @endif
                        <td class="col-sm-1">
                            <a href="/admin/payroll/show_payment_detail/{{ $sv->user_id }}/{{ \Request::get(payment_month) }}" class="btn btn-info btn-xs" title="" data-toggle="modal" data-target="#payment_show" data-placement="top" data-original-title="Show"><i class="fa fa-list"></i></a>
                        </td>
                        @if(sizeof($salary))
                        <td><span class="label label-success">Paid</span></td>
                        @else
                        <td><span class="label label-danger">Unpaid</span></td>
                        @endif
                        <td style="text-align:right;">
                           <a href="/admin/payroll/make_payment/{{ $sv->user_id }}/{{ $departments_id }}/{{ \Request::get(payment_month) }}" class="btn btn-primary btn-xs">@if(sizeof($salary)) View @else Make @endif Payment</a>
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>

            </div>
        </div>
    </div>
</div>
@endif


<div class="modal fade" id="payment_show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">

        </div>
    </div>
</div>
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')

<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>



<script type="text/javascript">
    $(function() {

        $('#payment_month').datetimepicker({
            format: 'YYYY-MM',
            sideBySide: true
        });

        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();

       
    });
</script>
@endsection
