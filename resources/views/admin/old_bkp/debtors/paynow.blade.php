<!-- Modal content-->


<form action="{{ route('admin.debtors_pay-subm') }}" method="POST">
  @csrf
  <div class="modal-content">
    <div class="modal-header bg-maroon">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Receive Now From {{ $selectLeger->name }}</h4>
    </div>
    <input type="hidden" name="ledger_id" value="{{ $ledger_id }}">
        <div class="modal-body">

            <div class="form-group">
            <label for="email">Received Amount:</label>
            <input type="number" class="form-control"  step="any" placeholder="Enter Amount" required="" name="amount" autocomplete="off">
          </div>

           <div class="form-group">
            <label for="email">TDS Amount:</label>
            <input type="number" class="form-control"  step="any" placeholder="Enter TDS Amount" required="" name="tds_amount" value="0" autocomplete="off">
          </div>

          <div class="form-group">
            <label>Receive in Ledger</label>
            <select name="ledger_type" class="form-control" required="" >
              <option value="">Select Ledger</option>
              @foreach($ledgers as $key=>$value)
                <option value="{{ $value->id }}">
                  [{{ $value->code  }}]  {{ $value->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure')">Pay</button>
    </div>
  </div>
</form>
