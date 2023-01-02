@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Admin | POS | Transaction Dashboard
        <small>{{$description}}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>


<style type="text/css">
    .btn-circle.btn-xl {
        width: 125px;
        height: 125px;
        padding: 32px 37px;
        border-radius: 76px;
        font-size: 14px;
        text-align: left;
    }

    .btn-circle.btn-xl span {
        margin-left: -15px;
    }

</style>


<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">


                <div class="row">

                    <div class='col-md-12'>

                        @if(\Auth::user()->hasRole(['waiter','admins']))
                        <div class="col-md-3">
                            <!-- small box -->
                            <div class="small-box bg-olive">
                                <div class="inner">
                                    <h3>Restro</h3>

                                    <p>Take F&B Orders</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <a href="/admin/hotel/openresturantoutlets" class="small-box-footer">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>

                            </div>
                        </div>
                        @endif

                         @if(\Auth::user()->hasRole(['pos-user','admins']))
                        <div class="col-md-3">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>Outlets</h3>

                                    <p>Take Service Orders</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <a href="/admin/hotel/openoutlets" class="small-box-footer">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>

                            </div>
                        </div>
                        @endif
{{-- 
                        <div class="col-md-3">
                            <!-- small box -->
                            <div class="small-box bg-maroon">
                                <div class="inner">
                                    <h3>Check</h3>

                                    <p>Check or Modify Orders</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-check"></i>
                                </div>
                                <a href="/admin/hotel/outletsales" class="small-box-footer">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>

                            </div>
                        </div>
 --}}


                        <div class="col-md-3">
                            <!-- small box -->
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>Settle</h3>

                                    <p>Pay Now and Issue Bills</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-money"></i>
                                </div>
                                <a href="/admin/hotel/settlement/openoutlets" class="small-box-footer">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>

                            </div>
                        </div>

                                                <div class="col-md-3">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>Print Bill</h3>

                                    <p>Bill Reprints</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-print"></i>
                                </div>
                                <a href="/admin/hotel/reprintbills/openoutlets" class="small-box-footer">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>

                            </div>
                        </div>



                        <div class="col-md-3">
                            <!-- small box -->
                            <div class="small-box bg-purple">
                                <div class="inner">
                                    <h3>Invoice</h3>

                                    <p>Todays invoices</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-file"></i>
                                </div>
                                <a href="/admin/orders/todaypos/invoice" class="small-box-footer">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        @if(\Auth::user()->hasRole(['waiter','admins']))
                        <div class="col-md-3">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>Order Screen</h3>

                                    <p>Check Food Status</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-tv"></i>
                                </div>
                                <a href="/admin/pos/orders/selectoutlet/forstatus" class="small-box-footer">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        @endif

                        <!--  <button type="button" onclick="window.open('/admin/hotel/unsettlement/openoutlets','_self')" class="btn bg-purple btn-circle btn-xl"><span>Unsettlement</span></button> -->
                        <!-- <button type="button" onclick="window.open('/admin/hotel/modifysettlement/openoutlets','_self')" class="btn btn-success btn-circle btn-xl"><span>Modify Setlmnt.</span></button>  -->
                        <hr />
                        <div class="clearfix"> </div>

                        <button type="button" onclick="window.open('/admin/orders/allpos/invoice','_self')" class="btn bg-red btn-circle btn-xl"><span>All Invoice</span></button>
                        <button type="button" onclick="window.open('/admin/orders/todaypos/payments','_self')" class="btn bg-maroon btn-circle btn-xl"><span>Todays Payment</span></button>
                        <button type="button" onclick="window.open('/admin/orders/allpos/payments','_self')" class="btn btn-success btn-circle btn-xl"><span>All Payment</span></button>


                        <button type="button" onclick="window.open('{{ route('admin.order.edm_list') }}','_self')" class="btn btn-primary btn-circle btn-xl" style="text-align: center;"><span>EDM</span></button>
                        <button type="button" onclick="window.open('{{ route('admin.hotel.roomtransfer.item') }}','_self')" class="btn bg-purple btn-circle btn-xl" style="text-align: center;"><span>Room Transfer</span></button>
                        {{-- <button type="button" onclick="window.open(' /admin/sales/paymentslist','_self')" class="btn bg-olive btn-circle btn-xl"><span>Payment List</span></button> --}}
                        @if(\Auth::user()->hasRole(['waiter','admins']))
                        <button type="button" onclick="window.open('/admin/select/outlet/table/transfer','_self')" class="btn btn-warning btn-circle btn-xl"><span>Table Transfer</span></button>
                        @endif

                        <!--   <button type="button" onclick="window.open('#')" class="btn btn-info btn-circle btn-xl">Day End</button>  -->
                         @if(!\Auth::user()->hasRole(['pos-manager','admins']))
                        <hr />

                        <button type="button" onclick="window.open('/admin/hotel/pos-outlets/index','_self')" class="btn bg-purple btn-circle btn-xl"><span>Outlets Setting</span></button>
                        <button type="button" onclick="window.open('/admin/hotel/pos-menu/index','_self')" class="btn bg-teal btn-circle btn-xl">Add Menu</button>
                        <button type="button" onclick="window.open('/admin/producttypemaster','_self')" class="btn bg-maroon btn-circle btn-xl"><span>Product Type</span></button>
                        <button type="button" onclick="window.open('/admin/products','_self')" class="btn bg-info btn-circle btn-xl">Products</button>
                        <button type="button" onclick="window.open('/admin/hotel/pos-cost-center/index','_self')" class="btn bg-purple btn-circle btn-xl">Cost Center</button>
                        <button type="button" onclick="window.open('/admin/products','_self')" class="btn btn-warning btn-circle btn-xl">Stocks</button>

                      @endif



                    </div>


                </div>





            </div>
        </div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });

</script>

<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true
            , allowInputToggle: true
        });

    });

</script>


@endsection
