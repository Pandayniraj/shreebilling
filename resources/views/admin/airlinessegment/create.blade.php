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
                <form method="post" action="{{route('admin.airline.segment.store')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Airline</label>
                                <select type="text" name="airline_id" class="form-control select2" required="" >
                                      <option value="">Please Select</option>
                                    @foreach($airlines as $airline)
                                    <option value="{{$airline->id}}">{{$airline->name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Segment Start</label>
                            <div class="input-group">
                                <input type="text" name="segment_start" class="form-control" placeholder="Segment Start" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-hourglass-start"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Segment End</label>
                            <div class="input-group">
                                <input type="text" name="segment_end" class="form-control" placeholder="Segment End" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa  fa-hourglass-end"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                  
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">User Adult Discount</label>
                            <div class="input-group">
                                <input type="number" name="user_adult_discount" class="form-control" placeholder="User Adult Discount">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-user-secret"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">User Child Discount</label>
                            <div class="input-group">
                                <input type="number" name="user_child_discount" class="form-control" placeholder="User Child Discount">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-user"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">User Infant Discount</label>
                            <div class="input-group">
                                <input type="number" name="user_infant_discount" class="form-control" placeholder="User Infant Discount">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-user-plus"></i></a>
                                </div>
                            </div>
                        </div>
                         
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Agent Adult Discount</label>
                            <div class="input-group">
                                <input type="number" name="agent_adult_discount" class="form-control" placeholder="Agent Adult Discount">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-user-secret"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Agent Child Discount</label>
                            <div class="input-group">
                                <input type="number" name="agent_child_discount" class="form-control" placeholder="Agent Child Discount">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-user"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Agent Infant Discount</label>
                            <div class="input-group">
                                <input type="number" name="agent_infant_discount" class="form-control" placeholder="Agent Infant Discount">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-user-plus"></i></a>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                                <a href="{!! route('admin.airline.segment.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
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
