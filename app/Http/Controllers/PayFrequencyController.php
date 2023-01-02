<?php

namespace App\Http\Controllers;

use App\Models\PayFrequency;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayFrequencyController extends Controller
{
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
    public function __construct(Permission $permission, PayFrequency $payfrequency)
    {
        parent::__construct();
        $this->permission = $permission;
        $this->payfrequency = $payfrequency;
    }

    public function index()
    {
        $page_title = 'Admin | PayFrequency';
        $label = ['H'=>'btn bg-maroon margin', 'W'=>'btn-danger margin', 'M'=>'btn bg-blue', 'B'=>'btn bg-purple'];
        $frequency = ['H' => 'Hourly', 'W' => 'Weekly', 'M' => 'Monthly', 'B'=>'Biweekly'];
        $time_entry = ['T'=>'Timesheet', 'W'=>'Web & Mobile', 'B'=>'BioMetric'];
        $pay_frequency = $this->payfrequency->paginate(30);

        return view('admin.payroll.payfrequency.index', compact('pay_frequency', 'frequency', 'label', 'page_title', 'time_entry'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin | PayFrequency';
        $page_description = 'Create new pay frequency';
        $frequency = ['H' => 'Hourly', 'W' => 'Weekly', 'M' => 'Monthly', 'B'=>'Biweekly'];
        $current_fiscal_year = \FinanceHelper::cur_fisc_yr();
        $fiscal_year = \App\Models\Fiscalyear::where('org_id', \Auth::user()->org_id)->get();

        return view('admin.payroll.payfrequency.create', compact('frequency', 'page_description', 'page_title', 'fiscal_year', 'current_fiscal_year'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function convertdate($date)
    {
        $cal = new \App\Helpers\NepaliCalendar();
        $converted = explode('-', $date);
        $converted = $cal->nep_to_eng($converted[0], $converted[1], $converted[2]);
        $converted_date = $converted['year'].'-'.$converted['month'].'-'.$converted['date'];

        return $converted_date;
    }

    public function store(Request $request)
    {
        $frequency = $request->all();
        if ($request->datetype == 'nep') {
            $frequency['check_date'] = $this->convertdate($frequency['check_date']);
            $frequency['period_start_date'] = $this->convertdate($frequency['period_start_date']);
            $frequency['period_end_date'] = $this->convertdate($frequency['period_end_date']);
        }

        $frequency['user_id'] = Auth::user()->id;
        $this->payfrequency->create($frequency);
        Flash::success('PayFrequency Sucessfully Created');

        return redirect('/admin/payroll/payfrequency');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Admin | PayFrequency';
        $page_description = "Update PayFrequency #{$id}";
        $frequency = ['H' => 'Hourly', 'W' => 'Weekly', 'M' => 'Monthly', 'B'=>'Biweekly'];
        $payfrequency = $this->payfrequency->find($id);
        if (! $payfrequency->iseditable()) {
            abort(403);
        }
        $fiscal_year = \App\Models\Fiscalyear::where('org_id', \Auth::user()->org_id)->get();

        return view('admin.payroll.payfrequency.edit', compact('payfrequency', 'frequency', 'page_title', 'page_description', 'fiscal_year'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $frequency = $this->payfrequency->find($id);
        $update = $request->all();

        $frequency->update($update);
        Flash::success('Pay frequency Sucessfully Updated');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payfrequency = $this->payfrequency->find($id);
        if (! $payfrequency->isdeletable()) {
            abort(403);
        }
        $payfrequency->delete();
        Flash::success('PayFrequency Sucessfully Deleted');

        return redirect('/admin/payroll/payfrequency');
    }

    public function getModalDelete($id)
    {
        $error = null;

        $modal_title = 'Delete PayFrequency';
        $modal_route = route('admin.payfrequency.delete', ['id' => $id]);

        $modal_body = "Are you sure you want to delete payfrequency with id #{$id}";

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function view_timecard(Request $request, $frequency_id)
    {
        if ($request->ajax()) {
            $update = ['remarks'=>$request->value];
            \App\Models\PayrollTimeCardReview::find($request->id)->update($update);

            return ['success'=>true];
        }
        $page_title = 'Timecard Review';
        $timecard = \App\Models\PayrollTimeCardReview::where('pay_frequency_id', $frequency_id)->get();
        $payfrequency = \App\Models\PayFrequency::find($frequency_id);
        if ($payfrequency->time_entry_method == 'T') {
            $view = 'timecard_timesheet';
        }
        if ($payfrequency->time_entry_method == 'W') {
            $view = 'timecard';
        }
        if ($view === null) {
            Flash::error('View File Not Found');

            return redirect()->back();
        }

        return view('admin.payroll.payfrequency.'.$view, compact('page_title', 'page_description', 'timecard', 'payfrequency', 'frequency_id'));
    }

    public function view_salarylist(Request $request, $frequency_id)
    {
        if ($request->ajax()) {
            $update = ['remarks'=>$request->value];
            \App\Models\PayrollEnterPay::find($request->id)->update($update);

            return ['success'=>true];
        }

        $payfrequency = \App\Models\PayFrequency::find($frequency_id);
        $enter_payroll = \App\Models\PayrollEnterPay::where('pay_frequency_id', $frequency_id)->get();
        $page_title = 'Enter Payroll';
        $totalarr = ['regular'=>0, 'overtime'=>0, 'sick'=>0, 'anual'=>0, 'public'=>0, 'other'=>0, 'total'=>0, 'taxamount'=>0, 'totalamountwithouttax'=>0];
        if ($payfrequency->time_entry_method == 'T') {
            $view = 'salary_list_timesheet';
        }
        if ($payfrequency->time_entry_method == 'W') {
            $view = 'salary_list';
        }

        return view('admin.payroll.payfrequency.'.$view, compact('page_title', 'totalarr', 'enter_payroll', 'payfrequency', 'frequency_id'));
    }

    public function run_payroll_filter()
    {
        $users = \App\User::all();
        $page_title = 'Payroll|Filter';
        $page_description = 'Filter Bulk Payment By User';

        return view('admin.payroll.payfrequency.filter.filter_salary', compact('users', 'page_title', 'page_description'));
    }

    public function userPayfrequecny($userid)
    {
        $frequency = $this->payfrequency->select('pay_frequency.*')
                            ->leftjoin('payroll_enter_pay','pay_frequency.id','=',
                                'payroll_enter_pay.pay_frequency_id')
                            ->where('payroll_enter_pay.user_id', $userid)
                            ->groupBy('pay_frequency.id')
                            ->get();

        return ['frequency'=>$frequency];
    }

    public function run_payroll_filterPost(Request $request)
    {
        $pay = \App\Models\PayrollEnterPay::where('user_id', $request->user_id)
                    ->where('pay_frequency_id', $request->payfrequency_id)
                    ->first();
        $timecard = \App\Models\PayrollTimeCardReview::where('user_id', $request->user_id)
        ->where('pay_frequency_id', $request->payfrequency_id)
        ->first();
        $html = view('admin.payroll.payfrequency.filter.filter_result', compact('pay', 'timecard'))->render();

        return ['result'=>$html];
    }
}
