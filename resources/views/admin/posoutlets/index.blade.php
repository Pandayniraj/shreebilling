@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              All Outlets
                <small>Admin | hotel | POS Outlets Index</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
            <p> All F&B or Non F&B outlets must be added here. You can also assign users access to outlets from here </p>
</section>

<div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
            <b><font size="4">POS Outlets List</font></b>
            <div style="display: inline; float: right;">
                <a class="btn btn-success btn-sm"  title="Create Outlets" href="{{ route('admin.pos-outlets.create') }}">
                   <i class="fa fa-plus"></i>&nbsp;&nbsp;<strong>Add new POS Outlets</strong>
                </a> 
            </div>      
        </div>
</div>
<br/>
<table class="table table-hover table-no-border table-striped table-hover" id="leads-table">
<thead>
    <tr class="bg-olive">
       
        <th>ID</th>
        <th>Outlet Code</th>
        <th>Name</th>
        <th>Short Name</th>
        <th>Outlet Type</th>
       <!--  <th>Menus</th> -->
        <th>Users</th>
        <th>Action</th> 
    </tr>
</thead> 
<tbody>
    @foreach($costcenters as $key=>$att)
     <tr>
        
        <td>{{ $att->id }}</td>
        <td>{{ $att->outlet_code }}</td>
        <td style="font-size: 16.5px">{{ $att->name }}</td>
        <td>{{ $att->short_name }}</td>
        <td>{{ $att->outlet_type }}</td>
        <!-- <td> <a href="/admin/hotel/pos-menu/create"> Add Menu</a></td> -->

        <td> <a href="{{ route('admin.hotel.pos-outlets.adduser',$att->id) }}"> Add User + </a></td>  
        <td>
            @if( $att->isEditable())
            <a href="{{ route('admin.pos-outlets.edit',$att->id) }}"><i class="fa fa-edit"></i></a>
            @else
             <i class="fa fa-pencil-square-o text-muted" title="{{ trans('admin/permissions/general.error.cant-edit-this-permission') }}"></i>
            @endif
            <a href="{!! route('admin.hotel.pos-outlets.confirm-delete', $att->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
        </td>
     </tr>
    @endforeach
</tbody>
</table>
</div>

@endsection