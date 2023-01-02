@extends('layouts.master')
@section('content')


<?php
 function CategoryTree($parent_id=null,$sub_mark='',$ledgers_data){
    
    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->get();

    if(count($groups)>0){
    	foreach($groups as $group){
    		echo '<option value="'.$group->id.'" disabled><strong>'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</strong></option>';

    		$ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$group->id)
    				->get(); 
	          if(count($ledgers)>0){
	            $submark= $sub_mark;
	            $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 

	             foreach($ledgers as $ledger){

	             if( ($ledgers_data->id??'') == $ledger->id){
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
<style type="text/css">
		#entry_list td,#entry_list th{


		font-size: 12px;

	}
</style>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>


<div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Report > Ledger Statement</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
					<!-- <div id="accordion"> -->
						<!-- <h3>Options</h3> -->
						<div class="balancesheet form">
							<form  method="GET" action="/admin/accounts/reports/ledger_statement" 
							id='ledgerstatementfilterform'>
								
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
									<label>Ledger Account</label>
										<select class="form-control input-sm customer_id select2" id="ReportLedgerId" name="ledger_id" tabindex="-1" aria-hidden="true">
											<option value="">Select</option>
											 {{ CategoryTree($parent_id=null,$sub_mark='',$ledgers_data) }}		
										</select>
										
									</div> 
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Start Date</label>
					                    <div class="input-group">
											<input id="ReportStartdate" type="text" name="startdate" class="form-control input-sm datepicker" value="{{$startdate}}">
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
								 <div class="col-md-2">
									<div class="form-group">
										<label>End Date</label>

					                    <div class="input-group">
											<input id="ReportEnddate" type="text" name="enddate" class="form-control input-sm datepicker" value="{{$enddate}}">
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
								<div class="col-md-2">
									<div class="form-group">
										<label>Currency</label>

					                    <div class="input-group">
								<select class="form-control input-sm select2 currency" name="currency" >
									
                                    @foreach($currency as $k => $v)
                                    <option value="">Select Currency</option>
                                    <option value="{{ $v->currency_code }}" @if(isset($v->currency_code) && $v->currency_code == $selected_currency) selected="selected"@endif>
                                        {{ $v->name }} {{ $v->currency_code }} ({{ $v->currency_symbol }})</option>
                                    @endforeach

                                </select>
					                        
					                    </div>
					                    <!-- /.input group -->
					                </div>
					                <!-- /.form group -->
								</div>

							<div class="col-md-2">
								<label></label>
							<div class="form-group">

								<a type="reset" href="/admin/accounts/reports/ledger_statement" class="btn btn-primary pull-right btn-sm" style="margin-left: 5px;" value="Clear">Clear</a>
								<input  type="submit" class="btn btn-primary pull-right btn-sm" value="Submit">
						    </div>
						</div>
					        </form>
					    </div>
					<!-- </div> -->
					<div class="subtitle">
						<strong>[{{$ledgers_data->code??''}}] {{$ledgers_data->name??''}}</strong> 
						<br>
						Transaction Date &nbsp;from <strong>{{  $startdate ?? date('d-M-Y', strtotime($startOfYear))}}</strong> to <strong>{{$enddate ?? date('d-M-Y', strtotime($endOfYear))}}</strong>				
					</div>
					<div class="row" style="margin-bottom: 10px;">
						<div class="col-md-6">
							<table class="summary table table-hover table-bordered">
								<tbody>
									<tr>
										<td class="td-fixwidth-summary">Bank or cash account</td>
										<td>
											<?php
												echo (isset($ledger_data) && $ledger_data->type == 1) ? 'Yes' : 'No';
											?>
										</td>
								    </tr>
									<tr>
										<td class="td-fixwidth-summary">Notes</td>
										<td>{{$ledgers_data->notes??''}}</td>
									</tr>
							   </tbody>
						    </table>
						</div>
						<div class="col-md-6">
							<table class="summary table table-hover table-bordered">
								<tbody>
									<tr>
										<td class="td-fixwidth-summary">Opening balance as on <strong>{{ $startdate ?? date('d-M-Y', strtotime($startOfYear))}}</strong></td>
										<td style="font-size:16px">
											@if( ($ledgers_data->op_balance_dc??'') == 'D') Dr @else Cr @endif {{$ledgers_data->op_balance??''}}</td>
								    </tr>
								    <?php 
								   $closing_balance =TaskHelper::getLedgerBalance($id)['ledger_balance'];

								    ?>

									<tr>
										<td class="td-fixwidth-summary">Closing balance as on <strong>{{$enddate ?? date('d-M-Y', strtotime($endOfYear))}}</strong></td>
										<td style="font-size:16px">{{$closing_balance}}</td>
									</tr>
							    </tbody>
							</table>
						</div>
					</div>
					<table class="table table-hover table-bordered">

					<tbody id='entry_list'>
						<tr class="bg-orange">
							<th>Date</th>
							<th>Miti</th>
							<th>Ref.</th>
							<th>Bill No.</th>
							<th>Description</th>
							<th>Type</th>
							<th>Tag</th>
							<th>(Dr)</th>
							<th>(Cr)</th>
							<th>Balance ({{ $selected_currency }})</th>
							<th></th>
					    </tr>

					    <tr class="tr-highlight">
					    	<td colspan="7">Current opening balance</td>
					    	<td>@if( ($ledgers_data->op_balance_dc??'') == 'D') Dr @else Cr @endif  {{$ledgers_data->op_balance??''}}</td>
					    	<td></td>
					    </tr>

					    <?php
						/* Current opening balance */
						$entry_balance['amount'] = $ledgers_data['op_balance'] ?? '';
						$entry_balance['dc'] = $ledgers_data['op_balance_dc'] ??'';
						
					   ?>

					    @foreach($entry_items as $ei)

                        <?php 

					    $entry_balance = TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
							$ei['amount'], $ei['dc']);
					
					    $getledger= TaskHelper::getLedger($ei->entry_id);
					    ?>

					    <tr>
					    	<td style="white-space: nowrap;">{{$ei->entry->date}}</td>
					    	<td style="white-space: nowrap;">{{ TaskHelper::getNepaliDate($ei->entry->date) }}</td>
					    	<td>{{   substr($ei->entry->number,0,5)   }} 
								{{  strlen($ei->entry->number) > 5 ? '...' : '' }}</td>
					    	<td>{{ $ei->entry ?  $ei->entry->billNum() : '-'  }}</td>
					    	@php $getEntryType = $ei->entry->getEntryType();    @endphp 
        					<td>{{$getledger}} \ {{  $getEntryType['type']}} No. [{{ $getEntryType['order']->bill_no }}]</td>
					    	<td>{{$ei->entry->entrytype->name??''}}</td>
					    	<td>
					    		<span class="tag" style="color:#f51421;">
					    			<span style="color: #f51421;">
					    				{{$ei->entry->tagname->title}}
					    			</span>
					    	    </span>
					    	</td>
					    	@if($ei->dc=='D')
						    	<td>{{$ei->currency}} {{$ei->amount}}</td>
						    	<td>-</td>
					    	@else
						    	<td>-</td>
						    	<td>{{$ei->currency}} {{$ei->amount}}</td>
					    	@endif

					    	<td>@if($entry_balance['dc']=='D') Dr @else Cr @endif 
					    	{{ 
					    	is_numeric($entry_balance['amount']) ? number_format($entry_balance['amount'],2) : '-'  }}</td>

					    	<td>
					    		<a href="/admin/entries/show/{{$ei->entry->entrytype->label}}/{{$ei->entry_id}}" class="no-hover" escape="false" title='View'><i class="glyphicon glyphicon-log-in"></i></a>
								<span class="link-pad"></span>
								<a href="/admin/entries/edit/{{$ei->entry->entrytype->label}}/{{$ei->entry_id}}" class="no-hover" escape="false" title="Edit"><i class="fa fa-edit"></i></a>
								<span class="link-pad"></span>
								<a href="/admin/entries/{{$ei->entry_id}}/confirm-delete" class="no-hover"  data-toggle="modal" data-target="#modal_dialog" escape="false" title="Delete"><i class="fa fa-trash deletable"></i></a>
						    </td>

					    </tr>

					    @endforeach

						<tr class="tr-highlight">
							<td colspan="7">Current closing balance</td>
							<td style="font-size: 16.5px;font-weight: 600;">@if($entry_balance['dc']=='D') Dr @else Cr @endif 
							{{ is_numeric($entry_balance['amount']) ?  number_format($entry_balance['amount'],2) : '-' }}

							</td>
							<td></td>
						</tr>
					</tbody>
				</table>
				<div align="align">{!! $entry_items->appends(\Request::except('page'))->render() !!}</div>
				<br>
{{-- 
				<a href="/admin/chartofaccounts/pdf/{{$ledgers_data->id}}" class="btn btn-primary">PDF</a> --}}
          	   

          	    <a href="javascript::void()" class="btn btn-success" onclick="downloadfile('excel')">Excel</a>

          	    <a href="javascript::void()" class="btn btn-primary" onclick="downloadfile('pdf')">PDF</a>

          	    <a href="javascript::void()" class="btn btn-primary" onclick="downloadfile('print')">Print</a>

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

         function downloadfile(type){

         	let data = $('#ledgerstatementfilterform').serializeArray().reduce(function(obj, item) {
				        obj[item.name] = item.value;
				        return obj;
				    }, {});

         	let startdate = data.startdate;

         	let enddate = data.enddate;

         	location.href = `/admin/chartofaccounts/${type}/{{$ledgers_data->id??''}}?startdate=${startdate}&enddate=${enddate}`;
         	
         }


</script>


@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom') 


@endsection  