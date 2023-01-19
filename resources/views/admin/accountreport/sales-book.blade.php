@extends('layouts.master')
@section('content')

 
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
           <h1> Sales Book </h1>
<span class="pull-right">Fiscal Year: {{$fiscal_year}}</span>
<div class="btn-group btn-xs pull-right">
    <?php 
      $url = \Request::query();
      if($url){
        $url = \Request::getRequestUri() .'&';
      }
      else{
        $url = \Request::getRequestUri() .'?';
      }
    ?>

    <a href="{{$url}}op=pdf"  class="btn btn-success btn-xs"> <i class ="fa fa-download"></i>Pdf
    </a>&nbsp;&nbsp;
      <a href="{{$url}}op=excel"  class="btn btn-primary btn-xs"><i class ="fa fa-print"></i>Excel</a>&nbsp;&nbsp;
                  <button type="button" class="btn btn-danger btn-xs">Monthly Report</button>
                  <button type="button" class="btn btn-danger dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="/admin/sales-book-bymonth/01">Baishakh</a></li>
                    <li><a href="/admin/sales-book-bymonth/02">Jesth</a></li>
                    <li><a href="/admin/sales-book-bymonth/03">Asar</a></li>
                    <li><a href="/admin/sales-book-bymonth/04">Shrawan</a></li>
                    <li class="divider"></li>
                    <li><a href="/admin/sales-book-bymonth/05">Bhadra</a></li>
                    <li><a href="/admin/sales-book-bymonth/06">Asoj</a></li>
                    <li><a href="/admin/sales-book-bymonth/07">Kartik</a></li>
                    <li><a href="/admin/sales-book-bymonth/08">Mangsir</a></li>
                    <li class="divider"></li>
                    <li><a href="/admin/sales-book-bymonth/09">Push</a></li>
                    <li><a href="/admin/sales-book-bymonth/10">Magh</a></li>
                    <li><a href="/admin/sales-book-bymonth/11">Falgun</a></li>
                    <li><a href="/admin/sales-book-bymonth/12">Chaitra</a></li>
                  </ul>
                </div>
                <br/>

          
        </section>



<form method="GET" action="/admin/sales-book/">
       <div class='row'>
        <div class='col-md-12'>

              <div class="box box-primary">
    <div class="box-header with-border">
        
        
        <div class="col-md-6 pull-left">   
           <div class="col-md-4">
           <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" placeholder="Start Date" class="form-control input-sm startdate" name='engstartdate'>
                </div>
            </div>

             <div class="col-md-4">
           <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" placeholder="End Date" class="form-control input-sm enddate" name='engenddate'>
                </div>
            </div>


            <button type="submit" class="btn btn-primary btn-sm" name="filter" >Show Bills</button>
            </div>

            <div class="col-md-6 pull-right">  


            <div class="col-md-4">
           <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" placeholder="सुरु  मिति " class="form-control input-sm" id='nep-startdate' name="nepstartdate">
                </div>
            </div>

             <div class="col-md-4">
           <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" placeholder="अन्तिम मिति" class="form-control input-sm" id ='nep-enddate' name="nependdate">
                </div>
            </div>


            <button type="submit" class="btn btn-success btn-sm" name="filter" value="nep">नतिजा </button>
            <button type="submit" class="btn btn-success btn-sm" id='btn-filter-clear'>Reset</button>
            </div>
        
</div>
   </form>
<table class="table table-hover table-bordered table-stripped" id="leads-table">
<thead>
    <tr>
        <th colspan="3" style="text-align: center; background-color: #eee" >Invoice</th>
        <th colspan="5"></th>
        <th colspan="2" style="text-align: center;  background-color: #eee">Taxable Purchase</th>
    </tr>
    <tr>
        <th>Bill Date</th>
        <th>Bill No</th>
        <th>Customer's Name</th>
        <th>Cust. PAN Number</th>
        <th>Total Sales</th>
        <th>Non Tax Sales</th>
        <th>Export Sales</th>
        <th>Discount</th> 
        <th>Amount</th>
        <th>Tax(Rs)</th>

    </tr>
</thead>
<tbody>
   <?php  
   $total_amount =0 ;
  $taxable_amount = 0;
  $tax_amount = 0;
  ?>
    @foreach($sales_book as $sal_bks)
