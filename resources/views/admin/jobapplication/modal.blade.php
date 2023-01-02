<div class="panel panel-custom">

    <div class="panel-heading">
        <div class="panel-title">
            <strong> Job Application Details </strong>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        </div>
    </div>
    <div class="panel-body form-horizontal">
        <div class="col-md-12">
            <div class="col-sm-4 text-right">
                <label class="control-label"><strong>Job Title : </strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static">{{ $application->circular->job_title }}</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-sm-4 text-right">
                <label class="control-label"><strong>Designation : </strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static">{{ $application->circular->designation->designations }}</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-sm-4 text-right">
                <label class="control-label"><strong>Name : </strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static">{{ $application->name }}</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-sm-4 text-right">
                <label class="control-label"><strong>Email : </strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static">{{ $application->email }}</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-sm-4 text-right">
                <label class="control-label"><strong>Mobile : </strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static">{{ $application->mobile }}</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-sm-4 text-right">
                <label class="control-label"><strong>Apply On : </strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static text-justify">{{ date('Y-m-d', strtotime($application->created_at)) }}</p>
            </div>
        </div>

        @if($application->resume)
        <div class="col-md-12">
            <div class="col-sm-4 text-right">
                <label class="control-label"><strong>Resume : </strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static pull-left">
                    <a class="label label-info" href="/job_applied/{{ $application->resume }}" target="_blank" style="text-decoration: none;background: #22313F;">Download Resume</a>
                </p>
            </div>
        </div>
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>