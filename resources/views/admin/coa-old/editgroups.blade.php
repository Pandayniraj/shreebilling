@extends('layouts.master')
@section('content')

<?php
 function CategoryTree($parent_id=null,$sub_mark='',$group_data){

 	
	    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->where('org_id',\Auth::user()->org_id)->get();

	    if(count($groups)>0){

	    	foreach($groups as $key=>$group){
	    	 if($parent_id != $group_data->id && $group_data->id != $group->id){

	    		if($group_data->parent_id == $group->id){

	    			echo '<option selected value="'.$group->id.'" >'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</option>';

	    		}else{
	    				echo '<option value="'.$group->id.'">'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</option>';

	    		}
	    		
	    		CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$group_data);
	    	}

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
					<form action="/admin/chartofaccounts/update/{{$group_data->id}}/groups" method="post" accept-charset="utf-8">
						{{@csrf_field()}}
                       <div class="row">
	                      <div class="col-xs-5">
		                    <div class="form-group">
		                    	<label for="parent_id">Parent Group</label>
		                    	<select name="parent_id" class="form-control select2-hidden-accessible" id="GroupParentId" tabindex="-1" aria-hidden="true">
		                    		{{ CategoryTree($parent_id=null,$sub_mark='',$group_data) }}
                                </select>
			                </div>
			              </div>
							<div class="col-xs-2">
								<div class="form-group">
									<label for="code">Group Code</label>
									<input type="text" name="code" value="{{$group_data->code}}" class="form-control" id="g_code">
							    </div>
							</div>
							<div class="col-xs-5">
								<div class="form-group">
									<label for="name">Group Name</label>
									<input type="text" name="name" value="{{$group_data->name}}" class="form-control">
								</div>
							</div>
			            </div>

			            <div class="form-group required" id="AffectsGross" style="display:none;">
			            	<label for="affects_gross" class="">Affects</label><br>
			            	<div class="iradio_square-green" aria-checked="false" aria-disabled="false" style="position: relative;">
			            	<input type="radio" name="affects_gross" value="1" checked="checked" id="affects_gross" @if($group_data->affects_gross == 1) checked @endif><label for="affects_gross" class="form-check-label">Gross Profit &amp; Loss </label>
			                 </div>
                           <div class="iradio_square-green checked" aria-checked="true" aria-disabled="false" style="position: relative;">
                          	<input type="radio" name="affects_gross" value="0" id="affects_gross"  @if($group_data->affects_gross == 0) checked @endif ><label for="affects_gross" class="form-check-label">Net Profit &amp; Loss </label>
                          </div>
                          <span class="help-block">Note: Changes to whether it affects Gross or Net Profit &amp; Loss is reflected in final Profit &amp; Loss statement.</span>
                        </div>
							<div class="form-group">
								<input type="submit" value="Update" class="btn btn-success pull-right">
								<span class="link-pad">
								</span>
							    <a href="/admin/chartofaccounts" class="btn btn-default pull-right" style="margin-right: 5px;">Cancel</a>
							</div>
			       </form>
			    </div>
			 </div>
		</div>
</div>


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

     if ($('#GroupParentId').val() == '3' || $('#GroupParentId').val() == '4') {
			$('#AffectsGross').show();
		} else {
			$('#AffectsGross').hide();
		}


});


 $(document).on('change', '#GroupParentId', function() {
      var id = $('#GroupParentId').val();

      $.post("/admin/chartofaccounts/groups/getNextCode",
      {id: id, _token: $('meta[name="csrf-token"]').attr('content')},
      function(data, status){
        if(data.status == '1')
            $("#ajax_status").after("<span style='color:green;' id='status_update'>Rating is successfully updated.</span>");
        else
            $("#ajax_status").after("<span style='color:red;' id='status_update'>Problem in updating rating; Please try again.</span>");

        $('#status_update').delay(3000).fadeOut('slow');
        //alert("Data: " + data + "\nStatus: " + status);
      });
   });



</script>


@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')


@endsection 