@extends('layouts.master')
@section('content')
    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
        {{$page_title ?? "Page Title"}}
            <small>{{$page_description ?? "Page Description"}}</small>
        </h1>
        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        {{dd(\Auth::user()->firstLineManger)}}
    </section>
   <div class="box box-header">
        <div class="box-header with-border">
        <div class='row'>
            <div class='col-md-12'>
                <span style="font-size: 20px"> Appraisal List</span>
                <div style="display: inline; float: right;">
                <a class="btn btn-info btn-sm" title="Give Appraisal" href="{{ route('admin.appraisal.create') }}">
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
            @foreach($appraisals as $key=>$val)
            <tr>		
                <td>{{$val->id}}</td>
                <td>{{ucfirst($val->employee->username)}}</td>
                <td>{{ucfirst($val->evaluator->username)}}</td>
                <td>{{$val->appraisal_month}}</td>
                <td>{{$val->appraisalData->sum('appraisal_point')}}</td>
                <td>
                    @if( $val->isEditable() )
                        <a href="/admin/appraisal/{{$val->id}}/edit" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a> 
                    @endif

                    @if($val->isDeletable())
                    <!-- <a href="{!! route('admin.appraisal.delete-confirm', $val->id) !!}" onclick="return confirm('Are You sure you want to delete this record ? ');" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a> -->
                    <a href="{!! route('admin.appraisal.delete', $val->id) !!}" onclick="return confirm('Are You sure you want to delete this record ? ');" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                    @endif
                </td>            
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection