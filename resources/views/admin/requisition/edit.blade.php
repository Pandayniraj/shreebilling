@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

    <style>
        .panel .mce-panel {
            border-left-color: #fff;
            border-right-color: #fff;
        }

        .panel .mce-toolbar,
        .panel .mce-statusbar {
            padding-left: 20px;
        }

        .panel .mce-edit-area,
        .panel .mce-edit-area iframe,
        .panel .mce-edit-area iframe html {
            padding: 0 10px;
            min-height: 350px;
        }

        .mce-content-body {
            color: #555;
            font-size: 14px;
        }

        .panel.is-fullscreen .mce-statusbar {
            position: absolute;
            bottom: 0;
            width: 100%;
            z-index: 200000;
        }

        .panel.is-fullscreen .mce-tinymce {
            height: 100%;
        }

        .panel.is-fullscreen .mce-edit-area,
        .panel.is-fullscreen .mce-edit-area iframe,
        .panel.is-fullscreen .mce-edit-area iframe html {
            height: 100%;
            position: absolute;
            width: 99%;
            overflow-y: scroll;
            overflow-x: hidden;
            min-height: 100%;
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #efefef;
            color: white;
            text-align: center;
        }


    </style>
@endsection

@section('content')
    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet"/>
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            {{ $page_title ?? "Page Title"}}
            <small> {{ $page_description ?? "Page Description" }}</small>
        </h1>
        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
    </section>


    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-header">
                <div id="orderFields" style="display: none;">
                    <table class="table">
                        <tbody id="more-tr">
                        <tr>
                            <td>

                                <select class="form-control product_id hiddensearchable " name="product_id[]"
                                        required="required">
                                    <option selected disabled >Select Product</option>
                                    @foreach($products as $key => $pk)
                                        <option value="{{ $pk->id }}"> {{ $pk->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control quantity" name="quantity[]"
                                       placeholder="Quantity" min="1" value="1" required="required">
                            </td>
                            <td>
                                <select class="form-control unit " name="unit[]">
                                    <option selected disabled > Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{$unit->id}}"> {{$unit->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input class="form-control input-sm datepicker" name="required_by[]"
                                       placeholder="Required By" type="text">
                            </td>

                            <td>
                                <input type="text" class="form-control total" name="reason[]" placeholder="Reson"
                                       value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif"
                                       style="float:left; width:80%;">
                                <a href="javascript:void(1);" style="width: 10%;">
                                    <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable"
                                       style="float: right; color: #fff;"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>


                <div class="col-md-12">
                    <div class="">
                        <form method="POST" action="/admin/requisition/{{$requisition->id}}">
                            {{ csrf_field() }}

                            <div class="">

                                <div class="clearfix"></div>
                                <div class="col-md-12">

                                    <div class="col-md-12">

                                        <div class="col-md-12">
                                        </div>
                                        <div class="col-md-3 form-group" style="">
                                            <label for="user_id"> <i class="fa fa-user"></i> Department</label>
                                            <select required class="form-control input-sm" name="department_id" id="">
                                                <option selected disabled>Select Department
                                                </option>
                                                @foreach($departments as $department)
                                                    <option
                                                        {{$requisition->department_id==$department->departments_id ? 'selected' : ''}} value="{{$department->departments_id}}">{{$department->deptname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group" style="">
                                            <label for="user_id"> Date</label>
                                            <input class="form-control input-sm datepicker"
                                                   value="{{$requisition->req_date}}" name="req_date" type="text">
                                        </div>

                                    </div>


                                    <div class="clearfix"></div>
                                    <br/><br/>

                                    <div class="col-md-12">
                                        <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMore"
                                           style="float: right;">
                                            <i class="fa fa-plus"></i> <span>Add Products Item</span>
                                        </a>
                                    </div>


                                    <hr/>
                                    <table class="table table-striped">
                                        <thead>
                                        <tr class="bg-gray">
                                            <th>Item Description *</th>
                                            <th>Quantity *</th>
                                            <th>Unit *</th>
                                            <th>Required By *</th>
                                            <th>Reason</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @if($requisitiondetail)
                                            @foreach($requisitiondetail as $lst)

                                                <tr>
                                                    <td>
                                                        <select class="form-control product_id hiddensearchable "
                                                                name="product_id[]" required="required">
                                                            <option value="">Select Product</option>
                                                            @foreach($products as $key => $pk)
                                                                <option value="{{ $pk->id }}"
                                                                        @if(isset($lst->product_id) && $lst->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control quantity"
                                                               name="quantity[]" placeholder="Quantity" min="1"
                                                               value="{{$lst->quantity}}" required="required">
                                                    </td>
                                                    <td>
                                                        <select class="form-control  unit" name="unit[]" >
                                                            @foreach($units as $unit)

                                                                <option
                                                                    @if($lst->unit && $unit->id==$lst->unit)
                                                                        selected="selected"
                                                                    @elseif(!$lst->unit && $unit->id==$lst->product->product_unit)
                                                                        selected="selected"
                                                                    @endif
                                                                     value="{{$unit->id}}"> {{$unit->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="form-control input-sm datepicker"
                                                               name="required_by[]" value="{{$lst->required_by}}"
                                                               placeholder="Required By" type="text">
                                                    </td>

                                                    <td>
                                                        <input type="text" class="form-control total" name="reason[]"
                                                               placeholder="Reason" value="{{$lst->reason}}"
                                                               style="float:left; width:80%;">
                                                        <a href="javascript:void(1);" style="width: 10%;">
                                                            <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable"
                                                               style="float: right; color: #fff;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        @endif


                                        <tr class="multipleDiv">

                                        </tr>
                                        </tbody>


                                    </table>

                                    <br/>

                                    <div class="box">
                                        <div class="box-header with-border">

                                            <div class="col-md-6 form-group" style="margin-top:5px;">
                                                <label for="comment">Remarks </label>
                                                <small class="text-muted"></small>
                                                <textarea class="form-control TextBox comment"
                                                          name="comment">{{$requisition->comment}}</textarea>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                                <div class="panel-footer footer">
                                    @if( request()->has('approve'))
                                    <a href="{{route('admin.requisition.approve',$requisition->id)}}" class="btn btn-social btn-foursquare">
                                        <i class="fa fa-save"></i>Approve
                                    </a>
                                    @else
                                    <button type="submit" class="btn btn-social btn-foursquare">
                                        <i class="fa fa-save"></i>Save {{ $_GET['type']}}
                                    </button>
                                    @endif

                                    <a class="btn btn-social btn-foursquare" href="/admin/requisition"> <i
                                            class="fa fa-times"></i> Cancel </a>

                                </div>
                        </form>
                    </div>
                </div>

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_bug_edit_form_js')

    <script type="text/javascript">

        // $(function() {
        //     $('.datepicker').datetimepicker({
        //       //inline: true,
        //       format: 'YYYY-MM-DD',
        //       sideBySide: true,
        //       allowInputToggle: true
        //     });

        //   });
    </script>

    <script>


        $("#addMore").on("click", function () {

            $(".multipleDiv").after($('#orderFields #more-tr').html());
            $('.datepicker').datetimepicker({
                //inline: true,
                format: 'YYYY-MM-DD',
                sideBySide: true,
                allowInputToggle: true
            });
        });


    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2();
        });

        $(function () {
            $('.datepicker').datetimepicker({
                //inline: true,
                format: 'YYYY-MM-DD',
                sideBySide: true,
                allowInputToggle: true
            });

        });

        $(document).on('click', '.remove-this', function () {
            $(this).parent().parent().parent().remove();
            calcTotal();
        });


    </script>

    <script type="text/javascript">

        $(document).on('change', '.product_id', function () {
            var parentDiv = $(this).parent().parent();
            $.ajax(
                {
                    url: "/admin/getStockUnit",
                    data: {product_id: $(this).val()},
                    dataType: "json",
                    success: function (data) {
                        var unit = data.unit;
                        console.log(unit)

                        parentDiv.find('.unit option').each(function(e) {
                            $(this).prop("selected", false)
                            if ($(this).val() == unit)
                                $(this).prop("selected", true)
                        });

                    }
                });

        });

        // $(document).on('ready', '.product_id', function() {
        //     var parentDiv = $(this).parent().parent();
        //     $.ajax(
        //         {
        //             url: "/admin/getStockUnit",
        //             data: { product_id: $(this).val() },
        //             dataType: "json",
        //             success: function( data ) {
        //                 var unit = data.unit;
        //                 console.log(unit)
        //                 // $(this).val(parentDiv.find('.unit').val(unit));
        //             }
        //         });
        //
        // });


        $(document).on('change', '#source_id', function () {

            $('.multipleDiv').nextAll('tr').remove();

        });


        $(document).on('change', '.quantity', function () {

            var parentDiv = $(this).parent().parent();

            $.ajax(
                {
                    url: "/admin/getStockAvailability",
                    data: {product_id: parentDiv.find('.product_id').val(), source_id: $('#source_id').val()},
                    dataType: "json",
                    success: function (data) {
                        var available = data.available;
                        $(this).val(parentDiv.find('.availablity').val(available));

                    }
                });

            if (this.value != '') {

                var availablity = parseInt(parentDiv.find('.availablity').val());

                if (parseInt(this.value) > availablity) {
                    $(this).val(availablity);

                    alert("Stock Quantity Cannot Be Greater Than Available Quantity");

                }
            }

        });
    </script>
@endsection
