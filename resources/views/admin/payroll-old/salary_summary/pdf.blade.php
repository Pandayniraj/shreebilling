<table style="width:100%; text-align:center; padding: 30px 0; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px;">

    <thead>
        <tr>
            <td colspan="8">
                <img src="{{env('APP_URL')}}/{{ 'org/'.$organization->logo }}" alt="" class="img-responsive" style="width:200px;">
                <br/>
                <h1 style="font-size:30px; margin-top:20px; margin-bottom:0px; font-weight: bold; color: #00aef0;">Salary Summary</h1>
                <p style="margin-top: 0; margin-bottom: 15px; padding-top: 0;">@if($employee_name) {{$employee_name->first_name}}  {{$employee_name->last_name}}  @else {{date('M Y',strtotime($month))}} @endif</p>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;font-weight: 500">EMP ID</td>
            @if(!$employee_name)
            <td style="text-align: left;">Name</td>
            @endif
            <td style="text-align: left;">Salary Type</td>
            <td style="text-align: left;">Basic Salary</td>
            <td style="text-align: left;">Net Salary</td>
            <td style="text-align: left;">OverTime</td>
            <td style="text-align: left;">Fine</td>
            <td style="text-align: left;">Total</td>
            <td style="text-align: left;"> @if($type == 'emp') Month @else Status @endif</td>
        </tr>
    </thead>
    <tbody>
        @foreach($tdata as $dk => $dv)
        <tr style="background-color: #f9f9f9; border:1px solid #ccc;">
            <td style="text-align:left;">{{ $dv['emp_id']  }}</td>
            @if(!$employee_name)
            <td style="text-align:left;">{{ $dv['name']  }}</td>
            @endif
            <td style="text-align:left;">{{ $dv['salary_grade']  }}</td>
            <td style="text-align:left;">{{ $dv['gross_salary']  }}</td>
            <td style="text-align:left;">{{ $dv['net_sal']  }}</td>
            <td style="text-align:left;">{{ $dv['overtime_money']  }}</td>
            <td style="text-align:left;">{{ $dv['fine'] }}</td>
            <td style="text-align:left;">{{ $dv['total'] }}</td>
            @if($employee_name)
            <td style="text-align:left;">{{ $dv['payment_month'] }}</td>
            @else
            <td style="text-align:left;">{{ $dv['status'] }}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            @if(!$employee_name)<td colspan="2"></td>@else<td colspan="1"></td>@endif
            <td style="float: right;">Total</td>
            <td style="text-align:left;">{{ $tgross_salary }}</td>
            <td style="text-align:left;">{{ $tnet_sal }}</td>
            <td style="text-align:left;">{{ $tovertime_money }}</td>
            <td style="text-align:left;">{{ $tfine }}</td>
            <td style="text-align:left;">{{ $total }}</td>
        </tr>
    </tfoot>
</table>

<hr>
<p style="text-align: center;">Sent from MEROCRM</p>