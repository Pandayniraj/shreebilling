@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

    <style>
        .panel .mce-panel {
            border-left-color: #fff;
            border-right-color: #fff;
        }

        .panel .mce-toolbar,
        .panel .mce-statusbar {
            padding-left: 20px;
        }

        .panel .mce-edit-area,
        .panel .mce-edit-area iframe,
        .panel .mce-edit-area iframe html {
            padding: 0 10px;
            min-height: 350px;
        }

        .mce-content-body {
            color: #555;
            font-size: 14px;
        }

        .panel.is-fullscreen .mce-statusbar {
            position: absolute;
            bottom: 0;
            width: 100%;
            z-index: 200000;
        }

        .panel.is-fullscreen .mce-tinymce {
            height:100%;
        }

        .panel.is-fullscreen .mce-edit-area,
        .panel.is-fullscreen .mce-edit-area iframe,
        .panel.is-fullscreen .mce-edit-area iframe html {
            height: 100%;
            position: absolute;
            width: 99%;
            overflow-y: scroll;
            overflow-x: hidden;
            min-height: 100%;
        }

.footer {
   position: fixed;
   left: 0;
   bottom: 0;
   width: 100%;
   background-color: #efefef;
   color: white;
   text-align: center;
}

        
    </style>
@endsection

@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {{ $page_title ?? "Page Title"}} 
                     <small> {{ $page_description ?? "Page Description" }}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>


   <div class='row'>
        <div class='col-md-12'> 
            <div class="box box-header">
                <div id="orderFields" style="display: none;">
                    <table class="table">
                        <tbody id="more-tr">
                            <tr>
                                <td>

                                  <select class="form-control product_id hiddensearchable" name="product_id[]" required="required">
                                       <option value="">Select Product</option>
                                      @foreach($products as $key => $pk)
                                          <option value="{{ $pk->id }}"@if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                      @endforeach
                                  </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control availablity" name="availablity[]" placeholder="Available" value="0" required="required">
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" name="quantity[]" placeholder="Quantity" min="1" value="1" required="required">
                                </td>
                                
                                <td>
                                    <input type="text" class="form-control total" name="reason[]" placeholder="REason" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" style="float:left; width:80%;">
                                    <a href="javascript::void(1);" style="width: 10%;">
                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

              
                <div class="col-md-12">
                    <div class="">
                        <form method="POST" action="/admin/location/stocktransfer/{{$locationstocktransfer->id}}">
                            {{ csrf_field() }}

                            <div class="">

                             <div class="clearfix"></div>
                                <div class="col-md-12">
                              
                           <div class="col-md-12">

                                <div class="col-md-3 form-group" style="">
                                    <label for="comment">Source </label>
                                    {!! Form::select('source_id',  $locations, $locationstocktransfer->source_id, ['class' => 'form-control input-sm', 'id'=>'source_id']) !!}
                                </div>

                                <div class="col-md-3 form-group" style="">
                                    <label for="user_id"> <i class="fa fa-user"></i> User</label>
                                    {!! Form::select('user_id',  $users,$locationstocktransfer->user_id, ['class' => 'form-control input-sm', 'id'=>'user_id']) !!}
                                </div>

                                <div class="col-md-3 form-group" style="">
                                    <label for="user_id">Status</label>
                                    {!! Form::select('status',['parked'=>'Parked','completed'=>'Completed'],$locationstocktransfer->status, ['class' => 'form-control input-sm', 'id'=>'status']) !!}
                                </div>

                                <div class="col-md-3 form-group" style="">
                                    <label for="comment">Trasnsfer Date </label>
                                      <input type="text" class="form-control input-sm pull-right datepicker" name="transfer_date" value="{{$locationstocktransfer->transfer_date}}" id="bill_date">
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="col-md-3 form-group" style="">
                                    <label for="comment">Destination </label>
                                     {!! Form::select('destination_id',  $locations, $locationstocktransfer->destination_id, ['class' => 'form-control input-sm', 'id'=>'destination_id']) !!}
                                </div>
                            </div>

                                <div class="clearfix"></div><br/><br/>

                                    <div class="col-md-12">
                                        <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMore" style="float: right;">
                                            <i class="fa fa-plus"></i> <span>Add Products Item</span>
                                        </a>
                                    </div>


                                <hr/>
                                <table class="table table-striped">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th>Item Description *</th>
                                            <th>Available</th>
                                            <th>Quantity *</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @if($locationstocktransferdetail)
                                        @foreach($locationstocktransferdetail as $lst)

                                        <tr>
                                            <td>
                                              <select class="form-control product_id hiddensearchable select2" name="product_id[]" required="required">
                                                   <option value="">Select Product</option>
                                                  @foreach($products as $key => $pk)
                                                      <option value="{{ $pk->id }}"@if(isset($lst->product_id) && $lst->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                                  @endforeach
                                              </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control availablity" name="availablity[]" placeholder="Available" value="0" required="required">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity" name="quantity[]" placeholder="Quantity" min="1" value="{{$lst->quantity}}" required="required">
                                            </td>
                                            
                                            <td>
                                                <input type="text" class="form-control total" name="reason[]" placeholder="Reason" value="{{$lst->reason}}" style="float:left; width:80%;">
                                                <a href="javascript::void(1);" style="width: 10%;">
                                                    <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach

                                        @endif


                                        <tr class="multipleDiv">
                                           
                                        </tr>
                                    </tbody>

                                
                                </table>

                                <br/>

                                <div class="box">
                                   <div class="box-header with-border">

                                <div class="col-md-6 form-group" style="margin-top:5px;">
                                    <label for="comment">Remarks </label>
                                    <small class="text-muted"></small>
                                    <textarea class="form-control TextBox comment" name="comment">@if(isset($locationstocktransfer->comment)){{ $locationstocktransfer->comment }}@endif</textarea>
                                </div>

                            </div></div>


                            </div>
                            <div class="panel-footer footer">
                                <button type="submit" class="btn btn-social btn-foursquare">
                                    <i class="fa fa-save"></i>Save {{ $_GET['type']}} 
                                </button>

                                <a class="btn btn-social btn-foursquare" href="/admin/orders/?type={{ $_GET['type']}}"> <i class="fa fa-times"></i> Cancel </a>

                            </div>
                        </form>
                    </div>
                </div>

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_bug_edit_form_js')

    <script type="text/javascript">

    // $(function() {
    //     $('.datepicker').datetimepicker({
    //       //inline: true,
    //       format: 'YYYY-MM-DD',
    //       sideBySide: true,
    //       allowInputToggle: true
    //     });

    //   });
  </script>

<script>


$("#addMore").on("click", function () {
 
    $(".multipleDiv").after($('#orderFields #more-tr').html());
    $(".multipleDiv").next('tr').find('select').select2({ width: '100%' });
});



</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });

    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        });

      });

    $(document).on('click', '.remove-this', function () {
    $(this).parent().parent().parent().remove();
    calcTotal();
});
   

