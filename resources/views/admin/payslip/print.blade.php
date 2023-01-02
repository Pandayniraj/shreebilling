<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_COMPANY')}} | Requisition</title>

    <!-- block from searh engines -->
    <meta name="robots" content="noindex">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Set a meta reference to the CSRF token for use in AJAX request -->
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet"
          type="text/css"/>
    <!-- Font Awesome Icons 4.7.0 -->
    <link href="{{ asset("/bower_components/admin-lte/font-awesome/css/all.css") }}" rel="stylesheet" type="text/css"/>
    <!-- Ionicons 2.0.1 -->
    <link href="{{ asset("/bower_components/admin-lte/ionicons/css/ionicons.min.css") }}" rel="stylesheet"
          type="text/css"/>
    <!-- Theme style -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css"/>

    <!-- Application CSS-->


    <style type="text/css">
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            .text-center {
                text-align: center;
            }
            .item-detail td,th{
                border:1px solid #eee;
            }

            .bg-gray {
                background-color: #d2d6de !important;
            }
        }

        .text-center {
            text-align: center;
        }
        .item-detail td,th{
            border:1px solid #eee;
        }
        .bg-gray {
            background-color: #d2d6de !important;
        }

        .vendorListHeading th {
            background-color: #1a4567 !important;
            color: white !important;
        }

        table {
            width: 100%;
            /*border: 1px solid dotted !important;*/
            /*font-size: 14px !important;*/
            padding-top: 2px !important; /*cancels out browser's default cell padding*/
            padding-bottom: 2px !important; /*cancels out browser's default cell padding*/
        }

        td {
            /*border: 1px dotted #999 !important;*/
            padding-top: 2px !important; /*cancels out browser's default cell padding*/
            padding-bottom: 2px !important; /*cancels out browser's default cell padding*/
        }

        th {
            /*border: 1px dotted #999 !important;*/
            padding-top: 2px !important; /*cancels out browser's default cell padding*/
            padding-bottom: 2px !important; /*cancels out browser's default cell padding*/
        }

        .invoice-col {
            /*border: 1px dotted #1a4567 !important;*/
            font-size: 13px !important;
            margin-bottom: -20px !important;
        }


        @page {
            size: auto;
            margin: 0;
        }

        body {
            padding-left: 1.3cm;
            padding-right: 1.3cm;
            padding-top: 1.3cm;
        }

        @media print {
            .pagebreak {
                page-break-before: always;
            }

            /* page-break-after works, as well */
        }
    </style>


</head>

<body onload="window.print();" cz-shortcut-listen="true" class="skin-blue sidebar-mini">

