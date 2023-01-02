<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Http\Request;

class GoogleCalendarController extends Controller
{
    public function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig('client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);

        $guzzleClient = new \GuzzleHttp\Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false]]);
        $client->setHttpClient($guzzleClient);
        $this->client = $client;
    }

    public function syncLeadTaskWithGoogle(Request $request)
    {
        session_start();
        // $_SESSION['access_token'] = null;
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';
            $tasks = \App\Models\Task::where('org_id', \Auth::user()->org_id)
                    ->where('synced_with_google', '0')
                    ->whereNotNull('task_start_date')
                    ->whereNotNull('task_due_date')
                    ->get();
            // $service->calendars->clear('primary');
            // dd("STOP");
            foreach ($tasks as $le) {
                try {
                    $event = new Google_Service_Calendar_Event([
                    'summary' => $le->task_subject,
                    'description' => $le->task_detail,
                    'start' => ['dateTime' => date('c', strtotime($le->task_start_date))],
                    'end' => ['dateTime' =>date('c', strtotime($le->task_due_date))],
                    'reminders' => ['useDefault' => true],
                ]);
                    $service->events->insert($calendarId, $event);
                    $le->update(['synced_with_google'=>'1']);
                } catch (\Exception $e) {
                    if ($e->getCode() == 401) {
                        return ['status'=>false, 'url'=>route('oauthCallback')];
                    }
                    // dd(date('c',strtotime($le->task_start_date)),date('c',strtotime($le->task_due_date)));
                }
            }

            return ['status'=>true];
        } else {
            return ['status'=>false, 'url'=>route('oauthCallback')];
        }
    }

    public function syncProjectTaskWithGoogle(Request $request)
    {
        session_start();
        // $_SESSION['access_token'] = null;
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';
            // $service->calendars->clear('primary');
            // dd("STOP");
            if (\Auth::user()->hasRole('admins')) {
                $tasks = \App\Models\ProjectTask::leftJoin('project_task_user', 'project_task_user.project_task_id', '=', 'project_tasks.id')
                        ->select('project_tasks.id', 'project_tasks.subject as title', 'project_tasks.start_date as start', 'project_tasks.end_date as end','project_tasks.color as color','project_tasks.start_date as start_date',
                            'project_tasks.description as description', 'project_tasks.project_id')
                        ->where(function ($query) use ($selected_project) {
                            if ($selected_project) {
                                return $query->where('project_tasks.project_id', $selected_project);
                            }
                        })->whereNotNull('end_date')
                         ->where('end_date', '!=', '0000-00-00 00:00:00')
                         ->whereNotNull('start_date')
                         ->where('start_date', '!=', '0000-00-00 00:00:00')
                        ->where('synced_with_google', '0')
                        ->orderBy('project_tasks.created_at', 'desc')
                        ->get();
            } else {
                $tasks = \App\Models\ProjectTask::leftJoin('project_task_user', 'project_task_user.project_task_id', '=', 'project_tasks.id')
                        ->select('project_tasks.id', 'project_tasks.subject as title', 'project_tasks.start_date as start', 'project_tasks.end_date as end','project_tasks.color as color','project_tasks.start_date as start_date',
                            'project_tasks.description as description', 'project_tasks.project_id')
                        ->where('project_task_user.user_id', \Auth::user()->id)
                        ->where(function ($query) use ($selected_project) {
                            if ($selected_project) {
                                return $query->where('project_tasks.project_id', $selected_project);
                            }
                        })->whereNotNull('end_date')
                         ->where('end_date', '!=', '0000-00-00 00:00:00')
                        ->whereNotNull('start_date')
                         ->where('start_date', '!=', '0000-00-00 00:00:00')
                          ->where('synced_with_google', '0')
                        ->orderBy('project_tasks.created_at', 'desc')
                      //  ->groupBy('project_tasks.id')
                        ->get();
            }
            //dd($tasks);
            foreach ($tasks as $le) {
                try {
                    $event = new Google_Service_Calendar_Event([
                    'summary' => $le->title,
                    'description' => $le->description,
                    'start' => ['dateTime' => date('c', strtotime($le->start))],
                    'end' => ['dateTime' =>date('c', strtotime($le->end))],
                    'reminders' => ['useDefault' => true],
                ]);
                    $service->events->insert($calendarId, $event);
                    $le->update(['synced_with_google'=>'1']);
                } catch (\Exception $e) {
                    if ($e->getCode() == 401) {
                        return ['status'=>false, 'url'=>route('oauthCallback')];
                    }
                    // dd(date('c',strtotime($le->task_start_date)),date('c',strtotime($le->task_due_date)));
                }
            }

            return ['status'=>true];
        } else {
            return ['status'=>false, 'url'=>route('oauthCallback')];
        }
    }

    public function oauth()
    {
        session_start();

        $rurl = action('GoogleCalendarController@oauth');
        $this->client->setRedirectUri($rurl);
        if (! isset($_GET['code'])) {
            $auth_url = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);

            return redirect($filtered_url);
        } else {
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            \Flash::success('SucceFully Authenticated Now You Can Sync With Google');

            return redirect()->route('home');
        }
    }
}
