@extends('layouts.master')
@section('content')


   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
            <b><font size="4">Folders Index</font></b>
            <div style="display: inline; float: right;">
            <a class="btn btn-primary btn-sm"  title="Import/Export Folder" href="{{ route('admin.folders.create') }}">
                            <i class="fa fa-plus"></i>&nbsp;<strong>Create New Folders</strong>
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
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($folders as $key=>$value)
	<tr>
        <td><input type="checkbox" name=""></td>
        <td>#{{ $value->id }}</td>
        <td>{{ $value->name }}</td>
        <td><a href="#" class="text-muted">{{ $value->description }}</a></td>
        <td>
            @if($value->isEditable())
            <a href="{{ route('admin.folders.edit',$value->id) }}"><i class="fa fa-edit editable"></i></a>
            @else
            <a href=""><i class="fa fa-edit text-muted"></i></a>
            @endif
            &nbsp;&nbsp;
             @if($value->isDeletable())
            <a href="{{ route('admin.folders.confirm-delete',$value->id) }}" data-toggle="modal" data-target="#modal_dialog" ><i class="fa fa-trash deletable"></i></a>
            @else
            <a href=""><i class="fa fa-trash text-muted"></i></a>
            @endif
        </td>
	</tr>
    @endforeach

</tbody>
</table>
<div align="center">{{ $folders->render()  }}</div>
</div>

@endsection