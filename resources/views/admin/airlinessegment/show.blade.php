@extends('layouts.master')

@section('content')
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
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Airline</label>
                                <select type="text" name="airline_id" class="form-control" required="" readonly>
                                      <option value="">Please Select</option>
                                    @foreach($airlines as $airline)
                                    <option value="{{$airline->id}}" @if($airline->id == $airlinesegment->airline_id) selected @endif>{{$airline->name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Segment Start</label>
                            <div class="input-group">
                                <input type="text" name="segment_start" class="form-control" placeholder="Segment Start" value="{{$airlinesegment->segment_start}}" required="" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa  fa-hourglass-start"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Segment End</label>
                            <div class="input-group">
                                <input type="text" name="segment_end" class="form-control" placeholder="Segment End" value="{{$airlinesegment->segment_end}}" required="" readonly>
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
                                <input type="number" name="user_adult_discount" class="form-control" placeholder="User Adult Discount" value="{{$airlinesegment->user_adult_discount}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-user-secret"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">User Child Discount</label>
                            <div class="input-group">
                                <input type="number" name="user_child_discount" class="form-control" placeholder="User Child Discount" value="{{$airlinesegment->user_child_discount}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-user"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">User Infant Discount</label>
                            <div class="input-group">
                                <input type="number" name="user_infant_discount" class="form-control" placeholder="User Infant Discount" value="{{$airlinesegment->user_infant_discount}}" readonly>
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
                                <input type="number" name="agent_adult_discount" class="form-control" placeholder="Agent Adult Discount" value="{{$airlinesegment->agent_adult_discount}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa  fa-user-secret"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Agent Child Discount</label>
                            <div class="input-group">
                                <input type="number" name="agent_child_discount" class="form-control" placeholder="Agent Child Discount" value="{{$airlinesegment->agent_child_discount}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-user"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Agent Infant Discount</label>
                            <div class="input-group">
                                <input type="number" name="agent_infant_discount" class="form-control" placeholder="Agent Infant Discount" value="{{$airlinesegment->agent_infant_discount}}" readonly>
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-user-plus"></i></a>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                 <a href="{!! route('admin.airline.segment.edit',$airlinesegment->id) !!}" class='btn btn-primary'>{{ trans('general.button.edit') }}</a>
                                <a href="{!! route('admin.airline.segment.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
    @endsection
