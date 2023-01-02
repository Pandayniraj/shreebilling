@extends('layouts.master')
@section('content')

@include('partials._head_extra_select2_css')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {{-- Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body ">
                <form method="post" action="{{route('admin.cms.contacts.store')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Full Name</label>
                            <div class="input-group">
                                <input type="text" name="full_name" class="form-control" placeholder="Full Name" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa  fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Email</label>
                            <div class="input-group">
                                <input type="email" name="email" class="form-control" placeholder="Email" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Guidence</label>
                            <div class="input-group">
                                <input type="text" name="guidence" class="form-control" placeholder="Guidence" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Phone</label>
                            <div class="input-group">
                                <input type="text" name="phone" class="form-control" placeholder="phone" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Message</label>
                            <div class="input-group">
                                <textarea name="message" class="form-control" placeholder="Message" cols="50"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                                <a href="{!! route('admin.cms.contacts.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
    <script>
        $('.select2').select2();

    </script>
    @endsection
