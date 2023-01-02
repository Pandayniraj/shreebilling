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
            Create New City
            <small>
                New City
            </small>
        </h1>
    </section>
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">
                <div class="col-md-12">
                    <div class="">
                        <form method="POST" enctype="multipart/form-data" action="{{route('admin.city.store')}}">
                            {{ csrf_field() }}

                            <div class="">
                                <div class="clearfix"></div>
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <hr>
                                        <div class="col-md-12">

                                            <div class="col-sm-3 form-group" style="">
                                                <label for="product_type">County</label>
                                                <select  name="country"  class="form-control input-sm" placeholder="First name" type="text">
                                                    <option selected disabled >Select Country

                                                    </option>
                                                    @foreach($countries as $key=>$country)
                                                    <option value="{{$country}}">{{$country}}

                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>  <div class="col-sm-3 form-group" style="">
                                                <label for="user_id">City Name</label>
                                                <input  name="city"  class="form-control input-sm" placeholder="City Name" type="text">
                                            </div>  <div class="col-sm-3 form-group" style="">
                                                <label for="user_id">Longitude</label>
                                                <input  name="lng"  class="form-control input-sm" placeholder="Longitude" type="text">
                                            </div>
                                            <div class="col-sm-3 form-group" style="">
                                                <label for="">Latitude</label>
                                                <input  name="lat"  class="form-control input-sm" placeholder="Latitude" type="text">
                                            </div>
                                        </div>

                                        <div class="col-md-12">

                                            <div class="col-sm-3 form-group" style="">
                                                <label for="user_id">Iso 2</label>
                                                <input  name="iso2"  class="form-control input-sm" placeholder="ISO 2" type="text"/>

                                            </div>
                                              <div class="col-sm-3 form-group" style="">
                                                <label for="user_id">Iso 3</label>
                                                <input  name="iso3"  class="form-control input-sm" placeholder="ISO 3" type="text">
                                            </div>

                                        </div>





                                </div>
                                    </div>
                                </div>


                                <div class="clearfix"></div><br /><br />

                                <br />

                            <div class="panel-footer footer">
                                <button type="submit" class="btn btn-social btn-foursquare">
                                    <i class="fa fa-save"></i>Create City
                                </button>
                                <a class="btn btn-social btn-foursquare" href="/admin/cities"> <i class="fa fa-times"></i> Cancel </a>
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



