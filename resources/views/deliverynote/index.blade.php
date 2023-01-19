@extends('layouts.master')
@section('content')

    {{-- <link href="{{ asset('/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" --}}
        {{-- type="text/css" /> --}}

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? 'Page Description' }}</small>
        </h1>
        <p>DN</p>


        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
    </section>


    <div class='row'>

        <div class='col-md-12'>

            <!-- Box -->
            {{-- {!! Form::open() !!} --}}
            <div class="box box-primary">
                <div class="box-header with-border">

                    <a class="btn btn-info btn-sm pull-right" title="Create new invoice"
                        href="{{ route('admin.deliverynote.create') }}">
                        <i class="fa fa-plus"></i>&nbsp;<strong> Create new Delivery Note</strong>
                    </a>

                </div>

                <div class="row">

                    <form method="POST" action="{{ route('admin.deliverynote.sales') }}">
                        {{ csrf_field() }}
                        {{-- <input type="hidden" name="excel" value="excel"> --}}
                        <div class="col-md-2">
                            <input type="text" class="form-control input-sm datepicker" placeholder="startdate"
                                name="start_date" value="{{date('Y-m-1')}}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control input-sm datepicker" placeholder="Enddate"
                                name="end_date" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="col-md-3">
                            <input type="submit" class="btn btn-success btn-sm" title="view reconcilation"
                                value="Company Reconcilation">
                        </div>
                    </form>
                </div>
                <div class="box-body">

                    <div class="table-responsive">

                        <table class="table table-hover table-bordered table-striped" id="orders-table">
                            <thead>
                                <tr class="bg-danger">
                                    <th>
                                        DN-Date
                                    </th>
                                    <th>DN No.</th>
                                    <th>Sales No</th>
                                    <th>Party name</th>
                                    <th>Sales Date</th>
                                    <th>Total</th>
                                    <th>Tools</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($deliverynote) && !empty($deliverynote))
                                    @foreach ($deliverynote as $o)
                                        <tr>
                                            <td>{{ date('D dS M y', strtotime($o->delivery_note_date)) }}</td>
                                            <td>#{{ $o->id }}</td>
                                            <td>#{{ $o->sales_bill_no }}</td>
                                            <td><span style="font-size: 16.5px">
                                                    {{ \App\Models\Client::where('id', $o->client_id)->first()->name }}
                                                </span></td>
                                            <td>{{ date('D dS M y', strtotime($o->sales_bill_date)) }}</td>
                                            <td>{!! number_format($o->subtotal, 2) !!}</td>
                                            <td>
                                                <a href="/admin/deliverynote/print/{{ $o->id }}" target="_blank"
                                                    title="print"><i class="fa fa-print"></i></a>

                                            </td>
                                            <td>
                                                <a href="{!! route('admin.deliverynote.edit', $o->id) !!}"
                                                    title="{{ trans('general.button.edit') }}"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{!! route('admin.deliverynote.confirm-delete', $o->id) !!}" data-toggle="modal"
                                                    data-target="#modal_dialog"
                                                    title="{{ trans('general.button.delete') }}"><i
                                                        class="fa fa-trash deletable"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {!! $deliverynote->links() !!}
                    </div> <!-- table-responsive -->

                </div><!-- /.box-body -->
            </div><!-- /.box -->
            {{-- {!! Form::close() !!} --}}
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
    <!-- DataTables -->
    {{-- <script src="{{ asset('/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js') }}"></script> --}}

    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkClient[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>

    <script>
        $(function() {
            $('#orders-table').DataTable({
                pageLength: 25
            });
        });
    </script>
    <script type="text/javascript">
        $(function() {
            $('.datepicker').datetimepicker({
                //inline: true,
                format: 'YYYY-MM-DD',
                sideBySide: true,
                allowInputToggle: true
            });

        });
    </script>
@endsection
