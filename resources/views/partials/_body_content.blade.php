<div class="wrapper">

    <!-- Header -->
    @include('partials._body_header')

    <!-- Sidebar -->
    @include('partials._body_left_sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @auth
        
        <?php  $total =   \App\Models\ReadAnnouncement::orderBy('id', 'DESC')->where('announcement_id',isset($announce) ? $announce->announcements_id : null)->where('user_id',\Auth::user()->id)->where('read_announce',1)->first();
            ?>


        @if(is_array($total) && count($total) != 1)
        @if(isset($announce))
        @if($announce->share_with == 'department')
        <?php $users = \App\User::where('departments_id', $announce->department_id)->where('enabled', '1')->pluck('id')->all();  ?>
        @elseif($announce->share_with == 'team')
        <?php 
                $users_id = \App\Models\UserTeam::where('team_id', $announce->team_id)->select('user_id')->get()->toArray();
                $users = \App\User::whereIn('id', $users_id)->where('enabled', '1')->pluck('id')->all();
         ?>
        @else
        <?php   $users = \App\User::orderBy('id', 'desc')->where('enabled', '1')->pluck('id')->all(); ?>
        @endif
        @endauth

        @if(in_array(\Auth::user()->id,$users))
        <div class="bg-danger">
            <div class="row">
                <div class="col-md-12" style="padding-left: 50px">
                    <h1 class="">{{ $announce->title }}</h1>
                    <div class="">
                        <p>{{ $announce->description}} - {{ $announce->user->first_name }} <i>({{ date('dS M y /h:m', strtotime($announce->created_at)) }})</i>
                            <a href="/admin/announcements"> more announcements &rarr; </a>
                            <a class="btn btn-default" href="/admin/close/announcements/{{
                                $announce ? $announce->announcements_id :''}}">Close</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
        @endif

        @if(isset($transfer) && is_array($transfer) && count($transfer) > 0)
        <div class="bg-success">
            <div class="row">
                <div class="col-md-12" style="padding-left: 50px">
                    <h1 class="">Leads has been transferred to you</h1>
                    @foreach($transfer as $k => $v)
                    <span class="lead">
                        <a href="/admin/leads/{{ $v->lead->id }}?type=leads&transfernotify=1">{{ $v->lead->name }}</a>,
                    </span>
                    @endforeach
                    <br /><br />
                </div>
            </div>
        </div>
        @endif

        @if(isset($next_action_query) && is_array($next_action_query) && count($next_action_query) > 0)
        <div class="bg-success">
            <div class="row">
                <div class="col-md-12" style="padding-left: 50px">
                    <h1 class="">Today's Next Action Query</h1>
                    @foreach($next_action_query as $k => $v)
                    <span class="lead">
                        <a href="/admin/leads/{{ $v->lead->id }}?type=customer&query_action_notify=1&query_id={{$v->id}}">{{ $v->lead->name }} for {{$v->course->name}}</a>,
                    </span>
                    @endforeach
                    <br /><br />
                </div>
            </div>
        </div>
        @endif


        <!-- Main content -->
        <section class="content">

            <div class="box-body">
                @include('vendor.flash.message')
                @include('partials._errors')

            </div>
            <!-- Your Page Content Here -->
            @yield('content')

           
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Body Footer -->
    @include('partials._body_footer')
    @include('partials._form_spinner')
    @include('partials._notify_chat')
    @if (\Config::get('settings.app_right_sidebar') )
    <!-- Body right sidebar -->
    @include('partials._body_right_sidebar')
    @endif

</div><!-- ./wrapper -->
