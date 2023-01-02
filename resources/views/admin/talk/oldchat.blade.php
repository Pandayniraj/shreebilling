@extends('layouts.master')
@section('content')

<style type="text/css">
 
.chatimg{ 
  border-radius: 50%;
  width: 50px;
  height: 50px;
  padding: 10px;


}
#userlivechatbox p{
  white-space: pre-wrap;
 font-size: 14px;
 line-height: 16px;
 text-decoration: none solid rgb(68,73,80);
 word-spacing: 0px;
} 
.talkDeleteMessage{
  display: none;
}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 25%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 64%; padding:
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar textarea{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 30px;
  width: 88%;
  position: relative;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 550px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
/*.received_withd_msg { width: 57%;}*/
.mesgs {
  float: left;
  padding: 20px 15px 0 25px;
  width: 50%;
}

.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 516px;
  overflow-y: auto;
}
.deletemessage{
  float: right;
}
.chatbox {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 20px;
  padding: 8px;
  margin: 5px 0;
  width: 80%;
  margin-left: -1px;
  overflow: visible;

}
.chatbox p {
    font-size: large;
  line-height: 16px;
   overflow-wrap: break-word;
}
.darker {
  border-color: #ccc;
  background-color: #ddd;
}
/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #888; 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555; 
}
.chatbox::after {
  content: "";
  clear: both;
  display: table;
}

.chatbox .userimage {
  float: left;
  max-width: 45px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.chatbox .userimage.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}
img[data-enlargable]{
  cursor: pointer;
}
.custom-file-input {
  color: transparent;
}
.custom-file-input::-webkit-file-upload-button {
  visibility: hidden;
}
.custom-file-input::before {
  content: '{{trans('admin/chat/general.button.send_attachment')}}';
  color: black;
  display: inline-block;
  background: -webkit-linear-gradient(top, #f9f9f9, #e3e3e3);
  border: 1px solid #999;
  border-radius: 3px;
  padding: 5px 8px;
  outline: none;
  white-space: nowrap;
  -webkit-user-select: none;
  cursor: pointer;
  text-shadow: 1px 1px #fff;
  font-weight: 700;
  font-size: 10pt;
}
.custom-file-input:hover::before {
  border-color: black;
}
.custom-file-input:active {
  outline: 0;
}
.custom-file-input:active::before {
  background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9); 
}
</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                <i style="color: green" class="fa fa-comments"> </i> {{ trans('/admin/chat/general.title.heading') }}
                <small>{{ trans('/admin/chat/general.title.sub_title') }}</small>
            </h1>
             <p> {{ trans('/admin/chat/general.title.page_description') }}</p>


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">
        <div class="" data-collapsed="0">
           
            <div class="">
