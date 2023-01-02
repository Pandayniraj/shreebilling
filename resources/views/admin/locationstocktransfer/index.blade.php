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
	                <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="{{ route('admin.location.stocktransfer.create') }}">
	                   <i class="fa fa-plus"></i>&nbsp;&nbsp;<strong>Create</strong>
	                </a>
	            </div>
	        </div>
       </div>

       <table class="table table-hover table-no-border" id="leads-table">

		<thead>
		    <tr class="bg-gray">

		        <th>TRID</th>
		        <th>Transfer Date</th>
		        <th>Source Location</th>
		        <th>Destination Location</th>
		        <th>Comments</th>
		        <th>Transfer Status</th>
		        <th>Tools</th>
		        <th>Action</th>
		    </tr>
		</thead>
		<tbody>
		    @foreach($locationstocktransfer as $key=>$att)

		    <tr>


		        <td>00{{ $att->id }}</td>
		        <td><a href="/admin/location/stocktransfer/{{$att->id}}" style="font-size: 16.5px;">{{ $att->transfer_date }}</a></td>
		        <td>{{ $att->source->name }}</td>
		        <td>{{ $att->destination->name }}</td>
		        <td>{{ $att->comment }}</td>
		        <td>{{ $att->status }}</td>
		         <td>
                    <a href="/admin/location/stocktransfer/print/{{$att->id}}" target="_blank" title="print"><i class="fa fa-print"></i></a>
                    <a href="/admin/location/stocktransfer/pdf/{{$att->id}}" title="download"><i class="fa fa-download"></i></a>
                </td>
		        <td>

		            <a href="/admin/location/stocktransfer/{{$att->id}}/edit"><i class="fa fa-edit"></i></a>

		            <a href="{!! route('admin.location.stocktransfer.confirm-delete', $att->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>

		        </td>
		    </tr>

		    @endforeach

      </tbody>
   </table>
 </div>
</div>


@endsection
