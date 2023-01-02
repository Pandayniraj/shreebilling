
<style type="text/css">
   .popover-title{
    display: none;
}
.popover-content{
  width: 0;
  height: 0;
  margin: 0;
  padding: 0;
}
.hoverlink:hover{
  text-decoration: underline;
}
/*================================
Filter Box Style
====================================*/
.job-box-filter label {
    width: 100%;
}

.text-right{
text-align:right;
}
.job-box-filter {
    padding: 5px 5px;
    background: #ffffff;
    border-bottom: 1px solid #e8eef1;
    margin-bottom: 10px;
}
.job-box {
    background: #ffffff;
    display: inline-block;
    width: 100%;
    border: 1px solid #e8eef1;
}
.job-box-filter a.filtsec {
    margin-top: 8px;
    display: inline-block;
    margin-right: 15px;
    padding: 4px 10px;
    font-family: 'Quicksand', sans-serif;
  transition: all ease 0.4s;
    background: #edf0f3;
    border-radius: 50px;
    font-size: 13px;
    color: #81a0b1;
    border: 1px solid #e2e8ef;
}
.job-box-filter a.filtsec.active {
    color: #ffffff;
    background: #16262c;
  border-color:#16262c;
}
.job-box-filter a.filtsec i {
    color: #03A9F4;
    margin-right: 5px;
}
.job-box-filter a.filtsec:hover, .job-box-filter a.filtsec:focus {
    color: #ffffff;
    background: #07b107;
    border-color: #07b107;
}
.job-box-filter a.filtsec:hover i, .job-box-filter a.filtsec:focus i{
color:#ffffff;
}
.job-box-filter h4 i {
    margin-right: 10px;
}

/*=====================================
Inbox Message Style
=======================================*/
.inbox-message ul {
    padding: 0;
    margin: 0;
}
.inbox-message ul li {
    list-style: none;
    position: relative;
    padding: 8px 8px;
  border-bottom: 1px solid #e8eef1;
}
.inbox-message ul li:hover, .inbox-message ul li:focus {
    background: #eff6f9;
}
.inbox-message .message-avatar {
    position: absolute;
    left: 30px;
    top: 50%;
    transform: translateY(-50%);
}
.message-avatar img {
    display: inline-block;
    width: 54px;
    height: 54px;
    border-radius: 50%;
}
.inbox-message .message-body {
    margin-left: 85px;
    font-size: 15px;
    color:#62748F;
}
.message-body-heading h5 {
    font-weight: 600;
  display:inline-block;
    color:#62748F;
    margin: 0 0 7px 0;
    padding: 0;
}
.message-body h5 span {
    border-radius: 50px;
    line-height: 14px;
    font-size: 12px;
    color: #fff;
    font-style: normal;
    padding: 4px 10px;
    margin-left: 5px;
    margin-top: -5px;
}
.message-body h5 span.unread{
  background:#07b107; 
}
.message-body h5 span.important{
  background:#dd2027; 
}
.message-body h5 span.pending{
  background:#2196f3; 
}
.message-body-heading span {
    float: right;
    color:#62748F;
    font-size: 14px;
}
.messages-inbox .message-body p {
    margin: 0;
    padding: 0;
    line-height: 27px;
    font-size: 15px;
}


</style>
 <div class="container" style="margin-left: -100px;">
<div class="row">
<div class="col-md-8">
  <div class="chat_container">
    <div class="job-box">
        <div class="job-box-filter">
        <div class="row">
          <div class="col-md-6 col-sm-6">
          <label> {{ trans('admin/chat/general.heading.recent_chat') }}</label> 
          </div>
          <div class="col-md-6 col-sm-6">
            <div class="filter-search-box text-right">
            <a href="#" onclick="$('#chatpopover').popover('hide')"><i class="fa fa-close"></i></a>
            </div>
          </div>
        </div>
      </div>
      <div class="inbox-message">
        <ul>
          @foreach($threads as $inbox)
             @if(!is_null($inbox->thread))
          <li @if( $inbox->thread->sender->id != \Auth::user()->id && $inbox->thread->is_seen == '0') class="bg-info" @endif>
            <a href="{{route('message.read', ['id'=>$inbox->withUser->id])}}">
              <div class="message-avatar" style="margin-left: -10px">
                <img src="{{$inbox->withUser->avatar}}" alt="">
              </div>
              <div class="message-body">
                <div class="message-body-heading">
                  <h5>{{$inbox->withUser->first_name}}&nbsp;{{$inbox->withUser->last_name}}</h5>
                  <span>{{  $inbox->thread->humans_time}}</span>
                </div>
                <p>
                  @if($inbox->thread->attachment)
                    @if($inbox->thread->att_type == 'img')
                      <i class="fa  fa-file-image-o"></i> images
                    @else
                      <i class="fa  fa-paperclip"></i> attachment
                    @endif
                  @else
                  {{substr($inbox->thread->message, 0, 20)}} 
                  @endif
                  <span class="chat_date"> @if(auth()->user()->id == $inbox->thread->sender->id)
                            <i class="fa fa-reply" style="font-size: smaller;"></i>
                        @endif
                      </span>

                </p>
              </div>
            </a>
          </li>
          @endif
          @endforeach
        </ul>
      </div>
      <div class="job-box-filter">
        <div class="row">
          <div class="col-md-6 col-sm-6">
          <a href="/admin/talk/" class='hoverlink' > {{ trans('admin/chat/general.heading.see_all') }}</a> 
          </div>
          <div class="col-md-6 col-sm-6">
    
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<script type="text/javascript">
  $(window).click(function(){
    $('#chatpopover').popover('hide');
  })
</script>