 <!-- Modal content-->
    

    <div class="modal-content">
      <form method="post" action="{{ route('admin.add_earned_leave.edit',$earnedLeave->id) }}">
        @csrf
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ $earnedLeave->user->first_name }} {{ $earnedLeave->user->last_name }}</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="control-label">Balance</label>
          <input type="number" name="num_of_carried" step="any" value="{{ $earnedLeave->num_of_carried }}" class="form-control" >
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" >Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>