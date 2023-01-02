@extends('layouts.dialog')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
    <style>
        select { width:200px !important; }
        label {
            font-weight: 600 !important;
        }

        .intl-tel-input { width: 100%; }
        .intl-tel-input .iti-flag .arrow {border: none;}

        #overlay{
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height:100%;
            display: none;
            background: rgba(0,0,0,0.6);
        }
        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }
        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }
        .is-hide{
            display:none;
        }

    </style>

    <script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{asset("/bower_components/admin-lte/select2/css/select2.min.css")}}" rel="stylesheet" />
    <link href="{{ asset("/bower_components/intl-tel-input/build/css/intlTelInput.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{asset("/bower_components/admin-lte/select2/js/select2.min.js")}}"></script>
    <script src="{{ asset("/bower_components/intl-tel-input/build/js/intlTelInput-jquery.min.js") }}"></script>

    {{--<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>--}}
    {{--<!-- Bootstrap 3.3.2 JS -->--}}
    {{--<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>--}}
    {{--<!-- AdminLTE App -->--}}
    {{--<script src="{{ asset ("/bower_components/admin-lte/dist/js/app.min.js") }}" type="text/javascript"></script>--}}


    <div class='row'>
        <div class='col-md-12'>
            <div class="box">
                <div class="box-body">
                    <form  class= 'form-horizontal' id = 'form_lead' >


                        <div class="content col-md-9">

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">
                                            {!! Form::label('title', trans('admin/leads/general.columns.title')) !!}
                                        </label>
                                        <div class="col-sm-10">
                                            {!! Form::select('title', ['Mr'=>'Mr', 'Miss'=>'Miss', 'Mrs'=>'Mrs', 'Ms'=>'Ms','Others'=>'Others'], null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Name</label>
                                        <div class="input-group ">

                                            <input type="text" name="name" placeholder="Person Name" id="name" value="{{ old('name') }}" class="form-control" pattern="[a-zA-Z0-9]+" required>
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-user"></i></a>
                                            </div>
                                        </div>

                                    </div>

                                </div>


                            </div>
                            {{--
                                                <div class="row">

                                                    <div class="col-md-4">
                                                    <div class="form-group">
                                                          <label class="control-label col-sm-4">Business Name</label>
                                                        <div class="input-group ">
                                                            <input type="text" name="company_id" placeholder="Business Name" id="company_id" value="{{ old('company_id') }}" class="form-control" pattern="[a-zA-Z0-9]+" required>
                                                            <div class="input-group-addon">
                                                                <a href="#"><i class="fa fa-building"></i></a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                   </div>

                                                </div>       --}}

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">Product and Services</label>
                                        <div>
                                            {!! Form::select('product_id', $courses, null, ['class' => 'form-control input-sm select2','id'=>'products', 'placeholder' => 'Please Select','required'=>'required']) !!}

                                        </div>

                                    </div>
                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Mobile.</label>

                                        <div class="input-group" >
                                            <input type="text" name="mob_phone" id="mob_phone" value="{{ old('mob_phone') }}" class="form-control" required="" placeholder="Primary phone" >
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-mobile"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Email</label>
                                        <div class="input-group ">
                                            <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control" required="">
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-at"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                            </div>




                            @if(\Request::get('source') && \Request::get('source') == 'lead')
                                {!! Form::select('lead_type_id', ['2'=>'Leads','4'=>'Customer','5'=>'Contact','6'=>'Agent'], '4', ['class' => 'form-control']) !!}


                            @else
                                @if(\Request::get('type') == 'leads')
                                    <input type="hidden" name="lead_type_id" value="2">
                                @elseif(\Request::get('type') == 'agent')
                                    <input type="hidden" name="lead_type_id" value="6">
                                @elseif(\Request::get('type') == 'contact')
                                    <input type="hidden" name="lead_type_id" value="5">
                                @else
                                    <input type="hidden" name="lead_type_id" value="4">
                                @endif
                            @endif
                            <input type="hidden" name="rating" value="active">
                            <div class="row">

                                <div class="col-md-12">
                                    <label for="inputEmail3" class="control-label">
                                        Descriptions and Client Requirements
                                    </label>

                                    <textarea class="form-control" name="description" id="description" placeholder="Write Description">{!! \Request::old('description') !!}</textarea>
                                </div>
                            </div>


                        </div><!-- /.content -->

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                                <a href="#" class='btn btn-default' onClick="window.close()">{{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
    <script type="text/javascript">
        $('.select2').select2();
        $('#btn-submit-edit').click(function(){
            $("#overlay").fadeIn(300);
            var obj ={};
            var data = JSON.stringify( $('#form_lead').serializeArray() ); //  <-----------
            var paramObj = {};
            $.each($('form').serializeArray(), function(_, kv) {
                paramObj[kv.name] = kv.value;
            });
            paramObj['_token']= $('meta[name="csrf-token"]').attr('content')
            $.post("/admin/leads/store/modal",paramObj,function(data,status){
                if(status == 'success'){
                    var  result = data;
                    try  {
                        window.opener.HandlePopupResult(result);
                    }
                    catch (err) {
                        console.log(err);
                    }
                }else{
                    alert("Failed to save client Try Again!!");
                }
                $("#overlay").fadeOut(300);
                window.close();
                return false;
            }).fail(function(){
                $("#overlay").fadeOut(300);
                $('#errormodal').slideDown(300);
                alert("Something Went Wrong")
            });
            return false;
        });
    </script>
@endsection


