@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title }} Master
        <small>{{ $page_description }}</small>
    </h1>
  

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.productcats.enable-selected', 'id' => 'frmLeadStatusList') ) !!}
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Leave Category</h3>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="{!! route('admin.leavecategory.create') !!}" title="">
                            <i class="fa fa-plus-square"></i> Add
                        </a>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped" id="productcats-table">
                                <thead>
                                    <tr class="bg-olive">
                                        
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Days</th>
                                         <th>Type</th>
                                        <th>Lapse</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Days</th>
                                         <th>Type</th>
                                        <th>Lapse</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($leave_categories as $p)
                                        <tr>
                                            
                                            <td>{{ $p->leave_code }}</td>
                                            <td>{{ $p->leave_category }}</td>
                                            <td>{{ $p->leave_quota }}</td>
                                            <td>{{ $p->leave_type }}</td>
                                            <td>{{ $p->lapse_type }}</td>
                                            <td>
                                                @if ( $p->isEditable() || $p->canChangePermissions() )
                                                    <a href="{!! route('admin.leavecategory.edit', $p->leave_category_id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/leadstatus/general.error.cant-edit-this-LeadStatus') }}"></i>
                                                @endif

                                                <a href="{!! route('admin.leavecategory.confirm-delete', $p->leave_category_id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                            
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
        $('#LeadStatus-table').DataTable({
            
        });
    });
    </script>

@endsection
