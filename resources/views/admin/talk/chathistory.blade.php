@extends('layouts.master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css">
<style type="text/css">
  body{
    font-family: Montserrat,Helvetica,Arial,serif;
  }
  @media only screen and (max-width: 768px) {
    #first {
      order: 1;
    }
    #second {
      bottom: 0;  }

    }
    .direct-chat-text p{

      white-space: pre-wrap;
      font-size: 15px;
      line-height: 15px;
      word-spacing: 0px;
    }
    .talkDeleteMessage{
      float: right;
      margin-top: -15px;
      display: none;
    }
    @media  screen and (max-width: 995px) {
     #second .products-list{
      max-height: 25vh !important;
    }
    #first .direct-chat-messages{
      min-height: 80vh !important;
    }
  }
  .direct-chat .right a{


    color : blue;
    text-decoration: underline;

  }
  .panel-default>.panel-heading {
    color: #333;
    background-color: #fff;
    border-color: #ddd;
  }
  .pull-first {
    margin-top: -23px;
    margin-right: -25px;
  }
  .headpanel {
    height: 75px !important;
  }
  .searchimg {
   /* border: 1px solid #3c8dbc;*/
   height: 44px;
   border-radius: 25px !important;
 }
 .box.box-solid.box-danger {
  border: none !important;
}
.box.box-solid.box-primary {
  border:none;
}
.defaultpanel {
  border-color: #fff !important;
  background-color: #fff !important;

}
.panel {
  border-radius: 0px;
}
#first {
  padding-left: 0px;
}
#second {
  padding-right: 0px;
}
.bodypanel {
  padding: 0px;
}
.box-body {
  padding: 1px !important;
}
.d-flex {
  display: flex;
}
.align-items-center {
  align-items: center;
}
.headpanel2 {
  height: 74px;
  border-bottom: none;
}
.secondflex {
  float: right;
  position: relative;
  top: -34px;
}
svg.font-medium-2 {
  height: 17px;
  width: 17px;
}
.me-1 {
  margin-right: 1rem!important;
}
h4.profilename {
  margin-left: 15px;
}
.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: none;
  float: left;
  min-width: 160px;
  padding: 5px 0;
  margin: 2px 0 0;
  font-size: 14px;
  text-align: left;
  list-style: none;
  background-color: #fff;
  -webkit-background-clip: padding-box;
  background-clip: padding-box;
  border: 1px solid #ccc;
  border: 1px solid rgba(0,0,0,.15);
  border-radius: 4px;
  -webkit-box-shadow: 0 6px 12px rgb(0 0 0 / 18%);
  box-shadow: 0 6px 12px rgb(0 0 0 / 18%);
  transform: translate(-136px, 35px);
}
  /*.panel-body {
    padding: 0px;
  }
  .*/panel-footer {

    background-color: #fff;
  }
  .box-header {
   padding: 2px;

 }
 .products-list>.item {
  padding: 0.786rem 1.286rem;
}
.product-img img {
  border: 3px solid #fff;
}

.label-success {
  background-color: none;
  background-color: transparent !important;
  color: #B9B9C3 !important;
  font-weight: 500;
}
.panel-body.bg-body {
  background-image: url(/images/uploaded/background.svg);
  background-color: #F2F0F7;
  background-repeat: repeat;
  background-size: 210px;
}
.direct-chat-text {
  background: #3c8dbc;
  border: 1px solid #3c8dbc;
  width: auto;
  max-width: 75%;
  float: right;
}
.direct-chat-text:after, .direct-chat-text:before {
  border-right-color: #3c8dbc;
}
.defaultpanel {
  border-radius: 10px;
}
::-webkit-scrollbar {
  width: 5px;
}

::-webkit-scrollbar-track {
  background-color: #ebebeb;
  -webkit-border-radius: 10px;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  -webkit-border-radius: 10px;
  border-radius: 10px;
  background: #6d6d6d6b; 
}
.panel-footer {
  background-color: #fff;
}
.box-footer {
  border-top: none;
}
.direct-chat-img {
  border-radius: 50%;
  float: right;
  width: 40px;
  height: 40px;
  border: 2px solid #FFF;
  margin-left: 8px;
}
.btn-default {
  background-color: #fff;
  color: #444;
  border-color: #fff;
}
p.msg-section {
  color: #B9B9C3;
  font-size: 14px;
}

