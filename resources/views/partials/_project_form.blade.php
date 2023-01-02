<?php $readonly = ($project->isEditable())? '' : 'readonly'; ?>

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />

<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script>
$(function() {
    $('#start_date').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD HH:mm',
            sideBySide: true
        });
    $('#end_date').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD HH:mm',
            sideBySide: true
        });
});
</script>

<div class="content" style="padding-left: 0;">
    <div class="col-md-6" style="padding-left: 0;">
        <div class="form-group">
             <label for="inputEmail3" class="col-sm-2 control-label">
            {!! Form::label('name', trans('admin/projects/general.columns.name')) !!}
            </label><div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
        </div>

        <div class="form-group">
             <label for="inputEmail3" class="col-sm-2 control-label">
             {{ trans('admin/projects/general.columns.class') }}
            </label><div class="col-sm-10">
            {!! Form::text('class', null, ['class' => 'form-control']) !!}
        </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            {{ trans('admin/projects/general.columns.manager') }}
            </label><div class="col-sm-10">
            {!! Form::select('assign_to', $users, null, ['class' => 'form-control input-sm']) !!}
        </div>
        </div>

        <div class="form-group">
             <label for="inputEmail3" class="col-sm-2 control-label">
            {{ trans('admin/projects/general.columns.people') }}
            </label><div class="col-sm-10">

            <span class="txtfield"><ul id="peoples"></ul></span>
            @if($project)
            <input type="hidden" class="form-control peoples" name="staffs" id="peoplesField"
            value="{!! implode(',', $peoples) !!}" >
            @else
            <input type="hidden" class="form-control peoples" name="staffs" id="peoplesField" value="" >
            @endif

        </div>
        </div>

         <div class="form-group">
             <label for="inputEmail3" class="col-sm-2 control-label">
             Website1
            </label><div class="col-sm-10">
            {!! Form::text('website1', null, ['class' => 'form-control','placeholder'=>'Website1']) !!}
        </div>
        </div>

         <div class="form-group">
             <label for="inputEmail3" class="col-sm-2 control-label">
             Website2
            </label><div class="col-sm-10">
            {!! Form::text('website2', null, ['class' => 'form-control','placeholder'=>'Website2']) !!}
        </div>
        </div>

         <div class="form-group">
             <label for="inputEmail3" class="col-sm-2 control-label">
             Website3
            </label><div class="col-sm-10">
            {!! Form::text('website3', null, ['class' => 'form-control','placeholder'=>'Website3']) !!}
        </div>
        </div>

         <div class="form-group">
             <label for="inputEmail3" class="col-sm-2 control-label">
             {{ trans('admin/projects/general.columns.tagline') }}
            </label><div class="col-sm-10">
            {!! Form::text('tagline', null, ['class' => 'form-control','placeholder'=>'Tagline']) !!}
        </div>
        </div>

    </div>
    <div class="col-md-6" style="padding-left: 0;">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            {!! Form::label('status', trans('admin/projects/general.columns.status')) !!}
            </label><div class="col-sm-10">
            {!! Form::select('status', ['New'=>'New', 'Started'=>'Stared', 'On Hold'=>'On Hold', 'Completed'=>'Completed'], $project->status, ['class' => 'form-control']) !!}
        </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
               {{ trans('admin/projects/general.columns.start_date') }}
            </label><div class="col-sm-10">
            {!! Form::text('start_date', \Request::get('start_date'), ['class' => 'form-control', 'id'=>'start_date', 'placeholder'=>trans('general.columns.start_date')]) !!}
        </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
           {{ trans('admin/projects/general.columns.end_date') }}
            </label><div class="col-sm-10">
            {!! Form::text('end_date', \Request::get('end_date'), ['class' => 'form-control', 'id'=>'end_date', 'placeholder'=>trans('general.columns.end_date')]) !!}
        </div>
        </div>

         <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
            {{ trans('admin/projects/general.columns.category') }}
            </label><div class="col-sm-10">
            {!! Form::select('projects_cat', $projects_cat, null, ['class' => 'form-control input-sm']) !!}
        </div>
        </div>

         <div class="form-group">
             <label for="inputEmail3" class="col-sm-2 control-label">
             Facebook1
            </label><div class="col-sm-10">
            {!! Form::text('facebook1', null, ['class' => 'form-control','placeholder'=>'Facebook1']) !!}
        </div>
        </div>

         <div class="form-group">
             <label for="inputEmail3" class="col-sm-2 control-label">
             Facebook2
            </label><div class="col-sm-10">
            {!! Form::text('facebook2', null, ['class' => 'form-control','placeholder'=>'Facebook2']) !!}
        </div>
        </div>

         <div class="form-group">
             <label for="inputEmail3" class="col-sm-2 control-label">
             Youtube
            </label><div class="col-sm-10">
            {!! Form::text('youtube', null, ['class' => 'form-control','placeholder'=>'Youtube']) !!}
        </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">
                </label><div class="col-sm-10">
                {!! Form::checkbox('enabled', '1', $project->enabled) !!} {!! trans('general.status.enabled') !!}
            </div>
        </div>



    </div>
     <div class="form-group">

           <div class="col-sm-12">
            <textarea rows="3" name="description" class="form-control richTextBox" id="description-textarea">{{$project->description}}</textarea>
        </div>
        </div>
</div>

<div class="clearfix"></div>
</div><!-- /.content -->

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
<!-- include tags scripts and css -->

<script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
<link href="/bower_components/tags/css/jquery.tagit.css" rel="stylesheet" type="text/css"/>
<link href="/bower_components/tags/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css"/>
<!-- tags scripts and css ends -->

<script>
$(function() {
    jQuery("#peoples").tagit({
        singleField: true,
        singleFieldNode: $('#peoplesField'),
        allowSpaces: true,
        minLength: 1,
        tagLimit: 20,
        placeholderText: 'Enter User Name',
        allowNewTags: false,
        requireAutocomplete: true,

        removeConfirmation: true,
        tagSource: function( request, response ) {
            //console.log("1");
            $.ajax({
                url: "/admin/getUserTagsJson",
                data: { term:request.term },
                dataType: "json",
                success: function( data ) {
                    response( $.map( data, function( item ) {
                        return {
                            label: item.username ,
                            value: item.value
                        }
                    }));
                }
            });
        }
    });

});
</script>
