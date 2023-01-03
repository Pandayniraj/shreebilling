@extends('layouts.master')
@section('content')


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title }} Manager
        <small>{{ $page_description }}</small>
    </h1>


    <p>

       Enter all your business invoices here  <a class="btn btn-primary btn-xs" title="Create new invoice" href="{{ route('admin.invoice.create') }}">
                    <span class="material-icons">add_circle_outline</span>New
                </a>



    </p>
    {{-- @if(\Auth::user()->hasRole(['admins']))
                <a class="btn btn-default btn-xs" href="/admin/invoicetrash"><i class="fa fa-trash"></i> Trash </a>
                @endif --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<div class='row'>

    <div class='col-md-12'>

        <!-- Box -->

        <div class="box ">
            <div class="box-header">



              <div class="wrap" style="margin-top: 5px;">
                        <form method="get" action="/admin/invoice1">
                    <div class="filter form-inline" style="">
                        {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:120px;', 'class' => 'form-control input-sm', 'id'=>'start_date', 'placeholder'=>'Bill start date...','autocomplete' =>'off']) !!}&nbsp;&nbsp;
                        <!-- <label for="end_date" style="float:left; padding-top:7px;">End Date: </label> -->
                        {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:120px; display:inline-block;', 'class' => 'form-control input-sm', 'id'=>'end_date', 'placeholder'=>'Bill end date..','autocomplete' =>'off']) !!}&nbsp;&nbsp;

                         {!! Form::text('bill_no', \Request::get('bill_no'), ['style' => 'width:120px; display:inline-block;', 'class' => 'form-control input-sm', 'id'=>'bill_no', 'placeholder'=>'Enter bill number...','autocomplete' =>'off']) !!}&nbsp;&nbsp;


                        {!! Form::select('client_id', $outlets, \Request::get('client_id'), ['id'=>'filter-customer', 'class'=>'form-control searchable','placeholder' => 'Select Customer', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;


                         {!! Form::select('fiscal_year',  $fiscal_years, \Request::get('fiscal_year'), ['id'=>'fiscal_year', 'class'=>'form-control', 'placeholder' => 'Select Fiscal Year','style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;




                        <input type="hidden" name="search" value="true">
                        <input type="hidden" name="type" value={{ Request::get('type') }}>
                        <button class="btn btn-info btn-sm" id="btn-submit-filter" type="submit">
                            <i class="fa fa-list"></i> Filter
                        </button>
                        <a href="/admin/invoice1" class="btn btn-sm btn-default" id="btn-filter-clear" >
                            <i class="fa fa-close"></i> Clear
                        </a>
                    </div>
                    </form>
                </div>
                  {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}

                    </div>
            <div class="box-body">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered table-striped" id="orders-table">
                        <thead>
                            <tr class="bg-danger">
                                <th>No</th>
                                <th>
                                    Bill date AD
                                </th>
                                <th>
                                    Bill date BS
                                </th>

                                <th>Sys Bill No.</th>
                                <th title="Manual Bill #">Man#</th>
                                <th>Customer</th>
                                <th>Due date</th>
                                <th>Total</th>
                                <th>Officer</th>
                                <th>Tools</th>

                            </tr>
                        </thead>
                        <tbody>
                            
                            @if(isset($orders) && !empty($orders))
                            @foreach($orders as $o)
                            <tr>
                                @if(isset($o->entry))
                                <td>
                                    <a target="_blank" href="/admin/entries/show/{{\FinanceHelper::get_entry_type_label($o->entry->entrytype_id)}}/{{$o->entry->id}}">{{$o->entry->number}}</a>
                                </td>
                                @else
                                <td>Not Found</td>
                                @endif
                                <td>{{ date('dS M y',strtotime($o->bill_date))}}</td>
                                <td>{{ TaskHelper::getNepaliDate($o->bill_date) }}</td>

                                <td><small>{{ $o->bill_no }}</small></td>
                                <td>{{ $o->sales_order_no}}</td>
                                <td><span> <a href="/admin/invoice1/{{$o->id}}"> {{ $o->client->name }}</a> <small>{{ $o->name }}</small> </span></td>
                                <td>{{ TaskHelper::getNepaliDate($o->due_date) }}</td>
                                <td style="font-size: 16.5px">{!! number_format($o->total_amount,2) !!}</td>
                                <td>{{$o->user->first_name}} {{$o->user->last_name}}</td>
                                <td>
                                    <a href="/admin/invoice/print/{{$o->id}}" target="_blank" title="print"><i class="fa fa-print"></i></a>
                                    <a href="/admin/invoice/payment/{{$o->id}}" title="Receive Payment"><i class="fa fa-credit-card"></i></a>
                                    {{-- <a href="/admin/invoice/detail/{{ $o->id }}" title="view detail"><i class="fa fa-eye" aria-hidden="true"></i></a>     --}}
                                    <a href="{{ route('admin.invoice.edit',$o->id) }}" title="edit"><i class="fa fa-edit"></i></a>
                                    <a href="/admin/invoice/excel/{{ $o->id }}" title="download excel"><i class="fa fa-table" aria-hidden="true"></i></a>
                                    <a href="{!! route('admin.invoice.confirm-delete', $o->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>

                                </td>

                            </tr>
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
<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkClient[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

</script>

<script>

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
