@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>

   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
            <b><font size="4">Customer & Supplier Group Index</font></b>
            <div style="display: inline; float: right;">
            <a class="btn btn-primary btn-sm"  title="Import/Export Folder" href="{{ route('admin.customergroup.create') }}">
                            <i class="fa fa-plus"></i>&nbsp;<strong>Create New Groups</strong>
                        </a> <br/>   
            </div>   

        </div>
</div>



<table class="table table-hover table-no-border table-striped" >
<thead>
    <tr class="bg-info">
       
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Description</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @foreach($customergroups as $key=>$value)
    <tr>
       
        <td>#{{ $value->id }}</td>

        <td style="font-size: 16.5px">{{ $value->name }} ({{\TaskHelper::countInGroup($value->id )}})</td>
         <td>{{  ucfirst($value->type) }}</td>
        <td><a href="#" class="text-muted">{{ $value->description }}</a></td>
        <td>
            @if($value->isEditable())
            <a href="{{ route('admin.customergroup.edit',$value->id) }}"><i class="fa fa-edit editable"></i></a>
            @else
            <a href=""><i class="fa fa-edit text-muted"></i></a>
            @endif
            &nbsp;&nbsp;
             @if($value->isDeletable())
            <a href="{{ route('admin.customergroup.confirm-delete',$value->id) }}" data-toggle="modal" data-target="#modal_dialog" ><i class="fa fa-trash deletable"></i></a>
            @else
            <a href=""><i class="fa fa-trash text-muted"></i></a>
            @endif
        </td>
    </tr>
    @endforeach

</tbody>
</table>
<div align="center">{{ $customergroups->render()  }}</div>
</div>

@endsection