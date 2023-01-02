<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Department;
use App\Models\Lead;
use App\Models\Leadstatus;
use App\Models\Role as Permission;
use App\Models\Task;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class ReportsController extends Controller
{
    /**
     * @var Contact
     */
    private $report;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param contact $contact
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, 'Reports', 'Accessed list of Reports.');

        $page_title = 'All Reports';
        $page_description = 'Get all the Reports';

        return view('admin.reports.index', compact('page_title', 'page_description'));
    }

    public function daily_sales_report()
    {
        /*$note_udpated = DB::table('users')
                    ->select(DB::raw('count(lead_notes.id) as total, users.first_name as name'))
                        ->join('lead_notes', 'users.id', '=', 'lead_notes.user_id')
                        ->where('lead_notes.updated_at', '>=', date('Y-m-d 00:01'))
                        ->where('lead_notes.updated_at', '<=', date('Y-m-d 23:59'))
                        ->groupBy('users.id')
                        ->orderBy('total','desc')
                        ->limit(10)
                        ->get();

        $page_title = 'Total Leads Followed';
        $page_description = 'List of total leads followed by users today';
        return view('admin.leads.today_followed', compact('note_udpated', 'page_title', 'page_description'));*/
    }

    public function leadsToday()
    {
        $data = DB::table('leads')
                    ->select('leads.title', 'leads.name', 'leads.description', 'leads.mob_phone', 'leads.home_phone', 'leads.address_line_1', 'leads.email', 'leads.price_value', 'leads.user_id')
                    ->join('lead_types', 'lead_types.id', '=', 'leads.lead_type_id')
                    ->where('lead_types.name', 'Target')
                    ->where('leads.created_at', '>=', date('Y-m-d 00:01'))
                    ->where('leads.created_at', '<=', date('Y-m-d 23:59'))
                    ->get();
        //dd($data);
        $pdf = \PDF::loadView('pdf.leadsToday', compact('data'));
        $file = 'Report_daily_sales_CRM_'.date('_Y_m_d').'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
        //$pdf->save('reports/'.$file);
    }

    public function leadsByStatus()
    {
        Audit::log(Auth::user()->id, 'Reports', 'Accessed list of Reports By Lead Status.');

        $page_title = 'Report - Leads By Status';
        $page_description = 'Get the reports of Leads by Status';

        $lead_status = Leadstatus::where('enabled', '1')->pluck('name', 'id');

        return view('admin.reports.leadStatus', compact('page_title', 'page_description', 'lead_status'));
    }

    public function postLeadsByStatus(Request $request)
    {
        $status_id = $request->input('status_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $data = Lead::select('title', 'name', 'description', 'mob_phone', 'home_phone', 'address_line_1', 'email', 'price_value', 'user_id', 'status_id')
                        ->whereBetween('created_at', [$start_date, $end_date])
                        ->where('enabled', '1')
                        ->where('status_id', $status_id)->get();
        //dd($start_date);

        $pdf = \PDF::loadView('pdf.reportLeadStatus', compact('data', 'start_date', 'end_date'));
        $file = 'Report_leads_by_status_CRM_'.date('_Y_m_d').'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function convertedLeads()
    {
        Audit::log(Auth::user()->id, 'Reports', 'Accessed list of Leads Converted Reports.');

        $page_title = 'Report - Leads Converted';
        $page_description = 'Get the reports of Leads converted';

        return view('admin.reports.leadConverted', compact('page_title', 'page_description'));
    }

    public function postConvertedLeads(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $data = Lead::select('leads.title', 'leads.name', 'leads.description', 'leads.mob_phone', 'leads.home_phone', 'leads.address_line_1', 'leads.email', 'leads.price_value', 'leads.user_id')
                    ->join('lead_types', 'lead_types.id', '=', 'leads.lead_type_id')
                    ->where('lead_types.name', 'Customer')
                    ->whereBetween('leads.created_at', [$start_date, $end_date])
                    ->where('leads.enabled', '1')
                    ->get();

        //dd($start_date);

        $pdf = \PDF::loadView('pdf.reportLeadConverted', compact('data', 'start_date', 'end_date'));
        $file = 'Report_leads_converted_CRM_'.date('_Y_m_d').'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function allActivities()
    {
        $data = Task::select('task_owner', 'task_type', 'task_subject', 'task_detail', 'task_status', 'task_due_date')
                    ->where('created_at', '>=', date('Y-m-d 00:01'))
                    ->where('created_at', '<=', date('Y-m-d 23:59'))
                    ->get();
        //dd($data);
        $pdf = \PDF::loadView('pdf.todaysActivities', compact('data'));
        $file = 'Report_todays_activities_CRM_'.date('_Y_m_d').'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function todayCallActivities()
    {
        $data = Task::select('task_owner', 'task_type', 'task_subject', 'task_detail', 'task_status', 'task_due_date')
                    ->whereIn('task_type', ['inbound', 'outbound'])
                    ->where('created_at', '>=', date('Y-m-d 00:01'))
                    ->where('created_at', '<=', date('Y-m-d 23:59'))
                    ->get();
        //dd($data);
        $pdf = \PDF::loadView('pdf.todaysCallActivities', compact('data'));
        $file = 'Report_todays_call_activities_CRM_'.date('_Y_m_d').'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function allContacts()
    {
        $data = Contact::select('client_id', 'salutation', 'full_name', 'email_1', 'phone', 'landline', 'city')
                    ->where('enabled', '1')
                    ->get();
        //dd($data);
        $pdf = \PDF::loadView('pdf.allContacts', compact('data'));
        $file = 'Report_All_Contacts_CRM_'.date('_Y_m_d').'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function allClients()
    {
        $data = Client::select('name', 'location', 'email', 'phone', 'industry')
                    ->where('enabled', '1')
                    ->get();
        //dd($data);
        $pdf = \PDF::loadView('pdf.allClients', compact('data'));
        $file = 'Report_All_Clients_CRM_'.date('_Y_m_d').'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function allPaymentDetails()
    {
        $data = \App\Models\Lead::select('leads.id as lead_id', 'users.first_name as owner_first_name', 'users.last_name as owner_last_name', 'invoice_name', 'phone_number', 'credit_recovery_date', 'total_amount', 'paid_amount', 'credit_amount')
        ->join('users', 'users.id', '=', 'leads.user_id')
        ->where('credit_recovery_date', \Carbon\Carbon::now()->format('Y-m-d'))->where('amount_type', 'credit')->get();
        $name = 'Recovery_Credit('.' '.$date.').xls';

        return \Excel::download(new \App\Exports\ExcelExport($data), $name);
    }

    public function payrollMonthlyReport()
    {
        $departments = Department::orderBy('deptname', 'asc')->pluck('deptname', 'departments_id')->all();

        return view('admin.payroll.reports.monthlyreport', compact('departments'));
    }

    public function postPayrollMonthlyReport(Request $request)
    {
        $departments_id = $request->input('departments_id');
        $month = $request->input('month');
        $users = DB::table('users')
                        ->select('first_name', 'last_name', 'id as user_id')
                        ->where('departments_id', $departments_id)
                        ->where('enabled', '1')
                        ->whereNotIn('users.id', [1, 2, 3, 28])
                        ->groupBy('users.id')
                        ->get();
        $date_start = $month.'-01';
        $date_end = $month.'-'.\Carbon\Carbon::parse($date_start)->daysInMonth;
        $tdata = [];
        $tgross_salary = 0;
        $tnet_sal = 0;
        $tovertime_money = 0;
        $tfine = 0;
        $total = 0;
        $dept_name = ucfirst((\App\Models\Department::where('departments_id', $departments_id)->first())->deptname);
        foreach ($users as $sk => $sv) {
            $data = [];
            $data['emp_id'] = $sv->user_id;
            $data['name'] = $sv->first_name.' '.$sv->last_name;
            $salary = \App\Models\SalaryPayment::where('user_id', $sv->user_id)->where('payment_month', $month)->first();
            $overtime_money = \TaskHelper::overtimesal($sv->user_id, $date_start, $date_end);
            if (count($salary)) {
                $data['salary_grade'] = $salary->salary_grade;
                $data['gross_salary'] = $salary->gross_salary;
                $data['net_sal'] = $salary->gross_salary + $salary->total_allowance - $salary->total_deduction;
                $data['overtime_money'] = $salary->overtime;
                $data['fine'] = $salary->fine_deduction;
                $data['total'] = $data['net_sal'] + $salary->overtime - $salary->fine_deduction;
                $data['status'] = 'Paid';
            } else {
                $template = \PayrollHelper::getEmployeePayroll($sv->user_id)->template;
                $data['salary_grade'] = $template->salary_grade;
                $data['gross_salary'] = $template->basic_salary;
                $net_salary = $template->basic_salary;
                $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id);
                $deductions = \PayrollHelper::getSalaryDeduction($template->salary_template_id);
                foreach ($allowances as $ak => $av) {
                    $net_salary += $av->allowance_value;
                }
                foreach ($deductions as $dk => $dv) {
                    $net_salary -= $dv->deduction_value;
                }
                $data['net_sal'] = $net_salary;
                $data['overtime_money'] = $overtime_money;
                $data['fine'] = 0;
                $data['total'] = $net_salary + $overtime_money;
                $data['status'] = 'Unpaid';
            }
            $tgross_salary = $tgross_salary + $data['gross_salary'];
            $tnet_sal = $tnet_sal + $data['net_sal'];
            $tovertime_money = $tovertime_money + $data['overtime_money'];
            $tfine = $tfine + $data['fine'];
            $total = $total + $data['total'];

            array_push($tdata, $data);
        }
        $summary = [
            '1'=>'',
            '2' => '',
            'salary_grade'=>'Total',
            '4' =>$tgross_salary,
            '5' =>$tnet_sal,
            '6' =>$tovertime_money,
            '7' =>$tfine,
            '8' =>$total,
        ];
        array_push($tdata, $summary);

        return \Excel::download(new \App\Exports\ExcelExport($tdata), 'monthly_payroll_report('.$month.')'.'.xls');
        // return \Excel::store('monthly_payroll_report('.$month.')', function ($excel) use ($tdata,$dept_name,$month) {
        //     $excel->sheet('mySheet', function ($sheet) use ($tdata,$dept_name,$month) {
        //         $sheet->fromArray($tdata);
        //         $sheet->prependRow([
        //             '', 'Department', $dept_name,
        //         ]);
        //         $sheet->prependRow(2, ['', 'Date', date('M Y', strtotime($month))]);
        //         $sheet->cell('B1:B2', function ($cell) {
        //             $cell->setFontWeight('bold');
        //         });
        //         $last_row = count($tdata) + 3;
        //         $sheet->cell('C'.$last_row.':H'.$last_row, function ($cell) {
        //             $cell->setFontWeight('bold');
        //             $cell->setBackground('#ffff00');
        //         });

        //         $sheet->cell('A3:I3', function ($cell) {
        //             $cell->setFontWeight('bold');
        //             $cell->setBackground('#ffff00');
        //         });
        //     });
        // })->download(xls);
    }

    // public function postPayrollMonthlyReportCopy(Request $request)
    // {

    //     $departments_id = $request->input('departments_id');
    //     $month = $request->input('month');

    //     $users = DB::table('users')
    //                     ->select('first_name', 'last_name', 'id as user_id')
    //                     ->where('departments_id', $request->departments_id)
    //                     ->whereNotIn('users.id', [1,2,3,28])
    //                     ->groupBy('users.id')
    //                     ->get();

    //     $data =[];

    //      foreach($users as $sk => $sv){

    //      $salary = \App\Models\SalaryPayment::where('user_id', $sv->user_id)->where('payment_month', $month)->first();

    //        if(sizeof($salary)){

    //           $net_salary = $salary->gross_salary + $salary->total_allowance - $salary->total_deduction - $salary->fine_deduction;

    //       }

    //          else{
    //         $template = \PayrollHelper::getEmployeePayroll($sv->user_id)->template;

    //         $net_salary = $template->basic_salary;
    //         $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id);
    //         $data[]= $allowances;

    //         $deductions = \PayrollHelper::getSalaryDeduction($template->salary_template_id);

    //         foreach($allowances as $ak => $av){
    //             $net_salary += $av->allowance_value;
    //         }

    //         foreach($deductions as $dk => $dv){
    //             $net_salary -= $dv->deduction_value;
    //         }

    //     }

    //      }

    //      dd($data);

    //     return \Excel::create('monthly_payroll_report('.$month.')', function($excel) use ($data) {
    //       $excel->sheet('mySheet', function($sheet) use ($data)
    //         {
    //             $sheet->fromArray($data);
    //         });
    //     })->download(xls);

    // }
}
