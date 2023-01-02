
<div class="modal-content">
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Income Transaction
                <small>Create new transcation</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">
            <form action="{!! route('admin.bank.income.store') !!}" method="post">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6 col-sm-6 form-group">
                        <label class="control-label">Bank Account</label>
                         <div class="input-group col-sm-12">

                         {!! Form::select('income_account',$account,null,['class'=>'form-control searchable select2 ']) !!}
                        </div>
                    </div>

                   

                    <div class="col-md-6 col-sm-6 form-group">
                        <label class="control-label">Tag</label>
                         <div class="input-group col-sm-12">

                         {!! Form::select('tag_id',$tags,null,['class'=>'form-control searchable select2 ']) !!}
                        </div>
                    </div>



                </div>
            <div class="tab-content">
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
                        <label class="control-label">Income type</label>
                        <div class="input-group col-sm-12">
                            <select class="form-group searchable" name="income_type" required="" 
                            id='income_type'>
                                @foreach($types as $key => $name)
                                <option value="{{$key}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 form-group" style="display: none;" id='other_income_ledgers'>
                        <label class="control-label">Other Income</label>
                        <div class="input-group col-sm-12">
                            <select class="form-group searchable" name="other_ledgers_id" >
                                @foreach($other_income as $key=>$ov)
                                <optgroup label="{{ ucfirst($key) }}">
                                    @foreach ($ov as $v)
                                        <option value="{{ $v->id }}">{{ ucfirst($v->name) }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 form-group" style="display: none" id='income_ledgers'>
                        <label class="control-label"></label>
                        <div class="input-group col-sm-12">
                            <select class="form-group searchable" name="ledgers_id" >
                                @foreach($sales_invoice as $sales)
                                <option value="{{$sales->id}}">{{$sales->name}}</option>
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

                 {!! Form::number('amount', $expenses->amount, ['class' => 'form-control','placeholder'=>'Amount', 'required'=>'required','step'=>'any']) !!}

               

              </div>
            </div>
        </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 form-group">
                        <label class="control-label">Received Date</label>
                        <div class="input-group">
                            <div id='dateselectors'>
                                <input name="date_received" value="{{date('Y-m-d')}}" class="form-control datepicker" placeholder="Received Date" >
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
                            <input type="text" name="reference_no" class="form-control" placeholder="Reference Number..Invoice Number or other idicators ">
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

     {!! Form::select('rcv_via',$received_via,null,['class'=>'form-control searchable select2 ']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 form-group">
                        <label class="control-label">Description</label>
                        <div class="input-group">
                            <textarea class="form-control" placeholder="Description" name="description"></textarea>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-file"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                 
                   <div class="row">
                    <div class="col-md-12">

                        <div class="form-group ">
                            {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                        </div>
                 </div>
               
            </div>
        </form>
                </div>
                </div>
                </div>


<?php 
    $cal = new \App\Helpers\NepaliCalendar();
?>
<div style="display: none;">
<div id="nepalidate"> 
    <input name="date_received" value="{{$cal->full_nepali_from_eng_date(date('Y-m-d'))}}" class="form-control nepalidatepicker" placeholder="Received Date" data-single='true'>
</div>
<div id="englishdate"> 
<input name="date_received" value="{{date('Y-m-d')}}" class="form-control datepicker" placeholder="Received Date" >
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
$(function(){

 $('#modal_dialog .searchable').select2({dropdownParent: $("#modal_dialog"),width: '100%'});
})


    $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          minDate: dateRange.minDate,
          maxDate: dateRange.maxDate,
          sideBySide: true,
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
            let link = $(this).val() == 'sales_without_invoice'?"+<a href='/admin/chartofaccounts/create/ledgers' target='_blank'>Create</a>":"+<a href='/admin/clients/create?relation_type=customer' target='_blank'>Create</a>";
            let toappend = label + link;
            $('#income_ledgers .control-label').html(toappend);
        }
        else{
            $('#income_ledgers').css('display','none');
            $('#other_income_ledgers').css('display','none');
            $('#other_income_ledgers select').prop('required',false);
            $('#income_ledgers select').prop('required',false);
        }
    });
</script>