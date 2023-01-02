<div class="modal-content">
    <div id="printableArea">
        <style>
        @media print {  .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,  .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 { float: left;                 }
          .col-sm-12 { width: 100%;  }
          .col-sm-11 { width: 91.66666666666666%;  }
          .col-sm-10 { width: 83.33333333333334%;  }
          .col-sm-9 {  width: 75%;  }
          .col-sm-8 {  width: 66.66666666666666%;  }
           .col-sm-7 {  width: 58.333333333333336%;   }
           .col-sm-6 {  width: 50%;   }
           .col-sm-5 {  width: 41.66666666666667%;   }
           .col-sm-4 {  width: 33.33333333333333%;   }
           .col-sm-3 {  width: 25%;   }
           .col-sm-2 {    width: 16.666666666666664%;   }
           .col-sm-1 {    width: 8.333333333333332%;    }
           .thumbnail {
                display: block;
                padding: 4px;
                margin-bottom: 20px;
                line-height: 1.42857143;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                -webkit-transition: border .2s ease-in-out;
                -o-transition: border .2s ease-in-out;
                transition: border .2s ease-in-out;
            }
            .thumbnail a>img, .thumbnail>img {
                margin-right: auto;
                margin-left: auto;
            }
           .form-horizontal .control-label {
                padding-top: 7px;
                margin-bottom: 0;
                text-align: right;
            }
            .panel-title {
                margin-top: 0;
                margin-bottom: 0;
                font-size: 16px;
                color: inherit;
            }
            .panel-custom .panel-heading {
                border-bottom: 2px solid #1797be;
                margin-bottom: 10px;
            }
            .panel-heading {
                padding: 10px 15px;
                border-top-left-radius: 3px;
                border-top-right-radius: 3px;
            }
            .panel {
                margin-bottom: 20px;
                background-color: #fff;
                border: 1px solid transparent;
                border-radius: 4px;
                -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
                box-shadow: 0 1px 1px rgba(0,0,0,.05);
            }
            .panel-info {
                border-color: #bce8f1;
            }
            .panel-info>.panel-heading {
                color: #31708f;
                background-color: #d9edf7;
                border-color: #bce8f1;
            }
        }
        </style>
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Employee Salary Details
                <div class="pull-right ">
                    <?php $salaryTemplate = $salary->template; ?>
                    <button class="btn btn-xs btn-danger" type="button" data-toggle="tooltip" title="Print" onclick="printDiv('printableArea')"><i class="fa fa-print"></i></button>
                </div>
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">

            <div class="show_print" style="width: 100%; border-bottom: 2px solid black;margin-bottom: 30px">
                <table style="width: 100%; vertical-align: middle;">
                    <tbody>
                        <tr>
                            <td style="border: 0px;">
                                <img style="margin-bottom: 5px;" src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}" alt="">
                            </td>

                            <td style="border: 0px;">
                                <p style="margin-left: 100px; font: 14px lighter;">{{ env('APP_NAME') }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> 
            <!-- show when print start-->
            <div class="row">
                <?php $user = $salary->user; ?>
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
                                        <td>{{ $salary->user_id }}</td>
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
                                    <tr>
                                        <td><strong>Date</strong> :</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>{{ $salary->date_format }}</td>
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
                            </div>
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
                            </div>
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
                                <label class="col-sm-6 control-label"><strong>Gross Salary: </strong></label>
                                <p class="form-control-static">{{ env('APP_CURRENCY').' '.($total_salary + $total_allowance) }}</p>
                            </div>
                            <div class="">
                                <label class="col-sm-6 control-label"><strong>Total Deduction: </strong></label>
                                <p class="form-control-static">{{ $total_deduction ? env('APP_CURRENCY').' '.$total_deduction : '' }}</p>
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