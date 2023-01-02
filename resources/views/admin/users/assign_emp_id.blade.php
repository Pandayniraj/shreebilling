<!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Add Employee ID of {{ $user->first_name }}  {{ $user->last_name }}</h4>
    </div>

    <form method="post" action="{{ route('admin.assign_empid',$user->id) }}">

      @csrf

    <div class="modal-body">
      
        <h5 style="white-space: nowrap;">
          <img src="{{ $user->image?'/images/profiles/'.$user->image:$user->avatar }}" alt="User Image"

          style="height: 50px;width: 50px;">
   
        <span style="margin-top: 4px;position: absolute;">&nbsp;{{ $user->first_name }} {{ $user->last_name }}</span>
        <br>
          <small style="margin-left: 54px;margin-top: -25px;position: absolute;"> {{ $user->designation->designations}}</small>
      </h5>
      <hr>
    
      <div class="form-group">
        <label class="control-label col-sm-2">Emp id:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="emp_id" name="emp_id" placeholder="Set Employe id" 
          value="{{ $user->emp_id }}">
        </div>
      </div>
      <br><br>


    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary" >Submit</button>
    </div>

    </form>
  </div>