@extends('layouts.master')
@section('content')
	<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

 <?php
 function CategoryTree($parent_id=null,$sub_mark='',$ledgers_data){

    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->where('org_id',\Auth::user()->org_id)->get();

    if(count($groups)>0){
    	foreach($groups as $group){

    		if($ledgers_data->group_id == $group->id){

    			echo '<option value="'.$group->id.'" Selected>'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</option>';

    		}else{
    				echo '<option value="'.$group->id.'">'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</option>';

    		}

    		CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$ledgers_data);
    	}
    }
 }

 ?>

<div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Ledger</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

				<div class="ledgers add form">
					<form action="/admin/chartofaccounts/update/{{$ledgers_data->id}}/ledgers" method="post" accept-charset="utf-8">
						{{@csrf_field()}}
						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<label>Parent Group</label>
										<select name="group_id" id="LedgerGroupId" class="form-control" onchange="getLedgerNumber()" tabindex="-1" aria-hidden="true">
								     	{{ CategoryTree($parent_id=null,$sub_mark='',$ledgers_data) }}
	                                   </select>

								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Ledger Code</label>
									<input type="text" name="code" id="l_code" value="{{$ledgers_data->code}}" class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Ledger name</label>
									<input type="text" name="name" value="{{$ledgers_data->name}}" class="form-control">
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
                                                  <option value="D"@if($ledgers_data->op_balance_dc == 'D') selected="selected"@endif>Dr</option>
                                                 <option value="C"@if($ledgers_data->op_balance_dc == 'C') selected="selected"@endif>Cr</option>
                                             </select>
										</div>
										<div class="col-md-8">
											<div class="form-group">
							                    <div class="input-group">
													<input type="number" value="{{$ledgers_data->op_balance}}" name="op_balance" class="form-control" step="any">
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

								<input type="checkbox" name="type" value="1" @if($ledgers_data->type == '1') checked="checked"@endif>
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
			                <input type="checkbox" name="reconciliation" value="1" @if($ledgers_data->reconciliation == '1') checked="checked"@endif>
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
							<div class="form-group">
								<input type="checkbox" name="affect_stock" value="1" @if($ledgers_data->affect_stock == '1') checked="checked"@endif>
								<label>Affects Stock Ledger</label>
								<p><small>Note : If selected the ledger account can be associated with products stock ledger.</small></p>
							</div>
			                <!-- /.form group -->
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label>Notes</label>
								<textarea name="notes" rows="3" class="form-control">{{$ledgers_data->notes}}</textarea>
							</div>
						</div>
					</div>
				</div>
            </div>
            <div class="box-footer">
            	<div class="form-group">
					<input type="submit" value="Update" class="btn btn-success  pull-right">
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
