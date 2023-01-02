@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

      <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               Renewals
                <small> Manage Renewals 


        


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
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a> 
                                        </th>
                                        <th>id</th>
                                        <th>Issue Date</th>
                                        <th>Client</th>
                                      
                                        <th>Order Status</th>
                                        <th>Source</th>
                                        <th>Paid Amount</th> 
                                        <th>Balance {{ env('APP_CURRENCY')}}</th>
                                        <th>Pay Status</th>
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
                                            <td class="bg-warning" align="center">{!! Form::checkbox('chkClient[]', $o->id); !!}</td>

                                            <td class="bg-warning">{!! $o->id !!}<input type="hidden" name="sale_id" class="index_sale_id" value="{{$o->id}}"></td> 

                                            <td> 
                                                {{ date('dS m yy', strtotime($o->bill_date)) }}
                                            </td>

                                            <td class="bg-warning">
                                              <span style="font-size: 16.5px"><a href="/admin/orders/{{$o->id}}">
                                              @if($o->source=='client') {{ $o->client->name }} @else {{ $o->lead->name }} @endif
                                              <small>
                                                {{ $o->name }}
                                              </small></a>
                                            </span></td>

                                           

                                            <td class="bg-warning">
                                                {!! \Form::select('order_status',['Active'=>'Active','Canceled'=>'Canceled'], $o->status, ['class' =>'form-control label-default','id' => 'order_status'])!!}
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
                                               @if($o->status != 'Invoiced'  && \Request::get('type') == 'invoice')
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
                                                  <a href="{!! route('admin.payment.purchase.list', $o->id) !!}"  title="View Payment"><i class="fa fa-credit-card"></i></a>
                                            </td>
                                            @else
                                            <td align="center">{!! Form::checkbox('chkClient[]', $o->id); !!}</td>


                                            <td>{!! $o->id !!}<input type="hidden" name="sale_id" class="index_sale_id" value="{{$o->id}}"></td>

                                            <td> 
                                                {{ date('dS m yy', strtotime($o->bill_date)) }}
                                            </td>

                                            <td > <span style="font-size: 16.5px"><a href="/admin/orders/{{$o->id}}"> 
                                              @if($o->source=='client') {{ $o->client->name }} @else {{ $o->lead->name }} @endif
                                              <small>{{ $o->name }}</small></a> </span>
                                            </td>
                                            <td>
                                                 {!! \Form::select('order_status',['Active'=>'Active','Canceled'=>'Canceled'], $o->status, ['class' =>'form-control label-default','id' => 'order_status'])!!}
                                            </td>
                                              <td>{{ ucfirst($o->source) }}</td>

                                             <?php
                                              $paid_amount= \TaskHelper::getSalesPaymentAmount($o->id);
                                             ?>
                                            <td>{!! number_format($paid_amount,2) !!}</td>
                                            <td>{!! number_format($o->total_amount-$paid_amount,2)  !!}</td>

                                            @if($o->payment_status == 'Pending')
                                              <td><span class="label label-warning">Pending</span></td>
                                            @elseif($o->payment_status == 'Partial')
                                              <td><span class="label label-info">Partial</span></td>
                                            @elseif($o->payment_status == 'Paid')
                                              <td><span class="label label-success">Paid</span></td>
                                            @else
                                               <td><span class="label label-warning">Pending</span></td>
                                            @endif   
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
                                                    <i class="fa fa-ban text-muted" title=""></i>
                                                    @else
                                                        <a href="{!! route('admin.orders.edit', $o->id) !!}?type={{\Request::get('type')}}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                    @endif
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/orders/general.error.cant-edit-this-document') }}"></i>
                                                @endif
                                                @if ( $o->isDeletable() )
                                                    @if($o->order_type == 'proforma_invoice' && \Request::get('type') == 'invoice')
                                                    <i class="fa fa-ban text-muted" title=""></i>
                                                    @else
                                                    <a href="{!! route('admin.orders.confirm-delete', $o->id) !!}?type={{\Request::get('type')}}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                    @endif
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/orders/general.error.cant-delete-this-document') }}"></i>
                                                @endif
                                                  <a href="{!! route('admin.payment.orders.list', $o->id) !!}"  title="View Payment"><i class="fa fa-credit-card"></i></a>
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
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>


    <script type="text/javascript"> 


     $(document).on('change', '#order_status', function() {
        
          var id = $(this).closest('tr').find('.index_sale_id').val(); 
          
          var purchase_status = $(this).val();
          $.post("/admin/ajax_order_status", 
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

</script>
<script type="text/javascript">
  
  $("#btn-submit-filter").on("click", function () {

   fiscal_id = $("#filter-fiscal").val();
   status = $("#filter-status").val();
   customer_id = $("#filter-customer").val();
   location_id = $("#filter-location").val();
   type = $("#order_type").val();

  window.location.href = "{!! url('/') !!}/admin/orders?fiscal_id="+fiscal_id+"&status="+status+"&customer_id="+customer_id+"&location_id="+location_id+"&type="+type;
});

$("#btn-filter-clear").on("click", function () {

    type = $("#order_type").val();
    window.location.href = "{!! url('/') !!}/admin/orders?type="+type;
});

</script>

<script type="text/javascript">
         $(document).ready(function() {
    $('.customer_id').select2();
});
</script>

@endsection