</script>

<script type="text/javascript">

    $(document).on('change', '.product_id', function() {
        var parentDiv = $(this).parent().parent();
        $.ajax(
            {
             url: "/admin/getStockAvailability",  
             data: { product_id: $(this).val(),source_id:$('#source_id').val() }, 
             dataType: "json", 
                success: function( data ) { 
                    var available = data.available;
                    $(this).val(parentDiv.find('.availablity').val(available));
                } 
            }); 


    });





    $(document).on('change','#source_id',function(){

        $('.multipleDiv').nextAll('tr').remove();

    });


     $(document).on('change', '.quantity', function() {

        var parentDiv = $(this).parent().parent();

         $.ajax(
            {
             url: "/admin/getStockAvailability",  
             data: { product_id: parentDiv.find('.product_id').val(),source_id:$('#source_id').val() }, 
             dataType: "json", 
                success: function( data ) { 
                    var available = data.available;
                    $(this).val(parentDiv.find('.availablity').val(available));
                    
                } 
            }); 

        if(this.value != ''){

            var availablity =parseInt( parentDiv.find('.availablity').val());

            if(parseInt(this.value) > availablity){
                  $(this).val(availablity); 

                alert("Stock Quantity Cannot Be Greater Than Available Quantity");  
               
            }    
        }
       
    });
</script>
@endsection