<div class="messaging">
      <div class="">

        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>{{ trans('/admin/chat/general.heading.recent_chat') }}</h4>
            </div>
          </div>
          <div class="inbox_chat">
            @foreach($threads as $inbox)
             @if(!is_null($inbox->thread))
             <a href="{{route('message.read', ['id'=>$inbox->withUser->id])}}">
            <div class="chat_list @if($receiver == $inbox->withUser->id) active_chat @endif">
              <div class="chat_people">
                <div class="chat_img"><img src="{{$inbox->withUser->avatar}}" alt="avatar" /> </div>
                <div class="chat_ib">
                  <h5>{{$inbox->withUser->first_name}}&nbsp;{{$inbox->withUser->last_name}}
                    <span class="chat_date">{{ date('dS M', strtotime($inbox->thread->created_at)) }} {{ $inbox }}</span></h5>
                  <p>{{substr($inbox->thread->message, 0, 20)}}
                    <span class="chat_date"> @if(auth()->user()->id == $inbox->thread->sender->id)
                            <span class="fa fa-reply"></span>
                        @endif
                      </span>
                  </p>
                </div>
              </div>
            </div>
            </a>
            @endif
            @endforeach
          </div>
        </div>

        <div id='userlivechatbox' style="visibility: hidden;">
      @if($rcv_user)
        <div class="mesgs" >
       
          <div class="msg_history" id='chathistory' data-total-message = "{{ $totalMessage }}" data-receiver="{{$receiver}}" v-if='isLoaded'>

            <div v-for = '(message,index) in messageobj'  :key='message.id'  v-if='isLoaded'> 
              
            <div class="chatbox darker"  v-if='message.sender.id == authuser' v-bind:id='"message-"+message.id' style="float: right;margin-right: 10px">
               
                <img :src="sender_profile_img" alt="Avatar" class="right userimage" style="width:100%;" v-if = "message.att_type != 'img'">
                  <p v-if = 'message.attachment'> 

                    <img v-if ="message.att_type == 'img' " :src='message.attachment' style="width: 100%; height: 100%;" data-enlargable>
                    <a v-else :href="message.attachment" target="_blank" :download="message.file_name">@{{message.file_name}}</a>

                  </p>

                  <p v-if = '!message.attachment' v-html='urlify(message.message)'> 
                   
                  </p>

                <span class="time-left">
                @{{message.created_at | moment }}
            <a href="#" class="text-muted" title="Seen" v-if='index == messageobj.length - 1 && (message.is_seen || live_seen)'>
              &#10004;&#10004;
            </a>
              </span>
            <a href='javascript::void()' style="float: left;" class="talkDeleteMessage" :data-message-id="message.id" data-toggle="tooltip" title="Delete">&nbsp;&nbsp;<i class="fa  fa-trash-o"></i></a>
              </div>


              <div class="chatbox" v-if='message.sender.id != authuser' v-bind:id='"message-"+message.id' style='float: left'>
                
                  <img :src="rcv_profile_img" alt="Avatar" class="right userimage" style="width:100%;" v-if = "message.att_type != 'img'">
                  <p v-if = 'message.attachment'> 

                    <img v-if ="message.att_type == 'img' " :src='message.attachment' style="width: 100%; height: 100%;" data-enlargable>
                    <a v-else :href="message.attachment" ><i class="fa  fa-file-o"></i>&nbsp;@{{message.file_name}}</a>

                  </p>
                  <p v-if = '!message.attachment' v-html='urlify(message.message)' > </p>
                 <a href='javascript::void()' :data-message-id="message.id" style="float: right;" class="talkDeleteMessage" data-toggle="tooltip" title="Delete" >&nbsp;&nbsp;<i class="fa  fa-trash-o"></i></a>
                <span class="time-right" >@{{message.created_at | moment }}  </span>

              </div>
          
          </div>
              <div class="chatbox"  style='float: left;display: none;' id='userTyping'>
                <p><i>{{ ucfirst($rcv_user->username) }} is typing...</i></p>
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

          </div>
           <form method="post" id ='talkSendMessages'>
          <div class="row">
          
             <div class="col-md-12">
               <textarea name="message-data" id="message-data" placeholder ="{{ trans('/admin/chat/general.placeholder.send_message') }}" rows="3" class="SeenMessage form-control" data-user-id="{{$receiver}}"
              data-conversation-id="{{$messages[0]->conversation_id}}" required="" rows="8" cols="5" style="width: 100%">
            </textarea>
             
              <input type="hidden" name="_id" value="{{ $receiver }}">
            </div>
          
          </div>
          <div class="row" style="margin-top: 10px">
            
            <div class="col-sm-6">
              <label class="btn">
                  <input type="file" id='attachment' class="custom-file-input">
              </label>
            </div>

            <div class="col-sm-6">
              <button type="submit" class="btn btn-primary" style="float: right;">{{ trans('/admin/chat/general.button.send_message') }}</button>
            </div>

          </div>
        </form>


        </div>
      @else
      <div class="mesgs">
          <div class="alert alert-info" role="alert">
              Please select a user to start conversation
          </div>
      </div>
      @endif
      </div>

        <div class="inbox_people" style="border-left:1px solid #c4c4c4;">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>{{ trans('/admin/chat/general.heading.user_list') }}</h4>

            </div>
              <input type="text" class="form-control" placeholder="{{ trans('/admin/chat/general.placeholder.search_user') }}.." id='search_user'>
          </div>
          <div class="inbox_chat" id='user_lists'>
           @foreach($users as $user)
             <a href="{{route('message.read', ['id'=>$user->id])}}">
            <div class="chat_list @if($receiver == $user->id) active_chat @endif">
              <div class="chat_people">
                <div class="chat_img"><img src="{{$user->avatar}}" alt="avatar" /> </div>
                <div class="chat_ib">
                  <h5>{{$user->first_name}}&nbsp;{{$user->last_name}}</h5>
                   @if(Cache::has('user-is-online-' .$user->id))
                  <i class="fa fa-circle text-success"></i>
                  <small>Online</small>
                  @else
                      <i class="fa fa-circle"></i>
                      <small>Offline</small>
                  @endif
                </div>
              </div>
            </div>
            </a>
            @endforeach
          </div>
        </div>
      </div>
      
    </div>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="https://js.pusher.com/3.1/pusher.min.js" ></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<?php $userid = \Auth::user()->id; ?>
