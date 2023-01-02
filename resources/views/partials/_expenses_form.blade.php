<?php
if (isset($showpage) && $showpage == true)
    $readonly = 'readonly';
else
    $readonly = ($expenses->isEditable())? '' : 'readonly';
?>

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<link rel="stylesheet" href="/nepali-date-picker/nepali-date-picker.min.css">
<script type="text/javascript" src="/nepali-date-picker/nepali-date-picker.js"> </script>


<div class="content">
    <div class="col-md-6">
        <div class="form-group">
              <label for="inputEmail3" class="col-sm-4 control-label">Date Type</label>
              <div class="col-sm-8">
              <select id="selectdatetype" name="datetype" class="form-control label-success">
                <option value="eng">English</option>
                <option value="nep">Nepali</option>
              </select>
          </div>
         </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
            Date
            </label><div class="col-sm-8" id='dateselectors'>
            {!! Form::text('date', $expenses->date??date('Y-m-d') , ['class' => 'form-control datepicker','placeholder'=>'Date', $readonly,'required'=>'required']) !!}
        </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Bill No
            </label><div class="col-sm-8">
                {!! Form::text('bill_no', $expenses->bill_no, ['class' => 'form-control','placeholder'=>'Supplie Bill #', $readonly]) !!}
            </div>
        </div>

