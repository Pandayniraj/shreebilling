<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class JobApplicationController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    public function index()
    {
        $page_title = 'Job Application';
        $page_description = 'All Application';

        $applications = JobApplication::orderBy('job_appliactions_id', 'desc')->get();

        return view('admin.jobapplication.index', compact('page_title', 'page_description', 'applications'));
    }

    public function status($job_appliactions_id)
    {
        $application = JobApplication::select('application_status', 'job_appliactions_id')->where('job_appliactions_id', $job_appliactions_id)->first();
        $returnHTML = view('admin.jobapplication.status_modal', ['application'=> $application])->render();

        return $returnHTML;
    }

    public function updateStatus($job_appliactions_id, Request $request)
    {
        JobApplication::where('job_appliactions_id', $job_appliactions_id)->update($request->except(['_token']));

        Flash::success('Job Application status has been updated.');

        return Redirect::back();
    }

    public function show($job_appliactions_id)
    {
        $application = JobApplication::where('job_appliactions_id', $job_appliactions_id)->first();

        $returnHTML = view('admin.jobapplication.modal', ['application'=> $application])->render();

        return $returnHTML;
    }

    public function delete($job_appliactions_id)
    {
        $application = JobApplication::where('job_appliactions_id', $job_appliactions_id)->first();
        if (! $application) {
            Flash::warning('Sorry! No Job Application found.');

            return Redirect::back();
        }

        if (! $application->isDeletable()) {
            Flash::warning('Sorry! You do not have enough privilege to delete this.');
            abort(403);
        }

        if ($application->resume != '' && File::exists('job_applied/'.$application->resume)) {
            File::Delete('job_applied/'.$application->resume);
        }
        JobApplication::where('job_appliactions_id', $job_appliactions_id)->delete();
        Flash::success('Job Application deleted Successfully');

        return Redirect::back();
    }
}
