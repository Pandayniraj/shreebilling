@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables.net-bs/css/dataTables.bootstrap.min.css") }}" rel="stylesheet" type="text/css" />

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            Product Type Master
            <small>Create a new Type Master</small>
        </h1>
       {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
    </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.productcats.enable-selected', 'id' => 'frmLeadStatusList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">New Type Master </h3>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="{!! route('admin.producttypemaster.create') !!}" title="Create Type Master">
                            <i class="fa fa-plus-square"></i>
                        </a>

                        
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="clients-table">
                                <thead>
                                    <tr class="bg-blue">
                                        <td>ID </td>
                                        <th>Name</th>
                                        <th>Sales Ledger</th>
                                        <th>Purchase-Inventory Ledger</th>
                                        <th>COGS Ledger</th>
                                        <th> Purchase-Fixed Asset Ledger</th>
                                        <th> Purchase-Service Ledger</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td>ID </td>
                                        <th>Name</th>
                                        <th>Sales Ledger</th>
                                        <th>Purchase-Inventory Ledger</th>
                                        <th>COGS Ledger</th>
                                        <th> Purchase-Fixed Asset Ledger</th>
                                        <th> Purchase-Service Ledger</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($producttypemasters as $p)
                                  
                                        <tr>
                                            <td> {{$p->id}}</td>
                                            <td style="font-size: 16.5px">{{ $p->name }}</td>
                                            @if(isset($p->saleLedger))
                                            <td>{{ '['.$p->saleLedger->code.'] '.$p->saleLedger->name }}</td>
                                            @else
                                            <td> - </td>
                                            @endif
                                            @if(isset($p->purchaseLedger))    
                                                <td>{{ '['.$p->purchaseLedger->code.'] '.$p->purchaseLedger->name }}</td>
                                            @else
                                            <td>-</td>
                                            @endif
                                            @if(isset($p->cogsLedger))
                                            <td>{{  '['.$p->cogsLedger->code.'] '.$p->cogsLedger->name }}</td>
                                            @else
                                            <td>-</td>
                                            @endif
                                            @if(isset($p->assetLedger))
                                            <td>{{  '['.$p->assetLedger->code.'] '.$p->assetLedger->name }}</td>
                                            @else
                                            <td>-</td>
                                            @endif
                                            @if(isset($p->serviceLedger))
                                            <td>{{  '['.$p->serviceLedger->code.'] '.$p->serviceLedger->name }}</td>
                                            @else
                                            <td>-</td>
                                            @endif
                                            <td>
                                                @if ( $p->isEditable() || $p->canChangePermissions() )
                                                    <a href="{!! route('admin.producttypemaster.edit', $p->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit btn-circle btn-default"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/leadstatus/general.error.cant-edit-this-LeadStatus') }}"></i>
                                                @endif

                                                @if ( $p->isDeletable() )
                                                    <a href="{!! route('admin.producttypemaster.confirm-delete', $p->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/leadstatus/general.error.cant-delete-this-LeadStatus') }}"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
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
            checkboxes = document.getElementsByName('chkLeadStatus[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>

  <script>
        $(function() {
            $('#clients-table').DataTable({
                'pageLength'  : 65,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true,
                "paging"      : false
            });
        });

    </script>

@endsection