<script>
var __baseUrl = "{{url('/')}}";
moment.locale('ne');
const receiver = {{ $receiver ?? $userid }};
const auth_users = {{  $userid }};
const sender_profile_img = `{{ $sender_profile_img }}`;
const rcv_profile_img = `{{ $rcv_profile_img }}`;
var messageobj =  <?php echo json_encode($messages); ?>;

const pusher = new Pusher('4df5c0b0bf2e03e7c74c', {
        cluster: 'ap2'
      });
const channel = pusher.subscribe("{{Config::get('talk.chat_channel')}}");

Pusher.logToConsole = false;

var clearTimerId;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('body').on('click', '#message-data', function (e) {
    var tag, url, id, request;
    tag = $(this);
    if(tag.val().trim() == ''){
        cid= tag.data('conversation-id');
        url = __baseUrl + '/ajax/message/seen/' + cid;
        request = $.ajax({
        method: "post",
        url: url,
        });
        request.done(function(response) {

        });
    }
   
});

function scrollmessage(){
    setTimeout(function() {
        $('#chathistory').scrollTop( $('#chathistory')[0].scrollHeight);
      }, 100);
 }
 function addenlargeclass(){
  $('img[data-enlargable]').addClass('img-enlargable').click(function(){
    var src = $(this).attr('src');
    var modal;
    function removeModal(){ modal.remove(); $('body').off('keyup.modal-close'); }
    modal = $('<div>').css({
        background: 'RGBA(0,0,0,.5) url('+src+') no-repeat center',
        backgroundSize: 'contain',
        width:'100%', height:'100%',
        position:'fixed',
        zIndex:'10000',
        top:'0', left:'0',
        cursor: 'zoom-out'
    }).click(function(){
        removeModal();
    }).appendTo('body');
    //handling ESC
    $('body').on('keyup.modal-close', function(e){
      if(e.key==='Escape'){ removeModal(); } 
    });
});
}
var myObject = new Vue({
  el: '#userlivechatbox',
  data: {
    messageobj:messageobj,
    authuser: auth_users,
    sender_profile_img: sender_profile_img,
    rcv_profile_img: rcv_profile_img,
    isLoaded: true,
    live_seen: false,
  },
  methods:{
        callFunction: function () {
            var v = this;
              channel.bind(`chat-user-{{ \Auth::user()->id }}-${receiver}`, function(data) {
                  v.messageobj.push(data.message);
                  scrollmessage();
              });
              channel.bind(`chat-user-typing-{{ \Auth::user()->id }}-${receiver}`, function(data) {
                    $('#userTyping').show();
                    //console.log('typing');
                    clearTimeout(clearTimerId);
                    clearTimerId = setTimeout(function () {
                      v.live_seen= true;
                      $('#userTyping').hide();
                    }, 4000);
                    scrollmessage();
              });
        },
        loadMessage: function(){
           var v = this;
           $.get(`/admin/talk/sync/message/${receiver}`,function(response){
              v.messageobj = response.message;
          });
        },
        appendmessage: function(message){
          var v = this;
          let obj = {};
          obj['message'] = message;
          obj['created_at'] =  new Date().toLocaleString();
          obj['id']= 'new';
          obj['sender'] = {id: auth_users};
          v.messageobj.push(obj);
          v.live_seen = false;
          $('#message-data').val('');
          scrollmessage();
        },
         appendattachment: function(message,type,fileName){
          var v = this;
          let obj = {};
          obj['attachment'] = message;
          obj['file_name'] = fileName;
          obj['created_at'] =  new Date().toLocaleString();
          obj['att_type'] = type;
          obj['id']= 'new';
          obj['sender'] = {id: auth_users};
          v.messageobj.push(obj);
          v.live_seen = false;
          $('#message-data').val('');
          scrollmessage();
          setTimeout(function(){
            addenlargeclass();
          },600);
          
        },
        assignid: function(id){
          this.messageobj[this.messageobj.length - 1]['id'] = id;
          return 0;
        },
        validateYouTubeUrl: function(url){
            if (url != undefined || url != '') {        
              var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
              var match = url.match(regExp);
              if (match && match[2].length == 11) {
              var iframeMarkup = '<a href="'+url+'" target="_blank"><iframe width="100%" height="315" src="//www.youtube.com/embed/' 
                + match[2] + '" frameborder="0" allowfullscreen></iframe></a>';
                return iframeMarkup;
              } else {
                return false;
              }
            }
        },
         urlify: function(text) {
          let isyoutube= this.validateYouTubeUrl(text)
          if(isyoutube){
            return isyoutube;
          }
          let urlRegex = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi;
          
          let entityMap = {
          '<': '&lt;',
          '>': '&gt;',
          };
          let safehtml = String(text).replace(/[<>]/g, function (s) {
              return entityMap[s];
           });
          let html = safehtml.replace(urlRegex, function(url) {
            return '<a href="' + url + '" target="_blank">' + url + '</a>';
          });

          return html;
        }
    },

    filters: {
      moment: function (date) {
        return moment(date).format("hh:mm a")
      },
     
    },

    mounted () {
      this.callFunction();
    }
});

