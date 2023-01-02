@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Sales {{ ucfirst(\Request::get('type'))}} (

        <?php
                $filteruser = function($query){
                    if(!Auth::user()->hasRole('admins')){
                        return $query->where('user_id',Auth::user()->id);
                    }
                };
                $filterfiscalyear = function($query){
                    if(Request::get('fiscal_id')){
                        return $query->where('fiscal_year_id',Request::get('fiscal_id'));
                    }
                };

                if(\Request::get('type') == 'quotation')
                  $number = \App\Models\Orders::where('order_type','quotation')->where(function($query) use ($filteruser){
                            $filteruser($query);
                        })->where(function($query) use ($filterfiscalyear){
                            $filterfiscalyear($query);
                        })->where('org_id', \Auth::user()->org_id)->sum('total_amount');
                elseif(\Request::get('type') == 'invoice')
                  $number = \App\Models\Orders::where('order_type','proforma_invoice') ->where(function($query) use ($filteruser){
                            $filteruser($query);
                        })->where(function($query) use ($filterfiscalyear){
                            $filterfiscalyear($query);
                        })->where('org_id', \Auth::user()->org_id)->sum('total_amount');
                elseif(\Request::get('type') == 'order')
                   $number = \App\Models\Orders::where('order_type','order')
                     ->where(function($query) use ($filteruser){
                            $filteruser($query);
                        })->where(function($query) use ($filterfiscalyear){
                            $filterfiscalyear($query);
                        })->where('org_id', \Auth::user()->org_id)->sum('total_amount');
                else
                   $number = \App\Models\Orders::sum('total_amount');
                  echo number_format($number,2);
                  ?> )
        <small> Manage {{ ucfirst(\Request::get('type'))}}

            <?php


                if(\Request::get('type') == 'quotation')
                  $ids = \App\Models\Orders::where('order_type','quotation')
                        ->where(function($query) use ($filteruser){
                            $filteruser($query);
                        })->where(function($query) use ($filterfiscalyear){
                            $filterfiscalyear($query);
                        })->where('org_id', \Auth::user()->org_id)
                        ->pluck('id')->all();

                elseif(\Request::get('type') == 'invoice')
                  $ids = \App\Models\Orders::where('order_type','proforma_invoice')
                        ->where(function($query) use ($filteruser){
                            $filteruser($query);
                        })->where(function($query) use ($filterfiscalyear){
                            $filterfiscalyear($query);
                        })->where('org_id', \Auth::user()->org_id)
                        ->pluck('id')->all();

                elseif(\Request::get('type') == 'order')
                   $ids = \App\Models\Orders::where('order_type','order')
                        ->where(function($query) use ($filteruser){
                            $filteruser($query);
                        })->where(function($query) use ($filterfiscalyear){
                            $filterfiscalyear($query);
                        })->where('org_id', \Auth::user()->org_id)
                        ->pluck('id')->all();
                else
                   $ids = \App\Models\Orders::pluck('id')->all();

                  $paid_amount = \App\Models\Payment::whereIn('sale_id',$ids)->sum('amount');

                  if($paid_amount ==  null)
                    $paid_amount = 0.00;

                  $due_amount = $number-$paid_amount;

                 ?>

            Paid <span class="label label-warning">{{ number_format($paid_amount,2) }}</span> Due <span class="label label-danger">{{ number_format($due_amount,2)}}</span> so far


        </small>
    </h1>


    <a href="/admin/orders?type=quotation">Quotes</a>
    | <a href="/admin/orders?type=invoice">Proforma Invoice</a>
    | <a href="/admin/sales/paymentslist">Payments </a>
    | <a href="/admin/invoice">Tax Invoices</a>

    <p>
        Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong>
    </p>

