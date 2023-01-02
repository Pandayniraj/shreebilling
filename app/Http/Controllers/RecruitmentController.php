<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\JobApplication;
use App\Models\JobCircular;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class RecruitmentController extends Controller
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
        $page_title = 'Recruitment';
        $page_description = 'All Recruitment';

        $circulars = JobCircular::orderBy('created_at', 'desc')->get();
        $designations = Designation::orderBy('designations_id', 'asc')->get();

        return view('admin.recruitment.index', compact('page_title', 'page_description', 'circulars', 'designations'));
    }

    public function save(Request $request)
    {
        /* if(!$training->isEditable())
        {
            abort(403);
        } */

        $attributes = $request->except(['_token']);

        if ($request->job_circular_id == '') {
            JobCircular::create($attributes);
            Flash::success('Job Circular created Successfully');
        } else {
            JobCircular::where('job_circular_id', $request->job_circular_id)->update($attributes);
            Flash::success('Job Circular updated Successfully');
        }

        return Redirect::back();
    }

    public function edit($job_circular_id)
    {
        $circular = JobCircular::where('job_circular_id', $job_circular_id)->first();
        $designations = Designation::orderBy('designations_id', 'asc')->get();

        $returnHTML = view('admin.recruitment.modal', ['circular'=> $circular, 'designations'=> $designations])->render();

        return $returnHTML;
        //return response()->json(['success' => true, 'html' => $returnHTML]);
    }

    public function show($job_circular_id)
    {
        $circular = JobCircular::where('job_circular_id', $job_circular_id)->first();
        $designations = Designation::orderBy('designations_id', 'asc')->get();

        $returnHTML = view('admin.recruitment.show_modal', ['circular'=> $circular, 'designations'=> $designations])->render();

        return $returnHTML;
    }

    public function delete($job_circular_id)
    {
        $circular = JobCircular::where('job_circular_id', $job_circular_id)->first();
        if (! $circular) {
            Flash::warning('Sorry! Problem in finding the Trainign data.');

            return Redirect::back();
        }

        if (! $circular->isDeletable()) {
            Flash::warning('Sorry! You do not have enough privilege to delete this.');
            abort(403);
        }

        JobCircular::where('job_circular_id', $job_circular_id)->delete();
        JobApplication::where('job_circular_id', $job_circular_id)->delete();
        Flash::success('Job Circular deleted Successfully');

        return Redirect::back();
    }
}
