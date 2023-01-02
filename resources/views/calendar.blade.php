@extends('layouts.master')

@section('head_extra')
<!-- jVectorMap 1.2.2 -->
<link href="{{ asset("/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css" />
<style>
  .filter_date { font-size:14px; }
</style>

<link href="{{ asset("/bower_components/admin-lte/plugins/fullcalendar/fullcalendar.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/plugins/fullcalendar/fullcalendar.print.css") }}" rel="stylesheet" media="print" />

@endsection
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
  <h1>
                Marketing and Operation Calendar
                <small>sync with Google calendar</small>
            </h1>

        

</section>
<p></p>


    <!-- Main content -->

      <div class="row">
        <div class="col-md-3" >
          <div class="box box-solid">
            <div class="box-header with-border">
              <h4 class="box-title">Waiting Task Queues</h4>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-events">
               @foreach($pendingTask as $pt)
                       <div class="external-event" data-id='{{ $pt->id }}' style="background-color: {{ $pt->color ?? '#f56954'}} ;color:white" 
                        title="{{ $pt->lead ? $pt->lead->name :'' }}">
                        {{ $pt->task_subject }}</div>
                    @endforeach
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Create New Task</h3>
            </div>
            <div class="box-body">
              <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                <ul class="fc-color-picker" id="color-chooser">
                  <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                </ul>
              </div>
              <!-- /btn-group -->
              <div class="input-group">

               <input id="new-event" type="text" class="form-control" placeholder="Task Title">

                <div class="input-group-btn">
                  <button  type="button" class="btn btn-primary btn-flat"><i class="fa fa-tasks"></i></button>
                </div>
                <!-- /btn-group -->
              </div><hr>
              <div class="input-group">
                <select id='new-event-lead' class="searchable" style="width: 210px;">
                    <option value="">Select Lead</option>
                    @foreach ($leads as $p)
                        <option value="{{ $p->id }}" @if(isset($selected_lead) && $selected_lead == $p->id) selected @endif>{{ ucfirst($p->name) }}</option>
                    @endforeach
                </select>
               
                <!-- /btn-group -->
              </div><hr>
              <div class="input-group">

               <input id="new-event-date" type="text" class="form-control" placeholder="Task EndDate..." value="{{ \Carbon\Carbon::now()->addDay(1)->format('Y-m-d h:i:s') }}">

                <div class="input-group-btn">
                  <button  type="button" class="btn btn-primary btn-flat"><i class="fa  fa-calendar"></i></button>
                </div>
                <!-- /btn-group -->
              </div><hr>
              <div class="input-group">

                <textarea id='new-event-description' class="form-control" cols='40' placeholder="Task Description"></textarea>
                <!-- /btn-group -->
              </div><hr>
              <div class="input-group">
                  <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Add Tasks</button>
              </div>
              <!-- /input-group -->
            </div>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9" >
          <div class="box box-primary">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->




@endsection


@section('body_bottom')
<link href='/bower_components/fullcalendar/fullcalendar.min.css' rel='stylesheet' />

<script src='/bower_components/fullcalendar/moment.min.js'></script>
<script src='/bower_components/fullcalendar/fullcalendar.min.js'></script>
<script src="/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<script>
  const project_id = `{{ $selected_project ?? '' }}`;
  $(function () {
          $('.searchable').select2();

    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }
function handleChange(task_id, value) {
    $.post("/admin/ajax_task_status", {
            id: task_id,
            update_value: value,
            update_types: 'start_date',
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        function(data) {
            if (data.status == '1') {
              toastr.success('Task SucessFully Updated');
            }
        });
}
function handleDragChange(task_id, start_date,end_date) {
    $.post("/admin/ajax_task_status", {
            id: task_id,
            start_date: start_date,
            end_date: end_date,
            update_types: 'calendar_changes',
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        function(data) {
            if (data.status == '1') {
                toastr.success('Task SucessFully Updated');
            }
        });
}
    function rgb2hex(rgb) {
    var hexDigits = ["0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"];
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    function hex(x) {
      return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
    }
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

    init_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar').fullCalendar({
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week : 'week',
        day  : 'day'
      },
      //Random default events
      events    : <?php echo $allTasks; ?>,
      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')
        var task_id = $(this).attr('data-id')
        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)
        // assign it the date that was reported
        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.id              = task_id
        copiedEventObject.end             = $(this).attr('data-end')
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

        // is the "remove after drop" checkbox checked?
        
          // if so, remove the element from the "Draggable Events" list
        $(this).remove();
        var start_date = new Date(date._d);
        start_date =  `${start_date.getFullYear()}-${start_date.getMonth()+1}-${start_date.getDate()}`;
        console.log(task_id,start_date);

        handleChange(task_id,start_date);
 
      },

      eventDrop: function(info){
        var start_date = info.start || null;
        if(start_date){
          start_date = new Date(start_date._d);
          start_date =  `${start_date.getFullYear()}-${start_date.getMonth()+1}-${start_date.getDate()}`;
        }
        var end_date = info.end || null;
        if(end_date){
          end_date = new Date(end_date._d);
          end_date =  `${end_date.getFullYear()}-${end_date.getMonth()+1}-${end_date.getDate()}`;
        }
        console.log(start_date,end_date);
        handleDragChange(info.id,start_date,end_date)
      },
      dayClick: function(date, jsEvent, view){
        date =  new Date(date._d);
        date =  `${date.getFullYear()}-${date.getMonth()+1}-${date.getDate()}`;
        $("#myModal").modal();
         $('#myModal .modal-body .task_start_date').val(date);
         $('#myModal .task_end_date').val(moment().format('YYYY-MM-DD'));
         $.get(`/getnepdate/${date}`,function(dates){
            $('#myModal .start_dates').text(dates.engdate+'/'+dates.nepdate);
         })

      },
      eventRender: function(info, element,view) {
      if(info?.iscompleted){
        element.find('.fc-title').html('<del>'+info.title+'</del>');

      }
      
      if(info.start_date)
           $(element).attr('title',info.group_name)
      },
       eventClick: function(info,jsEvent) {
      jsEvent.preventDefault(); // don't let the browser navigate

      if (info.url) {

          $('#modal_dialog .modal-content').html('');
        $.get(info.url+'?calandar=true',function(response){
       
            $('#modal_dialog').modal();
            $('#modal_dialog .modal-content').html(response)
        }).fail(function(){
          alert("Failed to open")
        })
          
    
        }
      },
    
      
    });

