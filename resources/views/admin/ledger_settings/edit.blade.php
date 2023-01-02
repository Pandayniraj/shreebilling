@extends('layouts.master')

@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
   <div class='row'>
       <div class='col-md-12'>
          <div class="box">
         <div class="box-body ">
          <form method="post" action="{{route('admin.ledgers.setting.update',$ledger->id)}}" enctype="multipart/form-data">  
          {{ csrf_field() }}   
            <div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Table Name</label>
                        <div class="input-group">
                         <select class="form-control" id='table_name' name='table_name'>
                           <option value="coa_groups" >Coa Groups</option>
                           <option value="coa_ledgers" @if( isset($table_name) && $table_name == 'coa_ledgers') selected="" @endif>Coa Ledger</option>
                         </select>
                          <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-folder"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>

                 <div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Select Ledger</label>
                        <div class="input-group">
                         <select class="form-control searchable" id='ledger_or_groups' name='ledger_id' required="">
                          
                         </select>
                          <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-object-group"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>

                <div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Ledger Label</label>
                        <div class="input-group">
                          <input type="text" name="ledger_name" class="form-control" 
                          placeholder="eg:(CUSTOMER_LEDGER)" required="" value="{{$ledger->ledger_name}}">
                          <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-database"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>
      <div class="row">
        <div class="checkbox col-sm-12 form-group">
            <label><input type="checkbox" value="1" name='is_default' 
              @if($ledger->is_default) checked="" @endif >Is Default</label>
            (Can Be Extended When New Organization is created)
            </div>
         </div>

          <div class="row">
                     <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">Description</label>
                        <div class="input-group">
                          <textarea  name="description" class="form-control" 
                              placeholder="Enter Description...">{!! $ledger->description !!}</textarea>
                          <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-database"></i></a>
                            </div>
                        </div>
                     </div>   
                 </div>
               
                   <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                          <a href="{!! route('admin.ledgers.setting') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                      </div>
                   </div>
              </div>
          </form>
         </div>
     </div>
    </div>


    <div class="options" style="display: none;">
        <select id='coa_groups' class="form-control">
          <option value="">Select Ledger Groups</option>
          @foreach($coa_groups as $key=>$cg)
            <option value="{{$cg->id}}">{{$cg->name}}[{{$cg->id}}]</option>
          @endforeach
        </select>
        <select id='coa_ledger' class="form-control">
          <option value="">Select Ledger</option>
          @foreach($coa_legders as $key=>$cg)
            <option value="{{$cg->id}}">{{$cg->name}}[{{$cg->id}}]</option>
          @endforeach
        </select>
    </div>
    <script type="text/javascript">
      const tables = `{{$table}}`;
     
      function appendOptions(table){
        if(table == 'coa_groups'){
          let option = $('.options #coa_groups').html();
          $('#ledger_or_groups').html(option);
        }else{

          let option = $('.options #coa_ledger').html();
          $('#ledger_or_groups').html(option);
        }

      }
     appendOptions(tables);
     
     $('select#table_name').val(tables);
      $('select#table_name').change(function(){
        appendOptions($(this).val());
      });

      $('#ledger_or_groups').val(`{{$ledger->ledger_id}}`);
      setTimeout(function(){
        $('.searchable').select2();
      },100)
      
    </script>
@endsection