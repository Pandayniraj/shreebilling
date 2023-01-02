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
  <div class="alert alert-danger alert-dismissable" style="display: none;" id="errormodal">
        <button type="button" aria-hidden="true" class="close" onclick="$('#errormodal').slideUp(300)">×</button>
        <h4>
            <i class="icon fa fa-ban"></i> Errors
        </h4>
        <div id="modalserrors">
        </div>
    </div>
<div class="modal-content" id='product_modals'>
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Add Product
                <small>Create new Product</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>

          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
               <div class="col-md-12">
                <form id='form_submit' method="POST">
                  @csrf
                       <div class="content">

                        <div class="form-group">
                            {!! Form::label('name', trans('admin/courses/general.columns.name')) !!}
                            {!! Form::text('name', null, ['class' => 'form-control','required'=>'required', $readonly]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('ordernum', 'Ordernum') !!}
                            {!! Form::text('ordernum', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('cost', 'Purchase Pricing') !!}
                            {!! Form::text('cost', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('price', 'Sales Pricing') !!}
                            {!! Form::text('price', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('dollar_price', 'Dollar Pricing') !!}
                            {!! Form::text('dollar_price', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('alert_qty', 'Alert Quantity') !!}
                            {!! Form::number('alert_qty', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('product_code', 'Produc Code') !!}
                            {!! Form::text('product_code', null, ['class' => 'form-control', $readonly]) !!}
                        </div>

                        <div class="form-group">
                            <label>Select Product Unit</label>
                            {!! Form::select('product_unit', $product_unit, null, ['class' => 'form-control label-primary']) !!}
                        </div>

                        <div class="form-group">
                            <label>Select Outlet</label>
                            {!! Form::select('outlet_id', [''=>'Please Select']+$outlets, null, ['class' => 'form-control label-success','id'=>'outlet_id','required'=>'required']) !!}
                        </div>



                        <div class="form-group">
                            <label>Select Category</label>
                            {!! Form::select('category_id', $categories, null, ['class' => 'form-control label-primary']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('product_image', 'Product Image') !!}
                            <div class="col-sm-6">
                                <input type="file" name="product_image">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!! '<input type="hidden" name="enabled" value="0">' !!}
                                    {!! Form::checkbox('enabled', '1', $course->enabled) !!} {{ trans('general.status.enabled') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!! '<input type="hidden" name="public" value="0">' !!}
                                    {!! Form::checkbox('public', '1', $course->public) !!} Show this product in Public Forms
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Select Product Type Master</label>
                            {!! Form::select('product_type_id', $product_type_masters, null, ['class' => 'form-control label-primary']) !!}
                        </div>
                          </div>


                         <div class="form-group">
                           {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                           <a href="#" title="{{ trans('general.button.cancel') }}" class='btn btn-default' data-dismiss="modal" aria-hidden="true">{{ trans('general.button.cancel') }}</a>
                          </div>


              </form>
                </div>


            </div>
          </div>
        </div>
      </div>

<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
<script type="text/javascript">
    $('#product_modals #form_submit').submit(function(){
        $("#overlay").fadeIn(300);　
        var obj ={};
        var data = JSON.stringify( $('#form_clients').serializeArray() ); //  <-----------
        var paramObj = {};
        $.each($('#form_submit').serializeArray(), function(_, kv) {
        paramObj[kv.name] = kv.value;
        });
        paramObj['_token']= $('meta[name="csrf-token"]').attr('content');

        $.post(`{{ route('admin.products.store') }}`,paramObj,function(data,status){
          if(status == 'success'){
            $("#overlay").fadeOut(300);
           var  result = data;
           if(result.error){
                $("#overlay").fadeOut(300);
                $('#errormodal').css("display", "block")
                let errors = Object.values(result.error);
                errors = errors.flat();
                err = '';
                //console.log(errors);
                errors.forEach(function(value){
                    err = err + `<li>${value}</li>`;
                })
                $('#modalserrors').html(err);
                $(window).scrollTop(0);
                return false;
           }

            handleProductModel(result);

          }
        }).fail(function(){
            $("#overlay").fadeOut(300);
            $('#errormodal').slideDown(300);
            $('#modalserrors').html('<li>Server Error Try Again !!</li>');

        });
        return false;
      });
    $('#product_modals .searchable').select2({dropdownParent: $("#product_modals")});
</script>
