  <style type="text/css">
    .ui-autocomplete {
        z-index:2147483647;
    }
    #overlay{ 
  position: fixed;
  top: 0;
  z-index: 100;
  width: 100%;
  height:100%;
  display: none;
  background: rgba(0,0,0,0.6);
}
.cv-spinner {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;  
}
.spinner {
  width: 40px;
  height: 40px;
  border: 4px #ddd solid;
  border-top: 4px #2e93e6 solid;
  border-radius: 50%;
  animation: sp-anime 0.8s infinite linear;
}
@keyframes sp-anime {
  100% { 
    transform: rotate(360deg); 
  }
}
.is-hide{
  display:none;
}
  </style>
  <div class="alert alert-danger alert-dismissable" style="display: none;" id="errormodal">
        <button type="button" aria-hidden="true" class="close" onclick="$('#errormodal').slideUp(300)">×</button>
        <h4>
            <i class="icon fa fa-ban"></i> Errors
        </h4>
        <div id="modalserrors">
        </div>
    </div>
<div class="modal-content" id='lead_tasks_modal'>
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Lead Tasks
                <small>Create new tasks</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">

  <div class='row'>
        <div class='col-md-12'>
            <div class="box">
				<div class="box-body">
                	{!! Form::open( ['id' => 'form_edit_task'] ) !!}

                <div class="content col-md-9">
                	<div class="row">
                    	<div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('lead', trans('admin/tasks/general.columns.lead')) !!} Autocompletes
                                <?php if(isset($_GET['lead_id'])) { ?>
									<input type="text" name="lead" class="form-control" id="lead_id" value="{!! TaskHelper::getLeadNameById($_GET['lead_id']) !!}">
                                    <input type="hidden" name="leadId" value="{!! $_GET['lead_id'] !!}">
								<?php } else { ?>
                                {!! Form::text('lead', null, ['class' => 'form-control', 'id'=>'lead_id']) !!}
                                <input type="hidden" name="leadId" value="0">
                                <?php } ?>
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group">
                                 {!! Form::label('task_subject', trans('admin/tasks/general.columns.task_subject')) !!}
                                {!! Form::text('task_subject', null, ['class' => 'form-control', 'placeholder'=>'Write Tasks Subject']) !!}
                            </div>
                    	</div>
                    </div>
                    <div class="row">
                    	<div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('task_detail', trans('admin/tasks/general.columns.task_detail')) !!}
                                {!! Form::textarea('task_detail', null, ['class' => 'form-control', 'rows'=>'5']) !!}
                            </div>
                        </div>   

                        <div class="col-md-6">     
                            <div class="form-group">
                                {!! Form::label('task_complete_percent', trans('admin/tasks/general.columns.task_complete_percent')) !!}
                                {!! Form::text('task_complete_percent', null, ['class' => 'form-control','placeholder' => '10, 20, 30 Out of 100 percent']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('task_alert', trans('admin/tasks/general.columns.task_alert')) !!}<br/>
                                {!! Form::radio('task_alert', 1, true) !!} Yes
                                {!! Form::radio('task_alert', 0, false) !!} No
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                    	<div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('task_start_date', trans('admin/tasks/general.columns.task_start_date')) !!}
                                {!! Form::text('task_start_date', null, ['class' => 'form-control', 'id'=>'task_start_date']) !!}
                            </div>
                    	</div>
                    	<div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('task_due_date', trans('admin/tasks/general.columns.task_due_date')) !!}
                                {!! Form::text('task_due_date', null, ['class' => 'form-control', 'id'=>'task_due_date', 'placeholder' => 'Due or expected end date is required']) !!}
                            </div>
                       	</div>                    	
                    </div>
                    <div class="row">
                    	
                    </div>       
                </div><!-- /.content -->
                <div class="content col-md-3">
                	<div class="form-group">
                        {!! Form::label('task_status', trans('admin/tasks/general.columns.task_status')) !!}
                        {!! Form::select('task_status', ['Started'=>'Started', 'Open'=>'Open', 'Processing'=>'Processing', 'Completed'=>'Completed'], null, ['class' => 'form-control']) !!}
                    </div>
                	<div class="form-group">
                    	{!! Form::label('task_owner', trans('admin/tasks/general.columns.task_owner')) !!}
                        {!! Form::select('task_owner',  $users, \Auth::user()->id, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                    	{!! Form::label('task_assign_to', trans('admin/tasks/general.columns.task_assign_to')) !!}
                        {!! Form::select('task_assign_to',  $users, \Auth::user()->id, ['class' => 'form-control']) !!}
                    </div> 
                    <div class="form-group">
                        {!! Form::label('task_priority', trans('admin/tasks/general.columns.task_priority')) !!}
                        {!! Form::select('task_priority', ['Low'=>'Low', 'Medium'=>'Medium', 'High'=>'High'], 'High', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="hidden" name="enabled" value="1">
                            </label>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}  
                      
                        <a href="javascript:void()" title="{{ trans('general.button.cancel') }}" class='btn btn-default'  data-dismiss="modal">{{ trans('general.button.cancel') }}</a>
                    </div>
                </div>

                	{!! Form::close() !!}
				</div>
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->

</div>
</div>
</div>
<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
<script type="text/javascript">

        $("#lead_tasks_modal #lead_id").autocomplete({
            source: "/admin/getLeads",
            minLength: 1
        });
      $('#lead_tasks_modal #task_start_date').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        }); 
      $('#lead_tasks_modal #task_due_date').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        }); 
      $('#lead_tasks_modal #form_edit_task').submit(function(){
        $("#overlay").fadeIn(300);　
        var obj ={};
        var data = JSON.stringify( $('#form_clients').serializeArray() ); //  <-----------
        var paramObj = {};
        $.each($('form').serializeArray(), function(_, kv) {
        paramObj[kv.name] = kv.value;
        });
        paramObj['_token']= $('meta[name="csrf-token"]').attr('content')
        $.post("{{ route('admin.tasks.store') }}",paramObj,function(data,status){
          if(status == 'success'){
           var  result = data;
           if(result.error){
                $("#overlay").fadeOut(300);
                $('#errormodal').css("display", "block")
                let errors = Object.values(result.error);
                errors = errors.flat();
                err = '';
                //console.log(errors);
                errors.forEach(function(value){
                    err = err + `<li>${value}</li>`;
                })
                $('#modalserrors').html(err);
                $(window).scrollTop(0);
                return false;
           }
        
            handleModalResults(result);
            
          }
        }).fail(function(){
            $("#overlay").fadeOut(300);
            $('#errormodal').slideDown(300);
            $('#modalserrors').html('<li>Server Error Try Again !!</li>');

        });
        return false;
      });

</script>
