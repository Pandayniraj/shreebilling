@extends('layouts.master')

@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />

<style>
      select { width:160px !important; }
</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Staff Roles & Resources Management Center
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
           {{ TaskHelper::topSubMenu('topsubmenu.hr')}}

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.users.enable-selected', 'id' => 'frmUserList') ) !!}
                <div class="box box-primary">
                <div class="box-header with-border">
                   
                    &nbsp;
                    <a class="btn btn-default btn-sm" href="{!! route('admin.users.create') !!}" title="{{ trans('admin/users/general.button.create') }}">
                        <i class="fa fa-user-plus"></i> New
                    </a>
                    &nbsp;
                    <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmUserList'].action = '{!! route('admin.users.enable-selected') !!}';  document.forms['frmUserList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                        <i class="fa fa-check-circle-o"></i>
                    </a>
                    &nbsp;
                    <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmUserList'].action = '{!! route('admin.users.disable-selected') !!}';  document.forms['frmUserList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                        <i class="fa fa-ban"></i>
                    </a>
                    &nbsp;&nbsp;
                    <a class="btn btn-primary btn-sm" href="{!! route('admin.users.importexport.index') !!}" title="Import/Export Users">
                        <i class="fa fa-download"></i>&nbsp;<strong> Import/Export</strong>
                    </a>

                    <a class="btn btn-success btn-sm" href="#"  onclick='openReportOption()'>
                        User Report
                    </a>
                          
                   
                    <div class="col-md-4 col-sm-4 col-lg-4" style="float: right;margin-top: 4px">  
                                  
                        <div class="input-group">
                            <input type="text" class="form-control input-sm" placeholder="Search users" name="search" id="terms" value="{{\Request::get('term')}}">

                        <div class="input-group-btn">
                        <button type="button" class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i>
                            &nbsp;Filter
                        </button>
                        </div>
                        <div class="input-group-btn">
                        <button type="button" class="btn btn-danger btn-sm" id="clear"><i class="fa fa-close (alias)"></i>
                            &nbsp; 
                        Clear</button>
                        </div>
                    </div>
                    </div>
                  
                        <div class="col-md-2 col-sm-2 col-lg-2" style="float: right;margin-top: 5px;margin-right: -25px"> 
                        <select class = 'form-control searchable select2' id="deginations" onchange="searchUser()">
                            <option value="">Select deginations</option>
                            @if(Request::get('dep'))
                            @foreach($designations->where('departments_id',Request::get('dep')) as $deg)
                                <option value="{{$deg->designations_id }}" @if(\Request::get('deg') == $deg->designations_id) selected @endif>{{$deg->designations}}</option>
                            @endforeach
                         
                            @endif
                        </select>
                        </div>

                           <div class="col-md-2 col-sm-2 col-lg-2" style="float: right;margin-top: 5px;margin-right: -25px"> 
                        <select class = 'form-control searchable select2' id="departments" >
                            <option value="">Select department</option>
                            @foreach($departments as $dep)
                            <option value="{{$dep->departments_id}}" @if(\Request::get('dep') == $dep->departments_id) selected @endif>{{$dep->deptname}}</option>
                            @endforeach
                        </select>
                        </div>
                        
                </div>
                <div class="box-body">
                     <span id="index_users_ajax_status"></span>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th style="text-align: center">
                                        <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                            <i class="fa fa-check-square-o"></i>
                                        </a>
                                    </th>
                                   
                                    <th>{{ trans('admin/users/general.columns.username') }}</th>
                                    <th>{{ trans('admin/users/general.columns.email') }}</th>
                                    <th> Phone</th>
                                    
                                    <th> Department</th>
                                    <th>Designation</th>
                                
                                    <th>{{ trans('admin/users/general.columns.actions') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="text-align: center">
                                        <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                            <i class="fa fa-check-square-o"></i>
                                        </a>
                                    </th>
                                   
                                    <th>{{ trans('admin/users/general.columns.username') }}</th>
                                    <th>{{ trans('admin/users/general.columns.email') }}</th>
                                    <th> Phone</th>
                                    
                                    <th>Department</th>
                                    <th>Designation</th>
                              
                                    <th>{{ trans('admin/users/general.columns.actions') }}</th>
                                </tr>
                            </tfoot>
                            <tbody id='userlist'>
                                @foreach($users as $user)
                                    <tr>
                                        <td align="center">
                                            @if ($user->canBeDisabled())
                                                {!! Form::checkbox('chkUser[]', $user->id); !!}
                                            @endif
                                        <input type="hidden" class="user_id" value="{{$user->id}}">
                                        </td>
                                       
                                        <td title="{{ $user->full_name }}">{!! link_to_route('admin.users.show', $user->username, [$user->id], []) !!}</td>
                                    
                                        <td title="{{$user->email}}"><a href="mailto:{{$user->email}}">{{ mb_substr($user->email,0,9) }}..</a></td>
                                        <td><a href="tel:{{ $user->phone }}">{{ $user->phone }}</a></td>
                                        
                                        <td title="" > 
                                        <select class = 'searchable departments' >
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dep)
                                        <option value="{{$dep->departments_id}}" @if($user->departments_id == $dep->departments_id) selected @endif>{{$dep->deptname}}</option>
                                        @endforeach
                                        </select>
                                        </td>
                                        <td >
                                        <select class = 'searchable designations' style="width:150px">
                                        <option value="">Select Designations</option>
                                        @foreach($designations->where('departments_id',$user->departments_id) as $deg)
                                        <option value="{{$deg->designations_id}}" @if($user->designations_id == $deg->designations_id) selected @endif>{{$deg->designations}}</option>
                                        @endforeach
                                        </select>
                                        </td>
                                       {{--  <td>
                                        <select class = 'searchable payroll_method' >
                                        <option value="">Select Pay Method</option>
                                        @foreach(array('wm'=>'Web & Mobile','b'=>'Biometric','t'=>'TimeSheet') as $key=>$pay)
                                        <option value="{{$key}}" @if($user->payroll_method == $key) selected @endif>{{$pay}}</option>
                                        @endforeach
                                        </select>
                                        </td> --}}
                                        <td>
                                            <a href="{!! route('admin.users.edit', $user->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-pencil-square-o"></i></a>

                                            @if ($user->canBeDisabled())
                                                @if ( $user->enabled )
                                                    <a href="{!! route('admin.users.disable', $user->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle-o enabled"></i></a>
                                                @else
                                                    <a href="{!! route('admin.users.enable', $user->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif
                                            @else
                                                    <i class="fa fa-ban text-muted" title="{{ trans('admin/users/general.error.cant-be-disabled') }}"></i>
                                            @endif

                                            @if ( $user->isDeletable() )
                                                <a href="{!! route('admin.users.confirm-delete', $user->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
                                            @else
                                                <i class="fa fa-trash-o text-muted" title="{{ trans('admin/users/general.error.cant-be-deleted') }}"></i>
                                            @endif
                                            <?php 
                                                $user_detail = \App\Models\UserDetail::where('user_id',$user->id)->first();
                                             ?>
                                            @if(count($user_detail)>0)
                                               <a href="/admin/users/{{$user->id}}/detail/{{$user_detail->id}}/edit" title="Details"><i class="fa fa-user-plus"></i></a>
                                            @else
                                             <a href="/admin/users/{{ $user->id }}/detail" title="Details"><i class="fa fa-user-plus"></i></a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! $users->appends(Request::except('page'))->render() !!}
                    </div> <!-- table-responsive -->

                </div><!-- /.box-body -->
            </div><!-- /.box -->
            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection
