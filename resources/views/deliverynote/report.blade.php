@extends('layouts.master')

@section('head_extra')
    <?php
    function CategoryTree($parent_id = null, $sub_mark = '', $ledgers_data)
    {
        $groups = \App\Models\COAgroups::orderBy('code', 'asc')
            ->where('parent_id', $parent_id)
            ->get();
    
        if (count($groups) > 0) {
            foreach ($groups as $group) {
                echo '<optgroup  label="' . $sub_mark . '[' . $group->code . ']' . ' ' . $group->name . '"></optgroup>';
    
                $ledgers = \App\Models\COALedgers::orderBy('code', 'asc')
                    ->where('group_id', $group->id)
                    ->get();
                if (count($ledgers > 0)) {
                    $submark = $sub_mark;
                    $sub_mark = $sub_mark . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    
                    foreach ($ledgers as $ledger) {
                        if ($ledgers_data->id == $ledger->id) {
                            echo '<option selected value="' . $ledger->id . '"><strong>' . $sub_mark . '[' . $ledger->code . ']' . ' ' . $ledger->name . '</strong></option>';
                        } else {
                            echo '<option value="' . $ledger->id . '"><strong>' . $sub_mark . '[' . $ledger->code . ']' . ' ' . $ledger->name . '</strong></option>';
                        }
                    }
                    $sub_mark = $submark;
                }
                CategoryTree($group->id, $sub_mark . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $ledgers_data);
            }
        }
    }
    
    ?>

    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

    <style>
        .panel .mce-panel {
            border-left-color: #fff;
            border-right-color: #fff;
        }

        .panel .mce-toolbar,
        .panel .mce-statusbar {
            padding-left: 20px;
        }

        .panel .mce-edit-area,
        .panel .mce-edit-area iframe,
        .panel .mce-edit-area iframe html {
            padding: 0 10px;
            min-height: 350px;
        }

        .mce-content-body {
            color: #555;
            font-size: 14px;
        }

        .panel.is-fullscreen .mce-statusbar {
            position: absolute;
            bottom: 0;
            width: 100%;
            z-index: 200000;
        }

        .panel.is-fullscreen .mce-tinymce {
            height: 100%;
        }

        .panel.is-fullscreen .mce-edit-area,
        .panel.is-fullscreen .mce-edit-area iframe,
        .panel.is-fullscreen .mce-edit-area iframe html {
            height: 100%;
            position: absolute;
            width: 99%;
            overflow-y: scroll;
            overflow-x: hidden;
            min-height: 100%;
        }
      .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #000;
}
    </style>
@endsection

