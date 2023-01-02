@extends('layouts.master')
@section('content')

<style type="text/css">
 .profile-pic {
 
}

.profile-pic:hover .edit {
  display: block;
}

.edit {
  padding-top: 7px; 
  padding-right: 7px;

  right: 0;
  top: 0;
  display: none;
}

</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ trans('admin/projects/general.columns.project') }}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>

            {{ TaskHelper::topSubMenu('topsubmenu.projects')}}
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>


    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            
                <div class="box box-primary">
                    <div class="box-header with-border">

  @if(\Auth::user()->hasRole('admins'))
                        <a class="btn btn-default btn-sm" href="{!! route('admin.projects.create') !!}" title="{{ trans('admin/projects/general.button.create') }}">
                            <i class="fa fa-plus"></i> {{ trans('admin/projects/general.button.create_project') }}
                        </a>
                      @endif

   <div class="pull-right">
                <form method="GET" action="/admin/projects/search/tasks/">                          
                 <div class="input-group input-group-sm hidden-xs" style="width: 260px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="{{ trans('admin/projects/general.placeholder.search_project') }}">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                </form>

   </div>            

</div>

<div class='row'>
        <div class='col-md-12'>

            <div class="col-xs-8">
          <div class="box">

            <!-- /.box-header -->
            {!! Form::open( array('route' => 'admin.projects.enable-selected', 'id' => 'frmClientList') ) !!}
            <div class="box-body box-primary table-responsive no-padding">
              
              <table class="table table-hover table-striped" id="project_table">
                <tbody>
                  <tr class="bg-danger">
                  <th>{{ trans('admin/projects/general.columns.id') }}</th>
                  <th>{{ trans('admin/projects/general.columns.project') }}</th>
                  <th>{{ trans('admin/projects/general.columns.category') }}</th>
                  <th>{{ trans('admin/projects/general.columns.summary') }}</th>
                  <th></th>
                </tr>


                @foreach($projects as $project)
                <tr class="profile-pic">
                  <td>PR{{$project->id}}</td>
                  <td style="font-size: 17px">{!! link_to_route('admin.projects.show', $project->name, [$project->id], []) !!}</td>
              
                  <td><span class="label {{ $project->class}}">{!! $project->projectcategory->name !!}</span></td>
                  <td>{{$project->tagline}}</td>
                  <td >
                    <span class="edit">
                    @if ( $project->isEditable() || $project->canChangePermissions() )
                        <a class="button1" href="{!! route('admin.projects.edit', $project->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                    @else
                        <i class="fa fa-edit text-muted" title="{{ trans('admin/projects/general.error.cant-edit-this-document') }}"></i>
                    @endif

                    @if ( $project->enabled )
                        <a href="{!! route('admin.projects.disable', $project->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                    @else
                        <a href="{!! route('admin.projects.enable', $project->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                    @endif

                    @if ( $project->isDeletable() )
                        <a href="{!! route('admin.projects.confirm-delete', $project->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                    @else
                        <i class="fa fa-trash text-muted" title="{{ trans('admin/projects/general.error.cant-delete-this-document') }}"></i>
                    @endif
                    <span>
                </td>
                </tr>
                @endforeach


                
              </tbody></table>
            </div>
            <div align="center">{!! $projects->render() !!}</div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>









        </div>
</div>



@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')






    

@endsection
