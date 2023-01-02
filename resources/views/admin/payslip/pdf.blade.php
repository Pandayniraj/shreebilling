<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | Payslip</title>
    <style type="text/css">
        @font-face {
            font-family: Arial;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        /*.item-detail td,th{*/
        /*    border:1px solid #eee;*/
        /*}*/
        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 18cm;
            height: 24.7cm;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }

        .border-table th,
        .border-table td {
            padding: 3px;
            text-align: left;
            border: 1px solid #AAAAAA!important;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: left;
        }

        table td h3 {
            color: #57B223;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1em;
            background: #57B223;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }

        table .qty {
        }

        table .total {
            background: #57B223;
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        /*table tbody tr:last-child td {*/
        /*    border: none;*/
        /*}*/

        /*table tfoot td {*/
        /*    padding: 5px 10px;*/
        /*    background: #FFFFFF;*/
        /*    border-bottom: none;*/
        /*    font-size: 1em;*/
        /*    white-space: nowrap;*/
        /*    border-top: 1px solid #AAAAAA;*/
        /*}*/

        /*table tfoot tr:first-child td {*/
        /*    border-top: none;*/
        /*}*/

        table tfoot tr:last-child td {
            color: #57B223;
            font-size: 1em;
            /*border-top: 1px solid #57B223;*/
            font-weight: bold;

        }

        /*table tfoot tr td:first-child {*/
        /*    border: none;*/
        /*}*/

        .text-center {
            text-align: center;
        }

        .bg-gray {
            background-color: #d2d6de !important;
        }

        div {
            line-height: 18px;
        }
    </style>
</head>
<body>
<?php
$chosen_date = explode('-', $slip->payroll->date);
$year = $chosen_date[0];
$month = $chosen_date[1];
$monthsName = ['Baisakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangsir', 'Paush', 'Magh', 'Falgun', 'Chaitra'];

?>
                    <div style="text-align: center;margin-bottom:10px">
                    <h2 style="padding: 0px;margin: 3px">
                        {{ $slip->payroll->division->name}}
                    </h2>
                    <h3 style="padding: 0px;margin: 3px">
                        Salary Summary
                    </h3>
                    <h3 style="padding: 0px;margin: 3px">
                        For the month of {{$monthsName[$month-1]}}, {{$year}}
                    </h3>
                    </div>
            <!-- info row -->
                <table>
                    <tr>
                        <td style="width: 33%;">
                            <strong>Name: </strong>{{$slip->user->full_name}}
                        </td>
                        <td style="width: 33%;">
                            <strong>Emp. Id:</strong> {{$slip->user->emp_id}}
                        </td>
                        <td style="width: 33%;">
                            <strong>PAN #:</strong> {{$slip->user->pan_no}}
                        </td>
                    </tr>

                    <tr>
                        <td style="width: 33%;">
                            <strong>Designation: </strong>{{$slip->user->designation->designations}}
                        </td>
                        <td style="width: 33%;">
                            <strong>Department:</strong> {{$slip->user->department->deptname}}
                        </td>
                         <td style="width: 33%;">
                            <strong>CIT #:</strong> {{$slip->user->cit_no}}
                        </td>

                    </tr>

                    <tr>
                        <td style="width: 33%;">
                            <strong>Marital Status: </strong>{{ucfirst($slip->user->userDetail->marital_status)}}
                        </td>
                        <td style="width: 33%;">
                            <strong>Payable Days:</strong> {{$slip->payable_days}}
                        </td>

                         <td style="width: 33%;">
                            <strong>BANK A/C:</strong> {{$slip->user->userDetail->bank_name}} {{$slip->user->userDetail->bank_account_no}}
                        </td>

                    </tr>
                    <tr>
                        <td style="width: 33%;">
                            <strong>DOJ: </strong>{{ucfirst($slip->user->userDetail->join_date)}}
                        </td>


                    </tr>
                </table>

<div style="width: 100%; display: table;margin-top: 20px">
    <div style="display: table-row">
        <div style="width: 48%;display: table-cell">
<table class="border-table">
    <thead>
    <tr style="background-color: #e5e5e5">
{{--        <th style="text-align: right;width:5%">S.No</th>--}}
        <th  style="font-weight:bold;width:30%">Salary & Allowances</th>
        <th  style="font-weight:bold;width:20%">Amount({{env('APP_CURRENCY')}})</th>
{{--        <th style="width:25%">Deduction</th>--}}
    </tr>
    </thead>
    <tbody>
    <?php
    $sno=1;

    ?>
    <tr>
        <td  style="width:30%">Payable Basic Salary</td>
        <td  style="width:20%">{{number_format($slip->payable_basic,2)}}</td>
    </tr>
    @foreach($slip->paidAllowances as $odk => $odv)
        <tr>
            <td  style="width:30%">{{$odv->salary_payment_allowance_label}}</td>
            <td  style="width:20%">{{number_format($odv->salary_payment_allowance_value,2)}}</td>
        </tr>
    @endforeach
    <tr>
        <td  style="width:30%">Dashain Allowance</td>
        <td  style="width:20%">{{number_format($slip->dashain_allowance,2)}}</td>
    </tr>
    <tr>
        <th  style="width:30%">Error Adjust</th>
        <th style="width:20%">{{($slip->error_adjust>0?'+':'').number_format($slip->error_adjust,2)}}</th>
    </tr>
    <tr>
        <?php
        $allowance_sum=$slip->paidAllowances->sum('salary_payment_allowance_value');
        $total_allowance=$slip->dashain_allowance+$slip->incentive+$slip->pf_contribution+$allowance_sum;
        ?>
{{--        <td style="text-align: center;width:5%">{{$sno++}}</td>--}}
        <td  style="font-weight:bold;width:30%">Total Earning</td>
        <td  style="font-weight:bold;width:20%">{{env('APP_CURRENCY')}} {{number_format($total_allowance+$slip->payable_basic+$slip->error_adjust,2)}}</td>
{{--        <td style="width:25%">-</td>--}}
    </tr>
    </tbody>
</table>
        </div>
    <div style="width: 4%;display: table-cell"></div>

        <div style="width: 48%;display: table-cell">
        <table class="border-table">
    <thead>
    <tr style="background-color: #e5e5e5">
        <th  style="font-weight:bold;width:30%">Deductions and Adjustments</th>
        <th  style="font-weight:bold;width:20%">Amount({{env('APP_CURRENCY')}})</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td  style="width:30%">CIT Deduction</td>
        <td style="width:25%">{{number_format($slip->pf,2)}}</td>
    </tr>
    <tr>
        <td  style="width:30%">SST</td>
        <td style="width:20%">{{number_format($slip->sst,2)}}</td>
    </tr>
    <tr>
        <td  style="width:30%">TDS</td>
        <td style="width:20%">{{number_format($slip->tds,2)}}</td>
    </tr>
    <tr>
        <td  style="width:30%">Loan</td>
        <td style="width:20%">{{number_format($slip->loan_deduction,2)}}</td>
    </tr>
    <tr>
        <td  style="width:30%">Advance</td>
        <td style="width:20%">{{number_format($slip->advance_deduction,2)}}</td>
    </tr>

{{--    <tr>--}}
{{--        <td  style="width:30%">Gratuity</td>--}}
{{--        <td style="width:20%">{{number_format($slip->gratuity,2)}}</td>--}}
{{--    </tr>--}}


    <tr>
        <?php
        $total_deduction=$slip->sst+$slip->tds+
            $slip->advance_deduction+$slip->loan_deduction+$slip->pf;
        ?>
        <td  style="font-weight:bold;width:30%">Total Deduction</td>
        <td style="font-weight:bold;width:20%">{{env('APP_CURRENCY')}} {{number_format($total_deduction,2)}}</td>
    </tr>

    <tr style="background-color: black; color: white">
         <td  style="font-weight:bold;width:30%">Net Payable</td>
        <td style="font-weight:bold;width:20%">{{env('APP_CURRENCY')}} {{number_format($slip->net_salary,2)}}</td>

    </tr>
    </tbody>
</table>
            <table>

            </table>
        </div>
</div>
</div>

<div style="width: 100%; display: table;justify-content: space-between">


<hr/><br/><br/>


        <div style="width: 48%;display: table-cell">
        <table class="" style="border: none" cellspacing="0" cellpadding="0">
@php
    $currentYear = \TaskHelper::cur_leave_yr();
    $carrayOverLeave = \App\Models\LeaveCarryForward::where('from_leave_year_id',$currentYear->id)->where('user_id',$slip->user_id)->first()->num_of_carried;
@endphp
            <tbody>
            <tr>
                 <td style="width: 33%;">
                    <strong>Total Day: </strong>{{$slip->total_days}}
                 </td>
                 <td style="width: 33%;">
                    <strong>Present Day: </strong>{{$slip->attendance}}
                 </td>
                 <td style="width: 33%;">
                    <strong>Carry forward leave:{{$carrayOverLeave}} </strong>
                </td>
                
            </tr>

           <tr>
               <td style="width: 33%;"></td>
                <td style="width: 33%;">
                    <strong>Weekends & Holiday: </strong>{{$slip->weekends +$slip->holidays}}
                </td>
                <td style="width: 33%;">
                    <strong>Absent Day: </strong>{{$slip->paid_leaves+$slip->absent}}(PL:{{$slip->paid_leaves>2?$slip->paid_leaves-abs((int)$slip->adjust_leave):$slip->paid_leaves}}+UP:{{$slip->absent>0?$slip->absent-abs((int)$slip->adjust_leave):$slip->absent}}+AL:{{$slip->adjust_leave}})
                 </td>
            </tr>
            <tr>
                <td style="width: 33%;"></td>
                <td style="width: 33%;"></td>
                 <td style="width: 33%;">
                     <strong>Unpaid Leave Days: </strong>{{$slip->absent>0?$slip->absent-abs((int)$slip->adjust_leave):$slip->absent}}
                  </td>
             </tr>
            </tbody>
        </table>
        </div>




</div>


<!-- <br style="margin-bottom: 20px"> -->
                <!-- <div style="width: 100%; display: table;">
                    <div style="display: table-row">
                <div style="width: 33%;display: table-cell">
                    <div style="margin-top: 5px;" >______________</div>
                    <div style="font-weight: bold;">Finance Manager</div>
                </div>
                    <div style="width: 33%;display: table-cell;">
                        <div style="margin-top: 5px;">______________</div>
                        <div style="font-weight: bold;">General Manager</div>
                       </div>
                    <div style="width: 33%;display: table-cell">
                        <div>
                            <div style="margin-top: 5px;">______________</div>
                            <div style="font-weight: bold;">Receiver's Signature</div>
                            </div>
                    </div>
            </div>
                </div> -->

                <br/>
    <pre>
    This is a computer generated payslip
    Note: PL="Paid Leave Days", UL="Unpaid Leave Days", AL="Adjust Leave Days"
    </pre>
</body>
</html>
