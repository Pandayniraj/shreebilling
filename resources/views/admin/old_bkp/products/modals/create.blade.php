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
                <form id='form_submit'>                   
                       <div class="content">

                            <div class="form-group">
                                {!! Form::label('name', trans('admin/courses/general.columns.name')) !!}
                                {!! Form::text('name', null, ['class' => 'form-control', $readonly]) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('ordernum', 'Ordernum') !!}
                                 {!! Form::text('ordernum', null, ['class' => 'form-control', $readonly]) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('cost', 'Purchase Pricing') !!}
                                

                                  <div class="input-group">
                                    <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                                      {!! Form::text('cost', null, ['class' => 'form-control', $readonly]) !!}
                                   
                                  </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('price', 'Sales Pricing') !!}
                                  <div class="input-group">
                                    <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                                        {!! Form::text('price', null, ['class' => 'form-control', $readonly]) !!}
                                   
                                  </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('alert_qty', 'Alert Quantity') !!}
                                {!! Form::number('alert_qty', null, ['class' => 'form-control', $readonly]) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('product_code', 'Product Code') !!}
                                {!! Form::text('product_code', null, ['class' => 'form-control', $readonly]) !!}
                            </div>

                            <div class="form-group">
                                <label>Select Supplier</label>
                                {!! Form::select('supplier_id', $supplier, null, ['class' => 'form-control label-default searchable','placeholder'=>'Select Supplier']) !!}
                            </div> 
                            <div class="form-group">
                                <label>Select Product Unit</label>
                                {!! Form::select('product_unit', $product_unit, null, ['class' => 'form-control label-primary']) !!}
                            </div> 

                            <div class="form-group">
                                <label>Select Category</label>
                                {!! Form::select('category_id', $categories, null, ['class' => 'form-control label-primary']) !!}
                            </div>
                            
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! '<input type="hidden" name="enabled" value="0">' !!}
                                        {!! Form::checkbox('enabled', '1', '1') !!} {{ trans('general.status.enabled') }}
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
                                <div class="checkbox"> 
                                    <label>
                                        {!! '<input type="hidden" name="never_deminishing" value="0">' !!}
                                        {!! Form::checkbox('never_deminishing', '1', $course->never_deminishing) !!} Never Diminishing
                                    </label>
                                </div>
                            </div>

                             <div class="form-group">
                                <div class="checkbox"> 
                                    <label>
                                        {!! '<input type="hidden" name="assembled_product" value="0">' !!}
                                        {!! Form::checkbox('assembled_product', '1', $course->assembled_product) !!} Assembled Product
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox"> 
                                    <label>
                                        {!! '<input type="hidden" name="component_product" value="0">' !!}
                                        {!! Form::checkbox('component_product', '1', $course->component_product) !!} Component Product
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                              <div class="checkbox"> 
                                  <label>
                                      {!! '<input type="hidden" name="is_fixed_assets" value="0">' !!}
                                      {!! Form::checkbox('is_fixed_assets', '1', $course->is_fixed_assets) !!} Fixed Assets
                                  </label>
                              </div>
                          </div>
                          

                        </div><!-- /.content -->

                         <div class="form-group">
                           {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit','type'=>'Submit'] ) !!}
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
  
   
</script>