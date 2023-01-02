@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset("/bower_components/intl-tel-input/build/css/intlTelInput.css") }}" rel="stylesheet" type="text/css" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script src="{{ asset("/bower_components/intl-tel-input/build/js/intlTelInput-jquery.min.js") }}"></script>
<style>
    .ledger-td .select2-container--default {
        width: 235px !important;
    }
    .box {
    border-radius: 15px;
    box-shadow: rgb(0 0 0 / 10%) 0px 10px 50px;
}
.box {
    border-top: none !important;
}
.bg-success {
    background-color: #f3f2f7 !important;
    border-top: 2px solid #ebe9f1 !important;

}
table.table.table-stripped.table-hover.table-bordered thead th {
    border-bottom: 2px solid #ebe9f1;
    border-left: solid 2px #FFF;
}
.form-control {
    border-radius: 6px !important;
}
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: transparent !important;
    opacity: 1;
}
.select2-container .select2-selection--single {

    height: 33px !important;
}
.select2-container--default .select2-selection--single {
    border: 1px solid #d2d6de !important;
    border-radius: 6px !important;
}
.input-sm {
    height: 33px !important;
}
td#dr-total {
    font-weight: bold !important;
}
td#cr-total {
    font-weight: bold !important;
}

</style>
<script type="text/javascript">

    function populateLedgerWithValue(value,tr_id,dc_option_val)
    {
        //extract the value form ledger dropdown create select and add value

        let virtual_ledger = $('#ledger_account').clone();
        // let virtual_ledger='''<select name="ledger_id[]" class="line-ledger-select ledger-dropdown form-control select2 " aria-hidden="true" id="ledger_account"><option value="">Select</option></select>''';
        // console.log(virtual_ledger);

        virtual_ledger.attr('class','form-control line-ledger-select');
        // virtual_ledger.val(value);
        $.get( "/admin/coa_ledgers_list_salaryvoucher?op="+dc_option_val, function( data ) {
            virtual_ledger.empty();
            data.forEach(function(datum) {
                optText = datum.name;
                optValue = datum.id;
                virtual_ledger.append(new Option(optText, optValue));
            });
        });

        virtual_ledger.attr('name','ledger_id[]');

        $(`#${tr_id}`).find("#cur_ledger").html(virtual_ledger);

        $(`#${tr_id}`).find("#cur_ledger select").select2({ width: '100%' });


    }

    $(document).ready(function() {

        /* javascript floating point operations */
        var jsFloatOps = function(param1, param2, op) {


            param1 = param1 * 1000;
            param2 = param2 * 1000;

            param1 = param1.toFixed(0);
            param2 = param2.toFixed(0);
            param1 = Math.floor(param1);
            param2 = Math.floor(param2);
            var result = 0;
            if (op == '+') {
                result = param1 + param2;
                result = result/1000;
                return result;
            }
            if (op == '-') {
                result = param1 - param2;
                result = result/1000;
                return result;
            }
            if (op == '!=') {
                if (param1 != param2)
                    return true;
                else
                    return false;
            }
            if (op == '==') {
                if (param1 == param2)
                    return true;
                else
                    return false;
            }
            if (op == '>') {
                if (param1 > param2)
                    return true;
                else
                    return false;
            }
            if (op == '<') {
                if (param1 < param2)
                    return true;
                else
                    return false;
            }
        }


        $(function() {
            $('.datepicker').datetimepicker({
                //inline: true,
                format: 'YYYY-MM-DD',
                sideBySide: true,
                allowInputToggle: true
            });

        });

        /* Ledger dropdown changed */
        $(document).on('change', '.ledger-dropdown', function(e) {
            if ($(this).val() == "") {
                /* Reset and diable dr and cr amount */
                $('.amount').prop('value', "");
                $('.amount').prop('disabled', 'disabled');
            } else {
                /* Enable dr and cr amount and trigger Dr/Cr change */
                $('.amount').prop('disabled', '');
                setTimeout(function() {
                    $(".amount").focus();
                }, 500);
            }
        });

        /* Delete ledger row */
        $(document).on('click', '.deleterow', function() {
            $(this).parent().parent().remove();
            var tbody = $("#entryitems");
            if (tbody.children().length == 0) {
                tbody.html("<tr class='empty'><td colspan='7' style='text-align:center'>No data available in table</td></tr>");
            }
            /* Recalculate Total */
            dc_diff();
        });

        /* Recalculate Total */
        $(document).on('click', '.recalculate', function() {
            /* Recalculate Total */
            dc_diff();
        });

        /* Calculating Dr and Cr total */
        function dc_diff() {
            var drTotal = 0;

            $(".dr-item").each(function() {
                var curDr = $(this).prop('value');
                curDr = parseFloat(curDr);
                if (isNaN(curDr))
                    curDr = 0;
                drTotal = jsFloatOps(drTotal, curDr, '+');
            });
            $("#dr-total").text(drTotal);
            $("#dram").val(drTotal);

            var crTotal = 0;
            $(".cr-item").each(function() {
                var curCr = $(this).prop('value');
                curCr = parseFloat(curCr);
                if (isNaN(curCr))
                    curCr = 0;
                crTotal = jsFloatOps(crTotal, curCr, '+');
            });
            $("#cr-total").text(crTotal);
            $("#cram").val(crTotal);

            if (jsFloatOps(drTotal, crTotal, '==')) {
                $("#dr-total").css("background-color", "#FFFF99");
                $("#cr-total").css("background-color", "#FFFF99");
                $("#dr-diff").text("-");
                $("#cr-diff").text("");
            } else {
                $("#dr-total").css("background-color", "#FFE9E8");
                $("#cr-total").css("background-color", "#FFE9E8");
                if (jsFloatOps(drTotal, crTotal, '>')) {
                    $("#dr-diff").text("");
                    $("#cr-diff").text(jsFloatOps(drTotal, crTotal, '-'));
                } else {
                    $("#dr-diff").text(jsFloatOps(crTotal, drTotal, '-'));
                    $("#cr-diff").text("");
                }
            }

            if ($('#cr-diff').text()) {
                $(".dc-dropdown").val('C');
            }else{
                $(".dc-dropdown").val('D');
            }
        }

        $(document).on('keyup','.line-amounts',function(){

            dc_diff();
        });


        $(document).on('change','.line-ledger-select',function(){

            let cur_ledger_id = $(this).val();

            var parent = $(this).parent().parent();


            $.post("/admin/entries/ajaxcl",
                {cur_ledger_id: cur_ledger_id,
                    _token: $('meta[name="csrf-token"]').attr('content')},
                function(data){
                    if (data&&JSON.parse(data)) {

                        let data1=JSON.parse(data);
                        console.log(data1);
                        var ledger_bal = parseFloat(data1.cl.amount);

                        var prefix = '';
                        var suffix = '';
                        if (data1.cl.status == 'neg') {
                            prefix = '<span class="error-text">';
                            suffix = '</span>';
                        }
                        if (data1.cl.dc == 'D') {
                            var ledger_balance = prefix + "Dr " + ledger_bal + suffix;
                        } else if (data1.cl.dc == 'C') {
                            ledger_balance = prefix + "Cr " + ledger_bal + suffix;
                        } else {
                            ledger_balance = '-';
                        }

                    }else {
                        ledger_balance= '-';
                    }

                    parent.find('.ledger-balance').html(`

						<div>${ledger_balance}</div> <input type="hidden" name="ledger_balance[]" value="${ledger_balance}">
					`);
                });


        });

        $(document).on('click', '#addentry', function() {

            var dc_option_val	= $('.dc-dropdown').val();

            var cur_ledger_id	= $('.ledger-dropdown').val();
            var  amount		    = $('.amount').val();
            var narration	    = $('.narration').val() || 'Being {{ Request::segment(4) }} made';
            var ledger_option 	= $.trim($('.ledger-dropdown').find(":selected").text());
            var dc_option    	= $('.dc-dropdown').find(":selected").text();





            $.post("/admin/entries/ajaxcl",{cur_ledger_id: cur_ledger_id,_token: $('meta[name="csrf-token"]').attr('content')},
                function(data){

                    ledger_balance= '-';


                    $.post("/admin/entries/ajaxaddsalaryvoucher",{dc_option_val: dc_option_val, cur_ledger_id: cur_ledger_id,amount: amount,narration: narration,ledger_option: ledger_option,dc_option: dc_option,ledger_balance:ledger_balance,_token: $('meta[name="csrf-token"]').attr('content')},
                        function(data){
                            if (data) {
                                let id = $(data).attr('id');

                                var tbody = $("#entryitems");
                                if (tbody.children().hasClass('empty')) {
                                    tbody.empty();
                                }
                                if (tbody.children().hasClass('danger')) {
                                    if (tbody.children().length > 0) {
                                        $('.danger').remove();
                                    }else{
                                        tbody.empty();
                                    }
                                }
                                tbody.append(data);
                                // console.log(data);
                                populateLedgerWithValue(cur_ledger_id,id,dc_option_val);
                                dc_diff();
                                if (!tbody.children().hasClass('danger')) {
                                    if ($('#cr-diff').text()) {
                                        $(".dc-dropdown").val('C');
                                    }else{
                                        $(".dc-dropdown").val('D');
                                    }
                                    $('.ledger-dropdown').val(0).trigger('change.select2');
                                    $('.amount').prop('value', "");
                                    $('.amount').prop('disabled', 'disabled');
                                    $(".narration").val('');
                                }
                                $(".dc-dropdown").focus();
                            }
                        });

                });

        });

        for(let i = 0;i<2;i++)
        {
            if(i == 0){

                $('.dc-dropdown').val('D');
            }else{
                $('.dc-dropdown').val('C');
            }

            $('#addentry').trigger('click');

        }
        $(document).on('change','.dr_cr_toggle',function(){

            let parent = $(this).parent().parent();

            let v = $(this).val();
            let virtual_ledger = parent.find('.line-ledger-select').first();

            if(v == 'D')
            {

                parent.find('.cr_row').html('-');
                parent.find('.dr_row').html(`<input type="number" name="dr_amount[]" step="any" value="" class="form-control dr-item line-amounts input-sm">`);
            }else{

                parent.find('.dr_row').html('-');
                parent.find('.cr_row').html(`<input type="number" name="dr_amount[]" step="any" value="" class="form-control cr-item line-amounts input-sm">`);
            }
            $.get( "/admin/coa_ledgers_list_salaryvoucher?op="+v, function( data ) {
                    virtual_ledger.empty();
                    data.forEach(function(datum) {
                        optText = datum.name;
                        optValue = datum.id;
                        virtual_ledger.append(new Option(optText, optValue));
                    });
                });
            dc_diff();

            return;


        });



        $('.narration').keypress(function (e) {
            var key = e.which;
            if(key == 13){
                $('#addentry').click();
                return false;
            }
        });

        $('.amount').keypress(function (e) {
            var key = e.which;
            if(key == 13){
                $('.narration').focus();
                return false;
            }
        });

        $('.dc-dropdown').keypress(function (e) {
            var key = e.which;
            if(key == 13){
                $('#ledger-dropdown').focus();
                return false;
            }
        });

    });



