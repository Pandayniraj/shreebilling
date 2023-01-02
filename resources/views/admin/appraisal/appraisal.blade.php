@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
            {{$page_title ?? "Page Title"}}
                <small>{{$page_description ?? "Page Description"}}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
   <div class="box box-header">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
            <span style="font-size: 20px"> Appraisal List</span>
            <div style="display: inline; float: right;">
            <a class="btn btn-info btn-sm"  title="Import/Export Leads" href="{{ route('admin.performance.giveappraisal') }}">
                            <span class="material-icons">rate_review</span> Give Appraisal
                        </a> 
            </div>      
        </div>
</div>
<table class="table table-hover table-no-border table-striped" id="leads-table">
<thead>
    <tr class="bg-info">
        
        <th>ID</th>
        <th>Employee</th>
        <th>Evaluator</th>
        <th>Appraisal month</th>
        <th>Score</th>
        <th>Action</th>
       
    </tr>
</thead>
<tbody>
	@foreach($appraisal as $key=>$app)
	<tr>
		
        <td>{{$app->performance_appraisal_id}}</td>
        <td style="font-size: 16.5px"><a href="edit-appeaisal/{{$app->performance_appraisal_id}}">{{ucfirst(trans($app->username))}}</a></td>
        <td>{{ucfirst(trans($app->username))}}</td>
        <td>{{date('F Y', strtotime($app->appraisal_month))}}</td>
        <td>{{ $score }}</td>
        <td><i class="fa fa-download"></i>  <i class="fa fa-trash"></i></td>
       
    </tr>
    @endforeach

</tbody>
</table>
</div>

@endsection