@extends('layouts.master')

@section('content')

<style type="text/css">
.news_posts{

 font-size: 14px;
 line-height: 22px;
color: #050505;

margin-top: 8px;
margin-bottom: 10px;
/*white-space: pre-wrap;*/
 word-spacing: 1px;
}

a{
  color: black;
}

.sSubNav {
    background: #3470aa;
    background-image: url('/images/nav-bg2.jpg');
    background-position: 88% 99%;
    background-repeat: no-repeat;
    background-size: cover;
    color: white;
}
.nav-tabs-custom {
    border-radius: 12px;
    margin-bottom: 15px;
    background: #fff;
    box-shadow: none;
}
.nav-tabs-custom>.tab-content {
    box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;
    border-radius: 12px;
}

.Sharing-section {
    width: 100%;
    margin-left: -61px;
    border-radius: 28px !important;
    line-height: 13px;
    padding-top: 17px;
    padding-left: 22px;
}
.panel-success {
    border-color: transparent;
}
.btn-section-top {
    text-align: center;
    margin-top: 56px;
    border-top: 1px solid #ddd;
    width: 97%;
    margin-left: 12px;
}
.btn-section-top button {
    margin-left: 13px;
}
.img-section img {
    width: 43%;
    border-radius: 50%;
        margin-left: 17px;
}
.box {
    border-radius: 12px;
    }
  
