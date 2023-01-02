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

              <div class="box-tools">
                <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search" 
                  id='searchbar'>

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <form method="POST" action="/admin/payroll/run/payroll_summary" id='runpayroll'>
              {{ csrf_field() }}
            <input type="hidden" name="frequency_id" value="{{$payfrequency->id}}">
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th colspan="2" style="text-align: center;background-color:#8FBC8F;">Employee Info</th>
                    <th colspan="2" style="text-align: center">Working Hours Salaries</th>
                    <th colspan="1" style="text-align: center;background-color:#8FBC8F;">Gross Salary</th>
                    <th colspan="2" style="text-align: center;">Tax</th>
                    <th style="text-align: center;background-color:#8FBC8F;">Net Salary</th>
                    <th style="text-align: center; ">Remarks</th>
                  </tr>
                </thead>
                <tr>
                  <th>Emp ID</th>
                  <th>User</th>
                  <th>Regular</th>
                  <th>Overtime</th>
                  <th>Total Without Tax</th>
                  <th>Tax</th>
                  <th>Tax amount</th>
                  <th>Total</th>
                  <th>Remarks</th>
                </tr>
                <tbody id='searchabletable'>
                @if(isset($enter_payroll) && count($enter_payroll) > 0 )
                @foreach($enter_payroll as $salary)
                <tr class='payrolldetails'>
                  <td>{{ env('SHORT_NAME') }}<span class="user_id">{{ $salary->user_id }}</span></td>
                  <td>{{ $salary->user->first_name }} {{ $salary->user->last_name }}</td>
                  <td class="edit">
                    <span class="editable regular">{{$salary->regular_salary}}</span>
                  </td>
                  <td class="edit">
                    <span class="editable overtime">{{$salary->overtime_salary}}</span>
                  </td>
                  <td class="totalwithouttax">{{$salary->gross_salary}}</td>
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
                    $totalarr['totalamountwithouttax'] += $salary->gross_salary;
                    $totalarr['taxamount'] +=  $salary->tax_amount;
                    $totalarr['total'] += $salary->net_salary;
                  ?>
                  
                </tr>
                @endforeach



                @else

                @foreach($salary as $d)
                <tr class='payrolldetails'>
                  <td>{{ env('SHORT_NAME') }}<span class="user_id">{{ $d['user']->id }}</span></td>
                  <td>{{ $d['user']->first_name }} {{ $d['user']->last_name }}</td>
                  <td class="edit">
                    <span class="editable regular">{{$d['regular_salary']}}</span>
                  </td>
                  <td class="edit">
                    <span class="editable overtime">{{$d['overtime_salary']}}</span>
                  </td>
                  <td class="totalwithouttax">{{$d['totalwithouttax']}}</td>
                  <td class="edit"><span class="editable tax">{{$d['tax']}}</span>%</td>
                  <td class="edit"><span class="editable taxamount">{{$d['taxamount']}}</span></td>
                  <td  style="width: 10%">
                    <span class="total">{{$d['total']}}</span>
                  </td>
                  <td >
                  <span class="payrollremarks" style="max-width: 60px; max-height: 20px; position: absolute; text-overflow: ellipsis; overflow: hidden;"></span>
                  </td>
                </tr>
                @endforeach

                @endif
                  </tbody>
                  <td>Total</td>
                  <td></td>
                  <td>NPR <span id='totalregular'>{{$totalarr['regular']}}</span></td>
                  <td>NPR <span id='totalovertime'>{{$totalarr['overtime']}}</span></td>
                  <td>NPR <span id='totalamountwithouttax'>{{$totalarr['totalamountwithouttax']}}</span></td>
                  <td></td>
                  <td>NPR <span id='totaltaxamount'>{{$totalarr['taxamount']}}</span></td>
                  <td>NPR <span id='totalamount'>{{$totalarr['total']}}</span></td>

               <tfoot>
      <tr>
        
      </tr>
    </tfoot>



            </table>

              <a href="#" type="button" class="btn btn-default btn-lg" onClick="submitpayroll('later')">
                <i class="fa fa-pause"></i>&nbsp; Finish Later
              </a><a href="#" type="button" class="btn btn-primary btn-lg" onClick="submitpayroll('next')">
                <i class="fa fa-play"></i>&nbsp; Next
              </a>

            </div>
          </form>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
