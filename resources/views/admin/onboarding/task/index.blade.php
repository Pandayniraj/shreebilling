@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                HR Onboarding Event Tasks
                <small>{!! $page_description ?? "event lists" !!}</small>
            </h1>
             <p> List of all the onboarding tasks</p>

           {{ TaskHelper::topSubMenu('topsubmenu.hr')}}

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
            <div style="display: inline; float: right;">
            <a class="btn btn-primary btn-sm"  title="Create new task type" href="/admin/onboard/task/create">
                            <i class="fa fa-plus"></i>&nbsp;<strong>New Task</strong>
                        </a> 
            </div>      
        </div>
</div>

<table class="table table-hover table-no-border" id="leads-table">
<thead>
    <tr class="bg-info">
        <th style="text-align:center;width:20px !important">
            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                <i class="fa fa-check-square-o"></i>
            </a>
        </th>
        <th>ID</th>
        <th>Task Name</th>
        <th>Event Name</th>
        <th>Effective Date</th>
        <th>Due Date</th>
        <th>Owner</th>
        <th>Priority</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($task as $key=>$value)
	<tr>
		<td >
			<input type="checkbox" name="event_id" value="{{$value->id}}">
        </td>
      <td>#{{$value->id}}</td>
      <td><a href="/admin/onboard/task/show/{{$value->id}}">{{$value->name}}</a></td>
      <td><a href="/admin/onboard/events/show/{{$value->event_id}}" target="_blank">{{ucfirst($value->event->name)}}</a></td>
     <td >
        @if($value->effective_date != '0000-00-00'){{date('dS M Y',strtotime($value->effective_date))}} @else -- @endif</td>
      <td>
        @if($value->due_date != '0000-00-00'){{date('dS M Y',strtotime($value->due_date))}} @else -- @endif</td>
      <td><label >{{ucfirst($value->owner->username ?? '')}}</label></td>
      <td><label class="label {{$priority[$value->priority] ?? ''}}">{{ucwords($value->priority)}}</label></td>
      <td>
        @if ( $value->isEditable()  )
        <a href="{!! route('admin.onboard.task.edit', $value->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
        @else
        <i class="fa fa-edit text-muted" title="{{ trans('admin/communication/general.error.cant-edit-this-organization') }}"></i>
        @endif
        &nbsp;
        @if($value->isDeletable())
        <a href="{!! route('admin.onboard.task.confirm-delete', $value->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
        @else
        asdsad
        <i class="fa fa-trash text-muted" title="{{ trans('admin/organization/general.error.cant-delete-this-organization') }}"></i>
        @endif
      </td>
    </tr>
    @endforeach

</tbody>
</table>
<div align="center">{!! $task->render() !!}</div>

</div>

@endsection