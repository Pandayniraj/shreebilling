<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Cases as Cases;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class CasesSubCategoryController extends Controller
{
    /**
     * @var Client
     */
    private $cases;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $case
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Cases $cases, Permission $permission)
    {
        parent::__construct();
        $this->cases = $cases;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page_title = 'Admin | Cases | Sub | Category | List';
        $page_description = 'Lists of Case Category';

        $cases_sub_categories = \App\Models\CaseSubCategory::orderBy('id', 'DESC')->get();

        return view('admin.casesubcategory.index', compact('cases_sub_categories', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Admin | Case | Sub |  Category | Create';
        $page_description = 'Creating a New Case sub Category';

        $category = \App\Models\CaseCategory::orderBy('id', 'desc')->get();

        return view('admin.casesubcategory.create', compact('page_title', 'page_description', 'category'));
    }

    public function store(Request $request)
    {
        $attributes = $request->all();

        \App\Models\CaseSubCategory::create($attributes);

        Flash::success('Case Sub Category Created Successfully');

        return redirect('/admin/casessubcategory');
    }

    public function edit($id)
    {
        $page_title = 'Admin | Case | Sub | Category | Edit';
        $page_description = 'Editing Case Sub Category';

        $edit = \App\Models\CaseSubCategory::find($id);

        $category = \App\Models\CaseCategory::orderBy('id', 'desc')->get();

        return view('admin.casesubcategory.edit', compact('page_title', 'page_description', 'edit', 'category'));
    }

    public function update(Request $request, $id)
    {
        $attributes = $request->all();

        //   dd($attributes);
        $attributes = \App\Models\CaseSubCategory::find($id)->update($attributes);

        Flash::success('Case Sub Category Updated Successfully');

        return redirect('/admin/casessubcategory');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $casescategory = \App\Models\CaseSubCategory::find($id);

        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-destroy', ['name' => $casescategory->name]));

        $casescategory->delete($id);

        Flash::success('Case Sub Category successfully deleted');

        return redirect('/admin/casessubcategory');
    }

    /**
     * Delete Confirm.
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $error = null;

        $casescategory = \App\Models\CaseSubCategory::find($id);

        $modal_title = 'Delete Case Sub Category';

        $casescategory = \App\Models\CaseSubCategory::find($id);
        $modal_route = route('admin.casessubcategory.delete', ['caseId' => $casescategory->id]);

        $modal_body = trans('admin/cases/dialog.delete-confirm.body', ['id' => $casescategory->id, 'name' => $casescategory->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
