
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Update Lead Task #{{$task->id}}
     <small class='start_dates'></small>
   </h4>
</div>
 {!! Form::model( $task, ['route' => ['admin.tasks.update', $task->id], 'method' => 'PATCH', 'id' => 'form_edit_lead'] ) !!}
<div class="modal-body">
  <div class="form-group">
    <label>Title</label>
      <input type="text" class="form-control title" placeholder="Enter ..." name='task_subject' value="{{ $task->task_subject }}">
  </div>
 <div class="row">
  <div class="col-sm-6 form-group">
   <label>Select Lead</label>
    <select class="form-control searchable-modal" name="lead">
     <option value="">Select Lead</option>
        @foreach ($leads as $p)
          <option value="{{ $p->name }}" @if($task->lead_id && $task->lead_id == $p->id) selected @endif>{{ ucfirst($p->name) }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-sm-6 form-group">
      {!! Form::label('task_status', trans('admin/tasks/general.columns.task_status')) !!}
      {!! Form::select('task_status', ['Open'=>'Open', 'Processing'=>'Processing', 'Completed'=>'Completed'], $task->task_status, ['class' => 'form-control']) !!}
  </div>
</div>
<div class="row">
  <div class="col-sm-6 form-group">
    <label>Start Date</label>
      <input type="text" class="form-control task_start_date" placeholder="Enter ..." name="task_start_date" value="{{$task->task_start_date}}">
  </div>
   <div class="col-sm-6 form-group">
    <label>End Date</label>
      <input type="text" class="form-control task_end_date" placeholder="Enter ..." name="task_due_date" value="{{$task->task_due_date}}">
  </div>
</div>
 

   <div class="form-group">
    <label>Description</label>
    <textarea placeholder="Enter Description..." class="form-control" name='task_detail'>{!! $task->task_detail !!}</textarea>
  </div>
  <input type="hidden" name="color" id='editColor' value="{{$task->color}}">
  <div class="form-group">

      <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
        <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
        <div style="width: 100%;min-height: 20px;background: {{ trim($task->color) == ''?'#3A87AD':$task->color }};" id='demo-color'></div><br>
        <ul class="fc-color-picker" id="color-chooser-modal">
          <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
          <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
          <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
          <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
          <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
          <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
          <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
          <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
          <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
          <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
          <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
          <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
        </ul>
      </div>

  </div>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left">Close</button>
    <button  type="submit" class="btn btn-success" value="complete" name="submit_option" >Complete Task</button>
  <button  type="submit" class="btn btn-primary">Update Task</button>

</div>
  


</form>
<script type="text/javascript">
   $('.modal-content .task_end_date').datepicker({
        //inline: true,
         dateFormat: 'yy-mm-dd',
          sideBySide: true,
          allowInputToggle: true,
          changeMonth: true,
            changeYear: true,
            yearRange: "-2:+5"
    });

    $('.modal-content .task_start_date').datepicker({
        //inline: true,
         dateFormat: 'yy-mm-dd',
          sideBySide: true,
          allowInputToggle: true,
          changeMonth: true,
            changeYear: true,
            yearRange: "-2:+5"
    });
    function rgb2hex1(rgb) {
          var hexDigits = ["0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"];
          rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
          function hex(x) {
            return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
          }
          return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }
    $('.modal-content #color-chooser-modal a').click(function (e) {
      e.preventDefault()
      //Save color
      let currColor = $(this).css('color');
      let color = rgb2hex1(currColor);      //Add color effect to button
      $('input#editColor').val(color);
  
      $('#demo-color').css({ 'background-color': currColor, 'border-color': currColor })
    })
</script>