<div class='row'>
    <div class='col-md-12'>

        <section class="invoice">
            <!-- title row -->

            <h2 class="page-header">
                <div style="text-align: center;">
                    <img src="{{asset('org/1611131027GNI Logo_190_1 px.png')}}" alt="Company Logo">
                    <div style="font-weight:bold">
                        REQUISITION FORM
                    </div>
                </div>
            </h2>
            <!-- info row -->
            <div class="row invoice-info">
                <table>
                    <tr>
                        <td style="width: 70%;">
                            <strong>Requisition No.: </strong>{{$requisition->requisition_no}}
                        </td>
                        <td style="width: 30%;">
                            <strong>For the Month: </strong>{{date("M-Y",strtotime($requisition->month))}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70%;">
                            <strong>Project Name: </strong>{{$requisition->project->name??'-'}}
                        </td>
                        <td style="width: 30%;">
                            <strong>Date:</strong> {{date('Y/m/d',strtotime($requisition->req_date))}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70%;">
                            <strong>Department:</strong> {{$requisition->department->deptname??'-'}}
                        </td>
                        <td style="width: 30%;">
                            <strong>USD Rate: </strong>{{$requisition->usd_rate??'_'}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70%;">
                            <strong>Budget Code:</strong> {{$requisition->budget_code??'-'}}
                        </td>
                    </tr>
                </table>
            </div>

            <h4 style="font-weight: bold;text-align: center;margin-top:10px">
                Requisition Details
            </h4>
            <!-- Table row -->
            <div class="row invoice-info">

            <table id="" class="table-striped item-detail">
                <thead>
                <tr>
                    <th class="text-center bg-gray">SN.</th>
                    <th style="width:20%" class="bg-gray">Particulars</th>
                    <th style="width:13%" class="text-center bg-gray">Unit</th>
                    <th class="text-center bg-gray">Quantity</th>
                    <th style="width:12%" class="text-center bg-gray">Rate(NRs)</th>
                    <th class="text-center bg-gray" style="width:13%">Expected Cost(NRs)</th>
                    <th class="text-center bg-gray" style="width:12%">USD Amount</th>
                    <th style="width: 20%;" class="bg-gray">Remarks</th>
                </tr>
                </thead>
                <tbody>
                @foreach($requisition->products as $odk => $odv)
                    <tr>
                        <td class="text-center">{{ $odk+1 }}</td>
                        <td>{{ $odv->product_name }}</td>
                        <td class="text-center">{{ $odv->unit_name }}</td>

                        <td class="text-center">{{ $odv->quantity }}</td>
                        <td class="text-center">{{ $odv->rate }}</td>
                        <td class="text-center">{{ $odv->expected_rate }}</td>
                        <td class="text-center">{{ $odv->usd_rate }}</td>
                        <td>{{ $odv->reason }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="bg-gray" colspan="5" style="text-align: right;font-weight:bold">Total Requisition
                        Budget
                    </td>
                    <td class="text-center bg-gray">NRs.{{number_format($requisition->total_expected_cost,2)}}</td>
                    <td class="text-center bg-gray">{{number_format($requisition->total_usd_amount,2)}}</td>
                    <td class="bg-gray"></td>
                </tr>
                </tbody>
            </table>
            <!-- /.col -->
            <?php
            $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
            ?>
            <div style="padding: 3px;"><strong>In
                    Words</strong>: {{ ucwords($f->format($requisition->total_expected_cost))}} only
            </div>
            <div style="border:1px solid #eee;margin:15px 0"></div>
                  <div style="display: flex">
                        <div style="width: 25%">
                        <div style="font-weight: bold;">Prepared By</div>
                        <div style="margin-top: 5px;height:60px;">
                            @if($requisition->preparedBy->signature)
                                <img style="margin-left:15px;height:60px;width:60px;object-fit: contain" src="{{asset('/images/profiles/'.$requisition->preparedBy->signature)}}" alt="">
                            @endif
                        </div>
                        <div >____________</div>
                        <div>{{$requisition->preparedBy->full_name}}</div>
                        <div>{{$requisition->preparedBy->int_designation}}</div>
                        <div>
                            Date: {{date('d M Y',strtotime($requisition->created_at))}}</div>
                        <div>Remarks: {{$requisition->remarks??'-'}}</div>
                        </div>
                    @if(\TaskHelper::hasApprovedStatus($requisition,'Check/Inspect','requisite'))
                          <div style="width: 25%">

                          <div style="font-weight: bold;">Checked By</div>
                            <div style="margin-top: 5px;height:60px;">
                                @if(\TaskHelper::hasApprovedStatus($requisition,'Check/Inspect','requisite')->requestTo->signature)
                                    <img style="margin-left:15px;height:60px;width:60px;object-fit: contain" src="{{asset('/images/profiles/'.\TaskHelper::hasApprovedStatus($requisition,'Check/Inspect','requisite')->requestTo->signature)}}" alt="">
                                @endif
                            </div>
                            <div>____________</div>
                            <div>{{\TaskHelper::hasApprovedStatus($requisition,'Check/Inspect','requisite')->requestTo->full_name}}</div>
                            <div>{{\TaskHelper::hasApprovedStatus($requisition,'Check/Inspect','requisite')->requestTo->int_designation}}</div>
                            <div>
                                Date: {{date('d M Y',strtotime(\TaskHelper::hasApprovedStatus($requisition,'Check/Inspect','requisite')->created_at))}}</div>
                            <div>Remarks: {{\TaskHelper::hasApprovedStatus($requisition,'Check/Inspect','requisite')->remarks}}</div>
                          </div>
                    @endif
                    @if(\TaskHelper::hasApprovedStatus($requisition,'Recommendation','requisite'))
                          <div style="width: 25%">
                          <div>
                            <div style="font-weight: bold;">Recommended By</div>
                            <div style="margin-top: 5px;height:60px;">
                                @if(\TaskHelper::hasApprovedStatus($requisition,'Recommendation','requisite')->requestTo->signature)
                                    <img style="margin-left:15px;height:60px;width:60px;object-fit: contain" src="{{asset('/images/profiles/'.\TaskHelper::hasApprovedStatus($requisition,'Recommendation','requisite')->requestTo->signature)}}" alt="">
                                @endif
                            </div>
                            <div >____________</div>
                            <div>{{\TaskHelper::hasApprovedStatus($requisition,'Recommendation','requisite')->requestTo->full_name}}</div>
                            <div>{{\TaskHelper::hasApprovedStatus($requisition,'Recommendation','requisite')->requestTo->int_designation}}</div>
                            <div>
                                Date: {{date('d M Y',strtotime(\TaskHelper::hasApprovedStatus($requisition,'Recommendation','requisite')->created_at))}}</div>
                            <div>Remarks: {{\TaskHelper::hasApprovedStatus($requisition,'Recommendation','requisite')->remarks}}</div>
                            </div>
                          </div>
                    @endif

                    @if(\TaskHelper::hasApprovedStatus($requisition,'Complete Approval','requisite'))
                          <div style="width: 25%">

                          <div style="font-weight: bold;">Approved By</div>
                            <div style="margin-top: 5px;height:60px;">
                                @if(\TaskHelper::hasApprovedStatus($requisition,'Complete Approval','requisite')->requestTo->signature)
                                    <img style="margin-left:15px;height:60px;width:60px;object-fit: contain" src="{{asset('/images/profiles/'.\TaskHelper::hasApprovedStatus($requisition,'Complete Approval','requisite')->requestTo->signature)}}" alt="">
                                @endif
                            </div>
                            <div >____________</div>
                            <div>{{\TaskHelper::hasApprovedStatus($requisition,'Complete Approval','requisite')->requestTo->full_name}}</div>
                            <div>{{\TaskHelper::hasApprovedStatus($requisition,'Complete Approval','requisite')->requestTo->int_designation}}</div>
                            <div>
                                Date: {{date('d M Y',strtotime(\TaskHelper::hasApprovedStatus($requisition,'Complete Approval','requisite')->created_at))}}</div>

                            <div>Remarks: {{\TaskHelper::hasApprovedStatus($requisition,'Complete Approval','requisite')->remarks}}</div>
                          </div>
                    @endif
            </div>
            <div class="row no-print">
                <div class="col-xs-12">


                </div>
            </div>
        </section>


    </div><!-- /.col -->

</div><!-- /.row -->


</body>
