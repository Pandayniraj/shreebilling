<?php $readonly = ($contact->isEditable())? '' : 'readonly'; ?>
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<div class="content">
    <div class="col-md-6">



          <div class="form-group">
           

          
            <div class="col-sm-4">
            {!! Form::select('salutation', ['Mr'=>'Mr', 'Mrs'=>'Mrs', 'Ms'=>'Ms', 'Sir'=>'Sir'], null, ['class' => 'form-control', $readonly]) !!}
            </div>

             <div class="col-sm-8">
            {!! Form::text('full_name', null, ['class' => 'form-control', $readonly,'placeholder' => 'Type Full Name']) !!}
                </div>
                    
                </div>



        <div class="form-group">
            <label class="control-label col-sm-4">
            {!! Form::label('client_id', trans('admin/contacts/general.columns.client')) !!}
            <a href="javascript::void()"  class="dropdown-toggle"  data-toggle="dropdown">[+]</a>
            <ul class="dropdown-menu" role="menu">
      <li><a href="#" onclick="createcustomer('customer')">Customer</a></li>
      <li><a href="#" onclick="createcustomer('supplier')">Supplier</a></li>
    
    </ul>
            </label><div class="input-group">
            @if(sizeof( is_array($contact['attributes']) ? $contact['attributes'] : []  ))
                {!! Form::text('client_id', $contact->client->name ??'', ['class' => 'form-control', $readonly]) !!}                
            @else
                {!! Form::text('client_id', $contact->client->name??'', ['class' => 'form-control', $readonly, 'placeholder' => 'Type and Select from auto complete only']) !!}
            @endif
        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-building"></i></a>
                        </div>
                    </div>
                </div>


         <div class="form-group">
           <label class="control-label col-sm-4">
             Primary Email
            </label>
             <div class="input-group">
            {!! Form::text('email_1', null, ['class' => 'form-control', $readonly]) !!}
        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

            <div class="form-group">
           <label class="control-label col-sm-4">
             Secondary
            </label>
             <div class="input-group ">
            {!! Form::text('email_2', null, ['class' => 'form-control', $readonly]) !!}
        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-envelope"></i></a>
                        </div>
                    </div>
                </div>



        <div class="form-group">
            <label class="control-label col-sm-4">
            {!! Form::label('position', trans('admin/contacts/general.columns.position')) !!}
            </label><div class="input-group">
            {!! Form::text('position', null, ['class' => 'form-control', $readonly]) !!}
        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-user-tie"></i></a>
                        </div>
                    </div>
                </div>


        <div class="form-group">
            <label class="control-label col-sm-4">
            {!! Form::label('department', trans('admin/contacts/general.columns.department')) !!}
            </label><div class="input-group">
            {!! Form::text('department', null, ['class' => 'form-control', $readonly]) !!}
        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-sitemap"></i></a>
                        </div>
                    </div>
                </div>


        <div class="form-group">
           <label class="control-label col-sm-4">
             DOB
            </label>
             <div class="input-group ">
            {!! Form::text('dob', null, ['class' => 'form-control datepicker', $readonly]) !!}
        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>


        <div class="form-group">
            <label class="control-label col-sm-4">
             Desc
            </label>
            <div class="col-sm-8">
              
        <textarea class="form-control" name="description" id="description" placeholder="Write Description">{!! $contact->description !!}</textarea>
            </div></div>


        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
            {!! Form::label('file', 'Photo') !!} 
            </label><div class="col-sm-8">
            <input type="file" name="file_name">
            @if($contact->file != '')
            <label>Current Photo: </label><br/><img style=" width: auto;" src="{{ '/contacts/'.$contact->file }}">
            @endif
        </div>
        </div>
    </div>
    <div class="col-md-6">

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label" style="text-transform: capitalize;">
                Tag
            </label>
            <div class="col-sm-8">
                {!! Form::select('tag_id', $tags, null, ['class' => 'form-control searchable', $readonly,'placeholder'=>'Select Tag']) !!}
            </div>
        </div>

        <div class="form-group">
           <label class="control-label col-sm-4">
             Phone
            </label>
             <div class="input-group " style="z-index: 70000">
            {!! Form::text('phone', null, ['id'=>'mobile1', 'class' => 'form-control telephone', $readonly]) !!}
        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-mobile"></i></a>
                        </div>
                    </div>
                </div>

        <div class="form-group">
           <label class="control-label col-sm-4">
             Phone 2
            </label>
             <div class="input-group ">
            {!! Form::text('phone_2', null, ['id'=>'mobile2','class' => 'form-control', $readonly]) !!}
        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-mobile"></i></a>
                        </div>
                    </div>
                </div>




        <div class="form-group">
           <label class="control-label col-sm-4">
             Landline
            </label>
             <div class="input-group ">
            {!! Form::text('landline', null, ['id'=>'landline','class' => 'form-control', $readonly]) !!}
        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-phone"></i></a>
                        </div>
                    </div>
                </div>


        <div class="form-group">
            <label class="control-label col-sm-4">
            Skype
            </label>
            <div class="input-group ">
            {!! Form::text('skype', null, ['class' => 'form-control', $readonly]) !!}
         <div class="input-group-addon">
                            <a href="#"><i class="fab fa-skype"></i></a>
                        </div>
                    </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-4">
            Address
            </label><div class="input-group ">
            {!! Form::text('address', null, ['class' => 'form-control', $readonly]) !!}
         <div class="input-group-addon">
                            <a href="#"><i class="fa fa-map"></i></a>
                        </div>
                    </div>
        </div>



        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
            {!! Form::label('city', trans('admin/contacts/general.columns.city')) !!}
            </label><div class="col-sm-8">
            {!! Form::text('', $contact->locations->city ?? '', ['class' => 'form-control','id'=>'city', $readonly]) !!}
        <input type="hidden" name="city" value="{{ $contact->city??'' }}" id='locationvalue'>
        </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
            {!! Form::label('postcode', trans('admin/contacts/general.columns.postcode')) !!}
            </label><div class="col-sm-8">
            {!! Form::text('postcode', null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
            {!! Form::label('country', trans('admin/contacts/general.columns.country')) !!}
            </label><div class="col-sm-8">
            {!! Form::text('country', null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
            {!! Form::label('website', trans('admin/contacts/general.columns.website')) !!}
            </label><div class="col-sm-8">
            {!! Form::text('website', null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
            {!! Form::label('facebook', trans('admin/contacts/general.columns.facebook')) !!}
            </label><div class="col-sm-8">
            {!! Form::text('facebook', null, ['class' => 'form-control', $readonly]) !!}
        </div>
        </div>
    </div>

    <div class="form-group">
        @if(isset($createfrommodals))
        <label>
            {!! Form::checkbox('enabled', '1','1') !!} {!! trans('general.status.enabled') !!}
        </label>
        @else
          <label>
            {!! Form::checkbox('enabled', '1', $contact->enabled) !!} {!! trans('general.status.enabled') !!}
        </label>
        @endif
    </div>

</div><!-- /.content -->
<script type="text/javascript">

$( "#city" ).autocomplete({
    source: "/admin/getCities",
    minLength: 2,
    select: function(event , ui){
        $('#locationvalue').val(ui.item.id);
        $('#locationvalue').val(ui.item.id);
        $(`input[name=country]`).val(ui.item.country);
    }
});

function createcustomer(type){
    var win =  window.open(`/admin/clients/modals?relation_type=${type}`, '_blank','toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
    
}

 function HandlePopupResult(result) {

  if(result){
    let clients = result.lastcreatedClient;
    
    $('input#client_id').val(clients.name);


  }
  else{
    $("#ajax_status").after("<span style='color:red;' id='status_update'>failed to create clients</span>");
    $('#status_update').delay(3000).fadeOut('slow');
  }
}

</script>