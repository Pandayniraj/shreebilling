

<style type="text/css">
  .form-check-label:hover{
    cursor: pointer;
  }

  .switch-ios.switch-light {
    margin-top: 5px !important;
}
.switch-ios.switch-light {
    color: #868686;
}
.switch-light {
    display: block;
    height: 30px;
    width: 100px;
    position: relative;
    overflow: visible;
    padding: 0;
    margin: auto;
}

</style>
<h3>PERSONAL DETAILS</h3>
  <div class="form-row align-items-center">
     <div class="col-md-6">
      <label >First  Name</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text" style=""><i class="fa  fa-user"></i></div>
        </div>
      <input type="text" name="first_name" value="{{Auth::user()->first_name}}" required class="form-control" placeholder="First Name.." >
      </div>
    </div>
    <div class="col-md-6">
      <label>Last Name</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text" style=""><i class="fa  fa-envelope"></i></div>
        </div>
        <input type="text" class="form-control" id="inlineFormInputGroupuser" placeholder="Last Name" required="" name="last_name" value="{{Auth::user()->last_name}}">
      </div>
    </div>
  </div>
  <div class="form-row align-items-center">
     <div class="col-md-12">
      <label >Email</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text" style=""><i class="fa  fa-at"></i></div>
        </div>
      <input type="text" name="email" value="{{Auth::user()->email}}" required class="form-control" placeholder="Email.." >
      </div>
    </div>
     
  </div>

  <div class="form-row align-items-center">
    <div class="col-md-6">
      <label > Contact Number</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text" style=""><i class="fa   fa-phone-square"></i></div>
        </div>
        <input type="text" class="form-control" id="inlineFormInputGroupuser" placeholder="Enter valid Contact Number"  required="" name="contact_no" value="{{Auth::user()->phone}}">
      </div>
    </div>
     <div class="col-md-6">
        <label>Address:</label>
        <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text" style=""><i class="fa fa-map"></i></div>
        </div>
        <input type="text" class="form-control" id="inlineFormInputGroupuser" placeholder="Address"  required="" name="address" value="">
      </div>
      </div>
  </div>

  <div class="form-row align-items-center">
    <div class="col-md-6">
      <label>Country</label>
       <select class="form-control" id="country" name="country" required>
           <option value="">Select Country</option>
           @foreach($countries as $country)
              <option value="{{$country->id}}" @if($country->id == '149') selected @endif>{{$country->name}}</option>
           @endforeach
          </select>
      </div>
     <div class="col-md-6">
        <label >Paid By:</label>
        <select class="form-control" id="paid_by" name="paid_by" required>
          <option value="reservation-only">Reservation Only</option>
          <option value="ipsconnect">IPSConnet</option>
          <option value="hbl">HBL</option>
        </select>
      </div>
  </div>   
  <br>

  <div class="row p-2">
    <div class="card" style="width: 100%;">
        <div class="card-header bg-default text-black">
         EXTRAS
        </div>
        <div class="card-body">

          <table class="table table-striped">
            <thead>
              <tr>
               
                <th scope="col">Item</th>
                <th scope="col">Price</th>
                <th scope="col">Order</th>
              </tr>
            </thead>
            <tbody>
             @if($tour_extras)
              @foreach($tour_extras as $te)
                <tr>
                  <td class="items">{{$te->item}}</td>
                  <td class="price">{{$te->price}}</td>
                  <input type="hidden" class="this_id" value="{{$te->id}}">
                  <td> 
                    <input type="checkbox" id="6" name="extras[]" value="{{$te->id}}">
                 </td>
                </tr>
              @endforeach
              @endif
            </tbody>
          </table>

        </div>
    </div>
  </div>


<div class="row p-2">
<div class="card" style="width: 100%;">
<div class="card-header  bg-danger text-white "> 
 GUEST INFORMATIONS
</div>
<div class="card-body">


<input type="hidden" name="adult_no" value="{{\Request::get('adults')}}">

<input type="hidden" name="child_no" value="{{\Request::get('childs')}}">

<input type="hidden" name="infant_no" value="{{\Request::get('infants')}}">
<input type="hidden" name="tour_date" value="{{\Request::get('tour_date')}}">


@for($pos = 0; $pos < $total ;$pos++ )
  <?php  ++$index ?>

  
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="formGroupExampleInput">Guest {{$pos + 1}} Name</label>
                <input type="text" class="form-control"  placeholder="Name" name="name[]" required="">
              </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="formGroupExampleInput">Guest {{$pos + 1}} Passport No</label>
                <input type="text" class="form-control"  placeholder="Passport" name="passport[]" required="">
              </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="formGroupExampleInput">Age</label>
                <input type="text" class="form-control" placeholder="Age" name="age[]" required="">
              </div>
        </div>
        
    </div>
  


@endfor

</div>
</div>
</div>


<div class="booking-complete">
<h2>Review and book your trip</h2>
<p>Voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui voluptatem sequi nesciunt. </p>
<button class="booking-complete-btn" >COMPLETE Reservation Only</button>
</div>


