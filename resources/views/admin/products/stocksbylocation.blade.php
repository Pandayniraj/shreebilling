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
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.css") }}" rel="stylesheet" type="text/css" />

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
                            <label class="control-label">Products</label>
                              {!! Form::select('product_id',[''=>'Select Product']+$products, $current_product ?? null, ['class'=>'form-control select2']) !!}
                          </div>
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
                <table class="table table-hover table-striped table-responsive" id="clients-table">
      <thead>
        <tr class="bg-purple">
          <th class="text-center">Id</th>
          <th class="text-center">Product</th>
          <th class="text-center">Tran #</th>
          <th class="text-center">Tran Type</th>
          <th class="text-center">Date</th>
          <th class="text-center">Store</th>

          <th class="text-center">In</th>
          <th class="text-center">Out</th>
          <th class="text-center bg-primary">Closing(Bottle-Wise)</th>
          <th class="text-center bg-primary">Closing(Case-Wise)</th>

          <th class="text-center">cost/unit</th>

        </tr>
      </thead>
      <tbody>
        <?php
        $sum = 0;
        $sumbottlewise=0;
        $StockIn = 0;
        $StockOut = 0;
        ?>
        @if(count($transations)>0)
        @foreach($transations as $result)
        
        <tr>
          <td align="center">{{$result->id}}</td>
          <td style="font-size: 16.5px" align="left"><a href="/admin/products/{{$result->pid}}/edit?op=trans" target="_blank"> {{$result->pname}}</a></td>
          <td align="center">{{$result->order_no}}</td>
          <td align="center">
            @if($result->trans_type == PURCHORDER)
            PURCHORDER
            @elseif($result->trans_type == PURCHINVOICE)
            PURCHINVOICE
            @elseif($result->trans_type == GRN)
            GRN
            @elseif($result->trans_type == SALESORDER)
            SALESORDER
            @elseif($result->trans_type == SALESINVOICE)
            SALESINVOICE
            @elseif($result->trans_type == OTHERSALESINVOICE)
            OTHERSALESINVOICE
            @elseif($result->trans_type == DELIVERYORDER)
            DELIVERYORDER
            @elseif($result->trans_type == STOCKMOVEIN)
            STOCKMOVEIN
            @elseif($result->trans_type == STOCKMOVEOUT)
            STOCKMOVEOUT
            @elseif($result->trans_type == OPENINGSTOCK)
            OPENINGSTOCK
            @endif

          </td>
          <td align="center">{{$result->tran_date}}</td>
          <td align="center">{{$result->name}}</td>

          <td align="center">
            @if($result->qty >0 )
            {{$result->qty * $result->unit->qty_count}}
            <?php
            $StockIn +=$result->qty;
            ?>
            @else
            -
            @endif
          </td>
          <td align="center">
            @if($result->qty <0 )
            {{str_ireplace('-','',$result->qty * $result->unit->qty_count)}}
            <?php
            $StockOut +=$result->qty;
            ?>
            @else
            -
            @endif
          </td>
          <?php
            $sumbottlewise += $result->qty * $result->unit->qty_count;
          ?>
        <td align="center">{{$sumbottlewise}}</td>
        <?php
        $unitid= \App\Models\Product::where('id', $result->stock_id)->first()->product_unit;
        $unitqty= \App\Models\ProductsUnit::where('id', $unitid)->first()->qty_count;
        $sum=$sumbottlewise/$unitqty;
?>
        <td align="center">{{number_format($sum,2)}}</td>
          <td>
                        <?php

                        $stock_moves=\App\Models\StockMove::selectRaw('sum(qty) as total_qty,sum(price*qty) as total_price')
                                ->where('trans_type',102)
                                //->orWhere('trans_type',401)
                                  ->where('stock_id',$result->stock_id)
                                  ->first();

                        $avg_price = $stock_moves->total_qty!=0?(($stock_moves->total_price)/$stock_moves->total_qty):0;

                        ?>

                        {{ $avg_price }}
                    </td>
        </tr>
        @endforeach

        @else
        <tr>
          <td colspan="6" class="text-center text-danger">No Transaction Yet</td>
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


<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>


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

        $('.select2').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
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
              'ordering'    : true,
              'info'        : true,
              'autoWidth'   : true,
              "paging"      : false
          });
      });

  </script>
@endsection
