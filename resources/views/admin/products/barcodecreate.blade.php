@extends('layouts.master')
@section('content')

<style>
    .ui-autocomplete {
        position: absolute !important;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 0;
        margin: 2px 0 0;
        list-style: none;
        font-size: 14px;
        text-align: left;
        background-color: #ffffff !important;
        border: 1px solid #cccccc !important;
        border: 1px solid rgba(0, 0, 0, 0.15) !important;
        border-radius: 4px;
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175) !important;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175) !important;
        background-clip: padding-box;
    }

    .ui-autocomplete>li {
        display: block !important;
        padding: 3px 20px !important;
        clear: both !important;
        font-weight: normal !important;
        line-height: 1.42857143 !important;
        background-color: #2fbf5f !important;
        color: #c92c2c !important;
        white-space: nowrap !important;
    }

    .ui-state-hover,
    .ui-state-active,
    .ui-state-focus {
        text-decoration: none !important;
        color: #e60026 !important;
        background-color: #b6e0b4 !important;
        cursor: pointer;
    }

    .ui-helper-hidden-accessible {
        border: 0;
        clip: rect(0 0 0 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }

</style>


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class="col-md-12">
    <div class="box">
        <div class="box-header no-print">
            <h1 class="box-title">Print Barcode/label</h1>
            <p class="introtext no-print">
                Please fill in the information below. </p>
            <div class="box-tools pull-right">
                <a href="#" id="print-icon" onclick="printDiv('print')" class="tip" title="Print">
                    <i class="icon fa fa-print"></i> Click to print barcodes </a>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="well well-sm no-print">
                        <div class="form-group">
                            <label for="add_item">Add Product</label> <input type="text" name="add_item" value="" class="form-control" id="add_item" placeholder="Add Item">
                        </div>
                        <form action="/admin/products/barcode/{{$id}}/post" id="barcode-print-form" data-toggle="validator" method="post" accept-charset="utf-8">
                            {{csrf_field()}}
                            <div class="controls table-controls">
                                <table id="bcTable" class="table items table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product Name (Product Code)</th>
                                            <th>Quantity</th>
                                            <th>Variants</th>
                                            <th class="text-center" style="width:30px;">
                                                <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="multipleDiv">
                                        </tr>

                                        @if(isset($requests))

                                        @foreach($products_all as $key => $value)
                                        <?php 
                                              $products_detail = \App\Models\Product::find($products_all[$key]);
                                         ?>
                                        <tr>
                                            <td><input name="product[]" type="hidden" value="{{$products_detail->id}}">{{$products_detail->name}} ({{$products_detail->product_code}})</td>
                                            <td><input class="form-control quantity " name="quantity[]" type="number" value="{{$quantity[$key]}}" onclick="this.select();"></td>
                                            <td></td>
                                            <td class="text-center"> <a href="javascript::void(1);" style="width: 10%;" readonly>
                                                    <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                        @else

                                        <tr>
                                            <td><input name="product[]" type="hidden" value="{{$products->id}}">{{$products->name}} ({{$products->product_code}})</td>
                                            <td><input class="form-control quantity " name="quantity[]" type="number" value="100" data-id="31" data-item="31" id="quantity_31" onclick="this.select();"></td>
                                            <td></td>
                                            <td class="text-center"> <a href="javascript::void(1);" style="width: 10%;" readonly>
                                                    <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                </a></td>
                                        </tr>
                                        @endif


                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group">
                                <label for="style">Style</label> <select name="style" class="form-control tip" id="style" required="required">
                                    <option value="">Select Style</option>

                                    <option value="44" selected="selected">44 per sheet (a4) (2.48" x 1.334")</option>

                                </select>
                                <div class="row cf-con" style="margin-top: 10px; display: none;">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" name="cf_width" value="" class="form-control" id="cf_width" placeholder="Width">
                                                <span class="input-group-addon" style="padding-left:10px;padding-right:10px;">Inches</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" name="cf_height" value="" class="form-control" id="cf_height" placeholder="Height">
                                                <span class="input-group-addon" style="padding-left:10px;padding-right:10px;">Inches</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <select name="cf_orientation" class="form-control" id="cf_orientation" placeholder="Orientation">
                                                <option value="0">Portrait</option>
                                                <option value="1">Landscape</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <span class="help-block">Please don't forget to set correct page size and margin for your printer. You can set right and bottom to 0 while left and top margin can be adjusted according to your need.</span>
                                <div class="clearfix"></div>
                            </div>

                            <div class="btn-group pull-right">
                                <input type="submit" name="print" value="Update" class="btn btn-success">

                            </div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                    @if(isset($products_all) && count($products_all)>0)

                    <div id="barcode-con">
                        <div id="print">

                            <style>
                                .barcodea4 {
                                    width: 10.70in;
                                    min-height: 11.6in;
                                    display: block;
                                    border: 5px solid #CCC;
                                    margin: 10px auto;
                                    padding: 0.1in 0 0 0.1in;
                                    page-break-after: always;
                                    overflow: auto
                                }

                                .barcodea4 .style24 {
                                    width: 2.48in;
                                    height: 1.28in;
                                    margin-left: 0.079in;
                                    padding-top: 0.05in;
                                }

                                .barcodea4 .item {
                                    display: block;
                                    overflow: hidden;
                                    text-align: center;
                                    border: 1px dotted #CCC;
                                    font-size: 12px;
                                    line-height: 14px;
                                    text-transform: uppercase;
                                    float: left;
                                }

                                img {
                                    vertical-align: middle;
                                }

                                .barcode .barcode_site {
                                    font-weight: bold;
                                }

                                .barcodea4 .barcode_site,
                                .barcodea4 .barcode_name,
                                .barcodea4 .barcode_image,
                                .barcodea4 .variants {
                                    display: block;
                                }

                                .barcodea4 .style24 .barcode_site,
                                .barcodea4 .style24 .barcode_name {
                                    font-size: 14px;
                                }

                            </style>
                            <button type="button" onclick="printDiv('print')" class="btn btn-success btn-block tip no-print" title="Print"><i class="icon fa fa-print"></i> Print</button>


                            @foreach($products_all as $key => $value)

                            <?php
                                $details = \App\Models\Product::find($products_all[$key]);
                                ?>

                            <div class="barcodea4">
                                @for($i=0;$i<$quantity[$key];$i++) <div class="item style24">
                                    <span class="barcode_site">{{env('SHORT_NAME')}}</span>
                                    <span class="barcode_name">{{$details->name}} </span>
                                    <span class="barcode_price">{{env('APP_CURRENCY')}} {{number_format($details->price,2)}}</span>
                                    <span class="barcode_image">
                                        <?php
                                            $id = $details->id.'';
                                            $code = 'MN'.str_pad($id, 6, '0', STR_PAD_LEFT); 
                                            echo $code."<br/> ";
                                            echo '<img src="data:image/png;base64,'. \DNS1D::getBarcodePNG($code, "C128") . '" alt="'.$details->product_code.'" class="bcimg"/>';
                                            
                                        ?>

                                    </span>
                            </div>
                            @endfor


                        </div>
                        @endforeach

                    </div>
                </div>


                @endif
            </div>
        </div>
    </div>
    <!-- /.box -->
</div>

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/intl-tel-input/build/css/intlTelInput.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset("/bower_components/intl-tel-input/build/js/intlTelInput-jquery.min.js") }}"></script>
<script>
    $(function() {
        $('#add_item').autocomplete({
            source: "/admin/getProducts"
            , minLength: 1
        });
    });

    $(document).on('change', '#add_item', function() {
        var parentDiv = $(this).val();
        //console.log(this.value);
        var el = $(this);
        if (this.value != 'NULL') {
            $.ajax({
                url: "/admin/products/barcode/getprintproduct"
                , data: {
                    product_name: this.value
                , }
                , dataType: "json"
                , success: function(data) {
                    var purchasedetailinfo = data.purchasedetailinfo;
                    if (purchasedetailinfo != 0) {
                        $(".multipleDiv").after(purchasedetailinfo);
                        $('#add_item').val('');
                    } else {

                        alert('Product Cannot Be Found in Database');

                        $('#add_item').val('');
                    }


                }
            });
        } else {
            parentDiv.find('.price').val('');
            parentDiv.find('.total').val('');
            parentDiv.find('.tax_amount').val('');
        }
    });

    $(document).on('click', '.remove-this', function() {
        $(this).parent().parent().parent().remove();
    })

</script>


<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;

    }

</script>
@endsection