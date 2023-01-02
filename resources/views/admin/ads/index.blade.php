@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {{-- Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>

    	 <div class="box box-primary">
            <div class="box-header with-border">
                @if(\Auth::user()->hasRole('admins'))
                <a class="btn btn-default btn-sm" href="{!! route('admin.ads.create') !!}" title="{{ trans('admin/fiscalyear/general.button.create') }}">
                    <i class="fa fa-plus-square"></i> &nbsp;Add
                </a>
                @endif

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">

                <div class="table-responsive">
    	<table class="table">
    		<thead>
    		<tr>
    			<th>Id</th>
    			<th>Name</th>
    			<th>Image</th>
    			<th>Action</th>
    		</tr>
    		</thead>

    		<tbody>
    			@foreach($ads as $key=>$value)
    			<tr>
    				<td>{{$value->id}}</td>
    				<td>{{$value->name}}</td>
    				<td><img src="/ads/{{$value->attachment}}" style="width: 300px;height: 100px;"></td>
    				<td>
    					
    					@if( $value->isEditable() || $value->canChangePermissions() )
                        <a href="{!! route('admin.ads.edit', $value->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                        
                        @else
                        <i class="fa fa-edit text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-edit-this-FiscalYear') }}"></i>
                        @endif
                        @if( $value->isDeletable() )
                        <a href="{!! route('admin.ads.confirm-delete', $value->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                        @else
                        <i class="fa fa-trash text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-delete-this-FiscalYear') }}"></i>
                        @endif
    				</td>
    			</tr>
    			@endforeach
    		</tbody>

    	</table>
    </div>
</div>
    </div>
</div>

@endsection

