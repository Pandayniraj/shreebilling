@extends('layouts.master')
@section('content')

<style type="text/css">
.select-mini {
  font-size: 10px;
  height: 25px;
  width: 90px;
}
 .nep-date-toggle{
        width: 120px !important;
    }
</style>

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
    Purchase {!! $_GET['type'] == 'purchase_orders' ? 'Purchase Order' : ucfirst($_GET['type']) !!}

        <small> Manage purchase {{ ucfirst(\Request::get('type'))}}</small>
    </h1>
    <p> Insert all the purchase Bills from Suppliers, Go to expenses for general cash expenses</p>
    {{ TaskHelper::topSubMenu('topsubmenu.purchase')}}
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12 box'>
        <!-- Box -->

        <div class="">


            <div class="box-body with-border">

                &nbsp;
                <a class="btn btn-primary btn-sm" href="{!! route('admin.purchase.create') !!}?type={{\Request::get('type')}}" title="Create Order">
                    <i class="fa fa-plus"></i> Create New {!! $_GET['type'] == 'purchase_orders' ? 'Purchase Order' : ucfirst($_GET['type']) !!}
                </a>
                <a class="btn btn-default btn-sm" href="/admin/purchasetrash" title="Purchase trash">
                    <i class="fa fa-trash"></i> Purchase Trash
                </a>

              <a href="/admin/download/purchase/pdf/index?type={{\Request::get('type')}}&start_date={{  \Request::get('start_date') }}&end_date={{  \Request::get('end_date') }}&supplier_id={{  \Request::get('supplier_id') }}" class="btn btn-default btn-xs float-right" style="margin-right: 20px;">PDF Download</a>
                      &nbsp;
                      <a href="/admin/download/purchase/excel/index?type={{\Request::get('type')}}&start_date={{  \Request::get('start_date') }}&end_date={{  \Request::get('end_date') }}&supplier_id={{  \Request::get('supplier_id') }}" class="btn btn-default btn-xs float-right" >Excel Download</a>
                &nbsp;


            </div>
              <div class="wrap" style="margin-top:5px;">
                        <form method="get" action="/admin/purchase">
                    <div class="filter form-inline" style="margin:0 30px 0 0;">
                        {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:120px;', 'class' => 'form-control input-sm date-toggle-nep-eng1', 'id'=>'start_date', 'placeholder'=>trans('general.columns.start_date'  ),'autocomplete' =>'off']) !!}&nbsp;&nbsp;
                        <!-- <label for="end_date" style="float:left; padding-top:7px;">End Date: </label> -->
                        {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:120px; display:inline-block;', 'class' => 'form-control input-sm date-toggle-nep-eng1', 'id'=>'end_date', 'placeholder'=>trans('general.columns.end_date'),'autocomplete' =>'off']) !!}&nbsp;&nbsp;

                        {!! Form::select('supplier_id', ['' => 'Select Suppliers'] + $suppliers, \Request::get('supplier_id'), ['id'=>'filter-supplier', 'class'=>'form-control input-sm', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                        {!! Form::select('currency', ['' => 'Select Currency'] + $currency, \Request::get('currency'), ['id'=>'', 'class'=>'form-control input-sm', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                        {!! Form::select('pay_status',[''=>'All Payments','Pending'=>'Pending',
                            'Partial'=>'Partial','Paid'=>'Paid'] , Request::get('pay_status') ,
                            ['class'=>'form-control input-sm','id'=>'pay_status'])  !!}

                        <input type="hidden" name="search" value="true">
                        <input type="hidden" name="type" value={{ Request::get('type') }}>
                        <button class="btn btn-primary btn-sm" id="btn-submit-filter" type="submit">
                            <i class="fa fa-list"></i> Filter
                        </button>
                        <a href="/admin/purchase?type={{ Request::get('type') }}" class="btn btn-danger btn-sm" id="btn-filter-clear" >
                            <i class="fa fa-close"></i> Clear
                        </a>
                    </div>
                    </form>
                </div>

                  {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
            <div class="box-body">

                <span id="index_lead_ajax_status"></span>

                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="orders-table">

                        <thead>
                            <tr class="bg-maroon">

                                <th>Num</th>
                                <th> Bill No</th>
                                <th>Bill Date</th>
                                <th>Officer</th>
                                <th>Supplier</th>

                                <th>Buy Status</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>TDS</th>
                                <th>Pay </th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($orders) && !empty($orders))
                            @foreach($orders as $o)
                            <tr>


                                 <td>
                                     @if($o->purchase_type!='purchase_orders')
                                    <a target="_blank" href="/admin/entries/show/{{\FinanceHelper::get_entry_type_label($o->entry->entrytype_id)}}/{{$o->entry->id}}">{{$o->entry->number}}</a>
                                @else
                                         <a href="/admin/purchase/{{$o->id}}?type={{\Request::get('type')}}">  #{{\FinanceHelper::getAccountingPrefix('PURCHASE_PRE')}}{{ $o->id }}</b></a>

                                     @endif
                                 </td>
                                 <td>

                                    {{ $o->bill_no }}
                                </td>

                                <td>

                                    {{ $o->bill_date }}
                                </td>

                                 <td> <span class=""> <img src="/images/profiles/{{$o->user->image ? $o->user->image : 'default.png'}}" class="img-circle img-fluid" style="width: 30px;height: 30px;" alt="User Image">  {{ucfirst($o->user->username)}} </span></td>

                                <td title="{{ $o->comments}}"><span style="font-size: 16.6px">
                                        <a href="/admin/purchase/{{$o->id}}?type={{\Request::get('type')}}"> {{ $o->client->name ?? '' }}</a>
                                    </span></td>



                                <td>
                                    {!! \Form::select('purchase_status',['Placed'=>'Placed','Parked'=>'Parked','Recieved'=>'Recieved'], $o->status, ['class' =>'form-control input-sm select-mini ','id' => 'purchse_status'])!!}
                                </td>

                                <td style="font-size: 16.5px">{{ $o->currency }}{!! number_format($o->total,2) !!}</td>
                                <?php
                                $paid_amount= \TaskHelper::getPurchasePaymentAmountWithoutTds($o->id);
                                $tds= \TaskHelper::getPurchaseTDS($o->id);
                                ?>
                                <td>{!! number_format($paid_amount,2)!!}</td>
                                <td>{!! number_format($tds,2)!!} </td>

                                @if($o->payment_status == 'Pending')
                                <td><span class="label label-warning">Pending</span></td>
                                @elseif($o->payment_status == 'Partial')
                                <td><span class="label label-info">Partial</span></td>
                                @elseif($o->payment_status == 'Paid')
                                <td><span class="label label-success">Paid</span></td>
                                @else
                                <td><span class="label label-warning">Pending</span></td>
                                @endif

                                <td>
                                    @if($o->purchase_type == 'bills')
                                    @if(\Auth::user()->hasRole('admins'))
                                    <a href="{!! route('admin.purchase.edit', $o->id) !!}?type={{\Request::get('type')}}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                     @if($o->supplier_type == 'supplier')

                                    <a href="{!! route('admin.payment.purchase.list', $o->id) !!}"  title="View Payment"><i class="fa fa-credit-card"></i></a>
                                    @endif
                                    <a href="{!! route('admin.purchase.confirm-delete', $o->id) !!}?type={{\Request::get('type')}}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                    @else
                                    <i class="fa fa-edit text-muted" title=""></i>
                                    <i class="fa fa-trash text-muted" title=""></i>
                                    <a href="{!! route('admin.payment.purchase.list', $o->id) !!}" title="View Payment"><i class="fa fa-credit-card"></i></a>

                                    @endif

                                    @else

                                    @if ( $o->isEditable() || $o->canChangePermissions() )
                                    @if($o->status == 'paid' && \Request::get('type') == 'invoice')
                                    <i class="fa fa-edit text-muted" title=""></i>
                                    @else
                                    <a href="{!! route('admin.purchase.edit', $o->id) !!}?type={{\Request::get('type')}}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                    <a href="{!! route('admin.payment.purchase.list', $o->id) !!}" title="View Payment"><i class="fa fa-credit-card"></i></a>
                                    @endif
                                    @else
                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/orders/general.error.cant-edit-this-document') }}"></i>
                                    @endif
                                    @if ( $o->isDeletable() )
                                    @if($o->status == 'paid' && \Request::get('type') == 'invoice')
                                    <i class="fa fa-trash text-muted" title=""></i>
                                    @else
                                    <a href="{!! route('admin.purchase.confirm-delete', $o->id) !!}?type={{\Request::get('type')}}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                    @endif
                                    @else
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/orders/general.error.cant-delete-this-document') }}"></i>
                                    @endif
                                    <a href="{!! route('admin.payment.purchase.list', $o->id) !!}" title="View Payment"><i class="fa fa-credit-card"></i></a>
                                    @endif
                                    <a href="/admin/purchase/generatePDF/{{ $o->id }}"><i class="fa fa-download"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                </div> <!-- table-responsive -->

                @if(!Request::get('search'))
                <div class="center">

                  {!!  $orders->appends(\Request::except('page'))->render() !!}

                </div>
                @endif

            </div><!-- /.box-body -->
        </div><!-- /.box -->
        {!! Form::close() !!}
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
@include('partials._date-toggle')
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<!-- DataTables -->

<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkClient[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }
      $('.date-toggle-nep-eng1').nepalidatetoggle();
</script>



<script type="text/javascript">
    $(document).on('change', '#purchse_status', function() {

        var id = $(this).closest('tr').find('.index_purchase_id').val();

        var purchase_status = $(this).val();
        $.post("/admin/ajax_purchase_status", {
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

@endsection
