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
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Select Stock Category</strong>
                </div>
            </div>
            <div class="panel-body">

                <form id="form" action="/admin/stock/history" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label">Select Stock Category <span class="required">*</span></label>

                        <div class="col-sm-5">
                            <select name="stock_sub_category_id" class="form-control select_box">
                                <option value="">Select Stock Category</option>
                                @foreach($categories as $sck => $scv)
                                    <optgroup label="{{ $scv->stock_category }}">
                                    @foreach(StockHelper::getSubCategories($scv->stock_category_id) as $sk => $sv)
                                      <option value="{{ $sv->stock_sub_category_id }}" @if($stock_sub_category_id && $stock_sub_category_id == $sv->stock_sub_category_id) selected="selected" @endif>{{ $sv->stock_sub_category }}</option>
                                    @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" id="sbtn" value="1" name="flag" class="btn btn-primary">Go</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if($stocks)
    <div class="col-sm-12" data-offset="0">
        <div class="panel panel-custom">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="panel-title">
                    @if(isset($stock))
                    <strong>{{ StockHelper::getSubCatName($stock->stock_sub_category_id)->stock_sub_category }}</strong>
                    @endif
                </div>
            </div>
            <!-- Table -->
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="col-sm-1">SL</th>
                        <th>Item Name</th>
                        <th>Inventory</th>
                        <th>Buying Date</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($stocks as $sk => $sv)
                    <tr>
                        <td>{{ $sk+1 }}</td>
                        <td>{{ $sv->item_name }}</td>
                        <td>{{ $sv->total_stock }}</td>
                        <td>{{ $sv->buying_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

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
        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
@endsection
