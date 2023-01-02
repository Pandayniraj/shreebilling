@extends('layouts.master')

@section('content')
<link rel="stylesheet" type="text/css" href="/bootstrap-iso.css">

<style type="text/css">
  .blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>

<div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">

                <p class="card-text">

                  
        <span class="lead">  {{ trans('general.text.tagline') }} </span>  <br />
          {{env('APP_COMPANY')}} v{{ App::VERSION() }}
          <p>
            This is a full service homepage.
            Meanwhile, you may <a href="/user/profile">update photo and quick profile</a>.
          </p>
          <img width="" style="max-width: 250px" src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}">

                </p>

             
              </div>
            </div>

            <div class="card card-primary card-outline">
              <div class="card-body">
                @if(!empty($latest_lead_task))
                <h5 class="card-title">My Lead Tasks</h5>
                <ol type="1">
                  @foreach($latest_lead_task as $key => $task)
                  <li>
                <p class="card-text">
                  <a href="/admin/tasks/{{$task->id}}" style="color:black;"> {{ $task->task_subject }}</a>
                  [{{date('dS M Y',strtotime($task->task_start_date))}} - {{date('dS M Y',strtotime($task->task_due_date))}}]
                </p>
              </li>
              @endforeach
              </ol>
              @endif
               @if(!empty($latest_project_task))
              <h5 class="card-title">My Project Tasks</h5>
                <ol type="1">
                  @foreach($latest_project_task as $key => $task)
                  <li>
                <p class="card-text">
                  <a href="/admin/tasks/{{$task->id}}" style="color:black;"> {{ 
                    $task->subject }}</a>
                  [{{date('dS M Y',strtotime($task->start_date))}} - {{date('dS M Y',strtotime($task->end_date))}}]
                </p>
              </li>
              @endforeach
              </ol>
              @endif
              </div>
            </div><!-- /.card -->

          

          </div>
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0"><i class="fa fa-clock"></i> Update your attendance today</h5>
              </div>
              <div class="card-body">
                <h6 class="card-title">Click on a button below to log in and log out</h6>

               
                
          @if($attendance_log && ($attendance_log->attendance_status % 2) != 0)
            <a class="btn btn-success " href="#" onclick="clockout()" style="padding: 0px 10px 0px 10px; margin: 0px;"> 
             <i class="fa fa-clock-o pull-left fa-spin" style="margin-top: 10%;zoom: 1.5;"></i>  
             <span class="pull-right" style="">
              <span style="text-align: left">
                Clock Out<br>
               <small style="color:white" id='livetime'>
                 {{ date('h:i:s A') }}
               </small>
             </span> </span>
            </a>

        <small>
          {{ \Carbon\Carbon::createFromTimeStamp(strtotime($attendance_log
              ->clockinstart()->time))->diffForHumans() }}
        </small>

          @else

            <a class="btn bg-maroon " href="#" onclick="clockin()" style="padding: 0px 10px 0px 10px; margin: 0px;"> 
             <i class="fa fa-clock-o pull-left fa-spin" style="margin-top: 10%;zoom: 1.5;"></i>  
             <span class="pull-right" >
              <span style="margin-left: -12px;">
                Clock In<br>
               <small style="color:white" id='livetime'>
                 {{ date('h:i:s A') }}
               </small>
             </span> </span>
            </a>




          @endif


              </div>
            </div>

        




          </div>
          <!-- /.col-md-6 -->


<hr/>

        </div>
        <!-- /.row -->

      

      

      <div class="row">
        <div class="bootstrap-iso" >
    
          
<div class="col-md-3">
  <div class="card" style="">
  
  <div class="card-body text-white bg-primary">
    <h3 class="card-title"><i class="fa fa-check-square"></i> Sales & Marketing</h3>
    <p class="card-text">A complete sales and marketing package. From sales target to full marketing report.</p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">CRM</li>
    <li class="list-group-item">SMS & Mail Campaigns</li>
    <li class="list-group-item">Proposal & Contracts</li>
    <li class="list-group-item">Quotes and Orders</li>
    <li class="list-group-item">Activities</li>
  </ul>
  <div class="card-body">
    <a href="#" class="card-link">Sales Board</a>
    <a href="#" class="card-link">Marketing Board</a>
  </div>
