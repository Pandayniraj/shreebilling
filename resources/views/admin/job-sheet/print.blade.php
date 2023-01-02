<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ \Auth::user()->organization->organization_name }} | Inbound</title>

    <!-- block from searh engines -->
    <meta name="robots" content="noindex">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Set a meta reference to the CSRF token for use in AJAX request -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons 4.7.0 -->
    <link href="{{ asset("/bower_components/admin-lte/font-awesome/css/all.css") }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.1 -->
    <link href="{{ asset("/bower_components/admin-lte/ionicons/css/ionicons.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css" />

    <!-- Application CSS-->


    <style type="text/css">
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }

        .vendorListHeading th {
            background-color: #1a4567 !important;
            color: white !important;
        }

        table{
            border: 1px solid dotted !important;
            font-size: 13px !important;
            padding-top: 2px !important; /*cancels out browser's default cell padding*/
            padding-bottom: 2px !important; /*cancels out browser's default cell padding*/
        }

        td{
            border: 1px dotted #999 !important;
            padding-top: 2px !important; /*cancels out browser's default cell padding*/
            padding-bottom: 2px !important; /*cancels out browser's default cell padding*/
        }

        th{
            border: 1px dotted #999 !important;
            padding-top: 2px !important; /*cancels out browser's default cell padding*/
            padding-bottom: 2px !important; /*cancels out browser's default cell padding*/
        }

        .invoice-col{
            /*border: 1px dotted #1a4567 !important;*/
            font-size: 12px !important;
            margin-bottom: -20px !important;
        }

        @page {
            size: auto;
            margin: 0;
        }

        body{
            padding-left: 1.3cm;
            padding-right: 1.3cm;
            padding-top: 1.3cm;
        }

        @media print {
            .pagebreak { page-break-before: always; } /* page-break-after works, as well */
        }
    </style>


</head>


<body onload="window.print();" cz-shortcut-listen="true" class="skin-blue sidebar-mini">


<div class='wrapper'>

        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <div >
                        <div class="col-xs-6">
                            <h4> <strong>{{env('APP_COMPANY')}}</strong></h4>
                            <small> <strong >{{env('APP_ADDRESS1')}}</strong></small> <br>
                            <small><strong>Phone No: {{env('APP_PHONE1')}}, {{env('APP_PHONE2')}}</strong></small> <br>
                            <small><strong>Email: {{env('APP_EMAIL')}}</strong></small> <br>

                            <h5> <strong>JOB CARD TO :</strong></h5>
                            <small> <strong >{!! nl2br($jobSheet->customer->name ) !!} </strong></small> <br>
                            <small> <strong > {!! nl2br($jobSheet->customer->physical_address ) !!}</strong></small> <br>
                            <small><strong>Phone : {!! nl2br($jobSheet->customer->phone ) !!}</strong></small>


                        </div>
                        <div class="col-xs-6 pull-right">
                            <span class="pull-right">
                               <img width="" style="width: 100px" src="{{asset("images/profiles/1611393264WhatsApp Image 2021-01-11 at 11.23.12 AM.jpeg")}}" alt="{{ \Auth::user()->organization->organization_name }}"> <br> <br> <br>
                                <div class="row">
                                <small><strong> J.Card No.{!! nl2br($jobSheet->id ) !!}</strong></small> <br>
                                <small><strong>Date : {{date('d-M-Y',strtotime($jobSheet->created_at))}}</strong></small>
                            </div>
                            </span>


                        </div>
                        <hr>
                    </div>

                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <!-- /.row -->

            <hr />
            <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <thead class="bg-gray">
                        <tr class="vendorListHeading">
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Model No</th>
                            <th>Serial | Service Tag</th>
                            <th>Problem</th>
                            <th>System Config</th>
                            <th>Status</th>
                            <th>Other Items</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--                            @foreach($challanDetail as $odk => $odv)--}}
                            <tr>
                                <td>{{ $jobSheet->type->name }}</td>
                                <td>{{ $jobSheet->brand }}</td>
                                <td>{{ $jobSheet->model_number }}</td>
                                <td>{{ $jobSheet->serial_number }}</td>
                                <td>{{ $jobSheet->reported_fault }}</td>
                                <td>{{ $jobSheet->device_config }}</td>
                                <td>{{ $jobSheet->status }}</td>
                                <td>@foreach($jobSheet->items as $item) <li>
                                        {{ ucfirst($item->item) }}
                                    </li> @endforeach</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            &nbsp; &nbsp;<small>1. This Card must be produced at the time of collecting items.</small><br>
            &nbsp; &nbsp;<small>2. Equipment should be collected within 15 days after informed by workshop,otherwise Microland won't be liable for the equipment.</small><br>
            &nbsp; &nbsp;<small>3. Lunch period form 3:00 PM to 3:30 PM.</small><br>
            &nbsp; &nbsp;<small>4. All Repair work undertaken on the risk of customer only.</small><br> <br>



            <div class="row">

                <!-- accepted payments column -->
                <div class="col-xs-4">


                    Perpared by: {{\Auth::user()->username}}<br/>
                    Printed Time: {{ date("F j, Y, g:i a") }} <br/>




                </div>
                <!-- /.col -->
                <div class="col-xs-4 pull-right">
                    <div class="table-responsive">



                        <table class="table" style="margin: 0">
                            <tbody>

                            <tr>
                                <th colspan="2"><br>______________________<br><span style="text-indent: 10px;">
                                    &nbsp;&nbsp; Receiver Signature</span></th>

                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-xs-4 pull-right">
                    <div class="table-responsive">



                        <table class="table" style="margin: 0">
                            <tbody>

                            <tr>
                                <th colspan="2"><br>______________________<br><span style="text-indent: 10px;">
                                    &nbsp;&nbsp; Customer Signature</span></th>

                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>


</div><!-- /.col -->

</body>
