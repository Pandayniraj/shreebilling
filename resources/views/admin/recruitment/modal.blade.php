
@php 
$circular = isset($circular) ? $circular : null  
@endphp
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">@if(isset($circular)) Edit @else New @endif Circular</h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <form role="form" id="circular_form" action="/admin/job_posted/save" method="post" enctype="multipart/form-data" class="form-horizontal form-groups-bordered">
            {{ csrf_field() }}

            <div class="form-group">
                <label class="col-sm-3 control-label">Job Title <span class="required">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="job_title" class="form-control" value="@if($circular){{$circular->job_title}}@endif" required="" placeholder="Enter Job Title">
                </div>
            </div>

            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label">Designation <span class="required">*</span></label>
                <div class="col-sm-5">
                    <select name="designation_id" style="width: 100%" id="designation_id" class="form-control select_box" required>
                        <option value="">Select Designation...</option>
                        @foreach($designations as $uk => $uv)
                        <option value="{{ $uv->designations_id }}" @if($circular && $circular->designation_id == $uv->designations_id) selected @endif>{{ $uv->designations }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label">Job Nature <span class="required">*</span></label>
                <div class="col-sm-5">
                    <select name="employment_type" class="form-control" required="">
                        
                        <option value="1" @if($circular && $circular->employment_type == 'contractual') selected @endif>Contractual </option>
                        <option value="2" @if($circular && $circular->employment_type == 'full_time') selected @endif>Full Time </option>
                        <option value="3" @if($circular && $circular->employment_type == 'part_time') selected @endif>Part Time</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Experience <span class="required">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="experience" class="form-control" value="@if($circular){{$circular->experience}}@endif" required="" placeholder="1 to 2 year(s)">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Age <span class="required">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="age" class="form-control" value="@if($circular){{$circular->age}}@endif" required="" placeholder="20-40">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Salary Range <span class="required">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="salary_range" class="form-control" value="@if($circular){{$circular->salary_range}}@endif" required="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">No of Vacancy <span class="required">*</span></label>
                <div class="col-sm-5">
                    <input type="number" name="vacancy_no" class="form-control" value="@if($circular){{$circular->vacancy_no}}@endif" required="">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Posted Date<span class="required">*</span></label>
                <div class="col-sm-5">
                    <div class="input-group ">
                        <input type="text" name="posted_date" id="posted_date" value="@if($circular){{$circular->posted_date}}@endif" class="form-control datepicker" required="">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Last Date to Apply<span class="required">*</span></label>
                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" name="last_date" id="last_date" value="@if($circular){{$circular->last_date}}@endif" class="form-control datepicker" required="">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label">Status <span class="required">*</span></label>
                <div class="col-sm-5">
                    <select name="status" class="form-control" required="">
                        <option value="published" @if(!$circular) selected @endif @if($circular && $circular->status == 'published') selected @endif>Published</option>
                        <option value="unpublished" @if($circular && $circular->status == 'unpublished') selected @endif>Unpublished </option>
                    </select>
                </div>
            </div>

   

            <div class="form-group">
                <label class="col-sm-3 control-label">Description</label>
                <div class="col-sm-8">
                    <textarea class="form-control textarea_2" name="description">@if($circular){{$circular->description}}@endif</textarea>
                </div>
            </div>
           
          

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-2">
                    <input type="hidden" name="job_circular_id" value="{{ $circular ? $circular->job_circular_id : '' }}">
                    <button type="submit" id="sbtn" class="btn btn-primary btn-block">@if(!$circular) Save @else Edit @endif</button>
                </div>
                <div class="col-sm-2">
                    <button type="button" data-dismiss="modal" class="btn btn-default btn-block">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