</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<style type="text/css">

    option:disabled{
        color: black !important;
        font-weight: bold;
    }
    .deleterow,.recalculate{
        cursor: pointer;
    }
</style>
<script type="text/javascript">

    function checkCreditDebit(theform) {

        if (document.getElementById("dram").value != document.getElementById("cram").value)
        {
            alert('Credit and Debit didn\'t match!');
            return false;
        } else {
            return true;
        }
    }

</script>
<?php
function CategoryTree($parent_id=null,$sub_mark=''){

    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)
        ->where('org_id', auth()->user()->org_id)
        ->get();

    if(count($groups)>0){
        foreach($groups as $group){
            echo '<optgroup label="'.$sub_mark.'['.$group->code.']'.' '.$group->name.'">';

            $ledgers= \App\Models\COALedgers::with('group')->orderBy('code', 'asc')->where('group_id',$group->id)->where('org_id', auth()->user()->org_id)->get();
            if(count($ledgers)>0){
                $submark= $sub_mark;
                $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;";

                foreach($ledgers as $ledger){

                    echo '<option value="'.$ledger->id.'"><strong>'.$sub_mark.'['.(strlen($ledger->group->name)<=8?$ledger->group->name:substr($ledger->group->name,0,8).'..').']'.
                        $ledger->name.'['.$ledger->code.']'.'</strong></option>';

                }
                $sub_mark=$submark;

            }
            echo '</optgroup>';
            CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;");
        }
    }
}