<style type="text/css">
    .custom-control-label{
        cursor: pointer;
    }
    ul.list-group.list-group-striped li:nth-of-type(odd){
    background: #e5e5e5e5;
}
ul.list-group.list-group-striped li:nth-of-type(even){
    background: white;
}
ul.list-group.list-group-hover li:hover{
    background:  #e5e5e5e5;
}
</style>
<div class="modal fade" id="smallReportOption" role="dialog">
    <div class="modal-dialog modal-sm">
         <form method="get" action="{{route('admin.users.report.gni')}}">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Report Option</h4>
        </div>
        <div class="modal-body" style="margin : 0; padding: 0;font-size: small;max-height: 500px;overflow-y: scroll;">
           
        <ul class="list-group list-group-flush list-group-striped" style="margin : 0; padding: 0;">

    @foreach(TaskHelper::getReportFields('user') as $key=>$field)
    <li class="list-group-item">
      <!-- Default checked -->
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="user[]" value="{{$field}}"  id="field-{{  $field }}">
        <label class="custom-control-label" for="field-{{  $field }}">&nbsp;{{$field}}</label>
      </div>
    </li>
    @endforeach

    @foreach(TaskHelper::getReportFields('user_details') as $key=>$field)
    <li class="list-group-item">
      <!-- Default checked -->
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="user_details[]" value="{{$field}}"
        id="field-{{  $field }}">
        <label class="custom-control-label" for="field-{{  $field }}">&nbsp;{{$field}}</label>
      </div>
    </li>
    @endforeach

       @foreach(TaskHelper::getReportFields('computed') as $key=>$field)
    <li class="list-group-item">
      <!-- Default checked -->
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="computed[]" value="{{$field}}"  id="field-{{  $field }}">
        <label class="custom-control-label" for="field-{{  $field }}">&nbsp;{{$field}}</label>
      </div>
    </li>
    @endforeach

  </ul>
 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary no-loading" >Filter</button>
        </div>
      </div>
    </div>
     </form>
  </div>


            <!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
    <script language="JavaScript">
