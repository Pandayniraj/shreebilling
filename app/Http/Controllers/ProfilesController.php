<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Imap as Imap;
use App\User as User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ProfilesController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Audit
     */
    protected $audit;

    /**
     * @param User $user
     * @param Audit $audit
     * @param Imap $imap
     */
    public function __construct(User $user, Audit $audit, Imap $imap)
    {
        parent::__construct();
        $this->user = $user;
        $this->audit = $audit;
        $this->imap = $imap;
    }

    public function publicProfile($user_id)
    {
        Audit::log(Auth::user()->id, trans('admin/users/general.audit-log.category'), trans('admin/users/general.audit-log.msg-index'));

        $page_title = trans('admin/users/general.page.index.title'); // "Admin | Users";
        $page_description = trans('admin/users/general.page.index.description'); // "List of users";
        $user = $this->user->find($user_id);

        $user_detail = \App\Models\UserDetail::where('user_id', $user_id)->first();
        $leads = \App\Models\Lead::orderBy('id', 'desc')->where('user_id', $user_id)->get();
        $tasks = \App\Models\Task::orderBy('id', 'desc')->where('task_assign_to', $user_id)->get();


        $project_taskUser =  \App\Models\ProjectTaskUser::where('user_id',$user_id)->pluck('project_task_id')->toArray();

        $projects_tasks = \App\Models\ProjectTask::orderBy('id', 'desc')->whereIn('id',$project_taskUser)->orWhere('user_id', $user_id)->get();

        return view('admin.profiles.profile', compact('user', 'page_title', 'page_description', 'user_detail', 'leads', 'tasks', 'projects_tasks'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = $this->user->find(Auth::user()->id);
        //$user = $this->user->find(2);

        Audit::log(Auth::user()->id, trans('admin/profiles/general.audit-log.category'), trans('admin/profiles/general.audit-log.msg-show', ['username' => $user->username]));

        $page_title = trans('admin/profiles/general.page.show.title'); // "Admin | User | Show";
        $page_description = trans('admin/profiles/general.page.show.description', ['full_name' => $user->full_name]); // "Displaying user";

        return view('admin.profiles.show', compact('user', 'page_title', 'page_description'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = $this->user->find(Auth::user()->id);
        //$user = $this->user->find(2);

        Audit::log(Auth::user()->id, trans('admin/profiles/general.audit-log.category'), trans('admin/profiles/general.audit-log.msg-edit', ['username' => $user->username]));

        $page_title = trans('admin/profiles/general.page.edit.title'); // "Admin | User | Edit";
        $page_description = trans('admin/profiles/general.page.edit.description', ['full_name' => $user->full_name]); // "Editing user";

        if (! $user->isEditable()) {
            abort(403);
        }

        return view('admin.profiles.edit', compact('user', 'page_title', 'page_description'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        $id = Auth::user()->id;
        //$id = '2';
        $this->validate($request, ['username'          => 'required|unique:users,username,'.$id,
                                            'email'             => 'required|unique:users,email,'.$id,
                                            'first_name'        => 'required',
                                            'last_name'         => 'required',
        ]);

        $user = $this->user->find($id);

        // Get all attribute from the request.
        $attributes = $request->except('x', 'y', 'h', 'w', 'real_img', 'profilephoto');

        // Fix #17 as per @sloan58
        // Check if the password was submitted and has changed.
        if (! Hash::check($attributes['password'], $user->password) && $attributes['password'] != '') {
            // Password was changed, do nothing we are good.
        } else {
            // Password was not changed or was not submitted, delete attribute from array to prevent it
            // from being set to blank.
            unset($attributes['password']);
        }

        // Get a copy of the attributes that we will modify to save for a replay.
        $replayAtt = $attributes;
        // Add the id of the current user for the replay action.
        $replayAtt['id'] = $id;
        // Create log entry with replay data.
        $tmp = Audit::log(Auth::user()->id, trans('admin/profiles/general.audit-log.category'), trans('admin/profiles/general.audit-log.msg-update', ['username' => $user->username]),
            $replayAtt, "App\Http\Controllers\UsersController::ParseUpdateAuditLog", 'admin.profiles.replay-edit');

        if (! $user->isEditable()) {
            abort(403);
        }

        if (Request::file('profilephoto')) {
            if (Auth::user()->image) {
                $old_file = public_path().'/images/profiles/'.\Auth::user()->image;
                File::delete($old_file);
            }

            if (Request::get('x') == '') {
                $stamp = time();
                $imageUploadPath = '/images/profiles/';
                //file_upload
                $file = Request::file('profilephoto');
                //base_path() is proj root in laravel
                $destinationPath = public_path().$imageUploadPath;
                $filename = $file->getClientOriginalName();
                Request::file('profilephoto')->move($destinationPath, $stamp.$filename);

                //create second image as big image and delete original
                $image = \Image::make($destinationPath.$stamp.$filename)
                    ->save($destinationPath.$stamp.$filename);

                $attributes['image'] = $stamp.$filename;
            } else {
                $quality = 90;
                $stamp = time();
                //file_upload
                $file = Request::file('profilephoto');
                $filename = $file->getClientOriginalName();
                $src = public_path().'/images/profiles/'.$stamp.$filename;
                Request::file('profilephoto')->move(public_path().'/images/profiles/', $stamp.$filename);

                //$src  = public_path().\Request::get('real_img');
                $img = imagecreatefromjpeg($src);
                $dest = imagecreatetruecolor(Request::get('w'), Request::get('h'));

                imagecopyresampled($dest, $img, 0, 0, Request::get('x'),
                    Request::get('y'), Request::get('w'), Request::get('h'),
                    Request::get('w'), Request::get('h'));
                imagejpeg($dest, $src, $quality);

                $attributes['image'] = $stamp.$filename;
            }

            Session::forget('profileImage_'.\Auth::user()->id);
        }
        // else
        // {
        // 	if(Request::get('x') == '')
        // 	{
        // 		Flash::error('Photo not selected.');
        // 		return redirect('/admin/myprofile/edit');
        // 	}
        // 	else
        // 	{
        // 		$quality = 90;
        // 		$src  = public_path().\Request::get('real_img');
        // 		$img  = imagecreatefromjpeg($src);
        // 		$dest = ImageCreateTrueColor(Request::get('w'),\Request::get('h'));
        //
        // 		imagecopyresampled($dest, $img, 0, 0, Request::get('x'),
        // 			Request::get('y'), Request::get('w'), Request::get('h'),
        // 			Request::get('w'), Request::get('h'));
        // 		imagejpeg($dest, $src, $quality);
        //
        // 		$tmp_ = explode('/profiles/', Request::get('real_img'));
        // 		$attributes['image'] = $tmp_[1];
        // 	}
        // }

        $user->update($attributes);
        Flash::success(trans('admin/profiles/general.status.updated'));

        return redirect('/admin/myprofile/show');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function showimap()
    {
        $imap = $this->imap->where('user_id', Auth::user()->id)->first(0);
        //$imap = $this->imap->find(2);

        Audit::log(Auth::user()->id, trans('admin/profiles/general.audit-log.imapcategory'), trans('admin/profiles/general.audit-log.msg-imapshow', ['username' => Auth::user()->username]));

        $page_title = trans('admin/profiles/general.page.imapshow.title'); // "Admin | User | Show";
        $page_description = trans('admin/profiles/general.page.imapshow.description', ['full_name' => Auth::user()->full_name]);

        return view('admin.profiles.showimap', compact('imap', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function editimap()
    {
        $imap = $this->imap->where('user_id', Auth::user()->id)->first();
        //$imap = $this->imap->find(2);

        Audit::log(Auth::user()->id, trans('admin/profiles/general.audit-log.imapcategory'), trans('admin/profiles/general.audit-log.msg-imapedit', ['username' => Auth::user()->username]));

        $page_title = trans('admin/profiles/general.page.imapedit.title'); // "Admin | User | Edit";
        $page_description = trans('admin/profiles/general.page.imapedit.description', ['full_name' => Auth::user()->full_name]);

        return view('admin.profiles.editimap', compact('imap', 'page_title', 'page_description'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateimap(Request $request)
    {
        $id = Auth::user()->id;
        //$id = '2';
        $this->validate($request, ['imap_password'          => 'required',
                                            'imap_email'       		 => 'required',
        ]);

        // Get all attribute from the request.
        $attributes = $request->except('_token');
        $attributes['user_id'] = $id;

        $imap = $this->imap->where('user_id', $id)->first();
        if ($imap) {
            $imap->update($attributes);
            Audit::log(Auth::user()->id, trans('admin/profiles/general.audit-log.imapcategory'), trans('admin/profiles/general.audit-log.msg-imapupdate', ['name' => Auth::user()->username]));
            Flash::success(trans('admin/profiles/general.status.imapupdated'));
        } else {
            $imap = $this->imap->create($attributes);
            Audit::log(Auth::user()->id, trans('admin/profiles/general.audit-log.imapcategory'), trans('admin/profiles/general.audit-log.msg-imapstore', ['name' => Auth::user()->username]));
            Flash::success(trans('admin/profiles/general.status.imapstore'));
        }

        return redirect('/admin/myprofile/imap');
    }
}
