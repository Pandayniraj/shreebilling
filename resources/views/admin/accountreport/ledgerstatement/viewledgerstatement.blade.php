{{--@extends('layouts.master')--}}
{{--@section('content')--}}


{{--<?php--}}
{{-- function CategoryTree($parent_id=null,$sub_mark='',$ledgers_data){--}}
{{--    --}}
{{--    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->get();--}}

{{--    if(count($groups)>0){--}}
{{--    	foreach($groups as $group){--}}
{{--    		echo '<option value="'.$group->id.'" disabled><strong>'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</strong></option>';--}}

{{--    		$ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$group->id)->get(); --}}
{{--	          if(count($ledgers) > 0){--}}
{{--	            $submark= $sub_mark;--}}
{{--	            $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; --}}

{{--	             foreach($ledgers as $ledger){--}}

{{--	             if( $ledgers_data && $ledger && $ledgers_data->id == $ledger->id) {--}}
{{--	              echo '<option selected value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';--}}
{{--	              }else{--}}

{{--	           	  echo '<option value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';--}}
{{--	           }--}}

{{--	           }--}}
{{--	           $sub_mark=$submark;--}}

{{--	        }--}}
{{--    		CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$ledgers_data);--}}
{{--    	}--}}
{{--    }--}}
{{-- }--}}

{{-- ?>--}}


{{-- <?php--}}
{{--      $startOfYear = FinanceHelper::cur_fisc_yr()->start_date;--}}

{{--      $endOfYear   = FinanceHelper::cur_fisc_yr()->start_date;--}}
{{--  ?>--}}

{{--<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />--}}
{{--<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>--}}

