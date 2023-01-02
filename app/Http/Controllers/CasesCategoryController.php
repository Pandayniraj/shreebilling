<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Cases as Cases;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class CasesCategoryController extends Controller
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
    public function __construct(Cases $cases)
    {
        parent::__construct();
        $this->cases = $cases;

    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page_title = 'Admin | Cases | Category | List';
        $page_description = 'Lists of Case Category';

        $cases_categories = \App\Models\Casecategory::orderBy('id', 'DESC')->get();

        return view('admin.casescategory.index', compact('cases_categories', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Admin | Case | Category | Create';
        $page_description = 'Creating a New Case Category';

        return view('admin.casescategory.create', compact('page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        $attributes = $request->all();

        \App\Models\Casecategory::create($attributes);

        Flash::success('Case Category Created Successfully');

        return redirect('/admin/casescategory');
    }

    public function edit($id)
    {
        $page_title = 'Admin | Case | Category | Edit';
        $page_description = 'Editing Case Category';

        $edit = \App\Models\Casecategory::find($id);

        return view('admin.casescategory.edit', compact('page_title', 'page_description', 'edit'));
    }

    public function update(Request $request, $id)
    {
        $attributes = $request->all();
        $attributes = \App\Models\Casecategory::find($id)->update($attributes);

        Flash::success('Case Category Updated Successfully');

        return redirect('/admin/casescategory');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $casescategory = \App\Models\Casecategory::find($id);

        Audit::log(Auth::user()->id, trans('admin/cases/general.audit-log.category'), trans('admin/cases/general.audit-log.msg-destroy', ['name' => $casescategory->name]));

        $casescategory->delete($id);

        Flash::success('Case Category successfully deleted');

        return redirect('/admin/casescategory');
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

        $casescategory = \App\Models\Casecategory::find($id);

        $modal_title = 'Delete Case Category';

        $casescategory = \App\Models\Casecategory::find($id);
        $modal_route = route('admin.casescategory.delete', ['caseId' => $casescategory->id]);

        $modal_body = trans('admin/cases/dialog.delete-confirm.body', ['id' => $casescategory->id, 'name' => $casescategory->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