.panel-default {
    border-color: transparent;
    border-radius: 12px !important;
    box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;
}
.mar10 {
    margin-top: 12px;
}
element.style {
}
.form-control:not(select) {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
select[multiple].input-sm, textarea.input-sm {
    height: auto;
}
textarea.form-control {
    height: auto;
}
.form-control {
    border-radius: 28px !important;
    box-shadow: none;
   
}
.mar11{
  margin-left: -61px;
    height: 37px;
    width: 61px;
    margin-top: 5px;
}
.section2 {
    margin-left: -176px;
}
@media (min-width: 769px) and (max-width: 1200px) {
    .img-section img {
    width: 9%;
    border-radius: 50%;
    margin-left: 0px;
}
.Sharing-section {
    width: 100%;
    margin-left: 0px;
  }
  .Sharing-section {
    width: 75%;
}
.txt-area-section {
    margin-top: -42px;
    margin-left: 47px;
}
.mar11 {
    margin-left: 0px;
    height: 37px;
    width: 61px;
    margin-top: -41px;
    float: right;
    margin-right: 22px;
}
.section2 {
    margin-left: 0;
}
.btn-section-top {
    text-align: center;
    margin-top: 9px;
    border-top: 1px solid #ddd;
    width: 94%;
    margin-left: 15px;
}
    }

 @media (min-width: 481px) and (max-width: 768px) {
     .img-section img {
    width: 9%;
    border-radius: 50%;
    margin-left: 0px;
}
.Sharing-section {
    width: 100%;
    margin-left: 0px;
  }
  .Sharing-section {
    width: 75%;
}
.txt-area-section {
    margin-top: -42px;
    margin-left: 47px;
}
.mar11 {
    margin-left: 0px;
    height: 37px;
    width: 61px;
    margin-top: -41px;
    float: right;
    margin-right: 22px;
}
.section2 {
    margin-left: 0;
}
.btn-section-top {
    text-align: center;
    margin-top: 9px;
    border-top: 1px solid #ddd;
    width: 94%;
    margin-left: 15px;
    font-size: 11px;
}
textarea.form-control {
    height: auto;
    width: 72%;
}
} 
@media (min-width: 320px) and (max-width: 480px) {
     .img-section img {
    width: 9%;
    border-radius: 50%;
    margin-left: 0px;
}
.Sharing-section {
    width: 100%;
    margin-left: 0px;
  }
  .Sharing-section {
    width: 75%;
}
.txt-area-section {
    margin-top: -42px;
    margin-left: 47px;
}
.mar11 {
    margin-left: 0px;
    height: 37px;
    width: 61px;
    margin-top: -41px;
    float: right;
    margin-right: 22px;
}
.section2 {
    margin-left: 0;
}
.btn-section-top {
    text-align: center;
    margin-top: 9px;
    border-top: 1px solid #ddd;
    width: 94%;
    margin-left: 15px;
}
}
</style>



<div class="row sSubNav" style="background-color: #ffffff ;padding: 10px; margin-top: -35px">
<section class="">

  <div class="col-sm-6">
      <span style="line-height: 20px;font-weight: bold; font-size: 16px;padding-left: 29px;color: #000000">

        {!! $greetings !!},&nbsp;{{ Auth::user()->first_name }}!
      </span>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a class="btn btn-default btn-xs hidden-xs hidden-sm" href="/admin/leave_management">
          Apply leave
        </a>
        <a class="btn btn-default btn-xs hidden-xs hidden-sm" href="/admin/mytimehistory">
          Adjust Time
        </a>

<br/>
</div> <div class="col-sm-6">

<span style="padding-top-top: 20px; margin-right: 80px" class="pull-right">
   @if($attendance_log && ($attendance_log->attendance_status % 2) != 0)
        <a class="btn bg-olive " href="#" onclick="clockout()" style="padding: 4px 10px 5px 10px; margin: 0px;">

         <span class="material-icons">timer</span>
         <span class="pull-right" style="">
           <span class="pull-right" >
            <span style="margin: 5px;">Clock Out</span>

         </span> </span>
        </a>

    <small>
      {{ \Carbon\Carbon::createFromTimeStamp(strtotime($attendance_log
          ->clockinstart()->time))->diffForHumans() }}
    </small>

      @else

        <a class="btn bg-maroon " href="#" onclick="clockin()" style="padding: 4px 10px 5px 10px; margin: 0px;">
         <i class="fa fa-clock-o pull-left" style="margin-top: ;font-size: 20px;padding-right: 5px;"></i>
         <span class="pull-right" >
            <span style="margin-top: 5px;">Clock In</span>
        </span>
        </a>
</span>

</div>

      @endif

</span>

</section></div>

<section class="content">

<div class="row">
  <div class="col-md-8">


     <form method="POST"  action="{{ route('admin.news_feeds.create') }}" enctype='multipart/form-data'>

      @csrf

     <div class="panel panel-success" style="height: 110px;">

        <div class="panel-body" style="padding: 10px; margin: 0">
          <div class="row">
            <div class="col-md-2 img-section">
            <img src="/images/profiles/156582604018198560_10155327046399637_4588502365326031497_n.jpg">
          </div>
          <div class="col-md-7 txt-area-section">
            <textarea class="form-control Sharing-section" rows="1" placeholder="Share Something..." name="body" style="height: 45px;"></textarea>

          </div>
          <div class="col-md-2"> 
            <button class="btn btn-primary btn-sm mar11">Post</button>
          </div>

          <div class="btn-section-top">
            <div class="section2">
             <a type="button" data-toggle="tooltip"  data-original-title="3 New Messages" title="Message Settings" id='flip' ><i class="fa  fa-cog mar10"></i></a>
        <label data-toggle="tooltip"  data-original-title="3 New Messages" title="Upload Photos" type="button"  style="margin-left: 13px;" for='attachment' title="Send Attachment" class="mar10"><span class="material-icons">
        add_photo_alternate
        </span>

          <input type="file" name="attachment[]" style="display: none;" id='attachment' multiple accept="image/*">
          <span id='no_of_file_choosen'></span>
        </label>

        &nbsp;&nbsp;
        <a data-toggle="modal" data-target="#modal-default">
               <span style="color:red" class="material-icons mar10">post_add</span>  Lead
              </a>

              &nbsp;&nbsp;



         <a data-toggle="modal" data-target="#modal-default2" class="mar10">
               <span class="material-icons mar10">task</span>  Private Task
              </a>


        &nbsp;&nbsp;



        <a  data-toggle="modal" data-target="#modal-default3" class="mar10">
               <span class="material-icons mar10">add_task</span>  Project Task
              </a>

              &nbsp;&nbsp;

            <a  class="" data-toggle="modal" data-target="#modal-default4">
               <span style="color: green; font-size: 18px" class="material-icons mar10">confirmation_number</span>  Ticket
              </a>


       &nbsp;&nbsp;



        <i class="fa fa-close remove_icons mar10" title="remove" onclick="removeAllFiles(this)" style="cursor: pointer;display: none;"></i>


          <!-- <button class="btn btn-primary btn-xs">Post</button> -->
</div>
          </div>
          </div>
        </div>
       <!--  <div class="panel-footer" style="padding: 5px 5px">
         <div id='post_option' style="display: none;">
           <table class="table">
             <td class="col-sm-6">
              <b> Scheduling </b>
              <p>You can dictate the time your post appears on the notice board by entering a datetime below. Only future dates are allowed.</p>
              <input type="text" name="schedule" class="form-control datepicker input-sm" placeholder="Schedule date">
             </td>
             <td class="col-sm-6">
              <b> Post Type </b>
              <p>Post type allows you to categorize posts by their scope.<br/>
                <select name="view_level" class="form-control input-sm" style="width: 100px;background: #007BFF; color: white">
                  <option value="normal">Normal</option>
                  <option value="dept">Department</option>
                </select>
              </p>
             </td>

           </table>
         </div>
       

        </div> -->
     </div>
     </form>
      <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
              <form method="POST"  action="{{route('admin.leads.store')}}">
                  @csrf
                  <input type="hidden" name="type" value="leads">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Posting  New Lead</h4>
                      </div>
                      <div class="modal-body">

                                  <div class="form-group">
                                      <label>Name</label>
                                      <input type="text" name="name" class="form-control" placeholder="Name">
                                  </div>
                          <div class="form-group">
                                      <label>Phone</label>
                                      <input type="text" name="mob_phone" class="form-control" placeholder="Phone">
                                  </div>



                                  <div class="form-group">
                                      <label>@</label>
                                      <input type="text" name="email" class="form-control" placeholder="Email">
                                  </div>



                                  <div class="form-group">
                                      <label>Desc</label>
                                      <input type="text" name="description" class="form-control" placeholder="Description">
                                  </div>




                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save changes</button>
                      </div>
                  </div>
              </form>
              <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <div class="modal fade" id="modal-default2">
          <div class="modal-dialog">
              <form method="POST"  action="{{route('admin.tasks.store')}}">

                  @csrf
                  <input type="hidden" name="type" value="task">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">New Private Task</h4>
                      </div>

                          <div class="modal-body">
                              <div class="form-group">
                                  <label>Title</label>
                                  <input type="text" class="form-control title" placeholder="Enter ..." name="task_subject">
                              </div>

                              <div class="form-group">
                                  <label>Select Lead</label>
                                  <select class="form-control searchable-modal select2" name="lead_id">
                                      <option disabled  selected >Select lead</option>
                                      @if(isset($leads))
                                      @foreach($leads as $lead)
                                          <option value="{{$lead->id}}">{{$lead->name}}</option>
                                      @endforeach
                                      @endif
                                  </select>
                              </div>
                              <input type="hidden" name="task_start_date" class="task_start_date" value="{{date('Y-m-d')}}">
                              <div class="form-group">
                                  <label>End Date</label>
                                  <input type="text" class="form-control datepicker" placeholder="Enter ..." name="task_due_date">
                              </div>
                              <div class="form-group">
                                  <label>Description</label>
                                  <textarea placeholder="Enter Description..." class="form-control" name="task_detail"></textarea>
                              </div>
                          </div>


                      <div class="modal-footer">
                          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save changes</button>
                      </div>
                  </div>
                  <!-- /.modal-content -->
              </form>

          </div>
          <!-- /.modal-dialog -->
      </div>
      <div class="modal fade" id="modal-default3">
          <div class="modal-dialog">

              <form action="/admin/project_tasks/store/modals" method="POST">
                  @csrf
                  <input type="hidden" name="type" value="ptask">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">New Project Task</h4>
                      </div>
                      <div class="modal-body">

                          <div class="form-group">
                              <label>Subject</label>
                              <input type="text" class="form-control" placeholder="Type Task Subject" name="subject" required>
                          </div>

                          <div class="form-group">
                              <label>Select Project</label>
                              <select name="project_id" id="" class="form-control input-sm">
                                  <option value="">Select Project</option>
                                  @if(isset($projects))
                                  @foreach($projects as $project)
                                      <option value="{{$project->id}}">{{$project->name}}</option>
                                  @endforeach
                                  @endif
                              </select>
                          </div>

                          <div class="form-group">
                              <label>Start Date</label>
                              <input type="text" class=" datepicker form-control input-sm required" placeholder="Start Date" value="{{date('Y-m-d')}}" name="start_date">
                          </div>
                          <div class="form-group">
                              <label>End Date</label>
                              <input type="text" class=" datepicker form-control input-sm required" placeholder="End Date" value="{{\Carbon\Carbon::now()->addDay(1)->format('Y-m-d')}}" name="end_date">
                          </div>
                          <div class="form-group">
                              <label>People</label>
                              <select name="peoples" id=""  class="form-control">
                                @if(isset($users))
                                  @foreach($users as $user)
                              
                                  <option value="{{$user->first_name}}" >
                                      {{$user->full_name}}

                                  </option>
                                 
                                  @endforeach
                                  @endif
                              </select>
                          </div>



                          <div class="clearfix"></div>

                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save changes</button>
                      </div>
                  </div>
                  <!-- /.modal-content -->
              </form>

          </div>
          <!-- /.modal-dialog -->
      </div>

      <div class="modal fade" id="modal-default4">
          <div class="modal-dialog">

              <form action="{{route('admin.ticket.store')}}" method="POST">
                  @csrf
                  <input type="hidden" name="type" value="ticket">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">New Ticket</h4>
                      </div>
                      <div class="modal-body">

                          <div class="form-group">
                              <label class="control-label">Source</label>
                              <select class="form-control input-sm" name="source">
                                  <option value="phone">Phone</option><option value="email">Email</option>
                                  <option value="others">Others</option></select>
                          </div>
                          <div class="form-group">
                              <label class="control-label" for="pwd">Issue Summary</label>

                              <input type="text" name="issue_summary" placeholder="Enter Issue Summary" class="form-control input-sm">

                          </div>
                          <div class="form-group">
                              <label class="control-label" for="pwd">Due Date</label>

                              <input type="text" name="due_date" placeholder="Select Due date" class="form-control input-sm datepicker">

                          </div>
                          <div class="form-group">
                              <label class="control-label" for="pwd">Work For</label>
                              <select class="form-control input-sm" name="assign_to" >
                                  <option selected="selected" value="">Select Users</option>
                                  @if(isset($users))
                                  @foreach($users as $user)
                                      <option value="{{$user->id}}" >
                                          {{$user->full_name}}

                                      </option>
                                  @endforeach
                                  @endif
                              </select>
                          </div>
                          <div class="form-group">
                              <label class="control-label" for="pwd">Detail Reason</label>

                              <textarea type="text" name="detail_reason" placeholder="Select Due date" class="form-control input-sm"></textarea>

                          </div>

                          <div class="clearfix"></div>

                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save changes</button>
                      </div>
                  </div>
                  <!-- /.modal-content -->
              </form>

          </div>
          <!-- /.modal-dialog -->
      </div>

    <div id='newsfeedsPost'>
    @include('admin.newsfeeds.feeds_partials')
    </div>
    <div id='feedLoding' align="center">
      <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
    </div>

  </div>
  <!-- /.col -->
  <div class="col-md-4">
     @include('admin.newsfeeds.side_post')
  </div>
</div>
<!-- /.row -->
</section>
@section('body_bottom')
<script type="text/javascript">

$(function() {
    $('.datepicker').datetimepicker({
      //inline: true,
      format: 'YYYY-MM-DD HH:mm',
      sideBySide: true,
      allowInputToggle: true,
      minDate: '{{ date('Y-m-d')}}',
      widgetPositioning: {
                vertical: 'bottom'
            }
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


@include('admin.newsfeeds.feeds_js')

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
$("#flip").click(function(){
$("#post_option").slideToggle("slow");
});
</script>
@endsection
@endsection
