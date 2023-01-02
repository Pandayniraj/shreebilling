@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                HR Onboarding Event Tasks Types
                <small>{!! $page_description ?? "event lists" !!}</small>
            </h1>
             <p>Task types helps creating tasks quickly, they are like task templates</p>

           {{ TaskHelper::topSubMenu('topsubmenu.hr')}}

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
          
            <div style="display: inline; float: right;">
            <a class="btn btn-primary btn-sm"  title="Create new task type" href="/admin/onboard/tasktype/create">
                            <i class="fa fa-plus"></i>&nbsp;<strong>Create New</strong>
                        </a> 
            </div>      
        </div>
</div>

<table class="table table-hover table-no-border" id="leads-table">
<thead>
    <tr class="bg-default">
        <th style="text-align:center;width:20px !important">
            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                <i class="fa fa-check-square-o"></i>
            </a>
        </th>
        <th>ID</th>
        <th>Name</th>
        <th>Owner</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($tasktype as $key=>$value)
	<tr>
		<td >
			<input type="checkbox" name="event_id" value="{{$value->id}}">
        </td>
      <td>#{{$value->id}}</td>
      <td><a href="/admin/onboard/tasktype/show/{{$value->id}}">{{ucfirst($value->name)}}</a></td>
      <td><label class="label btn-success">{{ucfirst($value->owner->username)}}</label></td>
      <td>
        @if ( $value->isEditable()  )
        <a href="{!! route('admin.onboard.tasktype.edit', $value->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
        @else
        <i class="fa fa-edit text-muted" title="{{ trans('admin/communication/general.error.cant-edit-this-organization') }}"></i>
        @endif
        &nbsp;
        @if($value->isDeletable())
        <a href="{!! route('admin.onboard.tasktype.confirm-delete', $value->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
        @else
        asdsad
        <i class="fa fa-trash text-muted" title="{{ trans('admin/organization/general.error.cant-delete-this-organization') }}"></i>
        @endif
      </td>
    </tr>
    @endforeach

</tbody>
</table>
<div align="center">{!! $tasktype->render() !!}</div>

</div>

@endsection