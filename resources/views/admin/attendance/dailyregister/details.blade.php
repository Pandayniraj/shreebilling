

<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <strong>Attendance List of {{ date('d F-Y', strtotime($start_date)) }} to 
                        {{ date('d F-Y', strtotime($end_date)) }}  </strong>
                        <div class="pull-right hidden-print">
                            <a href="javascript::void()" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="excel"  id='DownloadExcel'><span><i class="fa fa-file-pdf-o"></i></span></a>

                            
                  
                        </div>
                    </h3>
                </div>
               
                <div class="table-responsive">
                <table id="" class="table table-bordered std_table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Date In</th>
                            <th>Clock In Time</th>
                            <th style="white-space: nowrap;"class="text-center">In Location</th>
                            <th>Date Out</th>
                            <th>Clock Out Time</th>
                            <th class="text-center">Out Location</th>
                            <th>IP address</th>
                            <th>Hours</th>
                            <th class="hidden-print">Action</th> 
                        </tr>
                    </thead>
               
                 <tbody> 
                        @foreach($history as $hk => $rec)
                       
                        <tr  class="@if( ($loop->index +1) % 2 == 0) bg-primary @else bg-olive @endif"><th colspan='10'>
                            Date: {{date('dS M Y',strtotime($hk))}}</th></tr>
                        @foreach($usersLists as $k => $us)
                        <?php
                           $records = $rec->where('user_id',$us->id);
                           $rec_user = $records->sortBy('clock_id')->take(2);
                            $rec_user= $rec_user->values();
                        ?>
                        @if(count($records) == 0)
                            <tr class="bg-warning">
                            <td>
                                {{$us->first_name}}
                                {{$us->first_name}}
                            </td>
                            @for($j=0;$j<9;$j++)
                            <td class="text-center">-</td>
                            @endfor
                        </tr>
                        @else
                        <?php $key = 0; ?>
                        @foreach($rec_user as $key => $hv)

                        <tr @if($key == 1)class="bg-danger"@endif>

                            @if($key == 0 && count($rec_user) >= 2)

                             <td rowspan='2'style="white-space: nowrap;vertical-align: middle;">{{$hv->user->first_name}} {{$hv->user->last_name}}</td>
                            <td rowspan='2' style="white-space: nowrap;vertical-align: middle;"> 
                                {{ $hv->date_in }} 
                            </td>

                            @elseif($key == 0)

                            <td style="white-space: nowrap;vertical-align: middle;">
                                {{$hv->user->first_name}} {{$hv->user->last_name}}
                            </td>
                            <td style="white-space: nowrap;vertical-align: middle;"> 
                                {{ $hv->date_in }} 
                            </td>
                            @endif
                            <td>
                                
                                {{ date('h:i a', strtotime($hv->clockin_time)) }}
                                ({{$hv->in_device}})
                            </td>
                            @if($hv->inLoc)
                                <?php 
                                 $inLoc = json_decode($hv->inLoc);
                                ?>
                                @endif
                            <td title="{{$inLoc->formatted_address}}">
                                {{$inLoc->street_name}}
                            </td>
                             <td style="white-space: nowrap;"> {{ $hv->date_out }}   </td>
                            <td>
                                @if($hv->clockout_time)
                                    {{ date('h:i a', strtotime($hv->clockout_time)) }}
                                     ({{$hv->out_device}})
                                @endif
                            </td>
                             @if($hv->outLoc)
                                <?php 
                                 $outLoc = json_decode($hv->outLoc);
                                ?>
                                @endif
                             <td title="{{$outLoc->formatted_address}}">
                               {{$outLoc->street_name}}
                            </td>
                            <td>{{ $hv->ip_address }}</td>
                            <?php
                                $startTime = \Carbon\Carbon::parse(date('Y-m-d H:i:s', strtotime($hv->date_in.' '.$hv->clockin_time)));
                                $finishTime = \Carbon\Carbon::parse(date('Y-m-d H:i:s', strtotime($hv->date_out.' '.$hv->clockout_time)));

                                $totalDuration = $finishTime->diffInSeconds($startTime);
                                if(gmdate('d', $totalDuration) != '01')
                                    $hour = ((gmdate('d', $totalDuration) - 1) * 24) + gmdate('H', $totalDuration);
                                else
                                    $hour = gmdate('H', $totalDuration);

                                $minute = gmdate('i', $totalDuration);

                            ?>
                            <td>{{ $hour.':'.$minute.' m' }}</td>
                            <td class="hidden-print"><a href="/admin/time_history/edit_time/{{ $hv->clock_id }}" class="btn btn-primary btn-xs" title="Edit" data-toggle="modal" data-target="#modal_dialog"><span class="fa fa-edit" ></span></a></td>
                        </tr>


                        @endforeach
                        @endif
                        @endforeach
                        @endforeach
                        
                </tbody>
                 </table>

            </div>
        </div>
    </div>
</div>
</div>

