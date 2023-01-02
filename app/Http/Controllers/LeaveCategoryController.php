<?php

namespace App\Http\Controllers;

use App\Models\LeaveCategory;
use Flash;
use Illuminate\Http\Request;

class LeaveCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Leave Category';
        $page_description = 'Leave Category Listing';
        $leave_categories = LeaveCategory::orderBy('leave_category_id', 'DESC')->get();

        return view('admin.leavecategory.index', compact('leave_categories', 'page_title','page_description'));
    }

    public function create()
    {
        $page_title = 'Create Leave Category';

        $page_description = 'Create New Leave Category';

        return view('admin.leavecategory.create', compact('page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        $attributes = $request->all();

        $leavecategory = \App\Models\LeaveCategory::create($attributes);

        Flash::success('Leave Category created Successfully.');

        return redirect('/admin/leavecategory');
    }

    public function edit($id)
    {
        $leavecategory = \App\Models\LeaveCategory::where('leave_category_id', $id)->first();

        $page_title = 'Edit Leave Category';

        $page_description = 'Editing Leave Category: ' . $leavecategory->leave_category . '';

        return view('admin.leavecategory.edit', compact('page_title', 'page_description', 'leavecategory'));
    }

    public function update(Request $request, $id)
    {
        $leavecategory = \App\Models\LeaveCategory::where('leave_category_id', $id)->first();
        \App\Models\LeaveCategory::where('leave_category_id', $id)
                ->update([
                        'leave_category' => $request->leave_category, 
                        'leave_quota' => $request->leave_quota,
                        'leave_code' => $request->leave_code,
                        'leave_type' => $request->leave_type,
                        'lapse_type' => $request->lapse_type,

                    ]);

        Flash::success('Leave Category Updated Successfully.');

        return redirect('/admin/leavecategory');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $leavecategory = \App\Models\LeaveCategory::where('leave_category_id', $id)->first();

        //dd($leavecategory);

        \App\Models\LeaveCategory::where('leave_category_id', $id)->delete();

        Flash::success('Leave Category successfully deleted');

        return redirect('/admin/leavecategory');
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

        $leavecategory = \App\Models\LeaveCategory::where('leave_category_id', $id)->first();

        $modal_title = 'Delete Leave Category';

        $leavecategory = \App\Models\LeaveCategory::where('leave_category_id', $id)->first();
        $modal_route = route('admin.leavecategory.delete', ['caseId' => $leavecategory->leave_category_id]);

        $modal_body = 'Are you sure that you want to delete Leave Category ID with the name ' . $leavecategory->leave_category . '? This operation is irreversible.';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
