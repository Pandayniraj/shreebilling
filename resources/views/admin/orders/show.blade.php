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

<div class='row'>
    <div class='col-md-12'>

        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <img style="max-width: 240px" height="" src="{{env('APP_URL')}}{{ '/org/'.$organization->logo }}">
                        <span class="pull-right">
                            <span class="label @if($ord->source =='lead') label-primary @else label-success @endif">{{ ucwords($ord->source) }} {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}}</span>
                            &nbsp;
                            <a href="/admin/orders">
                                <button type="button" class="btn btn-xs btn-success pull-right">
                                    <i class="fa fa-times-circle"></i> Close
                                </button>
                            </a> &nbsp;
                            @if($ord->status != 'Invoiced')
                            @if($ord->order_type == 'proforma_invoice')
                            <a class="btn btn-xs btn-primary pull-right" href="{!! route('admin.invoice.confirm-invoice', $ord->id) !!}" data-toggle="modal" data-target="#modal_dialog"> Post Invoice</a> &nbsp;
                            @endif
                            @endif
                            <a href="/admin/order/print/{{ $ord->id }}" target="_blank" class="btn btn-xs btn-default pull-right"><i class="fa fa-print"></i> Print</a>&nbsp;&nbsp;


                            @if($ord->status != 'Invoiced')
                            @if($ord->order_type == "quotation")
                            <a href="/admin/orders/{{$ord->id}}/edit?type=quotation">
                                <button type="button" class="btn btn-xs btn-primary pull-right">
                                    <i class="fa fa-times-circle"></i> Edit
                                </button>
                            </a> &nbsp;

                            @elseif($ord->order_type == "order")
                            <a href="/admin/orders/{{$ord->id}}/edit?type=order">
                                <button type="button" class="btn btn-xs btn-primary pull-right">
                                    <i class="fa fa-times-circle"></i> Edit
                                </button>
                            </a> &nbsp;
                            @endif
                            @endif
                            @if($ord->order_type == "quotation" && $ord->source == 'lead')
                            <a href="/admin/orders/convert_to_pi_confirm/{{$ord->id}}" data-toggle="modal" data-target="#modal_dialog">
                                <button type="button" class="btn btn-xs btn-primary pull-right">
                                    <i class="fa fa-times-circle"></i> Post to PI
                                </button>
                            </a> &nbsp;
                            @endif


                            &nbsp;
                            <a href="/admin/order/generatePDF/{{ $ord->id }}">
                                <button type="button" class="btn btn-xs btn-primary pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-download"></i> PDF
                                </button>
                            </a> &nbsp;

                            <a href="#" class="btn btn-xs btn-default pull-right" data-toggle="modal" data-target="#emailInvoice"> <i class="fa fa-envelope"></i> Email </a> &nbsp;


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
                    To
                    <address>


                       <strong> @if($ord->source=='client') {{ $ord->client->name }} @else {{ $ord->lead->name }} @endif</strong>
                       <br />
                       Address: {!! nl2br($ord->address ) !!}<br />
                       Email: @if($ord->source == 'lead')<a href="mailto:{{ $ord->lead->email }}">{{ $ord->lead->email }}</a>@else<a href="mailto:{{ $ord->client->email }}">{{ $ord->client->email }}</a>@endif<br>
                       @if($ord->source == 'client') Customer's PAN: {!! $ord->client->vat !!} @endif

                   </address>
               </div>
               <!-- /.col -->
               <div class="col-sm-4 invoice-col">
                <b>{{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} @if($ord->order_type == 'quotation'){{\FinanceHelper::getAccountingPrefix('QUOTATION_PRE')}}@else{{\FinanceHelper::getAccountingPrefix('SALES_PRE')}}@endif{{ $ord->id }}</b><br>


                <b>Bill Date:</b> {{ date("d/M/Y", strtotime($ord->bill_date )) }}<br>
                <?php $timestamp = strtotime($ord->created_at) ?>
                <b>Valid Till:</b> {{ date("d/M/Y", strtotime("+30 days", $timestamp )) }}<br>
                <b>Terms :</b> {!! $ord->terms !!}<br>
                <b>{{ ucwords($ord->source) }} Account:</b> #@if($ord->source == 'lead') {{ $ord->lead->id }} @else {{ $ord->client->id }} @endif<br>
                <b>Source:</b> {{ ucfirst($ord->source) }}
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <p>Thank you for choosing {{ env('APP_COMPANY') }}. Your {{ ucwords(str_replace("_", " ", ucfirst($ord->order_type)))}} is detailed below. If you find errors or desire certain changes, please contact us.</p>
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table id="" class="table table-striped">
                    <thead class="bg-gray">
                        <tr>
                            <th>Particulars</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Unit</th>
                            <th>Total</th>
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
                            <td>{{ $odv->quantity }}</td>
                            <td>{{ $odv->price }}</td>
                            <td>{{ $odv->units->name }}</td>
                            <td>{{ env('APP_CURRENCY').' '.$odv->total }}</td>
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


             @if($ord->order_type != "quotation")
             <h3>EMI Payment Terms</h3>
             <table id="" class="table table-striped">
                <thead class="bg-gray">
                    <tr>
                        <th>S.N.</th>
                        <th>Date</th>
                        <th >Condition</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($OrderPaymentTerms as $opk => $opv)

                    <tr>
                        <td>{{ $opk+1 }}</td>
                        <td>{{ date('dS M, Y',strtotime($opv->term_date)) }}</td>
                        <td>{{ $opv->term_condition }}</td>
                        <td>{{ number_format($opv->term_amount,2) }}</td>
                        <td>
                            @if($opv->status == 1)
                            <span class="label label-success"> Paid</span>
                            @else
                            <span class="label label-warning"> Due</span>
                            @endif
                        </td>
                        <td>
                            @if($opv->status == 0)
                            <a href="{!! route('admin.order.payment_term.update', $opv->id) !!}" title="Approve Payment"><i class="fa fa-credit-card"></i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            <?php
            $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
            ?>




            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">

                <br/> Issued by: {{\Auth::user()->username}}<br/>
                Issue Time: {{ date("F j, Y, g:i a") }}
            </p>

            <p class="text-muted well well-sm well-success no-shadow" style="margin-top: 10px;">
                ___________________________________

                <br>Authorized Signature
            </p>

            <img style="width:250px;" src="{{ '/org/'.\Auth::user()->organization->stamp }}" >
        </div>

        <!-- /.col -->
        <div class="col-xs-6">


            <div class="table-responsive">
                <table id="" class="table table-striped">
                    <tbody>
                        <tr style="padding:0px; margin:0px;">
                            <th style="width:50%">Sub Total:</th>
                            <td>{{ env('APP_CURRENCY').' '. number_format($ord->subtotal,2) }}</td>
                        </tr>
                        <tr style="padding:0px; margin:0px;">
                            <th style="width:50%">Discount Percentage(%)</th>
                            <td>{{$ord->discount_percent }}%</td>
                        </tr>
                        <tr>
                            <th style="width:50%">Taxable Amount</th>
                            <td>{{env('APP_CURRENCY').' '. number_format($ord->taxable_amount,2) }}</td>
                        </tr>
                        <tr style="padding:0px; margin:0px;">
                            <th style="width:50%">Tax Amount(13%):</th>
                            <td>{{ env('APP_CURRENCY').' '. number_format($ord->tax_amount,2) }}</td>
                        </tr>
                        <tr style="padding:0px; margin:0px;">
                            <th style="width:50%">Total:</th>
                            <td style="font-size: 16.5px">{{ env('APP_CURRENCY').' '. number_format($ord->total_amount,2) }}</td>
                        </tr>
                                <!--   <tr>
                <th>Discount:</th>
                <td>{{ env('APP_CURRENCY').' '.($ord->discount_amount ? $ord->discount_amount : '0') }}</td>
              </tr>
              <tr>
                <th>Tax Amount</th>
                <td>{{ env('APP_CURRENCY').' '.$ord->total_tax_amount }}</td>
              </tr>

              <tr>
                <th>Total:</th>
                <td>{{ env('APP_CURRENCY').' '.$ord->total }}</td>
            </tr> -->
        </tbody>
    </table>
    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;text-transform: capitalize;font-size: 16.5px">
        In Words: {{ $f->format($ord->total_amount)}}
    </p>
    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
        Above information is only an estimate of services/goods described above.
    </p>

    <h4> Special Notes and Instruction</h4>
    <p class="text-muted well well-sm well-primary no-shadow" style="margin-top: 10px;">
        {!! nl2br($ord->comment) !!}
    </p>
</div>
</div>
<!-- /.col -->
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
        <form id="sendVoiceInfo" method="post" action="/admin/mail/quotation/{{$ord->id}}/send-mail-modal" enctype="multipart/form-data">

            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden" value="{{$ord->invoice_id}}" name="invoice_id" id="invoice_no">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Send Order information to client</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">From:</label>
                        <input type="email" value="{{env('APP_EMAIL')}}" class="form-control" name="mail_from" id="email">
                    </div>
                    <div class="form-group">
                        <label for="email">To:</label>
                        <input type="email" value="@if($ord->source == 'lead') {{ $ord->lead->email }} @else {{ $ord->client->email }} @endif" class="form-control" name="mail_to" id="email">
                    </div>
                    <?php
                    $subjectInfo = str_replace('{order_reference_no}',$ord->id, "Your order {app_code}{order_reference_no} from {company_name} ");
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
                        $bodyInfo = str_replace('{customer_name}', $ord->name, "<p>Hi {customer_name},</p><p>Thank you for your order. Here’s a brief overview of your order: Order {app_code}{invoice_reference_no}{order_reference_no} is for Quotation {app_code}{order_reference_no}. The order total is {currency} {total_amount}, this is valid till {due_date}.</p><p>If you have any questions, please feel free to reply to this email. </p><p><b>Billing address</b></p><p>{address}<br></p><p></p><p>Regards,</p><p>{company_name}<br></p><br><br>");



                        $bodyInfo = str_replace('{order_reference_no}', $ord->order_id, $bodyInfo);
                        $bodyInfo = str_replace('{invoice_reference_no}',$ord->invoice_id, $bodyInfo);
                        $bodyInfo = str_replace('{due_date}',$ord->due_date, $bodyInfo);
                        $bodyInfo = str_replace('{address}', $ord->address, $bodyInfo);
                        $bodyInfo = str_replace('{app_code}', env('APP_CODE'), $bodyInfo);
                        $bodyInfo = str_replace('{company_name}', env('APP_COMPANY'), $bodyInfo);
                        $bodyInfo = str_replace('{invoice_summery}', $itemsInformation, $bodyInfo);
                        $bodyInfo = str_replace('{currency}', env('APP_CURRENCY'), $bodyInfo);
                        $bodyInfo = str_replace('{total_amount}', number_format($ord->total_amount,2), $bodyInfo);
                        ?>
                        <textarea id="compose-textarea" name="message" id='message' class="form-control editor" style="height: 200px">{{$bodyInfo}}</textarea>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label><input type="checkbox" name="attachment" checked><strong>{{env('APP_CODE')}}{{$ord->id}}</strong></label>
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
