<?php $readonly = ($proposal->isEditable())? '' : 'readonly'; ?>

<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />
<script src='https://adminlte.io/themes/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'></script>




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
            <label for="inputEmail3" class="col-sm-3 control-label" id="client_lead_title"> Lead Object</label>
            <div class="col-sm-9">
              <select class="form-control client_lead_id select2" name="client_lead_id" required="required">
               
                  @foreach($leads as $key => $uk)
                    <option value="{{ $uk->id }}" @if($proposal && $uk->id == $proposal->client_lead_id){{ 'selected="selected"' }}@endif>{{ $uk->name }}, {{ $uk->department }} {{ $uk->city }}</option>
                  @endforeach
                
              </select>
              
            </div>

          </div>
        </div>


      <div class="col-md-6">
        <div class="form-group">
          
      </div>
    </div>


      <div class="col-md-6">
       <div class="form-group">
          <label for="inputEmail3" class="col-sm-3 control-label">
          Products *
          </label><div class="col-sm-9">
          <select class="form-control" name="product_id" required="required">
          <option class="input" value="">Select Product</option>
          @if(isset($products))
              @foreach($products as $key => $uk)
                  <option value="{{ $uk->id }}" @if($proposal && $uk->id == $proposal->product_id){{ 'selected="selected"' }}@endif>{{ $uk->name }}</option>
              @endforeach
          @endif
          </select>
        </div>
      </div>
    </div>


    <div class="col-md-6">
      <div class="form-group">
          <label for="inputEmail3" class="col-sm-3 control-label">
          Template 
          </label><div class="col-sm-9">
          <select class="form-control template select2" name="template" id="template">
          <option class="input input-lg" value="">Select Template</option>
          <option class="input input-lg" value="proposal1" @if($proposal && $proposal->template == "proposal1"){{ 'selected="selected"' }}@endif>Digital Marketing Proposal </option>
          <option class="input input-lg" value="proposal2" @if($proposal && $proposal->template == "proposal2"){{ 'selected="selected"' }}@endif>Web Design Proposal </option>
          <option class="input input-lg" value="proposal3" @if($proposal && $proposal->template == "proposal3"){{ 'selected="selected"' }}@endif>Software Design Proposal</option>
          <option class="input input-lg" value="contract1" @if($proposal && $proposal->template == "contract1"){{ 'selected="selected"' }}@endif>Digital Marketing Contract </option>
          <option class="input input-lg" value="contract2" @if($proposal && $proposal->template == "contract2"){{ 'selected="selected"' }}@endif>Web and Software Contract</option>
           <option class="input input-lg" value="crmproposal" @if($proposal && $proposal->template == "crmproposal"){{ 'selected="selected"' }}@endif>CRM Proposal</option>
          </select>

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
            <option class="input input-lg" value="proposal" @if($proposal && $proposal->type == 'proposal'){{ 'selected="selected"' }}@endif>Proposal</option>
            <option class="input input-lg" value="contract"  @if($proposal && $proposal->type == 'contract'){{ 'selected="selected"' }}@endif>Contract</option>
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
            <option class="input input-lg" value="draft" @if($proposal && $proposal->status == 'draft'){{ 'selected="selected"' }}@endif>Draft</option>
            <option class="input input-lg" value="paused" @if($proposal && $proposal->status == 'paused'){{ 'selected="selected"' }}@endif>Paused</option>
            <option class="input input-lg" value="negotiating" @if($proposal && $proposal->status == 'negotiating'){{ 'selected="selected"' }}@endif>Negotiating</option>
            <option class="input input-lg" value="finalized" @if($proposal && $proposal->status == 'finalized'){{ 'selected="selected"' }}@endif>Finalized</option>
            <option class="input input-lg" value="sold" @if($proposal && $proposal->status == 'sold'){{ 'selected="selected"' }}@endif>Sold</option>
          </select>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
       
     <div class="form-group">
           <textarea id='body' name ='body' class="textarea" placeholder="Place some text here"
          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
          {!! $proposal->body !!}
          </textarea>
     </div>

        
    </div>
    </div>

<div class="clearfix"></div>
</div><!-- /.content -->


<div id="client_div" style="display: none;">
    <option class="input input-lg" value="">Select Client</option>
  @if(isset($clients))
      @foreach($clients as $key => $uk)
          <option value="{{ $uk->id }}" @if($proposal && $uk->id == $proposal->client_lead_id){{ 'selected="selected"' }}@endif>{{ $uk->name.' ('.$uk->location.')' }}</option>
      @endforeach
  @endif
</div>

<div id="lead_div" style="display: none;">
    <option class="input input-lg" value="">Select Lead</option>
  @if(isset($leads))
      @foreach($leads as $key => $uk)
          <option value="{{ $uk->id }}" @if($proposal && $uk->id == $proposal->client_lead_id){{ 'selected="selected"' }}@endif>{{ $uk->name }}, {{ $uk->department }} {{ $uk->city }}</option>
      @endforeach
  @endif
</div>

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $('textarea#body').wysihtml5();
      $('.client_type').on('change', function() {
      
        $('#client_lead_title').html($(this).val().toUpperCase());
        $('.client_lead_id').html($('#'+$(this).val()+'_div').html());
        $('.create_new').css('display', 'none');
        $('#create_'+$(this).val()).css('display', 'block');
      });

      $('#template').on('change', function() {
        if($(this).val() != '')
        {
          var formData = new FormData();
          formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
          formData.append("template", $(this).val());

          $.ajax({
            type: 'POST',
            url: '/admin/proposal/loadTemplate',
            data: formData,
            contentType: false,
            processData: false, 
            success: function(response)
            {
                if(response.success == '1')
                  $('iframe').contents().find('.wysihtml5-editor').html(response.data);
                else
                    alert('Sorry! No Template found. Select another Template.');
            }
          });
        }
        else
        $('iframe').contents().find('.wysihtml5-editor').html('');
      });

    });
</script>
