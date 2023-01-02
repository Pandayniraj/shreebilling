<?php $readonly = ($knowledge->isEditable())? '' : 'readonly'; ?>

<div class="content" style="padding-left: 0;">

    <div class="col-md-8">
       
     <div class="form-group">
            {!! Form::label('title', trans('admin/knowledge/general.columns.title')) !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'id'=>'subject']) !!}
        </div>
       

        <div class="form-group">
                        <label>Select Category <i class="imp">*</i></label>
                                        <select class="form-control customer_id select2" name="cat_id" required="required">
                                        <option class="input input-lg" value="">Select Category</option>
                                        @if(isset($cat))
                                            @foreach($cat as $key => $v)
                                                <option value="{{ $v->id }}" @if($v->id == $knowledge->cat_id){{ 'selected="selected"' }}@endif>{{ $v->name}}</option>
                                            @endforeach
                                        @endif
                                        </select>
                                    </div>

        <div class="form-group">
            {!! Form::label('description', trans('admin/knowledge/general.columns.description')) !!}
            {!! Form::textarea('description', null, ['class'=>'form-control', 'rows'=>'2']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('body', trans('admin/knowledge/general.columns.body')) !!}
            {!! Form::textarea('body', null, ['class'=>'form-control', 'rows'=>'9']) !!}
        </div>

        
    </div>
    </div>

<div class="clearfix"></div>
</div><!-- /.content -->

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $("#contact_id").autocomplete({
            source: "/admin/getContacts",
            minLength: 1
      });

      $("#client_id").autocomplete({
            source: "/admin/getClients",
            minLength: 1
      });
    });
</script>
