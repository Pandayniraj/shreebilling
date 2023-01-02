@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>


        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.proposal.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="{!! route('admin.proposal.create') !!}" title="{{ trans('admin/proposal/general.button.create') }}">
                            <i class="fa fa-plus"></i> Create Proposal or Contract
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.proposal.enable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.proposal.disable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i>
                        </a>

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="proposal-table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;">
                                                <i class="fa fa-check-square"></i>
                                            </a>
                                        </th>
                                        
                                        <th>Subject</th>
                                        <th>Client</th>
                                        <th>Status</th>
                                        <th>Product</th>
                                        <th>Tools</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($proposal) && !empty($proposal)) 
                                    @foreach($proposal as $k)
                                        <tr>

                                            <td class="" align="center">{!! Form::checkbox('chkClient[]', $k->id); !!}</td>
                                          
                                            <td class=""><h4> {!! link_to_route('admin.proposal.show', $k->subject, [$k->id], []) !!}</h4></td>

                                            <td class=""><a href="/admin/leads/{{ $k->client_lead_id }}?type={{ $k->lead->leadType->name }}">{{ $k->lead->title.' '.$k->lead->name }}</a> </td>

                                            <td class="">

                                                @if($k->status == 'paused')
                                                <span class="label bg-red">{{ ucfirst($k->status)}}</span>
                                                @elseif($k->status == 'sold')
                                                <span class="label bg-primary">{{ ucfirst($k->status)}}</span>
                                                @else
                                                <span class="label bg-green">{{ ucfirst($k->status)}}</span>
                                                @endif

                                            </td> 


                                            <td class="">{{ ucfirst($k->product->name)}}

                                            </td>
                                            <td>  <a href="/admin/proposal/generatePDF/{{$k->id}}"><i class="fa fa-download"></i></a>
                                                 <a href="/admin/proposal/print/{{$k->id}}"><i class="fa fa-print"></i></a>
                                                 <a href="/admin/proposal/copy/{{$k->id}}"><i class="fa fa-copy"></i></a>

                                            </td>
                                            <td class="">
                                                @if ( $k->isEditable()  && $k->status != 'sold' )
                                                    <a href="{!! route('admin.proposal.edit', $k->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-ban text-muted" title="{{ trans('admin/k/general.error.cant-edit-this-document') }}"></i>
                                                @endif

                                                @if ( $k->enabled)
                                                    @if($k->status != 'sold')
                                                    <a href="{!! route('admin.proposal.disable', $k->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                                    @else
                                                    <a href="{!! route('admin.proposal.enable', $k->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban text-muted"></i></a>
                                                    @endif
                                                @else
                                                    <a href="{!! route('admin.proposal.enable', $k->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif

                                                @if ( $k->isDeletable() && $k->status != 'sold' )
                                                    <a href="{!! route('admin.proposal.confirm-delete', $k->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-ban text-muted" title="{{ trans('admin/proposal/general.error.cant-delete-this-document') }}"></i>
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
$('#proposal-table').DataTable({
    pageLength: 25
});
});
</script>

@endsection
