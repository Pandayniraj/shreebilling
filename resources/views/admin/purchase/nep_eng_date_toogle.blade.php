
<?php 
  $cal = new \App\Helpers\NepaliCalendar();
?>
<div id = 'datapart1' style="display: none">
<div class="col-md-12 nepalidate">
<input type="text" class="form-control pull-right nepalidatepicker" name="bill_date" 
@if($order->bill_date) value="{{$cal->full_nepali_from_eng_date($order->bill_date)}}" @endif
 id="bill_date" required=""  data-single='true'>
    
        <!-- /.input group -->
    
</div>

  <div class="col-md-12 englishdate">
                                    

<input type="text" class="form-control pull-right datepicker" name="bill_date" value="{{$order->bill_date}}" id="bill_date" required="">

</div>

</div>

<div id = 'datapart2' style="display: none">
<div class="col-md-12 nepalidate" >
<input type="text" class="form-control pull-right nepalidatepicker" name="due_date" 
@if($order->due_date) value="{{$cal->full_nepali_from_eng_date($order->due_date)}}"  @endif
id="due_date" required=""  data-single='true'>
</div>

  <div class="col-md-12 englishdate">
                                    

<input type="text" class="form-control pull-right datepicker" name="due_date" value="{{$order->due_date}}" id="due_date" required="">

</div>

</div>
<link rel="stylesheet" href="/nepali-date-picker/nepali-date-picker.min.css">
<script type="text/javascript" src="/nepali-date-picker/nepali-date-picker.js"> </script>

  <script type="text/javascript">
  const dateRange = {
    <?php $currentFiscalyear = FinanceHelper::cur_fisc_yr();?>
    minDate: `{{ $currentFiscalyear->start_date }}`,
    maxDate: `{{ $currentFiscalyear->end_date }}`
}
    function setnepalidate(){
    $(".nepalidatepicker").nepaliDatePicker();
}
function setenglishdate(){

   $('.datepicker').datetimepicker({
          //inline: true,
          //format: 'YYYY-MM-DD',
          format: 'YYYY-MM-DD', 
              sideBySide: true,
              allowInputToggle: true,
              minDate: dateRange.minDate,
            maxDate: dateRange.maxDate,
        });
}

 $('#selectdatetype').change(function(){
  let type = $(this).val();
  if(type =='nep'){
    let html = $('#datapart1 .nepalidate').html();
    // console.log(html);
    $('#dateselectors').html(html);

     let html1 = $('#datapart2 .nepalidate').html();
     $('#dateselectors1').html(html1);
    setnepalidate();
  }else{
    let html = $('#datapart1 .englishdate').html();
    $('#dateselectors').html(html);

    let html1 = $('#datapart2 .englishdate').html();
     $('#dateselectors1').html(html1);
    setenglishdate();
  }
 });

  </script>