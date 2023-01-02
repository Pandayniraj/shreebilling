
  <style type="text/css">
    .ui-autocomplete {
        z-index:2147483647;
    }
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
<div>
<div id="productModel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Product Model</h4>
      </div>
         <form role="form" action="">
             {{csrf_field()}}
      <div class="modal-body">
        <div class="box-body">
          <div class="form-group">
            <label>Model Name</label>
            <input type="text" class="form-control product-model" name='model[]' placeholder="Enter Model Name.." required="">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
      </form>
    </div>

  </div>
</div>



<div id="productSerial" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Serial Number</h4>
      </div>
    <form role="form" action="">
             {{csrf_field()}}
      <div class="modal-body">
        <div class="box-body">
          <div class="form-group">
            <label>Serial Number</label>
            <input type="hidden" name='model_id[]' class="model_id">
            <input type="text" class="form-control product-model" name='serial_num[]' placeholder="Enter Serial Number.." required="">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
      </form>
    </div>

  </div>
</div>



<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>

</div>
<script type="text/javascript">
    $('#productModel form').submit(function(){
        $("#overlay").fadeIn(300);　
        var obj ={};
        var paramObj = {};
        $.each($(this).serializeArray(), function(_, kv) {
        paramObj[kv.name] = kv.value;
        });
        paramObj['_token']= $('meta[name="csrf-token"]').attr('content');
        let action = $(this).attr('action');
        console.log(paramObj,action);
        
        $.post(action,paramObj,function(data){
            handleProductModels(data);
            $("#overlay").fadeOut(300);
         
        }).fail(function(){
            $("#overlay").fadeOut(300);
            $('#errormodal').slideDown(300);
            $('#modalserrors').html('<li>Server Error Try Again !!</li>');

        });
        return false;
      }); 


       $('#productSerial form').submit(function(){
        $("#overlay").fadeIn(300);　
        var obj ={};
        var paramObj = {};
        $.each($(this).serializeArray(), function(_, kv) {
        paramObj[kv.name] = kv.value;
        });
        paramObj['_token']= $('meta[name="csrf-token"]').attr('content');
        let action = $(this).attr('action');
        console.log(paramObj,action);
        $.post(action,paramObj,function(data){
            $("#overlay").fadeOut(300);
            handleSerialModels(data);
          
         
        }).fail(function(){
            $("#overlay").fadeOut(300);
            $('#errormodal').slideDown(300);
            $('#modalserrors').html('<li>Server Error Try Again !!</li>');

        });
        return false;
      }); 
</script>