?>

<?php
//function CategoryTree($parent_id=null,$sub_mark=''){
//
//    $groups= \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id',$parent_id)->get();
//
//
//
//    if(count($groups)>0){
//    	foreach($groups as $group){
//    		echo '<option value="'.$group->id.'" disabled><strong>'.$sub_mark.'['.strlen($ledger->group->name)<=8?$ledger->group->name:substr($ledger->group->name,1,8).'..'.']'.'['.$group->code.']'.' '.$group->name.'</strong></option>';
//
//
//
//            $ledgers= \App\Models\COALedgers::with('group')->orderBy('code', 'asc')->where('group_id',$group->id)->get();
//	        if(count($ledgers>0)){
//	            $submark= $sub_mark;
//	            $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;";
//
//	             foreach($ledgers as $ledger){
//
//	             echo '<option value="'.$ledger->id.'"><strong>'.$sub_mark.'['.strlen($ledger->group->name)<=8?$ledger->group->name:substr($ledger->group->name,1,8).'..'.']'.'['.$ledger->code.']'.' '.$ledger->name.'</strong></option>';
//
//	           }
//	           $sub_mark=$submark;
//
//	        }
//    		CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;");
//    	}
//    }
// }
//
?>
    <?php
        $type=\App\Models\Entrytype::where('label','salary')->first();
