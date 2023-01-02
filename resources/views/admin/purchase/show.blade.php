@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')

@endsection

@section('content')
<style>
    .box-comment {
        margin-bottom: 5px;
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
    }

    .box-comment img {
        float: left;
        margin-right: 10px;
    }

    .username {
        font-weight: bold;
    }

    .comment-text span {
        display: block;
    }

</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
       
        {!! $_GET['type'] == 'purchase_orders' ? 'Purchase Order' : ucfirst($_GET['type']) !!}

        <small> Manage {{ ucfirst(\Request::get('type'))}}</small>
    </h1>
    <p> When the purchase order is converted to purchase bills, it will hit the inventory and the ledger. However if we create the purchase biill directly it will hit the both directly. </p>
    {{ TaskHelper::topSubMenu('topsubmenu.purchase')}}
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>


<div class='row'>
    <div class='col-md-12'>

        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                    <img width="" style="max-width: 250px" src="{{ '/org/'.auth()->user()->organization->logo }}" alt="{{ \Auth::user()->organization->organization_name }}">
                        <span class="pull-right">
                            <span>{{ ucwords(str_replace("_", " ", ucfirst($ord->purchase_type)))}}</span>
                            &nbsp;
                            <a href="/admin/purchase?type={{ $ord->purchase_type }}">
                                <button type="button" class="btn btn-xs btn-success pull-right">
                                    <i class="fa fa-times-circle"></i>Close
                                </button>
                            </a> &nbsp;


                            <a href="/admin/purchase/print/{{ $ord->id }}" target="_blank" class="btn btn-xs btn-default pull-right"><i class="fa fa-print"></i> Print</a>&nbsp;&nbsp;

                            @if($ord->purchase_type == "request")
                            <a onclick="return confirm('Are you sure you want to POST')" href="/admin/purchase/confirm-post-to-po/{{$ord->id}}">
                                <button type="button" class="btn btn-xs btn-primary pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-book"></i> Post to PURCHASE ORDER
                                </button>
                            </a> &nbsp;&nbsp;&nbsp;
                            @endif

                            @if($ord->purchase_type == "purchase_orders")
                            <a href="/admin/purchase/{{$ord->id}}/edit?type=purchase_orders">
                                <button type="button" class="btn btn-xs btn-primary pull-right">
                                    <i class="fa fa-times-circle"></i> Edit
                                </button>
                            </a> &nbsp;
                            <a href="/admin/purchase/confirm-purchase-modal/{{$ord->id}}" data-toggle="modal" data-target="#modal_dialog">
                                <button type="button" class="btn btn-xs btn-primary pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-book"></i> Post to purchase bill
                                </button>
                            </a> &nbsp;&nbsp;&nbsp;
                            @elseif($ord->purchase_type == "bills")
                            <a href="/admin/purchase/{{$ord->id}}/edit?type=bills">
                                <button type="button" class="btn btn-xs btn-primary pull-right">
                                    <i class="fa fa-times-circle"></i> Edit
                                </button>
                            </a> &nbsp;
                            @endif
                            <a href="/admin/purchase/generatePDF/{{ $ord->id }}">
                                <button type="button" class="btn btn-xs btn-primary pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-download"></i>PDF
                                </button>
                            </a> &nbsp;
                            <a href="{{ route('purchase.sendmail',$ord->id) }}">
                                <button type="button" class="btn btn-xs btn-primary pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-envelope"></i> Send eMail
                                </button>
                            </a> &nbsp; &nbsp;
                            @if(!($ord->status == "GRN Created"))
                                <a href="/admin/grn/post/{{ $ord->id }}">
                                    <button type="button" class="btn btn-xs btn-success pull-right" title="Good Receipt Note" style="margin-right: 5px;">
                                        <i class="fa fa-list"></i> Post to GRN
                                    </button>
                                </a> &nbsp; &nbsp;
                            @endif
                            @if(!(($ord->status == "Approved" && $ord->approved_by) || ($ord->status == "GRN Created")))
                                <a href="/admin/purchase/{{$ord->id}}/approve?type=bills">
                                    <button type="button" class="btn btn-xs btn-info pull-right" title="Good Receipt Note" style="margin-right: 5px;">
                                        <i class="fa fa-check"></i> Approve
                                    </button>
                                </a> &nbsp; &nbsp;
                            @endif

                            <!--  <a href="/admin/quotation/mail/{!! $ord->id !!}/show-mailmodal" class="btn btn-xs btn-default pull-right"  data-toggle="modal" data-target="#emailInvoice">  <i class="fa fa-envelope"></i>Email </a> &nbsp; -->


                        </span>
                    </h2>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                <address>
                        <span style="font-size: 16.5px">{{ \Auth::user()->organization->organization_name }} </span><br>
                        {{ \Auth::user()->organization->address }}<br>

                        Phone: {{ \Auth::user()->organization->phone }}<br>
                        Email: {{ \Auth::user()->organization->email }}<br />
                        Generated by: {{ $ord->user->first_name}} {{ $ord->user->last_name}}<br>
                        Sellers PAN: {{ \Auth::user()->organization->vat_id }}
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    SUPPLIER
                    <address>
                        <span><a target="_blank" href="/admin/clients/{{ $ord->client->id }}?relation_type=supplier">{{ $ord->client->name }}</a></span><br>
                        Address: {!! nl2br($ord->client->location ) !!}<br />
                        Phone: {!! nl2br($ord->client->phone ) !!}<br />
                        Email: {!! nl2br($ord->client->email ) !!}<br />
                        PAN NO: {!! nl2br($ord->pan_no ) !!}<br />

                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <b>Order #{{\FinanceHelper::getAccountingPrefix('PURCHASE_PRE')}}{{ $ord->id }}</b><br>
                    <b>Bill No: {{ $ord->bill_no }}</b><br>
                    <b>Bill Date:</b> {{ date("d/M/Y", strtotime($ord->bill_date )) }}<br>
                    <b>Delivery Date:</b> {{ date("d/M/Y", strtotime($ord->delivery_date )) }}<br>
                    <b>Order Date:</b> {{ date("d/M/Y", strtotime($ord->ord_date )) }}<br>
                    <?php $timestamp = strtotime($ord->created_at) ?>
                    <b>Vendor Reg No:</b> #{{ $ord->supplier_id }}<br>
                    Voucher #: <a target="_blank" href="/admin/entries/show/{{\FinanceHelper::get_entry_type_label($ord->entry->entrytype_id)}}/{{$ord->entry->id}}">{{$ord->entry->number}}</a>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <p>
                Your negotiations and agreement to do business are conditioned on your acceptence of and compliance with these terms.
                The {{ ucwords(str_replace("_", " ", ucfirst($ord->purchase_type)))}} is requested by the {{ env('APP_COMPANY') }}.
            </p>
            <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table id="t01" class="table table-striped">
                        <thead class="bg-danger">
                            <tr>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <!-- <th>Agent Expenses</th> -->
                                <th> Unit </th>
                                @if($ord->is_import==1)
                                <th>Landing Price</th>
                                @endif
                                <th>Total</th>
                                <th>Cost <small>incl. logistic</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderDetails as $odk => $odv)

                            <tr>
                                @if($odv->is_inventory == 1)
                                <td>{{ $odv->product->name }}</td>
                                @elseif($odv->is_inventory == 0)
                                <td>{{ $odv->description }}</td>
                                @endif

                                <td>{{ number_format($odv->qty_invoiced,2) }}</td>
                                <td>{{ number_format($odv->unit_price,2) }}</td>

                                <!-- <td>{{ number_format($odv->agent_expenses,2) }}</td> -->
                                <td>{{ $odv->product->units->symbol??'-' }}</td>
                                @if($ord->is_import==1)
                                @php
                                    $stock_ledger=\App\Models\StockMove::where('order_no',$ord->id)->where('stock_id',$odv->product_id)->first();

                                    $landingcost=$stock_ledger->price??"";
                                @endphp
                                <td >{{ number_format($landingcost,2) }}</td>
                                @endif

                                <td>{{ $ord->currency.' '.number_format($odv->total,2) }}</td>
                             
                                <td class="std_p" title="Sum of Price,Excise Charge  Per Unit, Agent Commission Per Unit, Insurance Per Unit, Bank Commission  Per Unit, Transportation Charge Per Unit, Ware House Charge Per Unit ">{{ $odv->unit_price + $odv->product->excise_charge_percentage_per_unit??0 + $odv->product->bank_commission_percentage_per_unit??0 + $odv->product->agent_commission_per_unit??0 + $odv->product->transportation_charge_per_unit??0 + $odv->product->insurence_charge_per_unit??0 + $odv->product->warehouse_charge_per_unit??0 }}</td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>



            <div class="row">
                <!-- accepted payments column -->
                <div class="col-xs-6">
                    <?php
           $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
           ?>

                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;text-transform: capitalize;font-size: 16.5px">
                        In Words: {{ $f->format($ord->total)}}
                    </p>


                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        1. Please send two copy of your invoice.<br />
                        2. Please notify us ASAP if you are not able to supply the above requests <br />
                        3. Late delivery may be subject to cancellation
                    </p>

                    <h4> Special Notes and Instruction</h4>
                    <p class="text-muted well well-sm well-primary no-shadow" style="margin-top: 10px;">
                        {!! nl2br($ord->comments) !!}
                    </p>

                    {{-- --}}
                </div>
                <!-- /.col -->
                <div class="col-xs-6">


                    <div class="table-responsive">
                        <table id="t01" class="table table-striped">
                            <tbody>
                                <tr style="padding:0px; margin:0px;">
                                    <th style="width:60%">Amount:</th>
                                    <td>{{ $ord->currency.' '. number_format($ord->subtotal,2) }}</td>
                                </tr>
                                <tr style="padding:0px; margin:0px;">
                                    <th style="width:60%">Order Discount</th>
                                    <td>{{$ord->currency.' '. number_format($ord->discount_amount,2) }}</td>
                                </tr>
                                <tr style="padding:0px; margin:0px;">
                                    <th style="width:60%">Non Taxable Amount</th>
                                    <td>{{$ord->currency.' '. number_format($ord->non_taxable_amount,2) }}</td>
                                </tr>
                                <tr>
                                    <th style="width:60%">Taxable Amount</th>
                                    <td>{{$ord->currency.' '. number_format($ord->taxable_amount,2) }}</td>
                                </tr>
                                <tr style="padding:0px; margin:0px;">
                                    <th style="width:60%">Tax Amount:</th>
                                    <td>{{ $ord->currency.' '. number_format($ord->tax_amount,2) }}</td>
                                </tr>
                                <tr style="padding:0px; margin:0px;">
                                    <th class="bg-danger" style="width:60%">Total:</th>
                                    <td class="bg-danger" style="font-size: 16.5px">{{ $ord->currency.' '. number_format($ord->total,2) }}</td>
                                </tr>
                                <!--   <tr>
                <th>Discount:</th>
                <td>{{ $ord->currency.' '.($ord->discount_amount ? $ord->discount_amount : '0') }}</td>
              </tr>
              <tr>
                <th>Tax Amount</th>
                <td>{{ $ord->currency.' '.$ord->total_tax_amount }}</td>
              </tr>

              <tr>
                <th>Total:</th>
                <td>{{ $ord->currency.' '.$ord->total }}</td>
              </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
                @if($ord->is_import==1)
                <h4 style="padding-left: 15px;">Additional Cost</h4>

                <div class="col-xs-12 table-responsive">
                    <table id="t01" class="table table-striped">
                        <thead class="bg-danger">
                            <tr>
                                <th>Cost Type</th>
                                <th>Product</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th> Debit Amount </th>
                                <th>Credit Amount</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $importpurchase=\App\Models\ImportPurchase::where('purchase_order_id',$ord->id)->get();
                            @endphp
                            @foreach($importpurchase as $item)

                            <tr>
                                <td>{{$item->cost_type}}</td>
                                <td>{{ $item->product_id==0?'All Product':\App\Models\Product::find($item->product_id)->name }}</td>
                                <td>{{ $item->method }}</td>
                                <td>{{ number_format($item->amount,2) }}</td>
                                <td>{{ \App\Models\COALedgers::find($item->debit_account_ledger_id)->name }}</td>
                                <td>{{ \App\Models\COALedgers::find($item->credit_account_ledger_id)->name}}</td>
                                <td>{{ $item->description}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            <div class="col-md-3">
                <p class="text-muted well well-sm well-success no-shadow" style="margin-top: 10px;">
                    ___________________________________

                    <br>Authorized Signature
                </p>
            </div>

            <!-- /.row -->

            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-xs-12">



                </div>
            </div>
        </section>



    </div><!-- /.col -->

</div><!-- /.row -->


<!--Modal start-->
<div id="emailInvoice" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form id="sendVoiceInfo" method="post" action="/admin/invoice/email-invoice-info">

            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden" value="{{$ord->invoice_id}}" name="invoice_id" id="invoice_no">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Send invoice information to client</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">To:</label>
                        <input type="email" value="" class="form-control" name="email" id="email">
                    </div>
                    <?php
            $subjectInfo = str_replace('{order_reference_no}', $ord->invoice_id, "Your Invoice {app_code}{order_reference_no} from {company_name} has been shipped");
           // $subjectInfo = str_replace('{invoice_reference_no}', $saleDataInvoice->reference, $subjectInfo);
            $subjectInfo = str_replace('{app_code}', env('APP_CODE'), $subjectInfo);
            $subjectInfo = str_replace('{company_name}', env('APP_COMPANY') , $subjectInfo);
            ?>
                    <div class="form-group">
                        <label for="subject">Subject:</label>
                        <input type="text" class="form-control" name="subject" id="subject" value="{{$subjectInfo}}">
                    </div>
                    <div class="form-groupa">
                        <?php
                  $bodyInfo = str_replace('{customer_name}', $ord->name, "<p>Hi {customer_name},</p><p>Thank you for your order. Here’s a brief overview of your invoice: Invoice {app_code}{invoice_reference_no}{order_reference_no} is for Quotation {app_code}{order_reference_no}. The invoice total is {currency} {total_amount}, please pay before {due_date}.</p><p>If you have any questions, please feel free to reply to this email. </p><p><b>Billing address</b></p><p>{address}<br></p><p><b>Quotation summary<br></b></p><p><b></b>{invoice_summery}<br></p><p>Regards,</p><p>{company_name}<br></p><br><br>");

                  $bodyInfo = str_replace('{order_reference_no}', $ord->order_id, $bodyInfo);
                  $bodyInfo = str_replace('{invoice_reference_no}',$ord->invoice_id, $bodyInfo);
                  $bodyInfo = str_replace('{due_date}',$ord->due_date, $bodyInfo);
                  $bodyInfo = str_replace('{address}', $ord->address, $bodyInfo);
                  $bodyInfo = str_replace('{app_code}', env('APP_CODE'), $bodyInfo);
                  $bodyInfo = str_replace('{company_name}', env('APP_COMPANY'), $bodyInfo);
                  $bodyInfo = str_replace('{invoice_summery}','purchase', $bodyInfo);
                  $bodyInfo = str_replace('{currency}', $ord->currency, $bodyInfo);
                  $bodyInfo = str_replace('{total_amount}', number_format($ord->total_amount,2), $bodyInfo);
                  ?>
                        <textarea id="compose-textarea" name="message" id='message' class="form-control editor" style="height: 200px">{{$bodyInfo}}</textarea>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label><input type="checkbox" name="invoice_pdf" checked><strong>{{env('APP_CODE')}}{{$ord->invoice_id}}</strong></label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="Submit" class="btn btn-primary btn-sm">Send</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--Modal end -->

@endsection

@section('body_bottom')
<!-- Select2 js -->
@include('partials._body_bottom_select2_js_user_search')
@endsection
