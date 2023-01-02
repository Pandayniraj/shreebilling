@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}

        <small>{!! $page_description ?? "Page Description" !!}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>
<div class="box box-primary">
    <div class="box-header with-border">
        <div class='row'>
            <div class='col-md-12'>

                <b>
                    <font size="4">Event List</font>
                </b>
                <div style="display: inline; float: right;">
                    <a class="btn btn-success btn-sm" title="Import/Export Leads" href="{{ route('add-event-space') }}">
                        <i class="fa fa-check"></i>&nbsp;<strong>Add Event Space</strong>
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
                    <th>Event name</th>
                    <th>Room name</th>
                    <th>Room capability</th>
                    <th>Daily rate</th>
                    <th>Occupied date from</th>
                    <th>Occupied date to</th>
                    <th>booking_status</th>
                    <th>Owner</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($spaces as $key=>$s)
                <tr>
                    <td>
                        <input type="checkbox" name="event_id" value="{{$s->id}}">
                    </td>
                    <td>{{$s->id}}</td>
                    <td>{{$s->event_name}}</td>
                    <td>{{$s->room_name}}</td>
                    <td>{{$s->room_capability}}</td>
                    <td>{{$s->daily_rate}}</td>
                    <td>{{ date('dS M y', strtotime($event->occupied_date_from)) }}</td>
                    <td>{{ date('dS M y', strtotime($event->occupied_date_to)) }}</td>
                    <td>{{$s->booking_status}}</td>
                    <td>{{ucfirst(trans($s->username))}}</td>
                    <?php 
         $datas = '<a href="'.route('confirm-delete-space', $s->id).'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fa fa-trash-o deletable"></i></a>';
         ?>
                    <td><a href="/admin/edit-event-space/{{$s->id}}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;<?php echo $datas ?></td>
                </tr>
                @endforeach

            </tbody>
        </table>
        <div align="center">{!! $spaces->render() !!}</div>
    </div>

    @endsection
