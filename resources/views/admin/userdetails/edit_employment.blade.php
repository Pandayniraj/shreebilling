<div class="modal-content">
    <div class="modal-header bg-primary">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Adding Employment Record for  {{ $user->first_name }} {{ $user->last_name }}</h4>
    </div>

    <form class="form-horizontal" action="/admin/edit_employment/{{ $employement_details->id }}" method="post">

      {{ csrf_field() }}

    <div class="modal-body">
      
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-10">
          <div class="checkbox">
            <label>
              <input type="checkbox" name='is_current' 
              @if($employement_details->is_current == '1') checked=""  @endif>Is current</label>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <label class="control-label col-sm-3" >Organization:</label>
        <div class="col-sm-9">
          {!! Form::select('org_id',$allOrganization,$employement_details->org_id,['class'=>'form-control']) !!}
        </div>
      </div>
      
      <div class="form-group">
        <label class="control-label col-sm-3" >Departments:</label>
        <div class="col-sm-9">
          {!! Form::select('departments_id',$departments,$employement_details->departments_id,['class'=>'form-control',
          'id'=>'departments_id']) !!}
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3" >Designation:</label>
        <div class="col-sm-9">
          {!! Form::select('designations_id',$designations,$employement_details->designations_id,['class'=>'form-control',
          'id'=>'designations_id']) !!}
        </div>
      </div>
      
      <div class="form-group">
        <label class="control-label col-sm-3" >Job Title:</label>
        <div class="col-sm-9">
          {!! Form::select('job_title',$desgination,$employement_details->job_title,['class'=>'form-control',
          'id'=>'designations_id']) !!}
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3" >Employemnt Status:</label>
        <div class="col-sm-9">
          {!! Form::select('employment_type',$employment_type,$employement_details->employment_type,['class'=>'form-control',
          'id'=>'employment_type']) !!}
        </div>
      </div>
      
      <div class="form-group">
        <label class="control-label col-sm-3" >Branch:</label>
        <div class="col-sm-9">
          <input type="text" class="form-control"  placeholder="Enter Branch" name="work_station" value="{{ $employement_details->work_station }}">
        </div>
      </div>

       <div class="form-group">
        <label class="control-label col-sm-3" >Change Type:</label>
        <div class="col-sm-9">
          {!! Form::select('change_type',$change_type,$employement_details->change_type,['class'=>'form-control',
          'id'=>'change_type']) !!}
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3" >Supervisor:</label>
        <div class="col-sm-9">
          {{-- {!! Form::select('change_type',$change_type,null,['class'=>'form-control',
          'id'=>'change_type']) !!} --}}
          <select name="first_line_manager" class="form-control" >
              <option value="">Select Supervisor</option>
              @foreach($supervisor as $sup)
                <option value="{{ $sup->id }}" @if($employement_details->first_line_manager == $sup->id) selected="" @endif>{{ $sup->first_name }} {{ $sup->last_name }}</option>
              @endforeach
          </select>

        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3" >Scope of work:</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="scope_of_work"  placeholder="Scope of work"  id='scope_of_work' value="{{ $employement_details->scope_of_work }}">
        </div>
      </div>
      
      <div class="form-group">
        <label class="control-label col-sm-3" >Start Date:</label>
        <div class="col-sm-9">
          <input type="text" class="form-control datepicker" name="start_date"  placeholder="Start Date"  
          id='start_date' value="{{ $employement_details->start_date }}">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3" >End Date:</label>
        <div class="col-sm-9">
          <input type="text" class="form-control datepicker" name="end_date"  
          placeholder="End Date"  
          id='end_date' value="{{ $employement_details->end_date }}">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3" >Responsibility:</label>
        <div class="col-sm-9">
          <textarea name="responsibility" id='body' class="form-control">{!! $employement_details->responsibility !!}</textarea>
        </div>
      </div>


      <div class="form-group">
        <label class="control-label col-sm-3" >Projects:</label>
        <div class="col-sm-9">
          {!! Form::select('project_id',$project,$employement_details->project_id,['class'=>'form-control'])  !!}
        </div>
      </div>

   
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary" >Update</button>
    </div>

     </form>
  </div>

  <link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />

<script src='{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}'></script>

  <script type="text/javascript">
    $(function () {
        $('#departments_id').on('change', function() {

            if($(this).val() != '')
            {
                $.ajax({
                    url: "/admin/users/ajax/GetDesignation",
                    data: { departments_id: $(this).val() },
                    dataType: "json",
                    success: function( data ) {
                        var result = data.data;
                        $('#designations_id').html(result);
                    }
                });
            }
        });


      $(document).ready(function() {
          $("#scope_of_work").tagit();
      });
 $('.datepicker').datetimepicker({
            //inline: true,
            //format: 'YYYY-MM-DD',
            format: 'YYYY-MM-DD'
            , sideBySide: true
            , allowInputToggle: true
        });
   
    });

          $('textarea#body').wysihtml5();
  </script>