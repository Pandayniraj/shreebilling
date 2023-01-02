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
	            <b><font size="4">Requisition forms </font></b>
	            <div style="display: inline; float: right;">
	                <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="{{ route('admin.requisition.create') }}">
	                   <i class="fa fa-plus"></i>&nbsp;&nbsp;<strong>Create</strong>
	                </a>
	            </div>
	        </div>
       </div>

       <table class="table table-hover table-no-border" id="leads-table">

		<thead>
		    <tr class="bg-info">
		        
		        <th>ID</th>
		        <th>Department</th>
		        <th>Date</th>
		       
                <th>Status</th>
		        <th>Action</th>
		    </tr>
		</thead>
		<tbody>
		    @foreach($requisitions as $key=>$att)

		    <tr>
		      

		        <td>{{ $att->id }}</td>
		        <td>{{ $att->department->deptname }}</a></td>
		        <td>{{ $att->req_date }}</td>
		       
                <td>
                    @if(!$att->approved_by)
                        <p class="label label-info">Pending</p>
                    @else
                        <p class="label label-success">Approved</p>
                        @endif
		        </td>
                <td>
                    @if(!$att->approved_by)
                    <a href="/admin/requisition/{{$att->id}}/edit?approve"><i title="click to approve" class="fa fa-check-circle fa-colour-blue"></i></a>
                    @endif
                    @if($att->approved_by)
                    <a><i class="fa fa-check-circle"></i></a>
                    @endif
                    <a href="/admin/requisition/{{$att->id}}/edit"><i class="fa fa-edit"></i></a>
                    <a href="{!! route('admin.requisition.confirm-delete', $att->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>

                </td>
		    </tr>

		    @endforeach

      </tbody>
   </table>
 </div>
</div>


@endsection
