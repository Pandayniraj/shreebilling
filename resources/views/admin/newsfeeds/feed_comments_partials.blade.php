
<div class="post clearfix"  id='commentbox-{{ $comment->id }}'>

  <div style="background: #F0F2F5;padding: 0;margin: 0;border-radius: 5px;">
      <div class="user-block">
        <img class="img-circle img-bordered-sm" src="{{ $comment->user->image?'/images/profiles/'.$comment->user->image:$comment->user->avatar }}"alt="User Image" style="height: 28px; width: 28px;margin: 5px;">
            <span class="username">
              <a href="#">{{ $comment->user->first_name }} {{ $comment->user->last_name }}</a>
              @if($comment->isDeletable())
              <a href="javascript::void()" class="pull-right btn-box-tool remove-comment" data-id = '{{ $comment->id }}' data-pid = {{ $comment->news_feeds_id }}><i class="fa fa-times"></i></a>
              @endif
            </span>
        <span class="description" style="color:#999999;line-height: 18.5714px">{{ $comment->createdtime() }}</span>
      </div>
      <!-- /.user-block -->
      <p style="padding-bottom:  5px;padding-left:  5px;">
        &nbsp;{{  $comment->comment }}
      </p>
    </div>
</div>
