@extends('layouts.master')
@section('content')



<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
           <h1> Purchase Book </h1>
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
                    <li><a href="/admin/purchase-book-bymonth/01">Baishakh</a></li>
                    <li><a href="/admin/purchase-book-bymonth/02">Jesth</a></li>
                    <li><a href="/admin/purchase-book-bymonth/03">Asar</a></li>
                    <li><a href="/admin/purchase-book-bymonth/04">Shrawan</a></li>
                    <li class="divider"></li>
                    <li><a href="/admin/purchase-book-bymonth/05">Bhadra</a></li>
                    <li><a href="/admin/purchase-book-bymonth/06">Asoj</a></li>
                    <li><a href="/admin/purchase-book-bymonth/07">Kartik</a></li>
                    <li><a href="/admin/purchase-book-bymonth/08">Mangsir</a></li>
                    <li class="divider"></li>
                    <li><a href="/admin/purchase-book-bymonth/09">Push</a></li>
                    <li><a href="/admin/purchase-book-bymonth/10">Magh</a></li>
                    <li><a href="/admin/purchase-book-bymonth/11">Falgun</a></li>
                    <li><a href="/admin/purchase-book-bymonth/12">Chaitra</a></li>
                  </ul>
                </div>
                <br/>


        </section>




      <div class='row'>
      <div class='col-md-12'>

      <div class="box box-primary">
    <div class="box-header with-border">


        <div class="col-md-5 pull-left">
          <form method="GET" action="/admin/purchase-book/">
           <div class="col-md-4">
           <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" placeholder="Start Date" class="form-control input-sm startdate" name="engstartdate" value="{{\Request::get('engstartdate')}}">
                </div>
            </div>

             <div class="col-md-4">
           <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" placeholder="End Date" class="form-control input-sm enddate" name="engenddate" value="{{\Request::get('engenddate')}}">
                </div>
            </div>


            <button type="submit" class="btn btn-primary btn-sm" name="filter" value="eng">Show Bills</button>
          </form>
            </div>
        <div class="col-md-2">
            <label>
                {!! Form::checkbox('greater_than_1_lakh', 'true',\Request::get('greater_than_1_lakh')) !!}
                Only > 1 Lakh
            </label>
        </div>

            <div class="col-md-5 pull-right">


            <div class="col-md-4">
           <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  {{-- <input type="text" name="nepdate" class="form-control"> --}}
                  <input type="text" placeholder="सुरु  मिति " class="form-control input-sm " id="nep-startdate" name="nepstartdate" value="">
                </div>
            </div>

             <div class="col-md-4">
           <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" placeholder="अन्तिम मिति" class="form-control input-sm" id="nep-enddate" name="nependdate" value="">
                </div>
            </div>


            <button type="submit" id="btn-submit-filter-nep" class="btn btn-success btn-sm" value="nep" name="filter" style="white-space: nowrap;">नतिजा </button>

            <a href="/admin/purchase-book/" class="btn btn-success btn-sm" id ="btn-filter-clear">Reset</a>
            </div>

</div>
<table class="table table-hover table-bordered table-stripped" id="leads-table" cellspacing="0" width="100%">
<thead>
    <tr>

        <th colspan="3" style="text-align: center; background-color: #eee" >Invoice</th>
        <th colspan="5"></th>
        <th colspan="2" style="text-align: center;  background-color: #eee">Taxable Purchase</th>

    </tr>
    <tr class="bg-olive">
            <th>Date</th>
        <th>Bill No</th>
        <th>Supplier’s Name</th>
        <th>Supl. PAN Number</th>
        <th>Total Purchase</th>
        <th>Non Tax Purchase</th>
        <th>Exp. Purchase</th>
        <th>Discount</th>
        <th>Amount</th>
        <th>Tax(Rs)</th>

    </tr>
</thead>
<tbody>
  <?php
  $taxable_amount = 0;
  $tax_amount = 0;
  $total_purch = 0;
  $totalNontaxPurch = 0;
  $totalDiscount= 0;
  ?>
    @foreach($purchase_book as $pur_bks)
<tr>
      <td>{{ date('dS M y', strtotime($pur_bks->bill_date)) }}<br/>
                              <?php
                                        $temp_date = explode(" ",$pur_bks->bill_date );
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
    <td>{{$pur_bks->bill_no}} </td>
    <td>{{$pur_bks->client->name}}</td>
        <td>{{$pur_bks->client->vat}}</td>
    <td>


      {{-- {{$pur_bks->taxable_amount + round($pur_bks->tax_amount,2) }} --}}
      {{$pur_bks->total}}


    </td>
    <td>{{ $pur_bks->non_taxable_amount }}</td>
    <td></td>
    <td>{{$pur_bks->discount_amount}}</td>
    <td>{{$pur_bks->tax_amount?$pur_bks->taxable_amount:$pur_bks->non_taxable_amount??0}}</td>
    <?php
      $taxable_amount += $pur_bks->taxable_amount;
      $tax_amount = $tax_amount +  $pur_bks->tax_amount;
      $total_purch  +=  $pur_bks->total;
      $totalNontaxPurch += $pur_bks->non_taxable_amount;
      $totalDiscount += $pur_bks->discount_amount;
    ?>
 <td>{{ round($pur_bks->tax_amount,2) }}</td>
</tr>
@endforeach
<tr>
    <th>Total Amount</th>
    <td></td>
     <td></td>
      <td></td>
       <td>{{ $total_purch }}</td>
        <td>{{ $totalNontaxPurch }}</td>
         <td></td>
          <td>{{ $totalDiscount }}</td>
    <td>{{$taxable_amount}}</td>
    <td>{{$tax_amount}}</td>
    </tr>

</tbody>
</table>
</div>

@endsection

@section('body_bottom')
<link rel="stylesheet" type="text/css" href="/nepali-date-picker/nepali-date-picker.min.css">
<script src="/nepali-date-picker/nepali-date-picker.js"></script>

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
      $(function() {
$("#nep-startdate").nepaliDatePicker({
    dateFormat: "%y-%m-%d",
    closeOnDateSelect: true
})
      });
      $(function() {
$("#nep-enddate").nepaliDatePicker({
    dateFormat:"%y-%m-%d",
    closeOnDateSelect: true
})});
$("#btn-submit-filter-nep").on("click", function() {
nepstartdate = $("#nep-startdate").val();
nependdate = $("#nep-enddate").val();
filter = $("#btn-submit-filter-nep").val();
window.location.href = "{!! url('/') !!}/admin/purchase-book?nepstartdate=" + nepstartdate + "&nependdate=" + nependdate + "&filter=" + filter;
});
$("#btn-filter-clear").on("click", function () {
  window.location.href = "{!! url('/') !!}/admin/purchase-book/";
});
</script>
@endsection