{{--<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">--}}
{{--            <h1>--}}
{{--                {!! $page_title ?? "Page description" !!}--}}
{{--                <small>{!! $page_description ?? "Page description" !!}--}}
{{--                	| Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong>--}}

{{--                </small>--}}
{{--            </h1>--}}

{{--            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}--}}
{{--        </section>--}}

{{--<div class="col-xs-12">--}}
{{--          <div class="box">--}}
{{--            <div class="box-header with-border">--}}
{{--              <h3 class="box-title">Report - Ledger Statement--}}

{{--              	{!! Form::select('id', ['' => 'Select Financial Year'] + $fin_year, \Request::get('user_id'), ['id'=>'filter-user', 'class'=>'form-control', 'style'=>'width:170px; display:inline-block;']) !!}--}}

{{--              </h3>--}}
{{--            </div>--}}
{{--            <!-- /.box-header -->--}}
{{--            <div class="box-body">--}}
{{--					<!-- <div id="accordion"> -->--}}
{{--						<!-- <h3>Options</h3> -->--}}
{{--						<div class="balancesheet form">--}}
{{--							<form  method="GET" action="/admin/accounts/reports/ledger_statement">--}}
{{--								--}}
{{--							<div class="row">--}}
{{--								<div class="col-md-4">--}}
{{--									<div class="form-group">--}}
{{--									<label>Ledger Account</label>--}}
{{--										<select class="form-control customer_id select2" id="ReportLedgerId" name="ledger_id" aria-hidden="true">--}}
{{--											<option value="">Select</option>--}}
{{--											 {{ CategoryTree($parent_id=null,$sub_mark='',$ledgers_data ??'') }}		--}}
{{--										</select>--}}
{{--										--}}
{{--									</div>--}}
{{--								</div>--}}
{{--								<div class="col-md-2">--}}
{{--									<div class="form-group">--}}
{{--										<label>Start Date</label>--}}
{{--					                    <div class="input-group">--}}
{{--											<input id="ReportStartdate" type="text" name="startdate" class="form-control datepicker" value="{{old('startdate')}}" >--}}
{{--					                        <div class="input-group-addon">--}}
{{--					                            <i>--}}
{{--					                                <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave start date as empty if you want statement from the start of the financial year.">--}}
{{--					                                </div>--}}
{{--					                            </i>--}}
{{--					                        </div>--}}
{{--					                    </div>--}}
{{--					                    <!-- /.input group -->--}}
{{--					                </div>--}}
{{--					                <!-- /.form group -->--}}
{{--								</div>--}}
{{--								 <div class="col-md-2">--}}
{{--									<div class="form-group">--}}
{{--										<label>End Date</label>--}}

{{--					                    <div class="input-group">--}}
{{--											<input id="ReportEnddate" type="text" name="enddate" class="form-control datepicker" --}}
{{--											value="{{ old('enddate')}}">--}}
{{--					                        <div class="input-group-addon">--}}
{{--					                            <i>--}}
{{--					                                <div class="fa fa-info-circle" data-toggle="tooltip" title="Note : Leave end date as empty if you want statement till the end of the financial year.">--}}
{{--					                                </div>--}}
{{--					                            </i>--}}
{{--					                        </div>--}}
{{--					                    </div>--}}
{{--					                    <!-- /.input group -->--}}
{{--					                </div>--}}
{{--					                <!-- /.form group -->--}}
{{--								</div>--}}

{{--								 <div class="col-md-2">--}}
{{--									<div class="form-group">--}}
{{--										<label>Tags</label>--}}

{{--					                    <div class="input-group">--}}
{{--											{!! Form::select('id', ['' => 'Select Tags'] + $tags, \Request::get('user_id'), ['id'=>'filter-user', 'class'=>'form-control', 'style'=>'width:120px; display:inline-block;']) !!}--}}
{{--					                        --}}
{{--					                    </div>--}}
{{--					                    <!-- /.input group -->--}}
{{--					                </div>--}}
{{--					                <!-- /.form group -->--}}
{{--								</div>--}}

{{--								<div class="col-md-2">--}}
{{--									<div class="form-group">--}}
{{--										<label>Currency</label>--}}

{{--					                    <div class="input-group">--}}
{{--								<select class="form-control select2 currency" name="currency" required="required">--}}

{{--                                    @foreach($currency as $k => $v)--}}
{{--                                    <option value="{{ $v->currency_code }}" @if(isset($v->currency_code) && $v->currency_code == $v->currency_code) selected="selected" @endif>--}}
{{--                                        {{ $v->name }} {{ $v->currency_code }} ({{ $v->currency_symbol }})</option>--}}
{{--                                    @endforeach--}}

{{--                                </select>--}}
{{--					                        --}}
{{--					                    </div>--}}
{{--					                    <!-- /.input group -->--}}
{{--					                </div>--}}
{{--					                <!-- /.form group -->--}}
{{--								</div>--}}

{{--							</div>--}}
{{--							<div class="form-group">--}}

{{--								<input type="reset" name="reset" class="btn btn-primary pull-right" style="margin-left: 5px;" value="Clear">--}}
{{--								<input type="submit" class="btn btn-primary pull-right" value="Submit">--}}
{{--						    </div>--}}
{{--					        </form>--}}
{{--					    </div>--}}
{{--					<!-- </div> -->--}}
{{--				 </div>--}}
{{--          </div>--}}
{{--      </div>--}}


{{-- <script type="text/javascript">--}}
{{--    $(function() {--}}
{{--        $('.datepicker').datetimepicker({--}}
{{--          //inline: true,--}}
{{--          format: 'YYYY-MM-DD', --}}
{{--          sideBySide: true,--}}
{{--          allowInputToggle: true--}}
{{--        });--}}

{{--      });--}}
{{--</script>--}}

{{--<script type="text/javascript">--}}
{{--         $(document).ready(function() {--}}
{{--            $('.customer_id').select2();--}}
{{--        });--}}
{{--</script>--}}


{{--@endsection--}}

{{--<!-- Optional bottom section for modals etc... -->--}}
{{--@section('body_bottom') --}}


{{--@endsection--}}
@extends('layouts.master')
@section('content')


    <?php
    function CategoryTree($parent_id=null,$sub_mark='',$ledgers_data){

        $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('parent_id',$parent_id)->get();

        if(count($groups)>0){
            foreach($groups as $group){
                echo '<option value="'.$group->id.'" disabled><strong>'.$sub_mark.'['.$group->code.']'.' '.$group->name.'</strong></option>';

                $ledgers= \App\Models\COALedgers::with('group:id,name')->orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('group_id',$group->id)->get();
                if(count($ledgers)>0){
                    $submark= $sub_mark;
                    $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                    foreach($ledgers as $ledger){

                        if( ($ledgers_data->id ?? '') == $ledger->id){
                            echo '<option selected value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->group->name.']'.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
                        }else{

                            echo '<option value="'.$ledger->id.'"><strong>'.$sub_mark.'['.$ledger->group->name.']'.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
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
    $startOfYear = FinanceHelper::cur_fisc_yr()->start_date;

    $endOfYear   = FinanceHelper::cur_fisc_yr()->start_date;
    ?>

    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            {!! $page_title ?? "Page description" !!}
            <small>{!! $page_description ?? "Page description" !!}
                | Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong>

            </small>
        </h1>

        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
    </section>

    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Report - Ledger Statement

                    {{--  	{!! Form::select('id', ['' => 'Select Financial Year'] + $fin_year, \Request::get('user_id'), ['id'=>'filter-user', 'class'=>'form-control', 'style'=>'width:170px; display:inline-block;']) !!} --}}

                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- <div id="accordion"> -->
                <!-- <h3>Options</h3> -->
                <div class="balancesheet form">
                    <form  method="GET" action="/admin/accounts/reports/ledger_statement">

                        <div class="row" style="display: flex">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Ledger Account</label>
                                    <select class="form-control customer_id select2" required id="ReportLedgerId" name="ledger_id" aria-hidden="true">
                                        <option value="">Select</option>
                                        {{ CategoryTree($parent_id=null,$sub_mark='',
                                        isset($ledgers_data) ? $ledgers_data : []  ) }}
                                    </select>

                                </div>
                            </div>
                            <?php
                            $fiscal=\App\Models\Fiscalyear::where('fiscal_year',$fiscal_year)->first();
                            ?>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <div class="input-group">
                                        <input id="ReportStartdate" type="text" name="startdate" class="form-control datepicker" value="{{$fiscal->start_date}}" >
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
                                        <input id="ReportEnddate" type="text" name="enddate" class="form-control datepicker"
                                               value="{{$fiscal->end_date}}">
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

                            {{-- <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tags</label>

                                    <div class="input-group">
                                        {!! Form::select('id', ['' => 'Select Tags'] + $tags, \Request::get('user_id'), ['id'=>'filter-user', 'class'=>'form-control', 'style'=>'width:120px; display:inline-block;']) !!}

                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Currency</label>

                                    <div class="input-group">
                            <select class="form-control select2 currency" name="currency" required="required">

                                @foreach($currency as $k => $v)
                                <option value="{{ $v->currency_code }}" @if(isset($v->currency_code) && $v->currency_code == $v->currency_code) selected="selected" @endif>
                                    {{ $v->name }} {{ $v->currency_code }} ({{ $v->currency_symbol }})</option>
                                @endforeach

                            </select>

                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div> --}}
                            <div style="margin-left: 10px">
                                <label>
                                    <label></label>
                                </label>
                                <div class="form-group">
                                    {{--                            <input type="reset" name="reset" class="btn btn-danger pull-right" style="margin-left: 5px;" value="Clear">--}}
                                    <input type="submit" class="btn btn-primary" value="Submit">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- </div> -->
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


    <script>
        $(document).ready(function () {
            $('.select2').select2()
        })

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
            // $.ajax({
            //     type: "GET",
            //     contentType: "application/json; charset=utf-8",
            //     url: "/admin/get-fiscal-year?fiscal_year=" + fiscal_year,
            //     success: function (result) {
            //         debugger
            //         if (result.fiscal_year){
            //
            //         }
            //     }
            // })
        // })


    </script>


@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')


@endsection
