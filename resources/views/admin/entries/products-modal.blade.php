<style>
    .modal-dialog {
        height: 90%; /* = 90% of the .modal-backdrop block = %90 of the screen */
        width: 45%;
    }
    .select2-container{
        text-align: left!important;
    }
    /*.modal-content {*/
    /*    height: 100%; !* = 100% of the .modal-dialog block *!*/
    /*}*/
</style>
{{--<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />--}}
{{--<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />--}}
{{--<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>--}}

<div class="modal dialog fade" role="dialog fade" id="product_modal" style="min-height: 500px;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="display: flex;justify-content: space-between;">
                <h4 class="modal-title">Select Products for </h4>
                <i class="fa fa-times" data-dismiss="modal" style="cursor: pointer;"></i>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                {{--            <div class="row" style="text-align: center;">--}}
                {{--               <div class="col-12" >--}}
                {{--                  Paying through FonePay--}}
                {{--               </div>--}}
                {{--            </div>--}}
                <div class="row" style="text-align: center;margin-bottom: 5px">
                    <div class="col-12" >
                        <div id="productFields" style="display: none;">
                        <table class="table">
                            <tbody id="more-tr">
                            <tr>
                                <td style="vertical-align: bottom;">
                                    <select class="form-control product_id" name="product_id[]" required="required">
                                        <option value="">Select Product</option>
                                        @foreach($products as $key => $pk)
                                            <option value="{{ $pk->id }}">{{ $pk->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control product_amount" name="product_amount[]"  placeholder="Amount" min="1" value="" step="any" required="required">
                                </td>
                                                                <td>
                                    <a href="javascript:void(1);" style="width: 10%;">
                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>

                        </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <a href="javascript:void(0)" class="btn btn-primary" id="addMore" style="float: right;">
                            <i class="fa fa-plus"></i> <span>Add Product Item</span>
                        </a>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px;">
                        <table class="table table-striped table-bordered" style="">
                            <thead>
                            <tr class="bg-gray">
                                <th style="width: 65%;">Particular*</th>
                                <th>Amount*</th>
                                <th><i class="fa fa-cog"></i></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr class="multipleDiv">
{{--                                <td style="vertical-align: bottom;">--}}
{{--                                    <select class="form-control product_id" name="product_id[]" required="required">--}}
{{--                                        <option value="">Select Product</option>--}}
{{--                                        @foreach($products as $key => $pk)--}}
{{--                                            <option value="{{ $pk->id }}">{{ $pk->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <input type="number" class="form-control product_amount" name="product_amount[]"  placeholder="Amount" min="1" value="" step="any" required="required">--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <a href="javascript:void(1);" style="width: 10%;">--}}
{{--                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>--}}
{{--                                    </a>--}}
{{--                                </td>--}}
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr id="footer-total">
                                <th style="text-align: right;">Total</th>
                                <th id="product-total">0.00</th>
                                <td>&nbsp; <input type="hidden" name="product-total" id="subtotal" value="0"></td>
                            </tr>
                        </table>
                    </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer" id='form_edit_part'>
                <button type="submit" class="btn btn-success btn-sm" data-dismiss="modal" id="product-ok"><i class="fa  fa-check"></i> OK </button>
                {{--               <input type="submit" name="submit_option" id='formSubmit' style="visibility: hidden;">--}}
            </div>
        </div>
    </div>
</div>
</div>
<input type="hidden" name="modal_ledger_id" id="modal_ledger_id" value="">
