<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Organization Name<i class="imp">*</i></label>
                    <input type="text" class="form-control ticket" name="organization_name" placeholder="Organization Name" value="@if(isset($organizations->organization_name)){{ $organizations->organization_name }}@endif" required="required">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>VAT ID<i class="imp">*</i></label>
                    <input type="text" class="form-control ticket" name="vat_id" placeholder="VAT ID" value="@if(isset($organizations->vat_id)){{ $organizations->vat_id }}@endif" required="required">

                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                  <label>TP ID<i class="imp">*</i></label>
                  <input type="text" class="form-control ticket" name="tpid" placeholder="TP ID" value="@if(isset($organizations->tpid)){{ $organizations->tpid }}@endif" required="required">

              </div>
          </div>
      </div> --}}
      <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Address<i class="imp">*</i></label>
                <input type="text" class="form-control ticket" name="address" placeholder="Address" value="@if(isset($organizations->address)){{ $organizations->address }}@endif" required="required">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Phone No<i class="imp">*</i></label>
                <input type="text" class="form-control ticket" name="phone" placeholder="Phone No" value="@if(isset($organizations->phone)){{ $organizations->phone }}@endif" required="required">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Email<i class="imp">*</i></label>
                <input type="text" class="form-control ticket" name="email" placeholder="Email" value="@if(isset($organizations->email)){{ $organizations->email }}@endif" required="required">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Website</label>
                <input type="text" class="form-control ticket" name="website" placeholder="Website" value="@if(isset($organizations->website)){{ $organizations->website }}@endif" required="required">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="logo" class="col-sm-6 btn btn-default control-label">
                 <i class="fa fa-file"></i> Choose Logo Image 
                 <input type="file" name="logo" style="display: none;" id='logo'>
             </label>
             <div class="col-sm-10">

                @if( isset($organizations) && $organizations->logo != '' )
                <label>Current Logo: </label><br />
                <img style="" src="{{ '/org/'.$organizations->logo }}" class="uploads" >
                @else
                <img style="width:300px;" src="" class="uploads">
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="stamp" class="col-sm-6 btn btn-default control-label">
             <i class="fa fa-file"></i> Choose Stamp/Signature 
             <input type="file" name="stamp" style="display: none;" id='stamp'>
         </label>
         <div class="col-sm-10">

            @if( isset($organizations) && $organizations->stamp != '' )
            <label>Current Stamp/Signature: </label><br />
            <img style="width:150px;" src="{{ '/org/'.$organizations->stamp }}" class="uploadstamp" >
            @else
            <img style="width:150px;" src="" class="uploadstamp">
            @endif
        </div>
    </div>
</div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="login_bg" class="col-sm-6 btn btn-default control-label">
             <i class="fa fa-file"></i>Login Image (760X1280)
             <input type="file" name="login_bg" style="display: none;" id='login_bg' accept="image/*">
         </label>
         <div class="col-sm-10">

            @if( isset($organizations) && $organizations->login_bg != '' )
            <label>Current Background: </label><br />
            <img style="height: 100px;" src="{{ '/org/'.$organizations->login_bg }}" class="uploadsbg" 
            >
            @else
            <img style="width:300px;max-height: 100px;" src="" class="uploadsbg">
            @endif
        </div>
    </div>
</div>
</div>

<div class="form-group">
    <label>Enabled<i class="imp">*</i></label>
    <input type="checkbox" name="enabled" 
    value="@if(isset($organizations) && $organizations->enabled) {{ $organizations->enabled }} @endif" @if(isset($organizations) && $organizations->enabled) checked @endif>
</div>
</div>
</div><!-- /.content -->
<script type="text/javascript">
    const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
    var _URL = window.URL || window.webkitURL;
    function clearImg(){

        $('#logo').val('');

        $('img.uploads').attr('src','');
    }
    $('#logo').change(function(ev){
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            var fileType = this.files[0]['type'];
            let size = this.files[0]['size']/1024/1024;

            if(size > 5){
                clearImg();
                alert("Plase upload Image below 5MB");
                return false;
            }

            if(!validImageTypes.includes(fileType)){
                clearImg();
                alert("Plase upload Valid Image");
                return false;
            }
            var objectUrl = _URL.createObjectURL(file);
            img.onload = function () {

                if( !(this.width ==  190 && this.height == 70)){
                    clearImg();
                    alert("Image size must be of 190px in width & 70px in height");
                    return false;
                }
                $('img.uploads').attr('src',objectUrl);
                _URL.revokeObjectURL(objectUrl);
            };
            img.src = objectUrl;
        }
    });

    $('#login_bg').change(function(ev){
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            var fileType = this.files[0]['type'];
            let size = this.files[0]['size']/1024/1024;

            if(size > 5){
                clearImg();
                alert("Plase upload Image below 5MB");
                return false;
            }

            if(!validImageTypes.includes(fileType)){
                clearImg();
                alert("Plase upload Valid Image");
                return false;
            }
            var objectUrl = _URL.createObjectURL(file);
            img.onload = function () {


                $('img.uploadsbg').attr('src',objectUrl);
                _URL.revokeObjectURL(objectUrl);
            };
            img.src = objectUrl;
        }
    });

    $('#stamp').change(function(ev){
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            var fileType = this.files[0]['type'];
            let size = this.files[0]['size']/1024/1024;

            if(size > 5){
                clearImg();
                alert("Plase upload Image below 5MB");
                return false;
            }

            if(!validImageTypes.includes(fileType)){
                clearImg();
                alert("Plase upload Valid Image");
                return false;
            }
            var objectUrl = _URL.createObjectURL(file);
            img.onload = function () {


                $('img.uploadstamp').attr('src',objectUrl);
                _URL.revokeObjectURL(objectUrl);
            };
            img.src = objectUrl;
        }
    });
</script>