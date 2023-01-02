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
                    <th colspan="2" style="text-align: center" class="bg-success">Employee Info</th>
                    <th colspan="4" style="text-align: center" class="bg-primary">Working Hours Salaries</th>
                    <th>Allowance</th>
                    <th colspan="2" style="text-align: center" class="bg-info">Holiday Works</th>
                    <th colspan="4" style="text-align: center;" class="bg-info">Leave Salaries</th>
                    <th colspan="1" style="text-align: center" class="bg-primary">Gross Salary</th>
                    <th>Deduction</th>
                    <th colspan="2" style="text-align: center" class="bg-warning">Tax</th>
                    <th style="text-align: center" class="bg-primary">Net Salary</th>
                    <th style="text-align: center;" class="bg-success">Remarks</th>
                  </tr>
                </thead>
                <tr>
                  <th>Emp ID</th>
                  <th>User</th>
                  <th>Basic Salary</th>
                  <th>Regular</th>
                  <th>Gratuity Salary</th>
                  <th>Overtime</th>
                  <th>Total Allowance</th>
                  <th>Weekend</th>
                  <th>Public</th>
                  <th>Sick</th>
                  <th>Anual</th>
                  <th>Public</th>
                  <th>Others</th>
                 
                  <th>Total Without Tax</th>
                   <th>Total Deduction</th>
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
                  <td><span class="label basic_salary  label-success">{{$salary->basic_salary}}</span></td>
                  <td class="edit">
                    <span class="editable regular">{{$salary->regular_salary}}</span>
                  </td>
                  <td class="edit">
                    <span class="editable gratuity_salary">
                      {{ $salary->gratuity_salary ?? 0}}</span>
                  </td>
                  <td class="edit">
                    <span class="editable overtime">{{$salary->overtime_salary}}</span>
                  </td>
                  <td>
                    <span id="total_allowance_{{  $salary->user_id }}" class="total_allowance">
                      {{$salary->total_allowance}}
                    </span>
                    <span>&nbsp;
                      <a href="/admin/payroll/allowance/{{$payfrequency->id}}/{{ $salary->user_id }}" title="View All Allowance" data-toggle="modal" data-target="#modal_dialog">
                        <i class="fa fa-edit"></i></a>
                    </span>
                    <input type="hidden" class='allowance_record_json' id="allowance_record_json_{{ $salary->user_id }}" value="{{$salary->total_allowance_json}}">
                  </td>
                  <td class="edit">
                      <span class="editable weekend">{{$salary->weekend_salary}}</span>
                  </td>
                    <td class="edit">
                    <span class="editable public_holiday_work_salary">{{$salary->public_holiday_work_salary}}</span>
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
                 
                  <td class="totalwithouttax">{{$salary->gross_salary}}</td>
                   <td>
                    <span id="total_deduction_{{  $salary->user_id }}" class="total_deduction">
                      {{$salary->total_deduction}}
                    </span>
                    <span>&nbsp;
                      <a href="/admin/payroll/deduction/{{$payfrequency->id}}/{{ $salary->user_id }}" title="View All Allowance" data-toggle="modal" data-target="#modal_dialog">
                        <i class="fa fa-edit"></i></a>
                    </span>
                    <input type="hidden" class='deduction_record_json' id="deduction_record_json_{{ $salary->user_id }}" value="{{$salary->total_deduction_json}}">
                  </td>
                  <td class="edit"><span class="editable tax">{{$salary->tax_percent}}</span>%</td>
                  <td class="edit"><span class="editable taxamount">{{$salary->tax_amount}}</span></td>
                  <td  style="width: 10%">
                    <span class="total">{{$salary->net_salary}}</span>
                  </td>
                  <td title="{{$salary->remarks}}">
                     <span class="payrollremarks" style="max-width: 60px; max-height: 20px; position: relative; text-overflow: ellipsis; overflow: hidden;">
                      {{$salary->remarks}}
                    </span>
                  </td>
                  <?php
                    $totalarr['regular'] +=$salary->regular_salary;
                    $totalarr['allowance'] += $salary->total_allowance;
                    $totalarr['deduction'] += $salary->total_deduction;
                    $totalarr['overtime'] +=  $salary->overtime_salary;
                    $totalarr['weekend'] += $salary->weekend_salary;
                    $totalarr['public_holiday_work'] += $salary->public_holiday_work_salary;
                    $totalarr['sick'] +=  $salary->sick_salary;
                    $totalarr['anual'] +=  $salary->annual_leave_salary;
                    $totalarr['public'] +=   $salary->public_holiday_salary;
                    $totalarr['other'] +=   $salary->other_leave_salary;
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
                  <td><span class="label basic_salary  label-success">{{$d['basic_salary']}}</span></td>

                  <td class="edit">
                    <span class="editable regular">{{$d['regular_salary']}}</span>
                  </td>
                  <td class="edit">
                    <span class="editable gratuity_salary">
                      {{$d['gratuity_salary']}}
                    </span>
                  </td>
                  <td class="edit">
                    <span class="editable overtime">{{$d['overtime_salary']}}</span>
                  </td>
                  <td>
                    <span id="total_allowance_{{  $d['user']->id }}" class="total_allowance">
                      {{ $d['total_allowance'] }}
                    </span>
                    <span>&nbsp;
                      <a href="/admin/payroll/allowance/{{$payfrequency->id}}/{{ $d['user']->id }}" title="View All Allowance" data-toggle="modal" data-target="#modal_dialog">
                        <i class="fa fa-edit"></i></a>
                    </span>
                    <input type="hidden" class='allowance_record_json' id="allowance_record_json_{{ $d['user']->id }}" value="">
                  </td>
                  <td class="edit">
                    <span class="editable weekend">{{$d['weekend_salary']}}</span>
                  </td>
                  <td class="edit">
                    <span class='editable public_holiday_work_salary'>{{$d['public_holiday_work_salary']}}</span>
                  </td>
                  <td class="edit">
                    <span class='editable sick_leave'>{{$d['sick_salary']}}</span>
                  </td>
                  <td class="edit"> 
                   <span class='editable annual_leave'>{{$d['annual_leave_salary']}}</span>
                  </td>
                  <td class="edit"> 
                    <span class='editable public_holidays'>{{$d['public_holiday_salary']}}</span>
                  </td>
                  <td class="edit">
                   <span class='editable others_leave'>{{$d['other_leave_salary']}}</span>
                  </td>
                  
                  <td class="totalwithouttax">{{$d['totalwithouttax']}}</td>
                  <td>
                    <span id="total_deduction_{{  $d['user']->id }}" class="total_deduction">
                      {{ $d['total_deduction'] }}
                    </span>
                    <span>&nbsp;
                      <a href="/admin/payroll/deduction/{{$payfrequency->id}}/{{ $d['user']->id }}" title="View All deduction" data-toggle="modal" data-target="#modal_dialog">
                        <i class="fa fa-edit"></i></a>
                    </span>
                    <input type="hidden" class='deduction_record_json' id="deduction_record_json_{{ $d['user']->id }}" value="">
                  </td>
                  <td class="edit"><span class="editable tax">{{$d['tax']}}</span>%</td>
                  <td class="edit"><span class="editable taxamount">{{$d['taxamount']}}</span></td>
                  <td  style="width: 10%">
                    <span class="total">{{$d['total']}}</span>
                  </td>
                  <td >
                  <span class="payrollremarks" style="max-width: 60px; max-height: 20px; position: relative; text-overflow: ellipsis; overflow: hidden;"></span>
                  </td>
                </tr>
                @endforeach

                @endif
                  </tbody>
                  <td>Total</td>
                  <td></td>
                  <td></td>
                  <td>NPR <span id='totalregular'>{{$totalarr['regular']}}</span></td>
                  <td></td>
                  <td>NPR <span id='totalovertime'>{{$totalarr['overtime']}}</span></td>
                  <td>NPR <span id='totaltotal_allowance'>{{$totalarr['allowance']}}</td>
                  <td>NPR <span id='totalweekend'>{{ $totalarr['weekend'] }}</span></td>
                  <td>NPR <span id='totalpublic_holiday_work'>{{ $totalarr['public_holiday_work'] }}</span></td>
                  <td>NPR <span id='totalsick_leave'>{{$totalarr['sick']}}</span></td>
                  <td>NPR <span id='totalannual_leave'>{{$totalarr['anual']}}</span></td>
                  <td>NPR <span id='totalpublic_holidays'>{{$totalarr['public']}}</span></td>
                  <td>NPR <span id='totalothers_leave'>{{$totalarr['other']}}</span></td>
                  <td>NPR <span id='totalamountwithouttax'>{{$totalarr['totalamountwithouttax']}}</span></td>
                  <td>NPR <span id='totaltotal_deduction'>{{$totalarr['deduction']}}</td>
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
  let gratuity_salary = parent.find('.gratuity_salary').text();
  let overtime = parent.find('.overtime').text();
  let allowance = parent.find('.total_allowance').text();
  let sick_leave = parent.find('.sick_leave').text();
  let weekend = parent.find('.weekend').text();
  let deduction = parent.find('.total_deduction').text();
  let public_holiday_work_salary = parent.find('.public_holiday_work_salary').text();
  let annual_leave = parent.find('.annual_leave').text();
  let public_holidays = parent.find('.public_holidays').text();
  let others = parent.find('.others_leave').text();
  let absent_sal = Number(sick_leave) + Number(annual_leave) + Number(public_holidays) + Number(others);
  let total_sal = Number(regular) + Number(overtime) + Number(allowance) + Number(weekend)+Number(public_holiday_work_salary)+ absent_sal - Number(deduction) + 
  Number(gratuity_salary);
  return total_sal;
}
function calculateTotalColumn(parent,title){
  var total_sal = empsal_without_tax(parent);
  parent.find('.totalwithouttax').text(total_sal);
  var tax = Number(parent.find('.taxamount').text());
  if(tax == 0 || title != 'taxamount'){//if tax amount is set 0 calculate by tax percent and calculate tax amount
    let taxpercent = parent.find('.tax').text();
    tax = (taxpercent/100) * total_sal;
    parent.find('.taxamount').editable('setValue',tax.toFixed(2));
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
  $('#totalamountwithouttax').text(totalwithouttax.toFixed(2));
  $('#totalamount').text(totalamount.toFixed(2));
  return 0;
}
function calculateTotalRow(id){
  var totalamount = 0;
  $('.'+id).each(function(){
    totalamount += Number($(this).text()); 
  });
  $('#total'+id).text(totalamount.toFixed(2)
    );
}
function calculateTaxAmount(parent){
  let tax = parent.find('.tax').text();
  let total_sal = empsal_without_tax(parent);
  let taxamount = total_sal * Number(tax / 100);
  parent.find('.taxamount').editable('setValue',taxamount.toFixed(2));
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
  let c = confirm('Are You Sure Information Is correct ');
  if(!c){
    return false;
  }
  var data = [];
  $('.payrolldetails').each(function(){
    let parent = $(this);
    let obj = {
      user_id:parent.find('.user_id').text(),
      basic_salary:parent.find('.basic_salary').text(),
      regular_salary:parent.find('.regular').text(),
      weekend_salary: parent.find('.weekend').text(),
      public_holiday_work_salary: parent.find('.public_holiday_work_salary').text(),
      overtime_salary:parent.find('.overtime').text(),
      sick_salary:parent.find('.sick_leave').text(),
      annual_leave_salary: parent.find('.annual_leave').text(),
      public_holiday_salary: parent.find('.public_holidays').text(),
      other_leave_salary: parent.find('.others_leave').text(),
      gross_salary:parent.find('.totalwithouttax').text(),
      tax_percent: parent.find('.tax').text(),
      tax_amount: parent.find('.taxamount').text(),
      net_salary:parent.find('.total').text(),
      remarks: parent.find('.payrollremarks').text().trim(),
      total_allowance: parent.find('.total_allowance').text().trim(),
      total_allowance_json: parent.find('.allowance_record_json').val() || null,
      total_deduction: parent.find('.total_deduction').text().trim(),
      total_deduction_json: parent.find('.deduction_record_json').val() || null,
      gratuity_salary: parent.find('.gratuity_salary').text(),
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


$(document).on('click','#user_allowance_customize',function(){
    var allowance = [];
    var this_user_id = $("#user_allowance_customize_form input[name='user_id']").val();
    var _total_allowance = 0;
    alert("DONE");
    $('#user_allowance_customize_form  .allowance_label').each(function(){
      let parent = $(this).parent();
      let value = parent.find('.allowance_value').val();
      let obj = {
        allowance_label: $(this).text(),
        allowance_value: value,
         formatted_label: parent.find('.formatted_label').val(),
      }
      _total_allowance= _total_allowance + Number(value);
      allowance.push(obj);
    });
    allowance = JSON.stringify(allowance);
    $(`#allowance_record_json_${this_user_id}`).val(allowance);
    $(`#total_allowance_${this_user_id}`).text(_total_allowance);
    let parent = $(`#total_allowance_${this_user_id}`).parent().parent();
    calculateTotalColumn(parent,'total_allowance');
    calculateTotalRow('total_allowance');
    $('#modal_dialog').modal('hide');
    return false;
  });

$(document).on('click','#user_deduction_customize',function(){
    var deduction = [];
    var this_user_id = $("#user_deduction_customize_form input[name='user_id']").val();
    var _total_deduction = 0;
    $('#user_deduction_customize_form  .deduction_label').each(function(){
      let parent = $(this).parent();
      let value = parent.find('.deduction_value').val();
      let obj = {
        deduction_label: $(this).text(),
        deduction_value: value,
        formatted_label: parent.find('.formatted_label').val(),
      }
      _total_deduction= _total_deduction + Number(value);
      deduction.push(obj);
    });
    deduction = JSON.stringify(deduction);
    $(`#deduction_record_json_${this_user_id}`).val(deduction);
    $(`#total_deduction_${this_user_id}`).text(_total_deduction);
    let parent = $(`#total_deduction_${this_user_id}`).parent().parent();
    calculateTotalColumn(parent,'total_deduction');
    calculateTotalRow('total_deduction');
    $('#modal_dialog').modal('hide');
    return false;
  });

});

});
$(document).on('hidden.bs.modal', '#modal_dialog' , function(e){
    $('#modal_dialog .modal-content').html('');    
});
$(document).on('shown.bs.modal', '#modal_dialog' , function(e){
  let user_id_allowace = $('#user_allowance_customize_form input[name=user_id]').val();
  let user_id_deduction = $('#user_deduction_customize_form input[name=user_id]').val();
  var deduction = $(`#deduction_record_json_${user_id_deduction}`).val();
  var allowance = $(`#allowance_record_json_${user_id_allowace}`).val();
  if(deduction){
    deduction = JSON.parse(deduction);
    for(let d of deduction){
        $(`#user_deduction_customize_form  .${d.formatted_label}`).val(d.deduction_value);
    }
   
  }
  if(allowance){
    allowance = JSON.parse(allowance);
    for(let d of allowance){
        $(`#user_allowance_customize_form  .${d.formatted_label}`).val(d.allowance_value);
    }
    console.log(allowance);
  }
});
</script>
@endsection