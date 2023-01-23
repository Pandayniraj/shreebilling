@extends('layouts.master')
@section('content')
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.css") }}" rel="stylesheet" type="text/css" />
<style>
    .panel.panel-default{
        box-shadow: rgb(17 17 26 / 10%) 0px 4px 16px, rgb(17 17 26 / 5%) 0px 8px 32px;
    }
    .bill-section {
        padding: 0px;
    }
    form.form-section {
        display: inline-block;
    }
    .btn-height {
        height: 33px;
    }
    a.btn.btn-default.btn-xs.btn-height {
        padding: 6px;
    }

    td.tdsize {
        font-size: 14px;
    }
    tr.bg-maroon th {
        white-space: nowrap;
    }
    table.dataTable tbody th, table.dataTable tbody td {
        font-size: 13px !important;
    }
    td.sorting_1 {
        padding: 5px !important;
    }
    .label-default {
        background-color: #ffffff;
        color: #444;
        height: 32px;
        width: 115px;
        margin-left: 13px;
        border: 1px solid #bce1f7;
        border-radius: 3px !important;
    }
    table.dataTable.no-footer {
        border-bottom: 1px solid #ddd;
    }
    .box.box-primary {
        box-shadow: rgb(17 17 26 / 10%) 0px 4px 16px, rgb(17 17 26 / 5%) 0px 8px 32px;
    }
    .box.box-primary {
        border-top-color: #ffffff;
    }
    .box.box-primary {
        border-top-color: #ffffff;
        margin-top: 15px;
    }
    .bg-maroon {
        background-color: #1195e147 !important;
        color: #000 !important;
        border-bottom: !important;
    }
    table.dataTable thead th, table.dataTable thead td {
        padding: 10px 18px;
        border-bottom: 3px solid #ddd;
    }
    table.dataTable tbody th, table.dataTable tbody td {
        padding: 7px 0px;
    }
    td.sorting_1 a {
        font-size: 13px;
    }
    td.sorting_1 {
        padding: 5px !important;
        white-space: nowrap;
    }
    img.p-image {
      width: 27px;
      height: 27px;
      object-fit: cover;
      object-position: bottom;
      border-radius: 50%;
  }
  table.dataTable thead > tr > th {
    padding-right: 30px;
    padding-bottom: 6px;
    padding-top: 10px;
}
input[type="search"] {
    border-radius: 3px;
    border: 1px solid #bce1f7;
}
.form-control {
    border-color: #bce1f7;
    border-radius: 3px !important;
}

</style>


<div class="panel panel-default" style="margin-top: -26px;">
  <div class="panel-body bill-section">
      <section class="content-header">
        <h1 style="margin-bottom:6px;">
            Bills {!! $_GET['type'] == 'purchase_orders' ? 'Purchase Order' : ucfirst($_GET['type']) !!}

            <small> Manage purchase {{ ucfirst(\Request::get('type'))}}</small>
        </h1>
        {{ TaskHelper::topSubMenu('topsubmenu.purchase')}}
        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
    </section>
