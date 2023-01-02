@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

{{-- <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" /> --}}

@section('content')

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
      {{ $page_title ?? 'Page Title' }}
        <small>{{$page_description ?? 'Page Description'}}</small>
    </h1>
   
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

 <div class='row'>
   <div class='col-md-12'>
      <div class="box">
         <div class="box-body">
        <form method="post" action="{{route('admin.darta.edit',$darta->id)}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">

                <div class="col-md-4 col-sm-12 form-group">
                    <label class="control-label">{{ trans('admin/darta/general.columns.darta_number') }}</label>
                    <div class="input-group">
                      <input type="text" name="darta_num" class="form-control input-sm" placeholder="Darta Number..." required="" value="{{$darta->darta_num}}">
                      <div class="input-group-addon">
                            <a href="#"><i class="fa  fa-sort-numeric-asc"></i></a>
                        </div>
                    </div>
                </div>

                 <div class="col-md-4 col-sm-12 form-group">
                    <label class="control-label">{{ trans('admin/darta/general.columns.received_letter_number') }}</label>
                    <div class="input-group">
                      <input type="text" name="received_letter_num" class="form-control  input-sm" placeholder="Received Letter Number..." value="{{$darta->received_letter_num}}">
                      <div class="input-group-addon">
                            <a href="#"><i class="fa  fa-sort-amount-desc"></i></a>
                        </div>
                    </div>
                </div>

                 <div class="col-md-4 col-sm-12 form-group">
                    <label class="control-label">{{ trans('admin/darta/general.columns.letter_date') }}</label>
                    <div class="input-group">
                      <input type="text" name="letter_date" class="form-control  input-sm datepicker" placeholder="Letter Date..." value="{{$darta->letter_date}}">
                      <div class="input-group-addon">
                            <a href="#"><i class="fa  fa-calendar"></i></a>
                        </div>
                    </div>
                </div>

            </div>



            <div class="row">

                <div class="col-md-4 col-sm-12 form-group">
                    <label class="control-label">{{ trans('admin/darta/general.columns.subject') }}</label>
                    <div class="input-group">
                      <input type="text" name="subject" class="form-control  input-sm" placeholder="Subject..." required="" value="{{$darta->subject}}">
                      <div class="input-group-addon">
                            <a href="#"><i class="fa fa-sticky-note-o"></i></a>
                        </div>
                    </div>
                </div>

                 <div class="col-md-4 col-sm-12 form-group">
                    <label class="control-label">{{ trans('admin/darta/general.columns.sending_office_name') }}</label>
                    <div class="input-group">
                        {!! Form::select('sender_office_name',$users,$darta->sender_office_name,['class'=>'form-control searchable','placeholder'=>'Select Sending Officer']) !!}
                      <div class="input-group-addon">
                            <a href="#"><i class="fa fa-user-plus"></i></a>
                        </div>
                    </div>
                </div>

                 <div class="col-md-4 col-sm-12 form-group">
                    <label class="control-label">{{ trans('admin/darta/general.columns.receiving_office_name') }}</label>
                    <div class="input-group">
                        {!! Form::select('receiving_officer_name',$users,$darta->receiving_officer_name,['class'=>'form-control searchable','placeholder'=>'Select Receiving Officer']) !!}
                      <div class="input-group-addon">
                            <a href="#"><i class="fa fa-user-secret"></i></a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                  <div class="col-md-4 col-sm-12 form-group">
                      <label class="control-label">{{ trans('admin/darta/general.columns.received_date') }}</label>
                    <div class="input-group">
                      <input type="text" name="received_date" class="form-control input-sm datepicker" placeholder="Received Date..." value="{{$darta->received_date}}">
                      <div class="input-group-addon">
                            <a href="#"><i class="fa   fa-calendar-o"></i></a>
                        </div>
                    </div>
                </div>

                 <div class="col-md-4 col-sm-12 form-group">
                    <label class="control-label">{{ trans('admin/darta/general.columns.remarks') }}</label>
                    <div class="input-group">
                      <input type="text" name="remarks" class="form-control  input-sm" placeholder="Remarks..." value="{{$darta->remarks}}">
                      <div class="input-group-addon">
                            <a href="#"><i class="fa   fa-pencil-square-o"></i></a>
                        </div>
                    </div>
                </div>

            </div>


        
            <div class="row">
            <div class="col-md-6 ">
                  <div class="more-tr">
                     <table class="table more table-hover table-no-border" style="width: 100%;" cellspacing="2">
                        <tbody style="float: left">
                          <thead>
                            <tr>
                              <th> <button class="btn  bg-maroon btn-xs" id='more-button' type="button"><i class="fa fa-plus"></i>  {{ trans('admin/ticket/general.form_header.add_more_file') }}</button></th>
                              <th colspan="2"></th>
                            </tr>
                          </thead>
                       
                           <tr class="multipleDiv-attachment" style="float: left">
                           </tr>

                           @foreach($dartaFile as $key=>$files)
                               <tr>
                              <td class="moreattachment" style=""> 
                             <a href="/darta/{{$files->attachment}}">   <i class="fa fa-paperclip"></i> {{mb_substr($files->attachment,0,20) }}...</a>
                              </td>
                              <td class="w-25" >
                                @if(is_array(getimagesize(public_path().'/darta/'.$files->attachment)))
                                <a href="/darta/{{$files->attachment}}" target="_blank">
                                 <img src="/darta/{{$files->attachment}}"  style="max-height: 100px;float: right;margin-left: 30px" class='uploads'>
                                </a>
                      
                                 @endif
                              </td>
                              <td >
                                 <a href="javascript:void()" style="font-size: 20px; float: right" class="remove-this-attachment-stored" data-id='{{$files->id}}'> <i class="fa fa-close deletable"></i></a>
                                <span class="deleting" style="display: none;">
                                 <i class="fa fa-spinner fa-spin"></i>&nbsp;Deleting
                               </span>
                              </td>
                           </tr>

                           @endforeach
                        </tbody>
                     </table>
                  </div>
              </div>
        </div>

         <div class="row">
                    <div class="col-md-12 ">

                        <div class="form-group pull-right">
                            {!! Form::submit( trans('admin/ticket/general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                            <a href="/admin/darta/" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                        </div>
                        </div>
                 </div>

    </form>


         </div>
        </div>
    </div>
</div>


<div id="morefiles" style="display: none">
   <table class="table">
      <tbody id="more-custom-tr">
         <tr>
            <td class="moreattachment" style=""> 
               <input type="file" name="attachment[]" class="attachment" >
            </td>
            <td class="w-25" >
               <img src=""  style="max-height: 100px;float: right;margin-left: 30px" class='uploads'>
            </td>
            <td >
               <a href="javascript:void()" style="font-size: 20px; float: right" class="remove-this-attachment"> <i class="fa fa-close deletable"></i></a>
            </td>
         </tr>
      </tbody>
   </table>
</div>

<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script type="text/javascript">

  
$('#more-button').click(function(){
       $(".multipleDiv-attachment").after($('#morefiles #more-custom-tr').html());
});
$('.datepicker').datetimepicker({
                    //inline: true,
                    //format: 'YYYY-MM-DD',
                    format: 'YYYY-MM-DD', 
                    sideBySide: true,
                    allowInputToggle: true
                });
    $(document).on('click','.remove-this-attachment',function(){
      $(this).parent().parent().remove();
    });

    const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
$(document).on('change','.attachment',function(){
  var input = this;
  // console.log('done');
   var parent = $(this).parent().parent();
      if (input.files && input.files[0]) {
        var fileType = input.files[0]['type'];
        var reader = new FileReader();
        reader.onload = function (e) {
          if (validImageTypes.includes(fileType)) {
            parent.find('.uploads')
                .attr('src', e.target.result)
                .width(150)
                .height(200);
            }
          else{
             parent.find('.uploads')
                .attr('src','')
                .width(0)
                .height(0);
          }
       
        };

        reader.readAsDataURL(input.files[0]);
    }
});

$('.searchable').select2();

$('.remove-this-attachment-stored').click(function(){

      var id = $(this).attr('data-id');
      var parent =  $(this).parent().parent();
      parent.find('.deleting').show();
      let c = confirm('Are You Sure You want to delete');
      if(c){

        $.get(`/admin/darta/delete-file/${id}`,function(response){
         parent.remove();
        }).fail(function(){
           parent.find('.deleting').hide();
        });
     

      }


     });
</script>
@endsection

