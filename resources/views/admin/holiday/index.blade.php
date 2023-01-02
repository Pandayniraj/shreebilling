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
                Holidays
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p>Company yearly holiday list</p>

          {{ TaskHelper::topSubMenu('topsubmenu.payroll')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-sm-3">
                <form id="change_date" action="/admin/holiday" method="get">
                    <label for="field-1" class="control-label pull-left holiday-vertical"><strong>Year:</strong></label>
                    <div class="col-sm-8">
                        <input type="text" name="year" id="year" class="form-control years" value="{{ \Request::get('year')? \Request::get('year') : date('Y') }}" data-format="yyyy">
                    </div>
                    <!-- <button type="submit" id="search_product" data-toggle="tooltip" data-placement="top" title="" class="btn btn-purple pull-right" data-original-title="Search">
                    <i class="fa fa-search"></i></button> -->
                </form>
            </div>
            <div class="col-sm-9">
                @if(\Auth::user()->hasRole('admins'))
                <a href="" class="btn btn-primary" data-toggle="modal" data-placement="top" data-target="#eventModal">
                    <span class="fa fa-plus "> </span> New Holiday
                </a>
                @endif

                   <a href="/admin/holiday/exportpdf/{{ \Request::get('year')? \Request::get('year') : date('Y') }}" class="btn btn-success"  data-placement="top" data-target="#eventModal">
                    <span class="fa fa-file"> &nbsp;</span> Export Pdf
                </a>
                  <a href="/admin/holiday/exportexcel/{{ \Request::get('year')? \Request::get('year') : date('Y') }}" class="btn btn-success"  data-placement="top" data-target="#eventModal">
                    <span class="fa fa-download"> &nbsp;</span> Export Excel
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
                                        <strong><i class="fa fa-calendar"></i> {{ $month }}</strong>
                                    </div>
                                </div>
                                <!-- Table -->
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Event Name</th>
                                            <th class="col-sm-2">Start Date</th>
                                            <th class="col-sm-2">End Date</th>
                                            <th class="col-sm-1">Color</th>
                                            <th class="col-sm-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <?php 
                                                $cal = new \App\Helpers\NepaliCalendar();
                                            ?>
                                        @foreach($holidays as $hk => $hv)
                                            <?php
                                                $m_temp = explode('-', $hv->start_date);
                                            ?>
                                            @if($m_temp[1] == $j)
                                            <?php $count_flag++ ?>   
                                            <tr>
                                                <td>{{ $hv->event_name }}</td>
                                                <td>
                                                    {{ $hv->start_date }}/
                                                    {{$cal->formated_nepali_from_eng_date($hv->start_date)}}
                                                   
                                                </td>
                                                <td>{{ $hv->end_date }}/
                                                 {{$cal->formated_nepali_from_eng_date($hv->end_date)}}</td>
                                                <td>
                                                    <span style="background-color:{{ $hv->color }}" class="color-tag"></span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary btn-xs event_edit" title="Edit" data-placement="top" data-id="{{ $hv->holiday_id }}"><span class="fa fa-edit"></span></button>
                                                    <a href="/admin/holiday_delete/{{ $hv->holiday_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        @if(!$count_flag)
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
                    <h4 class="modal-title" id="eventModalLabel">New Holiday</h4>
                </div>
                <div class="modal-body wrap-modal wrap">
                    <form data-parsley-validate="" novalidate="" action="/admin/holiday" method="post" class="form-horizontal form-groups-bordered" id="new_holiday">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Event Name
                            <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input required="" type="text" name="event_name" class="form-control req" value="" id="event_name" placeholder="Enter Your Event Name" data-parsley-id="4">
                                <small class="form-text text-muted" style="display: none;">Event Name is required.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Description<span class="required"> *</span></label>

                            <div class="col-sm-8">
                            <textarea required="" style="height: 100px" name="description" class="form-control req" id="description" placeholder="Enter Your Description" data-parsley-id="6"></textarea>
                            <small class="form-text text-muted" style="display: none;">Description is required.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Start Date<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input required="" type="text" class="form-control req start_date" name="start_date" id="start_date" value="" data-parsley-id="8">
                                    <small class="form-text text-muted" style="display: none;">Start Date is required.</small>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">End Date<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input required="" type="text" class="form-control req end_date" id="end_date" name="end_date" value="" data-parsley-id="10">
                                    <small class="form-text text-muted" style="display: none;">End Date is required.</small>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Location</label>

                            <div class="col-sm-8">
                                <input type="text" name="location" class="form-control" value="" id="location" placeholder="Enter Your Location" data-parsley-id="12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Type</label>
                             <div class="col-sm-8">
                               <select class="form-control" name='types' id="types"> <!-- Holiday types -->
                                   <option value="internal">Internal</option>
                                   <option value="external">External</option>
                                   <option value="public">Public</option>
                               </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"></label>

                            <div class="color-palet col-sm-8">
                                <span style="background-color:#83c340" class="color-tag clickable active" data-color="#83c340"></span>
                                <span style="background-color:#29c2c2" class="color-tag clickable" data-color="#29c2c2"></span>
                                <span style="background-color:#2d9cdb" class="color-tag clickable" data-color="#2d9cdb"></span>
                                <span style="background-color:#aab7b7" class="color-tag clickable" data-color="#aab7b7"></span>
                                <span style="background-color:#f1c40f" class="color-tag clickable" data-color="#f1c40f"></span>
                                <span style="background-color:#e18a00" class="color-tag clickable" data-color="#e18a00"></span>
                                <span style="background-color:#e74c3c" class="color-tag clickable" data-color="#e74c3c"></span>
                                <span style="background-color:#d43480" class="color-tag clickable" data-color="#d43480"></span>
                                <span style="background-color:#ad159e" class="color-tag clickable" data-color="#ad159e"></span>
                                <span style="background-color:#34495e" class="color-tag clickable" data-color="#34495e"></span>
                                <span style="background-color:#dbadff" class="color-tag clickable" data-color="#dbadff"></span>
                                <span style="background-color:#f05050" class="color-tag clickable" data-color="#f05050"></span>
                                <input id="color" type="hidden" name="color" value="#83c340">
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


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
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
    
    $("#new_holiday").submit(function() {
        var flag = 0;
        $(this).parent().find('.text-muted').css('display', 'none');

        $("#new_holiday .req").each(function(){
            var itemVal = $(this).val();
            if(itemVal == '')
            {
                flag++;
                $(this).parent().find('.text-muted').css('display', 'block');
            }
        })

        if(!flag)
            return true;

        return false;
    });

    $(document).on('click', '.event_edit', function() {
        var holidayId = $(this).attr('data-id');
        $.post("/admin/holiday/"+holidayId,
        {id: holidayId, _token: $('meta[name="csrf-token"]').attr('content')},
        function(data, status){
            $('#eventModal .color-palet span').removeClass('active');
            /*$('.clickable').filter(function () {
                return $(this).attr('data-color') == data.color;
            }).addClass('active');*/

            $('#eventModal .color-palet span[data-color='+data.color+']').addClass('active');
            $('#eventModal #event_name').val(data.event_name);
            $('#eventModal #description').val(data.description);
            $('#eventModal #start_date').val(data.start_date);
            $('#eventModal #end_date').val(data.end_date);
            $('#eventModal #location').val(data.location);
            $('#eventModal #color').val(data.color);
            $('#eventModal #types').val(data.types);
            $('#eventModal form').attr('action', '/admin/holiday_update/'+holidayId);
            $('#eventModal').modal('show');
            is_modal_active = true;
            //alert(data.event_name);
          //alert("Data: " + data + "\nStatus: " + status);
        });
    });

    $('#eventModal').on('hidden.bs.modal', function () {
        $('#eventModal form').attr('action', '/admin/holiday');
        $(this).find('form').trigger('reset');
        is_modal_active = false;
    })


</script>
@endsection
