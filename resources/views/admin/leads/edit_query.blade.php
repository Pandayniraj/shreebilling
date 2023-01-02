 @extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<style>
  select { width:200px !important; }
label {
    font-weight: 600 !important;
}

 .intl-tel-input { width: 100%; }
 .intl-tel-input .iti-flag .arrow {border: none;}

</style>


<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset("/bower_components/intl-tel-input/build/css/intlTelInput.css") }}" rel="stylesheet" type="text/css" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script src="{{ asset("/bower_components/intl-tel-input/build/js/intlTelInput-jquery.min.js") }}"></script>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box">
        <div class="box-body">

          
                  
                  <form method="post" action="{{ route('admin.lead.query-update', $query->id) }}">
                    {{ csrf_field() }}

                <div class="content col-md-9">

         <h4> New Query </h4>
                      <input type="hidden" name="lead_id" value="{{$query->lead_id}}">
                    


                     <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                <label class="control-label col-sm-4">Main Product</label>
                    <div class="input-group ">
                         {!! Form::select('product', $products, $query->product, ['class' => 'form-control select2','id'=>'products']) !!}
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-tasks"></i></a>
                        </div>
                    </div>
                </div>
                        </div>
                       <div class="col-md-6">
                       <div class="form-group">
                <label class="control-label col-sm-4">Price</label>
                    <div class="input-group ">
                        <input type="text" name="price" id="airline" value="{{$query->price}}" class="form-control input-sm">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-dollar"></i></a>
                        </div>
                    </div>
                </div></div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                <label class="control-label col-sm-4">Phone</label>
                    <div class="input-group ">
                        <input type="text" name="phone" placeholder="" id="phone" value="{{$query->phone}}"  class="form-control input-sm">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-mobile"></i></a>
                        </div>
                    </div>
                </div>
                        </div>
                       <div class="col-md-6">
                       <div class="form-group">
                <label class="control-label col-sm-4">Email</label>
                    <div class="input-group ">
                        <input type="text" name="email" id="email" value="{{$query->email}}" class="form-control input-sm">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-at"></i></a>
                        </div>
                    </div>
                </div></div>
                    </div>



                    <div class="row">
                       
                       <div class="col-md-6">
                       <div class="form-group">
                <label class="control-label col-sm-4">Contact Person</label>
                    <div class="input-group ">
                        <input type="text" name="contact_person" id="contact_person" value="{{$query->contact_person}}" class="form-control input-sm">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-user"></i></a>
                        </div>
                    </div>
                </div></div>

                <div class="col-md-6">
                          <label class="control-label col-sm-4">Detail</label>
                           <div class="input-group ">
                          <textarea class="form-control" name="detail" id="detail" placeholder="Write Description">{{$query->detail}}</textarea>
                        </div>
                        </div>
                    </div>

                    <div class="row">
                       
                       <div class="col-md-6">
                       <div class="form-group">
                <label class="control-label col-sm-4">Next Action Date</label>
                    <div class="input-group ">
                        <input type="text" name="next_action_date" id="next_action_date" value="{{$query->next_action_date}}" class="form-control input-sm datepicker">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-user"></i></a>
                        </div>
                    </div>
                </div></div>

                <div class="col-md-6">
                             <div class="form-group">
                             <label class="control-label col-sm-4">Status</label>
                               <div class="input-group ">
                                {!! Form::select('status', ['New'=>'New','Follow Up'=>'Follow Up','Closed'=>'Closed'],$query->status, ['class' => 'form-control select2','id'=>'products']) !!}
                               </div>
                          </div>
                        </div>

                
                    </div>

                     <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">

                      <label class="control-label col-sm-4">More Products</label>

                      <div class="input-group ">
                      <div id="AddMoreFileId">
                            <a href="#" id="AddMoreFileBox" class=""><i class=" fa fa-plus"></i> Add more..</a><br><br>
                          </div>

                          <input type="hidden" name="lead_query_id" value="{{$query->id}}">


   

                      <span id="InputsWrapper">
                      	@foreach($pax as $p)
                       <div> 
                       	

                          <input type="text" class="form-control" name="paxName[]" id="field_1" value="{{$p->pax_name}}" placeholder="Product">
                          <input type="text" class="form-control" name="mileageCard[]" id="field_1" value="{{$p->mileage_card}}" placeholder="Quantity">
                          <a href="#" class="removeclass"></a>
                        </div>
                         @endforeach
                      </span>
                     
                          
                          </div>
                                                  <!--   <input type="submit" id="submit" name="send" value="Send"> -->
                        


                    </div>
                  </div>
                      </div>

                            
           
              
                </div><!-- /.content -->
               


                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Update</button>
                        <a href="{!! route('admin.leads.index') !!}/{{\Request::segment(3)}}?type=customer" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                    </div>
                </div>

                  </form>
        </div>
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
  <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
  <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
    <script type="text/javascript">

    $(function() {
      $('#dob').datetimepicker({
          //inline: true,
          //format: 'YYYY-MM-DD',
          format: 'DD-MM-YYYY',
          sideBySide: true
        });
    });
  </script>


  <script type="text/javascript">
    $(document).ready(function(){

      $("#selling_price, #cost_price, #number_pax, #total_selling, #total_profit, #total_cost").bind('change', function(){
                $('#total_selling').val(parseInt($("#selling_price").val()) * parseInt($("#number_pax").val()));
                $('#total_cost').val(parseInt($("#cost_price").val()) * parseInt($("#number_pax").val()));
                $('#total_profit').val(parseInt($("#total_selling").val()) - parseInt($("#total_cost").val()));
      });

   });

  </script>


    <script type="text/javascript">
    $(document).ready(function() {
      $("#contact_id").autocomplete({
            source: "/admin/getContacts",
            minLength: 1
      });

      $( "#city" ).autocomplete({
        source: "/admin/getCities",
        minLength: 1
      });

    });
  </script>

  <script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        });

      });

    $(document).ready(function() {
    $('#products').select2();
});
</script>

