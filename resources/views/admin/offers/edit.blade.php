@extends('layouts.master')

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    <link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />

<script src='{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}'></script>
    {{-- Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body ">
            <form method="post" action="{{route('admin.offers.edit',$offers->id)}}" enctype="multipart/form-data">
                @csrf
           <div class="row">
              <div class="col-md-12 col-sm-12 form-group">
                 <label class="control-label">Offer Title</label>
                 <div class="input-group">
                    <input type="text" name="offer_title" class="form-control offer_title" placeholder="Offer Title" required="" value="{{$offers->offer_title}}">
                    <div class="input-group-addon">
                       <a href="#"><i class="fa  fa-sticky-note-o"></i></a>
                    </div>
                 </div>
              </div>
           </div>
           <div class="row">
              <div class="col-md-12 col-sm-12 form-group">
                 <label class="control-label">Offer Slug</label>
                 <div class="input-group">
                    <input type="text" name="offer_slug" class="form-control offer_slug" placeholder="Offer Title" required="" value="{{$offers->offer_slug}}">
                    <div class="input-group-addon">
                       <a href="#"><i class="fa   fa-code"></i></a>
                    </div>
                 </div>
              </div>
           </div>
           <div class="row">
              <div class="col-md-4 col-sm-6 col-xs-6 form-group">
                 <label class="control-label">Phone</label>
                 <div class="input-group">
                    <input type="text" name="phone" class="form-control" placeholder="Phone" value="{{$offers->phone}}" >
                    <div class="input-group-addon">
                       <a href="#"><i class="fa   fa-phone"></i></a>
                    </div>
                 </div>
              </div>
              <div class="col-md-4 col-sm-6  col-xs-6 form-group">
                 <label class="control-label">Email</label>
                 <div class="input-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{$offers->email}}" >
                    <div class="input-group-addon">
                       <a href="#"><i class="fa  fa-envelope-o"></i></a>
                    </div>
                 </div>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-6 form-group">
                 <label class="control-label">Offer Price</label>
                 <div class="input-group">
                    <input type="text" name="offer_price" class="form-control" placeholder="Offer Price" required="" value="{{$offers->offer_price}}">
                    <div class="input-group-addon">
                       <a href="#"><i class="fa   fa-money"></i></a>
                    </div>
                 </div>
              </div>
              <div class="col-md-4 col-sm-6  col-xs-6 form-group">
                 <label class="control-label">Available From</label>
                 <div class="input-group">
                    <input type="text" name="available_from" class="form-control datepicker" placeholder="Available From" required="" value="{{$offers->available_from}}">
                    <div class="input-group-addon">
                       <a href="#"><i class="fa   fa-calendar"></i></a>
                    </div>
                 </div>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-6 form-group">
                 <label class="control-label">Available To</label>
                 <div class="input-group">
                    <input type="text" name="available_to" class="form-control datepicker" placeholder="Available To" required="" value="{{$offers->available_to}}">
                    <div class="input-group-addon">
                       <a href="#"><i class="fa    fa-calendar"></i></a>
                    </div>
                 </div>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-6 form-group">
                 <label class="control-label">Offer Sequence</label>
                 <div class="input-group">
                    <input type="text" name="order_sequence" class="form-control" placeholder="Enter a offer squence based on priority" required="" value="{{$offers->order_sequence}}">
                    <div class="input-group-addon">
                       <a href="#"><i class="fa   fa-reorder"></i></a>
                    </div>
                 </div>
              </div>
           </div>
           <div class="row">
              <div class="col-md-4 col-sm-6 form-group">
                 <label class="control-label">Offer Images</label>
                 <input type="file" name="offer_images"  accept="image/*">
                 <label class="control-label">Current Images</label><br>
                 <a href="{{ '/offers_images/'.$offers->offer_images}}" target="_blank"> <img src="{{ '/offers_images/'.$offers->offer_images}}" width="120" height="120" /></a>
              </div>
              <div class="col-md-4 col-sm-6 form-group">
                 <div class="checkbox" style="margin-top: 20px;">
                    <label><input type="checkbox" value="" name="enabled" @if($offers->enabled) checked="" @endif>Enable</label>
                 </div>
              </div>
           </div>
           <div class="row">
              <div class="col-md-12 col-sm-12 form-group">
                 <label class="control-label">Description</label>
                 <textarea  name="description" class="form-control" placeholder="Template Descriptions" style="width: 100%" rows="20" id='body'>{!! $offers->description !!}</textarea>
              </div>
           </div>

           <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                    <a href="{!! route('admin.offers.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                </div>
            </div>
            </div>
        </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $('textarea').each(function(){
            $(this).val($(this).val().trim());
        }
    );
      $('.form-horizontal').css('visibility','visible');
      $('textarea#body').wysihtml5();

  $('.datepicker').datetimepicker({
          //inline: true,
          //format: 'YYYY-MM-DD',
          format: 'YYYY-MM-DD', 
              sideBySide: true,
              allowInputToggle: true
        });

    function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}

$('.offer_title').keyup(function(){

  $('.offer_slug').val(convertToSlug($(this).val()));

});
</script>
@endsection