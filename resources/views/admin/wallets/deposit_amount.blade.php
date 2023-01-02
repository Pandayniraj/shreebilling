 <div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Deposit Amount</h4>
  </div>

<form action="{{route('admin.users.wallet.deposit',$userId)}}" onsubmit="return confirm('Are You the information is correct')" method="post">
  {!! csrf_field() !!}
  <div class="modal-body">
    
    <div class="form-group">
      <label for="amount">Amount</label>
      <input type="number" step="any" class="form-control" placeholder="Enter amount deposit..." required="" name="amount" >
    </div>
   
  </div>
  <div class="modal-footer">
      <button type="submit" class="btn btn-primary" >Deposit</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
  </form>
</div>