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
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-sm-3">
                <form id="change_date" action="/admin/payroll/employee_award" method="get">
                    <label for="field-1" class="control-label pull-left"><strong>Year:</strong></label>
                    <div class="col-sm-8">
                        <input type="text" name="year" id="year" class="form-control years" value="{{ \Request::get('year')? \Request::get('year') : date('Y') }}" data-format="yyyy">
                    </div>
                </form>
            </div>
            <div class="col-sm-9 text-right">
                <a href="" class="btn btn-primary" data-toggle="modal" data-placement="top" data-target="#eventModal">
                    <span class="fa fa-edit">Give Award</span>
                </a>
            </div>
        </div>
        <div class="row ">
            
            <div class="col-md-12">
                <div class="tab-content pl0">
                        <div id="" class="tab-pane  active">
                            <div class="panel panel-custom">
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <strong>Award List</strong>
                                    </div>
                                </div>
                                <!-- Table -->
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="col-sm-1">EMP ID</th>
                                            <th class="col-sm-3">Name</th>
                                            <th class="col-sm-2">Award Name</th>
                                            <th class="col-sm-1">Gift</th>
                                            <th class="col-sm-1">Cash</th>
                                            <th class="col-sm-2">Month</th>
                                            <th class="col-sm-1">Award Date</th>
                                            <th class="col-sm-1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                         ?>
                                        @foreach($employeeawards as $ask => $asv)
                                         
                                            <tr>
                                                <td>{{ $asv->user_id }}</td>
                                                <?php $user = $asv->user; ?>
                                                <td>{{ $user->first_name.' '.$user->last_name }}</td>
                                                <td>{{ $asv->award_name }}</td>
                                                <td>{{ $asv->gift_item }}</td>
                                                <td>{{env('APP_CURRENCY')}} {{ number_format($asv->cash_price ,2)}}</td>
                                                <td>
                                                    {{ date('M y', strtotime($asv->month)) }}
                                                </td>
                                                <td>
                                                    {{ date('dS M y', strtotime($asv->award_date)) }}
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary btn-xs event_edit" title="Edit" data-placement="top" data-id="{{ $asv->award_id }}"><span class="fa fa-edit"></span></button>
                                                    <a href="/admin/payroll/employee_award_delete/{{ $asv->award_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if(count($employeeawards)==0)
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <strong>Nothing to display here!</strong>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

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
                    <h4 class="modal-title" id="eventModalLabel">Give Award</h4>
                </div>
                <div class="modal-body wrap-modal wrap">
                    <form data-parsley-validate="" novalidate="" action="/admin/payroll/employee_award" method="post" class="form-horizontal form-groups-bordered" id="">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Employee
                            <span class="required">*</span></label>
                            <div class="col-sm-8">
                               <select class="form-control select2 product_id" name="user_id" id="user_id" required="required">
                                @if(isset($users))
                                    <option value="">Select Employee</option>
                                    @foreach($users as $key => $pk)
                                    <option value="{{ $pk->id }}"@if($odv->user_id == $pk->id) selected="selected"@endif>{{ $pk->first_name }} {{ $pk->last_name }} ({{$pk->designation->designations}})</option>
                                    @endforeach
                                @endif
                               </select>
                                <small class="form-text text-muted" style="display: none;">Event Name is required.</small>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="approved">
                        <div class="form-group"> 
                            <label for="field-1" class="col-sm-3 control-label">Award Name<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input required="" type="text" class="form-control req " name="award_name" id="award_name" value="" data-parsley-id="8">
                                    <small class="form-text text-muted" style="display: none;">Award name is Required</small>
                                    <div class="input-group-addon">
                                         <a href="#"><i class="fa fa-award"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group"> 
                            <label for="field-1" class="col-sm-3 control-label">Gift Item<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input required="" type="text" class="form-control req " name="gift_item" id="gift_item" value="" data-parsley-id="8">
                                    <small class="form-text text-muted" style="display: none;">Award name is Required</small>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-gifts"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Cash Price<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input required="" type="text" class="form-control req " id="cash_price" name="cash_price" value="" data-parsley-id="10">
                                    <small class="form-text text-muted" style="display: none;">Over time hours is required.</small>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-money-bill-wave"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Select Month<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input required="" type="text" class="form-control req select_month" id="month" name="month" value="" data-parsley-id="10">
                                    <small class="form-text text-muted" style="display: none;">Over time hours is required.</small>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                         </div>
                         <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Award date<span class="">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input required="" type="text" class="form-control req overtime_date" id="award_date" name="award_date" value="" data-parsley-id="10">
                                    <small class="form-text text-muted" style="display: none;">Award date hours is required.</small>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
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
                $('#datetimepicker3').datetimepicker({
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
        $('.select_month').datetimepicker({
            //inline: true,
            format: 'YYYY-MM'
        });

    });


    $(document).on('click', '.event_edit', function() {
        var awardId = $(this).attr('data-id');
        $.post("/admin/payroll/employee_award/"+awardId,
        {id: awardId, _token: $('meta[name="csrf-token"]').attr('content')},

        function(data, status){
            
            $('#eventModal #user_id').val(data.user_id);
            $('#eventModal #award_date').val(data.award_date);
            $('#eventModal #cash_price').val(data.cash_price);
            $('#eventModal #gift_item').val(data.gift_item);
            $('#eventModal #month').val(data.month);
            $('#eventModal #award_name').val(data.award_name);
           
            $('#eventModal form').attr('action', '/admin/payroll/employee_award_update/'+awardId);
            $('#eventModal').modal('show');
            //alert(data.event_name);
          //alert("Data: " + data + "\nStatus: " + status);
        });
    });


    $('#eventModal').on('hidden.bs.modal', function () {
        $('#eventModal form').attr('action', '/admin/payroll/employee_award');
        $(this).find('form').trigger('reset');
    })




</script>

@endsection
