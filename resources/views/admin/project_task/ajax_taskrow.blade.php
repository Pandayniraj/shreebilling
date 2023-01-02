
<?php
$priority_label = ['Low'=>'info','Medium'=>'primary','High'=>'danger','None'=>'default','Urgent'=>'warning'];
?>

            @if($task->timespan == null)
                <tr id = "{{$task->id}}" draggable="true" ondragstart="drag(event)" class="swapable new{{ $task->id }}">
                  <td><span class="taskid">{!! $task->id !!}</span>.</td>
                  <td><span class="openlink" id="{{$task->id}}" >{!! mb_substr($task->subject,0,300) !!}</span></td>
                  <td>
                    <input class="datepicker_end_date" style="width: 81px;border:none;" 
                    value="{{date('d M y',strtotime($task->end_date))}}">
                  </td>
                  <td>
                    <span class="label label-{{$priority_label[$task->priority]}} taskpriority" data-type="select" data-pk="1" data-title="Select status" data-value="{{$task->priority}}">
                    </span>
                  </td>
                  <td>
                    <div class="progress progress-xs progress-striped active  taskprogress">
                      <div class="progress-bar progress-bar-primary taskprogress-edit" style="width: {!! $task->percent_complete !!}%;color: transparent;">{{$task->percent_complete}}</div>
                    </div>
                  </td>
                  <td><span>
                     @if($task->user->image)
              <img class="profile-user-img img-responsive img-circle" src="/images/profiles/{{$task->user->image}}" alt="{{ $ov->user->first_name}}" style="margin: 0 auto;width: 50px; border: 1px solid #d2d6de;">
              @else
              <span data-letters="{{ mb_substr($task->user->first_name,0,1).' '. mb_substr($task->user->last_name,0,1) }}"></span>
              @endif
                    <!-- <a href="/admin/profile/show/{{ $task->user_id }}">{{ $task->user->first_name}}</a> -->
                  </span></td>
                    <td>
                         <span class="label {{$task->stage->bg_color}} taskstage" data-type="select" data-pk="1" data-title="Select stages" data-value="{{$task->stage_id}}"></span>
                    </td>
                    <td>
                 <span class="taskstatus"  data-type="select" data-title="Select Status" data-value="{{$task->status}}"></span>
               </td>
                </tr>
                @elseif($task->timespan != null)
                @if($task->timespan <= $timespan)

                <tr id = "{{$task->id}}" draggable="true" ondragstart="drag(event)" class="swapable new{{ $task->id }}">
                  <td><span class="taskid">{!! $task->id !!}</span>.</td>
                  <td><span class="openlink" id="{{$task->id}}">{!! mb_substr($task->subject,0,300) !!}</span></td>
                  <td>
                    <input class="datepicker_end_date" style="width: 60px;border:none;" 
                    value="{{date('d M y',strtotime($task->end_date))}}">
                  </td>
                  <td> <span class="label label-{{$priority_label[$task->priority]}} taskpriority" data-type="select" data-pk="1" data-title="Select status" data-value="{{$task->priority}}">{!! $task->priority !!}
                  </span></td>
                  <td>
                    <div class="progress progress-xs progress-striped active taskprogress">
                      <div class="progress-bar progress-bar-primary taskprogress-edit" style="width: {!! $task->percent_complete !!}%">{{$task->percent_complete}}</div>
                    </div>
                  </td>
                  <td><span >
                     @if($task->user->image)
              <img class="profile-user-img img-responsive img-circle" src="/images/profiles/{{$task->user->image}}" alt="{{ $task->user->first_name}}" style="margin: 0 auto;width: 50px; border: 1px solid #d2d6de;">
              @else
              <span data-letters="{{ mb_substr($task->user->first_name,0,1).' '.mb_substr($task->user->last_name,0,1) }}"></span>
              @endif
                    <!-- <a href="/admin/profile/show/{{ $task->user_id }}">{{ $task->user->first_name}}</a> -->
                  </span></td>
                  <td>
                 <span class="label {{$task->stage->bg_color}} taskstage" data-type="select" data-pk="1" data-title="Select stages" data-value="{{$task->stage_id}}"></span>
               </td>
               <td>
                 <span class="taskstatus"  data-type="select" data-title="Select Status" data-value="{{$task->status}}"></span>
               </td>
                </tr>

                @endif
                @endif
