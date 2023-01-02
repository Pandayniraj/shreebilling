@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title ?? "Page Title" !!}

                <small>{!! $page_description ?? "Page Description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
            <b><font size="4">Event List</font></b>
            <div style="display: inline; float: right;">
            <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="{{ route('add-venue') }}">
                            <i class="fa fa-check"></i>&nbsp;<strong>Add Event Venue</strong>
                        </a> 
            </div>      
        </div>
</div>
<table class="table table-hover table-no-border" id="leads-table">
<thead>
    <tr>
        
        <th>ID</th>
        <th>Venue name</th>
        <th>Venue facilities</th>
        <th>Other details</th>
        <th>Owner</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($venue as $key=>$v)
	<tr>
		
        <td>{{$v->id}}</td>
        <td>{{$v->venue_name}}</td>
        <td>{{$v->venue_facilities}}</td>
        <td >{{$v->other_details}}</td>
        <td>{{ucfirst(trans($v->username))}}</td>
        <?php 
         $datas = '<a href="'.route('confirm-delete-venue', $v->id).'?type='.\Request::get('type').'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fa fa-trash-o deletable"></i></a>';
         ?>
        <td><a href="/admin/edit-venue/{{$v->id}}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;<?php echo $datas ?></td>
    </tr>
    @endforeach

</tbody>
</table>
<div align="center">{!! $venue->render() !!}</div>
</div>

@endsection