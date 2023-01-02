@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title !!}

                <small>{!! $page_description !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

  <div class='row'> 
        <div class='col-md-12'> 
            <!-- Box -->
          
                <div class="box box-primary">
                    <div class="box-header with-border">
                        &nbsp;
                        <a class="btn btn-primary btn-xs" href="{!! route('admin.timesheet.create') !!}" title="Create Activity">
                            <i class="fa fa-plus"></i> Add Timesheet
                        </a>
                        <a class="btn btn-primary btn-xs" href="{!! route('admin.bulkadd.timesheet') !!}" title="Add many timesheet at once">
                            <i class="fa fa-plus"></i> Bulk Add Timesheet
                        </a>

                        <a class="btn btn-primary btn-xs" href="{!! route('admin.timesheet.attendancereport') !!}" title="Report">
                            <i class="fa fa-clock-o"></i> Timesheet Attendance Report
                        </a>
                             <div class="col-md-4 col-sm-4 col-lg-4" style="float: right;margin-top: 4px">  
                                  
                        <div class="input-group">
                            <input type="text" class="form-control input-sm" placeholder="Search timesheet by username" name="search" id="terms" value="{{\Request::get('term')}}">

                        <div class="input-group-btn">
                        <button type="button" class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i>
                            &nbsp;Filter
                        </button>
                        </div>
                        <div class="input-group-btn">
                        <button type="button" class="btn btn-danger btn-sm" id="clear"><i class="fa fa-close (alias)"></i>
                            &nbsp; 
                        Clear</button>
                        </div>
                    </div>
                    </div>
                    </div>

                   <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped" id="orders-table">

                                <thead>
                                    <tr>
                                        <th style="text-align: center;">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>id</th>
                                        <th>Employee Name</th>
                                        <th style="text-align: center;">Work Date</th>
                                        <th>Day </th>
                                        <th>DayDesc </th>
                                        <th>Time From</th>
                                        <th>Time To</th>
                                        <th>Total Hrs</th>
                                        <th>Activity Code</th>
                                        <th style="text-align: center">Submitted</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($data as $dat)
                                     <?php
                                       $slice_num = 5;
                                       $total = count($dat);
                                       $dat = $dat->slice(-$slice_num);
                                    ?>
                                   <tr>
                                    <td ></td>
                                    <td style="text-transform: capitalize;"><strong><i class="fa fa-user"></i> {{$dat[0]->employee->username}} #{{$dat[0]->employee->id}} </strong></td>
                                    <td colspan="9" style="text-align: center">
                                    @if($total > 5)<b> Showing latest  {{$slice_num}} out of {{$total}}</b>
                                    @else
                                    <b>Showing latest {{$total}} out of   {{$total}}</b>
                                    @endif 
                                    </td>
                                    <td colspan="3">
                                        <a href="/admin/timesheet/{{$dat[0]->employee_id}}/show" class="btn btn-primary btn-xs" target="blank"><i class="fa fa-tasks"></i>&nbsp;&nbsp;Show All</a>
                                    </td>
                                   </tr>
                                  
                                
                                    @foreach($dat as $key => $o) 
                                        <tr>
                                            <td align="center">{!! Form::checkbox('chkClient[]', $o->id); !!}</td> 
                                            <td>{!! $o->id !!}</td>
                                            <td style="text-transform: capitalize;">{!! $o->employee->username !!}</td>

                                            <?php
                                            $cal = new \App\Helpers\NepaliCalendar();
                                               $exp = explode("-",$o->date);
                                               $converted =$cal->eng_to_nep($exp[0],$exp[1],$exp[2]);

                                            ?>
                                            <td>{!! $o->date !!}/ {{$converted['date']}} {{$converted['nmonth']}}</td>
                                            <td> {{ date("D", strtotime($o->date)) }} </td>
                                            <td>
                                                <?php
                                                //$dt = \Carbon\Carbon::parse($o->date);
                                                
                                                if(date('N', strtotime($o->date)) == 6){
                                                    echo '<span class="label label-info">WEEKEND</span>';
                                                }else{
                                                    echo 'WEEKDAY';
                                                }
                                                //$dt->isWeekend();
                                                ?>

                                            </td>
                                            <td>{!! $o->time_from !!}</td>
                                            <td>{!! $o->time_to !!}</td> 
                                            <?php
                                              $total_time = \TaskHelper::GetTimeDifference($o->time_from,$o->time_to);
                                               $total_user_time = $total_user_time + $total_time;
                                               
                                            
                                            ?>
                                            <td>{!! gmdate("H:i", $total_time) !!} hrs</td>
                                            <td>{!! mb_substr($o->activity->code,0,10) !!}</td>
                                            <td>{!! $o->date_submitted !!} </td>
                                            <td>
                                                @if( $o->isEditable() )
                                                    <a href="/admin/timesheet/{{$o->id}}/edit" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a> 
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/orders/general.error.cant-edit-this-document') }}"></i>
                                                @endif

                                                @if($o->isDeletable())
                                                    <a href="{!! route('admin.timesheet.confirm-delete', $o->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/orders/general.error.cant-delete-this-document') }}"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                   <!--  <tr>
                                    <td  colspan="5"></td>
                                    <td><strong>Total</strong></td>
                                    <td><strong>{{  round($total_user_time / 60 / 60,0) }} hrs</strong></td>
                                    <td  colspan="5"></td>
                                   </tr> -->

                                    @endforeach
                                </tbody>

                            </table>
                            <div align="center">  {!! $emp->appends(Request::except('page'))->render() !!}</div>
                        </div> <!-- table-responsive -->
                    </div><!-- /.box-body -->
                 </div><!-- /.box -->

            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->
<script type="text/javascript">
    $('#search').click(function() {
    let terms = $('#terms').val();
    window.location.href = "{!! url('/') !!}/admin/timesheet?term=" + terms;
});
$(document).ready(function() {
    $(window).keydown(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            let terms = $('#terms').val();
            window.location.href = "{!! url('/') !!}/admin/timesheet?term=" + terms;
            return false;
        }
    });
});
$('#clear').click(function() {
    window.location.href = "{!! url('/') !!}/admin/timesheet";
});
</script>
@endsection

