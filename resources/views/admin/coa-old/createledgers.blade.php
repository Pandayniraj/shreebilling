@extends('layouts.master')
@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
	<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
 <script type="text/javascript">
    	
    $(document).on('change', '#LedgerGroupId', function() {
      var id = $('#LedgerGroupId').val();

      $.post("/admin/chartofaccounts/ledgers/getNextCode",
      {id: id, _token: $('meta[name="csrf-token"]').attr('content')},
      function(code){
        if(code != '')
              document.getElementById('l_code').value = code;
        else
            $("#ajax_status").after("<span style='color:red;' id='status_update'>Problem in generating the code</span>");

        $('#status_update').delay(3000).fadeOut('slow');
        //alert("Data: " + data + "\nStatus: " + status);
      });
    });


   $(document).ready(function() {

  var id = $('#LedgerGroupId').val();

      $.post("/admin/chartofaccounts/ledgers/getNextCode",
      {id: id, _token: $('meta[name="csrf-token"]').attr('content')},
      function(code){
        if(code != '')
              document.getElementById('l_code').value = code;
        else
            $("#ajax_status").after("<span style='color:red;' id='status_update'>Problem in generating the code</span>");

        $('#status_update').delay(3000).fadeOut('slow');
        //alert("Data: " + data + "\nStatus: " + status);
      });

});

  </script>

  <?php
  
 function CategoryTree($parent_id=null,$sub_mark=''){
    
    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->where('org_id',\Auth::user()->org_id)->get();

    if(count($groups)>0){
    	foreach($groups as $group){
    		echo '<option value="'.$group->id.'">'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</option>';
    		CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
    	}
    }
 }

 ?>

<div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Add Ledger</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	
				<div class="ledgers add form">
					<form action="/admin/chartofaccounts/store/ledgers" method="post" accept-charset="utf-8">
						{{@csrf_field()}}
						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<label>Parent Group</label>
										<select name="group_id" id="LedgerGroupId" class="form-control " tabindex="-1" aria-hidden="true">
									   {{ CategoryTree() }}

	                                   </select>

								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Ledger Code</label>
									<input type="text" name="code" id="l_code" value="" class="form-control" required="">
									<small id="ajax_status"></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Ledger name</label>
									<input type="text" name="name" value="{{ old('name') }}" class="form-control">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Opening balance</label>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<select name="op_balance_dc" class="form-control">
                                                  <option value="D">Dr</option>
                                                 <option value="C">Cr</option>
                                             </select>
										</div>
										<div class="col-md-8">
											<div class="form-group">
							                    <div class="input-group">
													<input type="number" value="0" name="op_balance" class="form-control">
							                        <div class="input-group-addon">
							                            <i>
						                                	<div class="fa fa-info-circle" data-toggle="tooltip" title="Note: Assets &amp; Expenses always have Dr balance and Liabilities &amp; Incomes always have Cr balance.">
							                                </div>
							                            </i>
							                        </div>
							                    </div>
							                    <!-- /.input group -->
							                </div>
							                <!-- /.form group -->
										</div>
									</div>
								</div>
							</div>
						</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								
								<input type="checkbox" name="type" value="1">
								<label>Bank or cash account</label>
								<p><small>Note: Select if the ledger account is a bank or a cash account.</small></p>
								<!-- <div class="input-group">
									<div class="input-group-addon">
										<i>
											<div class="fa fa-info-circle" data-toggle="tooltip" title="Note: Select if the ledger account is a bank or a cash account.">
											</div>
										</i>
									</div>
								</div> -->
								<!-- /.input group -->
			                </div>
			                <!-- /.form group -->
			                <div class="form-group">
			                <input type="checkbox" name="reconciliation" value="1">
			                	<label>Reconciliation</label>
			                	<p><small>Note : If selected the ledger account can be reconciled from Reports &gt; Reconciliation.</small></p>
			                    <!-- <div class="input-group">
			                        <div class="input-group-addon">
			                            <i>
			                                <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : If selected the ledger account can be reconciled from Reports > Reconciliation.">
			                                </div>
			                            </i>
			                        </div>
			                    </div> -->
			                    <!-- /.input group -->
			                </div>
			                <!-- /.form group -->
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label>Notes</label>
								<textarea name="notes" rows="3" class="form-control"></textarea>
							</div>
						</div>
					</div>
				</div>
            </div>
            <div class="box-footer">
            	<div class="form-group">
					<input type="submit" name="Submit" value="Submit" class="btn btn-success  pull-right">
					<a href="/admin/chartofaccounts" class="btn btn-default pull-right" style="margin-right: 5px;">Cancel</a>
				</div>
		    </div>
		    </form>
		            </div>
    </div>





@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<script type="text/javascript">
	
$('#LedgerGroupId').select2();
</script>

@endsection 