$latest_entry_number=\TaskHelper::generateId($type);
// ?>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Salary Voucher Entry
                <small>{!! $page_description ?? "Page description" !!}
                	| Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong>

                </small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.accounts')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h2 class="box-title" style="font-size: 20px;">Add <strong>{{ ucfirst(Request::segment(4)) }}</strong> Entry</h2>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
				<div class="entry add form">
				<form action="/admin/salary-voucher/store" method="post"
                      enctype="multipart/form-data" accept-charset="utf-8" onsubmit="return checkCreditDebit(this);">
					{{@csrf_field()}}
                <div class="row">
                 	<div class="col-xs-2">
                 		<div class="form-group">
                 			<label for="number">Number</label>
                 			<input type="text" name="number" readonly value="{{$latest_entry_number}}" placeholder="invoice id or ref number" id="number" beforeinput="" afterinput="" class="form-control input-sm" tabindex="1">
                        </div>
                    </div>

                    <input type="hidden" name="entrytype_id" value="{{$type->id}}">
{{--                    <div class="col-xs-2">--}}
{{--                    	<div class="form-group">--}}
{{--                    		<label for="date">Fiscal Year</label>--}}
{{--                                {!! Form::select('fiscal_year',$allFiscalYear,$fiscal_year,['id'=>'fiscal_year_id', 'class'=>'form-control input-sm select2', 'style'=>'width:150px; display:inline-block;'])  !!}--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="col-xs-2">
                    	<div class="form-group">
                    		<label for="date">Date*</label>
                    		<input type="text" name="date" value="{{\Carbon\Carbon::now()->toDateString()}}" id="EntryDate" class="form-control datepicker date-toggle" tabindex="2" required>
                         </div>
                    </div>
                     {{-- <div class="col-xs-2">
                    	<div class="form-group">
                    		<label for="bill_no">Bill Num.</label>
                    		<input type="text" name="bill_no" value="" id="bill_no" class="form-control" placeholder="Bill Number.." tabindex="2">
                         </div>
                    </div> --}}
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="number">Payment Month</label>
                            <input type="text" name="payment_month" placeholder="" class="form-control input-sm" tabindex="1">
                       </div>
                   </div>
                    <div class="col-xs-2">
                    	<div class="form-group">
	                    	<label for="tag_id">Tag<span style="color: red">*</span></label>
	                    	<select name="tag_id" class="form-control" tabindex="3" required>
								    <option value="">NONE</option>
									@foreach($tags as $tag)
									<option value="{{$tag->id}}" {{ ($tag->id == 1)? 'selected' : '' }}>{{$tag->title}}</option>
									@endforeach
						    </select>
					    </div>
					</div>
						<div class="col-md-2">
									<div class="form-group">
										<label>Currency</label>

					                    <div class="input-group">
								<select class="form-control select2 currency"
								name="currency" required="required" id='entry_currency'>

                                    @foreach($currency as $k => $v)
                                    <option value="{{ $v->currency_code }}" @if(isset($v->currency_code) && $v->currency_code == $selected_currency) selected="selected"@endif>
                                        {{ $v->name }} {{ $v->currency_code }} ({{ $v->currency_symbol }})</option>
                                    @endforeach

                                </select>

					                    </div>
					                    <!-- /.input group -->
					                </div>
					                <!-- /.form group -->
								</div>
				</div><br>
{{--				<div class="panel panel-primary">--}}
{{--				   <div class="panel-heading">--}}
{{--						<h3 class="panel-title"></h3>--}}
{{--					</div>--}}
{{--					<div class="panel-body">--}}
{{--					   <div class="row">--}}
{{--						<div class="col-xs-1">--}}
{{--							<div class="form-group-entryitem">--}}
{{--								<select class="dc-dropdown form-control" tabindex="4" style="width: 70px;">--}}
{{--									<option value="D" selected="selected">Dr</option>--}}
{{--									<option value="C">Cr</option>--}}
{{--								</select>--}}
{{--							</div>--}}
{{--						</div>--}}

{{--				    	<div class="col-xs-4">--}}
{{--				    	<div class="input-group">--}}
{{--							<div class="form-group-entryitem" id="ledger-dropdown" tabindex="5" data-toggle="popover" data-trigger="focus" title="Required!" data-content="Please Select a Ledger." data-container="body">--}}
{{--								<select  class="ledger-dropdown form-control select2" aria-hidden="true"--}}
{{--								id='ledger_account'>--}}
{{--								    <option value="">Select</option>--}}
{{--									{{ CategoryTree() }}--}}
{{--								</select>--}}
{{--						    </div>--}}
{{--						  <div class="input-group-btn" style="outline: 0;border: none;">--}}
{{--	                        <a href="/admin/chartofaccounts/create/ledgers" class="btn btn-danger btn-xs"--}}
{{--	                        data-target="#modal_dialog"  data-toggle="modal"--}}
{{--	                         style="margin-left: 2px; border-radius: 50%"--}}
{{--	                        >--}}
{{--	                        	<i class="fa fa-plus"  data-toggle="tooltip" title="Add Ledger"></i>--}}
{{--	                        </a>--}}
{{--                        </div>--}}
{{--						</div>--}}

{{--						</div>--}}

{{--					    <div class="col-xs-2">--}}
{{--							<div class="form-group-entryitem">--}}

{{--								<div class="input-group">--}}
{{--                                <span class="input-group-addon currency_name">{{ $selected_currency }}</span>--}}
{{--                                 <input type="text" value="" class="amount form-control" placeholder="Amount" disabled="disabled" tabindex="6">--}}

{{--                                  </div>--}}
{{--	                        </div>--}}
{{--                        </div>--}}
{{--						<div class="col-xs-2">--}}
{{--							<div class="input-group">--}}
{{--                                <label for="" style="position:absolute;--}}
{{--  top:-10px;--}}
{{--  left:20px;--}}
{{--  z-index:100;--}}
{{--  background-color:white;">Cheque No.</label>--}}
{{--								<div class="form-group-entryitem">--}}
{{--									<input type="text" value="" class="form-control cheque_no" name="cheque_no" tabindex="7">--}}
{{--	                            </div>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--                           <div style="margin-right: 5px">--}}
{{--							<div class="input-group">--}}
{{--								<div class="form-group-entryitem">--}}
{{--									<input type="text" value="Being {{ Request::segment(4) }} made" class="narration form-control" placeholder="Narration" tabindex="7">--}}
{{--	                            </div>--}}
{{--	                            <div class="input-group-btn">--}}
{{--									<button type="button" id="addentry" tabindex="8" class="btn btn-primary" data-toggle="tooltip" title="Add Entry">--}}
{{--										Add +--}}
{{--									</button>--}}
{{--								</div>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--				        </div>--}}
{{--							<br>--}}
{{--							<div class="table-responsive">--}}
{{--								<table class="table table-stripped table-hover table-bordered">--}}
{{--									<thead>--}}
{{--										<tr>--}}
{{--											<th>Dr/Cr</th>--}}
{{--											<th>Ledger</th>--}}
{{--											<th>Dr Amount ({{ env(APP_CURRENCY)}})</th>--}}
{{--											<th>Cr Amount ({{ env(APP_CURRENCY)}})</</th>--}}
{{--											<th>Cheque No.</th>--}}
{{--											<th>Narration</th>--}}
{{--											<th>Current Balance ({{ env(APP_CURRENCY)}})</</th>--}}
{{--											<th>Actions</th>--}}
{{--										</tr>--}}
{{--									</thead>--}}


{{--								    <tbody id="entryitems">--}}
{{--								    @if (isset($curEntryitems) && !empty($curEntryitems) && sizeof($curEntryitems, 1) > 5 )--}}
{{--								     @foreach ($curEntryitems as $row => $entryitem)--}}
{{--								     <!--  <tr>--}}
{{--										<td class="D">Dr<input type="hidden" name="Entryitem[4960932511][dc]" value="D"></td>--}}
{{--                                        <td class="273" id="cur_ledger">[01-0002] sxsx<input type="hidden" name="Entryitem[4960932511][ledger_id]" value="273"></td>--}}
{{--                                        <td>500<input type="hidden" name="Entryitem" value="500" class="dr-item"></td>--}}
{{--                                        <td><strong>-</strong></td>--}}
{{--                                        <td>lcbncv<input type="hidden" name="Entryitem[4960932511][narration]" value="lcbncv"></td>--}}
{{--                                        <td class="ledger-balance">--}}
{{--                                        	<div>Dr 5568606</div><input type="hidden" name="Entryitem[4960932511][ledger_balance]" value="Dr 5568606">--}}
{{--                                        </td>--}}
{{--                                        <td><span class="deleterow " escape="false"><i class="glyphicon glyphicon-trash"></i></span></td>--}}
{{--                                      </tr>	 -->--}}
{{--                                      @endforeach--}}
{{--                                      @else--}}
{{--									  <tr class="empty">--}}
{{--									    <td colspan="7" style="text-align:center">No data available in table</td>--}}
{{--									  </tr>--}}
{{--									  @endif--}}
{{--									  </tbody>--}}
{{--									<tbody>--}}
{{--										<tr class="bold-text">--}}
{{--											<td>Total</td>--}}
{{--											<td></td>--}}
{{--											<td id="dr-total" style="background-color: rgb(255, 255, 153);">0</td><input type="hidden" name="dr_total" id="dram" value="0" >--}}
{{--											<td id="cr-total" style="background-color: rgb(255, 255, 153);">0</td><input type="hidden" name="cr_total"  id="cram" value="0">--}}
{{--											<td></td>--}}
{{--											<td></td>--}}
{{--											<td><span class="recalculate" escape="false"><i class="glyphicon glyphicon-refresh"></i></span></td>--}}
{{--										</tr>--}}
{{--										<tr class="bold-text">--}}
{{--											<td>Difference</td>--}}
{{--											<td></td>--}}
{{--											<td id="dr-diff">-</td>--}}
{{--											<td id="cr-diff"></td>--}}
{{--											<td></td>--}}
{{--											<td></td>--}}
{{--											<td></td>--}}
{{--										</tr>--}}
{{--									</tbody>--}}
{{--								</table>--}}
{{--							</div>--}}
{{--				    </div>--}}
{{--				</div>--}}


                    <div class="panel panel-primary">
                        <div class="panel-heading" style="padding: 10px 10px 20px 10px">
                            <h3 class="panel-title">Basic Field

                                <a class = "btn btn-info" style="float: right; margin-left: 5px;" href="/admin/chartofaccounts/create/ledgers"  data-target="#modal_dialog"  data-toggle="modal">+ Add Ledger</a>
                                <a class = "btn btn-info" style="float: right" href="""  data-target="#staffModal"  data-toggle="modal">Add Staff</a>

                            </h3>
                        </div>

                       


                        <div class="panel-body">
                            <div class="row" style="display: none;">
                                <div class="col-xs-1">
                                    <div class="form-group-entryitem">
                                        <select class="dc-dropdown form-control input-sm" tabindex="4" style="width: 70px;">
                                            <option value="D" selected="selected">Dr</option>
                                            <option value="C">Cr</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="input-group">
                                        <div class="form-group-entryitem" id="ledger-dropdown" tabindex="5" data-toggle="popover" data-trigger="focus" title="Required!" data-content="Please Select a Ledger." data-container="body">
                                            <select  class="ledger-dropdown form-control select2 " aria-hidden="true"
                                                     id='ledger_account'>
                                                <option value="">Select</option>

                                                {{-- {{ CategoryTree() }} --}}
                                            </select>
                                        </div>
                                        <div class="input-group-btn" style="outline: 0;border: none;">
                                            <a href="/admin/chartofaccounts/create/ledgers" class="btn btn-info btn-xs"
                                               data-target="#modal_dialog"  data-toggle="modal"
                                               style="margin-left: 2px; "
                                            >
                                                <i class="fa fa-plus"  data-toggle="tooltip" title="Add Ledger"></i>
                                            </a>
                                        </div>
                                    </div>


                                </div>

                                <div class="col-xs-4 col-md-4">
                                    <div class="form-group-entryitem">

                                        <div class="input-group">
                                            <span class="input-group-addon currency_name">{{ $selected_currency }}</span>
                                            <input type="text" value="" class="amount form-control input-sm" placeholder="Amount" disabled="disabled" tabindex="6">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3 col-md-3">
                                    <div class="input-group">
                                        <div class="form-group-entryitem">
                                            <input type="text" value="Being {{ Request::segment(4) }} made" class="narration form-control input-sm" placeholder="Narration" tabindex="7">
                                        </div>
                                        <div class="input-group-btn">
                                            <button type="button" id="addentry" tabindex="8" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Add Entry">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-stripped table-hover table-bordered">
                                    <thead>
                                    <tr class="bg-success">
                                        <th style="width: 7%">Dr/Cr</th>
                                        <th style="width: 21%">Ledger</th>
                                        <th style="width: 13%">Dr Amount</th>
                                        <th style="width: 13%">Cr Amount</th>
                                        <th style="width: 8%;white-space: nowrap;">Cheque No.</th>
                                        <th style="width: 10%">Employee</th>
                                        {{-- <th style="width: 10%;white-space: nowrap;">Assign To</th> --}}
                                        <th style="width: 13%">Narration</th>
                                        <th style="width: 12%">Current Balance</th>
                                        <th style="width: 3%"><i class="fa fa-cog"></i></th>
                                    </tr>
                                    </thead>


                                    <tbody id="entryitems">
                                    @if (isset($curEntryitems) && !empty($curEntryitems) && sizeof($curEntryitems, 1) > 5 )
                                        @foreach ($curEntryitems as $row => $entryitem)
                                            <!--  <tr>
										<td class="D">Dr<input type="hidden" name="Entryitem[4960932511][dc]" value="D"></td>
                                        <td class="273" id="cur_ledger">[01-0002] sxsx<input type="hidden" name="Entryitem[4960932511][ledger_id]" value="273"></td>
                                        <td>500<input type="hidden" name="Entryitem" value="500" class="dr-item"></td>
                                        <td><strong>-</strong></td>
                                        <td>lcbncv<input type="hidden" name="Entryitem[4960932511][narration]" value="lcbncv"></td>
                                        <td class="ledger-balance">
                                        	<div>Dr 5568606</div><input type="hidden" name="Entryitem[4960932511][ledger_balance]" value="Dr 5568606">
                                        </td>
                                        <td><span class="deleterow " escape="false"><i class="glyphicon glyphicon-trash"></i></span></td>
                                      </tr>	 -->
                                        @endforeach
                                    @else
                                        <tr class="empty">
                                            <td colspan="8" style="text-align:center">No data available in table</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                    <tbody>
                                    <tr class="bold-text">
                                        <td>Total</td>
                                        <td></td>
                                        <td id="dr-total" style="background-color: rgb(255, 255, 153);" class="fontwt">0</td>
                                        <input type="hidden" name="dr_total" id="dram" value="0" >
                                        <td id="cr-total" style="background-color: rgb(255, 255, 153);">0</td>
                                        <input type="hidden" name="cr_total"  id="cram" value="0">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><span class="recalculate" escape="false"><i class="glyphicon glyphicon-refresh"></i></span></td>
                                    </tr>
                                    <tr class="bold-text">
                                        <td>Difference</td>
                                        <td></td>
                                        <td id="dr-diff">-</td>
                                        <td id="cr-diff"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <button type="button" id="addentry" tabindex="8" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Add Entry">
                                                <i class="fa fa-plus"></i> Add Entry
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group" style="margin-top: -26px !important;">
                        <label for="voucher_image">Voucher Document</label>
                        <div class="row">
                            <div class="col-md-10" style="padding-bottom:5px;">
                                <input type="file" id="voucher_image" class="" name="photo" data-parsley-trigger="change">
{{--                                <br>--}}
{{--                                <img src="{{asset('/images/capture.jpeg')}}" class="bg bg-default" id="display_logo" style="height:150px;width:250px;">--}}
                            </div>
                        </div>
                    </div>

					<br>
					<div class="form-group">
						<label for="note">Note</label>
						<textarea name="notes" class="form-control" tabindex="9" rows="3"></textarea>
					</div>
					<div class="form-group" style="float: right;">
						<input type="submit" value="Submit" class="btn btn-success pull-rignt">
                        <span class="link-pad"></span>
                        <a href="/admin/salary-voucher" class="btn btn-default">Cancel</a>
                        <a></a>
                    </div>
                </form>
                <a></a>
            </div>
            <div class="modal fade" id="staffModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">

                    <div class="modal-content">
                        <form method="POST" action="" id="createStaffForm">
                            <div class="modal-header">
                                <h5 class="modal-title">Create Staff</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>First Name <span style="color: rgb(200, 0, 0);">*</span></label>
                                            <input type="text" class="form-control" name="first_name" id="first_name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Last Name <span style="color: rgb(200, 0, 0);">*</span></label>
                                            <input type="text" class="form-control" name="last_name" id="last_name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input type="text" class="form-control" name="phone" id="phone">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Department <span style="color: rgb(200, 0, 0);">*</span></label>

                                            <select class="form-control" name="department_id" id="department_id" required>
                                                <option value="">Select Department</option>

                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->departments_id }}">{{ $department->deptname }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Pan Number</label>
                                            <input type="text" class="form-control" name="pan_number" id="pan_number">
                                        </div>
                                    </div>

                                </div>

                                {{-- <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Pan Number</label>
                                            <input type="text" class="form-control" name="pan_number" id="pan_number">
                                        </div>
                                    </div>
                                </div> --}}

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="saveStaff">Save changes</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
            <a>
            </a>
        </div>
        <a></a>
    </div>
    <a></a>
</div>

@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
@include('partials._date-toggle')
{{--<script type="text/javascript">--}}
{{--$('.date-toggle').nepalidatetoggle();--}}
{{--	$(document).on('hidden.bs.modal', '#modal_dialog' , function(e){--}}
{{--	        $('#modal_dialog .modal-content').html('');--}}
{{--	});--}}

{{--function handleModalResults(result){--}}
{{--  var options = $(result.data).html();--}}
{{--  $('#ledger_account').html(options);--}}

{{--  $('#ledger_account').val(result.lastcreated.id);--}}
{{--  $('#modal_dialog').modal('hide');--}}

{{--  if ($('.ledger-dropdown').val() == "") {--}}
{{--			/* Reset and diable dr and cr amount */--}}
{{--			$('.amount').prop('value', "");--}}
{{--			$('.amount').prop('disabled', 'disabled');--}}
{{--		} else {--}}
{{--			/* Enable dr and cr amount and trigger Dr/Cr change */--}}
{{--			$('.amount').prop('disabled', '');--}}
{{--			setTimeout(function() {--}}
{{--				$(".amount").focus();--}}
{{--			}, 500);--}}
{{--   }--}}
{{--}--}}

{{--$('#entry_currency').change(function(){--}}

{{--	$('.currency_name').text($(this).val());--}}

{{--});--}}

{{--</script>--}}

<script type="text/javascript">




    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                // $('#display_logo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#voucher_image").change(function () {
        readURL(this);
    });
</script>

<script type="text/javascript">
    $('.date-toggle').nepalidatetoggle();
    $(document).on('hidden.bs.modal', '#modal_dialog' , function(e){
        $('#modal_dialog .modal-content').html('');
    });

    function handleModalResults(result) {
        var options = $(result.data).html();
        // $('.select2').select2('destroy');
        $('.line-ledger-select').each(function () {
            var value = $(this).val()
            $(this).html(options);
            if (value)
                $(this).val(value).change()

            // if ($('.ledger-dropdown').val() == "") {
            //   /* Reset and diable dr and cr amount */
            //   $('.amount').prop('value', "");
            //   $('.amount').prop('disabled', 'disabled');
            // } else {
            //   /* Enable dr and cr amount and trigger Dr/Cr change */
            //   $('.amount').prop('disabled', '');
            //   setTimeout(function() {
            // 	  $(".amount").focus();
            //   }, 500);
            // }

        })
        $('#modal_dialog').modal('hide');

    }

    $('#entry_currency').change(function(){

        $('.currency_name').text($(this).val());

    });

    $(document).ready(function(){

        var url = '{{ url('/') }}';

        $('#saveStaff').click(function(e){
            e.preventDefault();

            var firstName = $('#first_name').val();
            var lastName = $('#last_name').val();
            var departmentId = $('#department_id').val();
            var phone = $('#phone').val();
            var panNumber = $('#pan_number').val();
            var email = $('#email').val();
            var username = $('#username').val();
            var token = $('meta[name="csrf-token"]').attr('content');

            // $("#staffModal").validate({
            //     rules: {
            //         first_name: "required",
            //     },
            //     messages: {
            //         first_name: " Please enter your firstname",
            //     }
            // });

            var request = $.ajax({
                url: url + '/admin/staff',
                method: "POST",
                data: {
                    '_token': token,
                    'first_name' : firstName,
                    'last_name' : lastName,
                    'department_id' : departmentId,
                    'phone' : phone,
                    'pan_number' : panNumber,
                    'email': email,
                    'username': username,
                },
            });

            request.done(function( data ) {
                console.log(data);
                if (data.success){

                    $('select[name="employee_id[]"]').append('<option value="'+ data.data.id +'">'+ data.data.first_name +' '+ data.data.last_name +'</option');
                    $('#staffModal').modal('hide');

                    if (data.data != null){
                        $('#first_name').val('');
                        $('#last_name').val('');
                        $('#department_id').val('');
                        $('#phone').val('');
                        $('#pan_number').val('');
                        $('#email').val('');
                        $('#username').val('');
                    }
                }
                else{
                    alert(data.data);
                }
            });

            request.fail(function( jqXHR, textStatus ) {
                alert( "Request failed: " + textStatus );
            });

        });

    });


</script>
@endsection
