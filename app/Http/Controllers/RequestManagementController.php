<?php

namespace App\Http\Controllers;

use App\Models\EmployeeRequest;
use Flash;
use Illuminate\Http\Request;

class RequestManagementController extends Controller
{
    public function __construct(EmployeeRequest $empRequest)
    {
        $this->empRequest = $empRequest;
        $this->request_types = [
            'festival'=>'Festival',
            'insurance'=>'Insurance',
            'travel'=>'Travel',
        ];
        $this->request_status = [
            'approve'=>'Approved',
            'pending'=>'Pending',
            'cancel'=>'Rejected',
        ];
        $this->benefit_types = [
            'festival_bonus'=>'Festival Bonus',
            'travel_bonus'=>'Travel Bonus',
            'food_allowance'=>'Food Allowance',
            'opportunity'=>'Opportunity',
            'medical'=>'Medical',
            'vacation'=>'Vacation',
            'retirement'=>'Retirement',
        ];
        $this->pay_type = ['cash'=>'Cash', 'cheque'=>'Cheque'];
    }

    public function index()
    {
        $empRequest = $this->empRequest->orderBy('id', 'desc')->paginate(30);
        $page_title = 'Staff Request';
        $page_description = 'requests';
        $request_types = $this->request_types;
        $request_status = $this->request_status;
        $benefit_types = $this->benefit_types;
        $pay_type = $this->pay_type;
        $status_color = [
            'approve'=>'label-success',
            'pending'=>'label-warning',
            'cancel'=>'label-danger',
        ];

        return view('admin.emp_requests.index', compact('empRequest', 'page_title', 'page_description', 'request_types', 'request_status', 'status_color'));
    }

    public function create()
    {
        $employee = \App\User::where('enabled', '1')->get();
        $teams = \App\Models\Team::where('org_id', \Auth::user()->org_id)->pluck('name', 'id');
        $request_types = $this->request_types;
        $request_status = $this->request_status;
        $benefit_types = $this->benefit_types;
        $pay_type = $this->pay_type;
        $page_title = 'Staff Request';
        $page_description = 'Create a requests';

        return view('admin.emp_requests.create', compact('employee', 'request_types', 'benefit_types', 'request_status', 'benefit_types', 'pay_type', 'page_title', 'page_description', 'teams'));
    }

    public function store(Request $request)
    {
        $attributes = $request->all();

        if ($attributes['attachment']) {
            $file = $attributes['attachment'];
            $destinationPath = public_path('/employee_request/');
            if (! \File::isDirectory($destinationPath)) {
                \File::makeDirectory($destinationPath, 0777, true, true);
            }
            $file_name = date('Y_m_d-h_i_s').'.'.$file->getClientOriginalExtension();
            $filename = $file_name.'__';
            $file->move($destinationPath, $filename);
            $attributes['attachment'] = '/employee_request/'.$filename;
        }
        $attributes['status'] = 'pending';
        $this->empRequest->create($attributes);
        Flash::success('Employee Request SuccessFully Created');

        return redirect('/admin/employeeRequest/');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $employee = \App\User::where('enabled', '1')->get();
        $teams = \App\Models\Team::where('org_id', \Auth::user()->org_id)->pluck('name', 'id');
        $request_types = $this->request_types;
        $request_status = $this->request_status;
        $benefit_types = $this->benefit_types;
        $pay_type = $this->pay_type;
        $page_title = 'Staff Request';
        $page_description = 'Edit a requests #'.$id;
        $empRequest = $this->empRequest->find($id);

        return view('admin.emp_requests.edit', compact('employee', 'request_types', 'benefit_types', 'request_status', 'benefit_types', 'pay_type', 'page_title', 'page_description', 'empRequest', 'teams'));
    }

    public function update(Request $request, $id)
    {
        $attributes = $request->all();
        $empRequest = $this->empRequest->find($id);
        if (! $empRequest->isEditable()) {
            abort(404);
        }
        if ($attributes['attachment']) {
            $file = $attributes['attachment'];
            $destinationPath = public_path('/employee_request/');
            if (! \File::isDirectory($destinationPath)) {
                \File::makeDirectory($destinationPath, 0777, true, true);
            }
            $file_name = date('Y_m_d-h_i_s').'.'.$file->getClientOriginalExtension();
            $filename = $file_name;
            $file->move($destinationPath, $filename.'__');
            $attributes['attachment'] = '/employee_request/'.$filename;
        }

        $empRequest->update($attributes);
        Flash::success('Staff Request SuccessFully Updated');

        return redirect()->back();
    }

    public function getModalDelete($id)
    {
        $teams = $this->empRequest->find($id);
        $modal_title = 'Delete Staff Request';
        $modal_body = 'Are you sure you want to delte teams with title '.$teams->title.' and Id'.$id;
        $modal_route = route('admin.employeeRequest.delete', ['id' => $id]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function destroy($id)
    {
        $empRequest = $this->empRequest->find($id);
        if (! $empRequest->isDeletable()) {
            abort(404);
        }
        $empRequest->delete();
        Flash::success('Staff Request SuccessFully Deleted');

        return redirect('/admin/employeeRequest/');
    }

    public function accept_reject($id)
    {
        $empRequest = $this->empRequest->find($id);
        $authorizeTeam = $empRequest->request_team;
        $authorize = \App\Models\UserTeam::where('team_id', $authorizeTeam)
                    ->where('user_id', \Auth::user()->id)
                    ->first();
        if (! $authorize || ! $authorizeTeam) {
            Flash::error('You are not authorized for this action');

            return redirect()->back();
        }
        $page_title = 'Accept|Reject Request';

        $request_types = $this->request_types;
        $request_status = [
             'approve'=>'Approved',
            'pending'=>'Pending',
            'cancel'=>'Rejected',
        ];
        $benefit_types = $this->benefit_types;
        $pay_type = $this->pay_type;
        $status_color = ['approve'=>'success',
            'pending'=>'warning',
            'cancel'=>'danger', ];
        $page_description = 'View Request Details';

        return view('admin.emp_requests.accept_reject',
            compact('page_title', 'empRequest', 'request_types', 'request_status', 'benefit_types', 'pay_type', 'status_color', 'page_description'));
    }

    public function accept_rejectPost(Request $request, $id)
    {
        $empRequest = $this->empRequest->find($id);
        $authorizeTeam = $empRequest->request_team;
        $authorize = \App\Models\UserTeam::where('team_id', $authorizeTeam)
                    ->where('user_id', \Auth::user()->id)
                    ->first();
        if (! $authorize || ! $authorizeTeam) {
            Flash::error('You are not authorized for this action');

            return redirect()->back();
        }

        $empRequest->update(['comment'=>$request->comment,
            'status'=>$request->status,
            'approved_by'=>\Auth::user()->id,
        ]);
        $message = $request->status == 'approve' ? 'Approved' : 'Rejected';
        Flash::success("Successfully {$message} request");

        return redirect()->back();
    }
}
