@extends('layouts.master')
@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Customers
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>

          <i class="fa fa-info"></i> Create Customers Account.


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.clients.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">

                        <a class="btn btn-default btn-sm" href="{!! route('admin.clients.create') !!}?relation_type=customer" title="{{ trans('admin/clients/general.button.create') }}">
                            <i class="fa fa-plus-square"></i> Add
                        </a>

                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.clients.enable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>

                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.clients.disable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i>
                        </a>
                        <a class="btn btn-default btn-sm" href="{!! route('admin.import-export-clients.index') !!}" title="Import/Export Contacts">
                            <i class="fa fa-download">&nbsp;</i> Import/Export
                        </a>
                        <a class="btn btn-default btn-sm" href="/admin/customer?op=excel" title="Export Clients">
                            <i class="fa fa-download">&nbsp;</i>
                        </a>
                    <!--     Search parts -->
                    <div class="col-md-4 col-sm-4 col-lg-4" style="float: right;margin-top: 4px">
                        <div class="input-group">
                        <input type="text" class="form-control input-sm" placeholder="Search clients" name="search" id="terms" value="{{\Request::get('term')}}">

                        <div class="input-group-btn">

                        <button type="button" class="btn btn-primary btn-flat btn-sm" id="search"><i class="fa fa-search"></i>&nbsp;Filter</button>
                        </div>
                        <div class="input-group-btn">
                        <button type="button" class="btn btn-default btn-flat btn-sm" id="clear"><i class="fa fa-close (alias)"></i>&nbsp; Clear</button>
                        </div>
                        </div>
                    </div>
                      <div class="col-md-3 col-sm-3 col-lg-3" style="float: right;margin-top: 5px;margin-right: -25px">
                    <select class = 'form-control searchable' id="clients_types" >
                            <option value="">Select customer group</option>
                            @foreach($groups as $grp)
                                <option value="{{ $grp->id }}">{{ $grp->name }}</option>
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
                                        <th>Mapped Accounts</th>
                                        <th>{{ trans('admin/clients/general.columns.phone') }} </th>
                                        <th>{{ trans('admin/clients/general.columns.website') }}</th>
                                        <th>{{ trans('admin/clients/general.columns.type') }}</th>
                                        <th>{{ trans('admin/clients/general.columns.actions') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td align="center">{!! Form::checkbox('chkClient[]', $client->id); !!}</td>
                                            <td class=""><span style="font-size: 17px">
                                                <a href="/admin/clients/{{$client->id}}?relation_type={{$client->relation_type}}"> {{ $client->name }}</a>
                                            </span></td>
                                              <td><small>{{ $client->ledger->name }}</small></td>
                                            <td>{!! mb_substr($client->phone,0,16) !!}</td>
                                            <td> <i class="fa fa-external-link"></i> <a target="_blank" href="http://{!! $client->website !!}"> {!! mb_substr($client->website,0,10).'..' !!}</a></td>
                                            <td> {!! $client->groups->name??'' !!}</td>
                                            <td>
                                                @if ( $client->isEditable() || $client->canChangePermissions() )
                                                    <a href="{!! route('admin.clients.edit', $client->id) !!}?relation_type={{$client->relation_type}}" title="{{ trans('general.button.edit')}}"><i class="fa fa-edit"></i></a>
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
        let url = `{!! url('/') !!}/admin/{{ \Request::segment(2) }}?term=${terms}&clients_types=${clients_types}`;
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
              window.location.href = "{!! url('/') !!}/admin/{{ \Request::segment(2) }}";
        })
            $('.searchable').select2();
    </script>





@endsection
