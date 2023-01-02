@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_description !!}

                <small></small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

  <div class='row'> 
        <div class='col-md-12'> 
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">

                    <div class="box-header with-border">
                        &nbsp;
                        <?php $order_name = \App\Models\Orders::find(\Request::segment(4));
                              $order_type=$order_name->order_type;
                         ?>

                        @if($order_type == 'quotation')
                         <a class="btn btn-primary btn-sm float-right" href="/admin/invoice1" title="Close">
                          Close
                         </a>
                        @elseif($order_type == 'proforma_invoice')
                        <a class="btn btn-primary btn-sm float-right" href="/admin/invoice1" title="Close">
                          Close
                        </a>
                        @elseif($order_type == 'order')
                        <a class="btn btn-primary btn-sm float-right" href="/admin/invoice1" title="Close">
                          Close
                        </a>
                        @else
                        <a class="btn btn-primary btn-sm" href="/admin/invoice1" title="Close">
                          Close
                        </a>
                        @endif

                        &nbsp;
                        <a class="btn btn-sm btn-success" href="{!! route('admin.payment.invoice.create', $invoice_id) !!}" title=" Add Payment">
                            <i class="fa fa-plus"></i> Add Payment
                        </a>
                    </div>

                   <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="orders-table">

                                <thead>
                                    <tr class="bg-info">
                                        
                                        <th>id</th>
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
                                            <td>{!! date('dS M y', strtotime($o->date)) !!}</td>
                                            <td>{!! $o->reference_no !!}</td>
                                            <td>{{env('APP_CURRENCY')}} {{ number_format($o->amount,2) }}</td>
                                            <td ><label class="label label-primary">{!! $o->paidby->name !!}</label></td>

                                            <td>
                                                 <a href="{{route('admin.payment.invoice.show',$o->id)}}"><i class="fa  fa-sticky-note-o" title="show"></i> </a>
                                             
                                                    <i class="fa fa-edit text-muted" title="cannot edit"></i> 
                                                      <i class="fa fa-trash text-muted" title="cannot delete"></i> 
                                                 
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

@endsection

