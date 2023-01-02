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
                <a class="btn btn-default btn-sm" href="{!! route('admin.cms.pages.create') !!}" title="{{ trans('admin/fiscalyear/general.button.create') }}">
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
                                <th>Title</th>
                                <th>Heading</th>
                                <th>Excerpt</th>
                                <th>Photo</th>
                                <th>User</th>
                                <th> Action </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($cmspages as $v)
                            <tr>
                                <td align="center">
                                    {{$v->id}}
                                </td>
                                <td><a href="{{route('admin.cms.pages.show',$v->id)}}" size="16px;">{!! $v->title !!}</a></td>
                                <td>{{ $v->heading}}</td>
                                <td>{{ substr($v->excerpt,0,70) }}...</td>
                                <td>@if($v->photo)<img src="/cmspages/{{ $v->photo }}" width="50px;">@endif</td>
                                <td>{{ $v->user->first_name}} {{ $v->user->last_name}}</td>
                                <td>
                                    @if( $v->isEditable() || $v->canChangePermissions() )
                                    <a href="{!! route('admin.cms.pages.edit', $v->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                    @else
                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-edit-this-FiscalYear') }}"></i>
                                    @endif
                                    @if( $v->isDeletable() )
                                    <a href="{!! route('admin.cms.pages.confirm-delete', $v->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                    @else
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-delete-this-FiscalYear') }}"></i>
                                    @endif
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                    {!! $cmspages->render() !!}
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
