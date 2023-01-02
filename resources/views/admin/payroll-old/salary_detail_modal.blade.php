<div class="modal-content">
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Employee Salary Details
                <div class="pull-right ">
                    <?php $salaryTemplate = $payroll->template; ?>
                    <span>
                        <a href="/admin/payroll/generatePdfSalaryDetail/{{ $payroll->payroll_id }}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="PDF"><span <i="" class="fa fa-file-pdf"></span></a>
                    </span>
                    <button class="btn btn-xs btn-danger" type="button" data-toggle="tooltip" title="Print" onclick="printDiv('printableArea')"><i class="fa fa-print"></i></button>
                </div>
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">

            <!-- <div class="show_print" style="width: 100%; border-bottom: 2px solid black;margin-bottom: 30px">
                <table style="width: 100%; vertical-align: middle;">
                    <tbody>
                        <tr>
                            <td style="width: 50px; border: 0px;">
                                <img style="width: 100%;height: 50px;margin-bottom: 5px;" src="{{ env(APP_URL).'/'.env('APP_LOGO1') }}" alt="" class="img-circle">
                            </td>

                            <td style="border: 0px;">
                                <p style="margin-left: 10px; font: 14px lighter;">{{ env('APP_NAME') }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> -->
            <!-- show when print start-->
            <div class="row">
                <?php $user = $payroll->user; ?>
                @if($user->image)
                <div class="col-lg-2 col-sm-2">
                    <div class="fileinput-new thumbnail" style="width: 144px; height: 158px; margin-top: 14px; margin-left: 16px; background-color: #EBEBEB;">
                        <img src="{{ env('APP_URL') }}/images/profiles/{!! $user->image !!}" style="width: 142px; height: 148px; border-radius: 3px;">
                    </div>
                </div>
                
                <div class="col-lg-1 col-sm-1">
                    &nbsp;
                </div>
                @endif
                <div class="col-lg-8 col-sm-8 ">
                    <div>
                        <div style="margin-left: 20px;">
                            <h3>{{ $user->first_name.' '.$user->last_name }}</h3>
                            <hr class="mt0">
                            <table class="table-hover">
                                <tbody>
                                    <tr>
                                        <td><strong>EMP ID</strong> :</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>{{ $payroll->user_id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Departments</strong> :</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>{{ $user->department->deptname }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Designation</strong> :</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>{{ $user->designation->designations }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-sm-12 form-horizontal panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <strong>Salary Details</strong>
                        </div>
                    </div>
                    <!-- ********************************* Salary Details Panel ***********************-->
                    <?php
                      $total_salary = 0;
                      $total_allowance = 0;
                      $total_deduction = 0;
                      $total_salary = $salaryTemplate->basic_salary;
                      $gratuity_salary = $salaryTemplate->gratuity_salary;
                    ?>
                    <div class="panel-body">
                        <div class="">
                            <label for="field-1" class="col-sm-5 control-label"><strong>Salary Grades :</strong></label>
                            <p class="form-control-static">{{ $salaryTemplate->salary_grade }}</p>
                        </div>
                        <div class="">
                            <label for="field-1" class="col-sm-5 control-label"><strong>Basic Salary :</strong>
                            </label>
                            <p class="form-control-static">{{ $salaryTemplate->basic_salary ? env('APP_CURRENCY').' '.$salaryTemplate->basic_salary : '' }}</p>
                        </div>
                        <div class="">
                            <label for="field-1" class="col-sm-5 control-label"><strong>Overtime <small>(Per Hour)</small>
                                :</strong> </label>
                            <p class="form-control-static">{{ $salaryTemplate->overtime_salary ? env('APP_CURRENCY').' '.$salaryTemplate->overtime_salary : '' }}</p>
                        </div>
                    </div>
                </div>
                <!-- ***************** Salary Details  Ends *********************-->

                <!-- ******************-- Allowance Panel Start **************************-->
                <div class="col-sm-6 form-horizontal">
                    <div class="panel panel-custom">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <strong>Allowances</strong>
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php $allowances = \PayrollHelper::getSalaryAllowance($salaryTemplate->salary_template_id); ?>
                            @foreach($allowances as $ak => $av)
                            <div class="">
                                <label class="col-sm-6 control-label"><strong>{{ $av->allowance_label }} : </strong></label>
                                <p class="form-control-static">{{ $av->allowance_value ? env('APP_CURRENCY').' '.$av->allowance_value : '' }}</p>
                            </div><br>
                            <?php $total_allowance += $av->allowance_value; ?>
                            @endforeach
                            @if(!sizeof($allowances))
                            <h3> Nothing to display here!</h3>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- ********************Allowance End ******************-->

                <!-- ************** Deduction Panel Column  **************-->
                <div class="col-sm-6 form-horizontal">
                    <div class="panel panel-custom">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <strong>Deductions</strong>
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php $deductions = \PayrollHelper::getSalaryDeduction($salaryTemplate->salary_template_id); ?>
                            @foreach($deductions as $dk => $dv)
                            <div class="">
                                <label class="col-sm-6 control-label"><strong>{{ $dv->deduction_label }} : </strong></label>
                                <p class="form-control-static">{{ $dv->deduction_value ? env('APP_CURRENCY').' '.$dv->deduction_value : '' }}</p>
                            </div><br>
                            <?php $total_deduction += $dv->deduction_value; ?>
                            @endforeach
                            @if(!sizeof($deductions))
                            <h3> Nothing to display here!</h3>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- ****************** Deduction End  *******************-->
            </div>
            <div class="row">
                <!-- ************** Total Salary Details Start  **************-->
                <div class="form-horizontal col-sm-8 pull-right">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <strong>Total Salary Details</strong>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="">
                                <label class="col-sm-6 control-label"><strong>Total Allowance: </strong></label>
                                <p class="form-control-static">{{ env('APP_CURRENCY').' '.($total_allowance) }}</p>
                            </div>
                            <div class="">
                                <label class="col-sm-6 control-label"><strong>
                                Gratuity Salary: </strong></label>
                                <p class="form-control-static">{{ env('APP_CURRENCY').' '.($gratuity_salary) }}</p>
                            </div>
                            <div class="">
                                <label class="col-sm-6 control-label"><strong>Gross Salary: </strong></label>
                                <p class="form-control-static">{{ env('APP_CURRENCY').' '.($total_salary + $total_allowance + $gratuity_salary) }}</p>
                            </div>
                            <div class="">
                                <label class="col-sm-6 control-label"><strong>Total Deduction: </strong></label>
                                <p class="form-control-static">{{ $total_deduction ? env('APP_CURRENCY').' '.$total_deduction : '' }}</p>
                            </div>
                            <div class="">
                                <label class="col-sm-6 control-label"><strong>Net Salary : </strong></label>
                                <p class="form-control-static">{{ env('APP_CURRENCY').' '.($total_salary + $total_allowance - $total_deduction +$gratuity_salary) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ****************** Total Salary Details End  *******************-->
            </div>
        </div>
    </div>
    <div class="modal-footer hidden-print">
        <div class="row">
            <div class="col-sm-12">
                <div class="pull-right col-sm-8">
                    <div class="col-sm-3 pull-right" style="margin-right: -31px;">
                        <button type="button" class="btn col-sm-12 pull-right btn-default btn-block" id="close_modal" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function printDiv(printableArea) {
        var printContents = document.getElementById(printableArea).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>