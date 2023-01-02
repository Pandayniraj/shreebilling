
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Change Status</h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <form role="form" id="circular_form" action="/admin/job_applied/updateStatus/{{ $application->job_appliactions_id }}" method="post" enctype="multipart/form-data" class="form-horizontal form-groups-bordered">
            {{ csrf_field() }}

            <div class="form-group" id="border-none">
                <label for="field-1" class="col-sm-3 control-label">Status <span class="required">*</span></label>
                <div class="col-sm-5">
                    <select class="form-control" name="application_status" required="">
                        <option value="0" @if($application && $application->application_status == 0) selected @endif>Pending</option>
                        <option value="1" @if($application && $application->application_status == 1) selected @endif>Accept</option>
                        <option value="2" @if($application && $application->application_status == 2) selected @endif>Reject</option>
                        <option value="3" @if($application && $application->application_status == 3) selected @endif>Call For Interview</option>
                        <option value="4" @if($application && $application->application_status == 4) selected @endif>Primary Selection</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-2">
                    <button type="submit" id="sbtn" class="btn btn-primary btn-block">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>