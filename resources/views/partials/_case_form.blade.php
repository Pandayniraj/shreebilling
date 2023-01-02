<?php $readonly = ($case->isEditable())? '' : 'readonly'; ?>

<div class="content" style="padding-left: 0;">
    <div class="col-md-6" style="padding-left: 0;">

        <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

        @if($case->ticket_name)
        <div class="form-group">
            {!! Form::label('ticket_name', trans('admin/cases/general.columns.ticket_name')) !!}
            {!! Form::text('ticket_name', $case->ticket_name, ['class' => 'form-control input-sm']) !!}
        </div>
        @endif

         <div class="form-group" id='customers_id'>
            <label>Select Customers <i class="imp">Or</i></label>&nbsp;<small><a href="javascript::void()" 
                onclick="createcustomer('customer')">create new customers</a></small>
            <select id="cust_id" class="form-control lead_id select2 searchable" name="client_id" >
            <option class="input input-lg" value="">Select Customers</option>

            @if(isset($clients))
            @foreach($clients as $key => $v)
            <option value="{{ $v->id }}" @if($v->id == $case->client_id) {{ 'selected="selected"' }} @endif>
                {{ $v->name }} (#{{$v->id}})
            </option>
            @endforeach
            @endif
            </select>
        </div>

        <div class="form-group">
            {!! Form::label('priority', trans('admin/cases/general.columns.priority')) !!}
            {!! Form::select('priority', ['Low'=>'Low', 'Medium'=>'Medium', 'High'=>'High'], $case->priority, ['class' => 'form-control input-sm']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('status', trans('admin/cases/general.columns.status')) !!}
            {!! Form::select('status', ['new'=>'New', 'assigned'=>'Assigned', 'closed'=>'Closed','pending'=>'Pending','rejected'=>'Rejected'], $case->status, ['class' => 'form-control input-sm ']) !!}
        </div>

      

       

    </div>
    <div class="col-md-6">
        @if($case->ticket_email)
        <div class="form-group">
            {!! Form::label('ticket_email', trans('admin/cases/general.columns.ticket_email')) !!}
            {!! Form::text('ticket_email', $case->ticket_email, ['class' => 'form-control input-sm']) !!}
        </div>
        @endif

        <div class="form-group">
            {!! Form::label('subject', trans('admin/cases/general.columns.subject')) !!}
            {!! Form::text('subject',(isset($case->subject)) ? $case->subject: "New Jobs" , ['class' => 'form-control', 'id'=>'subject','required'=>'true']) !!}
        </div>
      
         <div class="form-group">
            {!! Form::label('type', trans('admin/cases/general.columns.type')) !!}
            {!! Form::select('type', ['installation'=>'Installation', 'complain'=>'Complain'], $case->type, ['class' => 'form-control input-sm']) !!}
        </div>
    

        <div class="form-group">
            {!! Form::label('assigned_to', trans('admin/cases/general.columns.assigned_to')) !!}
            {!! Form::select('assigned_to',  $users, $case->assigned_to, ['class' => 'form-control searchable']) !!}
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
      $('#lead_id').select2();
      $('#cust_id').select2();
    });
</script>
