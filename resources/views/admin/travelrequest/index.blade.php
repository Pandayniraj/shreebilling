@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{$page_title ?? "Page Title"}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.hr')}}
         

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
            <b><font size="4">{{$page_title ?? "Page Title"}}</font></b>
            <div style="display: inline; float: right;">
            <a class="btn btn-primary btn-sm"  title="Import/Export travel request" href="{{ route('admin.tarvelrequest.create') }}">
                            <i class="fa fa-plus"></i>&nbsp;<strong>Create New Request</strong>
                        </a> 
            </div>      
        </div>
</div>
<?php 
$labelcolor= ['concert'=>"btn bg-maroon  margin",'dinner'=>'btn bg-red  margin','lunch'=>'btn bg-navy  margin','hightea'=>'btn bg-red  margin','cocktail'=>'btn bg-olive  margin','picnic'=>'btn bg-maroon margin','party'=>'btn bg-purple margin','seminar'=>'btn bg-blue margin','conference'=>'btn bg-orange margin','workshop'=>'btn bg-green margin','galas'=>'btn bg-yellow  margin'];
?>
<table class="table table-hover table-no-border" id="leads-table">
<thead>
    <tr>
        <th style="text-align:center;width:20px !important">
            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                <i class="fa fa-check-square-o"></i>
            </a>
        </th>
        <th>ID</th>
        <th>Account</th>
        <th>Visiting Place</th>
        <th>Depart Data</th>
        <th>Arrival Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($travelrequest as $key=>$value)
	<tr>
		<td >
			<input type="checkbox" name="event_id" value="{{$value->id}}">
        </td>
      <td>#{{$value->id}}</td>
      <td><a href="/admin/tarvelrequest/show/{{$value->id}}" target="_blank"> {{$value->client->name}}</a></td>
      <td>{{ucfirst($value->place_of_visit)}}</td>
      <td>{{date('dS M y',strtotime($value->depart_date))}}</td>
      <td>{{date('dS M y',strtotime($value->arrival_date))}}</td>
      <td>{{ucfirst($value->status)}}</td>
      <td>
        @if ( $value->isEditable()  )
        <a href="{!! route('admin.tarvelrequest.edit', $value->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
        @else
        <i class="fa fa-edit text-muted" title="{{ trans('admin/communication/general.error.cant-edit-this-organization') }}"></i>
        @endif
        &nbsp;
        @if($value->isDeletable())
        <a href="{!! route('admin.tarvelrequest.confirm-delete', $value->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
        @else
        asdsad
        <i class="fa fa-trash text-muted" title="{{ trans('admin/organization/general.error.cant-delete-this-organization') }}"></i>
        @endif
      </td>
    </tr>
    @endforeach

</tbody>
</table>

</div>

@endsection