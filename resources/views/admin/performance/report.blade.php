@extends('layouts.master')

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              Appraisal
                <small>Appraisal report</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<style>
    .err { border: 1px solid red; }
    .text-muted { font-size: 12px; color: red !important; }
    .navbar-custom-nav {
        background: #FFFFFF;
        box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.15);
        margin-top: 10px !important;
    }
    .navbar-custom-nav li {
        border-bottom: 1px solid #cfdbe2;
    }
    .table > thead > tr > th {
        color: rgb(136, 136, 136);
        padding: 14px 8px;
    }
    thead {
        display: table-header-group;
        vertical-align: middle;
        border-color: inherit;
    }
    .panel .table {
        margin-bottom: 0px;
        border-width: 0px;
        border-style: initial;
        border-color: initial;
        border-image: initial;
    }
    .panel > .table:last-child, .panel > .table-responsive:last-child > .table:last-child {
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
    }
    .required {
        color: red;
    }
    .color-tag.active {
        border-radius: 50%;
    }
    .color-tag {
        display: inline-block;
        width: 13px;
        height: 13px;
        margin: 2px 10px 0 0;
        transition: all 300ms ease;
    }
    .clickable {
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-lg-12 box">
        <div class="row box-header">
            <div class="col-sm-3">
                <form id="change_date" action="/admin/performance/report" method="get">
                    <label for="field-1" class="control-label pull-left holiday-vertical"><strong>Year:</strong></label>
                    <div class="col-sm-8">
                        <input type="text" name="year" id="year" class="form-control years" value="{{ \Request::get('year')? \Request::get('year') : date('Y') }}" data-format="yyyy">
                    </div>
                    <!-- <button type="submit" id="search_product" data-toggle="tooltip" data-placement="top" title="" class="btn btn-purple pull-right" data-original-title="Search">
                    <i class="fa fa-search"></i></button> -->
                </form>
            </div>
            <div class="col-sm-9">
                <a href="{{ route('admin.performance.giveappraisal') }}" class="btn btn-primary" >
                    <span class="fa fa-plus-square"> </span> Give Appraisal
                </a>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-3">
                <ul class="mt nav nav-pills nav-stacked navbar-custom-nav" id="event_month">
                    <li @if(date('m') == '01') class="active" @endif>
                        <a aria-expanded="true" data-toggle="tab" href="#January" >
                        <i class="fa fa-fw fa-calendar"></i> January </a>
                    </li>
                    <li @if(date('m') == '02') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#February" >
                        <i class="fa fa-fw fa-calendar"></i> February </a>
                    </li>
                    <li @if(date('m') == '03') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#March" >
                        <i class="fa fa-fw fa-calendar"></i> March </a>
                    </li>
                    <li @if(date('m') == '04') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#April" >
                        <i class="fa fa-fw fa-calendar"></i> April </a>
                    </li>
                    <li @if(date('m') == '05') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#May" >
                        <i class="fa fa-fw fa-calendar"></i> May </a>
                    </li>
                    <li @if(date('m') == '06') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#June" >
                        <i class="fa fa-fw fa-calendar"></i> June </a>
                    </li>
                    <li @if(date('m') == '07') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#July" >
                        <i class="fa fa-fw fa-calendar"></i> July </a>
                    </li>
                    <li @if(date('m') == '08') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#August" >
                        <i class="fa fa-fw fa-calendar"></i> August </a>
                    </li>
                    <li @if(date('m') == '09') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#September" >
                        <i class="fa fa-fw fa-calendar"></i> September </a>
                    </li>
                    <li @if(date('m') == '10') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#October" >
                        <i class="fa fa-fw fa-calendar"></i> October </a>
                    </li>
                    <li @if(date('m') == '11') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#November" >
                        <i class="fa fa-fw fa-calendar"></i> November </a>
                    </li>
                    <li @if(date('m') == '12') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#December" >
                        <i class="fa fa-fw fa-calendar"></i> December </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9">
                <div class="tab-content pl0">
                    <?php
                        $month = 'January';
                    if(\Request::get('year') == ""){
                        $current = date('Y');
                    }
                        else{
                        $current = \Request::get('year');
                    }
                    ?>
                    @for($i=1; $i<=12; $i++)
                        <?php
                            $count_flag = 0;

                            if($i < 10) $j = '0'.$i;
                            else $j = $i;
                            if($j == '02') $month = 'February';
                            if($j == '03') $month = 'March';
                            if($j == '04') $month = 'April';
                            if($j == '05') $month = 'May';
                            if($j == '06') $month = 'June';
                            if($j == '07') $month = 'July';
                            if($j == '08') $month = 'August';
                            if($j == '09') $month = 'September';
                            if($j == '10') $month = 'October';
                            if($j == '11') $month = 'November';
                            if($j == '12') $month = 'December';
                        ?>

                        <div id="{{ $month }}" class="tab-pane @if(date('m') == $j) active @endif">
                            <div class="panel panel-custom">
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <strong><i class="fa fa-calendar"></i> {{ $month }}</strong>
                                    </div>
                                </div>
                                <!-- Table -->
                                <div id="tablecontent{{$i}}">
                                       <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr class="bg-info">
                                            <th class="col-sm-1">Emp Id</th>
                                            <th class="col-sm-2">Emp name</th>
                                            <th class="col-sm-2">Department</th>
                                            <th class="col-sm-1">Desigination</th>
                                            <th class="col-sm-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php 
                                     $date = $current ."-".$j;
    $report = \App\Models\PerformanceApprisal::select('performance_appraisal.performance_appraisal_id','users.username','users.id','users.departments_id','users.designations_id')->leftjoin('users','users.id','=','performance_appraisal.user_id')->where('appraisal_month',$date)->get();
                                     ?>
                                     @if(count($report)>0)
                                        @foreach($report as $key => $rp)  
                                <tr>
                                    <td>Emp-{{ $rp->id }}</td>
                                    <td>{{ ucfirst(trans($rp->username)) }}</td>
                                    <td><?php 
                                    $dep = \App\Models\Department::where('departments_id',$rp->departments_id)->first();
                                    echo ucfirst(trans($dep->deptname));
                                    ?></td>
                                    <td><?php 
                                    $des = \App\Models\Designation::where('designations_id',$rp->designations_id)->first();
                                    echo $des->designations;
                                    ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-xs event_edit" title="Edit" data-placement="top" data-id="{{ $rp->performance_appraisal_id }}"><span class="fa fa-edit"></span></button>
                                        <a href="{{route('admin.performance.delete-appeaisal',$rp->performance_appraisal_id)}}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                   @else
                                        <tr>
                                            <td colspan="3">
                                                <strong>Nothing to display here!</strong>
                                            </td>
                                        </tr>
                                     @endif
                                    </tbody>
                                    
                             
                                </table>
                                   </div>
                            </div>
                        </div>

                    @endfor

                </div>
            </div>
        </div>          
    </div>
</div>

<div class="modal fade in" id="eventModal" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 50% !important;">
        <div class="modal-content">
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="eventModalLabel">User Appraisal</h4>
                </div>
                <div class="modal-body wrap-modal wrap">
                    <div id ="showreport"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<!-- Timepicker -->
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/timepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/timepicker.js") }}" type="text/javascript"></script>

<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>
<script>
    $(function() {
        $('.years').datetimepicker({
            //inline: true,
            format: 'YYYY',
            //sideBySide: true
        })
        .on('dp.change', function (event) {
            $('#change_date').submit();
        });

        $('.start_date, .end_date').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
        });

    });


    $(document).on('click', '.event_edit', function() {
        var perid = $(this).attr('data-id');
        $('#showreport').load('/admin/performance/report1/'+perid,()=>{
            $('#eventModal').modal('show');
        });
        
        return 0;
       
    });

 

</script>
@endsection
