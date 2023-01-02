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
        {!! Form::open( array('route' => 'admin.fiscalyear.enable-selected', 'id' => 'frmFiscalYearList') ) !!}
        <div class="box box-primary">
            <div class="box-header with-border">
                @if(\Auth::user()->hasRole('admins'))
                <a class="btn btn-default btn-sm" href="{!! route('admin.tours.maps.create') !!}" title="{{ trans('admin/fiscalyear/general.button.create') }}">
                    <i class="fa fa-plus-square"></i> &nbsp;Add
                </a>
                @endif

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
                                <th>City Name</th>
                                <th>City Lat</th>
                                <th>City Long</th>
                                <th>City Type</th>
                                <th>Tour</th>
                                <th>Order</th>
                                <th> Action </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($tourmaps as $v)
                            <tr>
                                <td align="center">
                                    {{$v->id}}
                                </td>
                                <td>{!! $v->map_city_name !!}</td>
                                <td>{!! $v->map_city_lat !!}</td>
                                <td>{{$v->map_city_long}}</td>
                                <td>{{ $v->map_city_type}}</td>
                                <td>{{ $v->tour->tour_title}}</td>
                                <td>{{ $v->map_order}}</td>
                                <td>
                                    @if( $v->isEditable() || $v->canChangePermissions() )
                                    <a href="{!! route('admin.tours.maps.edit', $v->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                    @else
                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-edit-this-FiscalYear') }}"></i>
                                    @endif
                                    @if( $v->isDeletable() )
                                    <a href="{!! route('admin.tours.maps.confirm-delete', $v->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                    @else
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-delete-this-FiscalYear') }}"></i>
                                    @endif
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                    {!! $tourmaps->render() !!}
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