</div>
</div>

<div class="col-md-3">
  <div class="card" style="">
   
  <div class="card-body text-white bg-success">
   <h3 class="card-title"><i class="fa fa-money"></i> Account & Finance</h3>
    <p class="card-text">Your daily money in and out to the broader finance reporting with chart of accounts and reports</p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">Billing</li>
    <li class="list-group-item">Double Entry Book Keeping</li>
    <li class="list-group-item">Ledgers and Reports</li>
    <li class="list-group-item">Banking & Income</li>
     <li class="list-group-item">Purchase & Expenses</li>
  </ul>
  <div class="card-body">
    <a href="#" class="card-link">Finance Board</a>
    <a href="#" class="card-link">Sales Board</a>
  </div>
</div>
</div>

<div class="col-md-3">
  <div class="card" style="">
  
  <div class="card-body text-white bg-info">
    <h3 class="card-title"><i class="fa fa-users"></i> Human Res.. & Payroll</h3>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">Attendance</li>
    <li class="list-group-item">Employee Directory</li>
    <li class="list-group-item">Leave Management</li>
    <li class="list-group-item">Payroll</li>
    <li class="list-group-item">Recruitment</li>
  </ul>
  <div class="card-body">
    <a href="#" class="card-link">HRM Board</a>
    
  </div>
</div>
</div>


<div class="col-md-3">
  <div class="card" style="">
 
  <div class="card-body text-white bg-danger">
    <h3 class="card-title"><i class="fa fa-bars"></i> Help Desk & Field Service</h3>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">Support Tickets</li>
    <li class="list-group-item">Bugs</li>
    <li class="list-group-item">Location Tracking</li>
    <li class="list-group-item">Just In</li>
    <li class="list-group-item">Support Portal</li>
  </ul>
  <div class="card-body bg-default">
    <a href="#" class="card-link">Tracking Report</a>
    
  </div>
</div>
</div>

      </div>





        </div>

         <div class="row"><br/>
           <div class="bootstrap-iso" >
        

        <div class="col-md-3">
  <div class="card" style="">
 
  <div class="card-body text-white bg-purple">
    <h3 class="card-title"> <i class="fa fa-globe"></i> Office Workflow</h3>
    <p class="card-text">A complete office management system with data and file storage along with tasks</p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">Darta</li>
    <li class="list-group-item">Chalani</li>
    <li class="list-group-item">Docs Management</li>
    <li class="list-group-item">Project Management</li>
    <li class="list-group-item">Notes & Calendar</li>
  </ul>
  <div class="card-body">
    <a href="/admin/projectboard" class="card-link">Project Board</a>

  </div>
</div>
</div>

       <div class="col-md-3">
  <div class="card" style="">
 
  <div class="card-body text-white bg-maroon">
    <h3 class="card-title"><i class="fa fa-check-circle"></i> Communication </h3>
    <p class="card-text">Communicate via various methods. For example chat, calendar, tasks, sms, mail  etc</p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">Calendar Organizer</li>
    <li class="list-group-item">Contact Management</li>
    <li class="list-group-item">Sticky Notes</li>
    <li class="list-group-item">Chat</li>
  </ul>
  <div class="card-body">
    <a href="/admin/talk" class="card-link">Start Talking</a>

  </div>
</div>
</div>


       <div class="col-md-3">
  <div class="card" style="">
 
  <div class="card-header text-white bg-info">
    <h3 class="card-title"><i class="fa fa-cubes"></i> Mobile Apps</h3>
    <p class="card-text">Automate with android and ios apps where applicable for example field service, chat, attendance.</p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">HRM App</li>
    <li class="list-group-item">CRM App</li>
    <li class="list-group-item">Field Service app</li>
  </ul>
  <div class="card-body">
    <a href="#" class="card-link">Meronetwork Profile</a>
    
  </div>
