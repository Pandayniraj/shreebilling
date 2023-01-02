<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use App\Models\Role as Permission;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class OverTimeController extends Controller
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $client
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    public function index()
    {
        $page_title = 'Over Time';
        $page_description = 'Over Time Lists';

        $users = User::orderBy('designations_id', 'desc')->where('enabled', 1)->get();

        if (\Request::get('year') && \Request::get('year') != '') {
            $overtimes = Overtime::whereBetween('overtime_date', [\Request::get('year').'-01-01', Request::get('year').'-12-31'])->orderBy('overtime_date', 'asc')->get();
        } else {
            $overtimes = Overtime::whereBetween('overtime_date', [date('Y').'-01-01', date('Y').'-12-31'])->orderBy('overtime_date', 'asc')->get();
        }

        // dd($overtimes);

        return view('admin.overtime.index', compact('page_title', 'page_description', 'users', 'overtimes'));
    }

    public function store(Request $request)
    {

        //dd($request->all());
        Overtime::create($request->all());
        Flash::success('Over Time has been created successfully.');

        return Redirect::back();
    }

    public function destroy($id)
    {
        Overtime::where('overtime_id', $id)->delete();
        Flash::success('Over Time has been deleted successfully.');

        return Redirect::back();
    }

    public function edit($id)
    {
        $overtime = Overtime::where('overtime_id', $id)->first()->toArray();
        //dd($overtime);
        return $overtime;
    }

    public function update(Request $request, $id)
    {
        Overtime::where('overtime_id', $id)->update(['user_id' => $request->user_id, 'overtime_date' => $request->overtime_date, 'overtime_hours' => $request->overtime_hours, 'notes' => $request->notes]);

        Flash::success('Over Time has been updated successfully.');

        return Redirect::back();
    }
}
