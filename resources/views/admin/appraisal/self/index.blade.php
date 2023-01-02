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
                <a class="btn btn-info btn-sm" title="Give Appraisal" href="{{ route('admin.self.appraisal.create') }}">
                    <span class="material-icons">rate_review</span> Give Appraisal
                </a>
                </div>
            </div>
        </div>
        <table class="table table-hover table-no-border table-striped" id="leads-table">
            <thead>
                <tr class="bg-info">

                    <th>ID</th>
                    <th>Employee </th>
                    <th>Evaluator</th>
                    <th>Appraisal From</th>
                    <th>Appraisal To</th>
                    <th>My Score</th>
                    <th>Line Manager Score</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
            @foreach($appraisals as $key=>$val)
            <tr>
                <td>{{$val->id}}</td>
                <td>{{ucfirst($val->employee->username)}}</td>
                <td>{{ucfirst($val->evaluator->username)}} {{$val->evaluator_id}}</td>
                <td>{{$val->appraisal_from}}</td>
                <td>{{$val->appraisal_to}}</td>
                <td>{{$val->marks_by_employee.'/'.$val->total_marks}}</td>
                <td>{{$val->marks_by_evaluator.'/'.$val->total_marks}}</td>
                <td>
                    @if($val->form_level==0 && $val->entry_by == \Auth::user()->id)
                    <a href="/admin/self-appraisal/{{$val->id}}/edit" title="{{ trans('general.button.edit') }}">Draft</a>
                    @elseif($val->form_level==1 && $val->evaluator_id == \Auth::user()->id )
                    Pending
                    @elseif($val->form_level==2)
                    Forwarded
                    @endif
                </td>
                <td>
                    @if($val->form_level==0)
                    @if( $val->isEditable() || ((int)$val->marks_by_evaluator == '0' && $val->evaluator_id == \Auth::user()->id))
                        @if($val->evaluator_id == \Auth::user()->id)
                            <a href="/admin/self-appraisal/{{$val->id}}/Give my appraisal" class="btn btn-sm btn-success" title="{{ trans('general.button.edit') }}">Give my appraisal</a>
                        @else
                            <a href="/admin/self-appraisal/{{$val->id}}/edit" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                        @endif
                    @endif
                    @endif
{{-- {{dd($val,\Auth::user()->id)}} --}}

                    @if(((int)$val->marks_by_evaluator == '0') && $val->evaluator_id == \Auth::user()->id && $val->form_level==0)
                    <!-- <a href="{!! route('admin.self.appraisal.delete-confirm', $val->id) !!}" onclick="return confirm('Are You sure you want to delete this record ? ');" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a> -->
                    <a href="{!! route('admin.self.appraisal.delete', $val->id) !!}" onclick="return confirm('Are You sure you want to delete this record ? ');" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                    @elseif ($val->form_level==1 && $val->evaluator_id == \Auth::user()->id)
                    <a href="/admin/self-appraisal/{{$val->id}}/edit" class="btn btn-sm btn-success" title="{{ trans('general.button.edit') }}">Give my appraisal</a>
                    @endif

                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