</div>
</div>


       <div class="col-md-3">
  <div class="card" style="">
 
  <div class="card-header text-white bg-olive">
    <h3 class="card-title"><i class="fa fa-truck"></i> Supply Chain</h3>
    <p class="card-text">Automate supply chain process for your industry. Relates all workflows</p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">Products & Inventory</li>
    <li class="list-group-item">Production</li>
    <li class="list-group-item">Location</li>
    <li class="list-group-item">Transportation</li>
    <li class="list-group-item">Returns of goods</li>
  </ul>
  <div class="card-body">
    <a href="#" class="card-link">Inventory Board</a>
  </div>
</div>
</div>





      </div>


    </div>


    


  <script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        });

      });

  </script>

   <script>
    <!-- To submit the note for the Lead - by Ajax -->
    $(document).on('click', '#follow_date', function() {

      var follow_date = $('#follow_date').val();
   //   console.log(credit_amount);

      $.post("/admin/ajax_follow_date",
      { follow_date: follow_date, _token: $('meta[name="csrf-token"]').attr('content')},
      function(data, status){
        if(data.status == '1')
            $("#ajax_amount_type").after("<span style='color:green;' id='status_update'>Credit Amount  is successfully updated.</span>");
        else
            $("#ajax_amount_type").after("<span style='color:red;' id='status_update'>Problem in updating Credit Amount; Please try again.</span>");

        $('#status_update').delay(3000).fadeOut('slow');
        //alert("Data: " + data + "\nStatus: " + status);
      });
      
    });

  </script>


  <script type="text/javascript">


function getLocation() {

    return new Promise((resolve,reject) => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                return resolve(position);
            }, function(err) {
                return reject(err);
            });

        } else {
            return reject(false);
        }
    })

}

function clockin(user_id){
  getLocation().then(response=>{
      let  crd = response.coords;
         let location = JSON.stringify({lat: crd.latitude,long: crd.longitude});
    console.log(user_id,location);
     window.location = `/clockin?location=${location}`;
  }).catch(err=>{
     console.log(err);
      window.location = `/clockin`;
  });
}

function clockout(user_id){
  getLocation().then(response=>{
      let  crd = response.coords;
         let location = JSON.stringify({lat: crd.latitude,long: crd.longitude});
    console.log(user_id,location);
     window.location = `clockout?location=${location}`;
  }).catch(err=>{
     window.location = `/clockout`;
  });
}


var serverTime  =<?php echo strtotime(date('Y-m-d H:i:s'))*1000; ?>;

var expected = serverTime;
var date;
var hours;
var minutes;
var seconds;
var now = performance.now();
var then = now;
var dt = 0;
var nextInterval = interval = 1000; // ms

setTimeout(step, interval);

function formatAMPM(date) {
  let hours = date.getHours();
  let minutes = date.getMinutes();
  let seconds = date.getSeconds();
  let ampm = hours >= 12 ? 'PM' : 'AM';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  hours=hours < 10 ? '0'+hours:hours
  minutes = minutes < 10 ? '0'+minutes : minutes;
  seconds = seconds < 10 ? '0'+seconds : seconds;
  let strTime = hours + ':' + minutes + ':'+seconds + ' ' + ampm;
  return strTime;
}
const timeZone = '{{date_default_timezone_get()}}';
function step() {
    then = now;
    now = performance.now();
    dt = now - then - nextInterval; // the drift
    
    nextInterval = interval - dt;
    serverTime += interval;
    date     = new Date(serverTime);
    date =  new Date(date.toLocaleString('en-US', { timeZone: timeZone }));
    let newtime = formatAMPM(date)
    $('#livetime').text(newtime);
    now = performance.now();

    setTimeout(step, Math.max(0, nextInterval)); // take into account drift
}

</script>


@endsection