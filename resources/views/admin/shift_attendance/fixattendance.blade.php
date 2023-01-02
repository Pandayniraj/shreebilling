
<div id="attendaceFix" class="modal" role="dialog fade" style="z-index: 10000000;">
  <div class="modal-dialog">

    <div class="modal-content">
      
        <div class="modal-body wrap-modal wrap">

        <form class="form-horizontal" action="#" method="post" onsubmit="event.preventDefault();updatefix()">
            {{ csrf_field() }}
            <div class="form-group">
            <label class="control-label col-sm-2" for="email">Time:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control fixdatetimepicker" 
              placeholder="Enter the time to fix">
            </div>
            <input type="hidden" name="date" >
          </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-primary submit-button" >Update</button>
              
               <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </form>

        </div>

    </div>
    
  </div>
</div>


<script type="text/javascript">      

    $('.fixdatetimepicker').datetimepicker({
        format: 'YYYY-MM-DD H:m:s',
        sideBySide: true
    });



function openattendanceFix(ev){
    console.log(ev);
    var el = $(ev);

    let _type = el.attr('data-type');
  
    if(_type == 'edit'){
        $('#attendaceFix form .submit-button').text('Update time');
        let _id = el.attr('data-id');
        let _value = el.attr('data-value');
        let action = `/admin/shiftAttendanceFix/${_id}`;
        $('#attendaceFix form input.fixdatetimepicker').val(_value);
        $('#attendaceFix form').attr('action',action);
        $('#attendaceFix').modal('show');

    }else{
        $('#attendaceFix form .submit-button').text('Add Clock Out');
        let action = `/admin/shiftAttendanceFix/new`;
        $('#attendaceFix form').attr('action',action);
        $('#attendaceFix').modal('show');
    }

}

function updatefix( value = false,shouldreload = true){
    var token =  $('meta[name="csrf-token"]').attr('content');
    if(!value){

        var _value = $('#attendaceFix form input.fixdatetimepicker').val();
    }else{
       var _value = value;
    }
   if(!_value){
        return false;
   }
    var _action = $('#attendaceFix form').attr('action');
    var _date = $('span#attendanceDate').attr('data-value');
    var _userId = $('span#attendanceUserId').attr('data-value');
    var _shiftId = $('span#attendanceShiftId').attr('data-value');
    let data = {
        _token: token,
        time: _value,
        date: _date,
        shift_id: _shiftId,
        user_id: _userId

    }

    
    $('#attendaceFix form input.fixdatetimepicker').prop('disabled','true');
  
    $.post(_action,data,function(data,status){
        if(shouldreload){
            location.reload();
        }else{
            alert('DateTime Updated');
        }
        
        $('#attendaceFix form input.fixdatetimepicker').prop('disabled',false);
    }).fail(function(){
        alert("failed to update");
        $('#attendaceFix form input.fixdatetimepicker').prop('disabled',false);
    });
    return false;
}
</script>