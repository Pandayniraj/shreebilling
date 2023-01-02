@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

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
        <!-- Box -->

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Issued ticket list</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered"  id="FiscalYear-table">
                        <thead>
                            <tr class="bg-info">
                                <th style="text-align: center">
                                    ID
                                </th>
                                <th>Passengar</th>
                                <th>Ticket Num.</th>
                                <th>Total Price</th>
                                <th>Currency</th>
                                <th>PNR</th>
                                <th>Flight date</th>
                                <th>Flight Type</th>
                                <th>Action</th>
                                <th>Invoice</th>
                                <th>Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issuedtickets->groupBy('reservation_id') as $tb)
                            <tr class="bg-danger">
                                 @php $res = $tb->first()->reservation;  @endphp 
                                <td colspan="100%">
                                   <span class="pull-left" style="font-size: 16.5px;">
                                        <a href="/admin/isssued/tickets/{{$res->id}}"> {{ ucfirst($res->contact_name) }}</a>
                                        
                                    </span>
                                    <span align='center' style="margin-left: 40%">
                                    <i class="fa fa-plane"></i>     {{  \FlightHelper::getAirlineName($res['airline_code'],strlen($res['airline_code']))->name }}
                                    </span>
                                    <span class="pull-right text-white">
                                        {{$res->from}} - {{$res->to}}
                                    </span>
                                </td>
                            </tr>
                            @foreach($tb as $v)

                            <tr>
                                <td align="center">
                                    {{$v->id}}
                                </td>
                                <td style="font-size: 16.5px;"> <a href="/admin/isssued/tickets/{{$v->reservation->id}}">
                                    <h4>
                                        {{$v->contact_name}}<br>
                                   <small> {{ $v->user->first_name }} {{ $v->user->last_name }} </small>
                                </h4>
                                </a></td>
                                <td> {{ $v->ticket_no}} </td>
                                
                                <td>{{ $v->reservation->total_amount }}</td>
                                <td>{{$v->reservation->currency}}</td>
                                <td>{{$v->pnr}}</td>

                                <td><i class="fa  fa-calendar-plus-o"></i> {{$v->reservation->flight_date}} 
                                    <i class="fa fa-clock-o"></i> {{ $v->reservation->flight_time }} 
                                </td>
                                <td>
                                    @if($v->reservation->flight_type =='international')
                                    <label class="label label-success">
                                    {{ ucwords($v->reservation->flight_type) }}
                                    </label>
                                    @else
                                    <label class="label label-primary">
                                    {{ ucwords($v->reservation->flight_type) }}
                                    </label>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="/booking/download/pdf/{{$v->reservation_id}}">
                                        @csrf
                                    <button href="/booking/download/pdf/{{$v->reservation_id}}" title="PDF" class="btn btn-primary btn-xs no-loading">
                                        <i class="fa fa-download"></i></button>
                                    </form>
                                    @if($v->reservation->flight_type =='international')
                                        <a href="/admin/save/pnr/{{$v->pnr}}" title="Save PNR"><i class="fa  fa-save (alias)"></i></a>
                                    @endif
                                </td>  
                                <td>
                                    <a href="/admin/electronic/invoice/print/{{$v->id}}" title="Invoice Print"><i class="fa fa-print"></i></a> 

                                   <a href="/admin/electronic/invoice/pdf/{{$v->id}}"  title="Invoice PDF"> <i class="fa fa-file-pdf-o"></i> </a>
                                </td>
                                <td>
                                    <a href="/admin/electronic/tickets/print/{{$v->id}}" title="Ticket Print"><i class="fa fa-print"></i></a>

                                    <a href="/admin/electronic/tickets/pdf/{{$v->id}}" title="Ticket PDF"><i class="fa fa-file-pdf-o"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                    {!! $issuedtickets->render() !!}
                </div> <!-- table-responsive -->

            </div><!-- /.box-body -->
        </div><!-- /.box -->
        {!! Form::close() !!}
    </div><!-- /.col -->

</div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkFiscalYear[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

</script>


@endsection
