@extends('layouts.dialog')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
<style type="text/css">
	th{
		text-align: center;
	}
</style>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
           	{!! $page_title !!}
                <small>{!! $page_descriptions !!}</small>
            </h1>
            <p>{{ trans('admin/projects/general.titles.activity_logs') }} <strong>{{$activities[0]->task->project->name}}</strong></p>

        </section>
   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
            <b><font size="4">{{ trans('admin/projects/general.columns.activity') }}</font></b>
            <div style="display: inline;float: right;">
            <a class="btn btn-danger btn-xs"  title="Close" href="#" onclick="window.close()">
                <i class="fa fa-close"></i>
            </a>
            </div>
        </div>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ trans('admin/projects/general.columns.task') }}</th>
      <th scope="col">{{ trans('admin/projects/general.columns.activity') }}</th>
      <th scope="col" style="width: 15%">{{ trans('admin/projects/general.columns.date') }}</th>
      <th scope="col" style="width: 15%">{{ trans('admin/projects/general.columns.user') }}</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($activities as $key=>$act)
    <tr>
      <th scope="row">{{$key + $index_start}}</th>
      <td><a href="#">{{$act->subject}}</a></td>
      <td><a href="#" class='text-muted'>{{$act->activity}}</a></td>
      <td style="text-align: center;">{{date('dS Y M',strtotime($act->created_at))}}</td>
      <td style="text-align: center">{{$act->user->username}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
<div align="center">{!! $activities->render() !!}</div>
</div>
</div>



@endsection