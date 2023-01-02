 <script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyBH1VwCF9e5KWE-_C9zl-7odmf4sTgWOgw&libraries=places'></script>
 <script src="/location-picker/locationpicker.jquery.js"></script>


 <div class="modal fade bd-example-modal-lg show" id="myModalLarge" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style='postion: absolute;'>
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <form method="POST" action="{!! route('register') !!}" enctype="multipart/form-data" >
                 {!! csrf_field() !!}

                 <div class="modal-header">
                     <h5 class="modal-title h4" id="myLargeModalLabel">Sign Up</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">×</span>
                     </button>
                 </div>
                 <div class="modal-body">

                     <div class="panel panel-default">
                         <div class="panel-heading reg-modal-panel-header">
                             <h5 class="panel-title text-uppercase">General Information</h5>
                         </div>


                         <div class="panel-body">
                             <!-- Name, Gender-->
                             <div class="row">
                                 <div class="form-group col-md-2">
                                     <select class="form-control" name="title" id="reg_title">
                                         <option value="Mr">Mr</option>
                                         <option value="Mrs">Mrs</option>
                                         <option value="Ms">Ms</option>

                                     </select>
                                 </div>

                                 <div class="form-group col-md-4">
                                     <input type="text" id="first_name" name="first_name" class="form-control" placeholder="First name" value="{{ old('first_name') }}" required autofocus />
                                 </div>

                                 <div class="form-group col-md-4">
                                     <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last name" value="{{ old('last_name') }}" required />
                                 </div>

                                 <div class="form-group col-md-2">
                                     <select class="form-control" name="gender" id="reg_gender">
                                         <option value="Male" selected="">Male</option>
                                         <option value="Female">Female</option>
                                     </select>
                                 </div>
                             </div>

                             <!-- Email, DOB -->
                             <div class="row">
                                 <div class="form-group col-md-4">
                                     <input type="text" id="username" name="username" class="form-control" placeholder="User name" value="{{ old('username') }}" required />
                                 </div>

                                 <div class="form-group col-md-5">
                                     <input type="text" id="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required />
                                 </div>

                                 <div class="form-group col-md-3">
                                     <input type="text" class="form-control" name="dob" id="reg_dob" placeholder="Date Of Birth" onfocus="(this.type='date')" onblur="(this.type='text')" value="">
                                 </div>
                             </div>
                             <div class="row">

                             </div>
                         </div>
                     </div>
                     <div class="panel panel-default">
                         <div class="panel-heading reg-modal-panel-header">
                             <h5 class="panel-title">
                                 <span class="text-uppercase">Upload Passport / Citizenship</span>
                                 <small class="text-max-filesize">(Max. file size 2MB)</small>
                             </h5>
                         </div>

                         <div class="panel-body">
                             <div class="row">
                                 <div class="form-group col-md-3">
                                     <select class="form-control" name="document_type" id="reg_document_type">
                                         <option value="passport" selected="">Passport</option>
                                         <option value="citizenship">Citizenship</option>
                                     </select>
                                 </div>

                                 <div class="form-group col-md-3">
                                     <div>
                                         <label for="reg_document_image" class="signup-upload-doc" id="upload-reg-doc"><i class="fas fa-upload" aria-hidden="true"></i> Upload Passport</label>
                                         <input type="file" name="document_image" id="reg_document_image" class="form-control" style="display: none;">
                                         <small class="truncate-text file_name"></small>
                                     </div>
                                     <div id="container-citizenship-back" style="margin-bottom: -20px;display: none;">
                                         <label for="citizenship_image_back" class="signup-upload-doc"><i class="fas fa-upload" aria-hidden="true"></i> Citizenship Back</label>
                                         <input type="file" name="citizenship_image_back" id="citizenship_image_back" class="form-control" style="display: none;">
                                         <small class="truncate-text file_name"></small>

                                     </div>  
                                 </div>

                                 <div class="form-group col-md-3">
                                     <input type="text" class="form-control" name="document_number" id="reg_document_number" placeholder="Passport Number" autocomplete="off">
                                 </div>

                                 <div class="form-group col-md-3">
                                     <input type="text" class="form-control" name="document_issue" id="reg_document_issue" placeholder="Expiry Date" onfocus="(this.type='date')" onblur="(this.type='text')">
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="panel panel-default">
                         <div class="panel-heading reg-modal-panel-header ">
                             <h3 class="panel-title text-uppercase">Current Address</h3>
                         </div>
                         <div class="panel-body">

                             <div class="row">
                                 <div class="form-group col-md-12">
                                     <input type="text" name="present_address" class="form-control mapControls pac-target-input" id="reg-pac-input" required="" placeholder="Address" autocomplete="off">
                                 </div>
                                 <div id="us3" style="width: 100%; height: 250px;"></div>

                             </div>
                             <br>
                             <div class="row">
                                 <div class="form-group col-md-4">
                                     <input type="text" class="form-control" name="street" id="reg_street" placeholder="Street Name" autocomplete="off">
                                 </div>
                                 <div class="form-group col-md-4">
                                     <input type="text" class="form-control" name="ward_no" id="ward_no" placeholder="Ward No." autocomplete="off">
                                 </div>
                                 <div class="form-group col-md-4">
                                     <input type="text" class="form-control" name="municipality" id="municipality" placeholder="Municipality" autocomplete="off">
                                 </div>

                                 <div class="form-group col-md-4">
                                     <select name="district_id" class="form-control" required="" id="district_id">
                                         <option value="">Select District</option>
                                         <option value="69">Achham</option>
                                         <option value="47">Arghakhanchi</option>
                                         <option value="36">Baglung</option>
                                         <option value="70">Baitadi</option>
                                         <option value="71">Bajhang</option>
                                         <option value="72">Bajura</option>
                                         <option value="48">Banke</option>
                                         <option value="15">Bara</option>
                                         <option value="49">Bardiya</option>
                                         <option value="23">Bhaktapur</option>
                                         <option value="1">Bhojpur</option>
                                         <option value="24">Chitwan</option>
                                         <option value="73">Dadeldhura</option>
                                         <option value="59">Dailekh</option>
                                         <option value="50">Dang</option>
                                         <option value="74">Darchula</option>
                                         <option value="25">Dhading</option>
                                         <option value="2">Dhankuta</option>
                                         <option value="16">Dhanusa</option>
                                         <option value="26">Dolakha</option>
                                         <option value="60">Dolpa</option>
                                         <option value="75">Doti</option>
                                         <option value="37">Gorkha</option>
                                         <option value="51">Gulmi</option>
                                         <option value="61">Humla</option>
                                         <option value="3">Ilam</option>
                                         <option value="62">Jajarkot</option>
                                         <option value="4">Jhapa</option>
                                         <option value="63">Jumla</option>
                                         <option value="76">Kailali</option>
                                         <option value="64">Kalikot</option>
                                         <option value="77">Kanchanpur</option>
                                         <option value="52">Kapilvastu</option>
                                         <option value="38">Kaski</option>
                                         <option value="27">Kathmandu</option>
                                         <option value="28">Kavre</option>
                                         <option value="5">Khotang</option>
                                         <option value="29">Lalitpur</option>
                                         <option value="39">Lamjung</option>
                                         <option value="17">Mahottari</option>
                                         <option value="30">Makwanpur</option>
                                         <option value="40">Manang</option>
                                         <option value="6">Morang</option>
                                         <option value="65">Mugu</option>
                                         <option value="41">Mustang</option>
                                         <option value="42">Myagdi</option>
                                         <option value="43">Nawalparasi District (east of Bardaghat Susta)</option>
                                         <option value="53">Nawalparasi District (west of Bardaghat Susta)</option>
                                         <option value="31">Nuwakot</option>
                                         <option value="7">Okhaldhunga</option>
                                         <option value="54">Palpa</option>
                                         <option value="8">Panchthar</option>
                                         <option value="44">Parbat</option>
                                         <option value="18">Parsa</option>
                                         <option value="55">Pyuthan</option>
                                         <option value="32">Ramechhap</option>
                                         <option value="33">Rasuwa</option>
                                         <option value="19">Rautahat</option>
                                         <option value="56">Rolpa</option>
                                         <option value="57">Rukum</option>
                                         <option value="66">Rukum District (western part)</option>
                                         <option value="58">Rupandehi</option>
                                         <option value="67">Salyan</option>
                                         <option value="9">Sankhuwasabha</option>
                                         <option value="20">Saptari</option>
                                         <option value="21">Sarlahi</option>
                                         <option value="34">Sindhuli</option>
                                         <option value="35">Sindhupalchowk</option>
                                         <option value="22">Siraha</option>
                                         <option value="10">Solukhumbu</option>
                                         <option value="11">Sunsari</option>
                                         <option value="68">Surkhet</option>
                                         <option value="45">Syangja</option>
                                         <option value="46">Tanahu</option>
                                         <option value="12">Taplejung</option>
                                         <option value="13">Terhathum</option>
                                         <option value="14">Udayapur</option>
                                     </select>
                                     <small class="help-block text-danger"></small>
                                 </div>

                                 <div class="form-group col-md-4">
                                     <select name="province_id" class="form-control" required="" id="province_id">
                                         <option value="">Select Province</option>
                                         <option value="1">Province 1</option>
                                         <option value="2">Province 2</option>
                                         <option value="3">Province 3</option>
                                         <option value="4">Province 4</option>
                                         <option value="5">Province 5</option>
                                         <option value="6">Province 6</option>
                                         <option value="7">Province 7</option>
                                     </select>
                                     <small class="help-block text-danger"></small>
                                 </div>

                                 <div class="form-group col-md-4">
                                     <select class="form-control" name="country_id" id="reg_country">
                                         <option value="">Select Country</option>
                                         @foreach($countries_register as $cr)
                                         <option value="{{$cr->id}}" @if($cr->name == "NEPAL") selected @endif>{{$cr->name}}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="panel panel-default">
                         <div class="panel-heading reg-modal-panel-header">
                             <h5 class="panel-title text-uppercase">Contact Details</h5>
                         </div>
                         <div class="panel-body">
                             <div class="row">
                                 <div class="form-group col-md-4">
                                     <input type="text" class="form-control" name="phone" id="reg_contact_no" placeholder="Mobile Number" autocomplete="off">
                                 </div>

                                 <div class="form-group col-md-4 mt5">
                                     How can we contact you ?
                                 </div>

                                 <div class="form-group col-md-2 mt5">
                                     <input type="checkbox" id="reg_whatsapp" name="whatsapp" value="1">
                                     <label class="checkbox-inline" for="reg_whatsapp">
                                         WhatsApp
                                     </label>
                                 </div>

                                 <div class="form-group col-md-2 mt5">
                                     <input type="checkbox" id="reg_viber" name="viber" value="1">
                                     <label class="checkbox-inline" for="reg_viber">
                                         Viber
                                     </label>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="panel panel-default">
                         <div class="panel-heading reg-modal-panel-header">
                             <h5 class="panel-title">Password</h5>
                         </div>
                         <div class="panel-body">
                             <div class="row">
                                 <div class="form-group col-md-3">
                                     <input type="password" id="password" name="password" class="form-control" placeholder="Password" required />
                                 </div>
                                 <div class="form-group col-md-3">
                                     <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password" required />
                                 </div>
                             </div>
                         </div>
                     </div>

                 </div>
                 <div class="modal-footer">
                     {{-- <button type="button" class="btn btn-secondary cross" data-dismiss="modal">Close</button> --}}
                     <button type="submit" class="btn btn-danger">Register</button>
                 </div>
             </form>
         </div>
     </div>
 </div>

 <script type="text/javascript">
     $('#reg_document_type').change(function() {
         let type = $(this).val();
         if (type == 'citizenship') {
             $('#upload-reg-doc').text('Citizenship Front');
             $('#container-citizenship-back').show();
             $("#reg_document_number").attr("placeholder", "Citizenship Number");
             $("#reg_document_issue").attr("placeholder", "Issue Date");


         } else {
             $('#upload-reg-doc').text('Upload Passport');
             $('#container-citizenship-back').hide();
             $("#reg_document_number").attr("placeholder", "Passport Number");
             $("#reg_document_issue").attr("placeholder", "Expiry Date");


         }


     });

     $('#reg_document_image,#citizenship_image_back').change(function(e) {
         var fileName = e.target.files[0].name;
         $(this).next('small.file_name').text(fileName);

     })
     $(document).ready(function() {
         $('#us3').locationpicker({
             location: {
                 latitude: 27.720022
                 , longitude: 85.3163449
             }
             , radius: 0
             , inputBinding: {

                 locationNameInput: $('#reg-pac-input')
             },

             enableAutocomplete: true,

             addressFormat: 'sublocality'
         });
         $('#location-display').hide();

     });

 </script>
