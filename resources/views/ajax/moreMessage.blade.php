
<style type="text/css">
  .talkDeleteMoreMessage{
    display: none;
  }
</style>
@foreach($messages as $message)
    @if($message->sender->id == auth()->user()->id)

      <div class="chatbox darker"  id="message-{{ $message->id}}"  style="float: right;margin-right: 10px">               
        <img src="{{ \TaskHelper::getProfileImage($message->sender->id ) }}" alt="Avatar" class="right" style="width:100%;">
        <p>{{$message->message}}</p>

      <span class="time-left">{{date('dS Y M',strtotime($message->created_at)) }} </span>
  <a href='javascript::void()' style="float: left;" class="talkDeleteMoreMessage" data-message-id="{{  $message->id }}" data-toggle="tooltip" title="Delete">&nbsp;&nbsp;<i class="fa  fa-trash-o"></i></a>
    </div>
    @else
     <div class="chatbox" id="message-{{ $message->id}}" style="float: left;">
    <img src="{{ \TaskHelper::getProfileImage($message->sender->id ) }}" alt="Avatar"  style="width:100%;">
      <p> {{$message->message}}</p>
       <a href='javascript::void()' data-message-id="{{  $message->id }}" style="float: right;" class="talkDeleteMoreMessage" data-toggle="tooltip" title="Delete" >&nbsp;&nbsp;<i class="fa  fa-trash-o"></i></a>
      <span class="time-right" >{{date('dS M',strtotime($message->created_at)) }}  </span>

    </div>
@endif  
@endforeach


<script type="text/javascript">
  

$('.chatbox').mouseenter(function(){
  $(this).find('.talkDeleteMoreMessage').show();
}).mouseleave(function(){
  $(this).find('.talkDeleteMoreMessage').hide();
});

</script>