
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Salary Template Details
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">

            <div class="show_print" style="width: 100%; border-bottom: 2px solid black;margin-bottom: 30px">
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
            </div>
            <!-- show when print start-->
            <div class="row">
                <div class="col-sm-12 form-horizontal"> 
                    <!-- ********************************* Salary Details Panel ***********************-->
                    <?php
                      $total_salary = 0;
                      $total_allowance = 0;
                      $total_deduction = 0;
                      $total_salary = $salaryTemplate->basic_salary;
                    ?>
                    <div class="panel-body">
                        <div class="">
                            <label for="field-1" class="col-sm-5 control-label"><strong>Salary Grades :</strong></label>
                            <p class="form-control-static">{{ $salaryTemplate->salary_grade }}</p>
                        </div>
                        <div class="">
                            <label for="field-1" class="col-sm-5 control-label"><strong>Basic Salary :</strong>
                            </label>
                            <p class="form-control-static">{{ env('APP_CURRENCY').' '.$salaryTemplate->basic_salary }}</p>
                        </div>
                        <div class="">
                            <label for="field-1" class="col-sm-5 control-label"><strong>Overtime <small>(Per Hour)</small>
                                :</strong> </label>
                            <p class="form-control-static">{{ env('APP_CURRENCY').' '.$salaryTemplate->overtime_salary }}</p>
                        </div>
                    </div>
                </div>
                <!-- ***************** Salary Details  Ends *********************-->

                <!-- ******************- Allowance Panel Start **************************-->
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
                                <p class="form-control-static">{{ env('APP_CURRENCY').' '.$av->allowance_value }}</p>
                            </div>
                            <?php $total_allowance += $av->allowance_value; ?>
                            @endforeach
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
                                <p class="form-control-static">{{ env('APP_CURRENCY').' '.$dv->deduction_value }}</p>
                            </div>
                            <?php $total_deduction += $dv->deduction_value; ?>
                            @endforeach
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
                                <label class="col-sm-6 control-label"><strong>Gross Salary: </strong></label>
                                <p class="form-control-static">{{ env('APP_CURRENCY').' '.($total_salary + $total_allowance) }}</p>
                            </div>
                            <div class="">
                                <label class="col-sm-6 control-label"><strong>Total Deduction: </strong></label>
                                <p class="form-control-static">{{ env('APP_CURRENCY').' '.$total_deduction }}</p>
                            </div>
                            <div class="">
                                <label class="col-sm-6 control-label"><strong>Net Salary : </strong></label>
                                <p class="form-control-static">{{ env('APP_CURRENCY').' '.($total_salary + $total_allowance - $total_deduction) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ****************** Total Salary Details End  *******************-->
            </div>
        </div>
 
