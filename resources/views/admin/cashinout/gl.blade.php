@extends('layouts.master')
@section('content')
    <link href="{{ asset('/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"
        type="text/css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            {!! $page_title !!}
            <small>{!! $page_description ?? 'GL' !!}</small>
        </h1>
        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}


    </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->

            <div class="box box-primary">
                <div class="box-header">
                    <div class="row" style="margin-left:-5px; margin-top: 5px;">
                        <form method="get" action="/admin/gl" class="form-section">
                            <div class="filter form-inline">

                                <select name="ledger_id" id="ledger_id" class="form-control searchable">
                                    <option value="">--SELECT LEDGER NAME--</option>
                                    @foreach ($filter_legders_name as $ledger_id=>$ledger_name)
                                        <option value="{{$ledger_id}}" @if(\Request::get('ledger_id') && \Request::get('ledger_id')==$ledger_id) selected @endif>{{$ledger_name}}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-default btn-height" id="btn-submit-filter" type="submit">
                                    <i class="fa fa-list"></i> Filter
                                </button>
                                <a href="/admin/gl" class="btn btn-default btn-height" id="btn-filter-clear" >
                                    <i class="fa fa-close"></i> Clear
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box-body">
                    <span id="index_lead_ajax_status"></span>


                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped" id="orders-table">
                            <thead>
                                <tr class="bg-gray">
                                    <th>L.N</th>
                                    <th style="font-size: 16.5px; font-weight:bold">Legder</th>
                                    <th> Code</th>
                                    <th style="font-size: 16.5px; font-weight:bold">Opening Balance</th>
                                    <th style="font-size: 16.5px; font-weight:bold">Closing Balance</th>
                                    <th>Action</th>


                                </tr>
                            </thead>
                            <tbody>

                                @foreach($ledgers as $k=> $v)
                                    <tr>
                                        <td> {{ $v->id}}</td>
                                        <td> <a href="/admin/accounts/reports/ledger_statement?ledger_id={{$v->id}}">{{ $v->name}}</a> </td>
                                        <td> {{ $v->code}}</td>
                                        @php    
                                        
                                            $opening_balance =TaskHelper::getLedgersOpeningBalance($v,$start_date??'',$fiscal??'');
                                            $dr_cr =TaskHelper::getLedgerDrCr($v,$fiscal??'',$start_date??'',$end_date??'');
                                            $closing_balance=\App\Helpers\TaskHelper::getLedgerClosing($opening_balance,$dr_cr['dr_total'],$dr_cr['cr_total']);
                                        @endphp
                                        <td> {{ $v->op_balance}}</td>
                                        <td>
                                            @if( $closing_balance['dc'] == 'D')
                                            {{'Dr '.number_format($closing_balance['amount'],2)}}
                                            @else

                                            @endif
                                        </td>
                                        <td> <a href="{{route('admin.chartofaccounts.edit.ledgers', $v->id)}}">Edit</a></td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                        <div style="text-align: center;">{!! $ledgers->appends(\Request::except('page'))->render() !!}   </div>
                    </div> <!-- table-responsive -->
                </div><!-- /.box-body -->

            </div><!-- /.box -->


        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script language="JavaScript">
    $('.searchable').select2();

</script>
@endsection



