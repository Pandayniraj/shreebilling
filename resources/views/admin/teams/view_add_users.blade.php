@extends('layouts.master')
@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection
@section('content')
{{-- <link href="/select2/css/select2.min.css" rel="stylesheet" /> --}}
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                User List
                <small> User List on Team #{{\Request::segment(4)}}</small>
            </h1>

          {{ TaskHelper::topSubMenu('topsubmenu.hr')}}
          <p>
            {{ $team->name }}
          </p>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
   <div class='row'>
       <div class='col-md-12'>
          <div class="box">
		     <div class="box-body ">
		     	  <table class="table">
              <thead>
                <tr>
                  <th>S.N</th>
                  <th>Username</th>
                  <th>Designation</th>
                  <th>Department</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty($team_users))
                  @foreach($team_users as $key => $tu)
                    <tr>
                      <td>{{++$key}}</td>
                      <td title="{{ $tu->user->first_name }} {{ $tu->user->last_name }}">{{$tu->user->username}}[{{$tu->user_id}}]</td>
                      <td>{{$tu->user->designation->designations}}</td>
                      <td>{{$tu->user->department->deptname}}</td>
                      <td>
                        &nbsp;&nbsp;&nbsp;
                        @if($tu->isDeletable())
                        <a href
                        ="{{route('admin.users.teams.confirm-delete',$tu->id)}}" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash deletable" ></i></a>
                        @else
                           <i class="fa fa-trash text-muted"></i>
                        @endif
                      </td> 
                    </tr>
                  @endforeach
                @else
                <tr>
                  <td colspan="5" style="text-align: center;">No any user on team</td>
                </tr>
                @endif
              </tbody>
            </table>
            <br>
            <form method="post" action="{{ route('admin.users.teams',$team->id) }}">
              {{ csrf_field() }}
              <input type="hidden" name="team_id" value="{{$team->id}}">
              <div class="row">
            <div class="col-md-6">
            <table class="table">

              <thead>
                <tr>
                  <th style="text-align: left;">Add more user on team</th>
                
                </tr>
                <tr>
                  <td>
                    <select name='user_id' class="form-control searchable" required="" style="width: 100%">
                        <option value="">Select User</option>
                        @foreach($users as $k=>$user)
                        <option value="{{$user->id}}">{{$user->username}}[{{$user->id}}]</option>
                        @endforeach
                    </select>
                  </td>
                  <td>
                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                      Add
                    </button>
                  </td>
                  <td>
                    <a href="/admin/teams/" class="btn btn-default btn-sm btn-block">
                        Cancel
                    </a>
                  </td>
                </tr>
              </thead>
              <tbody>
                <tr></tr>
              </tbody>
            </table>
          </div>
        </div>
        </form>
		     </div>
		 </div>
		</div>
    <script type="text/javascript">
      $('.searchable').select2();
    </script>
@endsection