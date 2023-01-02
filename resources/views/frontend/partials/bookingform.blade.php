<?php 
  
  $index = 0;
  
  $passangersArr = [ 'ADT'=>$formData->adult_no,'CNN'=>$formData->clild_no,'INF'=>$formData->infant_no ];
 
  
  $pax_type  = ['ADT'=>'ADULT','CNN'=>'CHILD','INF'=>'INFANT'];


  $isRequired = $isInternational ? 'required' : '';

?>
<style type="text/css">
  .form-check-label:hover{
    cursor: pointer;
  }
</style>
<h3>Passenger Contact Details</h3>
  <div class="form-row align-items-center">
     <div class="col-md-6">
      <label >Contact  Name</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text" style=""><i class="fa  fa-user"></i></div>
        </div>
      <input type="text" name="contact_name" value="{{Auth::user()->first_name}} {{Auth::user()->last_name}}" required class="form-control" placeholder="Passenger contact Information.." >
      </div>
    </div>
    <div class="col-md-6">
      <label >Passenger Email</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text" style=""><i class="fa  fa-envelope"></i></div>
        </div>
        <input type="text" class="form-control" id="inlineFormInputGroupuser" placeholder="Enter valid Email Address" required="" name="email" value="{{Auth::user()->email}}">
      </div>
    </div>
     
  </div>
  <div class="form-row align-items-center">
    <div class="col-md-6">
      <label >Passenger Contact Number</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text" style=""><i class="fa   fa-phone-square"></i></div>
        </div>
        <input type="tel" class="form-control" id="inlineFormInputGroupuser" placeholder="Enter valid Contact Number"  required="" name="contact_no" value="{{Auth::user()->phone}}">
      </div>
    </div>
    
  </div>

<h3>Traveller Details</h3>
@foreach($passangersArr as $kv=>$pas)
@for($pos = 0; $pos < $pas ;$pos++ )
  <?php  ++$index ?>
<div class="row p-2">
<div class="card" style="width: 100%;">
<div class="card-header @if($kv== 'ADT') bg-primary @elseif($kv=='CNN') bg-success @else bg-danger @endif text-white ">
  {{ strtoupper($pax_type[$kv]) }} {{ $pos+1 }}
</div>
<div class="card-body">
    <div class="row">
        <div class="col-md-12">
    <div class="form-check form-check-inline">
          
          <label class="control-label">Document Type</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input toogle-passport-citizen" type="radio" name="{{$kv}}document_type[{{$pos}}]" id="inlineRadiopassport{{$index}}" value="passport" checked="" data-position = '{{ $index }}' {{$isRequired}}>
          <label class="form-check-label" for="inlineRadiopassport{{$index}}">Passport</label>
        </div>
        @if($kv == 'ADT')
        <div class="form-check form-check-inline">
          <input class="form-check-input toogle-passport-citizen" type="radio" 
            name="{{$kv}}document_type[{{$pos}}]" id="inlineRadiocitizen{{$index}}" value="citizenship"  data-position = '{{ $index }}'>
          <label class="form-check-label" for="inlineRadiocitizen{{$index}}">Citizenship</label>
        </div>
        @else
        <div class="form-check form-check-inline">
          <input class="form-check-input toogle-passport-citizen" type="radio" name="{{$kv}}document_type[{{$pos}}]" id="inlineBirth{{$index}}" value="birth"  data-position = '{{ $index }}'>
          <label class="form-check-label" for="inlineBirth{{$index}}">Birth Certificate</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input toogle-passport-citizen" type="radio" 
            name="{{$kv}}document_type[{{$pos}}]" id="minorId{{$index}}" value="minorcard"  data-position = '{{ $index }}'>
          <label class="form-check-label" for="minorId{{$index}}">Minor Id Card</label>
        </div>
        @endif
    </div>
