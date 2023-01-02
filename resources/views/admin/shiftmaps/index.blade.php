@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title"}} 
                <small> {{ $page_description ?? "Page Description" }}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>


<div class="box box-primary">
    <div class="box-header with-border">  
       <div class='row'>
	        <div class='col-md-12'>
	            <b><font size="4">{{ $page_title ?? "Page Title"}} </font></b>
	            <div style="display: inline; float: right;">
	                <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="{{ route('admin.shift.maps.create') }}">
	                   <i class="fa fa-plus"></i>&nbsp;&nbsp;<strong>Create</strong>
	                </a> 
	                <a class="btn btn-primary btn-sm btn-social"  title="Import/Export Leads" href="{{ route('admin.shift.bulk.index') }}">
	                   <i class="fa fa-plus "></i>&nbsp;&nbsp;<strong>Shift By Project</strong>
	                </a> 
	            </div>      
	        </div>
       </div>
       
       <table class="table table-hover table-no-border" id="leads-table">

		<thead>
		    <tr>
		        <th style="text-align:center;width:20px !important">
		            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
		                <i class="fa fa-check-square-o"></i>
		            </a>
		        </th>
		        <th>ID</th>
		        <th>Shift Name</th>   
		        <th>Map From Date</th>
		        <th>Map To Date</th>
		        <th>Action</th>
		    </tr>
		</thead>
		<tbody>
		    @foreach($shifts as $key=>$att)

		    <tr>
		        <td>
		            <input type="checkbox" name="event_id" value="{{$att->id}}">
		        </td>

		        <td>{{ $att->id }}</td>
		        <td><span class="label label-{{ $att->shift->color }}">{{ $att->shift->shift_name }}</span></td>
		        <td>{{ $att->map_from_date }}</td>
		        <td>{{ $att->map_to_date }}</td>
		        <td>
		            <a href="/admin/shifts/maps/{{$att->id}}/edit"><i class="fa fa-edit"></i></a>
		            
		            <a href="{!! route('admin.shift.maps.delete-confirm', $att->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
		        </td>
		    </tr>

		    @endforeach

      </tbody>
   </table>
 </div>
</div>


@endsection