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


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_description ?? "Page description" !!}</small>
                <small id='ajax_status'></small>
            </h1>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box">
        <div class="box-body">
                  {!! Form::open( ['route' => 'admin.leads.store', 'class' => 'form-horizontal', 'id' => 'form_edit_lead'] ) !!}
      
                <div class="content col-md-9">

                    <h4> Company or Individual Contact Details</h4>
                
                    <div class="row">

                         <div class="col-md-4">
                            <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">
                                {!! Form::label('title', trans('admin/leads/general.columns.title')) !!}
                              </label>
                              <div class="col-sm-10">
                                {!! Form::select('title', ['Mr'=>'Mr', 'Miss'=>'Miss', 'Mrs'=>'Mrs', 'Ms'=>'Ms','Others'=>'Others'], null, ['class' => 'form-control input-sm']) !!}
                              </div>
                            </div>
                        </div>

                         <div class="col-md-4">

                         <div class="form-group">  
                            <label class="control-label col-sm-3">Name</label>
                                <div class="input-group ">
                                    
                                    <input type="text" name="name" placeholder="Company / Person Name" id="name" value="{{ old('name') }}" class="form-control input-sm" required>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-user"></i></a>
                                    </div>
                                </div>
                                
                            </div>
                        </div> 




                <div class="col-md-4"  id='city'>
                  <div class="form-group">
                     <label class="control-label col-sm-3">City</label>
                     <div class="input-group ">
                        <select name="city" class="form-control select2 input-sm">
                           <option value="">Select City</option>
                           <option value="" v-if="!iscityLoaded" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Loading.....</option>
                           <option v-if="iscityLoaded" v-for='ci in cities' :value="ci.id" :selected='ci.id == selectedcity'>
                               @{{ ci.city }}
                           </option>
                        </select>
                     </div>
                  </div>
                </div>
{{-- 
                <div class="col-md-4">
                          <div class="form-group">  
                <label class="control-label col-sm-3">City</label>
                    <div class="input-group ">
                        <input type="text" name="city" placeholder="city" id="city" value="{{ old('city') }}" class="form-control input-sm" required="required">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-map"></i></a>
                        </div>
                    </div>
                </div>
                        </div> --}}

                        

                        
                   </div>


                <div class="row">

                    <div class="col-md-4">
                           <div class="form-group"> 
                           <label class="control-label col-sm-3">Mobile.</label> 
                           
                              <div class="input-group" style="z-index: 7000">
                              <input type="text" name="mob_phone" id="mob_phone" value="{{ old('mob_phone') }}" class="form-control input-sm" required="" placeholder="Primary phone" >
                              <div class="input-group-addon">
                              <a href="#"><i class="fa fa-mobile"></i></a>
                             </div>
                           </div>
                         </div>
                       </div>


                        <div class="col-md-4">
                           <div class="form-group"> 
                           <label class="control-label col-sm-3">Mob 2</label> 
                           
                              <div class="input-group">
                              <input type="text" name="mob_phone2" id="mob_phone2" value="{{ old('mob_phone2') }}" class="form-control input-sm" placeholder="Primary phone" >
                              <div class="input-group-addon">
                              <a href="#"><i class="fa fa-mobile"></i></a>
                             </div>
                           </div>
                         </div>
                       </div>

                       <div class="col-md-4">
                            <div class="form-group">  
                            <label class="control-label col-sm-3">Landline</label>
                                <div class="input-group ">
                                    <input type="text" name="home_phone" id="home_phone" value="{{ old('home_phone') }}" class="form-control input-sm">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-phone"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>


                </div>



                <div class="row">

                     <div class="col-md-4">
                         <div class="form-group">  
                <label class="control-label col-sm-3">Email</label>
                    <div class="input-group ">
                        <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control input-sm" required="">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-at"></i></a>
                        </div>
                    </div>
                </div>
                      </div>

                        <div class="col-md-4">
                          <div class="form-group">  
                <label class="control-label col-sm-3">Website</label>
                    <div class="input-group ">
                        <input type="text" name="homepage" placeholder="website or blog" id="homepage" value="" class="form-control input-sm" >
                        <div class="input-group-addon">
                            <a href="#"><i class="fas fa-home"></i></a>
                        </div>
                    </div>
                </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">  
                <label class="control-label col-sm-3">Skype</label>
                    <div class="input-group ">
                        <input type="text" name="skype" placeholder="skype id" id="skype" value="{{ old('skype') }}" class="form-control input-sm">
                        <div class="input-group-addon">
                            <a href="#"><i class="fab fa-skype"></i></a>
                        </div>
                    </div>
                </div>
                        </div> 

                        
                    </div> 

                <div class="col-md-12">
                    <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMore" style="float: left;">
                        <i class="fa fa-plus"></i> <span>Add More Contact Details</span>
                    </a>
                  </div>

                    

                <div class="row InputsWrapper">


                </div> 
                <span id="orderFields">

                 <div class="row" id="more-tr"  style="display: none;">
    
                        <div class="col-md-4">
                           <div class="form-group"> 
                           <label class="control-label col-sm-3">Name.</label> 
                           
                              <div class="input-group" style="z-index: 7000">
                              <input type="text" name="extra_name[]" id="mobile1" value="{{ old('mob_phone') }}" class="form-control input-sm" placeholder="Primary phone" >
                              <div class="input-group-addon">
                              <a href="#"><i class="fa fa-user"></i></a>
                             </div>
                           </div>
                         </div>
                       </div>


                        <div class="col-md-4">
                           <div class="form-group"> 
                           <label class="control-label col-sm-3">Mobile</label> 
                              <div class="input-group" style="z-index: 7000">
                              <input type="text" name="extra_mobile[]" id="mobile1" value="{{ old('mob_phone2') }}" class="form-control input-sm" placeholder="Moblie Number" >
                              <div class="input-group-addon">
                              <a href="#"><i class="fa fa-mobile"></i></a>
                             </div>
                           </div>
                         </div>
                       </div>

                       <div class="col-md-4">
                            <div class="form-group">  
                            <label class="control-label col-sm-3">Email</label>
                                <div class="input-group ">
                                    <input type="text" name="extra_email[]" id="home_phone" placeholder="Email" value="{{ old('home_phone') }}" class="form-control input-sm">

                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-at"></i></a>
                                    </div>
                                    
                                </div>

                            </div>

                        </div>

                    
                </div> 
                </span> 

                <h4> Basic Details</h4>
                  <div class="row"> 
                            <div class="col-md-4">
                                <div class="form-group">
                                      <label for="inputEmail3" class="col-sm-3 control-label">
                                      {!! Form::label('contact_id', trans('admin/leads/general.columns.contact_id')) !!}
                                    </label>

                                      <div class="col-sm-9">
                                      {!! Form::text('contact_id', null, ['class' => 'form-control input-sm', 'id'=>'contact_id', 'placeholder' => 'Referral Agent Contact']) !!}

                                      <a href="#" onclick="openwindow()"> <i class="fa fa-external-link-alt"></i> New Contact</a>
                                    </div>

                                </div>
                            </div>
                      

                            <div class="col-md-4">
                                <div class="form-group">  
                                  <label class="control-label col-sm-4">Follow up</label>
                                  <div class="input-group ">
                                    <input type="text" name="target_date" id="target_date" value="{{\Carbon\Carbon::now()->addDays(4)->toDateString()}}" placeholder="Next Action Date" class="form-control datepicker input-sm" required="required">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="far fa-calendar-alt"></i></a>
                                    </div>
                                  </div>
                                </div>
                            </div>

                          <div class="col-md-4">
                              <div class="form-group">
                                 <label class="control-label col-sm-4">Value</label>
                                  <div class="input-group">
                                    <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                                        {!! Form::text('price_value', null, ['class' => 'form-control input-sm', $readonly]) !!}
                                  </div>
                            </div>
                        </div>

                  </div>

                                             
            <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                     <label class="control-label col-sm-3">Address</label>
                     <div class="input-group ">
                        <input type="text" name="address_line_1" id="address_line_1" value="{{ old('address_line_1') }}" class="form-control input-sm" >
                        <div class="input-group-addon">
                           <a href="#"><i class="fa fa-map"></i></a>
                        </div>
                     </div>
                  </div>
                </div>
                
                <div class="col-md-4"  id='vujsCountry'>
                  <div class="form-group">
                     <label class="control-label col-sm-2">Country</label>
                     <div class="col-sm-10">
                        <select name="country" class="form-control input-sm select2">
                           <option value="">Select Country</option>
                           <option value="" v-if="!iscountryLoaded">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Loading.....</option>
                           <option v-if="iscountryLoaded" v-for='co in country' :value="co.country" :selected='co.country == selectedcountry'>
                               @{{ co.country }}
                           </option>
                        </select>
                     </div>
                  </div>
                </div>
                </div>
                       
                    
                <div class="row">
                     <div class="col-md-12">
                        <label for="inputEmail3" class="control-label">
                        Descriptions and Client Requirements
                        </label>
                          <textarea class="form-control input-sm" name="description" id="description" placeholder="Write Description">{!! \Request::old('description') !!}</textarea>
                        </div>
                </div>


                </div><!-- /.content -->
                <div class="content col-md-3">

                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">
                        Prod..
                        </label>
                        <div class="col-sm-10">
                        {!! Form::select('product_id', $courses, null, ['class' => 'form-control input-sm select2','id'=>'products', 'placeholder' => 'Please Select']) !!}
                      </div>
                    </div>

                    <div class="form-group">  
                       <label class="control-label col-sm-2">Prod..</label>
                        <div class="col-sm-10">
                            <input type="text" name="custom_product" placeholder="Custom Product" id="custom_product" value="" class="form-control input-sm">
                        </div>
                    </div>

                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">
                        {!! Form::label('rating', trans('admin/leads/general.columns.rating')) !!}
                        </label><div class="col-sm-10">
                        {!! Form::select('rating', $lead_rating,
                         null, ['class' => 'form-control label-default input-sm']) !!}
                       </div>
                  </div>
                  
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">
                       Comm..
                        </label><div class="col-sm-10">
                        {!! Form::select('communication_id', $communications, null, ['class' => 'form-control label-default input-sm']) !!}
                      </div>
                    </div>


                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">
                      Status
                      </label><div class="col-sm-10">
                      {!! Form::select('status_id', $lead_status, 1, ['class' => 'form-control label-default input-sm']) !!}
                    </div>
                    </div>

                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">
                        Campaign
                      </label><div class="col-sm-10">
                        {!! Form::select('campaign_id', $campaigns, 1, ['class' => 'form-control label-default input-sm']) !!}
                    </div>
                    </div>

                  

                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">
                      Owner
                      </label><div class="col-sm-10">
                        {!! Form::select('user_id',  $users, \Auth::user()->id, ['class' => 'form-control label-default input-sm']) !!}
                      </div>
                    </div>

                     

                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">
                            Type

                          </label><div class="col-sm-10">
                            @if(!null == \Request::get('type'))
                              @if(\Request::get('type') == 'target')
                              {!! Form::select('lead_type_id', ['1'=>'Target', '2'=>'Leads','4'=>'Customer','5'=>'Contact','6'=>'Agent'], $lead->lead_type_id, ['class' => 'form-control input-sm']) !!}
                              @elseif(\Request::get('type') == 'leads')
                              {!! Form::select('lead_type_id', ['2'=>'Leads','4'=>'Customer','5'=>'Contact','6'=>'Agent'], $lead->lead_type_id, ['class' => 'form-control input-sm']) !!}
                              @elseif(\Request::get('type') == 'contact')
                              {!! Form::select('lead_type_id', ['5'=>'Contact','4'=>'Customer','2'=>'Leads','6'=>'Agent'], $lead->lead_type_id, ['class' => 'form-control input-sm']) !!}
                              @elseif(\Request::get('type') == 'customer')
                              {!! Form::select('lead_type_id', [ '4'=>'Customer','5'=>'Contact','6'=>'Agent'], $lead->lead_type_id, ['class' => 'form-control input-sm']) !!}
                               @elseif(\Request::get('type') == 'agent')
                              {!! Form::select('lead_type_id', [ '6'=>'Agent','4'=>'Customer','5'=>'Contact','2'=>'Leads'], $lead->lead_type_id, ['class' => 'form-control input-sm']) !!}
                              @endif
                              <input type="hidden" name="type" value="{{ \Request::get('type') }}">
                            @else
                            {!! Form::select('lead_type_id', $lead_types, $lead->lead_type_id, ['class' => 'form-control']) !!}
                            <input type="hidden" name="type" value="leads">
                            @endif
                      </div>
                      </div>
                     
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">
                            
                          </label><div class="col-sm-10">

                            @if(\Request::get('type') == 'contact')
                             <div class="form-group">
                                        <label>Select Customer <i class="imp"></i></label>
                                        <select class="form-control input-sm customer_id select2" name="customer_id" required="required">
                                        <option class="input input-lg" value="">Select Customer</option>
                                        @if(isset($cust))
                                            @foreach($cust as $key => $uk)
                                                <option value="{{ $uk->id }}" @if($orderDetail && $uk->id == $orderDetail->customer_id){{ 'selected="selected"' }}@endif>{{ '('. $uk->id.') '.$uk->name.' ('.$uk->organization.')' }}</option>
                                            @endforeach
                                        @endif
                                        </select>
                                    </div>
                            @endif


                          </div>
                      </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                        <a href="{!! route('admin.leads.index') !!}?type={{\Request::get('type')}}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                    </div>
                </div>

                  {!! Form::close() !!}
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
            $('.select2').select2();
      $('#dob').datetimepicker({
          //inline: true,
          //format: 'YYYY-MM-DD',
          format: 'DD-MM-YYYY',
          sideBySide: true
        });
    });
  </script>
    <script type="text/javascript">
    $(document).ready(function() {
      $("#contact_id").autocomplete({
            source: "/admin/getContacts",
            minLength: 1,
      });

      // $( "#city" ).autocomplete({
      //   source: "/admin/getCities",
      //   minLength: 1
      // });

    });