.product-info a {
  font-size: 16px;
  font-family: inherit;
  font-weight: 500 !important;
  line-height: 1.2;
  color: #5E5873 !important;
}
span.label.label-success.pull-right {
  margin-top: -47px;
}
.product-img [class*=avatar-status-] {
  border-radius: 50%;
  width: 11px;
  height: 11px;
  position: absolute;
  right: 0;
  bottom: 0;
  border: 1px solid #FFF;
}
.product-img {
  position: relative;
  cursor: pointer;
  display: inline-flex;
}
.product-img .avatar-status-busy {
  background-color: #EA5455;
  position: relative;
  top: 29px;
  right: 9px;
}
i.fa.fa-microphone {
  position: relative;
  top: 11px;
  z-index: 4;
  left: 12px;
  cursor: pointer;
}
.boxsize{
  margin-top: -20px !important;
  height: 43px !important;
  border-radius: 4px !important;
}
.btn-default:hover, .btn-default:active, .btn-default.hover {
  background-color: transparent !important;
  border: 1px solid transparent !important;
}
i.fa.fa-paperclip {
  position: relative;
  right: 35px;
  z-index: 4;
}
.btnsend {
  height: 43px;
  border-radius: 6px !important;
}
.form-control {
  padding: 10px 44px !important;
}
.serch-btn {
  position: relative;
  top: 31px;
  left: 16px;
  color: #3c8dbc;
}
svg.font-medium-2 {
  cursor: pointer;
}
.content-header {
  position: relative;
  padding: 0;
}
.panel-default {
  border-color: #ddd;
  box-shadow: rgb(100 100 111 / 20%) 0px 7px 29px 0px;
}
/*.emojionearea .emojionearea-editor {
  min-height: 0em !important;
  padding: 0 !important;
}*/
.emojionearea-button-open {
  right: 66px;
  top: -7px;
}
.emojionearea-button-close {
  margin-left: -65px;
  margin-top: -6px;
}
.active-chat-menu{
  background: #3c8dbc !important;
}
li.item.active_chat.active-chat-menu a {
  color: #fff !important;
}

.emojionearea .emojionearea-button {
 opacity: 1;
 z-index: 5;
 position: absolute;
 right: -41px;
 top: 15px;

}
.direct-chat-text p {
  color: #fff !important;
  font-size: 13px;
  font-weight: 400 !important;
}
.right .direct-chat-text:after, .right .direct-chat-text:before {
  right: auto;
  left: 100%;
  border-right-color: transparent;
  border-left-color: #3c8dbc;
}
.direct-chat-text p a {
  color: #fff !important;
  font-size: 13px;
  font-weight: 400;
}
i.fa.fa-trash-o {
  position: relative;
  top: 6px;
}
.direct-chat-text:after, .direct-chat-text:before {
    border-right-color: transparent;
}
@media (min-width: 769px) and (max-width: 1200px){
  .searchsect {
    margin-top: -66px !important;
    width: 80% !important;
    margin-left: 59px !important;
  }
  #second {
    padding-right: 15px;
    padding-left: 0px;
  }
  #second .products-list {
    max-height: 57vh !important;
  }
}
@media (min-width: 481px) and (max-width: 768px){
  .searchsect {
    margin-top: -66px !important;
    width: 80% !important;
    margin-left: 59px !important;
  }
  #second {
    padding-right: 15px;
    padding-left: 0px;
  }
  #second .products-list {
    max-height: 57vh !important;
  }

}
@media (min-width: 320px) and (max-width: 480px){
  .searchsect {
    margin-top: -66px !important;
    width: 80% !important;
    margin-left: 59px !important;
  }
  #second {
    padding-right: 15px;
    padding-left: 0px;
  }
  #second .products-list {
    max-height: 50vh !important;
  }
    .emojionearea .emojionearea-editor {
    min-height: 7em !important;
    padding: -7px !important;
    margin-top: -8px !important;
    width: 100% !important;
    font-size: 9px !important;
}
.input-group-btn:last-child>.btn, .input-group-btn:last-child>.btn-group {
    margin-left: -31px;
    width: 93px;
    font-size: 10px;
}
}
.emojionearea .emojionearea-editor {
    padding: 0 !important;
  }

</style>


