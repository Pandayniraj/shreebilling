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
                    PayBulk Salary Project Wise
                    <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p>Adjust Salary Before Pay Now. This will affect Salary amount</p>

         {{-- {{ TaskHelper::topSubMenu('topsubmenu.payroll')}} --}}


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
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/payroll/paybulk/" method="get" class="form-horizontal form-groups-bordered">
                    <div class="form-group">
                        <div class="col-sm-4">
                            <select required name="project_id" id="project" class="form-control select_box">
                                <option value="">Select Project</option>
                                @foreach($projects as $dk => $dv)
                                <option value="{{ $dv->id }}" @if($project_id == $dv->id) selected="selected" @endif>{{ $dv->name }}</option>
                                @endforeach
                            </select>
                        </div>
                       <?php $current_year = \App\Helpers\FinanceHelper::cur_fisc_yr(); 
                        // dd($current_year);
                       ?> 
                       <div class="col-sm-4 ">

                         <select required name="payment_month" id="project" class="form-control select_box" required="">
                                <option value="">Select Month</option>
                                <option value="1" @if($payment_month == 1) selected="selected" @endif>January</option>
                                <option value="2" @if($payment_month == 2) selected="selected" @endif>February</option>
                                <option value="3" @if($payment_month == 3) selected="selected" @endif>March</option>
                                <option value="4" @if($payment_month == 4) selected="selected" @endif>April</option>
                                <option value="5" @if($payment_month == 5) selected="selected" @endif>May</option>
                                <option value="6" @if($payment_month == 6) selected="selected" @endif>June</option>
                                <option value="7" @if($payment_month == 7) selected="selected" @endif>July</option>
                                <option value="8" @if($payment_month == 8) selected="selected" @endif>August</option>
                                <option value="9" @if($payment_month == 9) selected="selected" @endif>September</option>
                                <option value="10" @if($payment_month == 10) selected="selected" @endif>October</option>
                                <option value="11" @if($payment_month == 11) selected="selected" @endif>Nobember</option>
                                <option value="12" @if($payment_month == 12) selected="selected" @endif>December</option>
                          
                         </select>
                                <!-- <a href="#"><i class="fa fa-calendar"></i></a> -->
                       </div>

                        <div class="col-sm-2">
                            <button type="submit" id="sbtn" class="btn btn-primary">Search Users</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(!empty($users))
