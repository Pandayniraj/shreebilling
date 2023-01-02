<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{!! env('SHORT_NAME') !!}  | {{ $page_title ?? "Page Title" }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/adminlte3/plugins/fontawesome-free/css/all.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/adminlte3/dist/css/adminlte.min.css">


  <script src="/adminlte3/plugins/jquery/jquery.min.js"></script>
  <link href="/adminlte3/plugins/jquery-ui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="{{ asset(elixir('js/all.js')) }}"></script>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
 @include('partials3._head')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('partials3._left_sidebar')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      @yield('pageheader')
     
    </div>
    <!-- /.content-header -->

     <?php  $total =   \App\Models\ReadAnnouncement::orderBy('id', 'DESC')
                                    ->where('announcement_id',$announce->announcements_id)
                                    ->where('user_id',\Auth::user()->id)
                                    ->where('read_announce',1)
                                    ->first();

          

            ?> 
                @if(count($total) != 1)
                @if(isset($announce))
                <div class="bg-danger">
                    <div class="row">
                        <div class="col-md-12" style="padding-left: 50px">
                            <h1 class="">{{ $announce->title }}</h1>
                            <div class="">
                                <p>{{ $announce->description}} - {{ $announce->user->first_name }} <i>({{ date('dS M y /h:m', strtotime($announce->created_at)) }})</i> 

                                    <a href="/admin/announcements"> more announcements &rarr; </a>
                                    <a class="btn btn-default" href="/admin/close/announcements/{{$announce->announcements_id}}">Close</a> 

                                </p>
                               
                            </div>
                        </div>
                      
                     </div>
                </div>
                @endif
                @endif



                @if(count($transfer) > 0)
                <div class="bg-success">
                    <div class="row">
                        <div class="col-md-12" style="padding-left: 50px">
                            <h1 class="">Leads has been transferred to you</h1> 
                            @foreach($transfer as $k => $v)
                               <span class="lead"> 
                                <a href="/admin/leads/{{ $v->lead->id }}?type=leads&transfernotify=1">{{ $v->lead->name }}</a>, 
                                 </span>
                            @endforeach
                            <br/><br/>
                        </div>
                     </div>
                </div>
                @endif

                @if(count($next_action_query) > 0)
                <div class="bg-success">
                    <div class="row">
                        <div class="col-md-12" style="padding-left: 50px">
                            <h1 class="">Today's Next Action Query</h1> 
                            @foreach($next_action_query as $k => $v)
                               <span class="lead"> 
                                <a href="/admin/leads/{{ $v->lead->id }}?type=customer&query_action_notify=1&query_id={{$v->id}}">{{ $v->lead->name }} for {{$v->course->name}}</a>, 
                                 </span>
                            @endforeach
                            <br/><br/>
                        </div>
                     </div>
                </div>
                @endif

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

           <div class="box-body">
                @include('flash::message')
                @include('partials3._errors')
            </div>


        @yield('content')
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
    @include('partials3._modals')
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->

  <!-- Main Footer -->


<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
  @include('partials3._footer')

<!-- jQuery -->


<!-- Bootstrap 4 -->
<script src="/adminlte3/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="/adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/adminlte3/dist/js/adminlte.min.js"></script>
    
@yield('body_bottom')
</body>
</html>
