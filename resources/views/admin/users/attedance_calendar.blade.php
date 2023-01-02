@extends('layouts.master')

@section('head_extra')
<!-- jVectorMap 1.2.2 -->
<link href="{{ asset("/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css" />
<style>
  .filter_date { font-size:14px; }
  .fc-prev-button, .fc-next-button{
    display: none;
  }
  .fc-day-header.fc-sun{
    background: red !important;
    color: white;
  }
  .fc-day-header.fc-sat{
    background: red !important;
    color: white;
  }
</style>

<link href="{{ asset("/bower_components/admin-lte/plugins/fullcalendar/fullcalendar.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/plugins/fullcalendar/fullcalendar.print.css") }}" rel="stylesheet" media="print" />

@endsection
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
  <h1>
  Attendance Calendar for {{ $user->first_name }} {{ $user->last_name }}
  <small></small>
  </h1>

        <div class="wrap" style="margin-top:5px;">
                        <form method="get" action="{{ $form_action }}">
                    <div class="filter form-inline" style="margin:0 30px 0 0;">
                        {!! Form::text('date_range', \Request::get('date_range'), ['style' => 'width:120px;', 'class' => 'form-control', 'id'=>'date_range', 'placeholder'=>'Date','autocomplete' =>'off']) !!}&nbsp;&nbsp;
                     

                      

                        <input type="hidden" name="search" value="true">
                        <input type="hidden" name="type" value={{ Request::get('type') }}>
                        <button class="btn btn-primary" id="btn-submit-filter" type="submit">
                            <i class="fa fa-list"></i> Filter
                        </button>
                        <a href="/admin/hrcalandar" class="btn btn-danger" id="btn-filter-clear" >
                            <i class="fa fa-close"></i> Clear
                        </a>
                    </div>
                    </form>
                </div>

</section>
<p></p>

    <!-- Main content -->

      <div class="row">
        <div class="col-md-12" >
          <div class="box">
            
            <div class="box-body no-padding">
             
                  
              <div id="calendar"></div>
                 
                   
             
            </div>
          </div>
        </div>
      </div>
     





@endsection


@section('body_bottom')
<link href='/bower_components/fullcalendar/fullcalendar.min.css' rel='stylesheet' />
<link href='/bower_components/fullcalendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script src='/bower_components/fullcalendar/moment.min.js'></script>
<script src='/bower_components/fullcalendar/fullcalendar.min.js'></script>
<script src="/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script src="https://unpkg.com/tooltip.js@1.3.3/dist/umd/tooltip.min.js"></script>

<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
 <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
    <script src='/nepali-date-picker/date-conveter.js'></script>
<script>
const adbs = myExtDateConvetorFunction();
    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)

    function init_calandar(el,eventArr){

      $(`#${el}`).fullCalendar({

        monthNames: [
            'January (Poush/Magh)',
            'February (Magh/Falgun)',
            'March (Falgun/Chaitra)',
            'April (Chaitra/Baisakh)',
            'May (Baisakh/Jestha)',
            'June (Jestha/Asaadh)',
            'July (Asaadh/Shrawan)',
            'August (Shrawan/Bhadra)',
            'September (Bhadra/Ashwin)',
            'October (Ashwin/Kartik)',
            'November (Kartik/Mangsir)',
            'December (Mangsir/Poush)'
        ],
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
          events    : eventArr,
          editable  : true,
          droppable : true, // this allows things to be dropped onto the calendar !!!
     
         defaultDate: "{{ $start_date }}",
       
           eventClick: function(info,jsEvent) {
              jsEvent.preventDefault(); // don't let the browser navigate

          },
          eventRender: function(info, element,view) {
            element.find('.fc-title').html(info.title);
          },

        dayRender: function (date, cell) {

          var translate_date = new Date(date.toISOString());

          var day = translate_date.getDay();
          if(day == 0 || day == 6){
             cell[0].style.color ='red';
          }
         
          
          translate_date=     translate_date.getFullYear() + '/' +(translate_date.getMonth()+1) + '/' + translate_date.getDate();
         
            let convert =  adbs.ad2bs(translate_date);

            let nepdate = convert.ne.strMonth + ' ' + convert.ne.day;
              cell.html("<span class='p-xs text-xxs' style='font-size: 11px;padding: 5px;'>" + nepdate + "</span>");
          },
        
          
        });

    }

    init_calandar('calendar',<?php echo json_encode($hrData) ?>);


   $(function() {
        $('#date_range').datetimepicker({
                 format: 'YYYY-MM'
            , sideBySide: true
            , allowInputToggle: true,

               
            });
    
        }); 

</script>



@endsection