<tr>

    <td>{{ date('dS M y', strtotime($sal_bks->bill_date)) }}/<br/>
                              <?php
                                        $temp_date = explode(" ",$sal_bks->bill_date );
                                        $temp_date1 = explode("-",$temp_date[0]);
                                        $cal = new \App\Helpers\NepaliCalendar();
                                        //nepali date
                                        $a = $temp_date1[0];
                                        $b = $temp_date1[1];
                                        $c = $temp_date1[2];
                                        $d = $cal->eng_to_nep($a,$b,$c);
                                    
                                         $nepali_date = $d['date'].' '.$d['nmonth'] .', '.$d['year'];
                                        ?>

                         <small> {!! $nepali_date !!}</small>

                        </td>
    <!-- <td>{{env('SALES_BILL_PREFIX')}}{{$sal_bks->bill_no}} </td> -->
    <td>{{$sal_bks->bill_no}} </td>
    <td>@if($sal_bks->client){{$sal_bks->client->name}}@else{{$sal_bks->name}}@endif</td>
        <td>@if($sal_bks->client){{$sal_bks->client->vat}}@else {{$sal_bks->customer_pan}} @endif</td>
    <td>{{$sal_bks->total_amount}}</td>
    <td></td>
    <td></td>
    <td></td>
    <td>{{$sal_bks->taxable_amount}}</td>
 <td>{{$sal_bks->tax_amount}}</td>
</tr>
 <?php  
      $taxable_amount = $taxable_amount + $sal_bks->taxable_amount;
      $tax_amount = $tax_amount +  $sal_bks->tax_amount;
       $total_amount = $total_amount +  $sal_bks->total_amount;
    ?>

  @if($sal_bks->invoicemeta->is_bill_active === 0)

  <tr>
    <td>{{ date('dS M y', strtotime($sal_bks->bill_date)) }}/<br/>
                              <?php
                                        $temp_date = explode(" ",$sal_bks->bill_date );
                                        $temp_date1 = explode("-",$temp_date[0]);
                                        $cal = new \App\Helpers\NepaliCalendar();
                                        //nepali date
                                        $a = $temp_date1[0];
                                        $b = $temp_date1[1];
                                        $c = $temp_date1[2];
                                        $d = $cal->eng_to_nep($a,$b,$c);
                                    
                                         $nepali_date = $d['date'].' '.$d['nmonth'] .', '.$d['year'];
                                        ?>
                         <small> {!! $nepali_date !!}</small>

                        </td>
    <td>Ref of {{env('SALES_BILL_PREFIX')}}{{$sal_bks->bill_no}} CN {{$sal_bks->invoicemeta->credit_note_no}}</td>
   <td>@if($sal_bks->client){{$sal_bks->client->name}}@else{{$sal_bks->name}}@endif</td>
        <td>@if($sal_bks->client){{$sal_bks->client->vat}}@else {{$sal_bks->customer_pan}} @endif</td>
    <td>-{{$sal_bks->total_amount}}</td>
    <td></td>
    <td></td>
    <td></td>
    <td>-{{$sal_bks->taxable_amount}}</td>
 <td>-{{$sal_bks->tax_amount}}</td>
</tr>

 <?php  
      $taxable_amount = $taxable_amount- $sal_bks->taxable_amount;
      $tax_amount = $tax_amount-  $sal_bks->tax_amount;
       $total_amount = $total_amount -$sal_bks->total_amount;
    ?>

  @endif
@endforeach
<tr>
    <th>Total Amount</th>
    <td></td>
     <td></td>
      <td></td>
       <td>{{number_format($total_amount,2)}}</td>
        <td></td>
         <td></td>
          <td></td>
    <td>{{number_format($taxable_amount,2)}}</td>
    <td>{{number_format($tax_amount,2)}}</td>
    </tr>

</tbody>
</table>

</div>

@endsection
@section('body_bottom')
 <script type="text/javascript" src="https://nepali-date-picker.herokuapp.com/src/jquery.nepaliDatePicker.js"> </script>
<link rel="stylesheet" href="https://nepali-date-picker.herokuapp.com/src/nepaliDatePicker.css">
    <!-- form submit -->
    @include('partials._body_bottom_submit_bug_edit_form_js')
    <script type="text/javascript">
     $(function() {
        $('.startdate').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true,
         
        });

      });
       $(function() {
        $('.enddate').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true,
         
        });

      });
$("#nep-startdate").nepaliDatePicker({
    dateFormat: "%y-%m-%d",
    closeOnDateSelect: true
});
$("#nep-enddate").nepaliDatePicker({
    dateFormat:"%y-%m-%d",
    closeOnDateSelect: true
});
$("#btn-filter-clear").on("click", function () {
  window.location.href = "{!! url('/') !!}/admin/sales-book/";
});
</script>
    @endsection