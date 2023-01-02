<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | Attendance Report</title>
    <style>
      table td {
        padding: 5px;
        text-align: left;
        border: 1px solid #ccc;
      }
    </style>
  </head>
  <body>

    @if($summaryReport)

    
    <table style="width:100%; text-align:center; padding: 30px 0; box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); -webkit-box-shadow: 0 1.2rem 1.8rem 0 rgba(0,0,0,0.24),0 1.7rem 5rem 0 rgba(0,0,0,0.19); font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px;">

    <thead>
        <tr>
            <td colspan="2" style="text-align: left;">Company:{{\Auth::user()->organization->organization_name}}</td>
        </tr>
        <tr>
            <td style="text-align: left;">Address:{{\Auth::user()->organization->address}}</td>
            <td colspan="13" style="text-align: right;">
                Date: {{ date('dS M y', strtotime($start_date)) }} - {{ date('dS M y', strtotime($end_date)) }}
             </td>
        </tr>
        <tr>
            <td style="text-align: left;">PAN:{{\Auth::user()->organization->tpid}}</td>
        </tr>
        

      
        <tr>


            <th>Name</th>
            <th>Degination</th>
            <th>Shift Name</th>
            <th>Shift Time</th>
            <th>Clock In</th>
            <th>Late By</th>
            <th>Early By</th>
            <th>Break Taken</th>
            <th>Work Done</th>
            <th>OverTime</th>
            <th>Clock Out</th>
            <th>Remark</th>

        </tr>
    </thead>
    <tbody>
      @foreach($summaryReport as $key=>$value)
      <?php 
       $value = (object) $value;
      ?>
      <tr>

        <td>{{ $value->first_name }} {{ $value->last_name }} [{{ $value->emp_id }}]</td>
         <td>{{ $value->degination }} </td>
         <td>{{ $value->shift_name }} </td>
          
          <td>{{ $value->officeTime }} </td>
          <td>
            @if(strtotime($value->clockin))
            {{ date('h:i A',strtotime($value->clockin)) }} 
            @else
              --
            @endif

          </td>
         <td>{{ $value->lateby }} </td>
          
          <td>{{ $value->earlyby }} </td>
          <td>{{ $value->break_taken }} </td>
           
          <td>{{ $value->work_done }} </td>
           
          <td>{{ $value->overTime }} </td>
          <td>
            @if(strtotime($value->clockout))
            {{ date('h:i A',strtotime($value->clockout)) }}
            @else
              --
            @endif
          </td>
          <td>{{ $value->remark }} </td>
      </tr>
      @endforeach
    </tbody>



    </tbody>
</table>

<hr>
<p style="text-align: center;">Sent from {{env('APP_COMPANY')}}</p>



    @endif

  </body>
</html>