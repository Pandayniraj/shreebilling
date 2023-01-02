@extends('layouts.master')
@section('content')


   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
            <b><font size="4">Employee Request Index</font></b>
            <div style="display: inline; float: right;">
            <a class="btn btn-primary btn-sm"  title="Import/Export Request" href="{{ route('admin.employeeRequest.create') }}">
                            <i class="fa fa-plus"></i>&nbsp;<strong>Create New Request</strong>
                        </a> 
            </div>      
        </div>
</div>

<table class="table table-hover table-no-border" >
<thead>
    <tr>
        <th style="text-align:center;width:20px !important">
            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                <i class="fa fa-check-square-o"></i>
            </a>
        </th>
        <th>Title</th>
        <th>Requested By</th>
        <th>Request Type</th>
        <th>Request Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($empRequest as $key=>$value)
	<tr>
        <td><input type="checkbox" name=""></td>
        <td>{{ $value->title }}</td>
        <td title='{{$value->user->first_name}} {{$value->user->last_name}}'>{{ $value->user->username }}[{{ $value->user->id }}]</td>
        <td><a href="#" class="text-muted">{{ $request_types[$value->request_type] }}</a></td>
        <td>{{ date('dS M Y',strtotime($value->created_at)) }}</td>
        <td>
            <label class="label {{ $status_color[$value->status] }}">
                {{ $request_status[$value->status] }}
            </label>
            
        </td>
        <td>
            <a href="{{route('admin.employeeRequest.accept_reject',$value->id)}}" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="View">
                <span class="fa fa-list-alt"></span>
            </a> 
             &nbsp;&nbsp;
            @if($value->isEditable())
            <a href="{{ route('admin.employeeRequest.edit',$value->id) }}" class="btn btn-warning btn-xs"><i class="fa fa-edit editable"></i></a>
            @else
            <a href="#" class="btn btn-warning btn-xs"><i class="fa fa-edit text-muted" ></i></a>
            @endif
            &nbsp;&nbsp;
             @if($value->isDeletable())
            <a href="{{ route('admin.employeeRequest.confirm-delete',$value->id) }}" data-toggle="modal" data-target="#modal_dialog" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
            @else
            <a href=""><i class="fa fa-trash text-muted"></i></a>
            @endif
        </td>
	</tr>
    @endforeach

</tbody>
</table>
<div align="center">{{ $empRequest->render()  }}</div>
</div>

@endsection