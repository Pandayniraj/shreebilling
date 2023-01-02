@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">

                        &nbsp;
                        <a class="btn btn-social btn-foursquare" href="{!! route('admin.paymentaccount.create') !!}" title="Create Order">
                            <i class="fa fa-plus"></i> Create New
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.orders.enable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.orders.disable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i>
                        </a>

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="orders-table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>Payment No</th>
                                        <th>Quotation No</th>
                                        <th>Invoice No</th>
                                        <th>Customer Name</th>
                                        <th>Payment Method</th>
                                        <th>Amount</th>
                                        <th>Payment Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($paymentlist) && !empty($paymentlist))
                                    @foreach($paymentlist as $o)
                                        <tr>

                                                <td align="center">{!! Form::checkbox('chkClient[]', $o->id); !!}</td>
                                            <td><a href="#">{!! sprintf("%04d", $o->id) !!}</a></td>
                                            <td><a href="#">{{ $o->order_reference }}</a></td>

                                            <td><a href="#">{!! $o->invoice_reference !!}</a></td>
                                            <td><a href="#">{!! $o->lead->name !!}</a></td>
                                            <td>{!! $o->payment_type_id !!}</td>
                                            <td>{!! $o->amount !!}</td>
                                            <td>{!! $o->payment_date !!}</td>
                                            <td ><span class="label label-success">{!! $o->status !!}</span></td>

                                            <td>
                                                @if ( $o->isEditable() || $o->canChangePermissions() )
                                                    @if($o->status == 'paid' && \Request::get('type') == 'invoice')
                                                    <i class="fa fa-edit text-muted" title=""></i>
                                                    @else
                                                        <a href="{!! route('admin.paymentaccount.edit', $o->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                    @endif
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/orders/general.error.cant-edit-this-document') }}"></i>
                                                @endif


                                                @if ( $o->isDeletable() )
                                                    @if($o->status == 'paid' && \Request::get('type') == 'invoice')
                                                    <i class="fa fa-trash text-muted" title=""></i>
                                                    @else
                                                    <a href="{!! route('admin.orders.confirm-delete', $o->id) !!}?type={{\Request::get('type')}}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                    @endif
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/orders/general.error.cant-delete-this-document') }}"></i>
                                                @endif
                                            </td>




                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

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
            checkboxes = document.getElementsByName('chkClient[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
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

@endsection
