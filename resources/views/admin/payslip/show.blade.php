@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
    <style>
        .box-comment {
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .item-detail td,th{
            border:1px solid #eee;
        }
        .box-comment img {
            float: left;
            margin-right: 10px;
        }

        .username {
            font-weight: bold;
        }

        .comment-text span {
            display: block;
        }

        body {
            line-height: 22px;
        }
    </style>

    <div class='row'>
        <div class='col-md-12'>

            <section class="invoice">
                <!-- title row -->
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="page-header">
                            <?php
                            $type='requisite';
                            ?>
                            <div class="highlight_icons" style="margin-left: 20px;float: right;display: inline-flex;">
                                @if($requisition->roundApprovals()->where('round_type',$type)->get()->isEmpty())
                                    <span class="review-btn">
                                    <a href="{{route('admin.review.confirm-review',array('type'=>$type,'id'=>$requisition->id,'status'=>'approve'))}}"
                                       data-toggle="modal" data-target="#modal_dialog" class="btn btn-success"><i
                                            class="fa fa-check" style="padding: 0"
                                            aria-hidden="true"></i>Assign User</a></span>&nbsp;
                                @else
                                    @if(!($requisition->roundApprovals()->where('round_type',$type)->latest()->first()->approval_type=='Complete Approval'&&$requisition->roundApprovals()->where('round_type',$type)->latest()->first()->status=='Approved'
                                    ||$requisition->roundApprovals()->where('round_type',$type)->latest()->first()->request_to!=auth()->user()->id))
                                        <span class="review-btn">
                                    <a href="{{route('admin.review.confirm-review',array('type'=>$type,'id'=>$requisition->id,'status'=>'approve'))}}"
                                       data-toggle="modal" data-target="#modal_dialog" class="btn btn-success btn-sm"><i
                                            class="fa fa-check" style="padding: 0"
                                            aria-hidden="true"></i>Approve & Process</a></span>&nbsp;
                                        @if($requisition->roundApprovals()->where('round_type',$type)->latest()->first()->approval_type!='Modification')
                                            <span class="review-btn">
                                    <a href="{{route('admin.review.confirm-review',array('type'=>$type,'id'=>$requisition->id,'status'=>'reject'))}}"
                                       data-toggle="modal" data-target="#modal_dialog" class="btn btn-danger btn-sm"><i
                                            class="fa fa-times" style="padding: 0"
                                            aria-hidden="true"></i> Reject</a></span>&nbsp;
                                        @endif
                                    @endif
                                @endif
                                <span>
                                <a target="_blank" href="{{route('admin.reviews',array('type'=>'requisite','id'=>$requisition->id))}}"
                                   class="btn btn-primary btn-sm">View Reviews</a>
                                        </span>
                            </div>
              <span class="pull-left">

            <button type="button" class="btn btn-sm btn-danger pull-right" onclick="window.close()">
              <i class="fa fa-times-circle"></i> Close
            </button>

{{--           <a href="/admin/requisition/print/{{ $requisition->id }}" target="_blank" class="btn btn-xs btn-default pull-right" style="margin-right: 5px;">--}}
                  {{--               <i class="fa fa-print"></i> Print</a>&nbsp;&nbsp;--}}
                  @if($requisition->isApproved())
                  <a href="/admin/requisition/pdf/{{ $requisition->id }}">
            <button type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 5px;">
              <i class="fa fa-download"></i> PDF
            </button>
          </a>

                  <a href="/admin/requisition/print/{{ $requisition->id }}" target="_blank"
                                                 class="btn btn-sm btn-default pull-right" style="margin-right: 5px;"   ><i class="fa fa-print"></i> Print</a>&nbsp;&nbsp;
                  @endif
                      @if($requisition->isEditable())
                  <a href="/admin/requisition/{{ $requisition->id }}/edit" target="_blank">
            <button type="button" class="btn btn-sm btn-primary pull-right" style="margin-right: 5px;">
              <i class="fa fa-edit"></i> Edit
            </button>
          </a> &nbsp;
                      @endif
            </span>
                            <div class="clearfix"></div>
                            <div style="text-align: center;">
                                <img src="{{asset('org/1611131027GNI Logo_190_1 px.png')}}" alt="Company Logo">
                                <div style="font-weight:bold">
                                    REQUISITION FORM
                                </div>
                            </div>
                        </h2>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row invoice-info">
                    <div class="col-sm-8 invoice-col">
                        <label for="">
                        Requisition No.:</label>{{$requisition->requisition_no}}
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <label for="">For the Month: </label>{{date("M Y",strtotime($requisition->month))}}
                    </div>
                    <div class="col-sm-8 invoice-col">
                        <label for="">Project Name:</label> {{$requisition->project->name??'-'}}
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <label for="">Date: </label>{{date('d M Y',strtotime($requisition->req_date))}}
                    </div>
                    <div class="col-sm-8 invoice-col">
                        <label for="">Department: </label>{{$requisition->department->deptname??'-'}}
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <label for="">Balance: </label>NRs. {{number_format($requisition->balance,2)}}
                    </div>
                    <div class="col-sm-8 invoice-col">
                        <label for="">Accumulated Income:</label> NRs. {{number_format($requisition->accumulated_income,2)}}
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <label for="">Accumulated Expense:</label> NRs. {{number_format($requisition->accumulated_expense,2)}}
                    </div>
                    <div class="col-sm-8 invoice-col">
                        <label for="">USD Rate:</label> {{number_format($requisition->usd_rate,2)}}
                    </div>
                    <div class="col-sm-4 invoice-col"><strong>Budget Code</strong>: {{$requisition->budget_code??'-'}}</div>

                    <div class="col-sm-8 invoice-col" style="display:flex">
                        <strong>Document</strong>:  @if($requisition->document)
                            <a style="overflow: hidden;
                                                width: 600px;text-overflow: ellipsis;white-space: nowrap;margin-left: 10px;"
                               target="_blank"
                               href="{{asset('/documents/requisition/'.$requisition->document)}}">
                                {{$requisition->document}}</a>
                        @else
                            -
                        @endif
                    </div>

                </div>

                <h4 style="font-weight: bold;text-align: center;margin-top:10px">
                    Requisition Details
                </h4>
                <!-- Table row -->
                <div class="row">
                    <div class="col-xs-12 table-responsive">
                        <table id="" class="table table-striped item-detail">
                            <thead class="bg-gray">
                            <tr>
                                <th class="text-center">SN.</th>
                                <th style="width:20%">Particulars</th>
                                <th class="text-center" style="width:13%">Unit</th>
                                <th class="text-center" style="width:10%">Quantity</th>
                                <th class="text-center" style="width:12%">Rate Per Unit(NRs)</th>
                                <th class="text-center" style="width:13%">Expected Cost(NRs)</th>
                                <th class="text-center" style="width:12%">USD Amount</th>
                                <th style="width:20%">Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requisition->products as $odk => $odv)
                                <tr>
                                    <td class="text-center">{{ $odk+1 }}.</td>
                                    <td>{{ $odv->product_name }}</td>
                                    <td class="text-center">{{ $odv->unit_name?$odv->unit_name:'-' }}</td>

                                    <td class="text-center">{{ $odv->quantity }}</td>
                                    <td class="text-center">{{ $odv->rate }}</td>
                                    <td class="text-center">{{ $odv->expected_rate }}</td>
                                    <td class="text-center">{{ $odv->usd_rate }}</td>
                                    <td>{{ $odv->reason }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray">
                                <td colspan="5" style="text-align: right;font-weight:bold">Total Requisition Budget</td>
                                <td class="text-center">NRs.{{number_format($requisition->total_expected_cost,2)}}</td>
                                <td class="text-center">{{number_format($requisition->total_usd_amount,2)}}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.col -->
                </div>
                <?php
                $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
                ?>
                <div style="padding: 3px;"><strong>In
                        Words</strong>: {{ ucwords($f->format($requisition->total_expected_cost))}} only
                </div>
                <div style="border:1px solid #eee;margin:15px 0"></div>
                <div class="row">
                    <div class="col-md-3">
                        <?php
                        $assigned_prepared_round=\TaskHelper::hasAssignedUser($requisition,'Modification','requisite');

                        ?>
                        <div style="font-weight: bold;">Prepared By&nbsp;&nbsp;
                            @if($assigned_prepared_round)<span class="label label-warning">Pending</span>@endif
                        </div>
                        <div>{{$requisition->preparedBy->full_name}}</div>
                        <div>{{$requisition->preparedBy->int_designation}}</div>
                        <div>Date: {{date('d M Y',strtotime($requisition->created_at))}}</div>
                        <div>Remarks: {{$requisition->remarks??'-'}}</div>
                    </div>
                    <?php
                    $checked_round=\TaskHelper::hasApprovedStatus($requisition,'Check/Inspect','requisite');
                    $recommended_round=\TaskHelper::hasApprovedStatus($requisition,'Recommendation','requisite');
                    $approval_round=\TaskHelper::hasApprovedStatus($requisition,'Complete Approval','requisite');
                    $assigned_checked_round=\TaskHelper::hasAssignedUser($requisition,'Check/Inspect','requisite');
                    $assigned_recommended_round=\TaskHelper::hasAssignedUser($requisition,'Recommendation','requisite');
                    $assigned_approval_round=\TaskHelper::hasAssignedUser($requisition,'Complete Approval','requisite');

                    ?>
                    @if($checked_round)
                        <div class="col-md-3">
                            <div style="font-weight: bold;">Checked By&nbsp;&nbsp;
                            </div>
                            <div>{{$checked_round->requestTo->full_name}}</div>
                            <div>{{$checked_round->requestTo->int_designation}}</div>
                            <div>Date: {{date('d M Y',strtotime($checked_round->created_at))}}</div>
                            <div>Remarks: {{$checked_round->remarks??'-'}}</div>
                        </div>
                    @elseif($assigned_checked_round)
                        <div class="col-md-3">
                            <div style="font-weight: bold;">Checked By&nbsp;&nbsp;
                               <span class="label label-warning">Pending</span>

                            </div>
                            <div>{{$assigned_checked_round->requestTo->full_name}}</div>
                            <div>{{$assigned_checked_round->requestTo->int_designation}}</div>
                            <div>Date: {{date('d M Y',strtotime($assigned_checked_round->created_at))}}</div>
                            @if($assigned_checked_round->remarks)<div>Remarks: {{$assigned_checked_round->remarks}}</div>@endif
                        </div>

                    @endif
                    @if($recommended_round)
                        <div class="col-xs-3">
                            <div style="font-weight: bold;">Recommended By&nbsp;&nbsp;
                            </div>
                            <div>{{$recommended_round->requestTo->full_name}}</div>
                            <div>{{$recommended_round->requestTo->int_designation}}</div>
                            <div>Date: {{date('d M Y',strtotime($recommended_round->created_at))}}</div>
                            <div>Remarks: {{$recommended_round->remarks??'-'}}</div>

                        </div>
                    @elseif($assigned_recommended_round)
                        <div class="col-xs-3">
                            <div style="font-weight: bold;">Recommended By&nbsp;&nbsp;
                                <span class="label label-warning">Pending</span>
                            </div>
                            <div>{{$assigned_recommended_round->requestTo->full_name}}</div>
                            <div>{{$assigned_recommended_round->requestTo->int_designation}}</div>
                            <div>Date: {{date('d M Y',strtotime($assigned_recommended_round->created_at))}}</div>
                           @if($assigned_recommended_round->remarks)<div>Remarks: {{$assigned_recommended_round->remarks}}</div>@endif

                        </div>
                    @endif
                    @if($approval_round)
                        <div class="col-xs-3 ">
                            <div style="font-weight: bold;">Approved By&nbsp;&nbsp;
                            </div>
                            <div>{{$approval_round->requestTo->full_name}}</div>
                            <div>{{$approval_round->requestTo->int_designation}}</div>
                            <div>Date: {{date('d M Y',strtotime($approval_round->created_at))}}</div>
                            <div>Remarks: {{$approval_round->remarks??'-'}}</div>

                        </div>
                    @elseif($assigned_approval_round)
                        <div class="col-xs-3 ">
                            <div style="font-weight: bold;">Approved By&nbsp;&nbsp;
                                <span class="label label-warning">Pending</span>
                            </div>
                            <div>{{$assigned_approval_round->requestTo->full_name}}</div>
                            <div>{{$assigned_approval_round->requestTo->int_designation}}</div>
                            <div>Date: {{date('d M Y',strtotime($assigned_approval_round->created_at))}}</div>
                            @if($assigned_approval_round->remarks)<div>Remarks: {{$assigned_approval_round->remarks}}</div>@endif

                        </div>
                    @endif
                </div>
{{--                <div class="row">--}}
{{--                    <div class="col-xs-6 form-group" style="margin-top:5px;display: flex;align-items: baseline">--}}
{{--                        <label for="comment" >Remarks: </label>--}}
{{--                        <p class="text-muted well well-sm well-primary no-shadow"--}}
{{--                           style="margin-top: 10px;min-height:100px;width: 100%;margin-left: 15px;">--}}
{{--                            {!! $requisition->comment?$requisition->comment:'-' !!}--}}
{{--                        </p>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="row no-print">
                    <div class="col-xs-12">


                    </div>
                </div>
            </section>


        </div><!-- /.col -->

    </div><!-- /.row -->




@endsection

@section('body_bottom')
    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_user_search')
@endsection
