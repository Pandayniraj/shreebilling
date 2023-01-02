@extends('layouts.master')
@section('content')
<style>
    .tabs-left,
    .tabs-right {
        border-bottom: none;
        padding-top: 2px;
    }

    .tabs-left {
        border-right: 1px solid #ddd;
    }

    .tabs-right {
        border-left: 1px solid #ddd;
    }

    .tabs-left>li,
    .tabs-right>li {
        float: none;
        margin-bottom: 2px;
    }

    .tabs-left>li {
        margin-right: -1px;
    }

    .tabs-right>li {
        margin-left: -1px;
    }

    .tabs-left>li.active>a,
    .tabs-left>li.active>a:hover,
    .tabs-left>li.active>a:focus {
        border-bottom-color: #ddd;
        border-right-color: transparent;
    }

    .tabs-right>li.active>a,
    .tabs-right>li.active>a:hover,
    .tabs-right>li.active>a:focus {
        border-bottom: 1px solid #ddd;
        border-left-color: transparent;
    }

    .tabs-left>li>a {
        border-radius: 4px 0 0 4px;
        margin-right: 0;
        display: block;
    }

    .tabs-right>li>a {
        border-radius: 0 4px 4px 0;
        margin-right: 0;
    }
    .orderArea{

        cursor: pointer;
    }
</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">

    <?php
         $outlet_details = \App\Models\PosOutlets::find(\Request::get('outlet_id'));      
         $outlet_code = $outlet_details->outlet_code;
    ?>
    <h1>
       <strong>{{$outlet_details->name}}</strong>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
    <p> Take new order or update existing orders</p>
</section>
<div class="box box-primary">
    <div class="box-header with-border">
        <div class='row'>


            <div class="col-sm-12">

                <div class="col-xs-3">
                    <!-- required for floating -->
                    <!-- Nav tabs -->
                    <h6>Table Sections</h6>
                    <ul class="nav nav-tabs tabs-left sideways">
                        @foreach($table_area as $key => $ta)
                        <li @if($key==0)class="active" @endif style="font-size: 16px" class="bg-info">
                            <a href="#{{str_replace(' ', '_',$ta->name)}}-{{$ta->id}}" data-toggle="tab">{{ucwords($ta->name)}} </a> 
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-xs-9">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        @foreach($table_area as $key => $ta)
                        <div class="tab-pane @if($key==0) active @endif" id="{{str_replace(' ', '_',$ta->name)}}-{{$ta->id}}">
                            <?php 
                                $tables  = \TaskHelper::getTables($ta->id);
                                $tables = $tables->groupBy('table_area_id');
                             ?>
                            <div class='col-md-12'>

                                @foreach($tables as $tableArea)
                                @php $tableArea = $tableArea->sortBy('table_number');  @endphp
                                @foreach($tableArea as $table)
                                <div class="orderArea" >
                                @if($table->is_packing == 1)

                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <h3>{{$table->table_number}}</h3>
                                            <span>Takeaway Order</span>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-shopping-cart"></i>
                                        </div>
                                        <a href="/admin/orders/create?type={{\Request::get('type')}}&outlet_id={{\Request::get('outlet_id')}}&table_id={{$table->id}}" class="small-box-footer">
                                            Pack Order
                                        </a>
                                    </div>
                                </div>

                                @else

                           <?php 
                                //check for empty table
                                $check_blank = \App\Helpers\TaskHelper::checkcustomerontable($table->id);

                            ?>

                                @if($check_blank['status'] == 2)


                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <a href="/admin/orders/{{ TaskHelper::getOrderIdfromTable($table->id)}}/edit?type=invoice" style="color: white; font-weight: bold;">
                                            <h4 style="font-size: 30px; font-weight: strong">{{$table->table_number}}</h4>
                                            <span>MERGED</span>
                                        </a>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa fa-retweet"></i>
                                        </div>
                                        
                                            <a href="#" class="small-box-footer">MERGE To {{ $check_blank['order']->merged_order->restauranttable->table_number }} </a>
                                      
                                    </div>
                                </div>



                                @elseif($check_blank == 1)

                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-olive">

                                       
                                        <div class="inner">
                                             <a href="/admin/orders/create?type={{\Request::get('type')}}&outlet_id={{\Request::get('outlet_id')}}&table_id={{$table->id}}" style="color: white">
                                            <h4 style="font-size: 30px; font-weight: strong">{{$table->table_number}}</h4>
                                            <span>Capacity: {{$table->seating_capacity}}</span>
                                            </a>
                                        </div>
                                    

                                        <div class="icon">
                                            <i class="fa fa-cutlery"></i>
                                        </div>
                                        <a href="/admin/orders/create?type={{\Request::get('type')}}&outlet_id={{\Request::get('outlet_id')}}&table_id={{$table->id}}" class="small-box-footer">
                                            Take Order
                                        </a>
                                    </div>
                                </div>
                                

                                @else

                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-maroon">
                                        <div class="inner">
                                            <a href="/admin/orders/{{ TaskHelper::getOrderIdfromTable($table->id)}}/edit?type=invoice" style="color: white; font-weight: bold;">
                                            <h4 style="font-size: 30px; font-weight: strong">{{$table->table_number}}</h4>
                                            <span>Total: {{env('APP_CURRENCY')}} {{ TaskHelper::getOrderProxyTotalfromTable($table->id)}}</span>
                                        </a>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-check-circle"></i>
                                        </div>
                                        
                                            <a href="/admin/orders/{{ TaskHelper::getOrderIdfromTable($table->id)}}/edit?type=invoice" class="small-box-footer">
                                            Modify Order
                                        </a>
                                      
                                    </div>
                                </div>

                                @endif

                                @endif
                                </div>
                                @endforeach
                                @endforeach

                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="clearfix"></div>

            </div>





        </div>
    </div>
<script type="text/javascript">
    $(function(){

        $('.orderArea').click(function(){

        let link =   $(this).find('a.small-box-footer');
        let url = link.attr('href');

        location.href = url;
     



        });

    });


</script>
    @endsection
