@extends('layouts.master')
@section('content')

	<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	/**
	 * On changing the parent group select box check whether the selected value
	 * should show the "Affects Gross Profit/Loss Calculations".
	 */
	$('#GroupParentId').change(function() {
		if ($(this).val() == '3' || $(this).val() == '4') {
			$('#AffectsGross').show();
		} else {
			$('#AffectsGross').hide();
		}
	});

	
});


 $(document).on('change', '#GroupParentId', function() {
      var id = $('#GroupParentId').val();

      $.post("/admin/chartofaccounts/groups/getNextCode",
      {id: id, _token: $('meta[name="csrf-token"]').attr('content')},
      function(code){
        if(code != '')
              document.getElementById('g_code').value = code;
        else
            $("#ajax_status").after("<span style='color:red;' id='status_update'>Problem in generating the code</span>");

        $('#status_update').delay(3000).fadeOut('slow');
        //alert("Data: " + data + "\nStatus: " + status);
      });
    });

$(document).ready(function() {

   var id = $('#GroupParentId').val();

      $.post("/admin/chartofaccounts/groups/getNextCode",
      {id: id, _token: $('meta[name="csrf-token"]').attr('content')},
      function(code){
        if(code != '')
              document.getElementById('g_code').value = code;
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
              <h3 class="box-title"></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	  <div class="groups add form">
					<form action="/admin/chartofaccounts/store/groups" method="post" accept-charset="utf-8">
						{{@csrf_field()}}
                       <div class="row">
	                      <div class="col-xs-5">
		                    <div class="form-group">
		                    	<label for="parent_id">Parent Group</label>
		                    	<select name="parent_id" class="form-control " id="GroupParentId" tabindex="-1" aria-hidden="true">
		                    		{{ CategoryTree() }}
                                </select>
			                </div>
			              </div>
							<div class="col-xs-2">
								<div class="form-group">
									<label for="code">Group Code</label>
									<input type="text" name="code" value="" class="form-control" id="g_code"  required>
									<small id="ajax_status"></small>
							    </div>
							</div>
							<div class="col-xs-5">
								<div class="form-group">
									<label for="name">Group Name</label>
									<input type="text" name="name" value="" class="form-control">
								</div>
							</div>
			            </div>
			            <div class="form-group required" id="AffectsGross" style="display:none;">
			            	<label for="affects_gross" class="">Affects</label><br>
			            	<div class="iradio_square-green" aria-checked="false" aria-disabled="false" style="position: relative;">
			            	<input type="radio" name="affects_gross" value="1" checked="checked" id="affects_gross" ><label for="affects_gross" class="form-check-label">Gross Profit &amp; Loss </label>
			                 </div>
                           <div class="iradio_square-green checked" aria-checked="true" aria-disabled="false" style="position: relative;">
                          	<input type="radio" name="affects_gross" value="0" id="affects_gross" ><label for="affects_gross" class="form-check-label">Net Profit &amp; Loss </label>
                          </div>
                          <span class="help-block">Note: Changes to whether it affects Gross or Net Profit &amp; Loss is reflected in final Profit &amp; Loss statement.</span>
                      </div>
							<div class="form-group">
								<input type="submit" name="submit" value="Submit" class="btn btn-success pull-right">
								<span class="link-pad">
								</span>
							    <a href="/admin/chartofaccounts" class="btn btn-default pull-right" style="margin-right: 5px;">Cancel</a>
							</div>
			       </form>
			    </div>
			 </div>
		</div>
</div>





@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<script type="text/javascript">
	$('#GroupParentId').select2();
</script>

@endsection 