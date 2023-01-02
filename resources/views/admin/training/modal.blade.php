@php $training = isset($training) ?$training : null;  @endphp
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">@if(isset($training)) Edit @else New @endif Trainings</h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <form role="form" id="training_form" action="/admin/trainings/save" method="post" enctype="multipart/form-data" class="form-horizontal form-groups-bordered">
            {{ csrf_field() }}
            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label">Employee <span class="required">*</span></label>
                <div class="col-sm-5">
                    <select name="user_id" style="width: 100%" id="employee" class="form-control select_box" required>
                        <option value="">Select Employee...</option>
                        @foreach($users as $uk => $uv)
                        <option value="{{ $uv->id }}" @if(isset($training) && $training->user_id == $uv->id) selected @endif>{{ $uv->first_name.' '.$uv->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Course / Training <span class="required">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="training_name" required="" class="form-control" value="@if(isset($training)){{$training->training_name}}@endif" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Vendor <span class="required">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="vendor_name" class="form-control" value="@if(isset($training)){{$training->vendor_name}}@endif" required="">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Start Date<span class="required">*</span></label>
                <div class="col-sm-5">
                    <div class="input-group ">
                        <input type="text" name="start_date" id="start_date" value="@if(isset($training)){{$training->start_date}}@endif" class="form-control datepicker" required="">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Finish Date<span class="required">*</span></label>
                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" name="finish_date" id="finish_date" value="@if(isset($training)){{$training->finish_date}}@endif" class="form-control datepicker" required="">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Training Cost</label>
                <div class="col-sm-5">
                    <input type="text" data-parsley-type="number" name="training_cost" class="form-control" value="@if(isset($training)){{$training->training_cost}}@endif">
                </div>
            </div>

            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label">Status <span class="required">*</span></label>
                <div class="col-sm-5">
                    <select name="status" class="form-control" required="">
                        <option value="0" @if(! isset($training)) selected @endif @if($training && $training->status == 0) selected @endif>Pending </option>
                        <option value="1" @if($training && $training->status == 1) selected @endif>Started </option>
                        <option value="2" @if($training && $training->status == 2) selected @endif>Completed </option>
                        <option value="3" @if($training && $training->status == 3) selected @endif>Terminated</option>
                    </select>
                </div>
            </div>

            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label">Performance</label>
                <div class="col-sm-5">
                    <select name="performance" id="employee" class="form-control">
                        <option value="0" @if(!$training) selected @endif @if($training && $training->performance == 0) selected @endif> Not Concluded </option>
                        <option value="1" @if($training && $training->performance == 1) selected @endif> Satisfactory </option>
                        <option value="2" @if($training && $training->performance == 2) selected @endif> Average </option>
                        <option value="3" @if($training && $training->performance == 3) selected @endif> Poor </option>
                        <option value="4" @if($training && $training->performance == 4) selected @endif> Excellent </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Remarks</label>
                <div class="col-sm-8">
                    <textarea class="form-control textarea_2" name="remarks">@if($training){{$training->remarks}}@endif</textarea>
                </div>
            </div>
            <div id="add_new">
                @if($training && $training->upload_file != '')
                <div class="form-group ">
                    <label class="control-label col-sm-3"><strong>Attachment:</strong></label>
                    <div class="col-sm-8">
                        @foreach(explode(',', $training->upload_file) as $lfv)
                        <div>
                            <a class="btn btn-xs btn-dark" data-toggle="tooltip" data-placement="top" title="" href="/training/{{ $lfv }}" data-original-title="Download" target="_blank">
                                <p class="form-control-static">{{ $lfv }}</p>
                            </a> &nbsp;&nbsp;
                            <strong><a href="javascript:void(0);" class="remAttachments"><i class="fa fa-times"></i>&nbsp;Remove</a></strong>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="remove_attachments" id="remove_attachments" value="">
                </div>
                @endif
                <div class="form-group" style="margin-bottom: 0px">
                    <label for="field-1" class="col-sm-3 control-label">Attachment</label>
                    <div class="col-sm-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <span class="btn btn-default btn-file"><span class="fileinput-new">Select File</span>
                            <span class="fileinput-exists">Change</span>
                            <input type="hidden" value="" name="upload_file[]">
                            <input type="file" name="upload_file[]">
                            </span>
                            <span class="fileinput-filename"></span>
                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">×</a>
                        </div>
                        <div id="msg_pdf" style="color: #e11221"></div>
                    </div>
                    <div class="col-sm-3">
                        <strong><a href="javascript:void(0);" id="add_more" class="addCF "><i class="fa fa-plus"></i>&nbsp;Add More                                </a></strong>
                    </div>
                </div>
            </div>
            <div class="form-group" id="border-none" style="display: none;">
                <label for="field-1" class="col-sm-3 control-label">Permission <span class="required">*</span></label>
                <div class="col-sm-9">
                    <div class="checkbox c-radio needsclick">
                        <label class="needsclick">
                            <input id="" checked="" type="radio" name="permission" value="everyone" @if($training && $training->permission == 'everyone') checked @endif @if(!$training)checked @endif >
                            Everyone <i title="who have permission for this menu and all admin user." class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"></i>
                        </label>
                    </div>
                    <div class="checkbox c-radio needsclick">
                        <label class="needsclick">
                            <input id="" type="radio" name="permission" value="custom_permission" @if($training && $training->permission == 'custom_permission') checked @endif>
                            Customize Permission <i title="Select a individual permission . individually edit delete action" class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"></i>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group " id="permission_user" style="display: none;">
                <label for="field-1" class="col-sm-3 control-label">Select Users <span class="required">*</span></label>
                <div class="col-sm-9">

                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-2">
                    <input type="hidden" name="training_id" value="{{ $training ? $training->training_id : '' }}">
                    <button type="submit" id="sbtn" class="btn btn-primary btn-block">@if(!$training) Save @else Edit @endif</button>
                </div>
            </div>
        </form>
    </div>
</div>