@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title ?? "Page Title"}}
        <small>{{$description}}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>
<form action="{{route('admin.invoice.materalize.result')}}" method="get">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-custom">

                <div class="panel-heading">

                    <div class="row">
                        <div class="col-md-6">
                            <label>Start Date</label>
                            <div class="form-group">
                                <input type="text" name="start_date" class="form-control datepicker date-toogle" required="" placeholder="Start Date" value="{{$request['start_date'] ?? \Carbon\Carbon::today()->format('Y-m-d')}} ">
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
                                {!! Form::select('user_id',$users, isset($request['user_id']), ['class'=>'form-control searchable','placeholder'=>'Select Users']) !!}
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

@if(isset($sales))
<div class="panel panel-custom" id='scroll-me'>
    <div class="panel-heading">
        <b>Name of Firm:</b>
        {{ \Auth::user()->organization->organization_name }}
        <span style="margin-left: 45px;font-weight: 700;">Sales Materialized view</span> <br><br>
        <b>Pan No:</b> {{ \Auth::user()->organization->vat_id }} <br><br>
        <b>Duration of Sales:</b> {{$request['start_date']}} to {{$request['end_date']}}<br>
    </div>
    <div class="pannel-body">
        <a href="{{$url}}op=print" class="btn btn-default">Print</a>
        <a href="{{$url}}op=pdf" class="btn btn-default">PDF</a>

        <div class="row">
            <div class="col-md-12">
                <table class="table" id='filter-table'>
                    <thead>
                        <th>#SN</th>
                        <th>Fiscal Year</th>
                        <th>Bill No.</th>
                        <th>Customer Name</th>
                        <th>Customer Pan</th>
                        <th>Bill Date</th>
                        <th>Bill Amount</th>
                        <th>Discount Amount</th>
                        <th>Tax Amount</th>
                        <th>Total Amount</th>
                        <th>Sync With IRD</th>
                        <th>Is Bill Printed</th>
                        <th>Is Bill Active</th>
                        <th>Print Time</th>
                        <th>Entered By</th>
                        <th>Printed By</th>
                        <th>Is Real Time</th>
                    </thead>
                    <?php 
            $n = 0;
                $tbill_amount = 0;
                $tdiscount_amount = 0;
                $ttax_amount = 0;
                $ttotal_amount = 0;
            ?>
                    <tbody>
                        @foreach($sales as $key=>$s)
                        <tr>
                            <td>#{{++$n}}</td>
                            <td>{{$s->fiscal_year}}</td>
                            <td style="white-space: nowrap;"><b>{{$s->outlet_code}}</b>{{$s->bill_no}}</td>
                            <td>{{$s->customer_name}}</td>
                            <td>{{$s->customer_pan}}</td>
                            <td>{{$s->bill_date}}</td>
                            <td>{{number_format($s->amount,2)}}</td>
                            <td>{{number_format($s->discount,2)}}</td>
                            <td>{{ number_format($s->taxable_amount,2) }}</td>
                            <td>{{ number_format($s->total_amount,2) }}</td>
                            <td>{{$s->sync_with_ird }}</td>
                            <td>{{$s->is_bill_printed}}</td>
                            <td>{{$s->is_bill_active}}</td>
                            <td>{{$s->printed_time}}</td>
                            <td>{{$s->entered_by}}</td>
                            <td>{{$s->printed_by}}</td>
                            <td>{{$s->is_realtime}}</td>
                        </tr>
                        <?php 
                        $tbill_amount += $s->amount;
                        $tdiscount_amount += $s->discount;
                        $ttax_amount += $s->taxable_amount;
                        $ttotal_amount += $s->total_amount;
                    ?>
                        @if(!$s->is_bill_active)
                        <tr class="bg-danger">
                            <td>#{{++$n}}</td>
                            <td>{{$s->fiscal_year}}</td>
                            <td>Ref {{$s->bill_no}}<b>{{$s->outlet_code}}</b></td>
                            <td>{{$s->customer_name}}</td>
                            <td>{{$s->customer_pan}}</td>
                            <td>{{$s->bill_date}}</td>
                            <td>-{{$s->amount}}</td>
                            <td>-{{$s->discount}}</td>
                            <td>-{{ $s->taxable_amount }}</td>
                            <td>-{{$s->total_amount }}</td>
                            <td>{{$s->sync_with_ird }}</td>
                            <td>{{$s->is_bill_printed}}</td>
                            <td>{{$s->is_bill_active}}</td>
                            <td>{{$s->printed_time}}</td>
                            <td>{{$s->entered_by}}</td>
                            <td>{{$s->printed_by}}</td>
                            <td>{{$s->is_realtime}}</td>
                        </tr>
                        <?php 
                        $tbill_amount -= $s->amount;
                        $tdiscount_amount -= $s->discount;
                        $ttax_amount -= $s->taxable_amount;
                        $ttotal_amount -= $s->total_amount;
                    ?>
                        @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4"></th>
                            <th>Total</th>
                            <th>:</th>
                            <th>{{$tbill_amount}}</th>
                            <th>{{$tdiscount_amount}}</th>
                            <th>{{$ttax_amount}}</th>
                            <th>{{$ttotal_amount}}</th>
                            <th colspan="7"></th>
                        </tr>
                    </tfoot>



                </table>
            </div>
        </div>
    </div>
    <div style="text-align: center;"> {!! $sales->appends(\Request::except('page'))->render() !!} </div>
</div>
@endif



@endsection

@section('body_bottom')
@include('partials._date-toggle')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
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


</script>
@endsection