</div>
        
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script type="text/javascript">
 function empsal_without_tax(parent){
  let regular = parent.find('.regular').text();
  let overtime = parent.find('.overtime').text();
  let total_sal = Number(regular) + Number(overtime);
  return total_sal;
}
function calculateTotalColumn(parent,title){
  var total_sal = empsal_without_tax(parent);
  parent.find('.totalwithouttax').text(total_sal);
  var tax = Number(parent.find('.taxamount').text());
  if(tax == 0 || title != 'taxamount'){//if tax amount is set 0 calculate by tax percent and calculate tax amount
    let taxpercent = parent.find('.tax').text();
    tax = (taxpercent/100) * total_sal;
    parent.find('.taxamount').editable('setValue',tax);
    calculateTotalRow('taxamount');
  }
  let total_sal_with_tax = total_sal - tax;
  parent.find('.total').text(total_sal_with_tax);
  var totalamount = 0;
  $('.total').each(function(){
    totalamount += Number($(this).text()); 
  });
  var totalwithouttax = 0;
  $('.totalwithouttax').each(function(){
    totalwithouttax += Number($(this).text());
  });
  $('#totalamountwithouttax').text(totalwithouttax);
  $('#totalamount').text(totalamount);
  return 0;
}
function calculateTotalRow(id){
  var totalamount = 0;
  $('.'+id).each(function(){
    totalamount += Number($(this).text()); 
  });
  $('#total'+id).text(totalamount);
}
function calculateTaxAmount(parent){
  let tax = parent.find('.tax').text();
  let total_sal = empsal_without_tax(parent);
  let taxamount = total_sal * Number(tax / 100);
  parent.find('.taxamount').editable('setValue',taxamount);
  return 0;
}
function calculateTaxPercent(parent){
  let tax = parent.find('.taxamount').text();
  let total_sal = empsal_without_tax(parent);
  if( total_sal !=0){
    let taxpercent = (tax / Number(total_sal)) * 100;
    parent.find('.tax').editable('setValue',taxpercent.toFixed(2));
  }
  return 0;
}

function submitpayroll(runtype){
  let c = confirm('Are You Sure Information Is correct');
  if(!c){
    return false;
  }
  var data = [];
  $('.payrolldetails').each(function(){
    let parent = $(this);
    let obj = {
      user_id:parent.find('.user_id').text(),
      regular_salary:parent.find('.regular').text(),
      overtime_salary:parent.find('.overtime').text(),
      gross_salary:parent.find('.totalwithouttax').text(),
      tax_percent: parent.find('.tax').text(),
      tax_amount: parent.find('.taxamount').text(),
      net_salary:parent.find('.total').text(),
      remarks:parent.find('.payrollremarks').text().trim(),
    }
    data.push(obj);
  });
  let jsondata = JSON.stringify(data);
  let totalamount = $('#totalamount').text();
  $('<input type="hidden" name="data"/>').val(jsondata).appendTo('#runpayroll');
  $('<input type="hidden" name="totalamount"/>').val(totalamount).appendTo('#runpayroll');
   $('<input type="hidden" name="runtype"/>').val(runtype).appendTo('#runpayroll');
  $("#runpayroll").submit();
  return false;
}
$('.payrollremarks').each(function(){
  $(this).editable({
    type:'textarea',
    pk:1,
    url:null,
    title: `Salary Remarks`,
  })
});
$(document).ready(function(){
$('.editable').each(function(){
  var parent = $(this).parent().parent();
  var title = $(this).attr("class").split(/\s+/);
  $(this).editable({
      type: 'text',
      pk: 1,
      url: null,
      title: `Adjust ${title[1]}`,
      success: function(response, newValue) {
        setTimeout(function(){
          if(title[1].trim() == 'tax'){
            calculateTaxAmount(parent);
          }
          else if(title[1].trim() == 'taxamount'){
            calculateTaxPercent(parent);
          }
          calculateTotalColumn(parent,title[1]);
          calculateTotalRow(title[1]);
        });
      },
      validate: function(value) {
          if ($.isNumeric(value) == '') {
              return 'Only Numberical value is allowed';
          }
      }
    });
});

$(document).ready(function(){
  $("#searchbar").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#searchabletable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

});

</script>
@endsection