<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Phonelogs;
use App\Models\ProjectTask;
use App\Models\Task;
use App\Models\Usertoken;
use Auth;
use Illuminate\Http\Request;

class LeadData extends Controller
{
    private $authuser;

    public function __construct(Request $request)
    {
        $token = $request->token;
        if ($request->secret == 'apptesting9841') { //just for testing bug need to be removed
            return true;
        }
        $checktoken = \App\Models\Usertoken::where('token', $token)->first();
   
        if (! $checktoken) {
            abort(404);
        } else {
            $this->userinfo = $checktoken;
            Auth::loginUsingId($checktoken->user_id);
        }

        $this->authuser = $checktoken;
    }

    public function importContact(Request $request)
    {
        $contact = $request->data;
        $contact = json_decode($contact);
        foreach ($contact as $key) {
            $data = (array) ($key);
            $sql = Contact::create($data);
        }
    }

    public function addTask(Request $request)
    {
        $task = $request->data;
        $task = (array) json_decode($task);
        unset($task['timea']);
        unset($task['timeb']);
        $task['task_status'] = 'Started';
        $sql = Task::create($task);
    }

    public function completeTask(Request $request)
    {
        $uid = $request->userid;
        $taskid = $request->taskid;
        Task::where('id', $taskid)->where('task_assign_to', $uid)->update(['task_status'=>'Completed']);

        return ['res'=>true];
    }

    public function addcallLogs(Request $request)
    {
        $data = $request->all();
        if ($request->mob_phone != 0) {
            if (PhoneLogs::create($data)) {
                return ['res'=>true];
            }
        }
    }

    public function PostLead(Request $request)
    {
        $enq = $request->data;
        $enq = (array) json_decode($enq);
        $enq['org_id'] = 1;
        $sql = Lead::create($enq);
    }

    public function supportCase(Request $request)
    {
        $case = $request->data;
        $case = (array) json_decode($case);
        $case['type'] = 'ticket';
        $case['status'] = 'new';

        if (isset($request->attachment)) {
            $image = $request->attachment;
            $img_name = time().''.rand().''.'.jpg';
            $destinationPath = public_path('/case_attachments/');
            file_put_contents($destinationPath.$img_name, base64_decode($image));
            $case['attachment'] = $img_name;
        }
        if (Cases::create($case)) {
            return ['d'=>'sucess'];
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }

    public function closeSupport($id, Request $request)
    {
        $uid = $request->userid;
        if (Cases::where('id', $id)->where('user_id', $uid)->update(['status'=>'closed'])) {
            return ['res'=>true];
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }

    public function projectTask(Request $request)
    {
        $project = $request->data;
        $project = (array) json_decode($project);
        $project['status'] = 'new';
        $project['enabled'] = 1;
        if (isset($request->attachment)) {
            $image = $request->attachment;
            $img_name = time().''.rand().''.'.jpg';
            $destinationPath = public_path('/task_attachments/');
            file_put_contents($destinationPath.$img_name, base64_decode($image));
            $project['attachment'] = $img_name;
        }
        if (ProjectTask::create($project)) {
            return ['d'=>'sucess'];
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }

    public function closeProjectTask($id, Request $request)
    {
        $uid = $request->userid;
        if (ProjectTask::where('id', $id)->where('user_id', $uid)->update(['status'=>'completed'])) {
            return ['res'=>true];
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }
}
