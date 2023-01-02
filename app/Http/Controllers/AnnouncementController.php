<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\ReadAnnouncement;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class AnnouncementController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Permission $permission
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $page_title = 'Announcement';
        $page_description = 'All Announcements';

        //delete cookie
        if (\Request::get('deletecookie') == 'yes') {

            //\Cookie::forget('announce');
            Cookie::queue(Cookie::forget('announce'));

            return Redirect::back();
        }

        $departments = \App\Models\Department::orderBy('departments_id', 'desc')->get();

        $teams = \App\Models\Team::orderBy('id', 'desc')->get();

        //dd($teams);

        //dd($departments);
        $announcements = Announcement::where('org_id', Auth::user()->org_id)->orderBy('created_at', 'desc')->get();

        return view('admin.announcement.index', compact('page_title', 'page_description', 'announcements', 'departments', 'teams'));
    }

    public function save(Request $request)
    {
        /* if(!$announcement->isEditable())
        {
            abort(403);
        } */

        $attributes = $request->except('_token');

        if ($request->announcements_id == '') {
            $attributes['user_id'] = Auth::user()->id;
            $attributes['org_id'] = Auth::user()->org_id;

            $announcement = Announcement::create($attributes);

            if ($request->placement == 'email') {
                if ($request->share_with == 'department') {
                    $users = \App\User::where('departments_id', $request->department_id)->where('enabled', '1')->pluck('email')->all();
                } elseif ($request->share_with == 'team') {
                    $users_id = \App\Models\UserTeam::where('team_id', $request->team_id)->select('user_id')->get()->toArray();
                    $users = \App\User::whereIn('id', $users_id)->where('enabled', '1')->pluck('email')->all();

                //dd($users);
                } else {
                    $users = \App\User::orderBy('id', 'desc')->where('enabled', '1')->pluck('email')->all();
                }

                //dd($users);

                try {
                    Mail::send('emails.announcement-create', compact('announcement'), function ($message) use ($attributes, $request, $users, $announcement) {
                        $message->subject('Announcement: '.$announcement->title);
                        $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
                        $message->to($users);
                    });
                } catch (\Exception $e) {
                    Flash::error('Error in sending Mail');
                }
            }

            //dd($announcement);

            Flash::success('Announcement created Successfully');
        } else {
            Announcement::where('announcements_id', $request->announcements_id)->update($attributes);
            Flash::success('Announcement updated Successfully');
        }

        return Redirect::back()->withCookie(cookie()->forever('announce', serialize($attributes)));
    }

    public function edit($announcements_id)
    {
        $announcement = Announcement::where('announcements_id', $announcements_id)->first();

        $departments = \App\Models\Department::orderBy('departments_id', 'desc')->get();

        $teams = \App\Models\Team::orderBy('id', 'desc')->get();

        $returnHTML = view('admin.announcement.modal', ['announcement' => $announcement, 'departments' => $departments, 'teams' => $teams])->render();

        return $returnHTML;
        //return response()->json(['success' => true, 'html' => $returnHTML]);
    }

    public function show($announcements_id)
    {
        $announcement = Announcement::where('announcements_id', $announcements_id)->first();

        $data = '<div class="panel panel-custom">
                <div class="panel-heading">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Announcements Details</h4>
                </div>
                <div class="modal-body wrap-modal wrap">
                    <div class="panel-body form-horizontal">
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label"><strong>Title :</strong></label>
                            </div>
                            <div class="col-sm-8">
                                <p class="form-control-static">'.$announcement->title.'</p>
                            </div>
                        </div>
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Start Date :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">'.$announcement->start_date.'</p>
                            </div>
                        </div>
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">End Date :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">'.$announcement->end_date.'</p>
                            </div>
                        </div>

                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label"><strong>Created Date :</strong></label>
                            </div>
                            <div class="col-sm-8">
                                <p class="form-control-static"><span class="text-danger">'.date('Y-m-d', strtotime($announcement->created_at)).'</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label"><strong>Status :</strong></label>
                            </div>
                            <div class="col-sm-8">
                                <p class="form-control-static">'.ucfirst($announcement->status).'</p>
                            </div>
                        </div>';

        if ($announcement->all_client) {
            $data .= '<div class="col-md-12 notice-details-margin">
                                    <div class="col-sm-4 text-right">
                                        <label class="control-label"><strong>Share With :</strong></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="form-control-static">
                                            <span class="label label-info">Client</span>
                                        </p>
                                    </div>
                                </div>';
        }

        $data .= '
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label"><strong>Description :</strong></label>
                            </div>
                            <div class="col-sm-8">
                                <blockquote style="font-size: 12px">'.$announcement->description.'</blockquote>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>';

        return $data;
    }

    public function delete($announcements_id)
    {
        $announcements = Announcement::where('announcements_id', $announcements_id)->first();
        if (! $announcements->isDeletable()) {
            abort(403);
        }

        Announcement::where('announcements_id', $announcements_id)->delete();
        Flash::success('Announcement deleted Successfully');

        return Redirect::back();
    }

    public function printNow()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->get();

        return view('admin.announcement.print', compact('announcements'));
    }

    public function ReadAnnouncement($id)
    {

        // dd($id);
        $readannouncement = new ReadAnnouncement();
        $readannouncement->user_id = Auth::user()->id;
        $readannouncement->announcement_id = $id;
        $readannouncement->read_announce = 1;
        $readannouncement->save();

        return Redirect::back();
    }

    public function generatePdf()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->get();

        $pdf = \PDF::loadView('admin.announcement.generatePdf', compact('announcements'));
        $file = 'All_Announcements.pdf';

        return $pdf->download($file);
    }
}
