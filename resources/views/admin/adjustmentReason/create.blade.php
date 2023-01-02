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
            Adjustment Reason
            <small>
                Creating New Reason
            </small>
        </h1>
    </section>
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">
                <div class="col-md-12">
                    <div class="">
                        <form method="POST" action="{{route('admin.adjustment-reason.create')}}">
                            {{ csrf_field() }}

                            <div class="">
                                <div class="clearfix"></div>
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                <div class="col-md-12">

                                    <div class="col-md-4 form-group" style="">
                                        <label for="user_id">Name</label>
                                        <input  name="name"  class="form-control input-sm" placeholder="" type="text">
                                    </div>
                                    <div class="col-md-4 form-group" style="">
                                        <label for="user_id">Reason Type</label>
                                        <select name="reason_type" id="" class="form-control input-sm">
                                            <option  selected="selected" disabled value=""> Select Reason Type</option>
                                            <option  value="positive"> Positive</option>
                                            <option  value="negative"> Negative</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group" style="">
                                        <label for="user_id">Trans Type</label>
                                        <select name="trans_type" id="" class="form-control input-sm">
                                            <option  selected="selected" disabled value=""> Select Trans Type</option>
                                            <option  value="101"> PURCHORDER</option>
                                            <option  value="102"> PURCHINVOICE</option>
                                            <option  value="201"> SALESORDER</option>
                                            <option  value="202"> SALESINVOICE</option>
                                            <option  value="301"> OTHERSALESINVOICE</option>
                                            <option  value="302"> DELIVERYORDER</option>
                                            <option  value="401"> STOCKMOVEIN</option>
                                            <option  value="402"> STOCKMOVEOUT</option>
                                        </select>
                                    </div>

                                </div>
                                    </div>
                                </div>


                                <div class="clearfix"></div><br /><br />

                                <br />

                            </div>
                            <div class="panel-footer footer">
                                <button type="submit" class="btn btn-social btn-foursquare">
                                    <i class="fa fa-save"></i>Create Reason
                                </button>
                                <a class="btn btn-social btn-foursquare" href="/admin/adjustment-reason"> <i class="fa fa-times"></i> Cancel </a>
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
    @include('partials._date-toggle')

@endsection
