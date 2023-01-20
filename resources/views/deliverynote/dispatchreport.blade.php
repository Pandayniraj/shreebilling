@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

    <style>
        .table-bordered>thead>tr>th,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>tbody>tr>td,
        .table-bordered>tfoot>tr>td {
            border: 1px solid #000;
        }
    </style>
@endsection

@section('content')
    <link href="{{ asset('/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css') }}" rel="stylesheet" type="text/css" />

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small> {{ $page_description ?? 'Page Description' }}
            </small>
        </h1>
        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
    </section>

    <div class='row'>
        <div class="col-md-10">
            <h1 style="font-size:18px; font-weight:600; text-align:center; margin-bottom:-10px;">Jai Shreeninayak Distributor
                and Trade Link </h1>
            <h2 style="font-size:16px; font-weight:600; text-align:center; margin-bottom: -10px">Chamati-15, Kathmandu, Nepal
            </h2>
            <h3 style="font-size:16px; font-weight:600; text-align:center">Daily Dispatch Sheet & Stock of {{\App\Helpers\TaskHelper::getNepaliDate(date('Y-m-d'))}}</S>

        </div>
        <div class="col-md-2">
            <a href="/admin/deliverysheet/excel" class="btn btn-primary btn-xs"
                style="position: relative;
            top: 21px;"><i class="fa fa-print"></i>Export Excel</a>&nbsp;&nbsp;
        </div>
    </div>
    <div class="row table-responsive" >

        <table class="table table-bordered">
            <?php
            
            $totaldispatch = 0;
            $totalpurchase = 0;
            $totalopeningvalue = 0;
            $totalasopening=0;
            $totalclosingvalue = 0;
            $totalasclosing=0;
        
            ?>
            <tr>
                <th scope="col" style="font-weight: 700; font-size:16px">S.N</th>
                <th scope="col" style="font-weight: 700; font-size:16px">Outlet Name</th>
                <th scope="col" style="font-weight: 700; font-size:16px">Address</th>
                @foreach ($products as $product)
                    <th style="font-weight: 700; font-size:16px">{{ $product->name }}</th>
                @endforeach
                <th style="font-weight: 700; font-size:16px">Total</th>

            </tr>
            @foreach ($perdaysalesdetails as $key => $details)
            <tr>
                
              
                    <?php
                    $outletsalestotal=0;
                    $Client = \App\Models\Client::where('id', $key)
                        ->select('name', 'physical_address')
                        ->first();
                    ?>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $Client->name }}</td>
                    <td>{{ $Client->physical_address }}</td>
                    @foreach ($products as $product)
                        <td>
                            <?php
                            $outletsalestotal+=(double)abs($details->where('client_id', $key)->where('product_id', $product->id)->first()->totalsalesqty);
                                ?>
                            {{abs($details->where('client_id', $key)->where('product_id', $product->id)->first()->totalsalesqty ?? 0) }}
                        </td>
                    @endforeach
                    <td>{{$outletsalestotal}}</td>
            </tr>
         @endforeach
         <tr>
            <td></td>
            <td>Opening Stock</td>
            <td>-</td>
            @foreach($products as $product)
          <?php
          $openingvalue=0;
          ?>
          
           @if(isset($asopeningstock[$product->id][0]->asopeningstock)&&($asopeningstock[$product->id][0]->asopeningstock>0 || $asopeningstock[$product->id][0]->asopeningstock==0))
                @if($totalremaining[$product->id][0]->totalremainingqty== null || $totalremaining[$product->id][0]->totalremainingqty== "" )
                    <?php 
                    $openingvalue=(($asopeningstock[$product->id][0]->asopeningstock) + 0);
                ?>
               @else
               <?php 
               $openingvalue=(($asopeningstock[$product->id][0]->asopeningstock) + $totalremaining[$product->id][0]->totalremainingqty);
               ?>
              @endif
              <?php (double)$openingvalue;
                $totalopeningvalue+=$openingvalue;
              ?>
            <td>{{$openingvalue}}</td>
            @elseif($foropening[$product->id][0]->removestock<0 ||$foropening[$product->id][0]->removestock>0 || $foropening[$product->id][0]->removestock == 0)
           
                    @if($totalremaining[$product->id][0]->totalremainingqty== null || $totalremaining[$product->id][0]->totalremainingqty== "" )
                        <?php 
                        $openingvalue=(($foropening[$product->id][0]->removestock) + 0);
                        ?>
                    @else
                        <?php 
                        $openingvalue=(($foropening[$product->id][0]->removestock) + $totalremaining[$product->id][0]->totalremainingqty);
                        ?>
                @endif
                <?php
                $totalopeningvalue+=$openingvalue;
                ?>

            <td>{{$openingvalue}}</td>
            @elseif($totalremaining[$product->id][0]->totalremainingqty)
           
                <?php
                    $totalopeningvalue+=$totalasopening+$totalremaining[$product->id][0]->totalremainingqty
                ?>
                <td>{{$totalremaining[$product->id][0]->totalremainingqty}}</td>
            @else
            <td>0</td>   
            @endif 
            @endforeach
            <td>{{$totalopeningvalue}}</td>
         </tr>
          <tr>
            <td></td>
            <td>Purchase From Company</td>
            <td></td>
            
                @foreach($products as $product)
                @if($perdaypurchasedetail[$product->id][0]->totalpurchaseqty)
                <?php
                    $totalpurchase+=(double)$perdaypurchasedetail[$product->id][0]->totalpurchaseqty;
                ?>
                <td>
                    {{$perdaypurchasedetail[$product->id][0]->totalpurchaseqty}}
                </td>
                @else
                <td>0</td>    
                @endif
       
         @endforeach
         <td>{{$totalpurchase}}</td>
        </tr>  
        <tr>
            <td></td>
            <td>Total Dispatch</td>
            <td></td>
            @foreach($products as $product)
            @if($perdayproducttotal[$product->id][0]->totalproductqty)
            <?php
                $totaldispatch+=(double)abs($perdayproducttotal[$product->id][0]->totalproductqty);
            ?>
            <td>

                {{abs($perdayproducttotal[$product->id][0]->totalproductqty)}}
            </td>
            @else
            <td>0</td>    
            @endif
   
     @endforeach
     <td>{{$totaldispatch}}</td>
        </tr>
    <tr>
        <td></td>
        <td>Closing Stock</td>
        <td></td>
        @foreach($products as $product)
          <?php
            $openingvalue=0;
            $finalclosingvalue=0;
            ?>
           @if(isset($asopeningstock[$product->id][0]->asopeningstock)&&($asopeningstock[$product->id][0]->asopeningstock>0 || $asopeningstock[$product->id][0]->asopeningstock==0))
           @if($totalremaining[$product->id][0]->totalremainingqty== null || $totalremaining[$product->id][0]->totalremainingqty== "" )
               <?php 
               $openingvalue=(($asopeningstock[$product->id][0]->asopeningstock) + 0);
           ?>
          @else
          <?php 
          $openingvalue=(($asopeningstock[$product->id][0]->asopeningstock) + $totalremaining[$product->id][0]->totalremainingqty);
          ?>
         @endif
         <?php (double)$openingvalue;
           $totalopeningvalue+=$openingvalue;
         ?>
       @elseif($foropening[$product->id][0]->removestock<0 ||$foropening[$product->id][0]->removestock>0 || $foropening[$product->id][0]->removestock == 0)
      
               @if($totalremaining[$product->id][0]->totalremainingqty== null || $totalremaining[$product->id][0]->totalremainingqty== "" )
                   <?php 
                   $openingvalue=(($foropening[$product->id][0]->removestock) + 0);
                   ?>
               @else
                   <?php 
                   $openingvalue=(($foropening[$product->id][0]->removestock) + $totalremaining[$product->id][0]->totalremainingqty);
                   ?>
           @endif
           <?php
           $totalopeningvalue+=$openingvalue;
           ?>
       @elseif($totalremaining[$product->id][0]->totalremainingqty)
      
           <?php
               $totalopeningvalue+=$totalasopening+$totalremaining[$product->id][0]->totalremainingqty
           ?>
           <td>{{$totalremaining[$product->id][0]->totalremainingqty}}</td>
       @else
       <?php
            $openingvalue= 0;
       ?>
       @endif 
            @if($openingvalue &&  $openingvalue!="")
                @if($perdaypurchasedetail[$product->id][0]->totalpurchaseqty== null || $perdaypurchasedetail[$product->id][0]->totalpurchaseqty== "" )
                    <?php 
                    $finalclosingvalue=($openingvalue + 0 - abs($perdayproducttotal[$product->id][0]->totalproductqty??0));
                    ?>
                @else
                <?php 
                $finalclosingvalue=($openingvalue + $perdaypurchasedetail[$product->id][0]->totalpurchaseqty) - abs($perdayproducttotal[$product->id][0]->totalproductqty??0);
                ?>
                @endif
                <?php $totalasclosing+= (double)$finalclosingvalue;
                $totalclosingvalue+= (double)$finalclosingvalue?>
                <td>{{$finalclosingvalue}}</td>
            @elseif($perdaypurchasedetail[$product->id][0]->totalpurchaseqty)
                <?php
                    $totalclosingvalue+=$totalasclosing+$perdaypurchasedetail[$product->id][0]->totalpurchaseqty - abs($perdayproducttotal[$product->id][0]->totalproductqty??0)
                ?>
                <td>{{$perdaypurchasedetail[$product->id][0]->totalpurchaseqty- abs($perdayproducttotal[$product->id][0]->totalproductqty??0)}}</td>
            @else
                <td>0</td>   
            @endif 
        @endforeach
        <td>{{$totalclosingvalue}}</td>
       </tr>

        </table>
    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->
    {{-- @include('partials._body_bottom_submit_bug_edit_form_js') --}}

    <script></script>
@endsection