<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom table-responsive">
                <form id="form" role="form" enctype="multipart/form-data" action="/admin/payroll/paybulk/" method="get" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <table id="" class="table table-bordered std_table">
                        <thead>
                        <tr>
                            <th class="text-bold">Name</th>
                            <th class="text-bold">Adjustment</th>
                            <th class="text-bold" title="Gross salary form salary template">Gross</th>
                            <th class="text-bold" title="Gross salary After Adjustment">Gross after Adjustment</th>
                            {{--<th class="text-bold" title="Deductions  form salary template">Deduction</th>--}}
                            <th class="text-bold" title="SSF deductions  form salary template">SSF Deduction</th>
                            <!-- <th class="text-bold" title="net salary form salary template">Net</th> -->
                            <!-- <th class="text-bold" title="Insurance Premium">IP</th> -->
                            <!-- <th class="text-bold" title="Remote Tax Deductions">Remote</th> -->
                            <th class="text-bold" title="Tax Ammount">TAX</th>
                            <!-- <th class="text-bold" title="Payment Status">Status</th> -->
                            <th class="text-bold">Net</th>
                        </tr>
                        </thead>
                            <tbody>
                                <?php $flag = 0;
                                // dd($users);
                                 ?>
                                @foreach($users as $user)
                                <tr class="detail-tr">
                                  <?php 
                                  $salary_payment = \App\Models\PaySalary::where('payment_month',$payment_month)->where('payment_year',$payment_year)->where('user_id',$user->id)->first(); 
                                  $salary_payment_status = $salary_payment->status;
                                  $template = \App\Helpers\PayrollHelper::getEmployeePayroll($user->id)->template;
                                  $emp_payroll = \App\Models\EmployeePayroll::where('user_id',$user->id)->first();
                                
                                  $remote_tax_deduction_amount = \App\Helpers\PayrollHelper::remote_tax_deduction_amount($emp_payroll->district_name);

                                  $salary_adjustment = \App\Models\PaySalary::where('payment_month',$payment_month)->where('payment_year',$payment_year)->where('user_id',$user->id)->first()->adjustment;

                                  $insurance_premium = \App\Helpers\PayrollHelper::insurance_premium($emp_payroll->insurance_premium);



                                  $ssf_contribution_percentage = env('SSF_CONTRIBUTION');


                                  $ssf_deduction_limit = env('SSF_DEDUCTIION_LIMIT');

                                      
                                       $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id);
                                       $allowances1 = 0;
                                        foreach ($allowances as $ak => $av) {
                                            $allowances1 += $av->allowance_value;
                                        }

                                        $ssf_contribution = ($template->basic_salary * $ssf_contribution_percentage/100);

                                        $gross_salary = $template->basic_salary +  $allowances1 + $ssf_contribution;
                                        $total_adjustment = \App\Models\PaySalary::where('user_id',$user_id)->where('payment_month','<=',$payment_month)->sum('adjustment');

                                        $option_1 = (($template->basic_salary *12 * 31) / 100)+ (($emp_payroll->other_ssf)*12) ; 


                                        $option_2 = (($gross_salary * 12)+$adjustment)*0.33;

                                        $option_3 = $ssf_deduction_limit;
                                        $ssf_deduction_total = min($option_1,$option_2,$option_3);  


                                        $tax_amount1 = \App\Helpers\PayrollHelper::get_tax_amount($user->id,$payment_month,$gross_salary,$insurance_premium,$remote_tax_deduction_amount,$emp_payroll->is_disabled,$ssf_deduction_total,$emp_payroll->marrital_option);

                                         $gender = \App\Models\UserDetail::where('user_id',$user->id)->first()->gender;

                                         if($gender != 'Male' && $emp_payroll->marrital_option == 0)
                                         {
                                           $tax_amount = $tax_amount1*0.9/12;
                                         }else{
                                            $tax_amount = $tax_amount1/12;
                                         }
                                   ?>
                                   <input type="hidden" name="salary_template_id[]" value="{{$template->salary_template_id}}">
                                   <input type="hidden" name="basic_salary[]" value="{{$template->basic_salary}}">
                                   <input type="hidden" name="user_id[]" value="{{$user->id}}">
                                    <td title="{{$user->id}} &nbsp;  {{ $user->designation->designations }}">{{ $user->first_name.' '.$user->last_name }} </td>
                                    <td title="Adjustment">
                                        <input type="text" class = "form-control adjustment" name="adjustment[]" value="{{ $salary_adjustment }}">{{  $salary_adjustment }}
                                    </td>

                                    <td title="Gross salary from Salary Template"><input type="text"  class = "gross_salary" value="{{ $gross_salary }}"></td>

                                    <td title="Gross salary After adjustment"><input type="text" name="gross_salary[]" class = "gross_salary_after_adjustment" readonly=""></td>
                                  
                                    <td title="SSF Deductions"><input type="text" name="ssf[]" class="ssf_deduction" value="{{ round($ssf_deduction_total) }}">
                                    </td>
                                
                                    <td><input type="text" name="tax_amount[]" class="tax_amount" value="{{round($tax_amount)}}"></td>
                                   
                                  <!--   <td>   @if($salary_payment_status == 0) Unpaid @else Paid @endif</td> -->

                                     <?php $total_amount = $adjustment + $gross_salary - (($option_1/12) +  $tax_amount); ?>
                                    <td><input type="text" name="total_amount[]" class = "net_salary" value="{{round($taxable_amount -   $tax_amount)}}"></td>
                                </tr>
                                @endforeach
                            </tbody>
                    </table>
                    <div class="col-sm-8"></div>
                    <div class="col-sm-4 row mt-lg pull-right" style="margin-top: 10px;">
                        <input type="hidden" name="project_id" value="{{ $project_id }}">
                        <input type="hidden" name="payment_month" value="{{ $payment_month }}">
                   @if($salary_payment_status == 0) 
                        <button id="salery_btn" type="submit" class="btn btn-primary btn-block">Pay Now</button>
                         <a href="/admin/payroll/manage_salary_details" class="btn btn-default btn-block">Cancel</a>
                   @endif  
                  {{-- <!--  <a href="{{route('admin.payroll.excel',[$project_id,$payment_month])}}" class="btn btn-default btn-block">Download Excel</a>  -->  --}} 
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

<!-- <script type="text/javascript">
    $(function() {

        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script> -->

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

<script type="text/javascript">
    $(function() {
            $('.detail-tr').each(function(e){
              var adjustment = $(this).find('.adjustment').val();
              // console.log(adjustment);
              // debugger;
        });
         $(document).on('input', '.adjustment', function() {
                var parentDiv=$(this).parent().parent();
                var adjustment = parentDiv.find('.adjustment').val();
                var gross_salary_salary_template = parentDiv.find('.gross_salary').val();
                parentDiv.find('.gross_salary_after_adjustment').val(Number(adjustment)+Number(gross_salary_salary_template));
                var ssf_deduction = parentDiv.find('.ssf_deduction').val();
                var tax_amount = parentDiv.find('.tax_amount').val();
                var net_salary = parentDiv.find('.net_salary').val();
                
            });
     });    
</script>
@endsection
