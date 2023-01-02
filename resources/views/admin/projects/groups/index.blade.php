@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
           	{!!  $project_name  !!}
                <small>Project Groups</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
            <b><font size="4"><a href="/admin/projects/{{ \Request::segment(3) }}" class="btn btn-primary btn-xs">Back</a></font></b>
            <div style="display: inline; float: right;">
            <a class="btn btn-social btn-primary btn-sm"  title="Create New Bank" href="{{route('admin.projects.group.create',\Request::segment(3))}}">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;<strong>Create New group</strong>
                        </a>
            </div>
        </div>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($groups as $element)
        <tr>
        <td>#{{ $element->id }}</td>
         <td>{{ $element->name }}</td>
          <td><a href="#" class="text-muted"> {{ $element->description }}</a></td>
          <td>
        @if ($element->isEditable())
            <a href="/admin/projectsgroups/edit/{{\Request::segment(3)}}/{{$element->id}}"><i class="fa fa-edit editable"></i></a>
        @else
            <a href="#"><i class="fa fa-edit text-muted"></i></a>
        @endif

        @if(in_array($element->name, ['new','ongoing','completed']))
        <a href="#" title="Always Enabled"><i class="fa fa-check-circle-o enabled"></i></a>
        @elseif ( $element->enabled )
        <a href="{!! route('admin.projects.group.enabledisable', $element->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle-o enabled"></i></a>
        @else
        <a href="{!! route('admin.projects.group.enabledisable', $element->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
        @endif

          </td>
        </tr>
        @endforeach
      
    </tbody>
</table>
@endsection