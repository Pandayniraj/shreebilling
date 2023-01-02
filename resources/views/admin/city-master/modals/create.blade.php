@extends('layouts.dialog')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<div class="alert alert-danger alert-dismissable" style="display: none;" id="errormodal">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4>
            <i class="icon fa fa-ban"></i> Errors
        </h4>
        <div id="modalserrors">
        </div>
    </div>
<style>
    select { width:200px !important; }
label {
    font-weight: 600 !important;
}

 .intl-tel-input { width: 100%; }
 .intl-tel-input .iti-flag .arrow {border: none;}

#overlay{
  position: fixed;
  top: 0;
  z-index: 100;
  width: 100%;
  height:100%;
  display: none;
  background: rgba(0,0,0,0.6);
}
.cv-spinner {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}
.spinner {
  width: 40px;
  height: 40px;
  border: 4px #ddd solid;
  border-top: 4px #2e93e6 solid;
  border-radius: 50%;
  animation: sp-anime 0.8s infinite linear;
}
@keyframes sp-anime {
  100% {
    transform: rotate(360deg);
  }
}
.is-hide{
  display:none;
}

</style>

<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
     <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Create New City
        <small>
            New City
        </small>
    </h1>
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box-body">
            <div class="col-md-12">
                <div class="">
                    <form  class= 'form-horizontal' id = 'form_cities' >

                        <div class="">
                            <div class="clearfix"></div>
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <hr>
                                    <div class="col-md-12">

                                        <div class="col-sm-3 form-group" style="">
                                            <label for="product_type">County</label>
                                            <select  name="country"  class="form-control input-sm" required type="text">
                                                <option selected disabled >Select Country

                                                </option>
                                                @foreach($countries as $key=>$c)
                                                    <option value="{{$c}}" {{$country==$c?'selected':''}}>{{$c}}

                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>  <div class="col-sm-3 form-group" style="">
                                            <label for="user_id">City Name</label>
                                            <input  name="city"  class="form-control input-sm" placeholder="City Name" required type="text">
                                        </div>  <div class="col-sm-3 form-group" style="">
                                            <label for="user_id">Longitude</label>
                                            <input  name="lng"  class="form-control input-sm" placeholder="Longitude" type="text">
                                        </div>
                                        <div class="col-sm-3 form-group" style="">
                                            <label for="">Latitude</label>
                                            <input  name="lat"  class="form-control input-sm" placeholder="Latitude" type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-12">

                                        <div class="col-sm-3 form-group" style="">
                                            <label for="user_id">Iso 2</label>
                                            <input  name="iso2"  class="form-control input-sm" placeholder="ISO 2" type="text"/>

                                        </div>
                                        <div class="col-sm-3 form-group" style="">
                                            <label for="user_id">Iso 3</label>
                                            <input  name="iso3"  class="form-control input-sm" placeholder="ISO 3" type="text">
                                        </div>

                                    </div>





                                </div>
                            </div>
                        </div>


                        <div class="clearfix"></div><br /><br />

                        <br />

                        <div class="panel-footer footer">
                            <button class="btn btn-social btn-foursquare" id="btn-submit-edit">
                                <i class="fa fa-save"></i>Create City
                            </button>
                            <a class="btn btn-social btn-foursquare" onClick="window.close()"> <i class="fa fa-times"></i> Cancel </a>
                        </div>
                    </form>

                </div>
            </div>


        </div><!-- /.box-body -->
    </div><!-- /.col -->

</div><!-- /.row -->
<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
    <script type="text/javascript">
            $('#btn-submit-edit').click(function(){
              $("#overlay").fadeIn(300);　
              var obj ={};
              var data = JSON.stringify( $('#form_cities').serializeArray() ); //  <-----------
              var paramObj = {};
              $.each($('form').serializeArray(), function(_, kv) {
              paramObj[kv.name] = kv.value;
              });
              paramObj['_token']= $('meta[name="csrf-token"]').attr('content')
               $.post("/admin/cities/modals",paramObj,function(data,status){
                  if(status == 'success'){
                   var  result = data;
                   if(result.error){
                        $("#overlay").fadeOut(300);
                        $('#errormodal').css("display", "block")
                        let errors = Object.values(result.error);
                        errors = errors.flat();
                        err = '';
                        console.log(errors);
                        errors.forEach(function(value){
                            err = err + `<li>${value}</li>`;
                        })
                        $('#modalserrors').html(err);
                        $(window).scrollTop(0);
                        return false;
                   }
                    try  {
                    window.opener.HandlePopupResultForCities(result);
                    }
                    catch (err) {
                      console.log(err);
                    }
                  }else{
                    alert("Failed to save client Try Again!!");
                  }
                  $("#overlay").fadeOut(300);
                  window.close();
                  return false;
                });
              return false;
            });
    </script>
@endsection