<script type="text/javascript">

    $(".telephone").intlTelInput({
        // options here
        preferredCountries: [ "np","in","us","gb","au","sg","hk","cn","my","ae"],
        formatOnDisplay: true,
        separateDialCode: true,
         autoPlaceholder: 'polite',
        utilsScript: "/bower_components/intl-tel-input/build/js/utils.js?1549804213570"
    });

$('#mobile1').focus(function() {
    var countryCode = $('.selected-dial-code').text();
    $(this).val(countryCode);
});

</script>


<!-- javascript for pax taken from http://jsfiddle.net/7Weyb/1/-->

    <script>

  
$(document).ready(function() {

var MaxInputs       = 10; //maximum extra input boxes allowed
var InputsWrapper   = $("#InputsWrapper"); //Input boxes wrapper ID
var AddButton       = $("#AddMoreFileBox"); //Add button ID

var x = InputsWrapper.length; //initlal text box count
var FieldCount=1; //to keep track of text box added

//on add input button click
$(AddButton).click(function (e) {
        //max input box allowed
        if(x <= MaxInputs) {
            FieldCount++; //text box added ncrement
            //add input box
            $(InputsWrapper).append('<div class=""><input class="form-control input-sm" placeholder="product" type="text" name="paxName[]" id="field_'+ FieldCount +'"/> <input class="form-control input-sm" type="text" placeholder="qty" name="mileageCard[]" id="field_'+ FieldCount +'"/> <div id="lineBreak"></div>');
            x++; //text box increment
            
            $("#AddMoreFileId").show();
            
            $('AddMoreFileBox').html("Add field");
            
            // Delete the "add"-link if there is 3 fields.
            if(x == 10) {
                $("#AddMoreFileId").hide();
              $("#lineBreak").html("<br>");
            }
        }
        return false;
});

$("body").on("click",".removeclass", function(e){ //user click on remove text
        if( x > 1 ) {
                $(this).parent('div').remove(); //remove text box
                x--; //decrement textbox
            
              $("#AddMoreFileId").show();
            
              $("#lineBreak").html("");
            
                // Adds the "add" link again when a field is removed.
                $('AddMoreFileBox').html("Add field");
        }
  return false;
}) 

});
      </script>





    <!-- form submit -->
    @include('partials._body_bottom_submit_lead_edit_form_js')
@endsection
