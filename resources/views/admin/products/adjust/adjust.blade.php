@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
      {!! $page_title ?? "Page title" !!}

        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    <p> Grand Total:  {{ number_format(\App\Models\StockAdjustment::wherebetween('transaction_date',[Request::get('startdate'),Request::get('enddate')])->sum('total_amount'),2) }}</p>

     <br/>

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>



<div class="box box-header">

    <div class="pull-right">
        <a class="btn btn-sm btn-primary" href="/admin/product/stock_adjustment/create"> + Add Adjustment </a>
    </div>

    <div class="balancesheet form">
        <form method="GET" action="{{route('admin.products.stock_adjustment')}}">
            <div class="row col-md-12">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Reason</label>
                        <div class="input-group">
                            <select class="form-control input-sm select2" id="reason" name="reason" aria-hidden="true" required>
                                <option selected value="">Select Reason</option>
                                @foreach($reasons as $reason)
                                    <option value="{{$reason->id}}" @if(Request::get('reason') == $reason->id) selected="" @endif>{{$reason->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Start Date</label>
                        <div class="input-group">
                            <input type="text" name="startdate" id="startdate" class="form-control input-sm datepicker" value="{{Request::get('startdate')}}">
                            <div class="input-group-addon">
                                <i>
                                    <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave start date as empty if you want to filter from the start of the financial year."></div>
                                </i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>End Date</label>
                        <div class="input-group">
                            <input type="text" name="enddate" id="enddate" class="form-control input-sm datepicker" value="{{Request::get('enddate')}}">
                            <div class="input-group-addon">
                                <i>
                                    <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave end date as empty if you want to filter till the end of the financial year."></div>
                                </i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Store</label>
                        <select class="form-control input-sm select2" id="store" name="store" aria-hidden="true" required>
                            <option selected value="">Select Store</option>
                            @foreach($stores as $store)
                                <option value="{{$store->id}}" @if(Request::get('store') == $store->id) selected="" @endif>{{$store->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2" style="margin-top: 2.3rem;">
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary btn-sm" value="Filter">
                        <a type="reset" href="/admin/product/stock_adjustment" id="clearfilter" class="btn btn-danger btn-sm" style="margin-left: 5px;" value="Clear">Clear</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="">

        <div style="min-height:200px" class="" id="">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="bg-purple">
                      <th class="text-center">Adj Id#</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Store</th>
{{--                        <th class="text-center">Status</th>--}}
                        <th class="text-center">Reason</th>
                        <th class="text-center"> Total Adj Qty</th>
                        <th class="text-center"> Total Adj Price</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>

                   @foreach($stockadjustment as $aj)
                    @php
                        $total_qty = 0;
                        foreach($aj->detail as $ajdk => $ajdv)
                        {
                            $total_qty = $total_qty + $ajdv->qty;
                        }

                    @endphp
                    <tr class="bg-gray" style="font-size: 16.5px;">
                        <td class="text-center">#00{{$aj->id}}</td>
                        <td>{{date('d M Y',strtotime($aj->transaction_date))}}</td>
                        <td>{{$aj->outlet->name}}</td>
                        {{-- <td>{{ucwords($aj->status)}}</td>--}}
                        <td>{{$aj->adjustmentreason->name}}</td>
                        <td class="text-center">{{$total_qty}}</td>
                        <td class="text-center">{{$aj->total_amount}}</td>
                        <td>
                            <a href="/admin/product/stock_adjustment/{{$aj->id}}/edit"><i class="fa fa-edit"></i></a>

                            <a href="{!! route('admin.products.stock_adjustment.confirm-delete', $aj->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
                        </td>
                    </tr>
                    @foreach($aj->detail as $ajdk => $ajdv)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="text-center">{{$ajdv->product->name}}</td>
                        <td class="text-center">{{$ajdv->price}}</td>
                        <td class="text-center">{{$ajdv->qty}}</td>
                        <td class="text-center">{{$ajdv->total}}</td>
                        <td>&nbsp;</td>
                    </tr>
                    @endforeach
                   @endforeach

                </tbody>
            </table>

        </div>

        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script>
    $(function() {
        $('#startdate').datetimepicker({
            format: 'YYYY-MM-DD'
            , sideBySide: true
        });

        $('#enddate').datetimepicker({
            format: 'YYYY-MM-DD'
            , sideBySide: true
        });
    });

</script>
@endsection
