<link rel="stylesheet" type="text/css" href="/toastr/toastr.min.css">
<script src='/toastr/toastr.min.js'></script>

<script src="https://js.pusher.com/3.1/pusher.min.js" ></script>
<style type="text/css">
  .livechat-message{


    white-space: pre-wrap;
 font-size: 14px;
 line-height: 16px;

 word-spacing: 0px;
  }
</style>
@yield('chat_js')
@auth
<script type="text/javascript">
  

$(function(){

function showIncommingMessage(message){

  if(typeof(_LIVE_RECEIVER) !== 'undefined'){
    if(_LIVE_RECEIVER == message.sender.id){
      return ;
    }
  }

  if(message.attachment){
    var liveMessage = 'Sent an Attachment';
  }else{
    var liveMessage = message.message;
  }

  let messageHTML =(`
      <span class='livechat-message'>  ${liveMessage} </span>
      <form onsubmit = "event.preventDefault();" class="toastChatReplyMessage">
       <div class="form-group">
        <div class="row">
        <div class='col-sm-8 nopadding'>
        <input type='hidden' value='${message.sender.id}' name='sender_id'> 
          <input type="text" class="form-control" placeholder ='Type To reply' style='width: 100%;' name='message' onclick='event.stopPropagation();'>
        </div>
        <div class='col-sm-4 nopadding'>
          <button class="btn btn-primary" onclick='event.stopPropagation();'>Send</button>
        </div>
        </div>
        </div
      </form>`);

  toastr.info(messageHTML, message.sender.full_name, {
      timeOut: 500000000
      , closeButton: true
      , progressBar: true,
      onclick: function () {
          window.open(`/admin/talk/${message.sender.id}`);
      },
  });


}
  const pusherWatchMessage = new Pusher('{{Config::get('talk.app_key')}}', {
    cluster: '{{Config::get('talk.cluster')}}'
  });

  const channelWatchMessage = pusherWatchMessage.subscribe("{{Config::get('talk.chat_channel')}}");

  Pusher.logToConsole = false;
  channelWatchMessage.bind(`chat-recv_user-{{ \Auth::user()->id }}`, function(data) {
    
       
        showIncommingMessage(data.message);
    });


$(document).on('submit','.toastChatReplyMessage',function(){

   let messageObj =  $(this).serializeArray().reduce(function(obj, item) {
              obj[item.name] = item.value;
              return obj;
          }, {});

    
        console.log(messageObj);

    let toSend = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      'message-data': messageObj.message,
      '_id': messageObj.sender_id,
    }

    $.post('/ajax/message/send',toSend,function(){
      console.log('Message Sent');
    }).fail(function(){
      alert("Failed to send Message");
    });

    $(this).closest('.toast').remove();

    return false; // stop form submit prevent page;

});





});




</script>
@endauth