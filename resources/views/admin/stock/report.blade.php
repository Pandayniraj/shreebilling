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
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form role="form" id="sales_report" action="/admin/stock/report" method="post">
                            {{ csrf_field() }}
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label class="control-label">Start Date<span class="required"> *</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" name="start_date"
                                               data-format="yyyy-mm-dd" value="@if(\Request::get('start_date')) {{ \Request::get('start_date') }} @endif" required>
                                        <div class="input-group-addon">
                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label class="control-label">End Date<span class="required"> *</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" name="end_date"
                                               data-format="yyyy-mm-dd" value="@if(\Request::get('end_date')) {{ \Request::get('end_date') }} @endif" required>
                                        <div class="input-group-addon">
                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label"></label>
                                    <div class="">
                                        <button type="submit" name="flag" value="1" data-toggle="tooltip" data-placement="top" title="Search" class="btn-sm btn-primary"><i class="fa fa-search fa-2x"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($assigns)
    <div class="col-sm-12" data-offset="0">
        <div class="panel panel-custom">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Report List</strong>
                    <div class="pull-right hidden-print">
                        <a href="/admin/stock/generateAssignPdf/{{ \Request::get('start_date') }}/{{ \Request::get('end_date') }}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" target="_blank"><span><i class="fa fa-file-pdf"></i></span></a>
                        <a href="/admin/stock/printAssign/{{ \Request::get('start_date') }}/{{ \Request::get('end_date') }}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print"><span><i class="fa fa-print"></i></span></a>
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

        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
@endsection
