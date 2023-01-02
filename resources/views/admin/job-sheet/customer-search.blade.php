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
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            Create New Job Sheet
        </h1>
    </section>
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">
                <div class="col-md-12">
                    <div class="">
                            <div class="">
                                <div class="clearfix"></div>
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <div class="col-md-12">
                                            <form method="get" enctype="multipart/form-data" action="{{route('admin.job-sheet.find.customer')}}">


                                            <div class="col-md-12 form-group" style="">
                                                <h4 class="font-weight-bold" style="font-style: oblique;padding: 16px;" for="user_id">Search Existing Customer or create New Customer</h4>
                                                <div class="col-sm-12 ">
                                                    <div  class="col-sm-5" style="display: flex">
                                                        <span class="lead font-weight-bold ">Search:</span> <input required placeholder="Search By Name/Mobile" value="{{request('mobile')}}" name="mobile" class="form-control" type="text">
                                                    </div>

                                                        <button type="submit" class="btn btn-info col-sm-2" style="margin-right: 4px;">Search</button>


                                                        <a type="button" href="{{route('admin.clients.create',['relation_type'=>'customer','going_to'=>'jobsheet'])}}" class="btn btn-primary col-sm-3">Create New Customer</a>


                                                </div>


                                            </div>
                                            </form>

                                        </div>
                                        <hr>
                                        <div class="col-md-12">
                                            <h3>Customer Information</h3>

                                            <div  class="box-body">

                                                <div class="table-responsive">
                                                    <a href=""> </a>
                                                    <table class="table table-hover table-bordered table-striped" id="orders-table">
                                                        <thead>
                                                        <tr class="bg-danger">
                                                            <th>
                                                                ID
                                                            </th>
                                                            <th>
                                                                 Name
                                                            </th> <th>
                                                                Mobile No
                                                            </th> <th>
                                                                Email
                                                            </th>
                                                            <th>
                                                                Address
                                                            </th>

                                                        </tr>
                                                        </thead>

                                                        <tbody>

                                                                <tr>
                                                                    <td>{{ $customer->id}}</td>
                                                                    <td>{{ucfirst($customer->name)}}</td>
                                                                    <td>{!! $customer->phone !!} </td>
                                                                    <td> {{$customer->email}} </td>
                                                                    <td>{!! $customer->physical_address!!} </td>

                                                                </tr>

                                                        </tbody>
                                                    </table>



                                                </div> <!-- table-responsive -->

                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <h3>Customers Device History </h3>


                                            <div id="app" class="box-body">
                                                <span id="index_device_status"></span>

                                                <div class="table-responsive">
                                                    <a href=""> </a>
                                                    <table class="table table-hover table-bordered table-striped" id="orders-table">
                                                        <thead>
                                                        <tr class="bg-primary">
                                                            <th>
                                                               Date
                                                            </th><th>
                                                                Job Sheet No.
                                                            </th>
                                                            <th>
                                                              IMEI No
                                                            </th> <th>
                                                               Brand
                                                            </th> <th>
                                                               Model Number
                                                            </th>
                                                            <th style="width: 20%">
                                                                Device Status
                                                            </th>
                                                            <th>
                                                               Repaired By
                                                            </th>

                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        @if(isset($customer->jobSheets) && !empty($customer->jobSheets))
                                                            @foreach($customer->jobSheets as $o)
                                                                <tr>
                                                                    <td>{{date('d- M-Y',strtotime($o->created_at)) }}</td>
                                                                    <td>{{ $o->id}}</td>
                                                                    <td>{{$o->IMEI_num1}}</td>
                                                                    <td>{!! $o->brand !!} </td>
                                                                    <td> {{$o->model_number}} </td>
                                                                    <td style="width: 20%"><select @change="onChanges(event,{{$o->id}})" class="form-control"  >

                                                                            <option {{$o->device_status==='Repaired'?'selected':''}}  value="Repaired">
                                                                                Repaired
                                                                            </option>
                                                                            <option {{$o->device_status==='Pending'?'selected':''}}  value="Pending">
                                                                                Pending
                                                                            </option> <option {{$o->device_status==='Parts Pending'?'selected':''}}  value="Parts Pending">
                                                                                Parts Pending
                                                                            </option>
                                                                            <option  {{$o->device_status==='Return Without Repair'?'selected':''}} value="Return Without Repair">
                                                                                Return Without Repair
                                                                            </option>
                                                                            <option {{$o->device_status==='In Progress'?'selected':''}} value="In Progress">
                                                                                In Progress
                                                                            </option>
                                                                        </select>
                                                                    </td>

                                                                    <td>{!! $o->user->first_name!!} </td>

                                                                </tr>
                                                            @endforeach
                                                            @else

                                                        @endif
                                                        </tbody>
                                                    </table>
                                                    @if(!count($customer->jobSheets)>0 && $customer)
                                                        <h3 class="text-center">Repair Record Not Found</h3>
                                                    @endif

                                                    @if(isset($customer))
                                                    <a type="button" href="{{route('admin.job-sheet.create',['customer'=>$customer->id,'name'=>$customer->name])}}" class="btn btn-primary ">Create JobSheet</a>
                                                        @endif



                                                </div> <!-- table-responsive -->

                                            </div>
                                        </div>




                                    </div>
                                </div>
                            </div>


                            <div class="clearfix"></div><br /><br />

                            <br />






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
            var app = new Vue({
                el: '#app',
                // watch:{
                //     device_status(value){
                //         console.log(value)
                //     }
                // },
                data: {
                    message: '',
                    device_status:''
                },
                methods :{
                    onChanges(e,id) {
                        console.log($(e.target).val());
                        axios.post(`/admin/job-sheet/repair-status/`+id,{device_status:$(e.target).val()})
                            .then((response) => {
                                // this.message=response.data.device_status;
                                // if(data.status == '1')
                                $("#index_device_status").after("<span style='color:green;' id='index_status_update'>Status Updated Successfully.</span>");
                                // else
                                //     $("#index_lead_ajax_status").after("<span style='color:red;' id='index_status_update'>Problem in updating status; Please try again.</span>");

                                $('#index_status_update').delay(3000).fadeOut('slow');
                            })
                            .catch((error) => {
                                this.message='Failed';
                            })

                    }
                }
            })
        </script>

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



