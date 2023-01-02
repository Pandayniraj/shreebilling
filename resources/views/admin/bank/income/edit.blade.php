
<div class="modal-content">
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Income Transaction
                <small>Edit  transcation #{{$income->id}}</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">
            <form action="{!! route('admin.bank.income.update',$income->id) !!}" method="post">
                {{ csrf_field() }}


                <div class="row">
                    <div class="col-md-6 col-sm-6 form-group">
                        <label class="control-label">Date type</label>
                        <div class="input-group col-sm-12">
                            <select id="selectdatetype" name="datetype" class="label-success">
                                <option value="eng">English</option>
                                <option value="nep">Nepali</option>
                        </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 form-group">
                        <label class="control-label">Tag</label>
                         <div class="input-group col-sm-12">
     {!! Form::select('tag_id',$tags,$income->tag_id,['class'=>'form-control searchable select2 ']) !!}
                        </div>
                    </div>

                </div>

            <div class="tab-content">
              
                    <div class="row">
                    <div class="col-md-6 col-sm-6 form-group">
                        <label class="control-label">Income type</label>
                        <div class="input-group col-sm-12">
                            <select class="form-group searchable" name="income_type" required="" id='income_type'>
                                @foreach($types as $key => $name)
                                <option value="{{$key}}" @if($key == $income->income_type) selected @endif>{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                     <div class="col-md-6 col-sm-6 form-group" style="display: @if($income->income_type == 'other_income') block; @else none; @endif" id='other_income_ledgers'>
                        <label class="control-label">Other Income</label>
                        <div class="input-group col-sm-12">
                            <select class="form-group searchable" name="other_ledgers_id" required="">
                                @foreach($other_income as $key=>$ov)
                                <optgroup label="{{ ucfirst($key) }}">
                                    @foreach ($ov as $v)
                                        <option value="{{ $v->id }}" @if($v->id == $income_legder->ledger_id) selected="" @endif>{{ ucfirst($v->name) }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="col-md-6 col-sm-6 form-group" style="display: @if($income->income_type == 'sales_without_invoice' || $income->income_type =='customer_payment') block; @else none; @endif" id='income_ledgers'>
                        <label class="control-label">
                            @if($income->income_type == 'customer_payment')
                            Customer Payment Ledgers
                            @else
                            Sales Without Invoice Ledger
                            @endif

                        </label>
                        <div class="input-group col-sm-12">
                            <select class="form-group searchable" name="ledgers_id" required="">
                                @foreach($sales_invoice as $sales) <!-- this is ledger of selected income types -->
                                <option value="{{$sales->id}}" @if($income_legder->ledger_id == $sales->id) selected @endif>{{$sales->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 form-group">
                        <label class="control-label">Amount Received</label>
                        <div class="input-group">

                <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>

                 {!! Form::number('amount', $income->amount, ['class' => 'form-control','placeholder'=>'Amount', 'required'=>'required','step'=>'any']) !!}

               

              </div>
            </div>
        </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 form-group">
                        <label class="control-label">Received Date</label>
                        <div class="input-group">
                            <div id="dateselectors">
                            <input name="date_received" class="form-control datepicker" placeholder="Received Date" value="{{$income->date_received}}">
                        </div>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-calendar-plus-o"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 form-group">
                        <label class="control-label">Reference No.</label>
                        <div class="input-group">
                            <input type="text" name="reference_no" class="form-control" placeholder="Reference Number.." value="{{$income->reference_no}}">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-code"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 form-group">
                        <label class="control-label">Received Via</label>
                        <div class="input-group col-sm-12">

     {!! Form::select('rcv_via',$received_via,$income->rcv_via,['class'=>'form-control searchable select2 ']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 form-group">
                        <label class="control-label">Description</label>
                            <textarea class="form-control" placeholder="Description" name="description">
                                {!! $income->description !!}
                            </textarea>
                    </div>
                </div>

      
            @if(count($history) > 0)
            <div class="col-md-12">
                
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="clients-table">
                                <caption>Income History</caption>
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>From Amount</th>
                                        <th>To Amount</th>
                                        <th>User</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history as $hy)
                                    <tr>
                                        <td>#{{$hy->id}}</td>
                                        <td>{{$hy->from_amount}}</td>
                                        <td>{{$hy->to_amount}}</td>
                                        <td>{{$hy->user->username}}</td>
                                        <td>{{date('dS Y M',strtotime($hy->created_at))}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
            </div>
            @endif
            
                   <div class="row">
                    <div class="col-md-12">

                        <div class="form-group ">
                            {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                        </div>
                 </div>
               
            </div>
        </form>
                </div>

                </div>
                </div>

<script type="text/javascript" src="https://nepali-date-picker.herokuapp.com/src/jquery.nepaliDatePicker.js"> </script>
<link rel="stylesheet" href="https://nepali-date-picker.herokuapp.com/src/nepaliDatePicker.css">
<?php 
    $cal = new \App\Helpers\NepaliCalendar();
?>
<div style="display: none;">
<div id="nepalidate"> 
    <input name="date_received" value="{{$cal->full_nepali_from_eng_date($income->date_received)}}" class="form-control nepalidatepicker" placeholder="Received Date" >
</div>
<div id="englishdate"> 
<input name="date_received" value="{{$income->date_received}}" class="form-control datepicker" placeholder="Received Date" >
</div>

</div>
<script type="text/javascript">
const dateRange = {
    <?php $currentFiscalyear = FinanceHelper::cur_fisc_yr();?>
    minDate: `{{ $currentFiscalyear->start_date }}`,
    maxDate: `{{ $currentFiscalyear->end_date }}`
}
function setnepalidate(){
    $(".nepalidatepicker").nepaliDatePicker({
        dateFormat: "%y-%m-%d",
        closeOnDateSelect: true
    });
}
function setenglishdate(){
   $('.datepicker').datetimepicker({
          //inline: true,
          //format: 'YYYY-MM-DD',
         format: 'YYYY-MM-DD', 
              sideBySide: true,
              minDate: dateRange.minDate,
              maxDate: dateRange.maxDate,
              allowInputToggle: true
        });
}

 $('#selectdatetype').change(function(){
  let type = $(this).val();
  if(type =='nep'){
    let html = $('#nepalidate').html();
    console.log(html);
    $('#dateselectors').html(html);
    setnepalidate();
  }else{
    let html = $('#englishdate').html();
    $('#dateselectors').html(html);
    setenglishdate();
  }
 });

 $('.searchable').select2({dropdownParent: $("#modal_dialog")});
            $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD', 
          sideBySide: true,
          minDate: dateRange.minDate,
          maxDate: dateRange.maxDate,
          allowInputToggle: true
        });
         
   $('#income_type').change(function(){
        if($(this).val() == 'other_income'){
            $('#other_income_ledgers').css('display','block');
            $('#other_income_ledgers select').prop('required',true);
            $('#income_ledgers').css('display','none');
            $('#income_ledgers select').prop('required',false);
        }
        else if($(this).val() == 'customer_payment' || $(this).val() == 'sales_without_invoice' ){
            $('#income_ledgers').css('display','block');
            $('#income_ledgers select').prop('required',true);
            $('#other_income_ledgers').css('display','none');
            $('#other_income_ledgers select').prop('required',false);
            let label = $(this).val() == 'sales_without_invoice'?'Sales Without Invoice Ledger':'Customer Payment Ledgers';
            $('#income_ledgers .control-label').text(label);
        }
        else{
            $('#income_ledgers').css('display','none');
            $('#other_income_ledgers').css('display','none');
            $('#other_income_ledgers select').prop('required',false);
            $('#income_ledgers select').prop('required',false);
        }
    });
   
    $('textarea').each(function(){
            $(this).val($(this).val().trim());
        }
    );
</script>