@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        EMI Collection Report
    </h1>

    

</section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
        <div class="box box-primary">

            <div class="box-header with-border">

                <div class="wrap" style="margin-top:5px;">
                    <form action="" method="get">
                    <div class="filter form-inline" style="margin:0 30px 0 0;">
                        Start Date : 
                        {!! Form::text('start_date',  Request::get('start_date'), ['id'=>'start_date', 'class'=>'form-control input-sm', 'style'=>'width:200px; display:inline-block;']) !!}&nbsp;&nbsp;
                        End Date : 
                        {!! Form::text('end_date',  Request::get('end_date'), ['id'=>'end_date', 'class'=>'form-control input-sm', 'style'=>'width:200px; display:inline-block;']) !!}&nbsp;&nbsp;

                       

                        {!! Form::select('status',['0'=>'Pending','1'=>'Paid'] , Request::get('status') ,
                            ['class'=>'form-control input-sm','id'=>'pay_status'])  !!}&nbsp;&nbsp;

                        <button type="submit" class="btn btn-primary btn-sm" id="btn-submit-filter">
                            <i class="fa fa-list"></i> Filter
                        </button>

                        <a href="/admin/orders/payment_term/emi_list" class="btn btn-danger btn-sm" id="btn-filter-clear">
                            <i class="fa fa-close"></i> Clear
                        </a>

                    </div>
                    </form>
                </div>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>



            <div class="box-body">

                <span id="index_lead_ajax_status"></span>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped" id="orders-table">
                        <thead>
                            <tr class="bg-info">
                                
                                <th>S.N</th>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Condition</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            @foreach($payment_terms as $key=>$terms)
                                <tr>
                               
                                    

                                    <td>{!! $key+1 !!}</td>
                                    <td>{!! $terms->term_date !!}</td>
                                    <td>{!! $terms->orders->client->name !!}</td>
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