var cansendTypingEvent = true;
var typingTimeout;

$('#talkSendMessages').on('submit', function(e) {
        e.preventDefault();
        var url, request, tag, data;
        tag = $(this);
        url = __baseUrl + '/ajax/message/send';
        data = tag.serialize();
        myObject.appendmessage($('#message-data').val());
        request = $.ajax({
            method: "post",
            url: url,
            data: data
        });
        request.done(function (response) {
            if (response.sucess) {
                tag[0].reset();
                myObject.assignid(response.id);
            }
        });
        while (typingTimeout--) {
          clearTimeout(typingTimeout); // will do nothing if no timeout with id is present
        }
    });


  $('body').on('click', '.talkDeleteMessage', function (e) {
        e.preventDefault();
        var tag, url, id, request;
        tag = $(this);
        id = tag.data('message-id');
        url = __baseUrl + '/ajax/message/delete/' + id;
        if(!confirm('Do you want to delete this message?')) {
            return false;
        }
        request = $.ajax({
            method: "post",
            url: url,
            data: {"_method": "DELETE"}
        });

        request.done(function(response) {
            myObject.loadMessage();
        });
    });


  $("#message-data").keyup(function(e){
    if(cansendTypingEvent && e.keyCode != 13){ //dont sent typing event on enter press and only send in 2 sec interval
      $.get(`/admin/talk/typing/${receiver}`,function(){
        //console.log('done');
      });
      cansendTypingEvent = false;
       clearTimeout(clearTimerId);
      var typingTimeout = setTimeout(function(){
        cansendTypingEvent = true;
      },6000);
    }
   
  });

  $('body').on('click', '.talkDeleteMoreMessage', function (e) {
        e.preventDefault();
        var tag, url, id, request;
        tag = $(this);
        id = tag.data('message-id');
        url = __baseUrl + '/ajax/message/delete/' + id;
        if(!confirm('Do you want to delete this message?')) {
            return false;
        }
        request = $.ajax({
            method: "post",
            url: url,
            data: {"_method": "DELETE"}
        });

        request.done(function(response) {
             $('#message-' + id).hide(500, function () {
                    $(this).remove();
                });
        });
    });

