@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $contact->salutation }} {{ $contact->full_name }}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.clients')}}
         

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
    <div class='row'>
        <div class='col-md-12'>
        	<div class="box box-header with-border bg-info">

            	<div class="box-header with-border">

                     <span class="pull-left" >
                    <p>  {{ $contact->position }}, {{ $contact->department }},  
                        <strong><a href="/admin/clients/{{ $contact->client_id}}">{{ $contact->client->name }}</a></strong> </p>
                    <p> <i class="fa fa-phone"> </i> Phone: 
                        <a href="tel:{{ $contact->phone }}"> {{ $contact->phone }} </a>, 
                        <a href="tel:{{ $contact->landline }}"> {{ $contact->landline }} </a>  </p>
                    <p> <i class="fa fa-envelope"> </i> Primary Email: <a href="mailto:{{ $contact->email_1 }}">{{ $contact->email_1 }}</a></p>
                    <p> <i class="fa fa-facebook"> </i> facebook: {{ $contact->facebook }}</p>
                    <p> <i class="fa fa-map"> </i> location: {{ $contact->city }}, {{ $contact->country }}</p>
                </span>

                    <span class="pull-right" >
                    <img style="height: 250px; width: auto;"  src="/contacts/{{ $contact->file }}" />
                </span>

                </div>




                <div class="box-body">
    
                    {!! Form::model($contact, ['route' => 'admin.contacts.index', 'method' => 'GET']) !!}
    
                    <div class="content">
                        <div class="col-md-6">
                            
                            
                            <div class="form-group">
                                {!! Form::label('website', trans('admin/contacts/general.columns.website')) !!}
                                {!! Form::text('website', null, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                            
                            
                            <div class="form-group">
                                {!! Form::label('email_2', trans('admin/contacts/general.columns.email_2')) !!}
                                {!! Form::text('email_2', null, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            
                            
                            <div class="form-group">
                                {!! Form::label('address', trans('admin/contacts/general.columns.address')) !!}
                                {!! Form::text('address', null, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                            
                            <div class="form-group">
                                {!! Form::label('postcode', trans('admin/contacts/general.columns.postcode')) !!}
                                {!! Form::text('postcode', null, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                            
                            
                            
                        </div>

                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('enabled', '1', $contact->enabled, ['disabled']) !!} {!! trans('general.status.enabled') !!}
                            </label>
                        </div>                        
                        <div class="form-group">
                            {!! Form::submit(trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                            @if ( $contact->isEditable() || $contact->canChangePermissions() )
                                <a href="{!! route('admin.contacts.edit', $contact->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-success'>{{ trans('general.button.edit') }}</a>
                            @endif
                            <a href="/admin/clients/{{$contact->client_id}}?relation_type={{ $contact->client->relation_type }}" title="view ledger" class='btn btn-danger' target="_blank">{{ $contact->full_name }}'s' Company</a>
                            <a target="_blank" href="https://wa.me/{{$contact->phone}}" class="btn btn-social btn-success">
                            <i class="fa fa-whatsapp"></i> Whatsapp Web
                            </a>
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
