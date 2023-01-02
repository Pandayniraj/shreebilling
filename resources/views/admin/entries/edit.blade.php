@extends('layouts.master')
@section('content')

    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet"
          type="text/css"/>

    <link href="{{ asset("/bower_components/intl-tel-input/build/css/intlTelInput.css") }}" rel="stylesheet"
          type="text/css"/>

    <script src="{{ asset("/bower_components/intl-tel-input/build/js/intlTelInput-jquery.min.js") }}"></script>

    <script type="text/javascript">

        function populateLedgerWithValue(value, tr_id) {
            //extract the value form ledger dropdown create select and add value

            let virtual_ledger = $('#ledger_account').clone();

            virtual_ledger.attr('class', 'form-control line-ledger-select');

            virtual_ledger.val(value);

            virtual_ledger.attr('name', 'ledger_id[]');
            $(`#${tr_id}`).find("#cur_ledger").html('<div style="display:flex;align-items: baseline;"></div>');

            $(`#${tr_id}`).find("#cur_ledger div").html(virtual_ledger);
            $(`#${tr_id}`).find("#cur_ledger div").append('<span class="affect-stock"></span>');

            $(`#${tr_id}`).find("#cur_ledger select").select2({width: '93%'});

            if ($(`#${tr_id}`).find("#cur_ledger").data('stock')==1){
                $(`#${tr_id}`).find('.affect-stock').html(`<span class="add-product-btn" data-toggle="modal" data-target="#product_modal" >
					   <i class="fa fa-plus-square text-success" style="margin-left: 4px;font-size: 20px;cursor:pointer;" data-toggle="tooltip" data-placement="top" title="Add Products"></i></a>
					</span>`);
            }

        }
        $(document).on('click', '.add-product-btn', function () {
            let ledger_name=$(this).parent().parent().parent().parent().find('option:selected:eq(1)').text();
            let tr_id=$(this).parent().parent().parent().parent().attr('id');
            let current_ledger_id=$(this).parent().parent().parent().find('.line-ledger-select').val();
            let product_listings=[];
            $(".multipleDiv").nextAll('tr').remove()
            $($(this).parent().parent().parent().parent().find('table tr').get().reverse()).each(function (i) {
                $(".multipleDiv").after($('#productFields #more-tr').html());
                $(".multipleDiv").next('tr').find('select').select2({ width: '100%' },{'text-align':'left'});
                $(".multipleDiv").next('tr').find('select').val($(this).data('id')).change()
                $(".multipleDiv").next('tr').find('.product_amount').val($(this).data('amount'))
            })
            // setTimeout(()=>{
            // 	calcTotal()
            // },100)
            populateModal(tr_id,ledger_name,current_ledger_id)
        });
        function populateModal(tr_id,name){
            $('#modal_ledger_id').val(tr_id)
            $('.modal-title').html('').append('Select Products for '+name.trim())
            calcTotal()
        }

        function dc_diff() {
            var drTotal = 0;

            $(".dr-item").each(function () {
                var curDr = $(this).prop('value');
                curDr = parseFloat(curDr);
                if (isNaN(curDr))
                    curDr = 0;
                drTotal = jsFloatOps(drTotal, curDr, '+');
            });
            $("#dr-total").text(drTotal);
            $("#dram").val(drTotal);


            var crTotal = 0;
            $(".cr-item").each(function () {
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
            } else {
                $(".dc-dropdown").val('D');
            }
        }

        var jsFloatOps = function (param1, param2, op) {


            param1 = param1 * 1000;
            param2 = param2 * 1000;

            param1 = param1.toFixed(0);
            param2 = param2.toFixed(0);
            param1 = Math.floor(param1);
            param2 = Math.floor(param2);
            var result = 0;
            if (op == '+') {
                result = param1 + param2;
                result = result / 1000;
                return result;
            }
            if (op == '-') {
                result = param1 - param2;
                result = result / 1000;
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

        $(document).ready(function () {

            /* javascript floating point operations */


            $(function () {
                $('.datepicker').datetimepicker({
                    //inline: true,
                    format: 'YYYY-MM-DD',
                    sideBySide: true,
                    allowInputToggle: true
                });

            });

            /* Ledger dropdown changed */
            $(document).on('change', '.ledger-dropdown', function (e) {
                if ($(this).val() == "") {
                    /* Reset and diable dr and cr amount */
                    $('.amount').prop('value', "");
                    $('.amount').prop('disabled', 'disabled');
                } else {
                    /* Enable dr and cr amount and trigger Dr/Cr change */
                    $('.amount').prop('disabled', '');
                    setTimeout(function () {
                        $(".amount").focus();
                    }, 500);
                }
            });

            /* Delete ledger row */
            $(document).on('click', '.deleterow', function () {
                $(this).parent().parent().remove();
                var tbody = $("#entryitems");
                if (tbody.children().length == 0) {
                    tbody.html("<tr class='empty'><td colspan='7' style='text-align:center'>No data available in table</td></tr>");
                }
                /* Recalculate Total */
                dc_diff();
            });

            /* Recalculate Total */
            $(document).on('click', '.recalculate', function () {
                /* Recalculate Total */
                dc_diff();
            });

            /* Calculating Dr and Cr total */


            $(document).on('keyup', '.line-amounts', function () {

                dc_diff();
            });


            $(document).on('change', '.line-ledger-select', function () {

                let cur_ledger_id = $(this).val();

                var parent = $(this).parent().parent();


                $.post("/admin/entries/ajaxcl",
                    {
                        cur_ledger_id: cur_ledger_id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    function (data) {
                        if (data) {

                            let data1 = JSON.parse(data);
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


                            if (data1.cl.affect_stock == 1) {
                                let ledger_name=data1.cl.name

                                parent.find('.affect-stock').html(`<span class="add-product-btn" data-toggle="modal" data-target="#product_modal" >
					   <i class="fa fa-plus-square text-success" style="margin-left: 4px;font-size: 20px;cursor:pointer;" data-toggle="tooltip" data-placement="top" title="Add Products"></i></a>
					</span>`);
                                parent.parent().find('select:eq(0)').val('D').attr('disabled','true').change()

                            }
                            else{
                                parent.find('.affect-stock').html('')
                                parent.parent().find('select:eq(0)').removeAttr('disabled')
                            }
                        } else {
                            ledger_balance = '-';
                        }
                        parent.parent().find('table').remove()

                        parent.find('.ledger-balance').html(`

						<div>${ledger_balance}</div> <input type="hidden" name="ledger_balance[]" value="${ledger_balance}">
					`);
                    });


            });

            $(document).on('click', '#addentry', function () {

                var dc_option_val = $('.dc-dropdown').val();
                var cur_ledger_id = $('.ledger-dropdown').val();
                var amount = $('.amount').val();
                var narration = $('.narration').val() || 'Being {{ Request::segment(4) }} made';
                var ledger_option = $.trim($('.ledger-dropdown').find(":selected").text());
                var dc_option = $('.dc-dropdown').find(":selected").text();


                $.post("/admin/entries/ajaxcl", {
                        cur_ledger_id: cur_ledger_id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    function (data) {

                        ledger_balance = '-';


                        $.post("/admin/entries/ajaxaddentry", {
                                dc_option_val: dc_option_val,
                                cur_ledger_id: cur_ledger_id,
                                amount: amount,
                                narration: narration,
                                ledger_option: ledger_option,
                                dc_option: dc_option,
                                ledger_balance: ledger_balance,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            function (data) {
                                if (data) {
                                    let id = $(data).attr('id');

                                    var tbody = $("#entryitems");
                                    if (tbody.children().hasClass('empty')) {
                                        tbody.empty();
                                    }
                                    if (tbody.children().hasClass('danger')) {
                                        if (tbody.children().length > 0) {
                                            $('.danger').remove();
                                        } else {
                                            tbody.empty();
                                        }
                                    }
                                    tbody.append(data);
                                    populateLedgerWithValue(cur_ledger_id, id);
                                    dc_diff();
                                    if (!tbody.children().hasClass('danger')) {
                                        if ($('#cr-diff').text()) {
                                            $(".dc-dropdown").val('C');
                                        } else {
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


            $(document).on('change', '.dr_cr_toggle', function () {

                let parent = $(this).parent().parent();

                let v = $(this).val();

                if (v == 'D') {
                    parent.find('.cr_row').html('-');
                    parent.find('.dr_row').html(`<input type="number" name="dr_amount[]" step="any" value="" class="form-control dr-item line-amounts input-sm">`);
                } else {

                    parent.find('.dr_row').html('-');
                    parent.find('.cr_row').html(`<input type="number" name="dr_amount[]" step="any" value="" class="form-control cr-item line-amounts input-sm">`);
                }
                dc_diff();
                return;


            });


            $('.narration').keypress(function (e) {
                var key = e.which;
                if (key == 13) {
                    $('#addentry').click();
                    return false;
                }
            });

            $('.amount').keypress(function (e) {
                var key = e.which;
                if (key == 13) {
                    $('.narration').focus();
                    return false;
                }
            });

            $('.dc-dropdown').keypress(function (e) {
                var key = e.which;
                if (key == 13) {
                    $('#ledger-dropdown').focus();
                    return false;
                }
            });

        });


    </script>



    <style type="text/css">

        option:disabled {
            color: black !important;
            font-weight: bold;
        }

        .deleterow, .recalculate {
            cursor: pointer;
        }
    </style>
    <script type="text/javascript">

        function checkCreditDebit(theform) {

            if (document.getElementById("dram").value != document.getElementById("cram").value) {
                alert('Credit and Debit didn\'t match!');
                return false;
            } else {
                $('.dr_cr_toggle').removeAttr('disabled')
                return true;
            }
        }

    </script>



    <?php
    function CategoryTree($parent_id = null, $sub_mark = '')
    {

        $groups = \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id', $parent_id)->get();

        if (count($groups) > 0) {
            foreach ($groups as $group) {
                echo '<optgroup label="' . $sub_mark . '[' . $group->code . ']' . ' ' . $group->name . '">';

                $ledgers = \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id', $group->id)->get();
                if (count($ledgers) > 0) {
                    $submark = $sub_mark;
                    $sub_mark = $sub_mark . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                    foreach ($ledgers as $ledger) {

                        echo '<option value="' . $ledger->id . '"><strong>' . $sub_mark . '[' . $ledger->code . ']' . ' ' . $ledger->name . '</strong></option>';

                    }
                    $sub_mark = $submark;

                }
                echo ' </optgroup>';
                CategoryTree($group->id, $sub_mark . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
            }
        }
    }

    ?>

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            Edit Voucher Entry
            <small>{!! $page_description ?? "Page description" !!}
                | Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong>

            </small>
        </h1>


        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
    </section>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Edit <strong>{{ ucfirst(Request::segment(4)) }}</strong> Entry</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="entry add form">
                    <form action="/admin/entries/update/{{$entries->id}}" method="post" accept-charset="utf-8"
                          enctype="multipart/form-data" onsubmit="return checkCreditDebit(this);">
                        {{@csrf_field()}}
                        <div class="row">
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="number">Number</label>
                                    <input type="text" name="number" value="{{$entries->number}}" id="number"
                                           class="form-control input-sm" tabindex="1">
                                </div>
                            </div>
                            <?php
                            $type = \App\Models\Entrytype::where('label', Request::segment(4))->first();
                            ?>
                            <input type="hidden" name="entrytype_id" value="{{$type->id}}">
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="text" name="date" value="{{$entries->date}}" id="EntryDate"
                                           class="form-control input-sm datepicker date-toggle" tabindex="2">
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="tag_id">Tag</label>
                                    <select name="tag_id" class="form-control input-sm" tabindex="3">
                                        <option value="0">NONE</option>
                                        @foreach($tags as $tag)
                                            <option value="{{$tag->id}}"
                                                    @if($tag->id == $entries->tag_id) Selected=Selected @endif>{{$tag->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="bill_no">Bill Num.</label>
                                    <input type="text" name="bill_no" value="{{ $entries->billNum() }}" id="bill_no"
                                           class="form-control input-sm" placeholder="Bill Number.." tabindex="2">
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="tag_id">Type</label>
                                    <select name="entrytype_id" class="form-control input-sm" tabindex="3">
                                        <option value="0">NONE</option>
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}"
                                                    @if($type->id == $entries->entrytype_id) Selected=Selected @endif>{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Currency</label>

                                    <div class="input-group">
                                        <select class="form-control input-sm  currency"
                                                name="currency" required="required" id='entry_currency'>

                                            @foreach($currency as $k => $v)
                                                <option value="{{ $v->currency_code }}"
                                                        @if(isset($v->currency_code) && $v->currency_code == $selected_currency) selected="selected"@endif>
                                                    {{ $v->name }} {{ $v->currency_code }} ({{ $v->currency_symbol }})
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>
                        </div>
                        <br>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Basic Field</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row" style="display: none;">
                                    <div class="col-xs-1">
                                        <div class="form-group-entryitem">
                                            <select class="dc-dropdown form-control input-sm" tabindex="4"
                                                    style="width: 70px;">
                                                <option value="D" selected="selected">Dr</option>
                                                <option value="C">Cr</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="input-group">
                                            <div class="form-group-entryitem" id="ledger-dropdown" tabindex="5"
                                                 data-toggle="popover" data-trigger="focus" title="Required!"
                                                 data-content="Please Select a Ledger." data-container="body">
                                                <select class="ledger-dropdown form-control select2 " tabindex="-1"
                                                        aria-hidden="true"
                                                        id='ledger_account'>
                                                    <option value="">Select</option>
                                                    {{ CategoryTree() }}
                                                </select>
                                            </div>
                                            <div class="input-group-btn" style="outline: 0;border: none;">
                                                <a href="/admin/chartofaccounts/create/ledgers"
                                                   class="btn btn-info btn-xs"
                                                   data-target="#modal_dialog" data-toggle="modal"
                                                   style="margin-left: 2px; "
                                                >
                                                    <i class="fa fa-plus" data-toggle="tooltip" title="Add Ledger"></i>
                                                </a>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-xs-2">
                                        <div class="form-group-entryitem">

                                            <div class="input-group">
                                                <span class="input-group-addon currency_name">{{ $selected_currency }}</span>
                                                <input type="text" value="" class="amount form-control input-sm"
                                                       placeholder="Amount" disabled="disabled" tabindex="6">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <div class="input-group">
                                            <div class="form-group-entryitem">
                                                <input type="text" value="" class="narration form-control input-sm"
                                                       placeholder="Narration" tabindex="7">
                                            </div>
                                            <div class="input-group-btn">
                                                <button type="button" id="addentry" tabindex="8" class="btn btn-primary"
                                                        data-toggle="tooltip" title="Add Entry">
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
                                        <tr class="bg-info">
                                            <th class="col-md-1" style="width: 5%;text-align: center">Dr/Cr</th>
                                            <th style="width: 33%;">Ledger</th>
                                            <th class="col-md-1" style="width: 12%;">Dr Amount</th>
                                            <th class="col-md-1" style="width: 12%;">Cr Amount</th>
                                            <th style="width: 22%;">Narration</th>
                                            <th style="width: 13%;">Current Balance</th>
                                            <th style="width: 3%;"><i class="fa fa-cog"></i></th>
                                        </tr>
                                        </thead>
                                        {{-- {{dd($entries)}} --}}
                                        <tbody id="entryitems">
                                        @if (isset($entriesitem) )
                                        {{-- {{dd($entriesitem)}} --}}
                                            @foreach ($entriesitem as $row => $entryitem)
                                                <tr id='{{rand(1,1000).time()}}' class="edit-legders">
                                                    <td class="{{$entryitem->dc}}">

                                                        <select name="dc[]" class="form-control dr_cr_toggle">
                                                            <option value="D">Dr</option>
                                                            <option value="C"
                                                                    @if($entryitem->dc=='C') selected="" @endif>Cr
                                                            </option>
                                                        </select>


                                                    </td>
                                                    <td class="{{$entryitem->ledger_id}}" id="cur_ledger" data-stock="{{$entryitem->ledgerdetail->affect_stock}}">
                                                        [{{$entryitem->ledgerdetail->code}}
                                                        ] {{$entryitem->ledgerdetail->name}}<input type="hidden"
                                                                                                   name="ledger_id[]"
                                                                                                   value="{{$entryitem->ledger_id}}"
                                                                                                   class="selected_ledger_id_line">
                                                    </td>
                                                    @if($entryitem->dc == 'D')
                                                        <td class="dr_row"><input type="number" step="any"
                                                                                  name="dr_amount[]"
                                                                                  value="{{$entryitem->amount}}"
                                                                                  class="form-control dr-item line-amounts input-sm">
                                                        </td>
                                                        <td class="cr_row"><strong>-</strong></td>
                                                    @else
                                                        <td class="dr_row"><strong>-</strong></td>
                                                        <td class="cr_row"><input type="text" name="dr_amount[]"
                                                                                  value="{{$entryitem->amount}}"
                                                                                  class="form-control cr-item line-amounts input-sm">
                                                        </td>
                                                    @endif
                                                    <td><input type="text" name="narration[]"
                                                               value="{{$entryitem->narration}}"
                                                               class="form-control input-sm"></td>
{{--                                                    <td class="ledger-balance">--}}
{{--                                                        <div>{{\TaskHelper::getLedgerBalance($entryitem->ledger_id)['ledger_balance']}}</div>--}}
{{--                                                        <input type="hidden" name="ledger_balance[]"--}}
{{--                                                               value="{{\TaskHelper::getLedgerBalance($entryitem->ledger_id)['ledger_balance']}}">--}}
{{--                                                    </td>--}}
                                                    <td><span class="deleterow " escape="false"><i
                                                                    class="fa fa-trash deletable"></i></span></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="empty">
                                                <td colspan="7" style="text-align:center">No data available in table
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                        <tbody>
                                        <tr class="bold-text">
                                            <td>Total</td>
                                            <td></td>
                                            <td id="dr-total"
                                                style="background-color: rgb(255, 255, 153);">{{$entries->dr_total}}</td>
                                            <input type="hidden" name="dr_total" id="dram"
                                                   value="{{$entries->dr_total}}">
                                            <td id="cr-total"
                                                style="background-color: rgb(255, 255, 153);">{{$entries->cr_total}}</td>
                                            <input type="hidden" name="cr_total" id="cram"
                                                   value="{{$entries->cr_total}}">
                                            <td></td>
                                            <td></td>
                                            <td><span class="recalculate" escape="false"><i
                                                            class="glyphicon glyphicon-refresh"></i></span></td>
                                        </tr>
                                        <tr class="bold-text">
                                            <td>Difference</td>
                                            <td></td>
                                            <td id="dr-diff">-</td>
                                            <td id="cr-diff"></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <button type="button" id="addentry" tabindex="8"
                                                        class="btn btn-primary btn-sm" data-toggle="tooltip"
                                                        title="Add Entry">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="voucher_image">Voucher Image</label>
                            <div class="row">
                                <div class="col-md-10" style="padding-bottom:5px;">
                                    <input type="file" id="voucher_image" class="btn btn-default" name="photo"
                                           data-parsley-trigger="change">
                                    @if($entries->image)
                                        <img src="{{asset($entries->image)}}" id="display_logo"
                                             style="height:150px;width:250px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea name="notes" class="form-control input-sm" tabindex="9"
                                      rows="2">{{$entries->notes}}</textarea>
                        </div>
                        <div class="form-group">
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
    @include('admin.entries.products-modal')

@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet"/>
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
    @include('partials._date-toggle')
    <script type="text/javascript">
        $('.date-toggle').nepalidatetoggle()
        $(document).on('hidden.bs.modal', '#modal_dialog', function (e) {
            $('#modal_dialog .modal-content').html('');
        });

        function handleModalResults(result) {
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
                setTimeout(function () {
                    $(".amount").focus();
                }, 500);
            }
        }

        $('#entry_currency').change(function () {

            $('.currency_name').text($(this).val());

        });


    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2();

            $('.edit-legders').each(function () {

                let id = $(this).attr('id');
                let value = $(this).find('.selected_ledger_id_line').val();
                console.log(id, value);
                populateLedgerWithValue(value, id);
            });

            var entry_items = {!! json_encode($entriesitem); !!}
                $('.edit-legders').each(function () {
                    var cur_ledger_id=$(this).find('#cur_ledger select :selected').val()
               var cur_item= entry_items.find(x=>x.ledger_id==cur_ledger_id)
                var tr_id=$(this).attr('id')
                $(this).append('<input type="hidden" name="unique_ledger_id[]" value="'+tr_id+'">')
                var product_div='<table class="product-list" style="width: 100%;">'
                var tr_list=''
                if(cur_item&&cur_item.item_detail.length){
                    $(this).find('select:eq(0)').val('D').attr('disabled','true')

                    cur_item.item_detail.forEach((item)=>{
                        tr_list=tr_list+'<tr data-id="'+item.product_id+'" data-amount="'+item.amount+'"><th style="width: 70%;overflow: hidden;text-overflow: ellipsis;max-width:240px;white-space: nowrap">'+
                            item.product.name+
                            '<input type="hidden" name="product_id['+tr_id+'][]" value="'+item.product_id+'" />'+'</th>' +
                            '<th style="text-align: right">'+'Rs.'+Number(item.amount).toFixed(2)+'<input type="hidden" name="product_amount['+tr_id+'][]" value="'+Number(item.amount)+'"/></th></tr>'

                    })
                    $(this).find("#cur_ledger").append(product_div+tr_list+'</table>');

                }
            })
        });
    </script>
    <script>
        $(document).on('click', '.remove-this', function () {
            $(this).parent().parent().parent().remove();
            calcTotal();
        });
        $(document).on('input', '.product_amount', function () {
            calcTotal();
        });
        // $(document).on('change','.product_id', function () {
        // 	calcTotal();
        // });
        // $(document).on('change', '#modal_ledger_id', function () {
        //     // calcTotal();
        //     var tr_id=$('#modal_ledger_id').val();
        //     // debugger
        //     input_field_values[tr_id].forEach(function () {
        //         // debugger
        //     })
        // });

        $("#addMore").on("click", function () {

            $(".multipleDiv").after($('#productFields #more-tr').html());
            $(".multipleDiv").next('tr').find('select').select2({ width: '100%' },{ 'text-align':'left' });
            $(".multipleDiv").next('tr').find('select').select2();
            calcTotal();
        });
        $(document).ready(function () {
            // $('.product_id').select2();

            // $(".multipleDiv").after($('#productFields #more-tr').html());
            // $(".multipleDiv").next('tr').find('select').select2({ width: '100%' },{'text-align':'left'});
        })
        function calcTotal(){
            var subTotal=0;
            $(".product_amount").each(function() {
                if ($(this).val()!='')
                    subTotal = Number(subTotal) + Number($(this).val());
            });
            $('#product-total').html('NRs.'+subTotal.toFixed(2));
            $('#product-total').val(subTotal);
            var tr_val=$('#modal_ledger_id').val()

            $(`#${tr_val}`).find("#cur_ledger").find('table').remove()

            var product_div='<table class="product-list" style="width: 100%;">'
            var tr_list=''
            $(".product_id").each(function(i) {
                if ($(this).find('option:selected').val()!=''&&i!=0){
                    var amount=parseFloat($(this).parent().parent().find('.product_amount').val());
                    tr_list=tr_list+'<tr data-id="'+$(this).find('option:selected').val()+'" data-amount="'+amount+'"><th style="width: 70%;overflow: hidden;text-overflow: ellipsis;max-width:240px;white-space: nowrap">'+$(this).find('option:selected').text()+
                        '<input type="hidden" name="product_id['+tr_val+'][]" value="'+$(this).find('option:selected').val()+'" />'+'</th>' +
                        '<th style="text-align: right">'+'Rs.'+Number(amount).toFixed(2)+'<input type="hidden" name="product_amount['+tr_val+'][]" value="'+Number(amount)+'"/></th></tr>'
                }
                // product_div=product_div+'</div>'
            });
            $(`#${tr_val}`).find("#cur_ledger").append(product_div+tr_list+'</table>');
            if ($(".product_amount").length)
            $(`#${tr_val}`).find(".line-amounts").val(subTotal);
            dc_diff();

            // subTotal = Number(subTotal) + Number($(this).val());

        }
        $('#product-ok').on('click',function () {
            calcTotal()
        })
    </script>
@endsection