function addTask(paramObj){
    paramObj['_token'] = $('meta[name="csrf-token"]').attr('content');
    paramObj['isAddedFromCalandar'] = 1;
      $.post("{{ route('admin.tasks.store') }}", paramObj, function(result) {
        console.log(result);
        if (result) {
            var event = $('<div />')
              event.css({
              'background-color': currColor,
              'border-color'    : currColor,
              'color'           : '#fff'
              }).addClass('external-event')
              event.attr('data-id',result.tasks.id)
              event.attr('data-end',paramObj.task_end_date)
              event.html(paramObj.task_subject)
            $('#external-events').prepend(event)
            //Add draggable funtionality
            init_events(event);
            // toastr.success('Task SucessFully Updated');
        }

      });
}

    /* ADDING EVENTS */
    var currColor = 'rgb(0, 86, 179)' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event')
      if (val.val().length == 0) {
        val.focus();
        return
      }
      if($('#new-event-date').val().length == 0){
        $('#new-event-date').focus();
        return 
      }
      var task_due_date =  $('#new-event-date').val();
      var task_description =  $('#new-event-description').val();
      var lead_id = $('#new-event-lead').val();
      val = val.val();
      console.log(currColor);
      let color = rgb2hex(currColor);
      let taskobj = {
        task_subject: val,
        task_due_date: task_due_date,
        color: color,
        task_detail: task_description,
        lead_id: lead_id,
      };
      console.log(taskobj);
      addTask(taskobj);
      //Create events
      //Remove event from text input
      $('#new-event').val('');
      $('#new-event-date').val('');
      $('#new-event-description').val('');
    });

    $('#new-event-date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        sideBySide: true
    });

      $('#projects-id').change(function(){
      let pid = $(this).val();
      location.href = `{{ url('/') }}/admin/project/calendar?project=${pid}`;
      });
     
  });

</script>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Lead Task
           <small class='start_dates'></small>
         </h4>
      </div>
      <form method="post" action="{{ route('admin.tasks.store') }}">
        {{ csrf_field() }}
      <div class="modal-body">
        <div class="form-group">
          <label>Title</label>
            <input type="text" class="form-control title" placeholder="Enter ..." name='task_subject'>
        </div>
       
        <div class="form-group">
         <label>Select Lead</label>
          <select class="form-control searchable-modal" name="lead">
           <option value="">Select Lead</option>
              @foreach ($leads as $p)
                <option value="{{ $p->name }}" @if(isset($selected_lead) && $selected_lead == $p->id) selected @endif>{{ ucfirst($p->name) }}</option>
            @endforeach
          </select>
        </div>
          <input type="hidden"  name='task_start_date' class="task_start_date">
        <div class="form-group">
          <label>End Date</label>
            <input type="text" class="form-control task_end_date" placeholder="Enter ..." name="task_due_date">
        </div>
         <div class="form-group">
          <label>Description</label>
          <textarea placeholder="Enter Description..." class="form-control" name='task_detail'></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left">Close</button>
        <button  type="submit" class="btn btn-primary">Add Task</button>
      </div>
    </form>
    </div>

  </div>
</div>
<script type="text/javascript">
   $('.task_end_date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        sideBySide: true
    });

   $(document).on('hidden.bs.modal', '#myModal' , function(e){
        $('#myModal .task_start_date').val('');    
        $('#myModal .title').val('');    
        $('#myModal .task_end_date').val('');    
        $('#myModal .start_dates').text('')   
   });
   $('.searchable-modal').select2({dropdownParent: $("#myModal"),width: '100%'});

   function HandlePeopleChanges(prams, task_ids, isChanged) { // this function is called from another window
    if (prams) {
        console.log(prams);
        $.post("/admin/ajaxTaskPeopleUpdate", {
                id: task_ids,
                peoples: prams,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            function(data) {
                console.log(data);
                //alert("Data: " + data + "\nStatus: " + status);
            });
    }
    if (isChanged) {
        location.reload();
    }

}
// function customize(){
//   $('.fc-content-skeleton').each(function(){
//       let el = $(this);
//       var foot  = '<tfoot>';
//      $(el).find('thead tr td').each(function(){
//         let date = $(this).data('date');
//         $.get(`/getnepdate/${date}`,function(dates){
//             foot += '<'
//         });
//      });
//   })
// }
</script>


@endsection
