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
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="FiscalYear-table">
                        <thead>
                            <tr class="bg-info">
                                <th style="text-align: center">
                                    ID
                                </th>
                                <th>FlightId</th>
                                <th>ReturnFlightId</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Number</th>
                                <th>Base Fair</th>
                                <th>Total Amount</th>
                                <th>Service Fee</th>
                                <th>Currency</th>
                                <th>Paid By</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $v)
                            <tr>
                                <td align="center">
                                    {{$v->id}}
                                </td>
                                <td>{!! $v->FlightId !!}</td>
                                <td>{!! $v->ReturnFlightId !!}</td>
                                <td><a href="/admin/isssued/tickets/{{$v->id}}">{{$v->contact_name}}</a></td>
                                <td>{{ $v->email_address}}</td>
                                <td>{{ $v->contact_no}}</td>
                                <td>{{ $v->base_fair }}</td>
                                <td>{{ $v->total_amount}}</td>
                                <td>{{ (($v->total_amount-$v->base_fair)/$v->base_fair)*100 }}</td>
                                <td>{{ $v->currency}}</td>
                                <td>{{ $v->paid_by}}</td>
                                <td>{{ $v->user->username}}</td>

                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                    {!! $reservations->render() !!}
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
