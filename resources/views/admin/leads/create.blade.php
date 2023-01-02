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
<link rel="stylesheet" type="text/css" href="/bootstrap-iso.css">
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_description ?? "Page description" !!}</small>
                <small id='ajax_status'></small>
            </h1>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<div class="bootstrap-iso" style="min-height: 1416.81px;">
   {!! Form::open( ['route' => 'admin.leads.store',  'id' => 'form_edit_lead'] ) !!}
    <!-- Main content -->

      <div class="row">
        <div class="col-md-8">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Company or Individual Contact Details</h3>

            </div>
            <div class="card-body" style="display: block;">
            
            <div class="row">

              <div class="form-group col-sm-4">
                <label>  {!! Form::label('title', trans('admin/leads/general.columns.title')) !!}</label>
                {!! Form::select('title', ['Mr'=>'Mr', 'Miss'=>'Miss', 'Mrs'=>'Mrs', 'Ms'=>'Ms','Others'=>'Others'], null, ['class' => 'form-control input-sm']) !!}
              </div>
             <div class="form-group col-sm-4">
                <label>  {!! Form::label('title', trans('admin/leads/general.columns.name')) !!}</label>
                 <input type="text" name="name" placeholder="Company / Person Name" id="name" value="{{ old('name') }}" class="form-control input-sm" required>
              </div>

              <div class="form-group col-sm-4" id='city'>
                <label><label>City</label></label>
                 <select name="city" class="form-control select2 input-sm w-100">
                           <option value="">Select City</option>
                           <option value="" v-if="!iscityLoaded" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Loading.....</option>
                           <option v-if="iscityLoaded" v-for='ci in cities' :value="ci.id" :selected='ci.id == selectedcity'>
                               @{{ ci.city }}
                           </option>
                        </select>
              </div>

            </div>

            <div class="row">

              <div class="form-group col-sm-4">
                <label>Mobile</label>
                <input type="text" name="mob_phone" id="mob_phone" value="{{ old('mob_phone') }}" class="form-control input-sm" required="" placeholder="Primary phone" >
              </div>
             <div class="form-group col-sm-4">
                <label>Mob 2</label>
                <input type="text" name="mob_phone2" id="mob_phone2" value="{{ old('mob_phone2') }}" class="form-control input-sm"  placeholder="Primary phone" >
              </div>

              <div class="form-group col-sm-4">
                <label>Landline</label>
                <input type="text" name="home_phone" id="home_phone" value="{{ old('home_phone') }}" class="form-control input-sm"  placeholder="Landline Number">
              </div>

            </div>


            <div class="row">

              <div class="form-group col-sm-4">
                <label>Email</label>
                 <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control input-sm" placeholder="Email Address">
              </div>
             <div class="form-group col-sm-4">
                <label>Website</label>
                <input type="text" name="homepage" placeholder="website or blog" id="homepage" value="" class="form-control input-sm">
              </div>

              <div class="form-group col-sm-4">
                <label>Skype</label>
                <input type="text" name="skype" placeholder="skype id" id="skype" value="{{ old('skype') }}" class="form-control input-sm" >
              </div>

            </div>
            <div class="form-group">
                    <a href="javascript::void(0)" class="btn btn-danger btn-sm" id="addMore" style="float: left;">
                        <i class="fa fa-plus"></i> <span>Add More Contact Details</span>
                    </a>
                  </div><br>

                <div class="row InputsWrapper">


                </div> 
             <span id="orderFields">

                 <div class="row" id="more-tr"  style="display: none;">
    
                    <div class="form-group col-sm-4">
                <label>Name.</label>
                 <input type="text" name="extra_name[]" id="mobile1" value="{{ old('mob_phone') }}" class="form-control input-sm" placeholder="Primary phone" >
              </div>
              <div class="form-group col-sm-4">
                <label>Mobile</label>
                <input type="text" name="extra_mobile[]" id="mobile1" value="{{ old('mob_phone2') }}" class="form-control input-sm"  placeholder="Moblie Number" >
              </div>
              <div class="form-group col-sm-4">
                <label>Email</label>
                <input type="text" name="extra_email[]" id="home_phone" placeholder="Email" value="{{ old('home_phone') }}" class="form-control input-sm" >
              </div>


                    

                    
                </div> 
                </span> 
            <h3>Basic Details</h3>

            <div class="row">

              <div class="form-group col-sm-4">
               {!! Form::label('contact_id', trans('admin/leads/general.columns.contact_id')) !!}
                  {!! Form::text('contact_id', null, ['class' => 'form-control input-sm', 'id'=>'contact_id', 'placeholder' => 'Referral Agent Contact']) !!}

                                      <a href="#" onclick="openwindow()"> <i class="fa fa-external-link-alt"></i> New Contact</a>
              </div>
             <div class="form-group col-sm-4">
                <label>Follow up</label>
                 <input type="text" name="target_date" id="target_date" value="{{\Carbon\Carbon::now()->addDays(4)->toDateString()}}" placeholder="Next Action Date" class="form-control datepicker input-sm" required="required">
              </div>

              <div class="form-group col-sm-4">
                <label>Value</label>
                {!! Form::text('price_value', null, ['class' => 'form-control input-sm', 'placeholder'=>'Value in NPR',isset($readonly) ? $readonly : null ]) !!}
              </div>

            </div>

            <div class="row">

              <div class="form-group col-sm-4">
             <label>Address</label>
                 <input type="text" name="address_line_1" id="address_line_1" value="{{ old('address_line_1') }}" class="form-control input-sm"  placeholder="Address" required="">
              </div>
             <div class="form-group col-sm-4"  id='vujsCountry'>
                <label class="control-label">Country</label>
                 <select name="country" class="form-control input-sm select2 w-100">
                         <option value="">Select Country</option>
                         <option value="" v-if="!iscountryLoaded">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Loading.....</option>
                         <option v-if="iscountryLoaded" v-for='co in country' :value="co.country" :selected='co.country == selectedcountry'>
                             @{{ co.country }}
                         </option>
                      </select>
              </div>


            </div>


               <div class="form-group">
                <label for="inputDescription"> Descriptions and Client Requirements</label>
                <textarea class="form-control" rows="4" spellcheck="false" name="description" id="description" placeholder="Write Description">{!! \Request::old('description') !!}</textarea>
              </div>
              <hr>
              <div class="row">
        <div class="col-12">
          <a href="/admin/leads/" class="btn btn-secondary">Cancel</a>
          <input type="submit" value="Create new Lead" class="btn btn-success">
        </div>
      </div>

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <div class="col-md-4">
          <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">Products & More</h3>

        
            </div>
            <div class="card-body">
              <div class="form-group">
                <label >Products</label>
               {!! Form::select('product_id', $courses, null, ['class' => 'form-control select2 w-100','id'=>'products', 'placeholder' => 'Please Select']) !!}
              </div>
              <div class="form-group">
                <label>Custom product</label>
                <input type="text" name="custom_product" placeholder="Custom Product" id="custom_product" value="" class="form-control input-sm">
              </div>
              <div class="form-group">
                <label>Rating</label>
                 {!! Form::select('rating', $lead_rating,null, ['class' => 'form-control label-default w-100 bg-default']) !!}
              </div>
               <div class="form-group">
                <label >Communications</label>
                 {!! Form::select('communication_id', $communications, null, ['class' => 'form-control label-default w-100']) !!}
              </div>
               <div class="form-group">
                <label >Status</label>
                 {!! Form::select('status_id', $lead_status, 1, ['class' => 'form-control label-default w-100']) !!}
              </div>
               <div class="form-group">
                <label >Campaign</label>
                 {!! Form::select('campaign_id', $campaigns, 1, ['class' => 'form-control label-default w-100']) !!}
              </div>
              <div class="form-group">
                <label >Owner</label>
                 {!! Form::select('user_id',  $users, \Auth::user()->id, ['class' => 'form-control label-default w-100 select2']) !!}
              </div>
              <div class="form-group">
                <label >Type</label>
                  @if(!null == \Request::get('type'))
                              @if(\Request::get('type') == 'target')
                              {!! Form::select('lead_type_id', ['1'=>'Target', '2'=>'Leads','4'=>'Customer','5'=>'Contact','6'=>'Agent'], $lead->lead_type_id, ['class' => 'form-control w-100']) !!}
                              @elseif(\Request::get('type') == 'leads')
                              {!! Form::select('lead_type_id', ['2'=>'Leads','4'=>'Customer','5'=>'Contact','6'=>'Agent'], $lead->lead_type_id, ['class' => 'form-control w-100']) !!}
                              @elseif(\Request::get('type') == 'contact')
                              {!! Form::select('lead_type_id', ['5'=>'Contact','4'=>'Customer','2'=>'Leads','6'=>'Agent'], $lead->lead_type_id, ['class' => 'form-control w-100']) !!}
                              @elseif(\Request::get('type') == 'customer')
                              {!! Form::select('lead_type_id', [ '4'=>'Customer','5'=>'Contact','6'=>'Agent'], $lead->lead_type_id, ['class' => 'form-control w-100']) !!}
                               @elseif(\Request::get('type') == 'agent')
                              {!! Form::select('lead_type_id', [ '6'=>'Agent','4'=>'Customer','5'=>'Contact','2'=>'Leads'], $lead->lead_type_id, ['class' => 'form-control w-100']) !!}
                              @endif
                              <input type="hidden" name="type" value="{{ \Request::get('type') }}">
                            @else
                            {!! Form::select('lead_type_id', $lead_types, $lead->lead_type_id, ['class' => 'form-control w-100']) !!}
                            <input type="hidden" name="type" value="leads">
                            @endif
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      

    {!! Form::close() !!}
    <!-- /.content -->
  </div>




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
<script src="/vue/vue.js"></script>


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

$(function(){


  setTimeout(function(){

    $('.select2').select2();

  },500);
  
})

</script>
    <!-- form submit -->
    @include('partials._body_bottom_submit_lead_edit_form_js')
@endsection

