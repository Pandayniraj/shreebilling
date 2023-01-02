@extends('layouts.chatmaster')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Talk Messages
                <small>Scroll bottom to write messages</small>
            </h1>
        </section>
​
      <div class="container clearfix body">
   @include('partials.peoplelist')
    
    <div class="chat">
      <div class="chat-header clearfix">
        @if(isset($user))
            <img src="{{@$user->avatar}}" alt="avatar" />
        @endif
        <div class="chat-about">
            @if(isset($user))
                <div class="chat-with">{{$user->first_name}}&nbsp;{{$user->last_name}}</div>
            @else
                <div class="chat-with">No Thread Selected</div>
            @endif
        </div>
        <i class="fa fa-star"></i> 
      </div> <!-- end chat-header -->
      
      @yield('content1')
      
      <div class="chat-message clearfix">
      <form action="" method="post" id="talkSendMessage">
            <textarea name="message-data" id="message-data" placeholder ="Type your message" rows="3" class="SeenMessage" data-user-id="{{$message->user-id}}"
              data-conversation-id="{{$message->conversation_id}}"></textarea>
            <input type="hidden" name="_id" value="{{@request()->route('id')}}">
            <button type="submit" >Send</button>
      </form>
​
      </div> <!-- end chat-message -->
      
    </div> <!-- end chat -->
    
  </div> <!-- end container -->
​
​
      <script>
          var __baseUrl = "{{url('/')}}"
      </script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.0/handlebars.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js'></script>
​
​
​
        <script src="{{asset('chat/js/talk.js')}}"></script>
​
    <script>
        var show = function(data) {
            alert(data.sender.name + " - '" + data.message + "'");
        }
        var msgshow = function(data) {
            var html = '<li id="message-' + data.id + '">' +
            '<div class="message-data">' +
            '<span class="message-data-name"> <a href="#" class="talkDeleteMessage" data-message-id="' + data.id + '" title="Delete Messag"><i class="fa fa-close" style="margin-right: 3px;"></i></a>'  + '</span>' +
            '<span class="message-data-time">1 Second ago</span>' +
            '</div>' +
            '<div class="message my-message">' +
            data.message +
            '</div>' +
            '</li>';

            $('#talkMessages').append(html);
        }
    </script>
    {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['msgshow']]]) !!}
​
@endsection