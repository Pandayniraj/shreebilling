@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {!! $page_title ?? "Page title" !!} #0000{{Request::segment(3)}}
               
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            

             <br/>

           

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="box box-header">
   
    <div class="">
       
      

        <div style="min-height:200px" class="" id="">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="bg-purple">
                      <th class="text-center">Id</th>
                      <th class="text-center">Product</th>
                      <th class="text-center">Tran No #</th>
                        <th class="text-center">Tran Type</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Store</th>
                        <th class="text-center">Quantity In</th>
                        <th class="text-center">Quantity Out</th>
                        <th class="text-center"> <i class="fa fa- fa-hand-paper-o"></i> On Hand</th>
                    </tr>
                </thead>
                <tbody>  
                     <?php
                    $sum = 0;
                    $StockIn = 0;
                    $StockOut = 0;
                    ?>
                    @if(count($transations)>0)
                    @foreach($transations as $result)
                      <tr>
                        <td align="center">{{$result->id}}</td>
                      <td style="font-size: 16.5px" align="left"><a href="/admin/products/{{$result->pid}}/edit?op=trans" target="_blank"> {{$result->name}}</a></td>
                      <td align="center">{{$result->order_no}}</td>
                      <td align="center">
                        @if($result->trans_type == PURCHINVOICE)
                         Purchase
                        @elseif($result->trans_type == SALESINVOICE)
                          Sale
                        @elseif($result->trans_type == STOCKMOVEIN)
                         Transfer
                        @elseif($result->trans_type == STOCKMOVEOUT)
                        Transfer
                        @endif

                      </td>
                      <td align="center">{{$result->tran_date}}</td>
                      <td align="center">{{$result->storename}}</td>
                      <td align="center">
                        @if($result->qty >0 )
                          {{$result->qty}}
                          <?php
                          $StockIn +=$result->qty;
                          ?>
                        @else
                        -
                        @endif
                      </td>
                      <td align="center">
                        @if($result->qty <0 )
                          {{str_ireplace('-','',$result->qty)}}
                          <?php
                          $StockOut +=$result->qty;
                          ?>
                        @else
                        -
                        @endif
                      </td>
                      <td align="center">{{$sum += $result->qty}}</td>
                    </tr>
                    @endforeach
                     <tr><td colspan="3" align="right">Total</td><td align="center">{{$StockIn}}</td><td align="center">{{str_ireplace('-','',$StockOut)}}</td><td align="center">{{$StockIn+$StockOut}}</td></tr>
                    @else
                    <tr>
                        <td colspan="6" class="text-center text-danger">No Transaction Yet</td>
                    </tr>
                   @endif

                </tbody>
            </table>

            {!! $transations->render() !!}

        </div>

<a href="/admin/stockentries" class="btn btn-info"> Close </a>
      

        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>
@endsection