</section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
        {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
        <div class="box box-primary">

            <div class="box-header with-border">
                &nbsp;
                <a class="btn btn-social btn-foursquare btn-sm" href="{!! route('admin.orders.create') !!}?type={{\Request::get('type')}}" title="Create Order">
                    <i class="fa fa-plus"></i> Create New {!! $_GET['type'] == 'po' ? 'Purchase Order' : ucfirst($_GET['type']) !!}
                </a>
                &nbsp;

                <a href="/admin/download/orders/pdf/index?type={{\Request::get('type')}}" class="btn btn-default btn-xs"><i class="fa fa-fw fa-file-pdf-o"></i> PDF </a>
                &nbsp;
                <a href="/admin/download/orders/excel/index?type={{\Request::get('type')}}" class="btn btn-default btn-xs"><i class="fa fa-fw fa-file-excel-o"></i> Excel</a>
                @if(\Auth::user()->hasRole(['admins']))
                <a class="btn btn-default btn-xs" href="/admin/orders/trash"><i class="fa fa-trash"></i> Trash </a>
                @endif

                <div class="wrap" style="margin-top:5px;">
                    <div class="filter form-inline" style="margin:0 30px 0 0;">

                        {!! Form::select('fiscal_year', ['' => 'Fiscal Year']+$fiscalyears, \Request::get('fiscal_id'), ['id'=>'filter-fiscal', 'class'=>'form-control input-sm', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                        {!! Form::select('status', ['' => 'Order Status','Active'=>'Active', 'Canceled'=>'Canceled', 'Invoiced'=>'Invoiced'], \Request::get('status'), ['id'=>'filter-status', 'class'=>'form-control input-sm', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                        {!! Form::select('customer', ['' => 'Select Leads']+$leads, \Request::get('customer_id'), ['id'=>'filter-customer', 'class'=>'form-control input-sm customer_id', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                         {!! Form::select('client_id', ['' => 'Select Customer']+$clients, \Request::get('client_id'), ['id'=>'filter-clients', 'class'=>'form-control input-sm customer_id ', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                        {!! Form::select('location', ['' => 'location']+$locations, \Request::get('location_id'), ['id'=>'filter-location', 'class'=>'form-control input-sm', 'style'=>'width:100px; display:inline-block;']) !!}&nbsp;&nbsp;

                        {!! Form::select('pay_status',[''=>'All Payments','Pending'=>'Pending',
                            'Partial'=>'Partial','Paid'=>'Paid'] , Request::get('pay_status') ,
                            ['class'=>'form-control input-sm','id'=>'pay_status'])  !!}

                        <span class="btn btn-primary btn-sm" id="btn-submit-filter">
                            <i class="fa fa-list"></i> Filter
                        </span>

                        <span class="btn btn-danger btn-sm" id="btn-filter-clear">
                            <i class="fa fa-close"></i> Clear
                        </span>

                    </div>
                </div>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>



            <div class="box-body">

                <span id="index_lead_ajax_status"></span>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped" id="orders-table">
                        <thead>
                            <tr class="bg-info">

                                <th>#</th>
                                <th> Date(AD)</th>
                                <th> Date(BS)</th>
                                <th>Client</th>
                                <th>Ord Status</th>
                                <th>Source</th>
                                <th>Paid </th>
                                <th>Balance</th>
                                <th>P Status</th>
                                <th>Officer</th>
                                <th>Total</th>
                                <th>Tools </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($orders) && !empty($orders))
                            @foreach($orders as $o)
                            <tr>
                                @if($o->viewed == '0')

                                <td class="bg-warning">@if(\Request::get('type') == 'quotation'){{\FinanceHelper::getAccountingPrefix('QUOTATION_PRE')}}@else{{\FinanceHelper::getAccountingPrefix('SALES_PRE')}}@endif{!! $o->bill_no !!}<input type="hidden" name="sale_id" class="index_sale_id" value="{{$o->id}}"></td>
                                <td>
                                    {{ date('dS-m-Y', strtotime($o->bill_date)) }}
                                </td>
                                <td>
                                    {{ TaskHelper::getNepaliDate($o->bill_date)   }}
                                </td>
                                <td class="bg-warning">
                                    <span style="font-size: 16.5px"><a href="/admin/orders/{{$o->id}}">
                                            @if($o->source=='client') {{ $o->client->name }} @else {{ $o->lead->name }} @endif
                                            <small>
                                                {{ $o->name }}
                                            </small></a>
                                    </span></td>

                                <td class="bg-warning">
                                    {!! \Form::select('order_status',['Active'=>'Active','Canceled'=>'Canceled'], $o->status, ['class' =>'form-control input-sm label-default','id' => 'order_status'])!!}
                                </td>
                                <td class="bg-warning">{{ ucfirst($o->source) }}</td>
                                <?php
                                               $paid_amount= \TaskHelper::getSalesPaymentAmount($o->id);
                                             ?>

                                <td class="bg-warning">{!! mumber_format($paid_amount,2) !!}</td>

                                <td class="bg-warning">{!! number_format($o->total_amount-$paid_amount,2) !!}</td>

                                @if($o->payment_status == 'Pending')
                                <td><span class="label label-warning">Pending</span></td>
                                @elseif($o->payment_status == 'Partial')
                                <td><span class="label label-info">Partial</span></td>
                                @elseif($o->payment_status == 'Paid')
                                <td><span class="label label-success">Paid</span></td>
                                @else
                                <td><span class="label label-warning">Pending</span></td>
                                @endif

                                <td class="bg-warning">{!! $o->total_amount !!}</td>

                                <td class="bg-warning">
                                    <a href="/admin/order/generatePDF/{{$o->id}}"><i class="fa fa-download"></i></a>
                                    <a href="/admin/order/print/{{$o->id}}"><i class="fa fa-print"></i></a>
                                    @if(\Request::get('type') == 'quotation')
                                    <a href="/admin/order/copy/{{$o->id}}"><i class="fa fa-copy"></i></a>
                                    @endif
                                </td>
                                <td class="bg-warning">
                                    @if($o->status != 'Invoiced' && \Request::get('type') == 'invoice')
                                    @if( $o->isEditable() || $o->canChangePermissions() )
                                    <a href="{!! route('admin.orders.edit', $o->id) !!}?type={{$o->order_type}}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                    @else
                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/orders/general.error.cant-edit-this-document') }}"></i>
                                    @endif
                                    @if( $o->isDeletable())
                                    <a href="{!! route('admin.orders.confirm-delete', $o->id) !!}?type={{\Request::get('type')}}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                    @else
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/orders/general.error.cant-delete-this-document') }}"></i>
                                    @endif
                                    @else
                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/orders/general.error.cant-edit-this-document') }}"></i>
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/orders/general.error.cant-delete-this-document') }}"></i>
                                    @endif
                                    <a href="{!! route('admin.payment.purchase.list', $o->id) !!}" title="View Payment"><i class="fa fa-credit-card"></i></a>
                                </td>
                                @else

                                <td>@if(\Request::get('type') == 'quotation'){{\FinanceHelper::getAccountingPrefix('QUOTATION_PRE')}}@else{{\FinanceHelper::getAccountingPrefix('SALES_PRE')}}@endif{!! $o->bill_no !!}<input type="hidden" name="sale_id" class="index_sale_id" value="{{$o->id}}"></td>

                                <td>
                                     {{ date('dS-m-Y', strtotime($o->bill_date)) }}
                                </td>
                                 <td>
                                    {{ TaskHelper::getNepaliDate($o->bill_date)   }}
                                </td>
                                <td> <span style="font-size: 16.5px"><a href="/admin/orders/{{$o->id}}">
                                            @if($o->source=='client') {{ $o->client->name }} @else {{ $o->lead->name }} @endif
                                            <small>{{ $o->name }}</small></a> </span>
                                </td>
                                <td>
                                    {!! \Form::select('order_status',['Active'=>'Active','Canceled'=>'Canceled'], $o->status, ['class' =>'form-control input-sm label-default','id' => 'order_status'])!!}
                                </td>
                                <td>{{ ucfirst($o->source) }}</td>

                                <?php
                                              $paid_amount= \TaskHelper::getSalesPaymentAmount($o->id);
                                             ?>
                                <td>{!! number_format($paid_amount,2) !!}</td>
                                <td>{!! number_format($o->total_amount-$paid_amount,2) !!}</td>

                                @if($o->payment_status == 'Pending')
                                <td><span class="label label-warning">Pending</span></td>
                                @elseif($o->payment_status == 'Partial')
                                <td><span class="label label-info">Partial</span></td>
                                @elseif($o->payment_status == 'Paid')
                                <td><span class="label label-success">Paid</span></td>
                                @else
                                <td><span class="label label-warning">Pending</span></td>
                                @endif
                                <td> <span class=""> <img src="/images/profiles/{{$o->user->image ? $o->user->image : 'default.png'}}" class="img-circle img-fluid" style="width: 30px;height: 30px;" alt="User Image">  {{ucfirst($o->user->username)}} </span></td>
                                <td>{!! number_format($o->total_amount,2) !!}</td>
                                <td>
                                    <a href="/admin/order/generatePDF/{{$o->id}}"><i class="fa fa-download"></i></a>
                                    <a href="/admin/order/print/{{$o->id}}"><i class="fa fa-print"></i></a>
                                    @if(\Request::get('type') == 'quotation')
                                    <a href="/admin/order/copy/{{$o->id}}"><i class="fa fa-copy"></i></a>
                                    @endif
                                </td>
                                <td>
                                    @if ( $o->isEditable() || $o->canChangePermissions() )
                                    @if($o->order_type == 'proforma_invoice' && \Request::get('type') == 'invoice')
                                    @if(\Auth::user()->hasRole('admins'))
                                    <a href="{!! route('admin.orders.edit', $o->id) !!}?type={{\Request::get('type')}}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                    @else
                                    <i class="fa fa-ban text-muted" title=""></i>
                                    @endif
                                    @else
                                    <a href="{!! route('admin.orders.edit', $o->id) !!}?type={{\Request::get('type')}}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                    @endif
                                    @else
                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/orders/general.error.cant-edit-this-document') }}"></i>
                                    @endif
                                    @if ( $o->isDeletable() )
                                    @if($o->order_type == 'proforma_invoice' && \Request::get('type') == 'invoice')
                                    @if(\Auth::user()->hasRole('admins'))
                                    <a href="{!! route('admin.orders.confirm-delete', $o->id) !!}?type={{\Request::get('type')}}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                    @else
                                    <i class="fa fa-ban text-muted" title=""></i>
                                    @endif
                                    @else
                                    <a href="{!! route('admin.orders.confirm-delete', $o->id) !!}?type={{\Request::get('type')}}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                    @endif
                                    @else
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/orders/general.error.cant-delete-this-document') }}"></i>
                                    @endif
                                    <a href="{!! route('admin.payment.orders.list', $o->id) !!}" title="View Payment"><i class="fa fa-credit-card"></i></a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                </div> <!-- table-responsive -->

            </div><!-- /.box-body -->
            <div style="text-align: center;"> {!! $orders->appends(\Request::except('page'))->render() !!} </div>
        </div><!-- /.box -->
        <input type="hidden" name="order_type" id="order_type" value="{{\Request::get('type')}}">
        {!! Form::close() !!}
    </div><!-- /.col -->

</div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkClient[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

</script>


<script type="text/javascript">
    $(document).on('change', '#order_status', function() {

        var id = $(this).closest('tr').find('.index_sale_id').val();

        var purchase_status = $(this).val();
        $.post("/admin/ajax_order_status", {
                id: id
                , purchase_status: purchase_status
                , _token: $('meta[name="csrf-token"]').attr('content')
            }
            , function(data, status) {
                if (data.status == '1')
                    $("#index_lead_ajax_status").after("<span style='color:green;' id='index_status_update'>Status is successfully updated.</span>");
                else
                    $("#index_lead_ajax_status").after("<span style='color:red;' id='index_status_update'>Problem in updating status; Please try again.</span>");

                $('#index_status_update').delay(3000).fadeOut('slow');
                //alert("Data: " + data + "\nStatus: " + status);
            });

    });

</script>
<script type="text/javascript">
    $("#btn-submit-filter").on("click", function() {

        fiscal_id = $("#filter-fiscal").val();
        status = $("#filter-status").val();
        customer_id = $("#filter-customer").val();
        location_id = $("#filter-location").val();
        type = $("#order_type").val();
        pay_status = $('#pay_status').val();
        filterClients=$('#filter-clients').val();
        window.location.href = "{!! url('/') !!}/admin/orders?fiscal_id=" + fiscal_id + "&status=" + status + "&customer_id=" + customer_id + "&location_id=" + location_id + "&type=" + type + "&pay_status="+pay_status+"&client_id="+filterClients;
    });

    $("#btn-filter-clear").on("click", function() {

        type = $("#order_type").val();
        window.location.href = "{!! url('/') !!}/admin/orders?type=" + type;
    });

</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.customer_id').select2();
    });

</script>

@endsection
