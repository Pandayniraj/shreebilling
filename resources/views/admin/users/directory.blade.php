@extends('layouts.master')
@section('content')
<link rel="stylesheet" type="text/css" href="/bootstrap-iso.css">
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
            Employee Directory
                <small>{!! $page_description !!}</small>
            </h1>
            <p> This is an employee directory.
              Total employee ({{$users_count}}) (<span style="color:green"><i class="fa fa-check"></i> {{$active_users}}</span>)
            </p>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<div class="row">
    <div class='col-md-12'>
            <!-- Box -->
         <form method="get" action="/admin/employee/directory">
                <div class="box box-primary">
                    <div class="box-header with-border">
                       
                        &nbsp;
                        <a class="btn btn-primary btn-sm" href="{!! route('admin.users.create') !!}" title="{{ trans('admin/users/general.button.create') }}">
                            <i class="fa fa-edit"> </i> Add user
                        </a>
                        
                        &nbsp;
                        <a class="btn btn-primary btn-sm" href="{!! route('admin.import-export.index') !!}" title="Import/Export users">
                            <i class="fa fa-download">&nbsp;</i> Import/Export users
                        </a>

                        <select class="btn btn-sm btn-success" id='user_type' name="user_type">
                            <option value="">All user</option>
                            <option value="1" @if(Request::get('type') == 1 ) selected="" @endif>Enabled</option>
                            <option value="2" @if(Request::get('type') == 2 ) selected="" @endif>Resigned</option>
                        </select>

              
                    <div class="col-md-4 col-sm-4 col-lg-4" style="float: right;margin-top: 4px">  
                                  
                            <div class="input-group">
                    <input type="text" class="form-control input-sm" placeholder="Search" name="term" id="terms">

                    <div class="input-group-btn">
                    <button type="button" class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i>&nbsp;Filter</button>
                    </div>
                    &nbsp;
                    <div class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-sm" id="clear"><i class="fa fa-close (alias)"></i>&nbsp; Clear</button>
                    </div>
                    </div>    


                    </div>
                        
                    </div>
                </div>
           </form>
</div>
<div class="bootstrap-iso" style="padding: 15px;">

    <div class="card card-solid" >
        <div class="card-body">
          <div class="row d-flex align-items-stretch p-0">
            @foreach($users as $user)
            <div class="col-md-4 " style="padding-bottom: 10px;">
              <div class="card card-light" >
                <div class="card-header border-bottom-0">
                 {!! $user->department->deptname ?? '' !!} 
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-8">
                      <span style="font-size: 16px"><b><a href="/admin/profile/show/{{$user->id}}">{!! $user->full_name !!} </a></b></span>
                      @if($user->enabled)
                      <span title="Active Employee" style="color:green"><i class="fa fa-check-circle"></i></span>
                      @else
                      <span title="Disabled Employee" style="color:red"><i class="fa  fa-close (alias)"></i></span>


                      @endif

                       <br/><small>{{ $user->designation->designations ?? ''}}</small>
                     <!--  <p class="text-muted text-sm"></p> -->
                      <ul class="ml-4 mb-0 fa-ul text-muted">

                        <li class="small"><span class="fa-li"><i class="fa fa-phone"></i></span> Phone #: <a href="tel:{!! $user->phone !!}">{!! $user->phone !!}</a></li>
                        @if($user->enabled)

                        <li class="small"><span class="fa-li"><i class="fa fa-calendar"></i></span> Join  date: <?php  $joinDate = strtotime($user->userDetail->join_date ?? '');   ?> 
                          {!! $joinDate ? date('dS  M Y',$joinDate): '' !!} </li>
             
                        @else
                        <li class="small"><span class="fa-li"><i class="fa fa-phone"></i></span> Contact End:<?php  $leaveDate = strtotime($user->userDetail->contract_end_date ?? '');   ?> 

                          {!! $leaveDate ? date('dS M Y',$leaveDate) : '' !!}</li>
                          
                        @endif
                        <li class="small"><span class="fa-li"><i class="fa fa-envelope"></i></span> <a href="mailto::{{$user->email}}"> {!! $user->email  !!}</a><br></li>
                    

                      </ul>
                    </div>
                    <div class="col-4 text-center">
                    @if($user->image)
                     <img src="/images/profiles/{{$user->image}}"  alt="{{$user->full_name}}" class="img-circle img-fluid" style="height: 50px; width: 50px;">
                    @else
                      <img src="{{TaskHelper::getAvatarAttribute($user->full_name)}}" alt="" class="img-circle img-fluid" style="height: 50px; width: 50px;">
                    @endif
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                
                  <div class="text-right">
                   <a href="{{ route('admin.attendace.calandar',$user->id) }}" class="btn btn-danger">
                      <i class="fa  fa-calendar"></i>
                    </a>     

                    @if(Auth::user()->hasRole(['admins','hr-staff']) || Auth::user()->id == $user->first_line_manager  )
                  <a href="/admin/users/{{ $user->id }}/detail" class="btn btn-success">
                      <i class="fa fa-user"></i> User Details
                    </a>
                    <a href="/admin/stock/assign_report" class="btn bg-orange">
                      <i class="fa fa-check-circle"></i> Assets
                    </a>

                    <a href="/admin/leavereport?date_type=eng&filter_type=annual_year&user_id={{$user->id}}&leave_years=4" class="btn btn-info">
                      <i class="fa fa-check"></i> Leaves
                    </a>
                    @endif

                    <a href="/admin/profile/show/{{$user->id}}" class="btn bg-blue">
                      <i class="fa fa-bars"></i> Profile
                    </a>
                  </div>
                                            
                </div>
              </div>
            </div>

            @endforeach
          </div>
        </div>

        <div class="card-footer">
            <div align="center">{!! $users->render()  !!}</div>
        </div>
 
 
</div>
</div>
     
    </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


            <!-- Optional bottom section for modals etc... -->
@section('body_bottom')
    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkUser[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
$('#search').click(function(){
            let terms = $('#terms').val();
      let user_type = $('#user_type').val();
  window.location.href = `{!! url('/') !!}/admin/employee/directory?term=${terms}&type=${user_type}`;
        });
        $(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      let terms = $('#terms').val();
      let user_type = $('#user_type').val();
      window.location.href = `{!! url('/') !!}/admin/employee/directory?term=${terms}&type=${user_type}`;
      return false;
    }
  });
});
           $('#clear').click(function(){
              window.location.href = "{!! url('/') !!}/admin/employee/directory";
        });

      $('#user_type').click(function(){

          $('#search').trigger('click');

      });


    </script>
@endsection
