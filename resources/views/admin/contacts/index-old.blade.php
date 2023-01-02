@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Contact list of Clients
                <small>{!! $page_description or "Page description" !!}</small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.clients')}}
         

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.contacts.enable-selected', 'id' => 'frmContactList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                       
                        &nbsp;
                        <a class="btn btn-social btn-bitbucket" href="{!! route('admin.contacts.create') !!}" title="{{ trans('admin/contacts/general.button.create') }}">
                            <i class="fa fa-edit"> </i> Add Contact
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmContactList'].action = '{!! route('admin.contacts.enable-selected') !!}';  document.forms['frmContactList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmContactList'].action = '{!! route('admin.contacts.disable-selected') !!}';  document.forms['frmContactList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-primary btn-sm" href="{!! route('admin.import-export.index') !!}" title="Import/Export Contacts">
                            <i class="fa fa-download">&nbsp;</i> Import/Export Contacts
                        </a>

              
                    <div class="col-md-4 col-sm-4 col-lg-4" style="float: right;margin-top: 4px">  
                                  
                            <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="search" id="terms">
                    <div class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="search"><i class="fa fa-search"></i>&nbsp;Filter</button>
                    </div>
                    <div class="input-group-btn">
                    <button type="button" class="btn btn-danger" id="clear"><i class="fa fa-close (alias)"></i>&nbsp; Clear</button>
                    </div>
                    </div>    


                    </div>
                        
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="contacts-table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/contacts/general.columns.full_name') }}</th>
                                        <th>Position</th>
                                        <th>Phone</th>
                                        <th>Primary Email</th>
                                        <th>Company</th>
                                        <th>{{ trans('admin/contacts/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: center; width:10px">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/contacts/general.columns.full_name') }}</th>
                                        <th>Position</th>
                                         <th>Phone</th>
                                        <th>Primary Email</th>
                                        <th>Company</th>
                                        <th>{{ trans('admin/contacts/general.columns.actions') }}</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                @if(isset($contacts) && !empty($contacts))
                                    @foreach($contacts as $contact)
                                        <tr>
                                            <td align="center">{!! Form::checkbox('chkContact[]', $contact->id); !!}</td>

                                            <td class="lead"><h4>{!! link_to_route('admin.contacts.show', $contact->full_name, [$contact->id], []) !!}</h4></td>

                                            <td>{!! mb_substr($contact->position,0,18) !!}</td>
                                            <td>{!! $contact->phone !!}</td>
                                            <td><a href="mailto::{{$contact->email_1}}"> 
                                                {!! mb_substr($contact->email_1,0,15) !!}</a></td>
                                            <td>{!! $contact->client->name !!}</td>

                                            <td>
                                                @if ( $contact->isEditable() || $contact->canChangePermissions() )
                                                    <a href="{!! route('admin.contacts.edit', $contact->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/contacts/general.error.cant-edit-this-contact') }}"></i>
                                                @endif

                                                @if ( $contact->enabled )
                                                    <a href="{!! route('admin.contacts.disable', $contact->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                                @else
                                                    <a href="{!! route('admin.contacts.enable', $contact->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif

                                                @if ( $contact->isDeletable() )
                                                    <a href="{!! route('admin.contacts.confirm-delete', $contact->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/contacts/general.error.cant-delete-this-contact') }}"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                          
                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
                        <div align="center">{!! $contacts->render()  !!}</div>
            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection
@endif

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->


    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkContact[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
$('#search').click(function(){
            let terms = $('#terms').val();
           window.location.href = "{!! url('/') !!}/admin/contacts?term="+terms;
        });
        $(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      let terms = $('#terms').val();
      window.location.href = "{!! url('/') !!}/admin/contacts?term="+terms;
      return false;
    }
  });
});
        $('#clear').click(function(){
              window.location.href = "{!! url('/') !!}/admin/contacts";
        })
    </script>



@endsection