$(function(){
   $('#chathistory').scrollTop($('#chathistory')[0].scrollHeight);
 
   var $container = $("#user_lists");
   var $scrollTo = $('#user_lists .active_chat');
  $container.scrollTop(
      $scrollTo.offset().top - $container.offset().top + $container.scrollTop()
  );
  $('#userlivechatbox').css('visibility','visible');
})


$(document).on('mouseenter','.chatbox',function(){
   $(this).find('.talkDeleteMessage').show();
 }).on('mouseleave','.chatbox',function(){
  $(this).find('.talkDeleteMessage').hide();
 })
$(document).ready(function(){
  $("#search_user").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#user_lists a").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});


const validImageTypes = ['image/gif', 'image/jpeg', 'image/png','image/jpg'];
$(document).on('change','#attachment',function(){
  var input = this;
  // console.log('done');
      if (input.files && input.files[0]) {
        var fileType = input.files[0]['type'];
        var fileName = input.files[0].name;
        var sizeInMB = (input.files[0].size / (1024*1024)).toFixed(2);
        if(sizeInMB > 5){
          alert("Please Choose A file size below 5MB");
          $(input).val('');
          return;
        }
        extension = fileName.split('.').pop(); 
        var reader = new FileReader();
        var reader2 = new FileReader();
        reader.onload = function (e) {

          if (validImageTypes.includes(fileType)) {
            var types = 'img';
          }else{
             var types = 'others';
          } 
          url = __baseUrl + '/ajax/message/send';
          var _token = $('meta[name="csrf-token"]').attr('content');
          paraObj = {
            '_id': $('#talkSendMessages input[name=_id]').val(),
            'message-data': 'attachment',
            'attachment':  window.btoa(e.target.result),
            'att_type': types,
            'file_name': fileName,
            'extension': extension,
            '_token': _token,
          }
          $(input).val('');
          $.post(url,paraObj,function(response){
            if (response.sucess) {
                myObject.assignid(response.id);
            }
          }).fail(function(){
            alert("Failed To send attachment");
          });
        };
        reader2.onload = function (e) { //

          if (validImageTypes.includes(fileType)) {
            var types = 'img';
          }else{
             var types = 'others';
          }
          myObject.appendattachment(e.target.result,types,fileName);
        }
        reader2.readAsDataURL(input.files[0]);
        reader.readAsBinaryString(input.files[0]);
    }
});

addenlargeclass();

  $('.msg_history').on('scroll', function(e) {
    let  scroll = $(this).scrollTop();
    if(scroll == 0){
        var tag, url, id, request;
        tag = $(this);
        var top=0,start=0;
        var total_message=($(this).attr('data-total-message'));
        var uid=tag.data('receiver');
        var t1 = total_message-20;
        // console.log(t1);
        if(t1>0){
            top = t1
            s1 = t1-20;
            if(s1>0)
                start=s1;
            url = __baseUrl + '/ajax/message/more/' + uid + '/' + start + '/' + top;
            request = $.ajax({
            method: "post",
            url: url,
            });
            request.done(function(response) {

            if (response.status == 'success') {
                $('#chathistory').scrollTop(5);
                myObject.messageobj = [...response.data,...myObject.messageobj];
               
                $("#chathistory").attr("data-total-message",t1);
                
            }
            });
           } 
        console.log(top,start);
    }
});
</script>
@endsection
