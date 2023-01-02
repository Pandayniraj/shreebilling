@extends('layouts.master')

@section('content')
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
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Overtime (OT)
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p> Entry for overtime</p>

          {{ TaskHelper::topSubMenu('topsubmenu.payroll')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-sm-2">
                <form id="change_date" action="/admin/payroll/over_time" method="get">
                    <label for="field-1" class="control-label pull-left"><strong>Year:</strong></label>
                    <div class="col-sm-8">
                        <input type="text" name="year" id="year" class="form-control years" value="{{ \Request::get('year')? \Request::get('year') : date('Y') }}" data-format="yyyy">
                    </div>
                    <!-- <button type="submit" id="search_product" data-toggle="tooltip" data-placement="top" title="" class="btn btn-purple pull-right" data-original-title="Search">
                    <i class="fa fa-search"></i></button> -->
                </form>
            </div>
            <div class="col-sm-10">
                <a href="" class="btn btn-primary pull-right" data-toggle="modal" data-placement="top" data-target="#eventModal">
                    <span class="fa fa-plus "> </span> Request Over Time
                </a>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-3">
                <ul class="mt nav nav-pills nav-stacked navbar-custom-nav" id="event_month">
                    <li @if(date('m') == '01') class="active" @endif>
                        <a aria-expanded="true" data-toggle="tab" href="#January">
                        <i class="fa fa-fw fa-calendar"></i> January </a>
                    </li>
                    <li @if(date('m') == '02') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#February">
                        <i class="fa fa-fw fa-calendar"></i> February </a>
                    </li>
                    <li @if(date('m') == '03') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#March">
                        <i class="fa fa-fw fa-calendar"></i> March </a>
                    </li>
                    <li @if(date('m') == '04') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#April">
                        <i class="fa fa-fw fa-calendar"></i> April </a>
                    </li>
                    <li @if(date('m') == '05') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#May">
                        <i class="fa fa-fw fa-calendar"></i> May </a>
                    </li>
                    <li @if(date('m') == '06') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#June">
                        <i class="fa fa-fw fa-calendar"></i> June </a>
                    </li>
                    <li @if(date('m') == '07') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#July">
                        <i class="fa fa-fw fa-calendar"></i> July </a>
                    </li>
                    <li @if(date('m') == '08') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#August">
                        <i class="fa fa-fw fa-calendar"></i> August </a>
                    </li>
                    <li @if(date('m') == '09') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#September">
                        <i class="fa fa-fw fa-calendar"></i> September </a>
                    </li>
                    <li @if(date('m') == '10') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#October">
                        <i class="fa fa-fw fa-calendar"></i> October </a>
                    </li>
                    <li @if(date('m') == '11') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#November">
                        <i class="fa fa-fw fa-calendar"></i> November </a>
                    </li>
                    <li @if(date('m') == '12') class="active" @endif>
                        <a aria-expanded="false" data-toggle="tab" href="#December">
                        <i class="fa fa-fw fa-calendar"></i> December </a>
                    </li> 
                </ul>
            </div>
            <div class="col-md-9">
                <div class="tab-content pl0">
                    <?php
                        $month = 'January';
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
                                        <h4><i class="fa fa-calendar"></i> &nbsp; {{ $month }}</h4>
                                    </div>

                                </div>
                                <!-- Table -->
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="bg-success">
                                            <th class="col-sm-1">EMP ID</th>
                                            <th class="col-sm-3">Name</th>
                                            <th class="col-sm-2">OverTime Date</th>
                                            <th class="col-sm-2">OverTime Hours</th>
                                            <th class="col-sm-2">Status</th>
                                            <th class="col-sm-1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                         ?>
                            
                                        @foreach($overtimes as $ask => $asv)
                                         <?php
                                                $m_temp = explode('-', $asv->overtime_date);
                                                
                                                
                                            ?>
                                            @if($m_temp[1] == $j)
                                            <?php $count_flag++ ?>   
                                            <tr>
                                                <td>{{ $asv->user_id }}</td>
                                                <?php $user = $asv->user; ?>
                                                <td>{{ $user->first_name.' '.$user->last_name }}</td>
                                                <td>{{ $asv->overtime_date }}</td>
                                                <td>{{ $asv->overtime_hours }}</td>
                                                <td>
                                                    <span class="label @if($asv->status == 'pending') label-warning @elseif($asv->status == 'approved') label-success @elseif($asv->status == 'rejected') @else label-danger @endif">@if($asv->status == 'pending') Pending @elseif($asv->status == 'approved') Accepted @elseif($asv->status == 'rejected') Rejected @else Paid @endif</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary btn-xs event_edit" title="Edit" data-placement="top" data-id="{{ $asv->overtime_id }}"><span class="fa fa-edit"></span></button>
                                                    <a href="/admin/payroll/over_time_delete/{{ $asv->overtime_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>

                                           
                                             @endif
                                        @endforeach
                                        @if(!$count_flag)
                                        <tr>
                                            <td colspan="6">
                                                <strong>Nothing to display here!</strong>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    @endfor

                </div>
            </div>
        </div>           
    </div>
</div>



<div class="modal fade in" id="eventModal" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="eventModalLabel">Request Over Time</h4>
                </div>
                <div class="modal-body wrap-modal wrap">
                    <form data-parsley-validate="" novalidate="" action="/admin/payroll/over_time" method="post" class="form-horizontal form-groups-bordered" id="">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Employee
                            <span class="required">*</span></label>
                            <div class="col-sm-8">
                               <select class="form-control select2 product_id" name="user_id" id="user_id" required="required">
                                @if(isset($users))
                                    <option value="">Select Employee</option>
                                    @foreach($users as $key => $pk)
                                    <option value="{{ $pk->id }}"@if( isset($odv) && $odv->user_id == $pk->id) selected="selected"@endif>{{ $pk->first_name }} {{ $pk->last_name }} ({{$pk->designation->designations ??''}})</option>
                                    @endforeach
                                @endif
                               </select>
                                <small class="form-text text-muted" style="display: none;">Event Name is required.</small>
                            </div>
                        </div>
                        <input type="hidden" name="status" id="status" value="approved">
                        <div class="form-group"> 
                            <label for="field-1" class="col-sm-3 control-label">Date<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input required="" type="text" class="form-control req overtime_date" name="overtime_date" id="overtime_date" value="" data-parsley-id="8">
                                    <small class="form-text text-muted" style="display: none;">Date is Required .</small>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Overtime Hours<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input required="" type="text" class="form-control req " id="overtime_hours" name="overtime_hours" value="" data-parsley-id="10">
                                    <small class="form-text text-muted" style="display: none;">Over time hours is required.</small>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Notes<span class="required"> *</span></label>

                            <div class="col-sm-8">
                            <textarea required="" style="height: 100px" name="notes" class="form-control req" id="notes" placeholder="Enter Your Description" data-parsley-id="6"></textarea>
                            <small class="form-text text-muted" style="display: none;">Notes is required.</small>
                            </div>
                        </div>
                        <div class="form-group margin">
                            <div class="col-sm-offset-3 col-sm-5">
                                <button type="submit" id="sbtn" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $(".color-palet span").click(function () {
                            $(".color-palet").find(".active").removeClass("active");
                            $(this).addClass("active");
                            $("#color").val($(this).attr("data-color"));
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
@endsection



@section('body_bottom')


<script type="text/javascript">
            $(function () {
                $('#overtime_hours').datetimepicker({
                    useCurrent: false,
                    format: 'HH:mm'

                });
            });
        </script>
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

        $('.start_date, .end_date, .overtime_date').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
        });

    });


    $(document).on('click', '.event_edit', function() { 
        var overtimeId = $(this).attr('data-id');
        $.post("/admin/payroll/over_time/"+overtimeId,
        {id: overtimeId, _token: $('meta[name="csrf-token"]').attr('content')},
        function(data, status){
            
            $('#eventModal #user_id').val(data.user_id);
            $('#eventModal #overtime_date').val(data.overtime_date);
            $('#eventModal #overtime_hours').val(data.overtime_hours);
            $('#eventModal #notes').val(data.notes);
            $('#eventModal #status').val(data.status);

            $('#eventModal form').attr('action', '/admin/payroll/over_time_update/'+overtimeId);
            $('#eventModal').modal('show');
            //alert(data.event_name);
          //alert("Data: " + data + "\nStatus: " + status);
        });
    });

    $('#eventModal').on('hidden.bs.modal', function () {
        $('#eventModal form').attr('action', '/admin/payroll/over_time');
        $(this).find('form').trigger('reset');
    })




</script>

@endsection
