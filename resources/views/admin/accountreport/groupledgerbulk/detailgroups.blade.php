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
    $fiscal=\App\Models\Fiscalyear::where('fiscal_year',$fiscal_year)->first();
$startOfYear = $startdate?$startdate:$fiscal->start_date;

$endOfYear   = $enddate?$enddate:$fiscal->end_date;
?>
<style type="text/css">
    #entry_list td,#entry_list th{


        font-size: 13px;

    }
</style>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                @if($groups_data)[{{$groups_data->code}}] {{$groups_data->name}} - @endif Group Ledger Statement

            </h1>


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>


<div class="col-xs-12">
          <div class="box">

              <div class="box-header with-border">
                  <h3 class="box-title">Reports > Group Statement Bulk</h3>
                  @if($ledgers_data)
                      <a href="/admin/accounts/reports/group-ledger-bulk?startdate={{$startOfYear}}&enddate={{$endOfYear}}&group_id={{$groups_data->id}}&fiscal_year={{\Request::get('fiscal_year')}}&excel=true"
                         class="btn btn-success btn-sm pull-right"><i class="fa fa-file-excel-o"></i>  Export to Excel</a>
                  @endif
              </div>

            <!-- /.box-header -->
            <div class="box-body">
					<!-- <div id="accordion"> -->
						<!-- <h3>Options</h3> -->
						<div class="balancesheet form">
							<form  accept-charset="utf-8">
							<div class="row" style="display: flex">
								 <div class="col-md-5">
									<div class="form-group">
									<label>Ledger Groups</label>
										<select class="form-control select2" id="ReportLedgerId" name="group_id" aria-hidden="true">
											<option value="">Select Group</option>
											 {{ CategoryTree($parent_id=null,$sub_mark='',$groups_data) }}
										</select>

									</div>
								</div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <div class="input-group">
                                            <input id="ReportStartdate" type="text" name="startdate" class="form-control input-sm datepicker" value="{{$startOfYear}}">
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
                                            <input id="ReportEnddate" type="text" name="enddate" class="form-control input-sm datepicker" value="{{$endOfYear}}">
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
                                <div >
                                    <div class="form-group">
                                        <label>Fiscal Year</label>

                                        <div class="input-group">
                                            <select id="fiscal_year_id" class="form-control input-sm" name="fiscal_year" required="required">
                                                @foreach($allFiscalYear as $key => $pk)
                                                    <option value="{{ $pk->fiscal_year }}" {{$fiscal_year==$pk->fiscal_year?'selected':''}}>{{ $pk->fiscal_year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                                <div style="margin-left: 10px">
                                    <label>
                                        <label></label>
                                    </label>
                                    <div class="form-group">
                                        <input type="reset" name="reset" class="btn btn-primary btn-sm pull-right" style="margin-left: 5px;" value="Clear" id="btn-filter-clear">
                                        <input  type="submit" class="btn btn-primary btn-sm pull-right" value="Submit">
                                    </div>
                                </div>

							</div>

					        </form>
					    </div>
					<!-- </div> -->
                @if(Request::get('group_id'))
				   <div class="subtitle" style="margin-bottom: 5px">
						Group statement for <strong>[{{$groups_data->code}}] {{$groups_data->name}}</strong> from <strong>
                           {{date('d-M-Y', strtotime($startOfYear))}}</strong> to <strong>{{date('d-M-Y', strtotime($endOfYear))}}</strong>
					</div>
                @endif


					<table class="table table-hover table-bordered table-striped">

					<tbody id='entry_list'>
						<tr class="bg-red">
                            <th style="width: 4%;text-align: center">SNo.</th>
                            <th style="width:10%">Ledger Code</th>
							<th>Ledger</th>
							<th>Opening Balance</th>
							<th>Dr Amount</th>
							<th>Cr Amount</th>
							<th>Closing Balance</th>
					    </tr>
{{--                        @dd($ledgers_data);--}}
                        @if($ledgers_data)
                            <?php
                                $dr_total=0;
                                $cr_total=0;
                            ?>
					    @foreach($ledgers_data as $key=>$ledger)

                       <tr>
                           <td style="width: 4%;text-align: center">{{$key+1}}.</td>
                           <td>{{$ledger['code']}}</td>
                           <td><a href="/admin/accounts/reports/ledger_statement?startdate={{Request::get('start_date')}}&enddate={{Request::get('end_date')}}&ledger_id={{$ledger['id']}}&currency={{\Request::get('currency')}}&fiscal_year={{\Request::get('fiscal_year')}}" target="_blank">
                                   {{$ledger['name']}}</a></td>
                           <th>{{$ledger['op_dc']=='D'?'DR':'CR'}} {{number_format($ledger['op_balance'],2)}}</th>
                           <th>{{number_format($ledger['dr_amount'],2)}}</th>
                           <th>{{number_format($ledger['cr_amount'],2)}}</th>
                           <th>{{$ledger['closing_dc']=='D'?'DR':'CR'}} {{number_format($ledger['closing_balance'],2)}}</th>
                       </tr>
                            <?php
                            $dr_total+=$ledger['dr_amount'];
                            $cr_total+=$ledger['cr_amount'];
                            ?>


					    @endforeach

						<tr style="background: #d9edf7">
							<td colspan="3" style="text-align: right;"><b>Total</b></td>
                            <td></td>

                            <td style="font-size: 14px;font-weight: 600;">
                                <b>DR {{ number_format($dr_total,2) }}</b>
                            </td>
                            <td style="font-size: 14px;font-weight: 600;">
                                <b>CR {{ number_format($cr_total,2) }}</b>
                            </td>
							<td></td>
						</tr>
                        @else
                    <tr>
                        <td colspan="9" style="font-weight: bold;color:grey;text-align: center">No Records Found..</td>
                    </tr>
                            @endif
					</tbody>
				</table>
{{--                <div style="text-align: center;"> {!! $entry_items?$entry_items->appends(\Request::except('page'))->render():'' !!} </div>--}}

{{--                <br>--}}

{{--				<a href="/admin/chartofaccounts/groupdetail/pdf/{{$groups_data->id}}" class="btn btn-primary">PDF</a>--}}
{{--				<a href="/admin/chartofaccounts/groupdetail/excel/{{$groups_data->id}}" class="btn btn-primary">Excel</a>--}}
{{--          	    <a href="/admin/chartofaccounts/groupdetail/print/{{$groups_data->id}}" class="btn btn-primary">Print</a>--}}

				 </div>
          </div>
      </div>

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

 <script type="text/javascript">
     $(document).ready(function() {
         $('.select2').select2();
     });
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
     $(document).on('change','#fiscal_year_id',function () {
         var fiscal_year = $(this).val()
         var fiscal_detail = ''
         var all_fiscal_years = {!! json_encode($allFiscalYear); !!}
         all_fiscal_years.forEach((item) => {
             if (item.fiscal_year == fiscal_year)
                 fiscal_detail = item
         });

         $('#ReportStartdate').val(fiscal_detail.start_date)
         $('#ReportEnddate').val(fiscal_detail.end_date)

     })
</script>


@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')


@endsection
