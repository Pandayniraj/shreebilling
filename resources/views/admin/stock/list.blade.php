@extends('layouts.master')
@section('content')
<span class="text-muted pull-right">Total Investment:

    <?php  $amount =  \App\Models\Stock::select(DB::raw('sum(total_stock * unit_price) as grand_total'))->get();

ECHO number_format($amount[0]['grand_total']); 

?>

</span>

<style>
    .required {
        color: red;
    }

    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
    }

    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }

    .select2-container--bootstrap .select2-results__group {
        font-size: 15px !important;
        padding: 6px 3px !important;
    }

    .select2-container--bootstrap .select2-results__option .select2-results__option {
        color: #777 !important;
    }

</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Asset Lists

        <small> Check asset lists or add assets</small>
    </h1>
    {{ TaskHelper::topSubMenu('topsubmenu.hr')}}
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class="row">
    <div class="col-sm-12">

        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                <ul class="nav nav-tabs bg-danger">
                    <li @if(!\Request::segment(4) && \Request::get('type') !='new' ) class="active" @endif><a href="#all_stocks" data-toggle="tab" aria-expanded="true">All Stock</a>
                    </li>
                    @if(\Request::segment(4))
                    <li class="active"><a href="#new_stock" data-toggle="tab" aria-expanded="false">Edit Stock</a>
                    </li>
                    <li>
                        <a href="/admin/stock/list?type=new">New Stock +</a>
                    </li>
                    @else
                    <li @if(\Request::get('type')=='new' ) class="active" @endif><a href="#new_stock" data-toggle="tab" aria-expanded="false">New Stock +</a>
                    </li>
                    @endif
                </ul>
            </div>
            <div class="tab-content bg-white">

                <div class="tab-pane @if(!\Request::segment(4) && \Request::get('type') != 'new') active @endif" id="all_stocks" style="position: relative;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <td colspan="2">
                                    <select class="form-control select_box" required="" id='stock_category_filter'>
                                        <option value="">All Category</option>
                                        @foreach($stockCats as $sc)
                                        <option value="{{$sc->stock_category_id}}" @if(\Request::get('category')==$sc->stock_category_id)selected @endif>
                                            {{$sc->stock_category}}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr class="bg-olive">
                                <th>Id.</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Project Name </th>
                                <th>Department</th>
                                <th>Fiscal Yr</th>
                                <th>Unit Price</th>
                                <th>Location</th>
                                <th>Asset Num</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $key => $st)
                            <tr>
                                <td>{{$st->stock_id}}</td>
                                <td style="font-size:16px">{{$st->item_name}}</td>
                                <td>

                                    {{$st->subCategory->category->stock_category}}
                                    <small>{{$st->subCategory->stock_sub_category}}</small>

                                </td>
                                <td>{{$st->project->name}}</td>
                                <td>{{$st->deparment->deptname}}</td>
                                <td>{{$st->fiscalyear->fiscal_year}}</td>
                                <td>{{env('APP_CURRENCY')}}{{$st->unit_price}}</td>
                                <td>{{$st->location}}</td>
                                <td>{{$st->asset_number}}</td>
                                <td>
                                    <a href="/admin/stock/list/{{ $st->stock_id }}" class="btn btn-primary btn-xs" title="" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><span class="fa fa-edit"></span></a>
                                    <a href="/admin/stock/delete_stock/{{ $st->stock_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="text-align: center;"> {!! $stocks->appends(\Request::except('page'))->render() !!} </div>
                </div>

                <div class="tab-pane @if(\Request::segment(4)) active @elseif(\Request::get('type') == 'new') active @endif" id="new_stock" style="position: relative;">
                    <form role="form" data-parsley-validate="" enctype="multipart/form-data" action="/admin/stock/save_stock" method="post" class="form-horizontal form-groups-bordered">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="control-label col-sm-3">Stock Category<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="stock_sub_category_id" style="width: 100%" class="form-control select_box" required>
                                            <option value="">Select Stock Category</option>
                                            @foreach($stockCats as $sck => $scv)
                                            <optgroup label="{{ $scv->stock_category }}">
                                                @foreach(StockHelper::getSubCategories($scv->stock_category_id) as $sk => $sv)
                                                <option value="{{ $sv->stock_sub_category_id }}" @if(isset($stock) && $stock->stock_sub_category_id == $sv->stock_sub_category_id) selected="selected" @endif>{{ $sv->stock_sub_category }}</option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-sm-3">Project<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="project_id" style="width: 100%" class="form-control select_box">
                                            @foreach($projectslist as $projects)
                                            <option value="{{$projects->id}}" @if(isset($stock) && $stock->project_id == $projects->id) selected="selected" @endif>{{$projects->name}}</option>

                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-sm-3">Department<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="departments_id" style="width: 100%" class="form-control select_box" required>
                                            @foreach($departments as $dep)
                                            <option value="{{$dep->departments_id}}" @if(isset($stock) && $stock->departments_id == $dep->departments_id) selected="selected" @endif>{{$dep->deptname}}</option>

                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-sm-3">Type<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="types" style="width: 100%" class="form-control select_box" required>
                                            <option value="fixed">Fixed</option>
                                            <option value="consumable" @if(isset($stock) && $stock->types == 'consumable') selected="" @endif>Consumable</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-1" class="control-label col-sm-3 ">Buying Date<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input required="" type="text" class="form-control datepicker" name="buying_date" value="@if( isset($stock)) {{ $stock->buying_date }}@endif" data-format="yyyy/mm/dd" data-parsley-id="6" placeholder="Buying Date">
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-calendar"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-1" class="col-sm-3 control-label">Item Name<span class="required"> * </span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="item_name" class="form-control" placeholder="Item name" id="query" value="@if( isset( $stock)){{ $stock->item_name }}@endif" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-1" class="col-sm-3 control-label">Inventory (Qty)<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input required type="number" data-parsley-type="number" name="total_stock" placeholder="Total Stocks" class="form-control" value="@if(isset($stock)){{ $stock->total_stock }}@endif">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-1" class="col-sm-3 control-label">Unit Price</label>
                                    <div class="col-sm-9">

                                        <div class="input-group">
                                            <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                                            <input type="text" name="unit_price" class="form-control" placeholder="Unit Price" id="query" value="@if(isset($stock)){{ $stock->unit_price }}@endif" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-sm-3">Condition<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="conditions" style="width: 100%" class="form-control select_box" required>
                                            <option value="new">New</option>
                                            <option value="second_hand" @if(isset($stock) && $stock->conditions == 'second_hand') selected="" @endif>Second Hand</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-1" class="col-sm-3 control-label">Item model</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="item_model" class="form-control" placeholder="Item Models" value="@if(isset($stock))) {{ $stock->item_model }}@endif" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-1" class="col-sm-3 control-label">Location</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="location" class="form-control" placeholder="Locations" value="@if(isset($stock)){{ $stock->location }}@endif" id='locations' />
                                    </div>
                                </div>

                                @if(isset($stock) && $stock->asset_number)

                                <div class="form-group">
                                    <label for="field-1" class="col-sm-3 control-label">Barcode</label>
                                    <?php
                    
                                    $code =   $stock->asset_number; 
                                    echo '<img width="150" src="data:image/png;base64,'. \DNS1D::getBarcodePNG($code, "C128") . '" alt="barcode" class="bcimg"/>';
                                    
                                    ?>
                                </div>

                                @endif


                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="field-1" class="col-sm-4 control-label">Asset Number</label>
                                    <div class="col-sm-8">
                                        <input required type="text" name="asset_number" class="form-control" placeholder="Assets number" readonly="readonly" value="@if(isset($stock)){{ $stock->asset_number }} @else {{date('hi').mt_rand(10000,99999)}}@endif" />
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-sm-4">Supplier<span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="supplier" style="width: 100%" class="form-control select_box">
                                            <option value="">Select Supplier</option>
                                            @foreach($suplier as $key => $sup )
                                            <option value="{{$sup->id}}" @if( isset($stock) && $sup->id ==
                                                $stock->supplier) selected="" @endif>{{$sup->name}}[{{$sup->vat}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="field-1" class="col-sm-4 control-label">Invoice Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="invoice_number" class="form-control" placeholder="Invoice number" value="@if(isset($stock)){{ $stock->invoice_number }} @endif" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-1" class="col-sm-4 control-label">Unit Salvage Value</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="unit_salvage_value" class="form-control" placeholder="Unit Salvage Value" step='any' value="@if(isset($stock)){{ $stock->unit_salvage_value }}@endif" />
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="field-1" class="control-label col-sm-4 ">Service Date</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="service_date" value="@if(isset($stock)){{ $stock->service_date }}@endif" data-format="yyyy/mm/dd" data-parsley-id="6" placeholder="Service Date">
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-calendar"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-1" class="col-sm-4 control-label"> Depreciation Rate</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="depreciation_rate" class="form-control" placeholder="Depreciation Rate" step='any' value="@if(isset($stock)){{ $stock->depreciation_rate }}@endif" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-1" class="col-sm-4 control-label"> Annual Rate</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                                            <input type="number" name="annual_depreciation" class="form-control" placeholder="Annual Rate" step='any' value="@if(isset($stock)){{ $stock->annual_depreciation }}@endif" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-1" class="col-sm-4 control-label"> Accumulated Rate</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                                            <input type="number" name="accumulated_depreciation" class="form-control" placeholder="Accumulated Rate" step='0.01' value="@if(isset($stock)){{ $stock->accumulated_depreciation }}@endif" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-1" class="col-sm-4 control-label">Asset Status</label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" name="asset_status" class="form-control" placeholder="" step='any' value="@if(isset($stock)){{ $stock->asset_status }}@endif" />
                                    </div>
                                </div>


                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-5">
                                        <button type="submit" id="sbtn" class="btn btn-primary" id="i_submit">Save</button>
                                    </div>
                                </div>
                                <!-- Hidden input field-->
                                @if(isset($stock))
                                <input type="hidden" name="stock_id" value="{{ $stock->stock_id }}">
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')

<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>

<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true,

        });

        $('.select_box').select2({
            theme: 'bootstrap'
        , });

        $('[data-toggle="tooltip"]').tooltip();

        $('#stock_category_id').on('change', function() {
            $('#stock_category').css('border', '1px solid #ccc');
            $('#stock_sub_category').css('border', '1px solid #ccc');
            if ($(this).val() == '') {
                $('#new_stock_div').css('display', 'block');
                $('#sub_cat_label span').css('display', 'none');
            } else {
                $('#new_stock_div').css('display', 'none');
                $('#sub_cat_label span').css('display', 'block');
                $('#sub_cat_label span').css('float', 'right');
            }
        });
    });

    $('#create_category_btn').click(function() {
        var flag = 0;
        $('#stock_category').css('border', '1px solid #ccc');
        $('#stock_sub_category').css('border', '1px solid #ccc');

        var stock_category_id = $('#stock_category_id').val();
        if (stock_category_id == '') {
            if ($('#stock_category').val().trim() == '') {
                flag++;
                $('#stock_category').css('border', '1px solid red');
            }
        } else {
            if ($('#stock_sub_category').val().trim() == '') {
                flag++;
                $('#stock_sub_category').css('border', '1px solid red');
            }
        }
        if (flag == 0)
            $('#save_category').submit();
        else
            return false;
    });

    $("#locations").autocomplete({
        source: "/admin/getCities"
        , minLength: 2,

    });
    $('#stock_category_filter').change(function() {
        let id = $(this).val();
        location.href = `/admin/stock/list?category=${id}`;
    });

</script>
@endsection
