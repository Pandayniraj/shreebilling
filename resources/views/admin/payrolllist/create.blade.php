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

    input.form-control {
     width: 85px;
        font-size: 12px;
        height: 26px;
    }
    table td{
        padding: 2px!important;
    }
    table{
        font-size: 12px!important;
    }
    /*table>tbody>tr>td{*/

    /*}*/
    .show_print { display: none; }
    .mr, #DataTables_length { margin-right: 10px !important; }
</style>

 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Create Individual Payroll Payment
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p> Select Department and Month </p>

         {{-- {{ TaskHelper::topSubMenu('topsubmenu.payroll')}} --}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Make Payment @if(\Request::get(payment_month)) of {{date('M Y', strtotime(\Request::get(payment_month)))}} @endif</strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/payroll/create_payroll" method="get" class="form-horizontal form-groups-bordered">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <div >
                                <select class="form-control" name="division_id" required="" id="division_id">
                                    @foreach($divisions as $division)
                                        <option value="{{$division->id}}" @if(\Request::get('division_id') == $division->id) selected="selected" @endif>{{$division->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div >
                                <select class="form-control department" name="department_id" id="department">
                                    <option value="">Select Department</option>
{{--                                    @foreach($departments as $department)--}}
{{--                                        <option value="{{$department->departments_id}}" @if(\Request::get('department_id') == $department->departments_id) selected="selected" @endif>{{$department->deptname}}</option>--}}
{{--                                    @endforeach--}}
                                </select>

                            </div>
                        </div>
                        <div class="col-sm-2">
                             <select name="year" class="form-control">
{{--                                  @foreach($fiscalyears as $year)--}}
                                   <option id="{{$fiscalyears->id}}">{{$fiscalyears->numeric_fiscal_year}}</option>
{{--                                  @endforeach--}}
                             </select>
                        </div>
                        <?php
                           $monthsName = ['Baisakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangsir', 'Paush', 'Magh', 'Falgun', 'Chaitra'];
                        ?>
                        <div class="col-sm-2">
                             <select name="month" class="form-control" required>
                                 <option value="">Select Month</option>
                             @foreach($monthsName as $key=>$mnth)
                                   <option value="{{$key+1}}" @if(\Request::get('month') == $key+1) selected="selected" @endif >{{$mnth}}</option>
                                  @endforeach
                             </select>

                        </div>
                        <div class="col-sm-2">
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
         <form method="post" action="{{route('admin.payrolllist.store')}}">
                        <div class="panel panel-custom table-responsive">
                           @csrf
                           <input type="hidden" name="year" value="{{\Request::get('year')}}">
                           <input type="hidden" name="month" value="{{\Request::get('month')}}">
                            <table class="table table-striped DatsaTables  dataTable no-footer dtr-inline" id="DataTables">
                              <thead>
                                 <tr>
                                    <td class="text-bold col-sm-1 bg-black" style="min-width: 50px;">S.No</td>
                                    <td class="text-bold col-sm-1 bg-black" style="min-width: 50px;">EMP ID</td>
                                    <td class="text-bold bg-black" style="min-width: 185px;">Name</td>
                                    <td class="text-bold bg-black" style="min-width: 120px;">Designation</td>
                                    <td class="text-bold bg-black">Grade</td>
                                    <td class="text-bold bg-green">Basic Salary</td>
                                    <td class="text-bold bg-green">Actual Salary</td>
                                     @foreach($allowances as $allowance)
                                    <td class="text-bold bg-green">{{$allowance}}</td>
                                     @endforeach

                                     <td class="text-bold bg-primary"> Total Days</td>
                                     <td class="text-bold bg-primary">Attendance</td>
                                     <td class="text-bold bg-primary">Absent Days</td>
                                    <td class="text-bold bg-primary">Paid Leaves</td>
                                    <td class="text-bold bg-black">Payable Basic Salary</td>
                                     @foreach($allowances as $allowance)
                                         <td class="text-bold bg-green">P. {{$allowance}}</td>
                                     @endforeach
                                     <td class="text-bold bg-orange">Dashain Allowance</td>
                                     <td class="text-bold bg-black">Gross Salary</td>
                                     <td class="text-bold bg-orange">Annual Incentive</td>
                                     <td class="text-bold bg-orange">Annual Leave Salary</td>
                                     <td class="text-bold bg-red">CIT Deduction</td>
                                     <td class="text-bold bg-red">SST</td>
                                     <td class="text-bold bg-red">TDS</td>
                                     <td class="text-bold bg-red">Adv. Deduction</td>
                                     <td class="text-bold bg-red">Loan Deduction</td>
                                    <td class="text-bold bg-black">Monthly Payable</td>
                                     <td class="text-bold bg-black">Errors Adjust(+/-)</td>

                                     <td class="text-bold bg-black">Net Salary</td>
                                    <td class="text-bold bg-black">Remarks</td>
                                 </tr>
                              </thead>
                              <tbody>
                                  <?php
                                    $total_basic_sal = 0;
                                    $total_net_sal = 0;
                                    $total_overtime = 0;
                                    $total_fine = 0;
                                    $total_sal = 0;
                                ?>

                                  @if(count($users)>0)
                                @foreach($users as $sk => $sv)
                                    <?php
                                    $template=$total_actual_salary=$total_taxable_income='';
                                    ?>
                                    @if($sv->employeePayroll->template)
                                 <?php
                                  $template = $sv->employeePayroll->template;

                                 $total_actual_salary=$template->basic_salary+$template->allowance()->sum('allowance_value');

                                 $cit_deduction=$template->cit_deduction;

                                 $total_annual_salary=$total_actual_salary*13;
                                 $annual_gross_salary=$total_annual_salary;
                                 $annual_cit_contribution=$cit_deduction*12;
                                 $annual_insurance_premium=$template->insurance_premium;
                                 $total_taxable_income=$annual_gross_salary-$annual_cit_contribution-$annual_insurance_premium;
                                 ?>
                                 @endif
                                    <?php
                                  $attendance = \App\Helpers\AttendanceHelper::getUserAttendanceHistroy($sv->id,$start_date,$end_date)->count();
//                                  $overtime = \App\Helpers\TaskHelper::overtimedays($sv->id,$start_date,$end_date);

                                    $week_ends_days = \TaskHelper::weekendDays(null,$start_date,$end_date);
                                    $holidays = \TaskHelper::holidaysData(null,$start_date,$end_date);
//                                                                        if ($sv->emp_id==10)
                                    //paid_leave_days justify only register leave on website not-paid......
                                    $paid_leave_days=\App\Helpers\TaskHelper::countOnlyFullDayPaidLeaves($sv->id,$start_date,$end_date);

                                    // $payable_attendance = $attendance+$paid_leave_days+$week_ends_days+$holidays;
                                    // $absent_days = $total_days-$payable_attendance;

                                    //new   //anamol
                                    $register_days=$attendance+$paid_leave_days+$week_ends_days+$holidays;
                                    $unregister_days=$total_days-$register_days;

                                    $total_absent_days=$paid_leave_days+$unregister_days;

                                    $carryforwardleave=\App\Helpers\TaskHelper::usercarryforwardleave($sv->id);

                                    if($total_absent_days<=2){
                                        $paid_leave_days=$total_absent_days;
                                        $unpaid_leave_days=0;
                                        $adjust_leave=0;
                                        if($total_absent_days<2){
                                            $new_carryforwardleave=$carryforwardleave+(2 - $total_absent_days);
                                        }
                                    }else{
                                        $paid_leave_days=2;
                                        $unpaid_leave_days_deduct_monthly=$total_absent_days-$paid_leave_days;
                                        if($carryforwardleave >=0){
                                            if($carryforwardleave > $unpaid_leave_days_deduct_monthly){
                                                $new_carryforwardleave=$carryforwardleave-$unpaid_leave_days_deduct_monthly;
                                                $adjust_leave=$carryforwardleave-$new_carryforwardleave;
                                                $paid_leave_days=$paid_leave_days+$adjust_leave;
                                                $unpaid_leave_days=0;
                                            }elseif($carryforwardleave < $unpaid_leave_days_deduct_monthly){
                                                $unpaid_leave_days=$unpaid_leave_days_deduct_monthly-$carryforwardleave;
                                                $new_carryforwardleave=0;
                                                $paid_leave_days=$paid_leave_days+$carryforwardleave;
                                                $adjust_leave=$carryforwardleave;
                                            }
                                            //carryforwardleave == unpaid_leave_days_deduct_monthly
                                            else{
                                                // dd($sv->id,$carryforwardleave,$unpaid_leave_days_deduct_monthly);
                                                $unpaid_leave_days=0;
                                                $new_carryforwardleave=0;
                                                $adjust_leave=(int)$carryforwardleave;
                                                $paid_leave_days=$paid_leave_days+(int)$carryforwardleave;
                                            }
                                        }
                                        else{
                                            $unpaid_leave_days=$unpaid_leave_days_deduct_monthly+(-$carryforwardleave);
                                            $new_carryforwardleave=0;
                                            $paid_leave_days=$paid_leave_days;
                                            $payable_attendance = $attendance + $paid_leave_days + $week_ends_days + $holidays + $carryforwardleave;
                                            //adjust_leave will contain neagtive value
                                            $adjust_leave=$carryforwardleave;
                                          
                                        }

                                    }
                                    if($carryforwardleave>=0){
                                        $payable_attendance = $attendance+$paid_leave_days+$week_ends_days+$holidays;
                                    }
                                    $absent_days = $unpaid_leave_days;



                                    //newend //anamol
                                    // dd($payable_attendance);
                                 ?>
                                 <tr class="detail-tr">
                                    <td class="col-sm-1 text-center">{{$sk+1}}.
                                    </td>
                                    <td class="col-sm-1 text-center text-primary">#{{ $sv->emp_id}}
                                        <input type="hidden" name = "user_id[]" value="{{ $sv->id }}">
                                        <input type="hidden" name = "departments_id" value="{{ \Request::get('department_id') }}">
                                        <input type="hidden" name = "division_id" value="{{ \Request::get('division_id') }}">
                                        <input type="hidden" class="gender" name = "gender" value="{{$sv->userDetail->gender}}">
                                        <input type="hidden" class="marital_option" name = "gender" value="{{$sv->employeePayroll->marrital_option}}">
                                        <input type="hidden" class="total_taxable_income" name = "total_taxable_income" value="{{$total_taxable_income}}">
                                    </td>
                                    <td>{{ $sv->first_name.' '.$sv->last_name }}</td>
                                    <td>{{ $sv->designation->designations }}</td>
                                    <td  data-toggle="tooltip"><input type="text" title = "{{ $sv->first_name.' '.$sv->last_name }} | Salary Grade" data-toggle="tooltip" name="salary_grade[]" value = "{{$template->salary_grade}}" class="form-control salary_grade" readonly=""></td>

                                     <td class="bg-green"  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Basic Salary" data-toggle="tooltip" name="t_basic[]" value = "{{$template->basic_salary}}" class="form-control t_basic" readonly=""></td>

                                    <td class="bg-green" ><input type="number" name="actual_salary[]" title = "{{ $sv->first_name.' '.$sv->last_name }} | Actual Salary" data-toggle="tooltip" class="form-control actual_salary" value="{{$total_actual_salary}}" readonly></td>

                                     @foreach($allowances as $allowance)
                                         <?php $data=''; ?>
                                        @if($template)
                                        <?php
                                        $data=$template->allowance->where('allowance_label',$allowance)->first();

                                        ?>
                                         @endif
                                     <td  data-toggle="tooltip">
                                         <input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | {{$allowance}}" data-toggle="tooltip" value = "{{$data->allowance_value}}" class="form-control allowances" readonly="">
                                     @endforeach



                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Total Working Days" data-toggle="tooltip" name="total_days[]" value="{{$total_days}}" class="form-control total_days" readonly=""></td>
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Attendance" data-toggle="tooltip" name="attendance[]" class="form-control" value="{{$attendance}}" readonly></td>
                                     <td  data-toggle="tooltip">
                                         <input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} |  Absent Days" data-toggle="tooltip" name="absent[]" class="form-control absent" value="{{$absent_days}}" readonly>
                                         <input type="hidden" title = "{{ $sv->first_name.' '.$sv->last_name }} |  Weekend Days" data-toggle="tooltip" name="weekends[]" class="form-control absent" value="{{$week_ends_days}}" readonly>
                                         <input type="hidden" title = "{{ $sv->first_name.' '.$sv->last_name }} |  Holiday" data-toggle="tooltip" name="holidays[]" class="form-control absent" value="{{$holidays}}" readonly>
                                         <input type="hidden" title = "{{ $sv->first_name.' '.$sv->last_name }} |  Payable Attendance" data-toggle="tooltip" name="payable_days[]" class="form-control absent" value="{{$payable_attendance}}" readonly>
                                         <input type="hidden" title = "{{ $sv->first_name.' '.$sv->last_name }} |  New CarryForwardLeave" data-toggle="tooltip" name="new_carryforwardleave[]" class="form-control absent" value="{{$new_carryforwardleave}}" readonly>
                                         <input type="hidden" title = "{{ $sv->first_name.' '.$sv->last_name }} |  Adjust leave" data-toggle="tooltip" name="adjust_leave[]" class="form-control absent" value="{{$adjust_leave}}" readonly>
                                    </td>

{{--                                    <td title = "{{ $sv->first_name.' '.$sv->last_name }} | Annual leave"><input type="number" name="anual_leave[]" class="form-control" value="{{$annual_leave}}" step=".01"></td>--}}
{{--                                    <td title = "{{ $sv->first_name.' '.$sv->last_name }} | sick leave"><input type="number" name="sick_leave[]" class="form-control" value="{{$sick_leave}}"></td>--}}
{{--                                    <td title = "{{ $sv->first_name.' '.$sv->last_name }} | phl"><input type="number" name="phl[]" class="form-control" value="{{$phl}}"></td>--}}
{{--                                    <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  mol ml pl"><input type="number" name="mol_ml_pl[]" class="form-control" value="{{$mol}}"></td>--}}
{{--                                    <td title = "{{ $sv->first_name.' '.$sv->last_name }} | lwp"><input type="number" name="lwp[]" class="form-control" value="{{$lwp}}"></td>--}}

                                    <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} |  Paid Leave Days" data-toggle="tooltip" name="paid_leaves[]" class="form-control" value="{{$paid_leave_days}}" readonly></td>

{{--                                    <td title = "{{ $sv->first_name.' '.$sv->last_name }} | late half days" data-toggle="tooltip"><input type="number" name="late_half_days[]" class="form-control"></td>--}}
{{--                                    <td ><input type="number" name="payable_attendance[]" title = "{{ $sv->first_name.' '.$sv->last_name }} | Payable Attendance" data-toggle="tooltip" class="form-control payable_attendance" value="{{$payable_attendance}}" readonly></td>--}}



{{--                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | PF Contribution" data-toggle="tooltip" name="pf_contribution[]" value = "{{$pf_contribution}}" class="form-control pf_contribution" ></td>--}}



{{--                                     <td  ><input data-toggle="tooltip" title = "{{ $sv->first_name.' '.$sv->last_name }} | da" type="number" name="da[]" value = "{{$da}}" class="form-control da" readonly=""></td>--}}
                                   {{--                                    <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | OT Hours" data-toggle="tooltip" name="ot_hours[]" class="form-control ot_hours"></td>--}}
{{--                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | OT Amount" data-toggle="tooltip" name="ot_amount[]" class="form-control ot_amount" readonly></td>--}}

                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Working days basic" data-toggle="tooltip" name="payable_basic[]" class="form-control working_days_basic" readonly=""></td>
                                     @foreach($allowances as $allowance)
                                         <?php $data=''; ?>
                                         @if($template)
                                             <?php
                                             $data=$template->allowance->where('allowance_label',$allowance)->first();

                                             ?>
                                         @endif
                                         <td  data-toggle="tooltip">
                                             <input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Payable {{$allowance}}" data-toggle="tooltip" name="{{$sv->id}}_allowance_value[]" value = "{{$data->allowance_value}}" class="form-control payable_allowances" readonly="">
                                             <input type="hidden" name="{{$sv->id}}_allowance_label[]" value = "{{$data->allowance_label}}"></td>
                                     @endforeach
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Dashain Allowance" data-toggle="tooltip" name="dashain_allowance[]" class="form-control dashain_allowance"></td>
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Total after allowance" data-toggle="tooltip" name="total_after_allowance[]" class="form-control total_after_allowance"  readonly=""></td>
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Annual Incentive" data-toggle="tooltip" name="annual_incentive[]" class="form-control annual_incentive"></td>
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Annual Leave Salary" data-toggle="tooltip" name="annual_leave_salary[]" class="form-control annual_leave_salary"></td>
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} |  CIT Deduction" data-toggle="tooltip" name="pf[]" class="form-control pf" value="{{$cit_deduction}}" readonly></td>
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | SST" data-toggle="tooltip" name="sst[]" class="form-control sst" readonly></td>
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | TDS" data-toggle="tooltip" name="tds[]" class="form-control tds" readonly></td>
{{--                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Insurance Premium" data-toggle="tooltip" name="insurance_premium[]" class="form-control insurance_premium"></td>--}}
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Advance Deduction" data-toggle="tooltip" name="advance_deduction[]" class="form-control advance_deduction"></td>
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Loan Deduction" data-toggle="tooltip" name="loan_deduction[]" class="form-control loan_deduction"></td>


                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} |  Monthly payable amount" data-toggle="tooltip" name="monthly_payable_amount[]" class="form-control monthly_payable_amount" readonly=""></td>
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} |  Error Adjustment" data-toggle="tooltip" name="error_adjust[]" class="form-control error_adjust"></td>
                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Net Salary" data-toggle="tooltip" name="net_salary[]" class="form-control net_salary" readonly=""></td>
                                     <td  data-toggle="tooltip"><input type="text" title = "{{ $sv->first_name.' '.$sv->last_name }} | Remarks" data-toggle="tooltip" name="remarks[]" class="form-control"></td>

{{--                                     <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} |  error adjustment" data-toggle="tooltip" name="error_adjust[]" class="form-control error_adjust"></td>--}}
{{--                                    <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Gratuity" data-toggle="tooltip" name="gratuity[]" class="form-control gratuity" readonly=""></td>--}}
{{--                                    <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} |  PF" data-toggle="tooltip" name="pf[]" class="form-control pf" readonly=""></td>--}}
{{--                                    <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | ctc" data-toggle="tooltip" name="ctc[]" class="form-control ctc" readonly=""></td>--}}
{{--                                    <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | adv" data-toggle="tooltip" name="adv[]" class="form-control adv"></td>--}}
{{--                                    <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | pf after ctc" data-toggle="tooltip" name="pf_after_ctc[]" class="form-control pf_after_ctc" readonly=""></td>--}}
{{--                                    <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} |  cit" data-toggle="tooltip" name="cit[]" class="form-control cit"></td>--}}
{{--                                    <td title = "{{ $sv->first_name.' '.$sv->last_name }} |  Uniform deduction" data-toggle="tooltip"><input type="number" name="uniform_deduction[]" class="form-control uniform"></td>--}}
{{--                                    <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} |  Monthly payable amount" data-toggle="tooltip" name="monthly_payable_amount[]" class="form-control monthly_payable_amount" readonly=""></td>--}}
{{--                                    <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | sst" data-toggle="tooltip" name="sst[]" class="form-control sst" step=".01"></td>--}}
{{--                                    <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | net salary" data-toggle="tooltip" name="net_salary[]" class="form-control net_salary" readonly=""></td>--}}
{{--                                    <td  data-toggle="tooltip"><input type="text" title = "{{ $sv->first_name.' '.$sv->last_name }} | remarks" data-toggle="tooltip" name="remarks[]" class="form-control"></td>--}}
                                 </tr>


                                <?php
                                    $total_basic_sal = $total_basic_sal + $template->basic_salary;
                                    $total_net_sal = $total_net_sal + $net_salary;
                                    $total_overtime =  $total_overtime+$overtime_money;

                                ?>
                                 @endforeach
                                  @else
                              <tr>
                                  <td colspan="29">No Employees found for the selected department and month...</td>
                              </tr>
                                      @endif

                              </tbody>
                                 {{--}} <tr>
                                    <td colspan="2"></td>
                                    <td style="float: right">Total</td>
                                    <td>{{ $total_basic_sal }}</td>
                                    <td>{{ $total_net_sal }}</td>
                                    <td>{{ $total_overtime }}</td>
                                    <td>{{ $total_fine }}</td>
                                    <td>{{ $total_sal }}</td>
                                 </tr> --}}
                           </table>
                        </div>
                <input type="submit" value="Run Payroll" class="btn btn-primary">
             <a href="/admin/payroll/list_payroll" class="btn btn-danger">Cancel</a>
        </form>
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

      $(document).ready(function(e){
          $('.detail-tr').each(function(e){
             var t_basic = $(this).find('.t_basic').val();

              var total_days={!! $total_days !!}

                  // var payable_attendance = $(this).find('.payable_attendance').val();

                  var absent_days=$(this).find('.absent').val();
              var absent_basic_deduction = Number(t_basic)/Number(total_days)*Number(absent_days);

              var working_days_basic = Number(t_basic)-Number(absent_basic_deduction);

              $(this).find('.working_days_basic').val(working_days_basic.toFixed(2));

              var allowances=0;
              $(this).find('.allowances').each(function (index){
                  var absent_allowance_deduction = Number($(this).val())/Number(total_days)*Number(absent_days);

                  var final_allowance = Number($(this).val())-Number(absent_allowance_deduction);

                  $(this).parent().parent().find('.payable_allowances').eq(index).val(final_allowance.toFixed(2))
                  allowances+=final_allowance;
              })

              var total_salary = (Number(working_days_basic)+Number(allowances));
              $(this).find('.total_after_allowance').val(total_salary.toFixed(2));

              var total_taxable_income=$(this).find('.total_taxable_income').val()
              var marital_option=$(this).find('.marital_option').val()

              var sst_amount=0;
              var tds_amount=0;

              if (total_taxable_income>0) {

                  var tax_band = {!!json_encode($tax_band)!!};

                  var selected_band = ''

                  // var user_marital_status = $(this).find('.emp_marrital_status').val();

                  tax_band = tax_band.filter(item => Number(marital_option)==Number(item.marital_status));

                  tax_band.every((item, index) => {
                          if (((Number(total_taxable_income) > Number(item.from_amount) && Number(total_taxable_income) <= Number(item.to_amount)) ||
                              (Number(total_taxable_income) > Number(item.from_amount) && item.to_amount == null))
                          ) {
                              selected_band = item
                              return false
                          }
                          return true

                  })


                  tax_band.every((item, index) => {
                          if (index == 0) {
                              if (Number(total_taxable_income) > Number(item.to_amount)) {
                                  sst_amount = (item.tax_percentage / 100) * item.to_amount
                              } else if (Number(total_taxable_income) <= Number(item.to_amount))
                                  sst_amount = (item.tax_percentage / 100) * total_taxable_income


                              if (item.id == selected_band.id)
                                  return false

                              total_taxable_income = total_taxable_income - (item.to_amount - item.from_amount)
                              return true
                          } else if (item.id == selected_band.id && index != 0) {
                              tds_amount += (item.tax_percentage / 100) * total_taxable_income

                              return false;
                          } else if (item.id != selected_band.id && index != 0) {

                              total_taxable_income = total_taxable_income - (item.to_amount - item.from_amount)
                              tds_amount += (item.tax_percentage / 100) * (item.to_amount - item.from_amount)

                              return true
                          }

                          return true
                  })

              }

              var tds_amount_for_female=tds_amount-((10/100)*tds_amount)

              var monthly_sst=Math.round(Number(sst_amount)/12)
              var gender = $(this).find('.gender').val();
              var monthly_tds=gender=='Female'?(Math.round(Number(tds_amount_for_female)/12)):Math.round(Number(tds_amount)/12)
             $(this).find('.sst').val(Number(monthly_sst).toFixed(2));
             $(this).find('.tds').val(Number(monthly_tds).toFixed(2));



              var advance_deduction=$(this).find('.advance_deduction').val()
              var loan_deduction=$(this).find('.loan_deduction').val()
              var pf=$(this).find('.pf').val()

              var all_deductions=Number(monthly_sst)+Number(monthly_tds)+Number(pf)+Number(advance_deduction)+Number(loan_deduction)

              var salary_after_deduction=Number(total_salary)-Number(all_deductions)
              $(this).find('.monthly_payable_amount').val((Number(salary_after_deduction)).toFixed(2));

              var adjustment=$(this).find('.error_adjust').val()
              var salary_after_adjustment=Number(salary_after_deduction)+Number(adjustment);


              $(this).find('.net_salary').val((Number(salary_after_adjustment)).toFixed(2));


          });

      });

      // $.ajax()


    $(function() {

        $('#payment_month').datetimepicker({
            format: 'YYYY-MM',
            sideBySide: true
        });

        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();



    $(document).on('input', '.annual_incentive,.annual_leave_salary,.dashain_allowance,.ot_hours,.tds,.sst,.pf,.advance_deduction,.loan_deduction,.dormitory,.meal,.error_adjust', function() {
        var parentDiv=$(this).parent().parent();

        var t_basic = parentDiv.find('.t_basic').val();

        var dashain_allowance=parentDiv.find('.dashain_allowance').val()

        var total_days={!! $total_days !!}

            // var payable_attendance = parentDiv.find('.payable_attendance').val();

        var absent_days=parentDiv.find('.absent').val();
        var absent_basic_deduction = Number(t_basic)/Number(total_days)*Number(absent_days);

        var working_days_basic = Number(t_basic)-Number(absent_basic_deduction);

        parentDiv.find('.working_days_basic').val(working_days_basic.toFixed(2));


        var allowances=0;
        parentDiv.find('.allowances').each(function (index){
            var absent_allowance_deduction = Number($(this).val())/Number(total_days)*Number(absent_days);

            var final_allowance = Number($(this).val())-Number(absent_allowance_deduction);

            $(this).parent().parent().find('.payable_allowances').eq(index).val(final_allowance.toFixed(2))
            allowances+=final_allowance;
        })

        var total_salary = (Number(working_days_basic)+Number(allowances)+Number(dashain_allowance));

        parentDiv.find('.total_after_allowance').val(total_salary.toFixed(2));

        var annual_incentive=parentDiv.find('.annual_incentive').val()
        var annual_leave_salary=parentDiv.find('.annual_leave_salary').val()


        var total_taxable_income=parentDiv.find('.total_taxable_income').val()

        total_taxable_income=Number(total_taxable_income)+Number(annual_incentive)+Number(annual_leave_salary)

        var marital_option=parentDiv.find('.marital_option').val()

        var sst_amount=0;
        var tds_amount=0;

        if (total_taxable_income>0) {

            var tax_band = {!!json_encode($tax_band)!!};

            var selected_band = ''

            // var user_marital_status = $(this).find('.emp_marrital_status').val();
            tax_band = tax_band.filter(item => Number(marital_option)==Number(item.marital_status));

            tax_band.every((item, index) => {
                    if (((Number(total_taxable_income) > Number(item.from_amount) && Number(total_taxable_income) <= Number(item.to_amount)) ||
                        (Number(total_taxable_income) > Number(item.from_amount) && item.to_amount == null))
                    ) {
                        selected_band = item
                        return false
                    }
                    return true

            })


            tax_band.every((item, index) => {
                    if (index == 0) {
                        if (Number(total_taxable_income) > Number(item.to_amount)) {
                            sst_amount = (item.tax_percentage / 100) * item.to_amount
                        } else if (Number(total_taxable_income) <= Number(item.to_amount))
                            sst_amount = (item.tax_percentage / 100) * total_taxable_income


                        if (item.id == selected_band.id)
                            return false

                        total_taxable_income = total_taxable_income - (item.to_amount - item.from_amount)

                        return true
                    } else if (item.id == selected_band.id && index != 0) {
                        tds_amount += (item.tax_percentage / 100) * total_taxable_income

                        return false;
                    } else if (item.id != selected_band.id && index != 0) {

                        total_taxable_income = total_taxable_income - (item.to_amount - item.from_amount)
                        tds_amount += (item.tax_percentage / 100) * (item.to_amount - item.from_amount)

                        return true
                    }

                    return true

            })

        }
        var tds_amount_for_female=tds_amount-((10/100)*tds_amount)

        var monthly_sst=Math.round(Number(sst_amount)/12)
        var gender = parentDiv.find('.gender').val();
        var monthly_tds=gender=='Female'?(Math.round(Number(tds_amount_for_female)/12)):Math.round(Number(tds_amount)/12)
        parentDiv.find('.sst').val(Number(monthly_sst).toFixed(2));
        parentDiv.find('.tds').val(Number(monthly_tds).toFixed(2));



        var advance_deduction=parentDiv.find('.advance_deduction').val()
        var loan_deduction=parentDiv.find('.loan_deduction').val()
        var pf=parentDiv.find('.pf').val()

        var all_deductions=Number(monthly_sst)+Number(monthly_tds)+Number(pf)+Number(advance_deduction)+Number(loan_deduction)

        var salary_after_deduction=Number(total_salary)-Number(all_deductions)
        parentDiv.find('.monthly_payable_amount').val((Number(salary_after_deduction)).toFixed(2));

        var adjustment=parentDiv.find('.error_adjust').val()
        var salary_after_adjustment=Number(salary_after_deduction)+Number(adjustment);


        parentDiv.find('.net_salary').val((Number(salary_after_adjustment)).toFixed(2));

      });
    });
</script>
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


        // $(document).on('input', '.ot_hours,.payable_attendance,.t_basic,.other_allowance,.da,.additional_attendance_two_hours,.error_adjust,.adv,.cit,.uniform,.sst', function() {
        //     var parentDiv=$(this).parent().parent();
        //     var t_basic=parentDiv.find('.t_basic').val();
        //     // var other_allowance=parentDiv.find('.other_allowance').val();
        //     // var da=parentDiv.find('.da').val();
        //     // var additional_two_hours=parentDiv.find('.additional_attendance_two_hours').val();
        //
        //     // var total = (Number(t_basic)+Number(other_allowance)+Number(da));
        //     var allowances=0;
        //     $('.allowances').each(function (){
        //         allowances += $(this).val()
        //     })
        //     var total = (Number(t_basic)+Number(allowances));
        //     parentDiv.find('.total_after_allowance').val(total.toFixed(2));
        //
        //     var total_salary = (Number(total));
        //     parentDiv.find('.total_salary').val(total_salary.toFixed(2));
        //
        //
        //
        //     var total_days=parentDiv.find('.total_days').val();
        //     var payable_attendance=parentDiv.find('.payable_attendance').val();
        //
        //     var salary_for_the_month = Number(total_salary)/Number(total_days) * Number(payable_attendance);
        //     parentDiv.find('.salary_for_the_month').val(salary_for_the_month.toFixed(2));
        //     var working_days_basic = Number(t_basic)/Number(total_days)*Number(payable_attendance);
        //
        //     parentDiv.find('.working_days_basic').val(working_days_basic.toFixed(2));
        //
        //
        //     var ot_hours = parentDiv.find('.ot_hours').val();
        //     var ot_amount = t_basic/(365*8)*1.5*ot_hours;
        //     parentDiv.find('.ot_amount').val(ot_amount.toFixed(2));
        //
        //
        //     var error_adjust = parentDiv.find('.error_adjust').val();
        //     var gratuity = Number(working_days_basic)*8.33/100;
        //     parentDiv.find('.gratuity').val((Number(gratuity)).toFixed(2));
        //     var pf = Number(t_basic)*10/100;
        //     parentDiv.find('.pf').val(Number(t_basic)*10/100);
        //
        //     var ctc = Number(salary_for_the_month) + Number(ot_amount) + Number(error_adjust) + Number(gratuity)+Number(pf);
        //     parentDiv.find('.ctc').val(ctc.toFixed(2));
        //
        //     var advanced = parentDiv.find('.adv').val();
        //     parentDiv.find('.pf_after_ctc').val((Number(t_basic)*2*10/100).toFixed(2));
        //     var cit = parentDiv.find('.cit').val();
        //     var uniform = parentDiv.find('.uniform').val();
        //
        //     var monthly_payable_amount = Number(ctc)-Number(advanced)-Number(pf*2)-Number(cit);
        //     parentDiv.find('.monthly_payable_amount').val((Number(monthly_payable_amount)).toFixed(2));
        //
        //     var sst = parentDiv.find('.sst').val();
        //     parentDiv.find('.net_salary').val((Number(monthly_payable_amount)-Number(sst)).toFixed(2));
        //
        //
        // });
    });

    $(document).ready(function (){
        var division_id = $('#division_id').val();
        setDepartments(division_id)
    })
    $('#division_id').change(function(){
        var division_id = $(this).val();
        getDepartments(division_id)

    });
    function getDepartments(division_id){
        $.get(`/admin/get-departments/${division_id}`,function(res){
            let dept = res.data;
            var options = '<option value="">Select Department</option>';
            for(let u of dept){
                options = options + `<option value='${u.departments_id}'>${u.deptname}</option>`
            }
            $('#department').html(options);
        }).fail(function(){
            alert("Failed To Load");
        });
    }
    function setDepartments(division_id){
        var dept_id={!! json_encode(Request::get('department_id')) !!}

        $.get(`/admin/get-departments/${division_id}`,function(res){
            let dept = res.data;
            var options = '<option value="">Select Department</option>';
            for(let u of dept){
                options = options + `<option value='${u.departments_id}'>${u.deptname}</option>`
            }
            $('#department').html(options).val(dept_id);
        }).fail(function(){
            alert("Failed To Load");
        });
    }

</script>

@endsection
