@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
    <div class='row'>
        <div class='col-md-9'>
        	<div class="box box-primary">
            	<div class="box-header with-border">
                	<h3> Document: {!! $document->doc_name !!} </h3>
                    <p> {!! $document->doc_desc !!} </p>
                </div>
                <div class="box-body">
    
                    {!! Form::model($document, ['route' => 'admin.documents.index', 'method' => 'GET']) !!}
    
                    <div class="content">
                        <div class="col-md-6">
                            <div class="form-group">
                                @if($document->file != '')
                                <label>Current File: <i class="fa fa-paperclip"> </i> </label>&nbsp;&nbsp;&nbsp;<a target="_blank" href="{{ '/documents/'.$document->file }}">{{ $document->doc_name }}</a>
                                @endif
                            </div>

                            <div class="form-group">
                                {!! Form::label('doc_type', trans('admin/documents/general.columns.doc_type')) !!}
                                {!! Form::text('doc_type', $document->doc_type, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                <label>
                                    {!! Form::checkbox('enabled', '1', $document->enabled, ['disabled']) !!} {!! trans('general.status.enabled') !!}
                                </label>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                {!! Form::label('doc_cats', trans('admin/documents/general.columns.doc_cats')) !!}
                                {!! Form::text('doc_cats', $document->doc_cats, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                           

                            <div class="form-group">
                                <label>
                                    {!! Form::checkbox('show_in_portal', '1', $document->show_in_portal, ['disabled']) !!} {!! trans('admin/documents/general.columns.show_in_portal') !!}
                                </label>
                            </div>
                            
                        </div>
                        
                        <div class="clearfix"></div>
                        <div class="form-group">
                            {!! Form::submit(trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                            @if ( $document->isEditable() || $document->canChangePermissions() )
                                <a href="{!! route('admin.documents.edit', $document->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'>{{ trans('general.button.edit') }}</a>
                            @endif
                        </div>


                        {!! Form::close() !!}
                    </div><!-- /.content -->
    
                </div><!-- /.box-body -->
            </div>
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

@section('body_bottom')
    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_user_search')
@endsection
