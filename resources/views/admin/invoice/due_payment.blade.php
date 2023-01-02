@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title }}
        <small>{{ $page_description }}</small>
    </h1>
    <p> This is useful if you are registered in VAT</p>
    Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong>


    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>
<style type="text/css">
      @media only screen and (max-width: 770px) {

        .hide_on_tablet{
            display: none;
        }

    }
    .nep-date-toggle{
        width: 120px !important;
    }
</style>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<div class='row'>

    <div class='col-md-12'>

        <!-- Box -->

        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="wrap hide_on_tablet" style="margin-top:5px;margin-left: 4px;">
                    <form method="get" action="/admin/invoice/duepayment">
                    <div class="filter form-inline" style="margin:0 30px 0 0;">
                        {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:120px;', 'class' => 'form-control input-sm input-sm date-toggle-nep-eng1', 'id'=>'start_date', 'placeholder'=>'Bill start date...','autocomplete' =>'off']) !!}&nbsp;&nbsp;

                        {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:120px; display:inline-block;', 'class' => 'form-control input-sm input-sm date-toggle-nep-eng1', 'id'=>'end_date', 'placeholder'=>'Bill end date..','autocomplete' =>'off']) !!}&nbsp;&nbsp;

                        <button class="btn btn-primary btn-sm" id="btn-submit-filter" type="submit">
                            <i class="fa fa-list"></i> Filter
                        </button>
                        <a href="/admin/invoice/duepayment" class="btn btn-danger btn-sm" id="btn-filter-clear" >
                            <i class="fa fa-close"></i> Clear
                        </a>
                    </div>
                    </form>
                </div>

            </div>


                  {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
            <div class="box-body">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered table-striped" id="orders-table">
                        <thead>
                            <tr class="bg-danger">
                                <th>Bill No.</th>
                                <th>Fiscal Yr.</th>
                                <th>Customer name</th>
                                <th>Due date</th>
                                <th>Total</th>
                                <th>Outlet</th>
                                <th>Pay Status</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($orders) && !empty($orders))
                            @foreach($orders as $o)
                            @php

                                $paidAmount = TaskHelper::getTaxInvoicePaidAmount($o->id);
                                if( $paidAmount >= $o->total_amount ){
                                    $paystatus = 'Paid';
                                }elseif($paidAmount > 0){
                                    $paystatus = 'Partial';
                                }else{
                                    $paystatus = 'Pending';
                                }

                            @endphp

                            @if((!Request::get('pay_status') || Request::get('pay_status') == $paystatus) && $paidAmount <= $o->total_amount)
                            <tr>
                                <td>{{ $o->bill_no }}</td>
                                <td>{{ $o->fiscal_year }}</td>
                                <td><span style="font-size: 16.5px"> <a href="/admin/invoice1/{{$o->id}}"> {{ $o->client->name }}</a> <small>{{ $o->name }}</small> </span></td>
                                <td>{{ date('dS M y',strtotime($o->due_date))}}</td>
                                <td>{!! number_format($o->total_amount,2) !!}</td>
                                <td> {{ $o->outlet->outlet_code  }} </td>


                                @if( $paidAmount >= $o->total_amount )
                                <td><span class="label label-success">Paid</span></td>
                                @elseif($paidAmount > 0)
                                <td><span class="label label-info">Partial</span></td>
                                @else
                                <td><span class="label label-warning">Pending</span></td>
                                @endif

                                <td>
                                    <a href="/admin/invoice/payment/{{$o->id}}" title="Payments"><i class="fa fa-credit-card"></i></a>

                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                    {!! $orders->render() !!}

                </div> <!-- table-responsive -->

            </div><!-- /.box-body -->
        </div><!-- /.box -->
        {!! Form::close() !!}
    </div><!-- /.col -->

</div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
@include('partials._date-toggle')
<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkClient[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }
      // $('.date-toggle-nep-eng1').nepalidatetoggle();
</script>

<script>
    $(function() {
        $('#orders-table').DataTable({
            pageLength: 25
            , ordering: false
        });
    });

     $(function() {
        $('#start_date').datepicker({
                 //format: 'YYYY-MM-DD',
                dateFormat: 'yy-m-d',
                sideBySide: true,

            });
        $('#end_date').datepicker({
                 //format: 'YYYY-MM-DD',
                dateFormat: 'yy-m-d',
                sideBySide: true,

            });
        });

</script>

    <script type="text/javascript">
      $('.searchable').select2();
    </script>

@endsection
