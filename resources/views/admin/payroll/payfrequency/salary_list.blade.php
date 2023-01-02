@extends('layouts.master')

@section('content')

<style type="text/css">
  .blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
.totalwithouttax,.total{
  text-indent: 30px;
  font-weight: 600;
}
.payrollremarks:hover{
  cursor: pointer;
}
.control-sidebar.control-sidebar-dark{
  display: none !important;
}
</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                @if(count($enter_payroll) > 0 )
                <small>
                <i style="color: red">
                Showing Payment Modified at {{date('dS Y M',strtotime($enter_payroll[0]->updated_at))}} by:
                </i>
                <b>&nbsp;{{$enter_payroll[0]->issuedBy->username}}</b>
                </small>
                @else
                      <small>{!! $page_description ?? "Page description" !!}</small>
                @endif
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">     
                Monthly: {{date_format(date_create($payfrequency->period_start_date),'Y/m/d')}} 
                To: {{date_format(date_create($payfrequency->period_end_date),'Y/m/d')}}  
                Check date: {{date_format(date_create($payfrequency->check_date),'Y/m/d')}}
                GL Post date:{{date_format(date_create(date('Y-m-d')),'Y/m/d')}}
              </h3>

              <div class="box-tools pull-right">
                <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                  

                  <div class="input-group-btn">
                    <a href="/admin/payroll/bulk/salarydownload/csv/{{\Request::segment(5)}}" class="btn btn-primary btn-sm"><i class="fa fa-file-excel-o">&nbsp;</i>Download CSV </a>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <form method="POST" action="/admin/payroll/run/payroll_summary" id='runpayroll'>
              {{ csrf_field() }}
            <input type="hidden" name="frequency_id" value="{{$payfrequency->id}}" >
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover" id='filter-table'>
                <thead>
                  <tr>
                    <th colspan="2" style="text-align: center;background-color:#8FBC8F;">Employee Info</th>
                    <th colspan="4" style="text-align: center">Working Hours Salaries</th>
                    <th colspan="4" style="text-align: center;background-color:#8FBC8F;">Leave Salaries</th>
                    <th>Allowance</th>
                    <th colspan="1" style="text-align: center;background-color:#8FBC8F;">Gross Salary</th>
                    <th>Deduction</th>
                    <th colspan="2" style="text-align: center;background-color:#8FBC8F;">Tax</th>
                    <th style="text-align: center;">Net Salary</th>
                    <th style="text-align: center; background-color:#8FBC8F;">Remarks</th>
                  </tr>

                </thead>
                
                <tbody >
                @if(isset($enter_payroll) && count($enter_payroll) > 0 )
                @foreach($enter_payroll as $salary)
                <tr class='payrolldetails'>
                    <input type="hidden" name="salaryid" value="{{$salary->id}}">
                  <td>{{ env('SHORT_NAME') }}<span class="user_id">{{ $salary->user_id }}</span></td>
                  <td>{{ $salary->user->first_name }} {{ $salary->user->last_name }}</td>
                  <td><span class="label basic_salary  label-success">{{$salary->basic_salary}}</span></td>
                  <td class="edit">
                    <span class="editable regular">{{$salary->regular_salary}}</span>
                  </td>
                  <td class="edit">
                    <span class="editable gratuity_salary">{{$salary->gratuity_salary}}</span>
                  </td>
                  <td class="edit">
                    <span class="editable overtime">{{$salary->overtime_salary}}</span>
                  </td>
                    <td class="edit">
                    <span class='editable sick_leave'>{{$salary->sick_salary}}</span>
                  </td>
                  <td class="edit"> 
                   <span class='editable annual_leave'>{{$salary->annual_leave_salary}}</span>
                  </td>
                  <td class="edit"> 
                    <span class='editable public_holidays'>{{$salary->public_holiday_salary}}</span>
                  </td>
                  <td class="edit">
                   <span class='editable others_leave'>{{$salary->other_leave_salary}}</span>
                  </td>
                  <td>{{$salary->total_allowance}}</td>
                  <td class="totalwithouttax">{{$salary->gross_salary}}</td>
                  <td>{{$salary->total_deduction}}</td>
                  <td class="edit"><span class="editable tax">{{$salary->tax_percent}}</span>%</td>
                  <td class="edit"><span class="editable taxamount">{{$salary->tax_amount}}</span></td>
                  <td  style="width: 10%">
                    <span class="total">{{$salary->net_salary}}</span>
                  </td>
                  <td title="{{$salary->remarks}}">
                     <span class="payrollremarks" style="max-width: 60px; max-height: 20px; position: absolute; text-overflow: ellipsis; overflow: hidden;">
                      {{$salary->remarks}}
                    </span>
                  </td>
                  <?php
                    $totalarr['regular'] +=$salary->regular_salary;
                    $totalarr['overtime'] +=  $salary->overtime_salary;
                    $totalarr['sick'] +=  $salary->sick_salary;
                    $totalarr['anual'] +=  $salary->annual_leave_salary;
                    $totalarr['public'] +=   $salary->public_holiday_salary;
                    $totalarr['other'] +=   $salary->other_leave_salary;
                    $totalarr['allowance'] += $salary->total_allowance;
                    $totalarr['deduction'] += $salary->total_deduction;
                    $totalarr['totalamountwithouttax'] += $salary->gross_salary;
                    $totalarr['taxamount'] +=  $salary->tax_amount;
                    $totalarr['total'] += $salary->net_salary;
                  ?>
                  
                </tr>
                @endforeach

                @endif
                  </tbody>
                

    <tfoot>
      <tr>
          <td>Total</td>
                  <td></td>
                  <td></td>
                  <td>NPR <span id='totalregular'>{{$totalarr['regular']}}</span></td>
                  <td></td>
                  <td>NPR <span id='totalovertime'>{{$totalarr['overtime']}}</span></td>
                  <td>NPR <span id='totalsick_leave'>{{$totalarr['sick']}}</span></td>
                  <td>NPR <span id='totalannual_leave'>{{$totalarr['anual']}}</span></td>
                  <td>NPR <span id='totalpublic_holidays'>{{$totalarr['public']}}</span></td>
                  <td>NPR <span id='totalothers_leave'>{{$totalarr['other']}}</span></td>
                  <td>{{ $totalarr['allowance']  }}</td>
                  <td>NPR <span id='totalamountwithouttax'>{{$totalarr['totalamountwithouttax']}}</span></td>
                  <td>{{ $totalarr['deduction']  }}</td>
                  <td></td>
                  <td>NPR <span id='totaltaxamount'>{{$totalarr['taxamount']}}</span></td>
                  <td>NPR <span id='totalamount'>{{$totalarr['total']}}</span></td>
      </tr>
    </tfoot>



            </table>

              <a href="/admin/payroll/payfrequency/" type="button" class="btn btn-default btn-lg" >
                <i class="fa fa-close"></i>&nbsp; Close</a>
            </div>
          </form>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
