

@foreach($newsfeeds as $key=>$news)

<style>
  
</style>
 <div class="nav-tabs-custom" id='newsfeedsList-{{$news->id}}'>

            <div class="tab-content"  @if($news->view_level == 'dept')style="background: #90EE90"  @endif>
               <div class="tab-pane active" >
                  <!-- Post -->
                  <div class="post" style="margin-bottom: -3px">
                     <div class="user-block">
                       @php $department = $news->user->department;  @endphp
                      @if($news->view_level == 'dept' && $department )

                        <img class="img-circle img-bordered-sm" src="{{  TaskHelper::getAvatarAttribute($department->deptname ?? '') }}" style="height: 28px; width: 28px;" alt="{{ $news->user->username }}">


                        <span class="username" style="font-size:14px !important;color: #045382;font-weight: 800;">
                        <a href="#">{{  $department->deptname  }}</a>
                        @if($news->isEditable())
                        <a href="javascript::void()" class="pull-right btn-box-tool remove-news"  data-id = '{{$news->id}}'><i class="fa fa-times"></i></a>
                        @endif
                        </span>

                        @else

                         <img class="img-circle img-bordered-sm" src="{{ $news->user->image?'/images/profiles/'.$news->user->image:$news->user->avatar }}" style="height: 28px; width: 28px;" alt="{{ $news->user->username }}">


                        <span class="username" style="font-weight: bold;">
                          @php $newsUser = $news->user;  @endphp
                        <a href="/admin/profile/show/{{ $newsUser->id }}">{{$newsUser->first_name  }} {{$newsUser->last_name  }}</a>
                        @if($news->isEditable())
                        <a href="javascript::void()" class="pull-right btn-box-tool remove-news"  data-id='{{$news->id}}'><i class="fa fa-times"></i></a>
                        @endif
                        </span>


                        @endif

                        <span class="description" style="color:#999999;line-height: 18.5714px; font-weight: bold;"> <small> {{$news->createdtime()  }}   @if($news->view_level == 'dept' && $department && $news->auto_created != '1' && $news->auto_created != '2' ) by {{$news->user->first_name  }} {{$news->user->last_name  }} @endif </small></span>
                     </div>

                     <!-- /.user-block -->
                     <p class="news_posts"><span>

                      @if($news->type == "leads")
                      <span class="material-icons">phone_callback</span>New lead created for  {{ $news->lead->name }} {{ $news->lead->mob_phone }} {{ $news->lead->email }} {{ $news->lead->description }}
                      @endif
                      @if($news->type == "task")
                      Task has been created for  {{ $news->privatetask->task_subject}}
                      @endif
                      @if($news->type == "ptask")
                      <span class="material-icons">add_task</span> {{ $news->ptask->project->name }} task has been created for {{ $news->ptask->peoples}},  {{ $news->ptask->subject}}
                      @endif
                      @if($news->type =="ticket")
                       <span class="material-icons">confirmation_number</span> {{ $news->ticket->source}}  ticket logged {{ $news->ticket->issue_summary}} - {{ $news->ticket->detail_reason}}
                      @endif
                          @if($news->type =="")
                              {!! $news->body  !!}
                            @endif
                      </span>

                     </p>

                     {{-- note dont use interactive_post in css --}}

                     <?php $postFiles = $news->files;
                          $postLikes = $news->getTotalLikes;
                          $lkUser='';
                          foreach ($postLikes as $key => $value) {
                            $lkusr = $value->user;
                            $lkUser  .= $lkusr->first_name .' '.$lkusr->last_name . ', ';

                          }

                      ?>
                     <div class="row">

                    <div class="col-sm-12">
                      <div class="row">
                        @foreach($postFiles as $pi=>$pk)
                        <div class="col-sm-4">
                          <img class="img-responsive" src="/news_feeds/{{ $pk->images }}"
                          alt="Photo" data-enlargable>
                        </div>
                        @endforeach
                      </div>
                    </div>


                  </div>
                     <ul class="list-inline">
                        @if($news->checkLikes())
                         <li><a style="color: #999999" href="javascript::void()" class="link-black text-sm post-likes-unlike"   data-type='unlikes'
                          data-pid='{{$news->id}}'><i class="fa fa-thumbs-o-up margin-r-5"></i> Unlike</a>
                        </li>
                        @else


                        <li><a style="color: #999999" href="javascript::void()" class="link-black text-sm post-likes-unlike" data-type='likes'
                          data-pid='{{$news->id}}' > <i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                        </li>

                        @endif


                        <li class="pull-right">
                           <a  class="link-black text-sm" style="color: #999999"><i class="fa fa-comments-o margin-r-5"></i> <?php $thisnewsComments =  $news->comments; ?>
                           <span  id='{{$news->id}}-comment'> {{ count($thisnewsComments) }}  </span></a>
                        </li>
                        <li class="pull-right">
                           <a href="/admin/post_liker/{{ $news->id }}" class="link-black text-sm" data-toggle="modal" data-target="#modal_dialog"   data-toggle="tooltip" title="{{$lkUser}}" data-html="true" style="color: #999999"><i class="fa fa-thumbs-o-up margin-r-5"></i>
                           <span id='{{$news->id}}-likes'>{{ count($postLikes) }}</span></a>
                        </li>
                     </ul>
                     <div class="form-horizontal">
                    <div class="form-group margin-bottom-none">
                      <div class="col-sm-6">
                          <textarea class="form-control input-sm post-comment" type="text" placeholder="Type a comment..." data-id = '{{$news->id}}'  id ='{{$news->id}}-postComment' rows="1"></textarea>
                      </div>

                      <div class="col-sm-2">
                        <button type="button" class="btn btn-default btn-sm post-comment" data-id = '{{ $news->id }}'>Comment</button>
                      </div>

                    </div>
                  </div>


                  </div>

                    <div id='postCommentsList{{$news->id}}'>
                      @if(count($thisnewsComments) > 4  )
                      <a href="javascript::void" style="line-height:19.9995px;"
                      class="view-all-comments" data-id = '{{ $news->id }}'>View all {{count($thisnewsComments) - 4}} comments</a>
                      @endif
                      @foreach($news->topComments(4) as $key=>$comment)
                        @include('admin.newsfeeds.feed_comments_partials')
                      @endforeach
                    </div>


               </div>
            </div>

         </div>

@endforeach


