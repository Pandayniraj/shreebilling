@extends('layouts.master')
@section('content')


<?php
 function CategoryTree($parent_id=null,$sub_mark='',$ledgers_data){

    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->get();

    if(count($groups)>0){
    	foreach($groups as $group){
    		echo '<option value="'.$group->id.'" disabled><strong>'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</strong></option>';

    		$ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$group->id)->get();
	          if(count($ledgers>0)){
	            $submark= $sub_mark;
	            $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

	             foreach($ledgers as $ledger){

	             if($ledgers_data->id == $ledger->id){
	              echo '<option selected value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
	              }else{

	           	  echo '<option value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
	           }

	           }
	           $sub_mark=$submark;

	        }
    		CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$ledgers_data);
    	}
    }
 }

 ?>


 <?php
      $mytime = Carbon\Carbon::now();
      $startOfYear = $mytime->copy()->startOfYear();

      $endOfYear   = $mytime->copy()->endOfYear();
  ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Report - Ledger Entries Statement</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
					<!-- <div id="accordion"> -->
						<!-- <h3>Options</h3> -->
						<div class="balancesheet form">
							<form  method="POST" action="/admin/accounts/reports/ledgerentries">
								{{ csrf_field() }}
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
									<label>Ledger Account</label>
										<select class="form-control customer_id" id="ReportLedgerId" name="ledger_id" aria-hidden="true">
											<option value="">Select</option>
											 {{ CategoryTree($parent_id=null,$sub_mark='',$ledgers_data) }}
										</select>

									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Start Date</label>
					                    <div class="input-group">
											<input id="ReportStartdate" type="text" name="startdate" class="form-control datepicker" value="">
					                        <div class="input-group-addon">
					                            <i>
					                                <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave start date as empty if you want statement from the start of the financial year.">
					                                </div>
					                            </i>
					                        </div>
					                    </div>
					                    <!-- /.input group -->
					                </div>
					                <!-- /.form group -->
								</div>
								 <div class="col-md-3">
									<div class="form-group">
										<label>End Date</label>

					                    <div class="input-group">
											<input id="ReportEnddate" type="text" name="enddate" class="form-control datepicker" value="">
					                        <div class="input-group-addon">
					                            <i>
					                                <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave end date as empty if you want statement till the end of the financial year.">
					                                </div>
					                            </i>
					                        </div>
					                    </div>
					                    <!-- /.input group -->
					                </div>
					                <!-- /.form group -->
								</div>
							</div>
							<div class="form-group">
								<input type="reset" name="reset" class="btn btn-primary pull-right" style="margin-left: 5px;" value="Clear">
								<input  type="submit" class="btn btn-primary pull-right" value="Submit">
						    </div>
					        </form>
					    </div>
					<!-- </div> -->
					<div class="subtitle">
						Ledger statement for <strong>[{{$ledgers_data->code}}] {{$ledgers_data->name}}</strong> from <strong>{{date('d-M-Y', strtotime($startOfYear))}}</strong> to <strong>{{date('d-M-Y', strtotime($endOfYear))}}</strong>
					</div>
					<div class="row" style="margin-bottom: 10px;">
						<div class="col-md-6">
							<table class="summary table table-hover table-bordered">
								<tbody>
									<tr>
										<td class="td-fixwidth-summary">Bank or cash account</td>
										<td>
											<?php
												echo ($ledger_data->type == 1) ? 'Yes' : 'No';
											?>
										</td>
								    </tr>
									<tr>
										<td class="td-fixwidth-summary">Notes</td>
										<td>{{$ledgers_data->notes}}</td>
									</tr>
							   </tbody>
						    </table>
						</div>
						<div class="col-md-6">
							<table class="summary table table-hover table-bordered">
								<tbody>
									<tr>
										<td class="td-fixwidth-summary">Opening balance as on <strong>{{date('d-M-Y', strtotime($startOfYear))}}</strong></td>
										<td>@if($ledgers_data->op_balance_dc == 'D') Dr @else Cr @endif {{$ledgers_data->op_balance}}</td>
								    </tr>
								    <?php
								   $closing_balance =TaskHelper::getLedgerBalance($id);

								    ?>


									<tr>
										<td class="td-fixwidth-summary">Closing balance as on <strong>{{date('d-M-Y', strtotime($endOfYear))}}</strong></td>
										<td>{{$closing_balance}}</td>
									</tr>
							    </tbody>
							</table>
						</div>
					</div>
					 <table class="table table-bordered" style="border: 1px solid #c4b8b8;">
				<thead>
					<tr>
						<th>Date</th>
						<th>Number</th>
						<th>Ledger</th>
						<th>Type</th>
						<th>Tag</th>
						<th>Debit Amount</th>
						<th>Credit Amount</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				   @foreach($entries as $entry)
						<tr>
							<td>{{$entry->date}}</td>
							<td>{{$entry->number}}</td>
							<td>{{TaskHelper::getLedger($entry->id)}}</td>
							<td>{{$entry->entrytype->name}}</td>
							<td><span class="tag" style="color:#034e6; background-color:#ffffff;"><span style="color: #034e6;">{{$entry->tagname->title}}</span></span></td>
							<td>{{env(APP_CURRENCY)}} {{$entry->dr_total}}</td>
							<td>{{env(APP_CURRENCY)}} {{$entry->cr_total}}</td>
							<td>
								<a href="/admin/entries/show/{{$entry->entrytype->label}}/{{$entry->id}}" class="no-hover" title="View" escape="false"><i class="glyphicon glyphicon-log-in"></i></a>
								<span class="link-pad"></span>
								<a href="/admin/entries/edit/{{$entry->entrytype->label}}/{{$entry->id}}" class="no-hover" title="Edit" escape="false"><i class="fa fa-edit"></i></a>
								<span class="link-pad"></span>
							 	<a href="/admin/entries/{{$entry->id}}/confirm-delete" class="no-hover" data-toggle="modal" data-target="#modal_dialog" title="Delete" escape="false"><i class="fa fa-trash deletable"></i></a>
							</td>
						</tr>
					@endforeach
				  </tbody>
				</table>
				<br>

				<a href="/admin/chartofaccounts/pdf/{{$ledgers_data->id}}" class="btn btn-primary">PDF</a>
          	    <a href="/admin/chartofaccounts/print/{{$ledgers_data->id}}" class="btn btn-primary">Print</a>

				 </div>
          </div>
      </div>


 <script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        });

      });
</script>

<script type="text/javascript">
         $(document).ready(function() {
            $('.customer_id').select2();
        });
</script>

@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')


@endsection
