@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title !!}

                <small>Showing Timesheet of {{$time_groups[0]->employee->first_name}} {{$time_groups[0]->employee->last_name}}</small>
            </h1>
            
 </section>

  <div class='row'> 
        <div class='col-md-12'> 
            <!-- Box -->
           
                <div class="box box-primary">
                   <div class="box-body">
                        <div class="table-responsive">
                           
                            <table class="table table-hover table-bordered table-striped" id="employee_timesheet">
                                <thead>
                                    <tr>
                                        <td>
                                        <a class="btn btn-primary btn-xs pull-left" href="/admin/timesheet"> Back</a>
                                    </td>
                                <td colspan="2">
                                <input type="text" id="start_date" class="form-control input-sm datepicker" placeholder="Start Date">
                                </td>
                                <td colspan="3">
                                <input type="text" id='end_date' class="form-control input-sm datepicker" placeholder="End Date"></td>
                                <td>
                                <button class="btn btn-success btn-xs" id='filter'>Filter</button> &nbsp;
                                <button class="btn btn-danger btn-xs" id='clear'>Clear</button></td>
                                    <td colspan="11" style="text-align: right;">
                                       <b>Employee ID: </b>#{{$time_groups[0]->employee_id}}
                                    </td>
                                    </tr>
                                    <tr>

                                        <td colspan="12" style="text-align: right;">
                                            <b>Employee Name: </b>{{$time_groups[0]->employee->first_name}}
                                            {{$time_groups[0]->employee->last_name}}
                                        </td>
                                    </td>
                                    </tr>
                                </thead>
                                   
                              

                                <tbody>
                                    
                                    <tr>
                                        <th style="text-align: center;">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th style="text-align: center">Work Date</th>
                                        <th>Day </th>
                                        <th>DayDesc </th>
                                        <th>Time From</th>
                                        <th>Time To</th>
                                        <th>Total Hours</th>
                                        <th>Activity Code</th>
                                        <th style="text-align: center;">Submitted</th>
                                        <th> Cost</th>
                                         <th>OT</th>
                                        <th >Action</th>
                                    </tr>
                                    <?php
                                      $total_regular_salary = 0;
                                      $total_overtime_salary = 0;

                                    ?>
                                    @foreach($time_groups as $key => $o) 
                                        <tr>
                                            <td align="center">{!! Form::checkbox('chkClient[]', $o->id); !!}</td> 
                                            <?php
                                            $cal = new \App\Helpers\NepaliCalendar();
                                                $exp = explode("-",$o->date);
                                               $converted =$cal->eng_to_nep($exp[0],$exp[1],$exp[2]);$cal = new \App\Helpers\NepaliCalendar();
                                                $exp = explode("-",$o->date);
                                               $converted =$cal->eng_to_nep($exp[0],$exp[1],$exp[2]); 
                                            ?>
                                            <td>{!! $o->date !!} / {{$converted['date']}} {{$converted['nmonth']}}</td>
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
                                            <td>{!! $o->activity->code !!}</td>
                                            <td>{!! $o->date_submitted !!}  </td></td>
                                            <?php
                                            $template = \PayrollHelper::getTimeSheetSalaryDetails($o->employee_id)->template;
                                            $salary = \PayrollHelper::timeSheetSalaryPerDay($template,$total_time);
                                            $total_regular_salary += $salary['regular_salary'];
                                            $total_overtime_salary += $salary['overtime_salary'];
                                            ?>
                                            <td> {{env('APP_CURRENCY')}} {{$salary['regular_salary']}}</td>
                                            <td> {{env('APP_CURRENCY')}} {{$salary['overtime_salary']}}</td>
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

                                    <tr>
                                    <td  colspan="4"></td>
                                    <td><strong>Total</strong></td>
                                    <td><strong>{{  round($total_user_time / 60 / 60,0) }} hrs</strong></td>
                                    <td colspan="3"></td>
                                    <td  >
                                        <strong>{{env('APP_CURRENCY')}} {{ $total_regular_salary }}</strong>
                                    </td>
                                    <td  >
                                        <strong>{{env('APP_CURRENCY')}} {{  $total_overtime_salary }}</strong>
                                    </td>
                                   </tr>
                                   <!--  <tr>
                                    <td  colspan="4"></td>
                                    <td><strong>Total Cost</strong></td>
                                    <td><strong>{{env('APP_CURRENCY')}} {{  round($total_user_time / 60 / 60,0) }}</strong></td>
                                    <td  colspan="5"></td>
                                   </tr> -->

                  
                                </tbody>

                            </table>
                         
                        </div> <!-- table-responsive -->
                    </div><!-- /.box-body -->
                 </div><!-- /.box -->

       </div>

    </div><!-- /.row -->
<script type="text/javascript">
     $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        }); 
    $('#employee_timesheet #filter').click(function(){
       let start_date = $('#employee_timesheet #start_date').val();
       let end_date = $('#employee_timesheet #end_date').val();
       window.location.href = `{!! url('/') !!}/admin/timesheet/{{\Request::segment(3)}}/show?start_date=${start_date}&end_date=${end_date}`;
    });
    $('#clear').click(function() {
        window.location.href = "{!! url('/') !!}/admin/timesheet/{{\Request::segment(3)}}/show";
    });
</script>
@endsection