<div class="panel panel-default defaultpanel">
  <div class="panel-body">
    <section class="content-header" style="margin-top: 0px; margin-bottom: 0px">
      <h1>
        <i style="color: green" class="fa fa-comments"> </i> {{ trans('/admin/chat/general.title.heading') }}
        <small>{{ trans('/admin/chat/general.title.sub_title') }}</small>
      </h1>
      <p> {{ trans('/admin/chat/general.title.page_description') }}</p>


      {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
    </section>
  </div>
</div>

<div class="row">
  <div class="@if($receiver) col-md-4 @else col-md-12 @endif" id="second">
    <div class="panel panel-default">
      <div class="panel-heading headpanel">
        <div class="box-header">
        <!--   <div class="col-md-12">
           <div class="box-tools pull-right pull-first">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" onclick="makeFullView()"><i class="fa fa-times"></i></button>
          </div>
        </div> -->
        <div class="row">
          <div class="col-md-2">
            <div class="product-img">
             <img src="/images/profiles/default.png" style="width: 42px; border-radius: 50%; height: 42px;">
             <span class="avatar-status-busy"></span>
           </div>
         </div>
         <div class="col-md-10">
          <div class="searchsect" style="margin-top:-20px;">
            <span> <i class="fa fa-search serch-btn" aria-hidden="true"></i></span>
            <input type="text" class="form-control searchimg" placeholder="Search or start a new chat" id='search_user'>
          </div>

        </div>

      </div>
      <!--  <h3 class="box-title">{{ trans('/admin/chat/general.heading.user_list') }}</h3> -->


    </div>
  </div>
  <div class="panel-body bodypanel">
    <div class="box box-danger box-solid">

      <!-- /.box-header -->
      <div class="box-body">
        <ul class="products-list product-list-in-box"  style="max-height: 75vh;overflow-y: scroll; font-size: 17px; color: black" id='user_lists' >
         @foreach($users as $user)
         <li class="item active_chat @if($receiver == $user->id) active-chat-menu @endif">

          <div class="product-img">
            <img  src="{{ $user->image?'/images/profiles/'.$user->image:$user->avatar }}" style="width: 45px; border-radius: 50%; height: 45px;"><!-- alt="{{$user->first_name}} {{$user->last_name}} -->
            <span class="avatar-status-busy"></span>
          </div>

          <div class="product-info">
            <a href="{{route('message.read', ['id'=>$user->id])}}" class="product-title">{{$user->first_name}} {{$user->last_name}}
              <p class="msg-section">hi how are you ?</p>
              <span class="label label-success pull-right" style="margin-right: 5px;">4:15 PM</span>
                <!-- @if(Cache::has('user-is-online-' .$user->id))
                <span class="label label-success pull-right" style="margin-right: 5px;">Online</span>
                @else
                <span class="label label-default pull-right" style="margin-right: 5px;">Offline</span>
                @endif -->
              </a>
            </div>
          </li>
          @endforeach

          <!-- /.item -->
        </ul>
      </div>
      <!-- /.box-body -->

      <!-- /.box-footer -->
    </div>
  </div>
</div>


</div>
@if($receiver)
<div class="col-xs-12 col-sm-12 col-md-8" id="first">
  <div class="panel panel-default" style="border-left: none !important;">
   <div class="panel-heading headpanel2">
    <div class="chat-navbar">
      <div class="chat-header">
        <div class="d-flex align-items-center">
          <div class="product-img">
           <img src="/images/profiles/156582604018198560_10155327046399637_4588502365326031497_n.jpg" style="width: 42px; border-radius: 50%; height: 42px;" alt="Rajendra KC">
           <span class="avatar-status-busy"></span>
         </div>
         <h4 class="profilename">Rajendra KC</h4>
       </div>
       <div class="d-flex align-items-center secondflex">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call cursor-pointer d-sm-block d-none font-medium-2 me-1"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video cursor-pointer d-sm-block d-none font-medium-2 me-1"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search cursor-pointer d-sm-block d-none font-medium-2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <div class="dropdown">
          <button class="btn-icon btn btn-transparent hide-arrow btn-sm dropdown-toggle waves-effect waves-float waves-light" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-medium-2" id="chat-header-actions"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="chat-header-actions" style="">
            <a class="dropdown-item" href="#">View Contact</a>
            <a class="dropdown-item" href="#">Mute Notifications</a>
            <a class="dropdown-item" href="#">Block Contact</a>
            <a class="dropdown-item" href="#">Clear Chat</a>
            <a class="dropdown-item" href="#">Report</a>
          </div>
        </div>
      </div>
    </div>


  </div>
</div>

      <!-- <div class="box-header">
         <h3 class="box-title">{{$rcv_user->first_name}} {{$rcv_user->last_name}}</h3>

       <div class="box-tools pull-right">
        <span data-toggle="tooltip" title="" class="badge bg-light-blue" data-original-title="{{$total_unseen_message}} New Messages">{{$total_unseen_message}}</span>
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="My Contacts">
          <i class="fa fa-comments"></i></button>

        </div> -->


        <div class="panel-body bg-body">
         <!-- DIRECT CHAT SUCCESS -->
         <div class="box-section">

          <!-- /.box-header -->
          <div class="box-body">
            <!-- Conversations are loaded here -->
            <div class="direct-chat-messages msg_history" style="min-height: 61.8vh;visibility: hidden; overflow-x: hidden;" id='userlivechatbox'  data-total-message = "{{ $totalMessage }}" data-receiver="{{$receiver}}">
              <!-- Message. Default to the left -->

              <div  id='chathistory'  v-if='isLoaded' style="clear: both;">

               @if($rcv_user)
               <div v-for = '(message,index) in messageobj'  :key='message.id'  v-if='isLoaded' class="chatbox"> 
                <div class="direct-chat-msg" v-if='message.sender.id == authuser' v-bind:id='"message-"+message.id'>
                  <div class="direct-chat-info clearfix">
                   <!--  <span class="direct-chat-name pull-left">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span> -->
                   <span class="direct-chat-timestamp pull-right">@{{message.created_at | moment }}</span>
                 </div>
                 <!-- /.direct-chat-info -->
                 <img class="direct-chat-img" :src="sender_profile_img" alt="Avatar" class="right userimage" style="width:40px;height: 40px" ><!-- /.direct-chat-img -->
                 <div class="direct-chat-text" :style="[message.att_type == 'img' ? {'padding': '0px' } : {} ]">
                   <p v-if = 'message.attachment' class="para-color"> 
                    <img v-if ="message.att_type == 'img' " :src='message.attachment' style="width: 100%; height: 100%;" data-enlargable>
                    <a v-else :href="message.attachment" target="_blank" :download="message.file_name" class="msg-color">@{{message.file_name}}</a>
                  </p>
                  <p v-if = '!message.attachment' v-html='urlify(message.message)'></p>
                  <p>
                    <a href='javascript::void()'  class="talkDeleteMessage" :data-message-id="message.id" data-toggle="tooltip" title="Delete">&nbsp;&nbsp;<i class="fa  fa-trash-o"></i></a>
                  </p>
                  <a href="#" class="text-muted" title="Seen" v-if='index == messageobj.length - 1 && (message.is_seen || live_seen)'>
                   &#10004;&#10004;
                 </a>
               </div>
               <!-- /.direct-chat-text -->
             </div>
             <!-- /.direct-chat-msg -->

             <!-- Message to the right -->
             <div class="direct-chat-msg right" v-if='message.sender.id != authuser' v-bind:id='"message-"+message.id' >
              <div class="direct-chat-info clearfix">
                <span class="direct-chat-name pull-right">@{{message.sender.first_name}} @{{message.sender.last_name}}</span>
                <span class="direct-chat-timestamp pull-left">@{{message.created_at | moment }}</span>
              </div>
              <!-- /.direct-chat-info -->
              <img class="direct-chat-img" :src="rcv_profile_img" alt="Avatar" class="right userimage" style="width:40px;height: 40px" ><!-- /.direct-chat-img -->
              <div class="direct-chat-text" :style="[message.att_type == 'img' ? {'padding': '0px' } : {} ]">
               <p v-if = 'message.attachment'> 
                <img v-if ="message.att_type == 'img' " :src='message.attachment' style="width: 100%; height: 100%;" data-enlargable>
                <a v-else :href="message.attachment" target="_blank" :download="message.file_name">@{{message.file_name}}</a>
              </p>
              <p v-if = '!message.attachment' v-html='urlify(message.message)'></p>
              <p>
                <a href='javascript::void()'  class="talkDeleteMessage" :data-message-id="message.id" data-toggle="tooltip" title="Delete">&nbsp;&nbsp;<i class="fa  fa-trash-o"></i></a>
              </p>
            </div>
            <!-- /.direct-chat-text -->
          </div>
        </div>
        @endif
        <div class="direct-chat-msg right" style="display: none;" id='userTyping'>
         <img class="direct-chat-img" :src="rcv_profile_img" alt="Avatar" class="right userimage" style="width:40px;height: 40px" >
         <div class="direct-chat-text">
          <p><i>Typing.......</i></p>
        </div>
      </div> 

    </div>




    <div v-if='messageobj.length == 0' >
      <div align="center">
        <div class="img-responsive"> 
          <img :src="rcv_profile_img" class="img-thumbnail" alt="Avatar" style="width:100%;border-radius: 50%;height: 100px;width: 100px;" >
        </div>
        <span style="margin: auto;"><h3>{{  $rcv_user->first_name }} {{  $rcv_user->last_name }}</h3></span>
      </div>
      <hr>
      <div align="left">
        <div class="alert alert-info" role="alert">
          Type Message To start Conversation
        </div>
      </div>
    </div>
    <!-- /.direct-chat-msg -->
  </div>
  <!--/.direct-chat-messages-->

  <!-- Contacts are loaded here -->
  <div class="direct-chat-contacts" style="min-height: 76.8vh;">
    <ul class="contacts-list">
      @foreach($threads as $inbox)
      @if(!is_null($inbox->thread))
      <li>
        <a href="{{route('message.read', ['id'=>$inbox->withUser->id])}}">
          <img class="contacts-list-img"  src="{{$inbox->withUser->avatar}}" style="height: 40px; width: 40px;" alt="avatar">

          <div class="contacts-list-info">
            <span class="contacts-list-name">
              {{$inbox->withUser->first_name}}&nbsp;{{$inbox->withUser->last_name}}
              <small class="contacts-list-date pull-right">{{ date('dS M', strtotime($inbox->thread->created_at)) }}</small>
            </span>
            <span class="contacts-list-msg">{{substr($inbox->thread->message, 0, 20)}}</span>
            @if(auth()->user()->id == $inbox->thread->sender->id)
            <i class="fa fa-reply"></i>
            @endif
          </div>
          <!-- /.contacts-list-info -->
        </a>
      </li>
      @endif
      @endforeach
      <!-- End Contact Item -->
    </ul>
    <!-- /.contatcts-list -->
  </div>
  <!-- /.direct-chat-pane -->
</div>
<!-- /.box-body -->

</div>
</div>
<div class="panel-footer">
  <div class="box-footer">
    <!--  <textarea cols="10" rows="2" id="emoji"></textarea> -->
    <form method="post" id ='talkSendMessages'>
      <div class="input-group">
        <span> <i class="fa fa-microphone" aria-hidden="true"></i></span>

        <textarea type="text" name="message-data" placeholder="Type Your Message ..." class="SeenMessage form-control resize-ta boxsize emoji" data-user-id="{{$receiver}}"
        data-conversation-id="{{ count($messages) > 0 ?  $messages[0]->conversation_id : ''}}" required="" id="message-data" autofocus rows="1"></textarea>
        <input type="hidden" name="_id" value="{{ $receiver }}">

        <span class="input-group-btn">

          <label type="button" class="btn btn-default" for='attachment' title="Send Attachment"><i class="fa  fa-paperclip"></i>
            <input type="file" name="" style="display: none;" id='attachment'>

          </label>
        </span>
        <!--  <span id="emoji"></span> -->
        <span class="input-group-btn">
          <button type="submit" class="btn btn-primary btnsend" style="float: right;">{{ trans('/admin/chat/general.button.send_message') }}</button>


        </span>

      </div>
    </form>

  </div>
  <!-- /.box-footer-->
</div>
</div>
</div>


<!--/.direct-chat -->
</div>
@endif
</div>
<span id='scrollAferSend'></span>
@include('admin.talk.chatjs')
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>
<script>
  $('.emoji').emojioneArea({
    pickerPosition:'bottom'
  })
</script>
<script type="text/javascript">
  $(function(){

// Dealing with Textarea Height
function calcHeight(value) {
  let numberOfLineBreaks = (value.match(/\n/g) || []).length;
        // min-height + lines x line-height + padding + border
        let newHeight = 20 + numberOfLineBreaks * 20 + 12 + 2;
        return newHeight;
      }

      let textarea = document.querySelector(".resize-ta");
      textarea.addEventListener("keyup", () => {
        textarea.style.height = calcHeight(textarea.value) + "px";
      });

 // $('#remove-others').click(function(){
 //      alert("DONE");
 //      

 //    });

});

  function makeFullView(){

    $('#first').attr('class','col-xs-12');
  }  

</script>
@endsection