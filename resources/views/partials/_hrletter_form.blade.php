<?php $readonly = ($hrletter->isEditable())? '' : 'readonly'; ?>

<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />

<script src='{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}'></script>
<style>
  .mce-tinymce { position: static !important; }
</style>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<div class="content" style="padding-left: 0;">



    <div class="col-md-12">

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="col-sm-1 control-label">Subject</label>
              <div class="col-sm-11">
                  {!! Form::text('subject', null, ['class' => 'form-control','placeholder' => 'Type Letter Subject *', 'id'=>'subject', 'required'=>'required']) !!}
              </div>
            </div>
        </div>
      </div>


        <div class="col-md-6" id="client_lead">
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-3 control-label" id="client_lead_title">Staff Name</label>
            <div class="col-sm-9">
              <select class="form-control client_lead_id select2" name="staff_id" required="required" id='staff_id'>
                   <option value="0">Non Staff</option>
                  @foreach($users as $key => $uk)
                    <option value="{{ $uk->id }}" @if($hrletter && $uk->id == $hrletter->staff_id){{ 'selected="selected"' }}@endif>{{ $uk->first_name }} {{ $uk->last_name }}({{ $uk->email }})[{{$uk->id}}]</option>
                  @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
              <label for="inputEmail3" class="col-sm-3 control-label">
              Template 
              </label><div class="col-sm-9">
     
              {!!  Form::select('template',$templates,null,['id'=>'template',
              'class'=>'form-control template select2','placeholder'=>'Select Template']) !!}
            </div>
          </div>
        </div>

          
        <div class="col-md-6">  
          <div class="form-group">
          <label for="inputEmail3" class="col-sm-3 control-label">
          Type *
          </label>
            <div class="col-sm-9">
              <select class="form-control type select2" name="type" required="required">
                <option class="input input-lg" value="">Select Type</option>
                <option class="input input-lg" value="Contract" @if($hrletter && $hrletter->type == 'contract'){{ 'selected="selected"' }}@endif>Contract</option>
                <option class="input input-lg" value="intern"  @if($hrletter && $hrletter->type == 'intern'){{ 'selected="selected"' }}@endif>Intern</option>
                <option class="input input-lg" value="promotion"  @if($hrletter && $hrletter->type == 'promotion'){{ 'selected="selected"' }}@endif>Promotion</option>
                <option class="input input-lg" value="warning" @if($hrletter && $hrletter->type == 'warning'){{ 'selected="selected"' }}@endif>Warning</option>
              </select>
            </div>
          </div>
        </div>


        <div class="col-md-6">  
          <div class="form-group">
          <label for="inputEmail3" class="col-sm-3 control-label">
          Status *
          </label>
            <div class="col-sm-9">
              <select class="form-control status select2" name="status" required="required">
                <option class="input input-lg" value="">Select Status</option>
                <option class="input input-lg" value="draft" @if($hrletter && $hrletter->status == 'draft'){{ 'selected="selected"' }}@endif>Draft</option>
                <option class="input input-lg" value="paused" @if($hrletter && $hrletter->status == 'paused'){{ 'selected="selected"' }}@endif>Paused</option>
                <option class="input input-lg" value="finalized" @if($hrletter && $hrletter->status == 'finalized'){{ 'selected="selected"' }}@endif>Finalized</option>
                <option class="input input-lg" value="issued" @if($hrletter && $hrletter->status == 'issued'){{ 'selected="selected"' }}@endif>Issued</option>
              </select>
            </div>
          </div>
        </div>

    <div class="clearfix"></div>
       
    <div class="form-group">
    <textarea id='body' name ='body' class="textarea" placeholder="Place some text here"
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                          {!! $hrletter->body !!}
                          </textarea>
      
    </div>

        
    </div>
    </div>

<div class="clearfix"></div>
</div><!-- /.content -->


<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
      $('textarea#body').wysihtml5()

      $('.client_type').on('change', function() {
        $('#client_lead_title').html($(this).val().toUpperCase());
        $('.client_lead_id').html($('#'+$(this).val()+'_div').html());
        $('.create_new').css('display', 'none');
        $('#create_'+$(this).val()).css('display', 'block');
      });

      $('#template,#staff_id').on('change', function() {

        if($('#template').val() != '')
        {
          var formData = new FormData();
          formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
          formData.append("template", $('#template').val());
          formData.append("staff_id", $('#staff_id').val());

          $.ajax({
            type: 'POST',
            url: '/admin/hrletter/loadTemplate',
            data: formData,
            contentType: false,
            processData: false, 
            success: function(response)
            {
                if(response.success == '1'){
                console.log(response.data);
                  $('iframe').contents().find('.wysihtml5-editor').html(response.data)
                }
                
                  // wysihtml5-sandbox.get('body').setContent(response.data);
                else
                    alert('Sorry! No Template found. Select another Template.');
            }
          });
        }
        else
        $('iframe').contents().find('.wysihtml5-editor').html('')
      });
      $('.select2').select2()
    });
</script>