function toggleCheckbox() {
    checkboxes = document.getElementsByName('chkUser[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = !checkboxes[i].checked;
    }
}
function searchUser(){

    let terms = $('#terms').val();
    let designations = $('#deginations').val();
    let departments = $('#departments').val();
    window.location.href = "{!! url('/') !!}/admin/users?term=" + terms + `&dep=${departments}&deg=${designations}`;



}


$('#terms , #designations , #departments').on('change',function(){

        searchUser();


});


$('#search').click(function() {
    searchUser();
});


    


$(document).ready(function() {
    $(window).keydown(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            let terms = $('#terms').val();
            let designations = $('#deginations').val();
            let departments = $('#departments').val();
            window.location.href = "{!! url('/') !!}/admin/users?term=" + terms + `&dep=${departments}&deg=${designations}`;
            return false;
        }
    });
});
$('#clear').click(function() {
    window.location.href = "{!! url('/') !!}/admin/users";
})
$('#departments').select2();
$('#deginations').select2();
$('.searchable').select2({ width: 'resolve' });
function changeRequestedField(user_id,value,type){
    //alert("DONE");
     $.post("/admin/users/ajax_user_update",
      {id: user_id, update_value: value,type:type ,_token: $('meta[name="csrf-token"]').attr('content')},
      function(data){
        if(data.success == '1')
            $("#index_users_ajax_status").after("<span style='color:green;' id='index_status_update'>"+type+" is successfully updated.</span>");
        else
            $("#index_users_ajax_status").after("<span style='color:red;' id='index_status_update'>Problem in updating status; Please try again.</span>");

        $('#index_status_update').delay(3000).fadeOut('slow');
        //alert("Data: " + data + "\nStatus: " + status);
      });
}
$('#userlist .projects').change(function(){
    let user_id = $(this).parent().parent().find('.user_id').val();
    changeRequestedField(user_id,$(this).val(),'projects');
});
$('#userlist .departments').change(function(){
    let user_id = $(this).parent().parent().find('.user_id').val();
    var designations_select =  $(this).parent().parent().find('.designations');
    $.get(`/admin/users/ajax/GetDesignation?departments_id=${$(this).val()}`,function(response){
        designations_select.html(response.data);
    })
    changeRequestedField(user_id,$(this).val(),'departments');
});
$('#userlist .designations').change(function(){
    let user_id = $(this).parent().parent().find('.user_id').val();
    changeRequestedField(user_id,$(this).val(),'designations');
});
$('#userlist .payroll_method').change(function(){
    let user_id = $(this).parent().parent().find('.user_id').val();
    changeRequestedField(user_id,$(this).val(),'payroll_method');
});


function openReportOption(){

    $('#smallReportOption').modal('show');



}

</script>
@endsection
