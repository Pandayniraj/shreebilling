<div class="panel " style="border: none">
    <div class="panel-heading m0" style="border: none;background-color: #37474f;color: #fff">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <strong>Job Summery</strong>
    </div>
    <div class="panel-body" style="background-color: #f5f5f5;">
        <p class="m0">
            <strong>Job Title: {{ $circular->job_title }}</strong>

        </p>
        <p class="m0">
            <strong>Designation: {{ $circular->designation->designations }}</strong>
        </p>
        <p class="m0">
            <strong>Job Nature : {{ ucfirst($circular->employment_type) }}</strong>
        </p>
        <p class="m0">
            <strong>Experience: {{ $circular->experience }}</strong>
        </p>
        <p class="m0">
            <strong>Age: {{ $circular->age }}</strong>
        </p>
        <p class="m0">
            <strong>Salary Range: {{ $circular->salary_range }}</strong>
        </p>
        <p class="m0">
            <strong>No of Vacancy: {{ $circular->vacancy_no }}</strong>
        </p>
        
        <p class="m0">
            <strong> Posted Date : {{ $circular->posted_date }} </strong>
        </p>
        <p>
            <strong> Last Date : {{ $circular->last_date }} </strong>
        </p>
    </div>
</div>