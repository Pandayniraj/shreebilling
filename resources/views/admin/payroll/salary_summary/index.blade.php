@extends('layouts.master')
@section('content')
@php 
    $type = isset($type) ? $type : null;
@endphp
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
                Single Payroll Summary
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p>This is a summary for single payments, for the summary of bulk payment <a href="/admin/payroll/payfrequency"> click here</a> and goto action </p>

          {{ TaskHelper::topSubMenu('topsubmenu.payroll')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Salary Summary @if(isset($employee_name)) of {{$employee_name->first_name}} {{$employee_name->last_name}} @elseif(isset($payment_month)) of {{date('M Y', strtotime($payment_month))}} @endif</strong> 
                </div> 
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="{{route('admin.payroll.salary_summary_view')}}" method="get" class="form-horizontal form-groups-bordered">
                    <div class="form-group">
                        <label for="department" class="col-sm-3 control-label">Search type<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required name="type" id="searchtype" class="form-control select_box">
                                <option value="">Select search type</option>
                               <option value="emp" @if($type && $type == 'emp') selected @endif>Employee</option>
                               <option value="month"  @if($type && $type == 'month') selected @endif>Month</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group filteroptions">
                            @if($type == 'emp')
                            <div class="form-group">
                                <label for="department" class="col-sm-3 control-label">Select Employee<span
                                        class="required">*</span></label>

                                <div class="col-sm-5">
                                    <select required name="employee_id" id="searchtype" class="form-control select_box">
                                        <option value="">Select employee</option>
                                        @foreach($employee as $key => $value)
                                        <optgroup label="{{$key}}">
                                            @foreach($value as $val)
                                            <option value="{{$val->id}}" @if($emp_id == $val->id) selected @endif>{{ucfirst($val->username)}}({{$val->id}})</option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                            @endif 
                            @if($type == 'month')
                                <label for="payment_month" class="col-sm-3 control-label">Month<span class="required"> *</span></label>
                                <div class="col-sm-5">
                                <div class="input-group">
                                <input required="" type="text" class="form-control payment_month1" value="{{$payment_month}}" name="payment_month" >
                                <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                                </div>
                                </div>
                            @endif
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
<div class="selectoptions" style="display: none">
    <div id="bymonths">
    <label for="payment_month" class="col-sm-3 control-label">Month<span class="required"> *</span></label>
        <div class="col-sm-5">
                <div class="input-group">
                    <input required="" type="text" class="form-control payment_month1" value="" name="payment_month" >
                    <div class="input-group-addon">
                    <a href="#"><i class="fa fa-calendar"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div id="byemployee">
        <div class="form-group">
            <label for="department" class="col-sm-3 control-label">Select Employee<span
                    class="required">*</span></label>

            <div class="col-sm-5">
                <select required name="employee_id" id="searchtype" class="form-control select_box">
                    <option value="">Select employee</option>
                    @foreach($employee as $key => $value)
                    <optgroup label="{{$key}}">
                        @foreach($value as $val)
                        <option value="{{$val->id}}">{{ucfirst($val->username)}}({{$val->id}})</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
            </div>
    </div>
</div>
@if(isset($users))

<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
           <div class="panel-heading">
            <div class="panel-title">
                  <strong>Salary Summary @if($employee_name) of {{$employee_name->first_name}} {{$employee_name->last_name}} @elseif($payment_month) of {{date('M Y', strtotime($payment_month))}} @endif</strong> 
                <div class="pull-right"><!-- set pdf,Excel start action -->
                    @if($type == "emp")
                    <label class="hidden-print control-label pull-left hidden-xs">
                        <a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" type="button"  data-original-title="Print"  href="/admin/payroll/salary_summary/{{\Request::get('employee_id')}}/{{\Request::get('type')}}/print" target="_blank"><i class="fa fa-print"></i>
                        </a>
                        <span>  <a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" type="button"  data-original-title="Pdf"  href="/admin/payroll/salary_summary/{{\Request::get('employee_id')}}/{{\Request::get('type')}}/pdf" target="_blank"><i class="fa fa-file-o"></i>
                        </a></span>
                          <span>  <a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" type="button"  data-original-title="Pdf"  href="/admin/payroll/salary_summary/{{\Request::get('employee_id')}}/{{\Request::get('type')}}/excel" ><i class="fa fa-file-excel-o"></i>
                        </a></span>
                    </label>
                    @else
                    <label class="hidden-print control-label pull-left hidden-xs">
                        <a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" type="button"  data-original-title="Print"  href="/admin/payroll/salary_summary/{{\Request::get('payment_month')}}/{{\Request::get('type')}}/print" target="_blank"><i class="fa fa-print"></i>
                        </a>
                        <span><a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" type="button"  data-original-title="Pdf"  href="/admin/payroll/salary_summary/{{\Request::get('payment_month')}}/{{\Request::get('type')}}/pdf" target="_blank"><i class="fa fa-file-o"></i>
                        </a></span>
                        <span>  <a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" type="button"  data-original-title="Pdf"  href="/admin/payroll/salary_summary/{{\Request::get('payment_month')}}/{{\Request::get('type')}}/excel" ><i class="fa-file-excel-o"></i>
                        </a></span>
                    </label>
                    @endif
                </div><!-- set pdf,Excel start action -->
            </div>
        </div>
                <table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="DataTables">
                   
                  <thead>
                     <tr>
                        <td class="text-bold col-sm-1">EMP ID</td>
                        <td class="text-bold">Name</td>
                        <td class="text-bold">Salary Type</td>
                        <td class="text-bold">Basic Salary</td>
                        <td class="text-bold col-sm-1">Net Salary</td>
                        <td class="text-bold">OverTime</td>
                        <td class="text-bold">Fine</td>
                        <td class="text-bold">Total</td>
                        <td class="text-bold no-print">Details</td>
                        <td class="text-bold"> @if($type == 'emp') Month @else Status @endif</td>
                     </tr>
                  </thead>
                    <?php
                        $total_basic_sal = 0;
                        $total_net_sal = 0;
                        $total_overtime = 0;  
                        $total_fine = 0;
                        $total_sal = 0;
                    ?>
                @if($type == 'month')
                  <tbody>
                    @foreach($users as $sk => $sv)

                     <tr>
                        <td class="col-sm-1">{{ $sv->user_id }}</td>
                        <td>{{ $sv->first_name.' '.$sv->last_name }}</td>
                        <?php

                            $salary = \App\Models\SalaryPayment::where('user_id', $sv->user_id)->where('payment_month', \Request::get(payment_month))->first();
               
                        ?>

                        @if(sizeof($salary))
                        <td title="{{$salary->salary_grade}}">{{ substr($salary->salary_grade,0,10) }}</td>
                        <td>{{ $salary->gross_salary }}</td>
                        <?php $net_sal =$salary->gross_salary + $salary->total_allowance - $salary->total_deduction;
                              
                          ?>
                        <td>{{ $net_sal  }}</td>
                        <td>{{ $salary->overtime }}</td>
                        <td>{{ $salary->fine_deduction }} </td>
                        <td>{{ $net_sal +  $salary->overtime - $salary->fine_deduction}} </td>
                        <?php 
                          $total_basic_sal = $total_net_sal + $salary->gross_salary;
                          $total_net_sal = $total_net_sal + $net_sal;
                          $total_overtime = $total_overtime + $salary->overtime;
                          $total_fine = $total_fine + $salary->fine_deduction;
                          $total_sal = $total_sal + ($net_sal +  $salary->overtime - $salary->fine_deduction);
                        ?>
                        @else
                        <?php $template = \PayrollHelper::getEmployeePayroll($sv->user_id)->template; ?>
                        <td title="{{ $template->salary_grade }}">{{ substr($template->salary_grade ,0,10) }}</td>
                        <td>{{ $template->basic_salary }}</td>
                        <?php
                            $net_salary = $template->basic_salary;
                            $overtime_money = \TaskHelper::overtimesal($sv->user_id,$date_start, $date_end);
                            $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id);

                            $deductions = \PayrollHelper::getSalaryDeduction($template->salary_template_id);

                            foreach($allowances as $ak => $av)
                                $net_salary += $av->allowance_value;

                            foreach($deductions as $dk => $dv)
                                $net_salary -= $dv->deduction_value;
                            $total_sal = $total_sal + $net_salary;
                        ?>
                        <td>{{ $net_salary }}</td>
                        <td>{{ $overtime_money }}</td>
                        <td>0</td>
                        <td>{{ $net_salary + $overtime_money - 0 }} </td>
                            <?php
                                $total_basic_sal = $total_basic_sal + $template->basic_salary;
                                $total_net_sal = $total_net_sal + $net_salary;
                                $total_overtime =  $total_overtime+$overtime_money;
                                $total_fine = $total_fine + 0;
                                $total_sal = $total_sal +  ($net_salary + $overtime_money - 0);
                            ?>
                        @endif

                        <td class="col-sm-1 no-print">
                            <a href="/admin/payroll/show_payment_detail/{{ $sv->user_id }}/{{ \Request::get(payment_month) }}" class="btn btn-info btn-xs" title="" data-toggle="modal" data-target="#modal_dialog" ><i class="fa fa-list"></i></a>
                        </td>
                        @if(sizeof($salary))
                        <td><span class="label label-success">Paid</span></td>
                        @else
                        <td><span class="label label-danger">Unpaid</span></td>
                        @endif
                      
                     </tr>
                   

                     @endforeach
                  </tbody>
                  @endif

                  @if($type == 'emp')
                  <tbody>
                    
                        <?php
                            $salaries = \App\Models\SalaryPayment::where('user_id', $emp_id)->get();
                        ?>

                        @foreach($salaries as $salary)
                    <tr>
                        <td class="col-sm-1">{{ $emp_id }}</td>
                        <td>{{ $salary->user->first_name.' '.$salary->user->last_name }}</td>
                        <td title="{{$salary->salary_grade}}">{{ substr($salary->salary_grade,0,10) }}</td>
                        <td>{{ $salary->gross_salary }}</td>
                        <?php $net_sal = $salary->gross_salary + $salary->total_allowance - $salary->total_deduction;
                          $total_sal = $net_sal +  $salary->overtime - $salary->fine_deduction;
                          ?>
                        <td>{{ $net_sal  }}</td>
                        <td>{{ $salary->overtime }}</td>
                        <td> {{$salary->fine_deduction}} </td>
                        <td>{{ $net_sal +  $salary->overtime - $salary->fine_deduction}} </td>
                  
                     <?php 
                          $total_basic_sal = $total_net_sal + $salary->gross_salary;
                          $total_net_sal = $total_net_sal + $net_sal;
                          $total_overtime = $total_overtime + $salary->overtime;
                          $total_fine = $total_fine + $salary->fine_deduction;
                          $total_sal = $total_sal + ($net_sal +  $salary->overtime - $salary->fine_deduction);
                        ?>
                          <td class="col-sm-1">
                            <a href="/admin/payroll/show_payment_detail/{{ $emp_id }}/{{ $salary->payment_month }}" class="btn btn-info btn-xs" title="" data-toggle="modal" data-target="#modal_dialog" ><i class="fa fa-list"></i></a>
                        </td>
                        <td>{{ date('M Y', strtotime($salary->payment_month))}}</td>
    
                          </tr>
                      @endforeach

                  </tbody>
                  @endif
                   <tr>
                        <td colspan="2"></td>
                        <td style="float: right">Total</td>
                        <td>{{ $total_basic_sal }}</td>
                        <td>{{ $total_net_sal }}</td>
                        <td>{{ $total_overtime }}</td>
                        <td>{{ $total_fine }}</td>
                        <td>{{ $total_sal }}</td>
                     </tr>
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




 <!-- implemented with an html comment, 
    //OPTIMIZE: Nothing
    //TODO: make selec2 for employeee 
  -->
<script type="text/javascript">
    $(function() {

       $('#payment_month1').datetimepicker({
            format: 'YYYY-MM',
            sideBySide: true
        });



        $('[data-toggle="tooltip"]').tooltip();

 var isselect2 = true;
    $('#searchtype').change(function(){
        let type = $(this).val();
        if(type == 'month'){
            let html = $('.selectoptions #bymonths').html();
            $('.filteroptions').html(html);
            $('.payment_month1').datetimepicker({
                format: 'YYYY-MM',
                sideBySide: true
            });
        }
        else if(type == 'emp'){
         
            let html = $('.selectoptions #byemployee').html();
            $('.filteroptions').html(html);
            

        }
        else{
            $('.filteroptions').html('');
        }
       
    });
    });
    function printData()
{
   var divToPrint=document.getElementById("DataTables");
   newWin= window.open("");
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
}
</script>
@endsection
