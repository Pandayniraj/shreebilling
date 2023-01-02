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
    <p> Whether it is consumption, a supplier gift us some stock or some stocks are just got broken</p>

     <br/>

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>



<div class="box box-header">

 <div class="pull-right">
    <a class="btn btn-sm btn-primary" href="/admin/product/stock_adjustment/create"> + Add Adjustment </a>
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
                   <tr>
                     <td class="text-center">{{$aj->id}}</td>
                     <td>{{date('d M Y',strtotime($aj->transaction_date))}}</td>
                     <td>{{$aj->store->name}}</td>
{{--                     <td>{{ucwords($aj->status)}}</td>--}}
                     <td>{{$aj->adjustmentreason->name}}</td>
                     <?php
                     $total_qty = \App\Models\StockAdjustmentDetail::where('adjustment_id',$aj->id)->sum('qty');

                     ?>
                     <td class="text-center">{{$total_qty}}</td>
                     <td class="text-center">{{$aj->total_amount}}</td>
                     <td>
                        <a href="/admin/product/stock_adjustment/{{$aj->id}}/edit"><i class="fa fa-edit"></i></a>

                         <a href="{!! route('admin.products.stock_adjustment.confirm-delete', $aj->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
                     </td>
                   </tr>
                   @endforeach

                </tbody>
            </table>

        </div>

        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>
@endsection
