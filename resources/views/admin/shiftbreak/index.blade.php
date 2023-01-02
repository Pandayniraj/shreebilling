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
	                 
	                <a class="btn btn-primary btn-sm btn-social"  title="Import/Export Leads" href="{{ route('admin.shiftsBreaks.create') }}">
	                   <i class="fa fa-plus "></i>&nbsp;&nbsp;<strong>Create Shift Break</strong>
	                </a> 
	            </div>      
	        </div>
       </div>
    </div>


    <div class="box-body">
    	<table class="table table-striped">
    		<thead>
    			<tr>
	    			<th>Id</th>
                    <th>Name</th>
	    			<th>Shift</th>
	    			<th>Start Time</th>
	    			<th>End time</th>
	    			<th>Pay Type</th>
	    			<th>Action</th>
    			</tr>
    		</thead>
    		<tbody>
    			@foreach($shiftbreak as $key=>$sb)
    				<tr>
    					<td>#{{$sb->id}}</td>
                        <td>{{$sb->name}}<i class="{{$sb->icon}}"></i></td>
    					<td>{{$sb->shift->shift_name}}</td>
    					<td>{{date('h:i A',strtotime($sb->start_time))}}</td>
    					<td>{{date('h:i A',strtotime($sb->end_time))}}</td>
    					<td>{{ucwords($sb->pay_type)}}</td>
    					<td>
    					 <a href="{{route('admin.shiftsBreaks.edit',$sb->id)}}"><i class="fa fa-edit"></i></a>
		            	&nbsp;
		            	<a href="{!! route('admin.shiftsBreaks.delete-confirm', $sb->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
		            </td>
    				</tr>
    			@endforeach
    		</tbody>	
    	</table>
    </div>
</div>

@endsection

