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
  
 function CategoryTree($parent_id=null,$sub_mark='',$selectedgroup_value){
    
    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)
              ->where('org_id',\Auth::user()->org_id)
              ->where(function($query) use ($selectedgroup_value){
                if($selectedgroup_value)
                {
                  return $query->where('id',$selectedgroup_value);
                }
              })
              ->get();

    if(count($groups)>0){
    	foreach($groups as $group){
    	
    		echo '<option value="'.$group->id.'">'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</option>';
    
    		CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",false);
    	}
    }
 }

 ?>
<div class="alert alert-danger alert-dismissable" style="display: none;" id="errormodal">
        <button type="button" aria-hidden="true" class="close" onclick="$('#errormodal').slideUp(300)">×</button>
        <h4>
            <i class="icon fa fa-ban"></i> Errors
        </h4>
        <div id="modalserrors">
        </div>
    </div>
<div class="modal-content" id='ledger_modals'>
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Add Ledger
                <small>Create new Ledger</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>

          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            	
				<div class="ledgers add form">
					<form  id='form_submit'>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Parent Group</label>
										<select name="group_id" id="LedgerGroupId" class="form-control searchable-ledgers" tabindex="-1" aria-hidden="true">
									 {{ CategoryTree($parent_id=null,$sub_mark='',$selectedgroup_value) }}

	                                   </select>

								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Ledger Code</label>
									<input type="text" name="code" id="l_code" value="" class="form-control" readonly>
									<small id="ajax_status"></small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Ledger name</label>
									<input type="text" name="name" value="" class="form-control">
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
            <div class="box-footer">
            	<div class="form-group">
					<input type="submit" name="Submit" value="Submit" class="btn btn-success  pull-right">
					<a href="#" class="btn btn-default pull-right close-modal" data-dismiss="modal" aria-hidden="true" style="margin-right: 5px;">Cancel</a>
				</div>
		    </div>
		    </form>
		            </div>
    </div>
</div>
</div>
</div>
<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
<script type="text/javascript">
	const expenses_type = `{{ $expenses_type }}`;
  const selectedgroup_value = `{{ $selectedgroup_value }}`;
  $('.searchable-ledgers').select2({dropdownParent: $("#modal_dialog")});
    $('#ledger_modals #form_submit').submit(function(){
        $("#overlay").fadeIn(300);　
        var obj ={};
        var data = JSON.stringify( $('#form_clients').serializeArray() ); //  <-----------
        var paramObj = {};
        $.each($('#form_submit').serializeArray(), function(_, kv) {
        paramObj[kv.name] = kv.value;
        });
        paramObj['_token']= $('meta[name="csrf-token"]').attr('content');
        // console.log(paramObj);
        // return false;
        $.post(`/admin/chartofaccounts/store/ledgers?selectedgroup=${selectedgroup_value}&expenses_type=${expenses_type}`,paramObj,function(data,status){
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