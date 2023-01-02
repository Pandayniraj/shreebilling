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

    .select2-container--bootstrap .select2-results__group { font-size: 15px !important; padding: 6px 3px !important; }
    .select2-container--bootstrap .select2-results__option .select2-results__option { color: #777 !important; }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<div class="row">
    <div class="col-sm-12">

        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                <ul class="nav nav-tabs">
                    <li @if(!\Request::segment(4)) class="active" @endif><a href="#all_stocks_assign" data-toggle="tab" aria-expanded="true">Assign Stock List</a>
                    </li>
                    <li @if(\Request::segment(4)) class="active" @endif><a href="#new_stock_assign" data-toggle="tab" aria-expanded="false">Assign Stock</a>
                    </li>
                    <li>
                        <div class="pull-right hidden-print">
                            <a href="/admin/stock/generateAssignPdf" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" target="_blank"><span><i class="fa fa-file-pdf"></i></span></a>
                            <a href="/admin/stock/printAssign" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print"><span><i class="fa fa-print"></i></span></a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="tab-content bg-white">

                <div class="tab-pane @if(!\Request::segment(4)) active @endif" id="all_stocks_assign" style="position: relative;">
                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="col-sm-1">SL</th>
                                <th>Item Name</th>
                                <th>Stock Category</th>
                                <th>Assign Quantity</th>
                                <th>Assigned User</th>
                                <th>Assign Date</th>
                                <th class="col-sm-1 hidden-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assigns as $ak => $av)
                            <tr>
                                <td>{{ $ak+1 }}</td>
                                <?php $assign_stock = StockHelper::getStock($av->stock_id); ?>
                                <td>{{ $assign_stock->item_name }}</td>
                                <td>{{ StockHelper::getCatSubCat($assign_stock->stock_sub_category_id) }}</td>
                                <td>{{ $av->assign_inventory }}</td>
                                <?php $user = $av->user; ?>
                                <td>{{ $user->first_name.' '.$user->last_name }}</td>
                                <td>{{ $av->assign_date }}</td>
                                <td class="hidden-print">
                                    <a href="/admin/stock/delete_assign_stock/{{ $av->assign_item_id }}" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane @if(\Request::segment(4)) active @endif" id="new_stock_assign" style="position: relative;">
                  <form role="form" data-parsley-validate="" enctype="multipart/form-data" action="/admin/stock/save_assign" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group ">
                        <label class="control-label col-sm-3">Stock Category<span class="required">*</span></label>
                        <div class="col-sm-5">
                            <select name="stock_sub_category_id" id="stock_sub_category_id" style="width: 100%" class="form-control select_box" required>
                              <option value="">Select Stock Category</option>
                              @foreach($categories as $sck => $scv)
                              <optgroup label="{{ $scv->stock_category }}">
                                @foreach(StockHelper::getSubCategories($scv->stock_category_id) as $sk => $sv)
                                  <option value="{{ $sv->stock_sub_category_id }}">{{ $sv->stock_sub_category }}</option>
                                @endforeach
                              </optgroup>
                              @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label">Item Name<span class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required class="form-control" name="stock_id" id="stock_id" required>
                                <option value="">Select Item Name</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label class="control-label col-sm-3">Employee Name<span class="required">*</span></label>
                        <div class="col-sm-5">
                            <select name="user_id" id="user_id" style="width: 100%" class="form-control select_box" required>
                              <option value="">Select Employee</option>
                              @foreach($users as $uk => $uv)
                              <option value="{{ $uv->id }}">{{ $uv->first_name.' '.$uv->last_name }}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label">Assign Quantity<span class="required">*</span></label>

                        <div class="col-sm-5">
                            <input required type="number" data-parsley-type="number" name="assign_inventory" id="assign_inventory" placeholder="" class="form-control" value="Enter Assign Quantity" min="1" max="10">
                        </div>
                        <div class="col-sm-3" id="assign_qty_div" style="display: none;">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="control-label col-sm-3 ">Assign Date<span class="required">*</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input required="" type="text" class="form-control datepicker" name="assign_date" value="Enter Assign Date" data-format="yyyy/mm/dd" data-parsley-id="6">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5">
                            <button type="submit" id="sbtn" class="btn btn-primary">Save</button>
                        </div>
                    </div>


                  </form>
                </div>

            </div>
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

<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true
        });

        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('#DataTables').DataTable({
            pageLength: 50
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('#stock_sub_category_id').on('change', function() {
            $('#assign_qty_div').css('display', 'none');
            if($(this).val() != '')
            {
                $.ajax({
                    url: "/admin/stock/getStockBySubCategory",
                    data: { stock_sub_category_id: $(this).val() },
                    dataType: "json",
                    success: function( data ) {
                        var result = data.data;
                        $('#stock_id').html(result);
                    }
                });
            }
            else
            {
                $('#stock_id').html('<option value="">Select Item Name</option>');
            }
        });

        $('#stock_id').on('change', function() {
            $('#assign_qty_div').css('display', 'none');
            var total_stock = $('option:selected', this).attr('data-stock');
            if($(this).val() != '')
            {
                $.ajax({
                    url: "/admin/stock/getStockQuantity",
                    data: { stock_id: $(this).val() },
                    dataType: "json",
                    success: function( data ) {
                        var result = data.data;
                        var in_stock = total_stock - result;
                        $('#assign_qty_div').html('('+in_stock+' items reamin in stock.)');
                        $('#assign_qty_div').css('display', 'block');
                        $('#assign_inventory').attr('max', in_stock);
                    }
                });
            }
        });

    });
</script>
@endsection
