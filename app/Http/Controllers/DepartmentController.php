<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Audit as Audit;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Role as Permission;
use App\User;
use Auth;
use DB;
use Flash;
use Illuminate\Http\Request;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class DepartmentController extends Controller
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

    // Stock Category
    public function index()
    {
        $page_title = 'Departments';
        $page_description = 'All Departments';

        $departments = Department::where('org_id', \Auth::user()->org_id)->orderBy('deptname', 'asc')->get();

        return view('admin.departments.index', compact('page_title', 'page_description', 'departments'));
    }

    public function store(Request $request)
    {
        if ($request->departments_id == '') {
            $this->validate($request, ['deptname' => 'required']);
            $department = Department::create(['deptname' => $request->deptname, 'org_id'=>\Auth::user()->org_id]);

            if ($request->designations != '') {
                Designation::create(['departments_id' => $department->id, 'designations' => $request->designations,
                    'org_id'=>\Auth::user()->org_id, ]);
            }

            Flash::success('Department created Successfully');
        } else {
            if ($request->designations_id) {
                Designation::where('designations_id', $request->designations_id)->update(['departments_id'=>$request->departments_id, 'designations' => $request->designations]);
                Flash::success('Designation updated Successfully');
            } else {
                Designation::create(['departments_id'=>$request->departments_id, 'designations' => $request->designations, 'org_id'=>\Auth::user()->org_id]);
                Flash::success('Designation created Successfully');
            }
        }
        //return \Redirect::back();
        return redirect('/admin/departments');
    }

    public function edit($departments_id)
    {
        $department = Department::where('departments_id', $departments_id)->first();
        $data = '<div class="panel panel-custom">
                    <div class="panel-heading">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit Department</h4>
                    </div>
                    <div class="modal-body wrap-modal wrap">
                        <form id="form_validation" action="/admin/departments/update/'.$departments_id.'" method="post" class="form-horizontal form-groups-bordered">'.csrf_field().'
                            <div class="form-group" id="border-none">
                                <label for="field-1" class="col-sm-4 control-label">Department<span class="required">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" name="deptname" class="form-control" value="'.$department->deptname.'" required="required">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>';

        return $data;
    }

    public function updateDepartment($departments_id, Request $request)
    {
        $department = Department::where('departments_id', $departments_id)->first();

        if (! $department) {
            abort(404);
        }

        Department::where('departments_id', $departments_id)->update(['deptname'=>$request->deptname]);

        Flash::success('Department updated Successfully');

        return redirect('/admin/departments');
    }

    public function editDesignation($departments_id, $designations_id)
    {
        $page_title = 'Departments';
        $page_description = 'All Departments';

        $designation = null;

        $departments = Department::where('org_id', \Auth::user()->org_id)->orderBy('deptname', 'asc')->get();
        $designation = Designation::where('designations_id', $designations_id)->first();

        if (! $designation) {
            abort(404);
        }

        return view('admin.departments.index', compact('page_title', 'page_description', 'departments', 'designation'));
    }

    public function deleteDepartment($departments_id)
    {
        $department = Department::where('departments_id', $departments_id)->first();
        if (! $department->isEditable() && ! $department->canChangePermissions()) {
            abort(403);
        }

        Designation::where('departments_id', $departments_id)->delete();
        Department::where('departments_id', $departments_id)->delete();

        Flash::success('Department deleted Successfully');

        return redirect('/admin/departments');
    }

    public function deleteDesignation($designations_id)
    {
        $designation = Designation::where('designations_id', $designations_id)->first();
        if (! $designation->isEditable() && ! $designation->canChangePermissions()) {
            abort(403);
        }

        Designation::where('designations_id', $designations_id)->delete();
        Flash::success('Designation deleted Successfully');

        return redirect('/admin/departments');
    }
}
