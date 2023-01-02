<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserTarget;
use App\Models\UserTargetPivot;
use App\User;
use Datatables;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTargetController extends Controller
{
    /**
     * @var Lead
     */
    private $userTarget;
    private $userTargetPivot;
    private $permission;

    /**
     * @var User
     */
    private $user;

    /**
     * @param User Targets $lead
     * @param Permission $permission
     * @param User $user
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userTargets = UserTarget::select('id', 'user_id', 'year')->orderBy('year', 'desc')->orderBy('id', 'desc')->get();

        $targets = UserTarget::select('id', 'user_id', 'year')->where('year', date('Y'))->get();
        $products = Product::where('enabled', '1')->where('org_id', Auth::user()->org_id)->get();

        $page_title = 'Admin | User Targets';
        $page_description = 'List of User Targets';

        return view('admin.userTarget.index', compact('userTargets', 'page_title', 'page_description', 'targets', 'products'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (Auth::user()->id != 1 && Auth::user()->id != 5 && Auth::user()->id != 4) {
            abort(403);
        }

        $page_title = 'Admin | User Targets | Create';
        $page_description = 'Creating a new User Target';

        $target_users = UserTarget::where('year', date('Y'))->pluck('user_id');
        $users = User::where('id', '!=', '1')->where('enabled', '1')->where('org_id', Auth::user()->org_id)->whereNotIn('id', $target_users)->pluck('first_name', 'id');
        $products = Product::where('enabled', '1')->where('org_id', Auth::user()->org_id)->get();

        return view('admin.userTarget.create', compact('page_title', 'page_description', 'target_users', 'users', 'products'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if (Auth::user()->id != 1 && Auth::user()->id != 5 && Auth::user()->id != 4) {
            abort(403);
        }

        $target = UserTarget::where('user_id', $request->user_id)->where('year', date('Y'))->first();
        if (count($target)) {
            Flash::success('User Targets is already created');

            return redirect('/admin/userTarget');
        }

        $userTarget = new UserTarget();
        $userTarget->user_id = $request->user_id;
        $userTarget->year = date('Y');
        $userTarget->save();

        $products = $request->course;
        $targets = $request->target;
        foreach ($targets as $tk => $tv) {
            $userTargetPivot = new UserTargetPivot();
            $userTargetPivot->user_target_id = $userTarget->id;
            $userTargetPivot->course_id = $products[$tk];
            $userTargetPivot->user_id = $request->user_id;
            $userTargetPivot->target = $tv;
            $userTargetPivot->save();
        }

        Flash::success('User Targets successfully created');

        return redirect('/admin/userTarget');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $target = UserTarget::where('id', $id)->first();
        if (! $target->isEditable()) {
            abort(403);
        }

        $targetPivots = UserTargetPivot::where('user_target_id', $id)->get();

        $page_title = 'Admin | User Targets | Edit';
        $page_description = 'Edit User Target';

        $products = Product::where('enabled', '1')->get();

        return view('admin.userTarget.edit', compact('target', 'targetPivots', 'page_title', 'page_description', 'products'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $target = UserTarget::where('id', $id)->first();

        if (! $target->isEditable()) {
            abort(403);
        }

        UserTargetPivot::where('user_target_id', $id)->delete();

        $products = $request->course;
        $targets = $request->target;
        foreach ($targets as $tk => $tv) {
            $userTargetPivot = new UserTargetPivot();
            $userTargetPivot->user_target_id = $id;
            $userTargetPivot->course_id = $products[$tk];
            $userTargetPivot->user_id = $request->user_id;
            $userTargetPivot->target = $tv;
            $userTargetPivot->save();
        }

        Flash::success('User Targets successfully updated');

        return redirect('/admin/userTarget');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $target = UserTarget::where('id', $id)->first();

        if (! $target->isdeletable()) {
            abort(403);
        }

        UserTarget::where('id', $id)->delete($id);

        Flash::success('User Targets successfully deleted');

        return redirect('/admin/userTarget');
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

        $target = UserTarget::where('id', $id)->first();

        if (! $target->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete User Target';

        $modal_route = route('admin.userTarget.delete', ['id' => $target->id]);

        $modal_body = 'Are you sure that you want to delete user target ? This operation is irreversible.';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function summary($year)
    {
        $targets = UserTarget::select('id', 'user_id', 'year')->where('year', $year)->get();
        $products = Product::where('enabled', '1')->get();

        $page_title = 'Admin | User Targets Summary - '.$year;
        $page_description = 'List of User Targets - '.$year;

        return view('admin.userTarget.summary', compact('targets', 'products', 'page_title', 'page_description'));
    }
}
