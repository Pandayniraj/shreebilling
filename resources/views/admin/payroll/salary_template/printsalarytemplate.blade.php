 <!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
  <head>
    <meta charset="UTF-8">
    <title>{{ env('APP_COMPANY')}} | Salary Template</title>

    <!-- block from searh engines -->
    <meta name="robots" content="noindex">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Set a meta reference to the CSRF token for use in AJAX request -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons 4.7.0 -->
    <link href="{{ asset("/bower_components/admin-lte/font-awesome/css/all.css") }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.1 -->
    <link href="{{ asset("/bower_components/admin-lte/ionicons/css/ionicons.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css" />

    <!-- Application CSS-->
    <link href="{{ asset(elixir('css/all.css')) }}" rel="stylesheet" type="text/css" />

    <style>
    .borderless td, .borderless th {
    border: none !important;
}

</style>


  </head>
  <body onload="window.print();" cz-shortcut-listen="true" class="skin-blue sidebar-mini">
    <div class='wrapper'>

      <section class="invoice">

           <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
               <div class="col-xs-3">
              <img src="/images/logo-mini.png" style="max-width: 200px;">
                </div>
                <div class="col-xs-9">
              <span class="pull-right">
                <span>Salary Template</span>
              </span>
            </div>
             <hr>
            </h2>
           
          </div>
          <!-- /.col -->
        </div>
            <!-- show when print start-->
            <div class="row">
             <div class="col-sm-12">
                    <!-- ********************************* Salary Details Panel ***********************-->
                    <?php
                      $total_salary = 0;
                      $total_allowance = 0;
                      $total_deduction = 0;
                      $total_salary = $salaryTemplate->basic_salary;
                    ?>
                    
                        <table class="table">
                          <tbody>
                            <tr>
                              <th scope="row">Salary Grades :</th>
                              <td>{{ $salaryTemplate->salary_grade }}</td>
                            </tr>
                            <tr>
                              <th scope="row">Basic Salary :</th>
                              <td>{{ env('APP_CURRENCY').' '.$salaryTemplate->basic_salary }}</td>
                            </tr>
                            <tr>
                              <th scope="row"><strong>Overtime <small>(Per Hour)</small></th>
                              <td>{{ env('APP_CURRENCY').' '.$salaryTemplate->overtime_salary }}</td>
                            </tr>
                          </tbody>
                        </table>
                
                </div>
            </div>
                <!-- ***************** Salary Details  Ends *********************-->

                <!-- ******************-- Allowance Panel Start **************************-->
                <div class="row">
                    <div class="form-horizontal col-sm-6 pull-left">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <strong>Allowances</strong>
                                </div>
                             </div>
                             <?php $allowances = \PayrollHelper::getSalaryAllowance($salaryTemplate->salary_template_id); ?>
                             <div class="panel-body">
                               <table class="table borderless">
                                  <tbody>
                                     @foreach($allowances as $ak => $av)
                                    <tr>
                                      <th scope="row">{ $av->allowance_label }} : </th>
                                      <td>{{ env('APP_CURRENCY').' '.$av->allowance_value }}</td>
                                    </tr>
                                      <?php $total_allowance += $av->allowance_value; ?>
                                     @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </div>
                     </div>
                    <!-- ********************Allowance End ******************-->

                    <!-- ************** Deduction Panel Column  **************-->
                    <div class="form-horizontal col-sm-6 pull-right">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <strong>Deductions</strong>
                                </div>
                            </div>
                            <div class="panel-body">
                                <?php $deductions = \PayrollHelper::getSalaryDeduction($salaryTemplate->salary_template_id); ?>
                               
                            <table class="table borderless">
                                <tbody>
                                 @foreach($deductions as $dk => $dv)
                                
                                <tr>
                                  <th scope="row">{{ $dv->deduction_label }} :</th>
                                  <td>{{ env('APP_CURRENCY').' '.$dv->deduction_value }}</td>
                                </tr>
                                <?php $total_deduction += $dv->deduction_value; ?>
                                @endforeach
                            </tbody>
                        </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ****************** Deduction End  *******************-->
        
            <div class="row">
                <!-- ************** Total Salary Details Start  **************-->
                <div class="form-horizontal col-sm-6 pull-right">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <strong>Total Salary Details</strong>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table borderless">
                               <tbody>
                                <tr>
                                    <th>Gross Salary: </th>
                                    <td>{{ env('APP_CURRENCY').' '.($total_salary + $total_allowance) }}</td>    
                                </tr>
                                <tr>
                                    <th>Total Deduction: </th>
                                    <td>{{ env('APP_CURRENCY').' '.$total_deduction }}</td>    
                                </tr>
                                <tr>
                                    <th>Net Salary : </th>
                                    <td>{{ env('APP_CURRENCY').' '.($total_salary + $total_allowance - $total_deduction) }}</td>    
                                </tr>
                                
                               </tbody> 
                            </table>
                        </div>
                    </div>
                </div>
                <!-- ****************** Total Salary Details End  *******************-->
            </div>
        </section>
        </div>
</body>
</html>

       