</div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="formGroupExampleInput">Name</label>
                <input type="text" class="form-control first_name"  placeholder="Name" name="{{$kv}}name[]" required="">
              </div>
        </div>
{{-- 
        <div class="col-md-4">
            <div class="form-group">
                <label for="formGroupExampleInput">Middle Name</label>
                <input type="text" class="form-control"  placeholder="Middlename" name="{{$kv}}middlename[]">
              </div>
        </div> --}}
        <div class="col-md-4">
            <div class="form-group">
                <label for="formGroupExampleInput">Surname</label>
                <input type="text" class="form-control sur_name"  placeholder="Surname" name="{{$kv}}surname[]" required="">
              </div>
        </div>
        

    </div>
    <div class="row">
        <div class="col-md-4">
             <div class="form-group">
                <label for="formGroupExampleInput">Nationality</label>
                <select class="form-control" name="{{$kv}}nationality[]">
                    <option value="{{ $getNationality->iso }}">{{ $getNationality->nicename }}</option>
                </select>
              </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="formGroupExampleInput">Date of Birth</label>
                <input type="text" class="form-control date-inpt-past"placeholder="Date of Birth" 
                name="{{$kv}}dob[]" autocomplete="off"  {{$isRequired}}>
              </div>
        </div>
        <div class="col-md-4">
         <div class="form-group">
            <label for="formGroupExampleInput">Gender</label><br>
             <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="{{$kv}}gender[{{$pos}}]" value="M" checked="" id='{{$kv}}genderMale'>
          <label class="form-check-label" for='{{$kv}}genderMale'>Male</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="{{$kv}}gender[{{$pos}}]"  value="F" 
          id='{{$kv}}genderfeMale{{$pos}}'>
          <label class="form-check-label" for='{{$kv}}genderfeMale{{$pos}}'>Female</label>
        </div>
        </div>
    </div>
    </div>

    <div id="Passport{{$index}}">

     <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="formGroupExampleInput">Passport Number</label>
                <input type="text" class="form-control passport"  placeholder="Passport Number" name="{{$kv}}passport_number[]" {{$isRequired}}>
              </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label >Passport Expiry Date</label>
                <input type="text" class="form-control date-inpt-today passport-expiry"  placeholder="Passport Expiry Date" name="{{$kv}}passport_expiry[]"  autocomplete="off" {{$isRequired}}>
              </div>
        </div>
        <div class="col-md-4">
         <div class="form-group">
            <label for="{{$kv}}exampleFormControlFile1">Passport</label>
           <input type="file" class="form-control-file" id="{{$kv}}exampleFormControlFile1" 
           name="{{$kv}}passport{{$pos}}" >
        </div>
    </div>

    </div>

  </div>
  @if($kv == 'ADT')
   <div id="Citizenship{{$index}}" style="display: none;">

     <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="formGroupExampleInput">Citizenship Number</label>
                <input type="text" class="form-control citizenship"  placeholder="Citizenship Number" 
                name="{{$kv}}citizenship_number[]">
              </div>
        </div>
       
        <div class="col-md-4">
         <div class="form-group">
            <label for="{{$kv}}exampleFormControlFile1{{$pos}}">Citizenship</label>
           <input type="file" class="form-control-file" id="{{$kv}}exampleFormControlFile1{{$pos}}" name="{{$kv}}citizenship{{$pos}}">
        </div>
    </div>

    </div>

  </div>
@else
   <div id="BirthCertificate{{$index}}" style="display: none;">

     <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="formGroupExampleInput">Birth Certificate Number</label>
                <input type="text" class="form-control birth_certificate"  placeholder="Birth Certificate Number" name="{{$kv}}birth_cert_number[]">
              </div>
        </div>
       
        <div class="col-md-4">
         <div class="form-group">
            <label for="formGroupExampleInput">Birth Certificate</label>
           <input type="file" class="form-control-file" name="{{$kv}}birth_cert{{$pos}}">
        </div>
    </div>

    </div>

  </div>


   <div id="MinorId{{$index}}" style="display: none;">

     <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="formGroupExampleInput">Minor Id Number</label>
                <input type="text" class="form-control minor_id"  placeholder="Birth Certificate Number" name="{{$kv}}minor_id_number{{$pos}}">
              </div>
        </div>
       
        <div class="col-md-4">
         <div class="form-group">
            <label for="formGroupExampleInput">Minor ID Card</label>
           <input type="file" class="form-control-file" name="{{$kv}}minor_id{{$pos}}">
        </div>
    </div>

    </div>

  </div>
@endif

</div>
</div>
</div>
@endfor
@endforeach


<button class="btn btn-success btn-block" type="button" onclick="open_bookingModal()">
<i class="fa  fa-arrow-circle-right"></i>
Continue
</button>







{{-- <div class="booking-complete" style="display: none;">


<h2>Review and book your trip</h2>
<p>
  <input type="checkbox" id='agree_checkbox'>
  I Agree With The Rules And Restrictions, Privacy Policy, Visa Rules And Terms And Conditions Of {{ env('APP_COMPANY')}}

</p>
@auth
<button type="submit" name="submit_option" class="btn btn-success form-submit-button form-control" value="wallet" style="display: inline-block !important;" >COMPLETE BOOKING WITH WALLET</button>

@endauth
<div class="row">
  
 <div class="col-md-4">
      <label >Paid By:</label>
      <select class="form-control" id="paid_by" name="paid_by" required>
        <option value="ipsconnect">IPSConnet</option>
        <option value="hbl">HBL</option>
      </select>
  </div>


<div class="col-md-8">
<label ></label>
<br>
<button type="submit" name="submit_option" class="btn btn-primary choice-payment-button form-submit-button" >COMPLETE BOOKING </button>


<label ></label>







</div>


</div>



</div> --}}
