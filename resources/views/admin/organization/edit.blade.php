@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               Organization Edit
                <small>Editing the organization....</small>
            </h1>
</section>
    <div class='row'>
        <div class='col-md-6'>
            <div class="box box-body">
              {!! Form::model( $organization, ['route' => ['admin.organization.update', \Request::segment(3)], 'method' => 'PATCH', 'class' => 'form-horizontal',  'id' => 'form_edit_contact', 'enctype' => 'multipart/form-data'] ) !!}
                @include('partials._organization_form')
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Update</button>
                        <a href="{!! route('admin.organization.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                    </div>
                </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.col -->
    </div><!-- /.row -->

@endsection
