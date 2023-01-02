@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        EMI Dues Notification
    </h1>



</section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
        <div class="box box-primary">



            <div class="box-body">

                <span id="index_lead_ajax_status"></span>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped" id="orders-table">
                        <thead>
                            <tr class="bg-info">

                                <th>S.N</th>
                                <th>Date</th>
                                <th>Client</th>
                                <th>P. Invoice</th>
                                <th>Condition</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($payment_dues as $key=>$terms)
                                <tr>



                                    <td>{!! $key+1 !!}</td>
                                    <td><b>{!! $terms->term_date !!}</b>
                                    ({!! TaskHelper::getNepaliDateFormatted($terms->term_date) !!})
                                </td>
                                    <td>{!! $terms->orders->client->name !!}</td>
                                    <td><a target="_blank" href="/admin/orders/{{ $terms->order_id }}">View</a></td>
                                    <td>{!! $terms->term_condition !!}</td>
                                    <td>{!! number_format($terms->term_amount,2) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- table-responsive -->

            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div><!-- /.col -->

</div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<!-- DataTables -->

<script type="text/javascript">
    $(function() {
        $('#start_date').datepicker({
                 //format: 'YYYY-MM-DD',
                dateFormat: 'yy-m-d',
                sideBySide: true,

            });
        $('#end_date').datepicker({
                 //format: 'YYYY-MM-DD',
                dateFormat: 'yy-m-d',
                sideBySide: true,

            });
        });
</script>


@endsection
