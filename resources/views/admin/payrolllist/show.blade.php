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
                {{$page_title}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>

         {{-- {{ TaskHelper::topSubMenu('topsubmenu.payroll')}} --}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
    <?php
    $chosen_date = explode('-', $payroll->date);
    $year = $chosen_date[0];
    $month = $chosen_date[1];
    $monthsName = ['Baisakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangsir', 'Paush', 'Magh', 'Falgun', 'Chaitra'];

    ?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>View Payroll @if(\Request::get(payment_month)) of {{date('M Y', strtotime(\Request::get(payment_month)))}} @endif</strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/payroll/create_payroll" method="get" class="form-horizontal form-groups-bordered">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="">Division</label>
                            <input type="text" style="width: 100%;" name="division_id" class="form-control" value="{{$payroll->division->name}}" readonly="">
                        </div>
                        <div class="col-sm-3">
                            <label for="">Department</label>
                            <input type="text" style="width: 100%;" name="department_id" class="form-control" value="{{$payroll->department->deptname}}" readonly="">
                        </div>
                        <div class="col-sm-2">
                            <label for="">Year</label>
                            <input type="text" name="year" style="width: 100%;" class="form-control" value="{{$year}}" readonly="">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Month</label>
                            <input type="text" name="month" style="width: 100%;" class="form-control" value="{{$monthsName[$month-1]}}" readonly="">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
                        <div class="panel panel-custom table-responsive">
                           @csrf
                           <input type="hidden" name="date" value="{{\Request::get('payment_month')}}">
                            <table class="table table-triped DatsaTables  dataTable no-footer dtr-inline" id="DataTables">
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
                                      <td  class="text-bold bg-green">
                                          {{$allowance}}
                                      </td>
                                  @endforeach
                                  <td class="text-bold bg-primary"> Total Days</td>
                                  <td class="text-bold bg-primary">Attendance</td>
                                  <td class="text-bold bg-primary">Absent Days</td>
                                  <td class="text-bold bg-primary">Paid Leaves</td>

                                  {{--                                    <td class="text-bold">Payable Attendance</td>--}}



{{--                                  <td class="text-bold bg-orange">PF Contribution</td>--}}

                                  <td class="text-bold bg-black">Payable Basic Salary</td>
                                  @foreach($allowances as $allowance)
                                      <td  class="text-bold bg-green">
                                          P. {{$allowance}}
                                      </td>
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
                                  <td class="text-bold bg-black">Action</td>
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

                                @foreach($payroll->payrollDetails as $sk => $sv)
                                    <?php
                                    $template=$total_actual_salary=$total_taxable_income='';

                                    ?>
                                    @if($sv->user->employeePayroll->template)
                                        <?php
                                    $template = $sv->user->employeePayroll->template;
?>
                                    @endif
                                    <tr>
                                        <td class="col-sm-1 text-center">{{$sk+1}}.
                                        </td>
                                        <td class="col-sm-1 text-center text-primary">#{{ $sv->user->emp_id}}
                                        </td>
                                        <td>{{ $sv->user->first_name.' '.$sv->user->last_name }}</td>
                                        <td>{{ $sv->user->designation->designations }}</td>
                                        <td  data-toggle="tooltip"><input type="text" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Salary Grade" data-toggle="tooltip" name="salary_grade[]" value = "{{$sv->salary_grade}}" class="form-control salary_grade" readonly=""></td>
                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Basic Salary" data-toggle="tooltip" name="t_basic[]" value = "{{$sv->t_basic}}" class="form-control t_basic" readonly=""></td>
                                         <td ><input type="number" name="actual_salary[]" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Actual Salary" data-toggle="tooltip" class="form-control actual_salary" value="{{$sv->actual_salary}}" readonly></td>
                                        @foreach($allowances as $allowance)
                                            <?php $data=''; ?>
                                            @if($template)
                                                <?php
                                                $data=$template->allowance->where('allowance_label',$allowance)->first();

                                                ?>
                                            @endif
                                            <td  data-toggle="tooltip">
                                                <input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | {{$allowance->label}}" data-toggle="tooltip" value="{{$data->allowance_value}}" class="form-control allowances" readonly="">

                                            </td>
                                        @endforeach

                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Total Working Days" data-toggle="tooltip" name="total_days[]" value="{{$sv->total_days}}" class="form-control total_days" readonly=""></td>
                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Attendance" data-toggle="tooltip" name="attendance[]" class="form-control" value="{{$sv->attendance}}" readonly></td>
                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} |  Absent Days" data-toggle="tooltip" name="absent[]" class="form-control absent" value="{{$sv->absent}}" readonly></td>
                                       <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} |  Paid Leave Days" data-toggle="tooltip" name="paid_leaves[]" class="form-control" value="{{$sv->paid_leaves}}" readonly></td>








                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Working days basic" data-toggle="tooltip" name="payable_basic[]" class="form-control working_days_basic" value="{{$sv->payable_basic}}" readonly=""></td>
                                        @foreach($sv->paidAllowances as $allowance)
                                            <td  data-toggle="tooltip">
                                                <input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | {{$allowance->salary_payment_allowance_label}}" data-toggle="tooltip" name="allowance_value[]" value="{{$allowance->salary_payment_allowance_value}}" class="form-control allowances" readonly="">
                                            </td>
                                        @endforeach
                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Dashain Allowance" data-toggle="tooltip" name="dashain_allowance[]" class="form-control dashain_allowance" value="{{$sv->dashain_allowance}}" readonly></td>

                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Total After Allowance" data-toggle="tooltip" name="total_after_allowance[]" class="form-control total_after_allowance" value="{{$sv->total_after_allowance}}" readonly=""></td>
                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Annual Incentive" data-toggle="tooltip" name="annual_incentive[]" class="form-control annual_incentive" value="{{$sv->annual_incentive}}"></td>
                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->first_name.' '.$sv->last_name }} | Annual Leave Salary" data-toggle="tooltip" name="annual_leave_salary[]" class="form-control annual_leave_salary" value="{{$sv->annual_leave_salary}}"></td>

                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} |  CIT" data-toggle="tooltip" name="pf[]" class="form-control pf" value="{{$sv->pf}}" readonly=""></td>

                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | SST" data-toggle="tooltip" name="sst[]" class="form-control sst"  value="{{$sv->sst}}" readonly></td>

                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | TDS" data-toggle="tooltip" name="tds[]" class="form-control tds" value="{{$sv->tds}}" readonly></td>

                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Advance Deduction" data-toggle="tooltip" name="advance_deduction[]" class="form-control advance_deduction" value="{{$sv->advance_deduction}}" readonly></td>
                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Loan Deduction" data-toggle="tooltip" name="loan_deduction[]" class="form-control loan_deduction" value="{{$sv->loan_deduction}}" readonly></td>

                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} |  Monthly payable amount" data-toggle="tooltip" name="monthly_payable_amount[]" class="form-control monthly_payable_amount" value="{{$sv->monthly_payable_amount}}" readonly=""></td>
                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} |  Error Adjustment" data-toggle="tooltip" name="error_adjust[]" class="form-control error_adjust" value="{{$sv->error_adjust}}" readonly></td>
                                        <td  data-toggle="tooltip"><input type="number" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Net Salary" data-toggle="tooltip" name="net_salary[]" class="form-control net_salary" value="{{$sv->net_salary}}" readonly=""></td>
                                        <td  data-toggle="tooltip"><input type="text" title = "{{ $sv->user->first_name.' '.$sv->user->last_name }} | Remarks" data-toggle="tooltip" name="remarks[]" class="form-control" value="{{$sv->remarks}}"  readonly></td>
                                        <td>
                                            <a href="{{route('admin.payrolllist.generatePdf',['payroll_detail_id'=>$sv->id])}}" class="btn btn-info btn-xs" title="" data-original-title="Pay Slip"><i class="fa fa-list"></i></a>
                                        </td>
                                    </tr>


                                <?php
                                    $total_basic_sal = $total_basic_sal + $template->basic_salary;
                                    $total_net_sal = $total_net_sal + $net_salary;
                                    $total_overtime =  $total_overtime+$overtime_money;

                                ?>

                                 @endforeach

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
                        <a href="/admin/payroll/download-payroll?payroll_id={{$payroll->id}}" class="btn btn-primary"><i class="fa fa-download"></i>Download Excel</a>

                         <a href="/admin/payroll/list_payroll" class="btn btn-default"></i>Close</a>


        </div>
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
