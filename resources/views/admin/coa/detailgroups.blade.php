@extends('layouts.master')
@section('content')


<?php
 function CategoryTree($parent_id=null,$sub_mark='',$groups_data){
    
    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->where('org_id',\Auth::user()->org_id)->get();

    if(count($groups)>0){
    	foreach($groups as $group){
    		if($group->id<=4){
    		   echo '<option value="'.$group->id.'" disabled><strong>'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</strong></option>';
    	      }else{
    		echo '<option value="'.$group->id.'" '.(($group->id==request()->get('group_id')) ?'selected="selected"':"").'><strong>'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</strong></option>';
    	      }
    		CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$groups_data);
    	}
    }
 }

 ?>


 <?php
      $mytime = Carbon\Carbon::now();
      $startOfYear = $mytime->copy()->startOfYear();

      $endOfYear   = $mytime->copy()->endOfYear();
  ?>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                [{{$groups_data->code}}] {{$groups_data->name}} - Group Statement
                
            </h1>


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>


<div class="col-xs-12">
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
					<!-- <div id="accordion"> -->
						<!-- <h3>Options</h3> -->
						<div class="balancesheet form">
							<form  accept-charset="utf-8">
							<div class="row">
								 <div class="col-md-6">
									<div class="form-group">
									<label>Group Account</label>
										<select class="form-control select2-hidden-accessible" id="ReportLedgerId" name="group_id" tabindex="-1" aria-hidden="true">
											<option value="">Select</option>
											 {{ CategoryTree($parent_id=null,$sub_mark='',$groups_data) }}		
										</select>
										
									</div>
								</div> 
								<div class="col-md-3">
									<div class="form-group">
										<label>Start Date</label>
					                    <div class="input-group">
											<input id="ReportStartdate" type="text" name="startdate" class="form-control datepicker" value="{{ request()->get('startdate') ?? old('startdate')}}">
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
											<input id="ReportEnddate" type="text" name="enddate" class="form-control datepicker" value="{{request()->get('enddate') ?? old('enddate')}}">
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
								<input type="reset" name="reset" class="btn btn-primary btn-sm pull-right" style="margin-left: 5px;" value="Clear" id="btn-filter-clear">
								<input  type="submit" class="btn btn-primary btn-sm pull-right" value="Submit">
						    </div>
					        </form>
					    </div>
					<!-- </div> -->
				   <div class="subtitle">
						Group statement for <strong>[{{$groups_data->code}}] {{$groups_data->name}}</strong> from <strong>{{request()->get('startdate') ?? date('d-M-Y', strtotime($startOfYear))}}</strong> to <strong>{{request()->get('enddate') ?? date('d-M-Y', strtotime($endOfYear))}}</strong>					
					</div>
						
			
					<table class="table table-hover table-bordered table-striped">

					<tbody>
						<tr class="bg-red">
							<th>Date</th>
							<th>No.</th>
							<th>Ledger</th>
							<th>Description</th>
							<th>Entry Type</th>
							<th>Tag</th>
							<th> ({{env(APP_CURRENCY)}})</th>
							<th> ({{env(APP_CURRENCY)}})</th>
							<th>Balance ({{env(APP_CURRENCY)}})</th>
							<th></th>
					    </tr>

					    <tr class="tr-highlight">
					    	<td colspan="7">

							<span class="label label-default">Total No of enttries {{ $entry_items->count() }}</span></td>
					    	<td></td>
					    	<td></td>
					    	<td></td>
					    </tr> 
					
					    @foreach($entry_items as $ei)

                        <?php 

					    $entry_balance = TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
							$ei['amount'], $ei['dc']);

					    $getledger= TaskHelper::getLedger($ei->entry_id);

					    ?>
                      
					    <tr>
					    	<td>{{$ei->entry->date}}</td>
					    	<td>{{$ei->entry->number}}</td>
					    	<td>{{ substr($ei->ledgerdetail->name, 0,  20) }}</td>
					    	<td>{{$getledger}}</td>
					    	<td>{{$ei->entry->entrytype->name}}</td><td><span class="tag" style="color:#f51421; background-color:#gba(17;">
							<span class="label {{$ei->entry->tagname->color}}">{{$ei->entry->tagname->title}}</span>
					    	    </span>
					    	</td>

					    	@if($ei->dc=='D')
						    	<td>Dr {{$ei->amount}}</td>
						    	<td>-</td>
					    	@else
						    	<td>-</td>
						    	<td>Cr {{$ei->amount}}</td>
					    	@endif

					    	<td>@if($entry_balance['dc']=='D') Dr @else Cr @endif {{number_format($entry_balance['amount'],2)}}</td>

					    	<td>
					    		<a href="/admin/entries/show/{{$ei->entry->entrytype->label}}/{{$ei->entry_id}}" class="no-hover" escape="false" title='View'><i class="glyphicon glyphicon-log-in"></i></a>
								<span class="link-pad"></span>
								<a href="/admin/entries/edit/{{$ei->entry->entrytype->label}}/{{$ei->entry_id}}" class="no-hover" escape="false" title="Edit"><i class="fa fa-edit"></i></a>
								<span class="link-pad"></span>
								<a href="/admin/entries/{{$ei->entry_id}}/confirm-delete" class="no-hover"  data-toggle="modal" data-target="#modal_dialog" escape="false" title="Delete"><i class="fa fa-trash deletable"></i></a>
						    </td>
						    
					    </tr>



					    @endforeach

						<tr class="tr-highlight ">
							<td colspan="7" style="text-align: center;"><b>Current closing balance</b></td>
							<td><b>@if($entry_balance['dc']=='D') Dr @else Cr @endif {{ number_format($entry_balance['amount'],2)}}</b></td>
							<td colspan="2"></td>
						</tr>
					</tbody>
				</table>
				<br>

				<a href="/admin/chartofaccounts/groupdetail/pdf/{{$groups_data->id}}" class="btn btn-primary">PDF</a>
				<a href="/admin/chartofaccounts/groupdetail/excel/{{$groups_data->id}}" class="btn btn-primary">Excel</a>
          	    <a href="/admin/chartofaccounts/groupdetail/print/{{$groups_data->id}}" class="btn btn-primary">Print</a>

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

		$("#btn-filter-clear").on("click", function () {
		  window.location.href = "{!! url('/') !!}/admin/chartofaccounts/detail/{{$id}}/ledgers";
		});

</script>


@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom') 


@endsection  