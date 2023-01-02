@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title !!}
        <small>{!! $page_description !!}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}


</section>
<style type="text/css">
    .total{
        font-size: 16.5px;
    }
</style>
<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
     
        <div class="box box-primary">
            
            <div class="box-body">
                <span id="index_lead_ajax_status"></span>
            <form method="GET" action="/admin/cash_in_out">
            <div class="row">
              <div class="col-md-12">
                        <div class="form-group">
                            
                            <div class="col-md-2">
                                <input type="text" name="start_date" class="form-control input-sm datepicker date-toggle" placeholder="start Date" 
                                    value="{{ $start_date }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="end_date" class="form-control input-sm datepicker date-toggle" placeholder="end Date" 
                                value="{{ $end_date }}">
                            </div>
                         
                       

                           
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                            <a class="btn btn-danger btn-sm" type="button" href="/admin/edm/order">Clear</a>
                        </div>
                        </div>
                    </div>
                
                    
                </div><br/>
            </form>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="orders-table">
                        <thead>
                            <tr class="bg-maroon">
                                <th>Client</th>
                                <th>Date</th>
                                <th>Cash In</th>
                                <th>Cash Out</th>
                                <th>Created By</th>
                                <th>Paid By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $cashIn = 0; $cashOut = 0;   @endphp
                            @foreach($orders as $key=>$value)
                              <tr>
                                <td class="font-st">{{  $value->client->name  }} 
                                    
                                </td>
                                <td>{{ strtotime($value->bill_date) ?date('Y-m-d',strtotime($value->bill_date)) :'-'  }}</td>

                                <td class="font-st">{{ $value->total_amount  }}</td>
                                 <td>-</td>
                                <td>{{ $value->user->first_name  }} {{ $value->user->last_name  }}</td>

                                <td>Sales Made</td>
                              </tr>
                            @endforeach

                            @foreach($purhcase as $key=>$value)
                              <tr class="bg-danger">
                                <td class="font-st">{{  $value->client->name  }} 
                                   
                                </td>
                                <td>{{ strtotime($value->bill_date) ? date('Y-m-d',strtotime($value->bill_date)) :'-'  }}</td>
                                <td>-</td>
                                <td class="font-st">{{ $value->total  }}</td>
                                
                                <td>{{ $value->user->first_name  }} {{ $value->user->last_name  }}</td>
                                <td>Purchase  Made</td>
                              </tr>
                            @endforeach


                            @foreach($payment as $key=>$value)

                            @if($value->purchase_id) {{-- purchase --}}
                            <tr class="bg-danger">
                                <td class="font-st">{{  $value->purchase->client->name  }}</td>
                                <td>{{ strtotime($value->date) ?date('Y-m-d',strtotime($value->date)) :'-'  }}</td>
                                <td>-</td>
                                <td class="font-st">{{ $value->amount  }}</td>
                                <td>{{ $value->createdby->first_name  }} {{ $value->createdby->last_name  }}</td>
                                <td>{{ $value->paidby->name }}</td>
                                @php $cashOut += $value->amount  @endphp
                            </tr>
                            @elseif($value->sale_id)
                             <tr>
                                <td class="font-st">{{  $value->sale->client->name  }} 
                                    ({{ $value->sale->outlet->name }})
                                </td>
                                <td>{{ strtotime($value->date) ?date('Y-m-d',strtotime($value->date)) :'-'  }}</td>
                                <td class="font-st">{{ $value->amount  }}</td>
                                <td>-</td>
                                <td>{{ $value->createdby->first_name  }} {{ $value->createdby->last_name  }}</td>
                                 <td>{{ $value->paid_by }}</td>

                            </tr>

                                @php $cashIn += $value->amount  @endphp
                            @endif
                            @endforeach
                            @foreach($bankingIncome as $key=>$value)
                             <tr>
                                <td class="font-st">
                                <h5 class="font-st">
                                    {{$types[$value->income_type]}}
                                    <small>
                                     @if(strlen($value->customers->name) > 25)  {{ substr($value->customers->name,0,25).'...' }} @else  {{ ucfirst($value->customers->name) }} @endif
                                    </small>
                                </h5>
                                </td>
                                <td>{{ strtotime($value->date) ?date('Y-m-d',strtotime($value->date)) :'-'  }}</td>
                                <td class="font-st">{{ $value->amount  }}</td>
                                <td>-</td>
                                <td>{{ $value->user->first_name  }} {{ $value->user->last_name  }}</td>
                                 <td>Bank & Income</td>

                            </tr>
                            @php $cashIn += $value->amount  @endphp
                            @endforeach

                            @foreach($expenses as $key=>$value)
                             <tr class="bg-danger">
                               <td class="font-st">{{  $value->vendor->name ?? '' }}</td>
                               <td>{{  $value->date }}</td>
                               <td>-</td>
                               <td class="font-st">{{ $value->amount }}</td>
                               <td>{{ $value->user->first_name  ?? ''}} {{ $value->user->last_name  ?? ''}}</td>
                               <td>Expenses</td>
                            </tr>
                            @php $cashOut += $value->amount  @endphp
                            @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td class="font-st">Total</td>
                            <td class="font-st">{{$cashIn}}</td>
                            <td class="font-st">{{$cashOut}}</td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <th class="font-st">Balance</th>
                            <th class="font-st">{{ $cashIn - $cashOut }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div> <!-- table-responsive -->
            </div><!-- /.box-body -->

        </div><!-- /.box -->
        <input type="hidden" name="order_type" id="order_type" value="{{\Request::get('type')}}">
        {!! Form::close() !!}
    </div><!-- /.col -->

</div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
@include('partials._date-toggle')

<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkClient[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }
$('.date-toggle').nepalidatetoggle();
</script>


<script type="text/javascript">
    $(document).on('change', '#order_status', function() {

        var id = $(this).closest('tr').find('.index_sale_id').val();

        var purchase_status = $(this).val();
        $.post("/admin/ajax_order_status", {
                id: id
                , purchase_status: purchase_status
                , _token: $('meta[name="csrf-token"]').attr('content')
            }
            , function(data, status) {
                if (data.status == '1')
                    $("#index_lead_ajax_status").after("<span style='color:green;' id='index_status_update'>Status is successfully updated.</span>");
                else
                    $("#index_lead_ajax_status").after("<span style='color:red;' id='index_status_update'>Problem in updating status; Please try again.</span>");

                $('#index_status_update').delay(3000).fadeOut('slow');
                //alert("Data: " + data + "\nStatus: " + status);
            });

    });

</script>
<script type="text/javascript">
    $("#btn-submit-filter").on("click", function() {

        status = $("#filter-status").val();
        type = $("#order_type").val();

        window.location.href = "{!! url('/') !!}/admin/orders?status=" + "&type=" + type;
    });

    $("#btn-filter-clear").on("click", function() {

        type = $("#order_type").val();
        window.location.href = "{!! url('/') !!}/admin/edm/order";
    });

</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.customer_id').select2();
    });

    $('.datepicker').datetimepicker({

        format: 'YYYY-MM-DD',
    })

</script>

@endsection
