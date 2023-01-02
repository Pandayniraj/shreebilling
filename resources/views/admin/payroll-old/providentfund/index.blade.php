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
                Single Provident Fund
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p> To make a bulk payment please goto <i class="fa fa-play"></i>  Run Payroll </p>

          {{ TaskHelper::topSubMenu('topsubmenu.payroll')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-sm-3">
                <form id="change_date" action="/admin/payroll/advance_salary" method="get">
                    <label for="field-1" class="control-label pull-left"><strong>Year:</strong></label>
                    <div class="col-sm-8">
                        <input type="text" name="year" id="year" class="form-control years" value="{{ \Request::get('year')? \Request::get('year') : date('Y') }}" data-format="yyyy">
                    </div>
                    
                </form>
            </div>
            <div class="col-sm-9">
            <h3 class="text-right">Provident Fund</h3>
            <hr>
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
                                            <th class="col-sm-1">EMP ID</th>
                                            <th class="col-sm-3">Name</th>
                                            <th class="col-sm-2">Payment Date</th>
                                            <th class="col-sm-2">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($advanceSalaries as $ask => $asv)
                                         <?php
                                                $m_temp = explode('-', $asv->payment_month);
                                            ?>
                                            @if($m_temp[1] == $j)
                                            <?php $count_flag++ ?>   
                                             @foreach($asv->deduction as $paymentvalue )
                                             @if($paymentvalue->salary_payment_deduction_label == 'Provident Fund')

                                            <tr>
                                                <td>{{ $asv->user_id }}</td>
                                                <?php $user = $asv->user; ?>
                                                <td>{{ $user->first_name.' '.$user->last_name }}</td>
                                              
                                                <td>{{ $asv->payment_month }}</td>         
                                                
                                                <td>{{ $paymentvalue->salary_payment_deduction_value }}</td>
                                            </tr>
                                               @endif
                                            @endforeach
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
                    <h4 class="modal-title" id="eventModalLabel">Request Advance Salary</h4>
                </div>
                <div class="modal-body wrap-modal wrap">
                    <form data-parsley-validate="" novalidate="" action="/admin/payroll/advance_salary" method="post" class="form-horizontal form-groups-bordered" id="">
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
                        <input type="hidden" name="status" id="status" value="1">
                        <div class="form-group"> 
                            <label for="field-1" class="col-sm-3 control-label">Amount<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input required="" type="text" class="form-control req " name="advance_amount" id="advance_amount" value="" data-parsley-id="8">
                                    <small class="form-text text-muted" style="display: none;">Amount is required.</small>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Deduct Month<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input required="" type="text" class="form-control req deduct_month" id="deduct_month" name="deduct_month" value="" data-parsley-id="10">
                                    <small class="form-text text-muted" style="display: none;">Deduct Month is required.</small>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="approve_by" id="approve_by" value="{{ \Auth::user()->id }}">
                       <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Reason<span class="required"> *</span></label>

                            <div class="col-sm-8">
                            <textarea required="" style="height: 100px" name="reason" class="form-control req" id="reason" placeholder="Enter Your Description" data-parsley-id="6"></textarea>
                            <small class="form-text text-muted" style="display: none;">Reason is required.</small>
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

        $('.start_date, .end_date, .deduct_month').datetimepicker({
            //inline: true,
            format: 'YYYY-MM'
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
        var salaryId = $(this).attr('data-id');
        $.post("/admin/payroll/advance_salary/"+salaryId,
        {id: salaryId, _token: $('meta[name="csrf-token"]').attr('content')},
        function(data, status){

            $('#eventModal #user_id').val(data.user_id);
            $('#eventModal #advance_amount').val(data.advance_amount);
            $('#eventModal #deduct_month').val(data.deduct_month);
            $('#eventModal #reason').val(data.reason);
            $('#eventModal #request_date').val(data.request_date);
            $('#eventModal #status').val(data.status);
    

            $('#eventModal form').attr('action', '/admin/payroll/advance_salary_update/'+salaryId);
            $('#eventModal').modal('show');
            //alert(data.event_name);
          //alert("Data: " + data + "\nStatus: " + status);
        });
    });

    $('#eventModal').on('hidden.bs.modal', function () {
        $('#eventModal form').attr('action', '/admin/payroll/advance_salary');
        $(this).find('form').trigger('reset');
    })


</script>
@endsection