{{--          <div class="form-group">--}}
{{--            <label for="inputEmail3" class="col-sm-4 control-label">--}}
{{--           Expense Type--}}
{{--            </label><div class="col-sm-8">--}}
{{--               --}}{{--  {!! Form::select('expense_type',array('expense'=>'Indirect Expenses','bill_payment'=>'Bill Payement','advance_payment'=>'Advance Payment','sales_return'=>'Sales Return'),$expenses->expense_type,['class'=>'form-control label-primary','id'=>'expense_type']) !!} --}}
{{--                 {!! Form::select('expense_type',array('expense'=>'Indirect Expenses','bill_payment'=>'Bill Payement','advance_payment'=>'Advance Payment','sales_return'=>'Sales Return'),$expenses->expense_type,['class'=>'form-control label-primary','id'=>'expense_type']) !!}--}}
        <input type="hidden" value="expense" name="expense_type" id='expense_type'>
{{--            </div>--}}
{{--        </div>--}}


         <div class="form-group">
                            <label for="priority" class="col-sm-4 control-label">
                            Select Tags <a href="#" data-target="#modal_dialog_tags"  data-toggle="modal" >[+]</a>
                            </label>
                            <div class="col-sm-8">
                            {!! Form::select('tag_id', [''=>"Select Tag"]+$tags,$expenses->tag_id, ['class' => 'form-control select2','id'=>'select_tag','required'=>'required']) !!}
                        </div>
                        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Supplier <a href="javascript::void()" onclick="openwindow()" >[+]</a>
            </label><div class="col-sm-8" id='customers_id'>
                {!! Form::select('vendor_id',$vendors, $expenses->vendor_id, ['class' => 'form-control searchable select2','required'=>'required','placeholder'=>'Select Supplier',$readonly]) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
            Expenses Account <a href="#" data-target="#modal_dialog" data-toggle="modal" onclick="window.create_type=window.expenses_type=''">[+]</a>
            </label>
            <div class="col-sm-8" >
                <select  class="form-control searchable select2 " name="expenses_account" required="" id='expenses_account'>

                </select>
            </div>
        </div>
        @include('admin.expenses.expense-modal')


        <div class="form-group">
            <label for="priority" class="col-sm-4 control-label">
                Select Currency
            </label>
            <div class="col-sm-8">
                {!! Form::select('currency_id',$currencies,$expenses->currency_id, ['class' => 'form-control select2','id'=>'select_currency','required'=>'required']) !!}
            </div>
        </div>
        <div class="form-group">

            <label for="inputEmail3" class="col-sm-4 control-label">
            Amount
            </label>

            <div class="col-sm-8">



        <div class="input-group">

                 {!! Form::number('amount', $expenses->amount, ['step' => 'any', 'min' => '0.01','class' => 'form-control amount','placeholder'=>'Amount', $readonly]) !!}

              </div>

               </div>

        </div>




        <div class="form-group">

            <label for="inputEmail3" class="col-sm-4 control-label">
            Tax Type
            </label>

            <div class="col-sm-8">



        <div class="input-group">
            <select class="form-control tax_rate_line" name="tax_type">
                <option value="0" {{$expenses->tax_type==0?'selected':''}}>Exempt(0)</option>
                <option value="13" {{$expenses->tax_type==13?'selected':''}}>VAT(13)</option>
            </select>
            <input type="hidden" name="tax_amount" class="tax_amount" value="{{$expenses->tax_amount??0}}">
              </div>

               </div>

        </div>
        <div class="form-group total_amount_div" style="display: none">

            <label for="inputEmail3" class="col-sm-4 control-label">
            Total Amount
            </label>

            <div class="col-sm-8">



                <div class="input-group">
                    <input name="total_amount" class="form-control total_amount" readonly value="0">
                </div>


               </div>

        </div>


        <div style="margin: 10px">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Payment

            </label>
            <input style="height: 18px;width: 18px" type="checkbox" name="make_payment" value="1" class="make_payment" {{($expenses->paid_through!=0||$expenses->paid_through!=null)?'checked':''}}>
            &nbsp;<span style="font-size: 20px;font-weight: 600">Mark as Paid</span>
            <div id="payment_info" style="display: none">
                <div class="row">
                    <div>
                        <?php
                        //Sunny_deptors
                        $cashgroups= \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',\FinanceHelper::get_ledger_id('CASH_EQUIVALENTS'))->where('org_id',\Auth::user()->org_id)->get();
                        ?>

                        <div class="form-group" style="margin-top: 10px">
                            <label for="inputEmail3" class="col-sm-4 control-label">
                                Paid Through <a href="javascript::void()" onclick="openpaidThoughwindow()" >[+]</a>
                            </label><div class="col-sm-8">

                                <select class = 'form-control searchable select2 ledger_select' name="paid_through" id="paid_through">
                                    <option value="">Select Ledger</option>
                                    {{ \FinanceHelper::ledgerGroupsOptionshtml(6,null,'cash_equivalence',$expenses->paid_through) }}
                                </select>
                                {{--             <select class = 'form-control searchable select2 ' name="paid_through" id="paid_through">--}}
                                {{--                   @foreach($cashgroups as $gps)--}}
                                {{--                         <option value="{{$gps->id}}" @if($expenses->paid_through == $gps->id) selected @endif>[{{$gps->code}}] {{$gps->name}}</option>--}}
                                {{--                   @endforeach--}}

                                {{--            </select>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>








         <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
           Narration
            </label><div class="col-sm-8">
           {!! Form::text('reference', $expenses->reference, ['class' => 'form-control','placeholder'=>'Narration', $readonly]) !!}
        </div>
        </div>

        @if(! (  isset($showpage) ? $showpage : '' ) || $expenses->attachment)
         <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
            Attachment
            </label><div class="col-sm-8">
          @if(!(  isset($showpage) ? $showpage : '' ))
            {!! Form::file('attachment', null, ['class' => 'form-control']) !!}
          @endif
            @if($expenses->attachment)
            <a href="{{ asset('attachment/'.$expenses->attachment)}}" target="_blank">{{$expenses->attachment}}</a>
            @endif
        </div>
        </div>
        @endif
    </div>
</div>
<div id='expense_account_options' style="display: none">



@foreach ($required_account as $key=>$value)
  <select class="form-control searchable"  id='{{ $key }}-id'>
    <option value="">Select Ledger</option>
    {{ \FinanceHelper::ledgerGroupsOptionshtml($value) }}
  </select>
@endforeach


</div>

    <script>
        $(document).ready(function () {
            calcTotal()
        })
        $(document).on('change','.tax_rate_line',function (){
            calcTotal()
        })
        $(document).on('input','.amount',function (){
            calcTotal()
        })
        function calcTotal(){
            var amount=Number($('.amount').val())
            var tax_type=$('.tax_rate_line').val()
            var tax_amount=0
            var total=amount
            if(tax_type==13){
                tax_amount=(13/100)*amount
                total=tax_amount+amount
                $('.total_amount_div').show()
            }
            else{
                $('.total_amount_div').hide()
            }
            $('.tax_amount').val(tax_amount.toFixed(2))
            $('.total_amount').val(total.toFixed(2))
        }
    </script>
<script>
    $(document).on('change','.make_payment',function (){
        if($(this).is(':checked')){
            $('#payment_info').show()
            $('.ledger_select').attr('required',true)
        }
        else{
            $('.ledger_select').removeAttr('required')
            $('#payment_info').hide()
        }
    })
    $(document).ready(function (){
        if($('.make_payment').is(':checked')){
            $('#payment_info').show()
            $('.ledger_select').attr('required',true)
        }
    })
</script>
