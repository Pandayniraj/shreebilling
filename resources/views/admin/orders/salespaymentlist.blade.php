@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title ?? "Page Title" !!}

                <small>{!! $page_description ?? "Page Description" !!}</small>
            </h1>
            <p>The payment appears here if you pay from right side of <a href="/admin/invoice1">Tax invoice list</a> </p>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

  <div class='row'> 
        <div class='col-md-12'> 

            <!-- Box -->
            {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        
                        &nbsp;
                        <a class="btn btn-primary btn-xs" href="#" title="Create Order" data-toggle="modal" data-target="#makePaymentModal">
                            <i class="fa fa-plus"></i> Add Payment
                        </a>
                        

                        &nbsp;
                        <a class="btn btn-primary btn-xs" href="/admin/purchase?type=bills" title="Create Order">
                            <i class="fa fa-undo"></i> Back
                        </a>
                    </div>  

                    

                   <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="orders-table">

                                <thead>
                                    <tr class="bg-blue">
                                       
                                        <th>id</th>
                                        <th>Invoice ID</th>
                                        
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Reference No</th>
                                        <th>Amount</th>
                                        <th>Paid By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                   @if(isset($payment_list) && !empty($payment_list)) 
                                     @foreach($payment_list as $o)
                                        <tr>
                                           
                                            <td>{!! $o->id !!}</td>
                                            <td>{!! $o->invoice_id !!}</td>
                                            <td>{!! $o->sale->client->name !!}</td>
                                            <td>{!! date('dS M y', strtotime($o->date)) !!}</td>
                                            <td>{!! $o->reference_no !!}</td>
                                            <td>{{env('APP_CURRENCY')}} {{ number_format($o->amount,2) }}</td>
                                            <td>{!! $o->paidby->name !!}</td>

                                            <td>
                                                @if ( $o->isEditable() || $o->canChangePermissions() )
                                                    @if($o->status == 'paid' && \Request::get('type') == 'invoice')
                                                    <i class="fa fa-edit text-muted" title=""></i> 
                                                    @else
                                                        <a href="/admin/payment/orders/{{$o->sale_id}}/edit/{{$o->id}}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a> 
                                                    @endif
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/orders/general.error.cant-edit-this-document') }}"></i>
                                                @endif

                                                @if ( $o->isDeletable() )
                                                    @if($o->status == 'paid' && \Request::get('type') == 'invoice')
                                                    <i class="fa fa-trash text-muted" title=""></i>
                                                    @else
                                                    <a href="{!! route('admin.payment.orders.confirm-delete', $o->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                    @endif
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/orders/general.error.cant-delete-this-document') }}"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>

                            </table>
                        </div> <!-- table-responsive -->
                    </div><!-- /.box-body -->
                 </div><!-- /.box -->

            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->


<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<div id="makePaymentModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pending & Partial Payments</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <select class="form-control searchable" id='purchaseId'>
                        @foreach($orderTopay ?? [] as $k=>$value)
                            <option value="{{ $value->id }}">
                                Bill#{{$value->bill_no  }} [ {{$value->client->name  }} ] 
                                Total: {{
                                $value->total_amount -
                                 \TaskHelper::getSalesPaymentAmount($value->id)  

                                }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id='payNow' >Pay</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
    
    $('.searchable').select2({


        width: '100%',
    });

    $('#payNow').click(function(){

        let pid  = $('#purchaseId').val();

        location.href = `/admin/payment/orders/${pid}/create`;


    });



</script>
@endsection


