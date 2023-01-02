<?php $readonly = ($knowledge->isEditable())? '' : 'readonly'; ?>

<div class="content" style="padding-left: 0;">

    <div class="col-md-8">
       
     <div class="form-group">
            {!! Form::label('name', 'Category Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'id'=>'name']) !!}
        </div>
    </div>
    </div>
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
