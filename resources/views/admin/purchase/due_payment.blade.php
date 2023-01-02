@extends('layouts.master')
@section('content')

<style type="text/css">
.select-mini {
  font-size: 10px;
  height: 25px;
  width: 90px;
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
                <div class="wrap" style="margin-top:5px; margin-left:25px;">
                        <form method="get" action="/admin/purchase/duepayment">
                    <div class="filter form-inline" style="margin:0 30px 0 0;">
                        {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:120px;', 'class' => 'form-control', 'id'=>'start_date', 'placeholder'=>'Due Start Date','autocomplete' =>'off']) !!}&nbsp;&nbsp;
                        <!-- <label for="end_date" style="float:left; padding-top:7px;">End Date: </label> -->
                        {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:120px; display:inline-block;', 'class' => 'form-control', 'id'=>'end_date', 'placeholder'=>'Due End Date','autocomplete' =>'off']) !!}&nbsp;&nbsp;
                        <input type="hidden" name="type" value={{ Request::get('type') }}>
                        <button class="btn btn-primary" id="btn-submit-filter" type="submit">
                            <i class="fa fa-list"></i> Filter
                        </button>
                        <a href="/admin/purchase/duepayment?type={{ Request::get('type') }}" class="btn btn-danger" id="btn-filter-clear" >
                            <i class="fa fa-close"></i> Clear
                        </a>
                    </div>
                    </form>
                </div>

            </div>
             
                
            {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
            <div class="box-body">

                <span id="index_lead_ajax_status"></span>

                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="orders-table">

                        <thead>
                            <tr class="bg-maroon">
                                
                                <th>id</th>
                                <th>Bill No</th>
                                <th>Payment Date</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Pay Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $o)
                                <tr>

                                    <td>{{\FinanceHelper::getAccountingPrefix('PURCHASE_PRE')}}{!! $o->id !!}</td>
                                     <td>{{ $o->bill_no }}</td>
                                   
                                    <td>{{ $o->due_date }}</td>

                                    <td title="{{ $o->comments}}"><span style="font-size: 16.6px"><a href="/admin/purchase/{{$o->id}}?type={{\Request::get('type')}}"> {{ $o->client->name }}</a></span></td>
                                    <td style="font-size: 16.5px">{{ $o->currency }}{!! number_format($o->total,2) !!}</td>
                                    <?php
                                    $paid_amount= \TaskHelper::getPurchasePaymentAmountWithoutTds($o->id);
                                    $tds= \TaskHelper::getPurchaseTDS($o->id);
                                    $rmb = TaskHelper::getRmbPurch($o->id);
                                    $rmb_purch = TaskHelper::getRmbPaidPurch($o->id);
                                    ?>
                                    <td>{!! number_format($paid_amount,2)!!}</td>
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
                                       
                                        <a href="{!! route('admin.payment.purchase.list', $o->id) !!}" title="View Payment"><i class="fa fa-credit-card"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
