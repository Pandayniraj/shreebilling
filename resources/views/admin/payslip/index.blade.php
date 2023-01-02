@extends('layouts.master')


@section('content')
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

    <style type="text/css">
        .table-bordered>thead>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{
            border-bottom: 1px solid #c4b8b8;
        }

        a.tip {
            border-bottom: 1px dashed;
            text-decoration: none
        }
        a.tip:hover {
            cursor: help;
            position: relative
        }
        a.tip span {
            display: none
        }
        a.tip:hover span {
            border: blue 1px solid;
            padding: 5px 20px 5px 5px;
            display: block;
            z-index: 100;
            color: red;
            background: yellow no-repeat 100% 5%;
            left: 0px;
            margin: 10px;
            width: 250px;
            position: absolute;
            top: 10px;
            text-decoration: none
        }
        #entry_list td,#entry_list th{


            font-size: 12px;

        }
    </style>
        <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
        <link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title"}}
                <small> {{ $page_description ?? "List of Requisitions" }}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>


<div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
	        <div class='col-md-12 d-flex'>
                <div class="wrap" style="margin-top:5px;">
                    <form method="get" action="/admin/requisition">
                        <div class="filter form-inline">
                            <a class="btn btn-primary btn-sm" style="margin-right: 20px"  title="Create New Requisition" href="{{ route('admin.requisition.create') }}">
                                <i class="fa fa-plus"></i>&nbsp;&nbsp;<strong>Create New</strong>
                            </a>
                            {!! Form::text('search', \Request::get('search'), ['style' => 'width:150px;', 'class' => 'form-control input-sm ', 'id'=>'search', 'placeholder'=>trans('Search Requisition No.'  ),'autocomplete' =>'off']) !!}&nbsp;&nbsp;
                            {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:120px;', 'class' => 'form-control input-sm  datepicker date-toggle-nep-eng1', 'id'=>'start_date', 'placeholder'=>trans('Start Date'  ),'autocomplete' =>'off']) !!}&nbsp;&nbsp;
                            <!-- <label for="end_date" style="float:left; padding-top:7px;">End Date: </label> -->
                            {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:120px; display:inline-block;', 'class' => 'form-control datepicker input-sm date-toggle-nep-eng1', 'id'=>'end_date', 'placeholder'=>trans('End Date'),'autocomplete' =>'off']) !!}&nbsp;&nbsp;

                            {!! Form::select('project_id', ['' => 'Select Project'] + $projects, \Request::get('project_id'), ['id'=>'filter-project', 'class'=>'form-control input-sm select2', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                            {!! Form::select('department_id', ['' => 'Select Department'] + $departments, \Request::get('department_id'), ['id'=>'', 'class'=>'form-control input-sm select2', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

{{--                            <input type="hidden" name="type" value={{ Request::get('type') }}>--}}
                            <button class="btn btn-primary btn-sm" id="btn-submit-filter" type="submit">
                                <i class="fa fa-list"></i> Filter
                            </button>
                            <a href="/admin/requisition" class="btn btn-danger btn-sm" id="btn-filter-clear" >
                                <i class="fa fa-close"></i> Clear
                            </a>

                        </div>
                    </form>
                </div>
	        </div>
       </div>
        <div class="tab-pane" id="requisition-tabs" style="margin-top: 20px;position: relative;">
            <div class="nav-tabs-custom">
                <!-- Tabs within a box -->

                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="{{route('admin.requisition.index')}}"  aria-expanded="true">My Requisition</a>
                    </li>
                    <li class="">
                        <a href="{{route('admin.requisition.index',['type'=>'pending'])}}"  aria-expanded="false">
                            Pending for Approval <small><span class="fa-stack">
                                            <span class="fa fa-circle-o fa-stack-2x"></span>
                                            <strong class="fa-stack-1x bg-orange">

                                                {{$pending_requisitions_count}}

                                            </strong>

                                        </span></small></a>
                    </li>
                    <li class="">
                        <a href="{{route('admin.requisition.index',['type'=>'approved'])}}"  aria-expanded="false">My Approvals
                        </a>
                    </li>
                </ul>
                <div class="tab-content bg-white">

                    <div class="tab-pane active" id="my_requisition">
                        <div class="table-responsive">
                            <table class="table table-hover table-no-border" id="clients-table" >

                                <thead>
                                <tr style="background: #3c8dbc;color: #ffffff;">

                                    <th>SN</th>
                                    <th>Requisition No</th>
                                    <th>Department</th>
                                    <th>Month</th>
                                    <th>Project</th>
                                    <th class="text-center" style="width: 10%;">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Review</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="entry_list">
                                @if($requisitions->isNotEmpty())

                                    @foreach($requisitions as $key=>$att)

                                    <tr>

                                        <td>{{($key+1+(\Request::get('page')?\Request::get('page'):1-1)*20)}}.
                                        </td>
                                        <td><a href="{{route('admin.requisition.show',$att->id)}}" target="_blank">{{ $att->requisition_no }}</a></td>
                                        <td>{{ $att->department?$att->department->deptname :'-'}}</td>
                                        <td>{{ date("M,Y",strtotime($att->month)) }}</td>
                                        <td>{{ $att->project?$att->project->name:'-' }}</td>
                                        <td class="text-center" style="width: 15%;">NRs. {{ $att->total_expected_cost }}
                                        <div style="color:grey;font-size: 12px;">
                                            Items: {{$att->total_items}}
                                        </div>
                                        </td>
                                        <td class="text-center">
                                            @if($att->roundApprovals->isNotEmpty())
                                                <div class="label {{ $att->latestRound->approval_type=='Complete Approval'&&$att->latestRound->status=='Approved'?'label-success':'label-primary'}}">
                                                    {{$att->latestRound->approval_type=='Complete Approval'&&$att->latestRound->status=='Approved'?'Approved':
                                                    $att->latestRound->approval_type}}
                                                </div>
                                                <br>
                                                <div class="label {{$att->latestRound->approval_type=='Complete Approval'&&$att->latestRound->status=='Approved'?'label-success':'label-primary'}}">
                                                    {{$att->latestRound->requestTo->full_name}}
                                                </div>
                                            @else
                                                <div class="label label-danger">
                                                    Unassigned
                                                </div>
                                            @endif

                                        </td>
                                        <td class="text-center">
                                            <a href="{{route('admin.reviews',array('type'=>'requisite','id'=>$att->id))}}"  escape="false" class="btn btn-outline-primary btn-sm"> Review</a>

                                        </td>

                                        <td>
                                            @if($att->isApproved())
                                            <a href="/admin/requisition/pdf/{{ $att->id }}">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <a href="/admin/requisition/print/{{ $att->id }}" target="_blank">
                                                <i class="fa fa-print"></i>
                                            </a>&nbsp;
                                            @endif
                                        @if($att->isEditable())
                                            <a href="/admin/requisition/{{$att->id}}/edit"><i class="fa fa-edit"></i></a>&nbsp;
                                                @endif
                                            @if($att->isDeletable())
                                            <a href="{!! route('admin.requisition.confirm-delete', $att->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
                                                @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" style="font-size: 16px;
    color: grey;
    font-weight: 600;
    text-align: center;">
                                            No data available...
                                        </td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                            <div class="center">

                                {!!  $requisitions->appends(\Request::except('page'))->render() !!}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 </div>
</div>
    <script type="text/javascript">
        $('.datepicker').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD',
            sideBySide: true
        });
        $('.select2').select2();
        //
        // $(function() {
        //     $('#clients-table').DataTable({
        //         pageLength: 35,
        //         "order": [[ 2, "desc" ]],
        //         "searching": false,
        //         "paging":   false,
        //     });
        // });


    </script>
    @endsection



