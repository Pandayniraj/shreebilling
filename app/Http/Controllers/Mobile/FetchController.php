<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Leadstatus;
use App\Models\Leadtype;
use App\Models\Phonelogs;
use App\Models\Product;
use App\Models\Projects;
use App\Models\ProjectTask;
use App\Models\ProjectTaskCategory;
use App\Models\Task;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FetchController extends Controller
{
    private $authuser;


    public function __construct(){


        \Log::error('this error',[url()->full(),'ok']);
    }


    private function checkroles($users)
    {
        if ($users->id != 1 && $users->id != 5 && $users->id != 4 && $users->id != 3 && ! $users->hasRole('admins')) {
            return false;
        }

        return true;
    }

    public function product()
    {
        $data = Product::all('id', 'name');

        return ['data'=>$data];
    }

    public function status()
    {
        $data = Leadstatus::all('id', 'name');

        return ['data'=>$data];
    }

    public function lead_type()
    {
        $data = Leadtype::all('id', 'name');

        return ['data'=>$data];
    }

    public function Leads(Request $request)
    {
        $user_id = $request->user_id;
        $users = \App\User::find($user_id);

        $sql = Lead::select('leads.name', 'leads.target_date', 'leads.description', 'lead_types.name as lead_name', 'products.name as product_name', 'mob_phone', 'leads.created_at', 'lead_types.id as lead_types_id', 'leads.id as lead_id', 'leads.email as email')
        ->leftjoin('products', 'products.id', '=', 'leads.product_id')
        ->leftjoin('lead_types', 'lead_types.id', '=', 'leads.lead_type_id')
        
        ->where('leads.lead_type_id', '1')
        ->orWhere('leads.lead_type_id', '2')
        ->orderBy('lead_id', 'desc')
        ->get()
        ->take(100);

        $toReturn = [];

        foreach($sql as $value){
            $value['lead_name'] = $value['lead_name'] ?? '';
            $value['product_name'] = $value['product_name'] ?? '';
            $toReturn [] = $value;
        }


        
        return ['data'=>$toReturn];
    }

    public function sendContact(Request $request)
    {
        $user_id = $request->user_id;
        $sql = Contact::select('full_name', 'phone', 'created_at')->where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        return ['data'=>$sql];
    }

    public function countTask(Request $request)
    {
        $uid = $request->userid;
        $count_task = Task::where('task_status', '<>', 'Completed')->where('task_assign_to', $uid)->count();

        return ['data'=>$count_task];
    }

    public function allTask(Request $request)
    {
        $uid = $request->userid;
        $_task = DB::select("select users.username as task_owner,tasks.id,tasks.task_start_date,tasks.task_detail,tasks.task_subject,tasks.task_status,tasks.task_priority, tasks.task_due_date from tasks LEFT JOIN users ON tasks.task_owner=users.id where tasks.task_assign_to='$uid' and tasks.task_status<>'Completed' ORDER BY tasks.created_at desc");

        return ['data'=>$_task];
    }

    public function getcallLogs(Request $request)
    {
        $userid = $request->userid;
        $data = PhoneLogs::select('leads.name', 'phonelogs.mob_phone', 'phonelogs.created_at')->leftjoin('leads', 'leads.id', '=', 'phonelogs.lead_id')->where('phonelogs.userid', $userid)->get();

        return ['data'=>$data];
    }

    public function sendUser()
    {
        $users = User::select('id', 'username')->get();

        return ['data'=>$users];
    }

    public function leadTask(Request $request)
    {
        if (isset($request->leadid)) {
            $leadid = $request->leadid;
            $_task = DB::select("select users.username as task_owner,tasks.id,tasks.task_start_date,tasks.task_detail,tasks.task_subject,tasks.task_status,tasks.task_priority, tasks.task_due_date from tasks LEFT JOIN users ON tasks.task_owner=users.id where tasks.lead_id='$leadid'");

            return ['data'=>$_task];
        }
    }

    public function projectTaskCat()
    {
        $cat = ProjectTaskCategory::select('id', 'name')->get();

        return ['data'=>$cat];
    }

    public function projectID()
    {
        $pro = Projects::select('id', 'name')->get();

        return ['data'=>$pro];
    }

    public function showSupport($id)
    {
        $case = Cases::select('cases.id', 'cases.created_at', 'cases.subject', 'cases.description', 'cases.attachment', 'cases.status', 'users.username as assigned_to')->leftjoin('users', 'cases.assigned_to', '=', 'users.id')->where('cases.user_id', $id)->where('cases.status', '<>', 'closed')->orderBy('cases.created_at', 'desc')->get();

        return ['data'=>$case];
    }

    public function showProjectTask($id)
    {
        $case = ProjectTask::select('project_tasks.id', 'project_tasks.start_date', 'project_tasks.end_date', 'project_tasks.subject', 'project_tasks.description', 'project_tasks.status', 'project_tasks.created_at', 'project_tasks.attachment', 'projects.name as project', 'project_task_categories.name as project_task_categories', 'users.username')->leftjoin('projects', 'project_tasks.project_id', '=', 'projects.id')->leftjoin('project_task_categories', 'project_task_categories.id', '=', 'project_tasks.category_id')->leftjoin('users', 'users.id', '=', 'project_tasks.peoples')->where('project_tasks.user_id', $id)->where('project_tasks.status', '<>', 'completed')->orderBy('project_tasks.created_at', 'desc')->get();

        return ['data'=>$case];
    }
}
