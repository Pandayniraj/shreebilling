@extends('layouts.master')
@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Customers and Suppliers
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.clients')}}
          <i class="fa fa-info"></i> Create Customer or Suppliers for Billing and Purchase.


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>


    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.clients.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">

                        <a class="btn btn-default btn-sm" href="{!! route('admin.clients.create') !!}" title="{{ trans('admin/clients/general.button.create') }}">
                            <i class="fa fa-plus-square"></i> Add
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.clients.enable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.clients.disable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i>
                        </a>
                        <a class="btn btn-primary btn-sm" href="{!! route('admin.import-export-clients.index') !!}" title="Import/Export Contacts">
                            <i class="fa fa-download">&nbsp;</i> Import/Export Clients
                        </a>
                    <!--     Search parts -->
                    <div class="col-md-4 col-sm-4 col-lg-4" style="float: right;margin-top: 4px">
                        <div class="input-group">
                        <input type="text" class="form-control input-sm" placeholder="Search clients" name="search" id="terms" value="{{\Request::get('term')}}">

                        <div class="input-group-btn">

                        <button type="button" class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i>&nbsp;Filter</button>
                        </div>
                        <div class="input-group-btn">
                        <button type="button" class="btn btn-danger btn-sm" id="clear"><i class="fa fa-close (alias)"></i>&nbsp; Clear</button>
                        </div>
                        </div>
                    </div>
                      <div class="col-md-2 col-sm-2 col-lg-2" style="float: right;margin-top: 5px;margin-right: -25px">
                        <select class = 'form-control searchable' id="clients_types" >
                            <option value="">Select client types</option>
                            @foreach($groups as $grp)
                                <option value="{{ $grp->id }}"@if(\Request::get('clients_types') == $grp->id) selected="" @endif>{{ $grp->name }}</option>
                            @endforeach
                        </select>
                        </div>
                    <!-- Search part end -->
                    <div class="box-body">
                            <br>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="clients-table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/clients/general.columns.name') }}</th>
                                        <th>{{ trans('admin/clients/general.columns.location') }}</th>
                                        <th>{{ trans('admin/clients/general.columns.phone') }} </th>
                                        <th>{{ trans('admin/clients/general.columns.website') }}</th>
                                        <th>{{ trans('admin/clients/general.columns.type') }}</th>
                                        <th>{{ trans('admin/clients/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th style="">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/clients/general.columns.name') }}</th>
                                        <th>{{ trans('admin/clients/general.columns.location') }}</th>
                                        <th>{{ trans('admin/clients/general.columns.phone') }}</th>
                                        <th>{{ trans('admin/clients/general.columns.website') }}</th>
                                        <th>{{ trans('admin/clients/general.columns.type') }}</th>
                                        <th>{{ trans('admin/clients/general.columns.actions') }}</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td align="center">{!! Form::checkbox('chkClient[]', $client->id); !!}</td>
                                            <td class="lead"><h4>{!! link_to_route('admin.clients.show', $client->name, [$client->id], []) !!}</h4></td>
                                            <td>@if($client->locations){!! $client->locations->city !!},{!! $client->locations->country !!}@endif</td>
                                            <td>{!! mb_substr($client->phone,0,16) !!}</td>
                                            <td> <i class="fa fa-external-link"></i> <a target="_blank" href="http://{!! $client->website !!}"> {!! mb_substr($client->website,0,10).'..' !!}</a></td>
                                            <td> {!! $client->type !!}</td>
                                            <td>
                                                @if ( $client->isEditable() || $client->canChangePermissions() )
                                                    <a href="{!! route('admin.clients.edit', $client->id) !!}?relation_type={{$client->relation_type}}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/clients/general.error.cant-edit-this-client') }}"></i>
                                                @endif

                                                @if ( $client->enabled )
                                                    <a href="{!! route('admin.clients.disable', $client->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                                @else
                                                    <a href="{!! route('admin.clients.enable', $client->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif

                                                @if ( $client->isDeletable() )
                                                    <a href="{!! route('admin.clients.confirm-delete', $client->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/clients/general.error.cant-delete-this-client') }}"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->

                </div><!-- /.box -->
                            <div align="center">{!! $clients->appends(\Request::except('page'))->render() !!}</div>
            {!! Form::close() !!}

        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkClient[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
        function search(){
        let terms = $('#terms').val();
        let clients_types = $('#clients_types').val();
        let url = `{!! url('/') !!}/admin/clients?term=${terms}&clients_types=${clients_types}`;
        console.log(url);
        window.location.href = url;
        }
        $('#search').click(function(){
           search();
        });
        $(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
        search();
        return false;
    }
  });
});
           $('#clear').click(function(){
              window.location.href = "{!! url('/') !!}/admin/clients";
        })
            $('.searchable').select2();
    </script>





@endsection
