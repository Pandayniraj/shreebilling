@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title !!}

                <small>{!! $page_description !!}</small>
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
                         <a class="btn btn-primary btn-sm float-right" href="/admin/orders?type=quotation" title="Close">
                          Close
                         </a>
                        @elseif($order_type == 'proforma_invoice')
                        <a class="btn btn-primary btn-sm float-right" href="/admin/orders?type=invoice" title="Close">
                          Close
                        </a>
                        @elseif($order_type == 'order')
                        <a class="btn btn-primary btn-sm float-right" href="/admin/orders?type=order" title="Close">
                          Close
                        </a>
                        @else
                        <a class="btn btn-primary btn-sm" href="/admin/orders?type=quotation" title="Close">
                          Close
                        </a>
                        @endif

                        &nbsp;
                        <a class="btn btn-social btn-foursquare" href="{!! route('admin.payment.orders.create', $purchase_id) !!}" title="Add Payment">
                            <i class="fa fa-plus"></i>Add Payment
                        </a>
                    </div>

                   <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="orders-table">

                                <thead>
                                    <tr>
                                        <th style="text-align: center;">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
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
                                            <td align="center">{!! Form::checkbox('chkClient[]', $o->id); !!}</td> 
                                            <td>{!! $o->id !!}</td>
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

@endsection

