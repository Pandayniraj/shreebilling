<?php //dd($entryitem->employee_id); ?>
@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset("/bower_components/intl-tel-input/build/css/intlTelInput.css") }}" rel="stylesheet" type="text/css" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script src="{{ asset("/bower_components/intl-tel-input/build/js/intlTelInput-jquery.min.js") }}"></script>


<script type="text/javascript">

    function populateLedgerWithValue(value,tr_id)
    {
        //extract the value form ledger dropdown create select and add value

        let virtual_ledger = $('#ledger_account').clone();

        virtual_ledger.attr('class','form-control line-ledger-select');

        virtual_ledger.val(value);

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
                    if (data) {

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
            var  cur_ledger_id	= $('.ledger-dropdown').val();
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
                                populateLedgerWithValue(cur_ledger_id,id);
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


        $(document).on('change','.dr_cr_toggle',function(){

            let parent = $(this).parent().parent();

            let v = $(this).val();

            if(v == 'D')
            {
                parent.find('.cr_row').html('-');
                parent.find('.dr_row').html(`<input type="number" name="dr_amount[]" step="any" value="" class="form-control dr-item line-amounts input-sm">`);
            }else{

                parent.find('.dr_row').html('-');
                parent.find('.cr_row').html(`<input type="number" name="dr_amount[]" step="any" value="" class="form-control cr-item line-amounts input-sm">`);
            }
            dc_diff();
            return;


        });



            dc_diff();






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



<style type="text/css">

    option:disabled{
        color: black !important;
        font-weight: bold;
    }
    .deleterow,.recalculate{
        cursor: pointer;
    }
    .ledger-td .select2-container--default{
        width: 220px!important;
    }
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: transparent !important;
    opacity: 1;
}
.input-sm {
    border-radius: 6px !important;
    box-shadow: none;
    border-color: #d2d6de;
    height: 33px !important;
}
.input-group .form-control {
    position: absolute !important;
}
span.select2.select2-container.select2-container--default{
    width: 100% !important;
}
.select2-container .select2-selection--single {
    height: 33px !important;
}
.form-control {
    border-radius: 6px !important;
}
.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #d2d6de !important;
    border-radius: 6px;
}
.btn-default {
    background-color: transparent !important;
    color: #444;
    border-color: transparent !important;
}
.bg-success {
    background-color: #f3f2f7 !important;
    border-top: 2px solid #ebe9f1 !important;

}
table.table.table-stripped.table-hover.table-bordered thead th {
    border-bottom: 2px solid #ebe9f1;
    border-left: solid 2px #FFF;
}
.box {
    border-radius: 15px;
    box-shadow: rgb(0 0 0 / 10%) 0px 10px 50px;
}
.box {
    border-top: none !important;
}
.box-header {
    padding-left: 12px !important;
}
td#dr-total {
    font-weight: bold !important;
}
td#cr-total {
    font-weight: bold !important;
}
.panel-body {
    padding: 0px;
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
$type=\App\Models\Entrytype::where('label',Request::segment(4))->first();
$latest_entry_number=\TaskHelper::generateId($type);
// ?>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Edit Salary Voucher
                <small>{!! $page_description ?? "Page description" !!}


                </small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.accounts')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class=""><strong>{{ ucfirst(Request::segment(4)) }}</strong> Salary Voucher  #{{ request()->segment(5)}}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<div class="entry add form">
				<form action="/admin/salary-voucher/update/{{$entries->id}}" method="post" accept-charset="utf-8"
                      onsubmit="return checkCreditDebit(this);" enctype="multipart/form-data">
					{{@csrf_field()}}
                <div class="row">
                 	<div class="col-md-2">
                 		<div class="form-group">
                 			<label for="number">Number</label>
                 			<input type="text" name="number" readonly value="{{$entries->number}}" id="number" class="form-control input-sm" tabindex="1">
                        </div>
                    </div>
                    <?php
                         $type=\App\Models\Entrytype::where('label',Request::segment(4))->first();
                    ?>
                    <input type="hidden" name="entrytype_id" value="{{$type->id}}">
                    <div class="col-md-2">
                    	<div class="form-group">
                    		<label for="date">Date</label>
                    		<input type="text" name="date" value="{{$entries->date}}" id="EntryDate" class="form-control input-sm datepicker date-toggle" tabindex="2">
                         </div>
                    </div>
                    <div class="col-md-2">
                    	<div class="form-group">
	                    	<label for="tag_id">Tag</label>
	                    	<select name="tag_id" class="form-control input-sm" tabindex="3">
								    <option value="0">NONE</option>
									@foreach($tags as $tag)
									<option value="{{$tag->id}}" @if($tag->id == $entries->tag_id) Selected=Selected @endif>{{$tag->title}}</option>
									@endforeach
						    </select>
					    </div>
					</div>
					 {{-- <div class="col-md-2">
                    	<div class="form-group">
                    		<label for="bill_no">Bill Num.</label>
                    		<input type="text" name="bill_no" value="{{ $entries->billNum() }}" id="bill_no" class="form-control input-sm" placeholder="Bill Number.." tabindex="2">
                         </div>
                    </div> --}}
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="number">Payment Period</label>
                            <input type="text" name="payment_month" value="{{ $entries->payment_month }}" placeholder="" class="form-control input-sm" tabindex="1">
                       </div>
                   </div>
					 <div class="col-md-2">
                    	<div class="form-group">
	                    	<label for="tag_id">Type</label>
	                    	<select name="entrytype_id" class="form-control" tabindex="3">
								    <option value="0">NONE</option>
									@foreach($types as $type)
									<option value="{{$type->id}}" @if($type->id == $entries->entrytype_id) Selected=Selected @endif>{{$type->name}}</option>
									@endforeach
						    </select>
					    </div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label>Currency</label>

					                    <div class="input-group">
								<select class="form-control input-sm select2 currency"
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
				</div>
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
{{--                           <div class="col-xs-2">--}}
{{--                               <div class="input-group">--}}
{{--                                   <label for="" style="position:absolute;--}}
{{--  top:-10px;--}}
{{--  left:20px;--}}
{{--  z-index:100;--}}
{{--  background-color:white;">Cheque No.</label>--}}
{{--                                   <div class="form-group-entryitem">--}}
{{--                                       <input type="text" value="" class="form-control cheque_no" placeholder="Cheque No." name="cheque_no" tabindex="7">--}}
{{--                                   </div>--}}
{{--                               </div>--}}
{{--                           </div>--}}
{{--						<div  style="margin-right: 5px">--}}
{{--							<div class="input-group">--}}
{{--								<div class="form-group-entryitem">--}}
{{--									<input type="text" value="Being {{ Request::segment(4) }} made" class="narration form-control" placeholder="Narration" tabindex="7">--}}
{{--	                            </div>--}}
{{--	                            <div class="input-group-btn">--}}
{{--									<button type="button" id="addentry" tabindex="8" class="btn btn-primary" data-toggle="tooltip" title="Add Entry">--}}
{{--										Add + </span>--}}
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
{{--								    @if (isset($entriesitem) )--}}
{{--								     @foreach ($entriesitem as $row => $entryitem)--}}
{{--								      <tr>--}}
{{--											<td class="{{$entryitem->dc}}">@if($entryitem->dc=='D') Dr @else Cr @endif<input type="hidden" name="dc[]" value="{{$entryitem->dc}}"></td>--}}
{{--	                                        <td class="{{$entryitem->ledger_id}}" id="cur_ledger">[{{$entryitem->ledgerdetail->code}}] {{$entryitem->ledgerdetail->name}}<input type="hidden" name="ledger_id[]" value="{{$entryitem->ledger_id}}"></td>--}}
{{--	                                        @if($entryitem->dc == 'D')--}}
{{--	                                        <td>{{$entryitem->amount}}<input type="hidden" name="dr_amount[]" value="{{$entryitem->amount}}" class="dr-item"></td>--}}
{{--	                                        <td><strong>-</strong></td>--}}
{{--	                                        @else--}}
{{--	                                         <td><strong>-</strong></td>--}}
{{--	                                         <td>{{$entryitem->amount}}<input type="hidden" name="dr_amount[]" value="{{$entryitem->amount}}" class="cr-item"></td>--}}
{{--	                                        @endif--}}
{{--	                                        <td>{{$entryitem->cheque_no}}<input type="hidden" name="cheque_no[]" value="{{$entryitem->cheque_no}}"></td>--}}
{{--	                                        <td>{{$entryitem->narration}}<input type="hidden" name="narration[]" value="{{$entryitem->narration}}"></td>--}}
{{--	                                        <td class="ledger-balance">--}}
{{--	                                        	<div>{{\TaskHelper::getLedgerBalance($entryitem->ledger_id)}}</div><input type="hidden" name="ledger_balance[]" value="{{\TaskHelper::getLedgerBalance($entryitem->ledger_id)}}">--}}
{{--	                                        </td>--}}
{{--	                                        <td><span class="deleterow " escape="false"><i class="fa fa-trash deletable"></i></span></td>--}}
{{--                                      </tr>--}}
{{--                                      @endforeach--}}
{{--                                      @else--}}
{{--									   <tr class="empty">--}}
{{--									     <td colspan="7" style="text-align:center">No data available in table</td>--}}
{{--									   </tr>--}}
{{--									  @endif--}}
{{--									  </tbody>--}}
{{--									<tbody>--}}
{{--										<tr class="bold-text">--}}
{{--											<td>Total</td>--}}
{{--											<td></td>--}}
{{--											<td id="dr-total" style="background-color: rgb(255, 255, 153);">{{$entries->dr_total}}</td><input type="hidden" name="dr_total" id="dram" value="{{$entries->dr_total}}">--}}
{{--											<td id="cr-total" style="background-color: rgb(255, 255, 153);">{{$entries->cr_total}}</td><input type="hidden" name="cr_total"  id="cram" value="{{$entries->cr_total}}">--}}
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
{{--                                        @if($entries->entry_difference!=0)--}}
{{--                                    <tr>--}}
{{--                                        <td colspan="2">Entry Difference</td>--}}
{{--                                        <td colspan="3">{{$entries->entry_difference<0?'Cr ':'Dr '}}{{abs($entries->entry_difference)}}</td>--}}
{{--                                    </tr>--}}
{{--                                            @endif--}}
{{--									</tbody>--}}
{{--								</table>--}}
{{--							</div>--}}
{{--				    </div>--}}
{{--				</div>--}}
                    <div>

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
                                                {{ CategoryTree() }}
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

                                <div class="col-xs-2">
                                    <div class="form-group-entryitem">

                                        <div class="input-group">
                                            <span class="input-group-addon currency_name">{{ $selected_currency }}</span>
                                            <input type="text" value="" class="amount form-control input-sm" placeholder="Amount" disabled="disabled" tabindex="6">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-5">
                                    <div class="input-group">
                                        <div class="form-group-entryitem">
                                            <input type="text" value="" class="narration form-control input-sm" placeholder="Narration" tabindex="7">
                                        </div>
                                        <div class="input-group-btn">
                                            <button type="button" id="addentry" tabindex="8" class="btn btn-primary" data-toggle="tooltip" title="Add Entry">
                                                Add + </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-stripped table-responsive table-hover table-bordered">
                                    <thead>
                                    <tr class="bg-success">
                                        <th style="width: 7%">Dr/Cr</th>
                                        <th style="width: 15%">Ledger</th>
                                        <th style="width: 15%; white-space: nowrap;">Dr Amount</th>
                                        <th style="width: 15%; white-space: nowrap;">Cr Amount</th>
                                        <th style="width: 12%; white-space: nowrap;" >Cheque No.</th>
                                        <th style="width: 11%">Employee</th>
                                        {{-- <th style="width: 7%; white-space: nowrap;">Assign To</th> --}}
                                        <th style="width: 12%">Narration</th>
                                        <th style="width: 10%">Balance</th>
                                        <th style="width: 3%"><i class="fa fa-cog"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody id="entryitems">
                                    @if (isset($entriesitem) )
                                        @foreach ($entriesitem as $row => $entryitem)
                                            <tr id='{{rand(1,1000).time()}}' class="edit-legders">
                                                <td class="{{$entryitem->dc}}">

                                                    <select name="dc[]" class="form-control dr_cr_toggle">
                                                        <option value="D" >Dr</option>
                                                        <option value="C" @if($entryitem->dc=='C') selected="" @endif>Cr</option>
                                                    </select>


                                                </td>
                                                <td class="{{$entryitem->ledger_id}} ledger-td" id="cur_ledger">[{{$entryitem->ledgerdetail->code}}] {{$entryitem->ledgerdetail->name}}<input type="hidden" name="ledger_id[]" value="{{$entryitem->ledger_id}}" class="selected_ledger_id_line"></td>
                                                @if($entryitem->dc == 'D')
                                                    <td class="dr_row"><input type="number" step="any" name="dr_amount[]" value="{{$entryitem->amount}}" class="form-control dr-item line-amounts input-sm" ></td>
                                                    <td class="cr_row"><strong>-</strong></td>
                                                @else
                                                    <td class="dr_row"><strong>-</strong></td>
                                                    <td class="cr_row"><input type="text" name="dr_amount[]" value="{{$entryitem->amount}}"  class="form-control cr-item line-amounts input-sm"></td>
                                                @endif
                                                <td><input type="text" name="cheque_no[]" value="{{$entryitem->cheque_no}}" class="form-control input-sm"></td>

                                                <td>
                                                    <select class="form-control" name="employee_id[]">
                                                        <option value="">Select Employee</option>

                                                        @foreach( $users as $user)
                                                            <option value="{{ $user->id }}" {{ ($entryitem->employee_id == $user->id)? 'selected' : '' }}>{{ $user->first_name.' '.$user->last_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td><input type="text" name="narration[]" value="{{$entryitem->narration}}" class="form-control input-sm"></td>
                                                <td class="ledger-balance">
                                                    <div style="white-space: nowrap;">{{\TaskHelper::getLedgerBalance($entryitem->ledger_id)}}</div><input type="hidden" name="ledger_balance[]" value="{{\TaskHelper::getLedgerBalance($entryitem->ledger_id)['ledger_balance']}}">
                                                </td>
                                                <td><span class="deleterow " escape="false"><i class="fa fa-trash deletable"></i></span></td>
                                            </tr>
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
                                        <td id="dr-total" style="background-color: rgb(255, 255, 153);">{{$entries->dr_total}}</td><input type="hidden" name="dr_total" id="dram" value="{{$entries->dr_total}}">
                                        <td id="cr-total" style="background-color: rgb(255, 255, 153);">{{$entries->cr_total}}</td><input type="hidden" name="cr_total"  id="cram" value="{{$entries->cr_total}}">
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
                                        <td><button type="button" id="addentry" tabindex="8" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Add Entry">
                                                <i class="fa fa-plus"></i> Add Entry
                                            </button></td>
                                    </tr>
                                    @if($entries->entry_difference!=0)
                                        <tr>
                                            <td colspan="2">Entry Difference</td>
                                            <td colspan="3">{{$entries->entry_difference<0?'Cr ':'Dr '}}{{abs($entries->entry_difference)}}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: -13px;">
                        <label for="voucher_image">Voucher Document</label>
                        <div class="row">
                            <div class="col-md-10" style="padding-bottom:5px;">
                                <input type="file" id="voucher_image" class="btn btn-default" name="photo" data-parsley-trigger="change">
                                @if($entries->image)
                                    <a href="{{asset($entries->image)}}" target="_blank" style="margin-left: 15px;margin-top: 8px">{{$entries->image}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
					<br>
					<div class="form-group" style="margin-top: -27px;">
						<label for="note">Note</label>
						<textarea name="notes" class="form-control" tabindex="9" rows="2">{{$entries->notes}}</textarea>
					</div>
					<div class="form-group" style="float: right;">
						<input type="submit" value="Submit Edit" class="btn btn-success pull-rignt">
                        <span class="link-pad"></span>
                        <a href="/admin/entries" class="btn btn-default">Cancel</a>
                        <a></a>
                    </div>
                </form>
                <a></a>
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

<script type="text/javascript">
    $('.date-toggle').nepalidatetoggle()
    $(document).on('hidden.bs.modal', '#modal_dialog' , function(e){
        $('#modal_dialog .modal-content').html('');
    });

    function handleModalResults(result){
        var options = $(result.data).html();
        console.log(options);
        $('#ledger_account').html(options);

        $('#ledger_account').val(result.lastcreated.id);
        $('#modal_dialog').modal('hide');

        if ($('.ledger-dropdown').val() == "") {
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
    }

    $('#entry_currency').change(function(){

        $('.currency_name').text($(this).val());

    });


</script>
<script >
    $(document).ready(function() {
        $('.select2').select2();

        $('.edit-legders').each(function () {

            let id = $(this).attr('id');
            let value = $(this).find('.selected_ledger_id_line').val();
            console.log(id, value);
            populateLedgerWithValue(value, id);
        });
    })
</script>
@endsection
