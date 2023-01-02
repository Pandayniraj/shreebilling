@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
    <link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />

@section('content')
        <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>


     <div class='row'>
       <div class='col-md-12'>
          <div class="box">
		     <div class="box-body ">
		     	{!! Form::open( ['route' => 'admin.timesheetsalary.store', 'class' => '', 'id' => 'form_edit_timesheetsalary'] ) !!}                
		     	<h4>Salary Templates</h4>
          <div class="row">
            <div class="col-md-6 form-group">
              <label class="control-label">Salary Grade/Post</label>
              <input type="text" name="salary_grade" class="form-control" placeholder="Enter Salary Grade" value="{{\Request::old('salary_grade')}}">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6  form-group">
              <label class="control-label">Regular Salary Per Hours</label>
              <div class="input-group">
                <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                  <input type="number" name="salary_per_hour" class="form-control" placeholder="Enter Regular Salary Per Hours" value="{{\Request::old('salary_per_hour')}}" step="any" id='salary_per_hour'>
               
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6  form-group">
              <label class="control-label">OverTime Salary Per Hours</label>
              

              <div class="input-group">
                <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                  <input type="number" name="overtime_salary_per_hour" class="form-control" placeholder="Enter Overtime Salary Per Hours" value="{{\Request::old('overtime_salary_per_hour')}}" step="any" id='overtime_salary_per_hour'>
               
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6  form-group">
              <label class="control-label">Other Salary Per Hours</label>

              <div class="input-group">
                <span class="input-group-addon">{{ env('APP_CURRENCY') }}</span>
                  <input type="number" name="other_salary_per_hour" class="form-control" placeholder="Other  Salary Per Hours (if required)" value="{{\Request::old('other_salary_per_hour')}}" step="any">
               
              </div>
            </div>
          </div>

          <div class="row">
          <div class="col-md-6 form-group">
                <label for="inputEmail3" class="control-label">
          Remarks
          </label>
            <textarea class="form-control" name="remarks" id="" placeholder="Enter Salary Remarks" rows="4">
              {{\Request::old('remarks')}}
            </textarea>
          </div>
          </div>
            <div class="row">
        <div class="col-md-5 checkbox">
          <label class="control-label"><input type="checkbox" value="" name="enabled">Enable</label>
        </div>
          </div>
            <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                           <a href="/admin/timesheetsalary/" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                      </div>
                   </div>
              </div>

         </form>
       </div>
     </div>
   </div>
<script type="text/javascript">
  $('#salary_per_hour').keyup(function(){
    let val = $(this).val();
    $('#overtime_salary_per_hour').val(Number(val)*1.5);
  })
</script>
  @endsection