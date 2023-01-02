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
                   <form action="/admin/activity/{{ $activities->id }}" method="post">

                    {{ csrf_field() }}
            
                <div class="content col-md-9">

                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-3">Code</label>
                              <div class="input-group ">

                                <input type="text" name="code" id="" value="{{ $activities->code }}" placeholder="Code" class="form-control " required="required"> 
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar-alt"></i></a>
                                </div>


                              </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">  
                                  <label class="control-label col-sm-3">Project Name</label>
                                 <div class="col-md-9">
                                  {!! Form::select('project_id', $projects, $activities->project_id, ['class' => 'form-control project_id','id'=>'products', 'placeholder' => 'Please Select']) !!}
                                 </div>
                            </div>   
                        </div> 
                  </div>

                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">  
                           <label class="control-label col-sm-3">Start date</label>
                            <div class="input-group ">
                              <input type="text" name="start_date" placeholder="Start Date" id="start_date" value="{{ $activities->start_date }}" class="form-control datepicker" required="required">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-credit-card"></i></a>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">  
                              <label class="control-label col-sm-3">End Date</label>
                                <div class="input-group ">
                                   <input type="text" name="end_date" placeholder="End Date" id="end_date" value="{{ $activities->end_date }}" class="form-control datepicker" required="required">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-credit-card"></i></a>
                                    </div>
                              </div>
                            </div>
                        </div>
                    </div>                    

                  <div class="row">
                     <div class="col-md-12">
                        <label for="inputEmail3" class="control-label">
                        Other Details
                        </label>
                          <textarea class="form-control" name="other_details" id="description" placeholder="Other Details">{!! $activities->other_details !!}</textarea>
                        </div>
                  </div>

                </div><!-- /.content -->

                <div class="col-md-12">
                    <div class="form-group">
                       <input type="Submit" value="Update Activity" class="btn btn-primary">
                        <a href="{!! route('admin.activity.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
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

