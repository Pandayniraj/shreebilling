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

<div class="row">
    <div class="col-sm-12">

        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                <ul class="nav nav-tabs">
                    <li @if(!\Request::segment(5)) class="active" @endif><a href="#all_category" data-toggle="tab" aria-expanded="true">All Stock Category</a>
                    </li>
                    <li @if(\Request::segment(5)) class="active" @endif><a href="#new_category" data-toggle="tab" aria-expanded="false">New Stock Category</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content bg-white">

                <div class="tab-pane @if(!\Request::segment(5)) active @endif" id="all_category" style="position: relative;">
                   <!-- NESTED-->
                   <div class="box" style="" data-collapsed="0">
                      <div class="box-body">
                         <!-- Table -->
                         <div class="row">
                            @foreach($categories as $ck => $cv)
                            <div class="col-sm-6">
                               <div class="box-heading">
                                  <div class="box-title">
                                     <h4>
                                        {{ $cv->stock_category }}                                                    
                                        <div class="pull-right" style="margin-right: 10px;">
                                           <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                            <a href="/admin/stock/category/{{ $cv->stock_category_id }}" class="btn btn-primary btn-xs" title="Edit" data-toggle="modal" data-placement="top" data-target="#myModal"><span class="fa fa-edit"></span></a>

                                            <a href="/admin/stock/delete_stock_category/{{ $cv->stock_category_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                        </div>
                                     </h4>
                                  </div>
                               </div>
                               <!-- Table -->
                               <table class="table table-bordered table-hover">
                                  <thead>
                                     <tr>
                                        <td class="text-bold col-sm-1">#</td>
                                        <td class="text-bold"></td>
                                        <td class="text-bold col-sm-2">Action</td>
                                     </tr>
                                  </thead>
                                  <tbody>
                                    @foreach(StockHelper::getSubCategories($cv->stock_category_id) as $sk => $sv)
                                     <tr>
                                        <td>{{ $sk+1 }}</td>
                                        <td>{{ $sv->stock_sub_category }}</td>
                                        <td style="text-align:right;">
                                           <a href="/admin/stock/category/{{ $cv->stock_category_id }}/{{ $sv->stock_sub_category_id }}" class="btn btn-primary btn-xs" title="" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                           <a href="/admin/stock/delete_stock_sub_category/{{ $sv->stock_sub_category_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                     </tr>
                                     @endforeach
                                  </tbody>
                               </table>
                            </div>
                            @endforeach
                         </div>
                      </div>
                   </div>
                </div>

                <div class="tab-pane @if(\Request::segment(5)) active @endif" id="new_category" style="position: relative;">
                    <form action="/admin/stock/save_category" method="post" class="form-horizontal form-groups-bordered" id="save_category">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Select Sub Category <span class="text-danger">*</span>
                                  </label>
                                    <div class="col-lg-8">
                                        <select class="form-control select_box" style="width: 100%" name="stock_category_id" id="stock_category_id">
                                          <option value="">New Category</option>
                                          @foreach($categories as $ck => $cv)
                                          <option value="{{ $cv->stock_category_id }}" @if(isset($subCategory) && $subCategory->stock_category_id == $cv->stock_category_id) selected="selected" @endif>{{ $cv->stock_category }}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group new_stocks" id="new_category_div" @if(\Request::segment(5)) style="display: none;" @endif>
                                    <label class="col-sm-4 control-label">New Category <span class="required">*</span>
                                      </label></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="stock_category" id="stock_category" class="form-control new_stocks" value="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class=" col-sm-4 control-label" id="sub_cat_label">Sub Category <span class="required" style="display: none;">*</span>
                                      </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="stock_sub_category" id="stock_sub_category" class="form-control" value="@if(isset($subCategory)){{ $subCategory->stock_sub_category }}@endif" />
                                    </div>
                                </div>
                                @if(\Request::segment(5))
                                <input type="hidden" name="stock_sub_category_id" class="form-control" value="{{ \Request::segment(5) }}" />
                                @endif
                                <div class="form-group margin">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <button type="button" class="btn btn-primary btn-block" id="create_category_btn">@if(\Request::segment(5)) Update @else Create @endif</button>
                                    </div>
                                </div>
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

<script type="text/javascript">
    $(function() {
        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('#stock_category_id').on('change', function() {
            $('#stock_category').css('border', '1px solid #ccc');
            $('#stock_sub_category').css('border', '1px solid #ccc');
            if($(this).val() == '')
            {
                $('#new_category_div').css('display', 'block');
                $('#sub_cat_label span').css('display', 'none');
            }
            else
            {
                $('#new_category_div').css('display', 'none');
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
