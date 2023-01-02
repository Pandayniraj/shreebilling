<div class="panel panel-custom">
<div class="panel-heading">
<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
<h4 class="modal-title" id="myModalLabel">Edit Department</h4>
</div>
<div class="modal-body wrap-modal wrap">
<form id="form_validation" action="/admin/departments/update/{{$department->departments_id}}" method="post" class="form-horizontal form-groups-bordered">{{csrf_field()}}
    <div class="form-group"></div>

    <div class="form-group" id="border-none">
        <label for="field-1" class="col-sm-4 control-label">Division<span class="required">*</span></label>
        <div class="col-sm-5">
            {!! Form::select('division_id',$division,$department->division_id,['class'=>'form-control','placeholder'=>'Select Divison']) !!}
        </div>
    </div>
    <div class="form-group" id="border-none">
        <label for="field-1" class="col-sm-4 control-label">Department<span class="required">*</span></label>
        <div class="col-sm-5">
            <input type="text" name="deptname" class="form-control" 
                value="{{$department->deptname}}" required="required">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
</div>
</div>