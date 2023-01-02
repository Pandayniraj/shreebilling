<script src="/vue/vue.js"></script>

<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<?php $userid = \Auth::user()->id; ?>
@section('chat_js')
<script>
var __baseUrl = "{{url('/')}}";
moment.locale('ne');
const receiver = {{ $receiver ?? $userid }};
const _LIVE_RECEIVER = receiver;
const auth_users = {{  $userid }};
const sender_profile_img = `{{ $sender_profile_img }}`;
const rcv_profile_img = `{{ $rcv_profile_img }}`;
var messageobj =  <?php echo json_encode($messages); ?>;

const pusher = new Pusher('{{Config::get('talk.app_key')}}', {
        cluster: '{{Config::get('talk.cluster')}}'
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
        let element = document.getElementById("chathistory");
        element.scrollIntoView({ block: "end"});
         let el2 = document.getElementById("scrollAferSend");
        el2.scrollIntoView({ block: "end"});
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
                console.log(data.message);
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
           $('#message-data').height('20px');
          
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
        return moment(date).format("LLLL")
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
           $(`#message-${id}`).remove();
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
  //  $('#chathistory').scrollTop($('#chathistory')[0].scrollHeight);
  //  var $container = $("#user_lists");
  //  var $scrollTo = $('#user_lists .active_chat');
  // $container.scrollTop(
  //     $scrollTo.offset().top - $container.offset().top + $container.scrollTop()
  // );
  $('#userlivechatbox').css('visibility','visible');


  let element = document.getElementById("chathistory");
  element.scrollIntoView({block: "end"});
  setTimeout(function(){

    let elmnt = document.getElementById("first");
    elmnt.scrollIntoView();


  },100);

});



$(document).on('mouseenter','.chatbox',function(){
   $(this).find('.talkDeleteMessage').show();
 }).on('mouseleave','.chatbox',function(){
  $(this).find('.talkDeleteMessage').hide();
 })
$(document).ready(function(){
  $("#search_user").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#user_lists li").filter(function() {
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

                // var last = response.data[response.data.length - 1];
                // let element = document.getElementById(`message-${last.id}`);
                // element.scrollIntoView();
                tag.attr("data-total-message",t1);
                  
            }
            });
           } 
        console.log(top,start);
    }
});
</script>

@endsection