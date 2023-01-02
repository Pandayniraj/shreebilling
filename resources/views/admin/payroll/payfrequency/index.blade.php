@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 40px">
    <h1> {{ $page_title}} </h1>
    {{ TaskHelper::topSubMenu('topsubmenu.payroll')}}
     {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
   </section>
   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
            <b><font size="4">Payroll | Pay Frequency | Index</font></b>
            <div style="display: inline; float: right;">
            <a class="btn btn-primary btn-sm"  title="Create new task type" href="/admin/payroll/payfrequency/create">
                            <i class="fa fa-plus"></i>&nbsp;<strong>Create New</strong>
                        </a> 
            </div>      
        </div>
</div>

<table class="table table-hover table-no-border table-striped" id="leads-table">
<thead>
    <tr>
        <th style="text-align:center;width:20px !important">
            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                <i class="fa fa-check-square-o"></i>
            </a>
        </th>
        <th>ID</th>
        <th>Name</th>
        <th>Frequency</th>
        <th>Time Entry Method</th>
        <th>Check Date</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($pay_frequency as $key=>$value)
	<tr>
		<td >
			<input type="checkbox" name="event_id" value="{{$value->id}}">
    </td>
      <td>#{{$value->id}}</td>
      <td>{{$value->name}}</td>
      <td><label class="label {{$label[$value->frequency]}}">{{$frequency[$value->frequency]}}</label></td>
      <td>{{$time_entry[$value->time_entry_method]}}</td>
      <td>{{date('dS Y M',strtotime($value->check_date))}}</td>
      <td>{{date('dS Y M',strtotime($value->period_start_date))}}</td>
      <td>{{date('dS Y M',strtotime($value->period_end_date))}}</td>
      <td>
        @if($value->is_issued)
          <a href="/admin/payroll/payfrequency/view_timecard/{{$value->id}}" title="View Time Cards"><i class="fa fa-calendar-plus-o"></i></a>
          &nbsp; &nbsp;
          <a href="/admin/payroll/payfrequency/view_salarylist/{{$value->id}}" title="View Amount"><i class="fa   fa-credit-card"></i></a>
        @else
         Pending
        @endif

       <!--  @if ( $value->isEditable()  )
        <a href="{!! route('admin.payfrequency.edit', $value->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
        @else
        <i class="fa fa-edit text-muted" title="{{ trans('admin/communication/general.error.cant-edit-this-organization') }}"></i>
        @endif
        &nbsp; -->
       <!--  @if($value->isDeletable())
        <a href="{!! route('admin.payfrequency.confirmdelete', $value->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
        @else
        asdsad
        <i class="fa fa-trash text-muted" title="{{ trans('admin/organization/general.error.cant-delete-this-organization') }}"></i>
        @endif -->
      </td>
    </tr>
    @endforeach

</tbody>
</table>


</div>

@endsection