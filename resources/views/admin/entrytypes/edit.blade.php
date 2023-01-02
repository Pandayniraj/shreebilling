@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
          <h1>
              Edit a Entry Types
              <small>Editing Datas of Form</small>
          </h1>
          {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
      </section>
    <div class='row'>
        <div class='col-md-12'>
            <div class="box-body">
                    <form method="post" action="/admin/entrytype/update/{{$entrytype->id}}"  class = 'form-horizontal'>
                        {{ csrf_field() }}

                        @include('partials._entrytype_form')
                        <br>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Update</button>
                            <a href="{!! route('admin.entrytype.index') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                        </div>
                    </form>
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection
@section('body_bottom')
@endsection