@section('content')
    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

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
        <h1 style="font-size:18px; font-weight:600; text-align:center">Statement Of Calculation</h1>
        </div>
      
        <div class="col-md-2">
            <form method="GET" action="{{ route('admin.deliverynote.excel') }}" >
                <input type="hidden" name="excel" value="excel">
                <input type="hidden" name="start_date" value="{{$startdate}}">
                <input type="hidden" name="end_date" value="{{$enddate}}">
                <input type="submit" value="Export Excel" class="btn-primary btn-xs" style="position: relative; top: 21px;">
            </form>
        </div>
    </div> 
    
    <div class="row">  
        @foreach ($details as $index => $invoice)
        <table class="table table-bordered">
            <?php
            $totalunitcost=0;
            $totalqty=0;
            $subtotal=0;
            $totalvat=0;
            $totalamount=0;
            $totaldeliveryrate=0;
            $totaldeliveryamt=0;
            $totaldifferenceamt=0;
            ?>
                    <tr>
                        <th scope="col">S.N</th>
                        <th scope="col">Particulars</th>
                        <th scope="col">Product-id</th>
                        <th scope="col">Unit Purchase Price</th>
                        <th scope="col">Total Purchase Price</th>
                        <th scope="col">Billing Unit Cost</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Unit</th>
                        <th scope="col">Total</th>
                        <th scope="col">VAT13%</th>
                        <th scope="col">Total Amount</th>
                        <th scope="col">Delivery Quotation Amount</th>
                        <th scope="col">Total</th>
                        <th scope="col">Difference Amount</th>
                        <th scope="col">VAT Bill No.</th>
                        <th scope="col">VAT No.</th>
                        <th scope="col">Bill Date</th>
                        <th scope="col">Claim Amount</th>
                    </tr>
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $invoice->client->name }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $invoice->bill_no }}</td>
                        <td>{{ $invoice->client->vat }}</td>
                        <td>{{ $invoice->bill_date }}</td>
                        <?php
                        if($invoice->deliverynote == null)
                        {
                            $deliverynotesum=0;
                        }
                        else{
                            $deliverynotesum= (float) $invoice->deliverynote->notedetails->sum('return_total');
                        }
                        ?>
                        <td>{{ ((float) $invoice->invoiceDetail->sum('total') + (float) $invoice->invoiceDetail->sum('tax_amount') )- ($deliverynotesum)}}
                        </td>
                    </tr>
                    <tr>
                        @for($i=0;$i<count($invoice->invoiceDetail);$i++)
                            <tr>
                                <td></td>
                                <td>{{ $invoice->invoicedetail[$i]->product->name }}</td>
                                <td>{{$invoice->invoicedetail[$i]->product->id}}</td>
                                <td>{{$invoice->invoicedetail[$i]->product->cost}}</td>
                                <td>{{(float)$invoice->invoicedetail[$i]->product->cost * (float)$invoice->invoicedetail[$i]->quantity}}</td>

                                <td>{{ $invoice->invoicedetail[$i]->price }}</td>
                                <td>{{ $invoice->invoicedetail[$i]->quantity }}</td>
                                <td>{{ \App\Models\ProductsUnit::where('id', $invoice->invoicedetail[$i]->product_id)->first()->name }}</td>
                                <td>{{ $invoice->invoicedetail[$i]->total }}</td>
                                <td>{{ number_format( $invoice->invoicedetail[$i]->tax_amount,2) }}
                                    @php
                                        $bill_total = (float) $invoice->invoicedetail[$i]->total + (float) $invoice->invoicedetail[$i]->tax_amount;
                                    @endphp
                                <td>{{ number_format($bill_total,2) }}</td>
                        
                                <td>{{ $invoice->deliverynote->notedetails[$i]->return_price }}</td>
                                @php
                                    $del_total_row =  $invoice->deliverynote->notedetails[$i]->return_total;
                                @endphp
                                <td>{{ number_format($del_total_row,2) }}</td>
                                <td>{{  $bill_total-$del_total_row }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <?php 
                                $totalunitcost+=$invoice->invoicedetail[$i]->price;
                                $totalqty+=$invoice->invoicedetail[$i]->quantity;
                                $subtotal+=$invoice->invoicedetail[$i]->total;
                                $totalvat+=$invoice->invoicedetail[$i]->tax_amount;
                                $totalamount+=$bill_total;
                                $totaldeliveryrate+=$invoice->deliverynote->notedetails[$i]->return_price;
                                $totaldeliveryamt+=$del_total_row;
                                $totaldifferenceamt+=$bill_total-$del_total_row;
                                ?>
                            </tr>
                            
                        @endfor
                        <tr>
                            <td></td>
                            <td>Total</td>
                            <td></td>
                            <td></td>
                            <td></td>  
                            <td>{{number_format($totalunitcost,2)}}</td>
                            <td>{{number_format($totalqty,2)}}</td>
                            <td></td>
                            <td>{{number_format($subtotal,2)}}</td>
                            <td>{{number_format($totalvat,2)}}</td>  
                            <td>{{number_format($totalamount,2)}}</td> 
                            <td>{{number_format($totaldeliveryrate,2)}}</td> 
                            <td>{{number_format($totaldeliveryamt,2)}}</td> 
                            <td>{{number_format($totaldifferenceamt,2)}}</td> 
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>



                        </tr>
                        
            
        </table>
        @endforeach
    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_bug_edit_form_js')

    <script></script>
@endsection
