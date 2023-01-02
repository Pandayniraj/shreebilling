@extends('layouts.master')
@section('content')
<link rel="stylesheet" type="text/css" href="/bootstrap-iso.css">
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
            Contacts
                <small>{!! $page_description !!}</small>
            </h1>
            <p> Contact List, contact created from customers or suppliers also appears here.
              Total contacts <strong>({{$contacts_count}})</strong>
            </p>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<div class="row">
    <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.contacts.enable-selected', 'id' => 'frmContactList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                       
                        &nbsp;
                        <a class="btn btn-primary btn-sm" href="{!! route('admin.contacts.create') !!}" title="{{ trans('admin/contacts/general.button.create') }}">
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
                    <input type="text" class="form-control input-sm" placeholder="Search" name="search" id="terms">

                    <div class="input-group-btn">
                    <button type="button" class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i>&nbsp;Filter</button>
                    </div>
                    &nbsp;
                    <div class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-sm" id="clear"><i class="fa fa-close (alias)"></i>&nbsp; Clear</button>
                    </div>
                    </div>    


                    </div>
                        
                    </div>
                </div>
           
</div>
<div class="bootstrap-iso" style="padding: 15px;">

    <div class="card card-solid" >
        <div class="card-body">
          <div class="row d-flex align-items-stretch p-0">
            @foreach($contacts as $contact)
            <div class="col-md-4 " style="padding-bottom: 10px;">
              <div class="card bg-light">
                <div class="card-header border-bottom-0 bg">
                 {!! $contact->client->name !!} 
                 @if($contact->tag_id)
                 <span class="label label-default">{!! $contact->tag->name !!} </span>
                 @endif
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-7">
                      <span style="font-size: 16px"><b>{!! link_to_route('admin.contacts.show', $contact->full_name, [$contact->id], []) !!}</b></span>
                      <p class="text-muted text-sm"><b>Position: </b> {!! mb_substr($contact->position,0,18) !!} </p>
                      <ul class="ml-4 mb-0 fa-ul text-muted">
                        <li class="small"><span class="fa-li"><i class="fa fa-envelope"></i></span> Email: <a href="mailto::{{$contact->email_1}}"> {!! mb_substr($contact->email_1,0,21) !!}</a><br></li>
                    
                        <li class="small"><span class="fa-li"><i class="fa fa-phone"></i></span> Phone #: <a href="tel:{!! $contact->phone !!}">{!! $contact->phone !!}</a></li>
                      </ul>
                    </div>
                    <div class="col-5 text-center">
                    @if(is_file( url('/').'/contacts/'.$contact->file) && getimagesize(  url('/').'/contacts/'.$contact->file))
                     <img src="/contacts/{{ $contact->file }}"  alt="{{$contact->full_name}}" class="img-circle img-fluid" style="height: 50px; width: 50px;">
                    @else
                      <img src="{{TaskHelper::getAvatarAttribute($contact->full_name)}}" alt="" class="img-circle img-fluid" style="height: 50px; width: 50px;">
                    @endif
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                
                  <div class="text-right">
                     @if ( $contact->isEditable() || $contact->canChangePermissions() )
                        <a href="{!! route('admin.contacts.edit', $contact->id) !!}" class="btn btn-sm bg-teal">
                          <i class="fa fa-edit"></i>
                        </a>
                    @else
                        <a href="#" class="btn btn-sm bg-teal">
                         <i class="fa fa-edit text-muted" title="{{ trans('admin/contacts/general.error.cant-edit-this-contact') }}"></i>
                        </a>
                    @endif

                    @if ( $contact->enabled )
                        <a href="{!! route('admin.contacts.disable', $contact->id) !!}" title="{{ trans('general.button.disable') }}" class="btn btn-sm bg-olive" >
                          <i class="fa fa-check-circle "></i>
                        </a>
                    @else
                        <a href="{!! route('admin.contacts.enable', $contact->id) !!}" title="{{ trans('general.button.enable') }}" class="btn btn-sm bg-yellow">
                        <i class="fa fa-ban "></i>
                        </a>
                    @endif
                    @if ( $contact->isDeletable() )
                        <a href="{!! route('admin.contacts.confirm-delete', $contact->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}" class="btn btn-sm bg-red" ><i class="fa fa-trash"></i></a>
                    @else
                    <a href="#" class="btn btn-sm bg-yellow" >
                        <i class="fa fa-trash text-muted" title="{{ trans('admin/contacts/general.error.cant-delete-this-contact') }}"></i>
                    </a>
                    @endif


                    <a href="{{route('admin.contacts.show',$contact->id)}}" class="btn btn-sm bg-blue">
                      <i class="fa fa-user"></i> View Contact
                    </a>
                  </div>
                                            
                </div>
              </div>
            </div>

            @endforeach
          </div>
        </div>

        <div class="card-footer">
            <div align="center">{!! $contacts->render()  !!}</div>
        </div>
 
 
</div>
</div>
     {!! Form::close() !!}
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



