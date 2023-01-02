@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title !!}
                <small>{!! $page_description !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

  <div class='row'>
        <div class='col-md-12'>
            <div class="box">
                <div class="box-body">
                   <form action="/admin/costcenter/{{ $costcenter->id }}" method="post">

                    {{ csrf_field() }}
            
                <div class="content col-md-9">

                  <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-3">Name</label>
                              <div class="input-group ">

                                <input type="text" name="name" id="" value="{{ $costcenter->name }}" placeholder="Name" class="form-control " required="required"> 
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar-alt"></i></a>
                                </div>
                              </div>
                            </div>
                         </div>
                        <div class="col-md-6">
                            <div class="form-group">  
                                  <label class="control-label col-sm-3">Owner Name</label>
                                 <div class="col-md-9">
                                  {!! Form::select('owner_id', $owners, $costcenter->owner_id, ['class' => 'form-control project_id','id'=>'products', 'placeholder' => 'Please Select']) !!}
                                 </div>
                            </div>   
                        </div>  
                  </div>

                   <div class="row">
                      <div class="col-md-12">
                          <label for="inputEmail3" class="control-label">
                            Description
                          </label>
                          <textarea class="form-control" name="description" id="description" placeholder="Description">{!! $costcenter->description !!}</textarea>
                        </div>
                   </div>                 

                  <div class="row">
                     <div class="col-md-12">
                        <label for="inputEmail3" class="control-label">
                          Order Details
                        </label>
                          <textarea class="form-control" name="order_details" id="order_details" placeholder="Order Details">{!! $costcenter->order_details !!}</textarea>
                        </div>
                  </div>

                </div><!-- /.content -->

                <div class="col-md-12">
                    <div class="form-group">
                       <input type="Submit" value="Update CostCenter" class="btn btn-primary">
                        <a href="{!! route('admin.costcenter.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                    </div>
                 </div>

                  </form>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.col -->
    </div><!-- /.row -->

@endsection

@section('body_bottom')
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

    <script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD', 
          sideBySide: true,
          allowInputToggle: true
        });

      });
</script>

<script type="text/javascript">
         $(document).ready(function() {
    $('.project_id').select2();
});
</script>

 @endsection

