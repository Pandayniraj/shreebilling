<div class="panel panel-custom">
  <div class="box-header with-border">
    <h3 class="box-title"><b>{{ $pay->user->first_name }} {{ $pay->user->last_name }}</b>  Payment Report with time from <b>{{ date('dS M Y',strtotime($pay->payfrequency->period_start_date)) }}</b> To <b>  
      to {{ date('dS M Y',strtotime($pay->payfrequency->period_end_date)) }}
    </b>
    </h3>
  </div><br>
 <div class="row">
  <div class="col-md-12">
  <div class="col-md-6">
    <table class="table table-hover table-bordered table-striped">
      <thead>
        <tr >
          <th class="bg-olive text-center" colspan="2">Working Salary</th>
        </tr>
      </thead>
      <tr>
        <td>Basic Salary</td>
        <td>NPR. {{$pay->basic_salary}}</td>
      </tr>
       <tr>
        <td>Regular Salary</td>
        <td>NPR. {{$pay->regular_salary}}</td>
      </tr>
       <tr>
        <td>Gratuity Salary</td>
        <td>NPR. {{$pay->gratuity_salary}}</td>
      </tr>
       <tr>
        <td>Overtime Salary</td>
        <td>NPR. {{$pay->overtime_salary}}</td>
       
      </tr>
      <tr>
        <td>Weekend Salary</td>
        <td>NPR. {{$pay->weekend_salary}}</td>
        
      </tr>
       <tr>
        <td>Public Holiday Salary</td>
        <td>NPR. {{$pay->public_holiday_work_salary}}</td>
        
      </tr>
      <tr>
        <td>Sick Salary</td>
        <td>NPR. {{$pay->sick_salary}}</td>
        
      </tr>
      <tr>
        <td>Anual Leave Salary</td>
        <td>NPR. {{$pay->annual_leave_salary}}</td>
      
      </tr>
      <tr>
        <td>Public Holiday Leave Salary</td>
        <td>NPR. {{$pay->public_holiday_salary}}</td>
        
      </tr>
      <tr>
        <td>Other Leave Salary</td>
        <td>NPR. {{$pay->other_leave_salary}}</td>
        
      </tr>

      <tr>
        <td>Total Allowence</td>
        <td>NPR. {{$pay->total_allowance}}</td>
        
      </tr>


      <tr>
        <td>Gross Salary</td>
        <td>NPR. {{$pay->gross_salary}}</td>
       
      </tr>

      <tr>
        <td>Total Deduction</td>
        <td>NPR. {{$pay->total_deduction}}</td>
        

      </tr>

      <tr>
        <td>Tax Amount</td>
        <td>NPR. {{$pay->tax_amount}}</td>
      
      </tr>

      <tr>
        <td>Tax Percentage</td>
        <td>{{$pay->tax_percent}} %</td>
       
      </tr>


      <tr>
        <td>Net Salary</td>
        <td>NPR. {{$pay->net_salary}}</td>
   
      </tr>


     

      <tr>
        <td>Issued By</td>
        <td>{{$pay->issuedBy->username}}</td>
    
      </tr>
      <tr>
        <td>Remarks</td>
        <td>{{$pay->remarks}}</td>
    
      </tr>

    </table>

</div>

<div class="col-md-6">
    <table class="table table-hover table-bordered table-striped">
      <thead>
        <tr >
          <th class="bg-maroon text-center" colspan="2">Working Time</th>
        </tr>
      </thead>
      <tr>
        {{-- <td>Basic Salary</td>
        <td>adawd</td> --}}
        <td>-</td>
        <td>-</td>
      </tr>
       <tr>
        {{-- <td>Regular Salary</td>
        <td>adawd</td> --}}
        <td>Regular Days</td>
        <td>{{$timecard->regular_days}} Days</td>
      </tr>
       <tr>
        {{-- <td>Gratuity Salary</td>
        <td>adawd</td> --}}
        <td>-</td>
        <td>-</td>
      </tr>
       <tr>
       {{--  <td>Overtime Salary</td>
        <td>adawd</td> --}}
        <td>Overtime Hours</td>
        <td>{{$timecard->ot_hour}} Hours</td>
      </tr>
      <tr>
        <td>Weekend Work</td>
        <td>{{$timecard->weekend}} Days</td>
        
      </tr>
       <tr>
       {{--  <td>Public Holiday Salary</td>
        <td>awsd</td> --}}
        <td>Public Holiday Work</td>
        <td>{{$timecard->public_holiday_work}} Days</td>
      </tr>
      <tr>
        {{-- <td>Sick Salary</td>
        <td>awsd</td> --}}
        <td>Sick Leave</td>
        <td>{{$timecard->sick_leave}} Days</td>
      </tr>
      <tr>
        {{-- <td>Anual Leave Salary</td>
        <td>awsd</td> --}}
        <td>Anual Leave</td>
        <td>{{$timecard->annual_leave}} Days</td>
      </tr>
      <tr>
   {{--      <td>Public Holiday Leave Salary</td>
        <td>awsd</td> --}}
        <td>Public Holiday Leave</td>
        <td>{{$timecard->public_holidays}} Days</td>
      </tr>
      <tr>
        {{-- <td>Other Leave Salary</td>
        <td>awsd</td> --}}
        <td>Other Leave</td>
        <td>{{$timecard->other_leave}} Days</td>
      </tr>

      <tr>
        {{-- <td>Total Allowence</td>
        <td>awsd</td> --}}
        <td>-</td>
        <td>-</td>
      </tr>


      <tr>
        {{-- <td>Gross Salary</td>
        <td>awsd</td> --}}
        <td>-</td>
        <td>-</td>
      </tr>

      <tr>
        {{-- <td>Total Deduction</td>
        <td>awsd</td> --}}
        <td>-</td>
        <td>-</td>
      </tr>

      <tr>
        {{-- <td>Tax Amount</td>
        <td>awsd</td> --}}
        <td>-</td>
        <td>-</td>
      </tr>

      <tr>
     {{--    <td>Tax Percentage</td>
        <td>awsd</td> --}}
        <td>-</td>
        <td>-</td>
      </tr>


      <tr>
        {{-- <td>Net Salary</td>
        <td>awsd</td> --}}
        <td>-</td>
        <td>-</td>
      </tr>


      <tr>
     {{--    <td>Remarks</td>
        <td>awsd</td> --}}
      


      <tr>
 {{--        <td>Issued By</td>
        <td>awsd</td> --}}
        <td>Issued By</td>
        <td>{{$timecard->issuedBy->username}} </td>
      </tr>

       <tr>
        <td>Remarks</td>
        <td>{{$timecard->remarks}}</td>
    
      </tr>

    </table>

</div>
</div>
</div>
</div>

     