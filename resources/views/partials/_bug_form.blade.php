<?php $readonly = ($bug->isEditable())? '' : 'readonly'; ?>

<div class="content" style="padding-left: 0;">
    <div class="col-md-2" style="padding-left: 0;">

         <div class="form-group">
        <label>Select Project <i class="imp">*</i></label>
                        <select class="form-control customer_id select2" name="project_id" required="required">
                        <option class="input input-lg" value="">Select Project</option>
                        @if(isset($projects))
                            @foreach($projects as $key => $v)
                                <option value="{{ $v->id }}" @if($v->id == $bug->project_id){{ 'selected="selected"' }}@endif>{{ $v->name}}</option>
                            @endforeach
                        @endif
                        </select>
                    </div>

        <div class="form-group">
            {!! Form::label('priority', trans('admin/bugs/general.columns.priority')) !!}
            {!! Form::select('priority', ['l'=>'Low', 'm'=>'Medium', 'h'=>'High'], $bug->priority, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('status', trans('admin/bugs/general.columns.status')) !!}
            {!! Form::select('status', ['open'=>'Open', 'in_progress'=>'In Progress', 'assigned'=>'Assigned','to_be_fixed'=>'To Be Fixed','reopen'=>'Reopen','fixed' => 'Fixed'], $bug->status, ['class' => 'form-control input-sm']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('source', trans('admin/bugs/general.columns.source')) !!}
            {!! Form::select('source', ['internal'=>'Internal', 'email'=>'Email', 'cases'=>'Cases','client'=>'Client'], $bug->source, ['class' => 'form-control input-sm']) !!}
        </div>

          <div class="form-group">
            {!! Form::label('type', trans('admin/bugs/general.columns.type')) !!}
            {!! Form::select('type', ['defect'=>'Defect', 'feature'=>'Feature'], $bug->type, ['class' => 'form-control input-sm']) !!}
        </div>

       

        <div class="form-group">
            <label>
                {!! Form::checkbox('enabled', '1', $bug->enabled) !!} {!! trans('general.status.enabled') !!}
            </label>
        </div>
        
    </div>


    <div class="col-md-2" style="padding-left: 0;">

   

        <div class="form-group">
            {!! Form::label('assigned_to', trans('admin/bugs/general.columns.assigned_to')) !!}
            {!! Form::select('assigned_to',  $users, $bug->assigned_to, ['class' => 'form-control']) !!}
        </div>

         <div class="form-group">
            {!! Form::label('found_in_release', trans('admin/bugs/general.columns.found_in_release')) !!}
            {!! Form::text('found_in_release', null, ['class' => 'form-control', 'id'=>'found_in_release']) !!}
        </div>


        <div class="form-group">
            {!! Form::label('category', trans('admin/bugs/general.columns.category')) !!}
            {!! Form::text('category', null, ['class' => 'form-control', 'id'=>'category']) !!}
        </div>



        <div class="form-group">
            {!! Form::label('fixed_in_release', trans('admin/bugs/general.columns.fixed_in_release')) !!}
            {!! Form::text('fixed_in_release', null, ['class' => 'form-control', 'id'=>'fixed_in_release']) !!}
        </div>
       
        
    </div>




    <div class="col-md-8">
       
     <div class="form-group">
            {!! Form::label('subject', trans('admin/bugs/general.columns.subject')) !!}
            {!! Form::text('subject', null, ['class' => 'form-control', 'id'=>'subject']) !!}
        </div>


       

        <div class="form-group">
            {!! Form::label('type', trans('admin/bugs/general.columns.description')) !!}
            {!! Form::textarea('description', null, ['class'=>'form-control', 'rows'=>'3']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('type', trans('admin/bugs/general.columns.resolution')) !!}
            {!! Form::textarea('resolution', null, ['class'=>'form-control', 'rows'=>'3']) !!}
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
