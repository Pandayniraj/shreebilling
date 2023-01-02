@extends('layouts.master')
@section('content')
    <link href="{{ asset('/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            {!! $page_title !!}
            <small>{!! $page_description ?? 'DayBook' !!}</small>
        </h1>
        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}


    </section>
    <style type="text/css">
        .total {
            font-size: 16.5px;
        }
    </style>
    <?php
    $url = \Request::query();
    if($url){
        $url = \Request::getRequestUri() .'&';
    }
    else{
        $url = \Request::getRequestUri() .'?';
    }
    ?>
    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->

            <div class="box box-primary">

                <div class="box-body">
                    <span id="index_lead_ajax_status"></span>
                    <form method="GET" action="/admin/daybook">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                    <div class="col-md-2">
                                        <input type="text" name="start_date"
                                            class="form-control input-sm datepicker date-toggle" placeholder="start Date"
                                            value="{{ $start_date }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" name="end_date"
                                            class="form-control input-sm datepicker date-toggle" placeholder="end Date"
                                            value="{{ $end_date }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-default btn-sm" type="submit">Filter</button>
                                        <a class="btn btn-default btn-sm" type="button" href="/admin/daybook">Clear</a>

                                    </div>
                                    @if ($entries)
                                    <a href="{{ $url }}op=excel" class="btn btn-default btn-sm float-right">
                                         Excel <i class="fa fa-download"></i> </a>
                                @endif
                                </div>
                            </div>
                        </div><br />
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped" id="orders-table">
                            <thead>
                                <tr class="bg-gray">
                                    <th>S.N</th>
                                    <th>Transaction Date</th>
                                    <th>Trans Type</th>
                                    <th>Transaction No</th>

                                    <th>Legder</th>
                                    <th>Debit Amount</th>
                                    <th>Credit Amount</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_dr_amount=0;
                                    $total_cr_amount=0;
                                @endphp
                                @foreach ($entries as $entry)
                                @php
                                     $total_dr_amount+=$entry->dr_total;
                                    $total_cr_amount+=$entry->cr_total;
                                @endphp
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $entry->date }}</td>
                                        <td>{{ $entry->entrytype->name }}</td>
                                        <td><a
                                                href="/admin/entries/show/{{ $entry->entrytype->label }}/{{ $entry->id }}?fiscal_year={{ \Session::get('selected_fiscal_year') }}">{{ $entry->number }}</a>
                                        </td>

                                        <td  style="white-space: nowrap;">
                                            @php
                                                $legder_and_lastest_entry=TaskHelper::getDynamicEntryLedger($entry->id);
                                                // dd($legder_and_lastest_entry);
                                            @endphp
                                              {{$legder_and_lastest_entry}}
                                              <div style="color:grey">
                                                  {{$legder_and_lastest_entry}}...
                                              </div>
                                          </td>
                                        <td><a href="#" class="tip">{{ $entry->currency }}
                                                {{ number_format($entry->dr_total, 2) }}</a></td>
                                        <td><a href="#" class="tip">{{ $entry->currency }}
                                            {{ number_format($entry->cr_total, 2) }}</a></td>
                                        <td>{{ $entry->source }}
                                            <br>
                                            {!! $entry->checkLedger() !!}
                                            @if ($entry->entry_difference != 0)
                                                <br>
                                                {{ $entry->entry_difference }}
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-info" style="font-size: 20px;font-weight: bold">
                                    <td colspan="5">Total</td>
                                    <td>{{ $entry->currency }} {{number_format($total_dr_amount,2)}}</td>
                                    <td>{{ $entry->currency }} {{number_format($total_cr_amount,2)}}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div style="text-align: center;"> {!! $entries->appends(\Request::except('page'))->render() !!} </div>
                    </div> <!-- table-responsive -->
                </div><!-- /.box-body -->

            </div><!-- /.box -->
            <input type="hidden" name="order_type" id="order_type" value="{{ \Request::get('type') }}">
            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
    <!-- DataTables -->
    @include('partials._date-toggle')

    <script src="{{ asset('/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>

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
                id: id,
                purchase_status: purchase_status,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(data, status) {
                if (data.status == '1')
                    $("#index_lead_ajax_status").after(
                        "<span style='color:green;' id='index_status_update'>Status is successfully updated.</span>"
                        );
                else
                    $("#index_lead_ajax_status").after(
                        "<span style='color:red;' id='index_status_update'>Problem in updating status; Please try again.</span>"
                        );

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