</div>
        
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script src="/bower_components/admin-lte/plugins/datatables/extra/export.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  $("#searchbar").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#searchabletable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });




  });

    var table = $('#filter-table').DataTable({ 
  
  dom: 'Bfrtip',
   
   buttons: [
            {
                extend: 'copyHtml5',
                title: 'Data export',

            },
            


            {
                extend: 'pdfHtml5',
                footer: true,
                orientation: 'landscape',
                customize: function(doc) {
                doc.content.splice(0, 1, {
                  text: [{
                    text: 'Company: {{env('APP_COMPANY')}} \n PAN: {{\Auth::user()->organization->tpid}}\nAddress:{{\Auth::user()->organization->address}}',
                    bold: true,
                    fontSize: 12,
                    alignment: 'left'
                  }, {
                    text: '\nPayroll Report \n {{date('dS M Y',strtotime($payfrequency->period_start_date) )}} - {{date('dS M Y',strtotime($payfrequency->period_end_date)) }} \n Check Date - {{date('dS M Y',strtotime($payfrequency->check_date))}}\n\n',
                    bold: false,
                    fontSize: 12,
                    alignment:'center'
                  }
                  ]
               
                });
              }



            }
        ],
  });



});
$.fn.editable.defaults.ajaxOptions = {type: "GET"};
$('.payrollremarks').each(function(){
  var parent = $(this).parent().parent();
  $(this).editable({
    type:'textarea',
    pk:1,
    url:'/admin/payroll/payfrequency/view_salarylist/{{$frequency_id}}?id='+parent.find('input[name=salaryid]').val(),
    title: `Salary Remarks`,
  });
});

</script>
@endsection