function populatecontacts(result){
    $('#name').val(result.full_name); 
    $('#mob_phone').val(result.phone);
    $('#mob_phone2').val(result.phone_2); 
    $('#email').val(result.email_1);  
    $('#department').val(result.department);
    $('#position').val(result.position);
    $('#home_phone').val(result.landline);
    $('#skype').val(result.skype);
    $('#homepage').val(result.website);
    $('#address_line_1').val(result.address);
    $('#city select').val(result.city);
    $('#title').val(result.salutation);
    return 0;
}

$('#contact_id').on('change',function(){

 $.ajax(
        { url: "/admin/getcontactinfo",  data: { contact_id: $(this).val() }, dataType: "json", 
        success: function( data ) { 
            var result = data.data;
            populatecontacts(result);
        } 
        }); 
  
    });
  </script>

   <script type="text/javascript">
    $(function(){
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD', 
          sideBySide: true,
          allowInputToggle: true
        });

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

<script type="text/javascript">
        $(document).ready(function() {
            $("#company_id").autocomplete({
                source: "/admin/getdata",
                minLength: 1
            });
        });
</script>
<script>

    $("#addMore").on("click", function () {
      
         $(".InputsWrapper").after($('#orderFields #more-tr').html());
    });



 function openwindow(){
    var win =  window.open('/admin/contacts/create/modals', '_blank','toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
    }
 function HandlePopupResult(result) {
      if(result){
        let contact = result.contacts;

        setTimeout(function(){
            $('#contact_id').val(contact.full_name);
            populatecontacts(contact);
            $("#ajax_status").after("<span style='color:green;' id='status_update'>Contacts sucessfully created</span>");
            $('#status_update').delay(3000).fadeOut('slow');
        },500);
      }
      else{
        $("#ajax_status").after("<span style='color:red;' id='status_update'>failed to create clients</span>");
        $('#status_update').delay(3000).fadeOut('slow');
      }
    }




    
</script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>


<script type="text/javascript">


var Objectcities = new Vue({
el:'#city',
data:{
    cities: [],
    selectedcity: 508, //kathmandu
    iscityLoaded: true,
},
methods:{
    getCities: function(country){
        console.log(country);
        iscityLoaded= false;
        var v= this;
         v.cities = [];
        $.get(`/admin/cities/${country}`,function(response){
            v.cities = response.cities;
        });
    },
},
mounted(){
    this.getCities('Nepal');
}

});


var Objectcountry = new Vue({
    el:'#vujsCountry',
    data:{
        country: [],
        selectedcountry: 'Nepal',
        iscountryLoaded: false,
    },
    methods:{
        getCountry: function(){
            var v= this;
            $.get('/admin/country',function(response){
                v.country = response.country;
                v.iscountryLoaded = true
            });
        },
    },
    mounted(){
        this.getCountry();
        $('#vujsCountry select').change(function(){
            var c = $(this);
            setTimeout(function(){
                Objectcities.getCities(c.val());
            },100);
        return true;
            
        })
    }

});



</script>
    <!-- form submit -->
    @include('partials._body_bottom_submit_lead_edit_form_js')
@endsection
