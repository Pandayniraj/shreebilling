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
                    <strong>Select Employee</strong>
                </div>
            </div>
            <div class="panel-body">

                <form id="form" action="/admin/stock/assign_report" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label">Select Employee <span class="required">*</span></label>

                        <div class="col-sm-5">
                            <select name="user_id" class="form-control select_box">
                                <option value="">Select Employee</option>
                                @foreach($users as $uk => $uv)
                                 <option value="{{ $uv->id }}" @if($user_id && $user_id == $uv->id) selected="selected" @endif>{{ $uv->first_name.' '.$uv->last_name }}</option>
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
    @if($assigns)
    <div class="col-sm-12" data-offset="0">
        <div class="panel panel-custom">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>{{ TaskHelper::getUserName($user_id) }}</strong>
                    <div class="pull-right hidden-print">
                        <a href="/admin/stock/generateAssignPdf/{{ $user_id }}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" target="_blank"><span><i class="fa fa-file-pdf"></i></span></a>
                        <a href="/admin/stock/printAssign/{{ $user_id }}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print"><span><i class="fa fa-print"></i></span></a>
                    </div>
                </div>
            </div>
            <!-- Table -->
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
