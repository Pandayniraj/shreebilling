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
            <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="{{ route('addevent') }}">
                            <i class="fa fa-plus"></i>&nbsp;<strong>Create New Event</strong>
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
        
        <th>ID</th>
        <th>Event Name</th>
        <th>Event Type</th>
        <th>Venue</th>
        <th>Owner</th>
        <th>Event Status</th>
        <th>Participants</th>
        <th>Start Date</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($events as $key=>$event)
	<tr>
		
        <td>{{$event->eid}}</td>
        <td>{{$event->event_name}}</td>
        <td><label class="label {{$labelcolor[$event->event_type]}}">{{ucfirst(trans($event->event_type))}}</label></td>
        <td >{{$event->venue_name}}</td>
        <td>{{ucfirst(trans($event->username))}}</td>
        <td>{{ucfirst(trans($event->event_status))}}</td>
        <td>{{$event->num_participants}}</td>
        <td>{{ date('dS M y', strtotime($event->event_start_date)) }}</td>
        <?php 
         $datas = '<a href="'.route('confirm-delete', $event->eid).'?type='.\Request::get('type').'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fa fa-trash-o deletable"></i></a>';
         ?>
        <td><a href="/admin/editevent/{{$event->eid}}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;<?php echo $datas ?></td>
    </tr>
    @endforeach

</tbody>
</table>
<div align="center">{!! $events->render() !!}</div>
</div>

@endsection