</div>
</div>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->

        <div class="box box-primary">
            <div class="box-header">
                <div class="row" style="margin-left:-5px; margin-top: 5px;">
                    <a class="btn btn-social btn-foursquare btn-height" href="{!! route('admin.purchase.create') !!}?type={{\Request::get('type')}}" title="Create Order">
                        <i class="fa fa-plus"></i> New {!! $_GET['type'] == 'purchase_orders' ? 'Purchase Order' : ucfirst($_GET['type']) !!}
                    </a>
                    <form style="display:inline-block;padding-left: 5px; margin-left: 10px; padding-right: 5px;border: 1px solid #ccc;" action="{{ URL::to('admin/ExcelentriesAdd/') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="file" name="import_file" style="display: inline-block; padding-top: 5px;
                         padding-bottom: 5px; border-radius: 3px;" />
                        <button class="btn btn-default btn-xs">Import File</button>
                    </form>
                    <a class="btn btn-default btn-sm btn-height" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.orders.enable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                        <i class="fa fa-check-circle"></i>
                    </a>
                    <a class="btn btn-default btn-sm btn-height" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.orders.disable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                        <i class="fa fa-ban"></i>
                    </a>
                    <a class="btn btn-default btn-sm btn-height" href="/admin/purchasetrash" title="Purchase trash">
                        <i class="fa fa-trash"></i> Trash
                    </a>
                    <a class="btn btn-default btn-sm btn-height" href="/admin/purchase/overallexcel" title="Purchase Detail Excel">
                        <i class="fa fa-table"></i>Purchase-Detail Excel</a>
                    {{-- <a  href="/admin/entries/sampleexcel"><button class="btn btn-sm">Download Sample</a> --}}
                    &nbsp;
                </div>
                <div class="row" style="margin-left:-5px; margin-top: 5px;">
                    <form method="get" action="/admin/purchase" class="form-section">
                        <div class="filter form-inline">
                            {!! Form::text('start_date', \Request::get('start_date'), [ 'class' => 'form-control date-toggle', 'id'=>'start_date', 'placeholder'=>'Start Date','autocomplete' =>'off']) !!}&nbsp;&nbsp;
                            <!-- <label for="end_date" style="float:left; padding-top:7px;">End Date: </label> -->
                            {!! Form::text('end_date', \Request::get('end_date'), [ 'class' => 'form-control date-toggle', 'id'=>'end_date', 'placeholder'=>'End Date','autocomplete' =>'off']) !!}&nbsp;&nbsp;

                            {!! Form::select('supplier_id', ['' => 'Select Suppliers'] + $suppliers, \Request::get('supplier_id'), ['id'=>'filter-supplier', 'class'=>'form-control', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                            <input type="hidden" name="search" value="true">
                            <input type="hidden" name="type" value={{ Request::get('type') }}>
                            <button class="btn btn-default btn-height" id="btn-submit-filter" type="submit">
                                <i class="fa fa-list"></i> Filter
                            </button>
                            <a href="/admin/purchase?type={{ Request::get('type') }}" class="btn btn-default btn-height" id="btn-filter-clear" >
                                <i class="fa fa-close"></i> Clear
                            </a>
                        </div>
                    </form>
                    &nbsp;

                    <a href="/admin/download/purchase/pdf/index?type={{\Request::get('type')}}&start_date={{  \Request::get('start_date') }}&end_date={{  \Request::get('end_date') }}&supplier_id={{  \Request::get('supplier_id') }}" class="btn btn-default btn-xs btn-height"><i class="fa fa-file-pdf-o"></i>   PDF</a>
                    &nbsp;
                    {{-- <a href="/admin/download/purchase/excel/index?type={{\Request::get('type')}}&start_date={{  \Request::get('start_date') }}&end_date={{  \Request::get('end_date') }}&supplier_id={{  \Request::get('supplier_id') }}" class="btn btn-default btn-xs btn-height" ><i class="fa  fa-file-excel-o"></i>  Excel</a>
                    &nbsp; --}}
                </div>
            </div>
           <!--  <div class="wrap" style="margin-top:5px;">

           </div> -->

           {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
           <div class="box-body">

            <span id="index_lead_ajax_status"></span>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-responsive" id="clients-table">

                    <thead>
                        <tr class="bg-maroon">

                            <th>No</th>
                            <th>Bill No</th>
                            <th>Bill Date</th>
                            <th>Officer</th>
                            <th>Supplier</th>
                            <th>Purchse Status</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Pay Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                        $totalSummaryAmount = 0;
                        $totalSummaryPaid = 0;
                        $totalSummaryBalance = 0;
                        @endphp
                        @if(isset($orders) && !empty($orders))
                     
                        @foreach($orders as $o)
                       
                        <?php
                        if(isset($o->entry))
                        {
                            $entrytypeid=$o->entry->entrytype_id;
                            $entryid=$o->entry->id;
                            $entrynumber=$o->entry->number;
                        }
                        ?>
                        
                        <tr>
                           @if(isset($entrytypeid, $entryid, $entrynumber))
                            <td>
                             <a target="_blank" href="/admin/entries/show/{{\FinanceHelper::get_entry_type_label($entrytypeid)}}/{{$entryid}}">{{$entrynumber}}</a>
                         </td>
                         @else
                         <td>Not Found</td>
                         @endif
                         <td style="padding-left: 18px;">{!! $o->bill_no !!}<input type="hidden" name="purchase_id" class="index_purchase_id" value="{{$o->id}}"></td>

                         <td style="white-space: nowrap; padding-left: 6px;" class="tdsize">
                            {{ date('dS M Y', strtotime($o->bill_date)) }}<br>
                            {{ TaskHelper::getNepaliDate($o->bill_date)  }}
                        </td>

                        <td style="padding-left: 8px;"> <span class=""> <img src="/images/profiles/{{$o->user->image ? $o->user->image : 'default.png'}}" class="img-circle img-fluid" style="width: 27px;height: 27px;" alt="User Image">  {{ucfirst($o->user->username)}} </span></td>

                        <td title="{{ $o->comments}}">
                            @if($o->client->image)
                            <img src="{{asset($o->client->image)}}" class="p-image" id="blah" src="#" alt="your image" />
                            @else
                            <img src="/images/profiles/default.png" class="p-image" id="blah" src="#" alt="your image" />
                            @endif

                            <span style="font-size: 13px;"><a href="/admin/purchase/{{$o->id}}?type={{\Request::get('type')}}" style="color:#000;"> {{ $o->client->name }}</a></span>
                        </td>


                        <td>
                          {!! \Form::select('purchase_status',['Placed'=>'Placed','Parked'=>'Parked','Recieved'=>'Recieved'], $o->status, ['class' =>'form-control label-default','id' => 'purchse_status'])!!}
                      </td>

                      <td style="text-align: center;">{!! number_format($o->total,2) !!}</td>


                      <?php
                      $paid_amount= \TaskHelper::getPurchasePaymentAmount($o->id);

                      $totalSummaryAmount  += $o->total;
                      $totalSummaryPaid += $paid_amount;
                      $totalSummaryBalance += (  $o->total-$paid_amount );

                      ?>
                      <td style="text-align:center;">{!! number_format($paid_amount,2)!!}</td>
                      <td style="text-align: center;">{!! $o->total - $paid_amount !!}</td>

                      @if($o->payment_status == 'Pending')
                      <td  style="text-align: center;"><span class="label label-warning">Pending</span></td>
                      @elseif($o->payment_status == 'Partial')
                      <td  style="text-align: center;"><span class="label label-info">Partial</span></td>
                      @elseif($o->payment_status == 'Paid')
                      <td  style="text-align: center;"><span class="label label-success">Paid</span></td>
                      @else
                      <td style="text-align: center;"><span class="label label-warning">Pending</span></td>
                      @endif

                      <td style="text-align: center;">
                        @if($o->purchase_type == 'bills' || $o->purchase_type == 'assets' || $o->purchase_type == 'services')

                            @if( $o->isEditable())
                            <a href="{!! route('admin.purchase.edit', $o->id) !!}?type={{\Request::get('type')}}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                            @if($o->supplier_type == 'supplier')
                                <a href="{!! route('admin.payment.purchase.list', $o->id) !!}"  title="View Payment"><i class="fa fa-credit-card"></i></a>
                            @endif
                            <a href="/admin/purchase/excel/{{ $o->id }}" title="download excel"><i class="fa fa-table" aria-hidden="true"></i></a>
                            <a href="{!! route('admin.purchase.confirm-delete', $o->id) !!}?type={{\Request::get('type')}}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                            @else
                                <i class="fa fa-edit text-muted" title=""></i>
                                <i class="fa fa-trash text-muted" title=""></i>
                            <a href="{!! route('admin.payment.purchase.list', $o->id) !!}"  title="View Payment"><i class="fa fa-credit-card"></i></a>

                        @endif
                        @else
                            @if ( $o->isEditable() )
                                @if($o->status == 'paid' && \Request::get('type') == 'invoice')
                                <i class="fa fa-edit text-muted" title=""></i>
                                @else
                                <a href="{!! route('admin.purchase.edit', $o->id) !!}?type={{\Request::get('type')}}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                @endif
                            @else
                                <i class="fa fa-edit text-muted" title="{{ trans('admin/orders/general.error.cant-edit-this-document') }}"></i>
                            @endif
                        {{-- @if ( $o->isDeletable() )--}}
                        {{--     @if($o->status == 'paid' && \Request::get('type') == 'invoice')--}}
                        {{--     <i class="fa fa-trash text-muted" title=""></i>--}}
                        {{--     @else--}}
                        <a href="{!! route('admin.purchase.confirm-delete', $o->id) !!}?type={{\Request::get('type')}}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                    </td>
{{--    <!--  @endif--}}
{{-- @else--}}
{{--     <i class="fa fa-trash text-muted" title="{{ trans('admin/orders/general.error.cant-delete-this-document') }}"></i>--}}
{{-- @endif--}}

                            @endif
                            <a href="/admin/purchase/generatePDF/{{ $o->id }}"><i class="fa fa-download"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                @if(Request::get('search'))
                                <tfoot>
                                    <tr >
                                        <td colspan="5"></td>
                                        <td>Total:</td>
                                        <td>{{ $totalSummaryAmount }}</td>
                                        <td>{{ $totalSummaryPaid }}</td>
                                        <td>{{ $totalSummaryBalance }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>

                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->
                </div><!-- /.box -->

                @if(!Request::get('search'))
                <div class="center">

                  {!!  $orders->appends(\Request::except('page'))->render() !!}

              </div>
              @endif

              {!! Form::close() !!}
          </div><!-- /.col -->
      </div><!-- /.row -->
      @endsection


      <!-- Optional bottom section for modals etc... -->
      @section('body_bottom')
      @include('partials._date-toggle')
      <script type="text/javascript">
        $('.date-toggle').nepalidatetoggle();
    </script>
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkClient[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>

    <script type="text/javascript">


     $(document).on('change', '#purchse_status', function() {

      var id = $(this).closest('tr').find('.index_purchase_id').val();

      var purchase_status = $(this).val();
      $.post("/admin/ajax_purchase_status",
          {id: id, purchase_status:purchase_status, _token: $('meta[name="csrf-token"]').attr('content')},
          function(data, status){
            if(data.status == '1')
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

<script>
    $(function() {
        $('#clients-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
            'csv', 'excel', 'pdf', 'print'
            ],
            'pageLength'  : 65,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : false,
            'info'        : true,
            'autoWidth'   : true,
            "paging"      : false
        });
    });

</script>

@endsection
