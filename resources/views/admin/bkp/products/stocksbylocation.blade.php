@extends('layouts.master')
@section('content')

<style>
    .required { color: red; }
    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
    }
    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{$page_title ?? 'Page Title'}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    
                </div>

            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/product/stocks_by_location" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="row">
                       
                        <div class="col-sm-3">
                          <label class="control-label">Store</label>
                            {!! Form::select('store_id',[''=>'Select Store']+$stores, $current_store ?? null, ['class'=>'form-control']) !!}
                        </div>

                    </div>
                    <br>
                   
                    <div class="row">
                        <div class="col-sm-4 ">
                            <button type="submit" id="sbtn" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(isset($transations))
<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <strong> List of Stocks </strong>
                        
                    </h3>
                </div>
                <table id="" class="table table-bordered std_table">
                    <thead>
                     <tr class="bg-purple">
                      <th class="text-center">Id</th>
                      <th class="text-center">Product</th>
                      <th class="text-center">Tran No #</th>
                        <th class="text-center">Tran Type</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Quantity In</th>
                        <th class="text-center">Quantity Out</th>
                        <th class="text-center">Avg Price</th>
                        <th class="text-center"> <i class="fa fa- fa-hand-paper-o"></i> On Hand</th>
                    </tr>
                    </thead>
                        <tbody>  
                     <?php
                    $sum = 0;
                    $StockIn = 0;
                    $StockOut = 0;
                    ?>
                    @if(count($transations)>0)
                    @foreach($transations as $result)
                      <tr>
                        <td align="center">
                          <a href="{{ $result->getUrl() }}" target="_blank"> {{$result->id}}</a></td>
                      <td style="font-size: 16.5px" align="left">{{$result->name}}</td>
                      <td align="center"><a href="{{ $result->getUrl() }}" target="_blank">{{$result->getOrdersBill()}}</a></td>
                      <td align="center">

                        <?php 
                           $reasons = \App\Models\AdjustmentReason::all();
                        ?>

                        @if($result->trans_type == PURCHINVOICE)
                         Purchase
                        @elseif($result->trans_type == SALESINVOICE)
                          Sale
                        @elseif($result->trans_type == STOCKMOVEIN)
                         Transfer
                        @elseif($result->trans_type == STOCKMOVEOUT)
                        Transfer
                        @endif

                        @foreach($reasons as $reason)

                          @if($reason->trans_type == $result->trans_type)

                          {{ucwords($reason->name)}}

                          @endif
                          
                        @endforeach

                      </td>
                      <td align="center">{{$result->tran_date}}</td>
                      <td align="center">{{$result->location_name}}</td>
                      <td align="center">
                        @if($result->qty >0) 
                          {{$result->qty}}
                          <?php
                          $StockIn +=$result->qty;
                          ?>
                        @else
                        -
                        @endif
                      </td>
                      <td align="center">
                        @if($result->qty <0 )
                          {{str_ireplace('-','',$result->qty)}} 
                          <?php
                          $StockOut +=$result->qty;
                          ?>
                        @else
                        -
                        @endif
                      </td>
                      <td align="center">{{$sum += $result->qty}}</td>
                    </tr>
                    @endforeach
                     <tr><td colspan="6" align="right">Total</td><td align="center">{{$StockIn}}</td><td align="center">{{str_ireplace('-','',$StockOut)}}</td><td align="center">{{$StockIn+$StockOut}}</td></tr>
                    @else
                    <tr>
                        <td colspan="9" class="text-center text-danger">No Transaction Yet</td>
                    </tr>
                   @endif

                </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endif

@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>


<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

<script type="text/javascript">
    $(function() {
        $('#date_in').datetimepicker({
            format: 'YYYY-MM',
            sideBySide: true
        });

        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
