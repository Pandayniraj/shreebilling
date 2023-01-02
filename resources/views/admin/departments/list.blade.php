@extends('layouts.master')
@section('content')
<span class="text-muted pull-right">Total Investment: 

<?php  $amount =  \App\Models\Stock::select(DB::raw('sum(total_stock * unit_price) as grand_total'))->get();

ECHO number_format($amount[0]['grand_total']);

?>

</span>

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
                    <li @if(!\Request::segment(4)) class="active" @endif><a href="#all_stocks" data-toggle="tab" aria-expanded="true">All Stock</a>
                    </li>
                    <li @if(\Request::segment(4)) class="active" @endif><a href="#new_stock" data-toggle="tab" aria-expanded="false">New Stock</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content bg-white">

                <div class="tab-pane @if(!\Request::segment(4)) active @endif" id="all_stocks" style="position: relative;">
                   <!-- NESTED-->
                   <div class="box" style="" data-collapsed="0">
                      <div class="box-body">
                         <!-- Table -->
                         <div class="row">
                            <?php $stock_category_id = 0; ?>
                            @foreach($allStockCats as $ak => $av)
                              @if($stock_category_id != $av->stock_category_id)
                                <?php $stock_category_id = $av->stock_category_id; ?>
                                <div class="col-sm-6">
                                  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                                      <div class="panel panel-default">
                                          <div class="panel-heading" role="tab" id="headingOne">
                                              <h4 class="panel-title">
                                                  <a data-toggle="collapse" class="collapsed" data-parent="#accordion" href="#{{$ak}}" aria-expanded="true" aria-controls="collapseOne">
                                                    <i class="fa fa-plus"> </i> {{ StockHelper::getCatName($stock_category_id)->stock_category }} </a>
                                              </h4>
                                          </div>
                                          <div id="{{ $ak }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="true" style="">
                                            <?php $stock_sub_category_id = []; ?>
                                            @foreach($allStockCats as $ak1 => $av1)
                                              @if($av1->stock_category_id == $stock_category_id && !in_array($av1->stock_sub_category_id, $stock_sub_category_id))

                                                <?php array_push($stock_sub_category_id, $av1->stock_sub_category_id); ?>
                                                <div class="panel-body">
                                                    <table class="table table-bordered" style="margin-bottom: 0px;">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="3" style="background-color: #E3E5E6;color: #000000 ">
                                                                    <strong>{{ $av1->stock_sub_category }}</strong>
                                                                  </th>
                                                            </tr>
                                                            <tr style="font-size: 13px;color: #000000">
                                                                <th>Item Name</th>
                                                                <th>Unit Price</th>
                                                                <th>Total Stock</th>
                                                                <th class="col-sm-2">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="margin-bottom: 0px;background: #FFFFFF;font-size: 12px;">
                                                          @foreach(StockHelper::getStockSubCategory($av1->stock_sub_category_id) as $sk => $sv)
                                                            <tr>
                                                                <td>{{ $sv->item_name }}</td>
                                                                <td>{{ $sv->unit_price }}</td>
                                                                <td>{{ $sv->total_stock }}</td>
                                                                <td>
                                                                    <a href="/admin/stock/list/{{ $sv->stock_id }}" class="btn btn-primary btn-xs" title="" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><span class="fa fa-pen-square"></span></a>
                                                                    <a href="/admin/stock/delete_stock/{{ $sv->stock_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');"
                                                                        data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                          @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                              @endif
                                            @endforeach
                                          </div>
                                      </div>
                                  </div>
                                </div>
                              @endif
                            @endforeach
                         </div>
                      </div>
                   </div>
                </div>

                <div class="tab-pane @if(\Request::segment(4)) active @endif" id="new_stock" style="position: relative;">
                  <form role="form" data-parsley-validate="" enctype="multipart/form-data" action="/admin/stock/save_stock" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group ">
                        <label class="control-label col-sm-3">Stock Category<span class="required">*</span></label>
                        <div class="col-sm-5">
                            <select name="stock_sub_category_id" style="width: 100%" class="form-control select_box" required>
                              <option value="">Select Stock Category</option>
                              @foreach($stockCats as $sck => $scv)
                              <optgroup label="{{ $scv->stock_category }}">
                                @foreach(StockHelper::getSubCategories($scv->stock_category_id) as $sk => $sv)
                                  <option value="{{ $sv->stock_sub_category_id }}" @if($stock && $stock->stock_sub_category_id == $sv->stock_sub_category_id) selected="selected" @endif>{{ $sv->stock_sub_category }}</option>
                                @endforeach
                              </optgroup>
                              @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="control-label col-sm-3 ">Buying Date<span class="required">*</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input required="" type="text" class="form-control datepicker" name="buying_date" value="@if($stock){{ $stock->buying_date }}@endif" data-format="yyyy/mm/dd" data-parsley-id="6">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label">Item Name<span class="required"> * </span></label>
                        <div class="col-sm-5">
                            <input required type="text" name="item_name" class="form-control" placeholder="" id="query" value="@if($stock){{ $stock->item_name }}@endif" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label">Inventory <span class="required">*</span></label>
                        <div class="col-sm-5">
                            <input required type="number" data-parsley-type="number" name="total_stock" placeholder="" class="form-control" value="@if($stock){{ $stock->total_stock }}@endif">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label">Unit Price<span class="required"> * </span></label>
                        <div class="col-sm-5">
                            <input required type="text" name="unit_price" class="form-control" placeholder="" id="query" value="@if($stock){{ $stock->unit_price }}@endif" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="sbtn" class="btn btn-primary" id="i_submit">Save</button>
                        </div>
                    </div>
                    <!-- Hidden input field-->
                    @if($stock)
                    <input type="hidden" name="stock_id" value="{{ $stock->stock_id }}">
                    @endif

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

        $('[data-toggle="tooltip"]').tooltip();

        $('#stock_category_id').on('change', function() {
            $('#stock_category').css('border', '1px solid #ccc');
            $('#stock_sub_category').css('border', '1px solid #ccc');
            if($(this).val() == '')
            {
                $('#new_stock_div').css('display', 'block');
                $('#sub_cat_label span').css('display', 'none');
            }
            else
            {
                $('#new_stock_div').css('display', 'none');
                $('#sub_cat_label span').css('display', 'block');
                $('#sub_cat_label span').css('float', 'right');
            }
        });
    });

    $('#create_category_btn').click(function () {
        var flag = 0;
        $('#stock_category').css('border', '1px solid #ccc');
        $('#stock_sub_category').css('border', '1px solid #ccc');

        var stock_category_id = $('#stock_category_id').val();
        if(stock_category_id == '')
        {
            if($('#stock_category').val().trim() == '')
            {
                flag++;
                $('#stock_category').css('border', '1px solid red');
            }
        }
        else
        {
            if($('#stock_sub_category').val().trim() == '')
            {
                flag++;
                $('#stock_sub_category').css('border', '1px solid red');
            }
        }
        if(flag == 0)
            $('#save_category').submit();
        else
            return false;
    });
</script>
@endsection
