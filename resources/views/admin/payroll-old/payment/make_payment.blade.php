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

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Make Payment</strong>

                </div>

            </div>

            <div class="panel-body">
                   <form id="payment_form" role="form" enctype="multipart/form-data" action="/admin/payroll/make_payment" method="post" class="form-horizontal form-groups-bordered">

                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Select Department<span
                                class="required">*</span></label>
                        <div class="col-sm-5">
                            <select required name="departments_id" id="department" class="form-control select_box">
                                <option value="">Select Department</option>
                                @foreach($departments as $dk => $dv)
                                <option value="{{ $dv->departments_id }}" @if(\Request::segment(5) == $dv->departments_id) selected="selected" @endif>{{ $dv->deptname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="payment_month" class="col-sm-3 control-label">Month<span class="required"> *</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input required="" type="text" class="form-control payment_month" value="{{ \Request::segment(6) }}" name="payment_month" id="payment_month">
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

<div id="EmpprintReport">
    <div class="row">
        @if(!sizeof($payment))
        <div class="col-sm-12 std_print">
            <div class="row">
                <form id="payment_form" role="form" enctype="multipart/form-data" action="/admin/payroll/make_payment/submit_new_payment" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="panel panel-custom fees_payment">
                        <!-- Default panel contents -->
                        <div class="panel-heading">
                            <div class="">
                               <small> Payment For <span class="text-danger">{{ date('F Y', strtotime(\Request::segment(6))) }}</span>:</small>
                                <?php
                                    $user_details = \App\User::find(\Request::segment(4));
                                 ?>
                                {{ $user_details->first_name }} {{ $user_details->last_name }}
                                <br>
                            <?php

                                $userAtt = \TaskHelper::getUserAttendance(\Request::segment(4), \Request::segment(6));
                             ?>

                                Attendance P: {{count($userAtt)}}
                                <br>

                            </div>
                        </div>

                        <div class="panel-body">
                            <?php
                                $template = \PayrollHelper::getEmployeePayroll(\Request::segment(4))->template;
                                $gross_salary = $template->basic_salary; //basic salary
                                $gratuity_salary = $template->gratuity_salary;
                                $net_salary = $gross_salary;
                                $total_allowance = 0;
                                $total_deduction = 0;

                                $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id);

                                $deductions = \PayrollHelper::getSalaryDeduction($template->salary_template_id);

                                foreach($allowances as $ak => $av)
                                {
                                    $net_salary += $av->allowance_value;
                                    $total_allowance += $av->allowance_value;
                                }

                                foreach($deductions as $dk => $dv)
                                {
                                    $net_salary -= $dv->deduction_value;
                                    $total_deduction += $dv->deduction_value;
                                }
                            ?>
                            <div class="col-sm-4">
                                <label class="control-label">Basic Salary </label>
                                <input type="text" name="gross_salary" readonly="readonly" value="{{ $gross_salary }}" class="salary form-control" id="gross_salary">
                            </div>
                            <div class="clear"></div>
                            <div class="col-sm-4">
                                <label class="control-label"> Gratuity Salary</label>
                                <input type="text" name="gratuity_salary" id="gratuity_salary" value="{{ $gratuity_salary }}" class="form-control">
                            </div>
                             <div class="clear"></div>
                            <div class="col-sm-4">
                                <label class="control-label">Total Allowance </label>
                                <input type="text" name="total_allowance" id="total_allowance" value="{{ $total_allowance }}" class="form-control">
                            </div>
                             <div class="clear"></div>
                            <div class="col-sm-4">
                                <label class="control-label">Total Deduction </label>
                                <input type="text" name="total_deduction" value="{{ $total_deduction }}" class="form-control" id="total_deduction">
                            </div>
                             <div class="clear"></div>

                            <div class="col-sm-4">
                                <label class="control-label">Net Salary </label>
                                <input type="text" id="net_salary" name="net_salary" readonly="readonly" value="{{ $net_salary }}" class="salary form-control">
                            </div>
                             <div class="clear"></div>
                            <div class="col-sm-4">
                                <label class="control-label">Overtime</label>
                                <input type="text" id="overtime" name="overtime"  value="{{ $overtime }}" class="form-control">
                            </div>
                             <div class="clear"></div>
                            <div class="col-sm-4">
                                <label class="control-label">Fine Deduction </label>
                                <input type="number" data-parsley-type="number" name="fine_deduction" id="fine_deduction" value="" class="form-control">
                            </div>
                             <div class="clear"></div>
                            <div class="col-sm-4">
                                <label class="control-label"><strong>Payment Amount </strong></label>
                                <input type="text" readonly="readonly" value="{{ $net_salary + $overtime }}" class="payment_amount form-control">
                            </div>
                             <div class="clear"></div>
                            <input type="hidden" name="payment_amount" value="{{ $net_salary + $overtime }}" class="payment_amount form-control" id="payment_amount">
                            <!-- Hidden Employee Id -->
                            <input type="hidden" id="user_id" name="user_id" value="{{ \Request::segment(4) }}" class="salary form-control">
                            <input type="hidden" name="payment_month" value="{{ \Request::segment(6) }}" class="salary form-control">
                            <div class="col-sm-4">
                                <!-- Payment Type -->

                        <label class="control-label">Payment Ledger <span class="required"> *</span></label>

                    <select class = 'form-control searchable select2' name='payment_method' >

                    <?php
                    //Sunny_deptors
                    $groups= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id','28')->where('org_id',\Auth::user()->org_id)->get();
                    foreach($groups as $grp)
                    {
                        echo '<option value="'.$grp->id.'"'.
                        (($grp->name==$client->type)?'selected="selected"':"").
                        '>'
                        .$grp->name.'</option>';
                    }
                     ?>


            </select>



                            </div>
                             <div class="clear"></div>
                            <!-- Payment Type -->
                            <div class="col-sm-4">
                                <label class="control-label">Comments </label>
                                <input type="text" name="comments" value="" class=" form-control" data-parsley-id="16">
                            </div>
                             <div class="clear"></div>
                            <br>
                            <div class="col-sm-4 ">
                                    <input type="hidden" name="salary_grade" value="{{ $template->salary_grade }}">
                                    <input type="hidden" name="salary_template_id" value="{{ $template->salary_template_id }}">
                                     <label class="control-label"> </label>
                                     <label class="control-label"> </label>
                                    <button type="submit" name="sbtn" value="1"  class="form-control btn btn-primary btn-block">Pay </button>
                            </div>
                             <div class="clear"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>


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

<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/dataTables.buttons.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/jszip.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/pdfmake.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/vfs_fonts.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/buttons.html5.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/buttons.print.min.js") }}"></script>


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

$("#fine_deduction,#total_allowance,#total_deduction,#overtime,#fine_deduction,#gratuity_salary").on('change', function() {
    total_sal();
});
var gross_salary = Number($('#gross_salary').val());
function total_sal(){
    let allowances = Number($('#total_allowance').val().trim());
    let deductions = Number($('#total_deduction').val().trim());
    let gratuity_salary = Number($('#gratuity_salary').val().trim());
    let _salary = gross_salary + allowances + gratuity_salary - deductions;
    $('#net_salary').val(_salary);
    let overtime = Number($('#overtime').val().trim());
    let fine = Number($('#fine_deduction').val().trim());

    let total_salary =  _salary + overtime - fine;
    $(".payment_amount").val(total_salary);
}
$('#user_id').change(function(){
    let dep_id = $('#department').val();
    let user_id = $(this).val();
    let month = $('#payment_month').val();
    location.href = `/admin/payroll/make_payment/${user_id}/${dep_id}/${month}/`;
});

</script>
@endsection
