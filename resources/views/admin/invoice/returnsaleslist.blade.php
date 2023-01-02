@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{$page_title ?? "Page Title"}}
        <small>{{$description}}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
    <p> To Print Credit Note advice click credit note # column</p>
</section>

<form action="{{route('admin.invoice.return.sales.list')}}" method="get">

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-custom">

                <div class="panel-heading">


                    <div class="row">
                        <div class="col-md-6">
                            <label>Start Date</label>
                            <div class="form-group">
                                <input type="text" name="start_date" class="form-control datepicker date-toogle" required="" placeholder="Start Date" value="{{$request['start_date'] ?? \Carbon\Carbon::today()->format('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Start Time</label>
                            <div class="form-group">
                                <input type="text" name="start_time" class="form-control timepicker" required="" placeholder="Start time" value="{{$request['start_time'] ?? date('h:i')}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>End Date</label>
                            <div class="form-group">
                                <input type="text" name="end_date" class="form-control datepicker date-toogle" required="" placeholder="End Date" value="{{$request['end_date'] ?? \Carbon\Carbon::today()->format('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>End Time</label>
                            <div class="form-group">
                                <input type="text" name="end_time" class="form-control timepicker" required="" placeholder="End Date" value="{{$request['end_time'] ?? date('h:i')}}">
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                            <label>Users</label>
                            <div class="form-group">
                                {!! Form::select('user_id',$users,isset($request['user_id']), ['class'=>'form-control searchable','placeholder'=>'Select Users']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Bill Type</label>
                            <div class="form-group">
                                <select class="form-control" name='bill_type'>
                                    <option value="tax">Tax Bills</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Load</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php 
      $url = \Request::query();
      if($url){
        $url = \Request::getRequestUri() .'&';
      }
      else{
        $url = \Request::getRequestUri() .'?';
      }
    ?>


@if(isset($invoice))
<div class="panel panel-custom">
    <div class="row">
        <div class="col-md-12">
            <a href="{{$url}}op=print" class="btn btn-default">Print</a>
            <a href="{{$url}}op=pdf" class="btn btn-default">PDF</a>
            <table class="table" id='filter-table'>
                <thead>
                    <th style="text-align: center">
                        SN
                    </th>
                    <th>Fiscal Year</th>
                    <th>Bill Date</th>
                    <th>Ref Bill No</th>
                    <th>Cancel Date</th>
                    <th>Credit Note No</th>
                    <th>Cancel Reason</th>
                    <th>Guest Name</th>
                    <th>Guest PAN</th>
                    <th>Total Sales</th>
                    <th>Non Tax Sale</th>
                    <th>Export Sale</th>
                    <th>Taxable Amount</th>
                    <th>TAX</th>
                </thead>
                <tbody>
                    <?php
            $n = 0;
                $pos_total_amount = 0;
                $pos_taxable_amount = 0;
                $pos_tax_amount = 0;

           ?>
                    @if(isset($invoice) && !empty($invoice))
                    @foreach($invoice as $o)
                    <tr>
                        <td align="center">{{++$n}}</td>
                        <td>{{$o->fiscal_year}}</td>
                        <td>{{$o->bill_date}}
                            <?php
                                $temp_date = explode(" ",$o->bill_date );
                                $temp_date1 = explode("-",$temp_date[0]);
                                $cal = new \App\Helpers\NepaliCalendar();
                                //nepali date
                                $a = $temp_date1[0];
                                $b = $temp_date1[1];
                                $c = $temp_date1[2];
                                $d = $cal->eng_to_nep($a,$b,$c);
                                 $nepali_date = $d['date'].' '.$d['nmonth'] .', '.$d['year'];
                                ?><br>
                            <small> {!! $nepali_date !!}</small></td>

                        <td>{{env('SALES_BILL_PREFIX')}}{{$o->bill_no}}</td>
                        <td>{{$o->cancel_date}}</td>
                        <td><a href='/admin/credit_note/orders/show/{{$o->id}}' data-toggle="modal" data-target="#modal_dialog">
                                CN {{$o->credit_note_no}}</a>
                        </td>
                        <td>{{$o->void_reason}}</td>

                        <td>@if($o->client_id){{$o->client->name}} @else {{$o->name}} @endif</td>


                        <td>@if($o->client_id) {{$o->client->vat}} @else {{$o->customer_pan}} @endif</td>
                        <td>{{ number_format($o->total_amount,2)}}</td>
                        <td></td>
                        <td></td>
                        <td>{!! number_format($o->taxable_amount,2) !!}</td>
                        <td>{!! number_format($o->tax_amount,2) !!}</td>
                        <?php 
                                  $pos_taxable_amount = $pos_taxable_amount+$o->taxable_amount;
                                  $pos_total_amount = $pos_total_amount + $o->total_amount;
                                  $pos_tax_amount = $pos_tax_amount+$o->tax_amount; ?>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <td colspan="8">
                    </td>
                    <td>
                        Total Amount:
                    </td>
                    <td> <strong> {{env(APP_CURRENCY)}} {{ number_format($pos_total_amount,2) }} </strong> </td>
                    <td colspan="2"></td>
                    <td>
                        <strong> {{env(APP_CURRENCY)}} {{ number_format($pos_taxable_amount,2) }} </strong>
                    </td>
                    <td>
                        <strong> {{env(APP_CURRENCY)}} {{ number_format($pos_tax_amount,2) }} </strong>
                    </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div style="text-align: center;"> {!! $invoice->appends(\Request::except('page'))->render() !!} </div>
</div>
</div>
@endif



@endsection

@section('body_bottom')
@include('partials._date-toggle')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />


<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script type="text/javascript">
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
        , sideBySide: true
    , });

    $('.timepicker').datetimepicker({
        //inline: true,
        //format: 'YYYY-MM-DD',
        format: 'HH:mm'
        , sideBySide: true
    });
    $('.date-toogle').nepalidatetoggle();


    $('.searchable').select2();
    //  $(function() {
    //     $('#filter-table').DataTable({
    //         pageLength: 25,
    //          buttons: [
    //         'copy', 'csv', 'excel', 'pdf', 'print'
    //     ]
    //     });
    // });

</script>

<script src="/bower_components/admin-lte/plugins/datatables/extra/export.js"></script>
<script type="text/javascript">
    $(document).on('hidden.bs.modal', '#modal_dialog', function(e) {

        $('#modal_dialog #coaModels').modal('hide');

        $('#modal_dialog .modal-content').html('');
    });

</script>
<script src="/bower_components/admin-lte/plugins/datatables/extra/print.min.js"></script>
@endsection
