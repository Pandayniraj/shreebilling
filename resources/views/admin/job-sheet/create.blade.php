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
    .form-group {
        padding: 2px;
        margin-bottom: 2px;
        color:#40555e;
    }
    .form-control{
        height: 30px;
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
    .content{
        background: whitesmoke;
    }
    .box-section{
        background: white;
        margin-top: 10px;
        padding-top: 9px;
        padding-bottom: 14px;
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
    .radio-button input[type=radio] {
        margin: 24px 6px 14px
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
    .numberCircle {
        width: 34px;
        line-height: 26px;
        border-radius: 76%;
        text-align: center;
        font-size: 23px;
        border: 2px solid #666;
        display: inline-block;
    }
    .col-sm-3 strong {
        font-size: 1.7rem;

    }
    .form-check-label{
        font-size: 10px;
    }

    .col-md-3 strong {
        font-size: 1.7rem;

    }
    .active{
        color:#51aa1b;
    }
    a{
        color: #0a0a0a;
    }

</style>
@endsection

@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Create New Job Sheet
        <small>
            New Job Sheet
        </small>
    </h1>
</section>
<div class='row'>
    <div class='col-md-12'>
        <div class="box-body">
            <div class="col-md-12">
                <div class="">
                    <form method="POST" enctype="multipart/form-data" action="{{route('admin.job-sheet.create')}}">
                        {{ csrf_field() }}
                        <input name="customer_id" value="{{$_GET['customer']}}" type="hidden">

                        <div class="">
                            <div class="clearfix"></div>
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <div class="col-md-12">
                                        @if($_GET['name'])
                                        <h4 class="font-italic text-primary"> Customer's Name:- <strong>{{$_GET['name']}}</strong></h4>
                                        @endif

                                        <div class="col-md-4 form-group" style="">
                                            <h3 style="color: #25D366 " for="user_id">Service Type</h3>
                                            <div class="col">
                                                <span class="form-check">
                                                    <input class="form-check-input" value="carryin" type="radio" name="service_type" id="flexRadioDefault1">
                                                    <label class="form-check-label">
                                                        Carry In
                                                    </label>
                                                </span> &nbsp;
                                                <span class="form-check">
                                                    <input class="form-check-input" value="pickup" type="radio" name="service_type" id="flexRadioDefault1">
                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                        Pick Up
                                                    </label>
                                                </span> &nbsp;
                                                <span class="form-check">
                                                    <input class="form-check-input" value="onsite" type="radio" name="service_type" id="flexRadioDefault1">
                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                        On Site
                                                    </label>
                                                </span> &nbsp;
                                            </div>


                                        </div>

                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <h3 style="color: #25D366 ">Device Information</h3>

                                        <div class="col-sm-3 form-group" style="">
                                            <label for="product_type">Product Type</label>
                                            <select  name="product_type"  class="form-control input-sm" placeholder="First name" type="text">
                                                @foreach($types as $k=>$v)
                                                <option value="{{$k}}">{{$v}}

                                                </option>
                                                @endforeach
                                            </select>
                                        </div>  <div class="col-sm-3 form-group" style="">
                                            <label for="user_id">Brand</label>
                                            <input  name="brand"  class="form-control input-sm" placeholder="Brand" type="text">
                                        </div>  <div class="col-sm-3 form-group" style="">
                                            <label for="user_id">Product Name</label>
                                            <input  name="model_name"  class="form-control input-sm" placeholder="Model Name" type="text">
                                        </div>
                                        <div class="col-sm-3 form-group" style="">
                                            <label for="user_id">Model No</label>
                                            <input  name="model_number"  class="form-control input-sm" placeholder="Model No" type="text">
                                        </div>
                                        <div class="col-sm-3 form-group" style="">
                                            <label for="user_id">IMEI No. 1</label>
                                            <input  name="IMEI_num1"  class="form-control input-sm" placeholder="IMEI No. 1" type="text">
                                        </div>
                                        <div class="col-sm-3 form-group" style="">
                                            <label for="user_id">Serial Number</label>
                                            <input  name="serial_number"  class="form-control input-sm" placeholder="Serial Number" type="text"/>

                                        </div>
                                        <div class="col-sm-3 form-group" style="">
                                            <label for="user_id">Color</label>
                                            <input  name="color"  class="form-control input-sm" placeholder="Color" type="text">
                                        </div>
                                        <div class="col-sm-3 form-group" style="">
                                            <label for="user_id">Device Configuration</label>
                                            <input  name="device_config"  class="form-control input-sm" placeholder="Device Configuration" type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-12">

                                      <div class="col-sm-3 form-group" style="">
                                        <div ><label> Status</label></div>
                                        <span class="form-check">
                                            <input class="form-check-input" value="warranty" type="radio" name="status" id="flexRadioDefault1">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                Warranty
                                            </label>
                                        </span> &nbsp;
                                        <span class="form-check">
                                            <input class="form-check-input" value="nowarranty" type="radio" name="status" id="flexRadioDefault1">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                Non Warranty
                                            </label>
                                        </span> &nbsp;
                                        <span class="form-check">
                                            <input class="form-check-input" value="AMC" type="radio" name="status" id="flexRadioDefault1">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                Amc
                                            </label>
                                        </span> &nbsp;
                                        <span class="form-check">
                                            <input class="form-check-input" value="return" type="radio" name="status" id="flexRadioDefault1">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                Return
                                            </label>
                                        </span> &nbsp;
                                    </div>
                                    <div class="col-sm-3 form-group" style="">
                                        <label for="user_id">Pass Code/Patterns</label>
                                        <input  name="passcode"  class="form-control input-sm" placeholder="Pass Code/Patterns" type="text">
                                    </div>
                                    <div class="col-sm-3 form-group" style="">
                                        <label for="user_id">Reported Fault By Customer</label>
                                        <input  name="reported_fault"  class="form-control input-sm" placeholder="Reported Fault By Customer" type="text">
                                    </div>
                                    <div class="col-sm-3 form-group" style="">
                                        <label for="user_id">Physical State Of Device</label>
                                        <input  name="physical_state"  class="form-control input-sm" placeholder="Physical State Of Device" type="text">
                                    </div>

                                </div>

                                <div class="col-md-12">

                                    <div class="col-sm-3 form-group" style="">
                                        <label for="user_id">Estimated cost (Rs.)</label>
                                        <input  name="estimated_cost"  class="form-control input-sm" placeholder="Estimated cost (Rs)" type="text">
                                    </div>
                                    <div class="col-sm-3 form-group" style="">
                                        <label for="user_id">Advance Paid (Rs.)</label>
                                        <input  name="advance_paid"  class="form-control input-sm" placeholder="Advance Paid (Rs.)" type="text">
                                    </div>
                                    <div class="col-sm-3 form-group" style="">
                                        <label for="user_id">Expected Delivery Date</label>
                                        <input id="date" name="expected_delivery_date"  class="form-control input-sm" placeholder="Expected Delivery Date" type="text">
                                    </div>
                                    <div class="col-sm-3 form-group" style="">
                                        <label for="user_id">Expected Delivery Time</label>
                                        <input id="time"  name="expected_delivery_time"  class="form-control input-sm" placeholder="Expected Delivery Time" type="text">
                                    </div>

                                </div>

                                <div class="col-md-12">

                                    <div class="col-sm-3 form-group" style="">
                                        <label for="user_id">Dropper Name</label>
                                        <input  name="dropper_name"  class="form-control input-sm" placeholder="dropper name" type="text">
                                    </div>
                                    <div class="col-sm-3 form-group" style="">
                                        <label for="user_id">Dropper Email</label>
                                        <input  name="dropper_email"  class="form-control input-sm" placeholder="dropper email" type="text">
                                    </div>
                                    <div class="col-sm-3 form-group" style="">
                                        <label for="user_id">Dropper Phone</label>
                                        <input id="date" name="dropper_phone"  class="form-control input-sm" placeholder="dropper phone" type="text">
                                    </div>
                                    <div class="col-sm-3 form-group" style="">
                                        <label for="user_id">Dropped Time</label>
                                        <input id="time2"  name="dropped_time"  class="form-control input-sm" placeholder="E Time" type="text">
                                    </div>

                                </div>

                                <div class="col-sm-12 box-section">


                                    <div class="col-sm-12" style="margin-top: 10px; display: flex;justify-content: space-between">
                                        <h4 style="color: #25D366 " >Received Items</h4>
                                        <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMore"
                                        style="float: left;">
                                        <i class="fa fa-plus"></i> <span>Items</span>
                                    </a>
                                </div>
                                <div id="" class="InputsItem ">
                                    <table class="table ">
                                        <thead>
                                            <tr class="bg-info">
                                                <td><label for="">Items name</label></td>
                                                <td><label for="">Faults</label></td>
                                                <td><label for="">Remarks</label></td>
                                                <td><label for="">Action</label></td>
                                            </tr>

                                        </thead>


                                        <tbody>



                                            <tr @if(count($jobSheet->items)>0) style="display: none" @endif>

                                                <td >
                                                    <input type="text" name="items[]" class="form-control"
                                                    placeholder="Item name" id="position"
                                                    value="">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                    value=""
                                                    name="faults[]"
                                                    class="form-control"
                                                    placeholder="Faults">

                                                </td>
                                                <td >
                                                    <input  type="text" value="" name="remarks[]"
                                                    class="form-control"
                                                    placeholder="Remarks">
                                                </td>

                                                <td style="">
                                                    <a href="javascript::void(0)" style="width: 10%;">
                                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable"
                                                        style="float: right; color: #fff;"></i>
                                                    </a>
                                                </td>

                                            </tr>



                                        </tbody>

                                    </table>


                                </div>


                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-3">
                                    <label for=""> Assign operator/technician </label>
                                    <select class="form-control" name="assigned_user" id="">
                                        @foreach($users as $key=>$user)
                                        <option class=""  value="{{$key}}">{{$user}}

                                        </option>
                                        @endforeach
                                    </select>

                                </div>

                            </div>




                        </div>
                    </div>
                </div>


                <div class="clearfix"></div><br /><br />

                <br />

                <div class="panel-footer footer">
                    <button type="submit" class="btn btn-social btn-foursquare">
                        <i class="fa fa-save"></i>Create Job Sheet
                    </button>
                    <a class="btn btn-social btn-foursquare" href="/admin/job-sheet"> <i class="fa fa-times"></i> Cancel </a>
                </div>
            </form>

            <table class="item-section" style="display: none">
                <tbody>
                    <tr>

                        <td >
                            <input type="text" name="item_names[]" class="form-control"
                            placeholder="Item Name" id="position"
                            value="">
                        </td>
                        <td>
                            <input type="text"
                            value=""
                            name="faults[]"
                            class="form-control"
                            placeholder="Faults">

                        </td>
                        <td >
                            <input type="text" value="" name="remarks[]"
                            class="form-control"
                            placeholder="Remark">
                        </td>
                        <td style="">
                            <a href="javascript::void(0)" style="width: 10%;">
                                <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable"
                                style="float: right; color: #fff;"></i>
                            </a>
                        </td>

                    </tr>
                </tbody>

            </table>
        </div>
    </div>


</div><!-- /.box-body -->
</div><!-- /.col -->

</div><!-- /.row -->
@endsection

@section('body_bottom')
<!-- form submit -->
@include('partials._body_bottom_submit_bug_edit_form_js')
@include('partials._date-toggle')



<script type="text/javascript">

    $(function () {
        $('#date').datetimepicker({
                //inline: true,
                format: 'YYYY-MM-DD',
                // format: 'MM',
                sideBySide: true
            });
        $('#time').datetimepicker({
                //inline: true,
                format: "HH:mm:ss",
                // format: 'MM',
                sideBySide: true
            });
        $('#time2').datetimepicker({
                //inline: true,
                format: "HH:mm:ss",
                // format: 'MM',
                sideBySide: true
            });
    });
    $(function () {
            // $('.client_id').select2();
            $("#addMore").on("click", function () {
                // $(".InputsWrapperWork").after($('#more-row-work .more-section section').html());
                $(".InputsItem tbody").append($('.item-section tbody').html());

            });

            $(document).on('click', '.remove-this', function () {
                $(this).closest('tr').remove();
            });

        });




    </script>

    @endsection



