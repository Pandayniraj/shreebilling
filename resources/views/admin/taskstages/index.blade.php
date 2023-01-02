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
	                <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="{{ route('admin.task.stages.create') }}">
	                   <i class="fa fa-plus"></i>&nbsp;&nbsp;<strong>Create</strong>
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
		        <th>Name</th>   
		        <th>Bg Color</th>
		        <th>Action</th>
		    </tr>
		</thead>
		<tbody>
		    @foreach($taskstages as $key=>$att) 

		    <tr>
		        <td>
		            <input type="checkbox" name="event_id" value="{{$att->id}}">
		        </td>
		        <td>{{ $att->id }}</td>
		        <td>{{ $att->name }}</td>
		        <td>{{ $att->bg_color }}</td>
		        <td>
		            <a href="/admin/task/stages/{{$att->id}}/edit"><i class="fa fa-edit"></i></a>
		            <a href="{!! route('admin.task.stages.delete-confirm', $att->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>

		        </td>
		    </tr>

		    @endforeach

      </tbody>
   </table>
 </div>
</div>


@endsection