   
<form method="GET" action="{{ route('admin.users.disable', $user->id) }}">
   <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Disable {{ $user->first_name }} {{ $user->last_name }}</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
        	<label class="control-label">Resignation Date</label>
        	<input type="text" name="resgination_date" value="{{ date('Y-m-d') }}" class="form-control datepicker">
        </div>
      </div>
      <div class="modal-footer">
      	<button type="submit" class="btn btn-primary" >Disable</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
</form>
   <script type="text/javascript">
   	 $('.datepicker').datetimepicker({
          format: 'YYYY-MM-DD', 
          sideBySide: true,
         
        });
   </script>