<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Libraries\Arr;
use App\Libraries\Str;
use App\Models\Audit as Audit;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Organization;
use App\Models\Permission as Permission;
use App\Models\Role as Role;
use App\User as User;
use Config;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UsersController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Role
     */
    protected $role;

    /**
     * @var Permission
     */
    protected $perm;

    /**
     * @param User $user
     * @param Role $role
     */
    public function __construct(Application $app, Audit $audit, User $user, Role $role, Permission $perm)
    {
        parent::__construct($app, $audit);
        $this->user = $user;
        $this->role = $role;
        $this->perm = $perm;
        // Set default crumbtrail for controller.
        session(['crumbtrail.leaf' => 'users']);
        $this->themes = ['default', 'green', 'red', 'black', 'purple', 'yellow'];
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/users/general.audit-log.category'), trans('admin/users/general.audit-log.msg-index'));

        $page_title = trans('admin/users/general.page.index.title'); // "Admin | Users";
        $page_description = trans('admin/users/general.page.index.description'); // "List of users";

        $users = $this->user->where(function ($query) {
            $term = \Request::get('term');
            if ($term && $term != '') {
                return $query->where('id', $term)
                    ->orWhere('username', 'LIKE', '%' . $term . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $term . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $term . '%')
                    ->orWhere('email', 'LIKE', $term);
            }
        })->where(function ($query) {
            $designation_id = \Request::get('deg');
            if ($designation_id && $designation_id != '') {
                return $query->where('designations_id', $designation_id);
            }
        })->where(function ($query) {
            $department_id = \Request::get('dep');
            if ($department_id && $department_id != '') {
                return $query->where('departments_id', $department_id);
            }
        })->where(function ($query) {
            if (\Auth::user()->org_id != 1) {
                return $query->where('org_id', \Auth::user()->org_id);
            }
        })
            ->orderBy('username', 'asc')->paginate(30);

        $departments = \App\Models\Department::where(function ($query) {
            if (\Auth::user()->org_id != '1') {
                return $query->where('org_id', \Auth::user()->org_id);
            }
        })->get();
        $designations = \App\Models\Designation::where(function ($query) {
            if (\Auth::user()->org_id != '1') {
                return $query->where('org_id', \Auth::user()->org_id);
            }
        })->get();
        $projects = \App\Models\Projects::get();


        return view('admin.users.index', compact('users', 'page_title', 'page_description', 'departments', 'designations', 'projects'));
    }

    public function directory()
    {
        $page_title = 'Employee | Directory';
        $page_description = 'Staff Direcory';

        // $emp_directory_filter = function($query){

        //     if(!\Auth::user()->hasRole(['admins','hr-staff'])) {

        //         $users = \App\User::where('first_line_manager',\Auth::user()->id)->pluck('id')->toArray();

        //         $auth = [\Auth::user()->id];

        //         $users = array_merge($users,$auth);

        //         return $query->wherein('id',$users);


        //     }


        // };


        $users = $this->user->where(function ($query) {
            $term = \Request::get('term');
            if ($term && $term != '') {
                return $query->where('id', $term)
                    ->orWhere('username', 'LIKE', '%' . $term . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $term . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $term . '%')
                    ->orWhere('email', 'LIKE', $term);
            }
        })->where(function ($query) {
            $designation_id = \Request::get('deg');
            if ($designation_id && $designation_id != '') {
                return $query->where('designations_id', $designation_id);
            }
        })->where(function ($query) {
            $department_id = \Request::get('dep');
            if ($department_id && $department_id != '') {
                return $query->where('departments_id', $department_id);
            }
        })->where(function($query){

            $user_type = \Request::get('type');
            if($user_type && $user_type != ''){

                $isenabled = $user_type == '1' ? 1 : "0";
                
                return $query->where('enabled', $isenabled);
            }    

        })
       
        ->orderBy('first_name', 'asc')->paginate(30);

        $users_count = $this->user->count();
        $active_users = $this->user->where('enabled','1')->count();

        return view('admin.users.directory', compact('users', 'page_title', 'page_description','users_count','active_users'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = $this->user->find($id);

        Audit::log(Auth::user()->id, trans('admin/users/general.audit-log.category'), trans('admin/users/general.audit-log.msg-show', ['username' => $user->username]));

        $page_title = trans('admin/users/general.page.show.title'); // "Admin | User | Show";
        $page_description = trans('admin/users/general.page.show.description', ['full_name' => $user->full_name]); // "Displaying user";

        $perms = $this->perm->orderBy('name', 'ASC');

        $time_format = \Config::get('settings.time_format', null);
        $locales = \Config::get('settings.app_supportedLocales');
        $localeIdent = $user->settings()->get('locale', null);
        if (!Str::isNullOrEmptyString($localeIdent)) {
            $locale = $locales[$localeIdent];
        } else {
            $locale = '';
        }
        $themes = $this->themes;
        $themes = Arr::indexToAssoc($themes, true);

        return view('admin.users.show', compact('user', 'perms', 'theme', 'time_format', 'locale', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/users/general.page.create.title'); // "Admin | User | Create";
        $page_description = trans('admin/users/general.page.create.description'); // "Creating a new user";

        $perms = $perms = $this->perm->orderBy('name', 'ASC');
        $user = new \App\User();

        // $themes = \Theme::getList();
        // $themes = Arr::indexToAssoc($themes, true);
        $themes = $this->themes;
        $themes = Arr::indexToAssoc($themes, true);
        $time_zones = \DateTimeZone::listIdentifiers();
        // $time_zone = $user->settings()->get('time_zone', null);
        //$tzKey = array_search($time_zone, $time_zones);
        $tzKey =null;
        //  $time_format = $user->settings()->get('time_format', null);

        $locales = Config::get('settings.app_supportedLocales');

        $departments = Department::get();
        $organization = Organization::get();
        $projects = \App\Models\Projects::get();
        $users = \App\User::orderBy('first_name','asc')->get();


        return view('admin.users.create', compact('user', 'perms', 'themes', 'time_zones', 'tzKey',  'locales', 'page_title', 'page_description', 'departments', 'organization', 'projects','users'));
    }

    /**
     * @param CreateUserRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function getNextCodeLedgers($id, $org_id)
    {
        $group_data = \App\Models\COAgroups::find($id);
        $group_code = $group_data->code;

        $q = \App\Models\COALedgers::where('group_id', $id)->where('org_id', $org_id)->where('code', '!=', 'null')->get();

        if ($q) {
            $last = $q->last();
            $last = $last->code;
            $l_array = explode('-', $last);
            $new_index = end($l_array);
            $new_index += 1;
            $new_index = sprintf('%04d', $new_index);

            return $group_code . '-' . $new_index;
        } else {
            return $group_code . '-0001';
        }
    }

    private function PostLedgers($name, $org_id)
    {
        $detail = new \App\Models\COALedgers();
        $detail->group_id = \FinanceHelper::get_ledger_id('USER_LEDGER_GROUP', $org_id);

        $detail->org_id = $org_id;
        $detail->user_id = \Auth::user()->id;
        $detail->org_id = $org_id;
        $detail->code = $this->getNextCodeLedgers(\FinanceHelper::get_ledger_id('USER_LEDGER_GROUP', $org_id), $org_id);
        $detail->name = $name;
        $detail->op_balance_dc = 'D';
        $detail->op_balance = 0.00;
        $detail->notes = $name;
        $detail->ledger_type = 'Staff Group';
        $detail->staff_or_company_id = \FinanceHelper::get_ledger_id('USER_LEDGER_GROUP', $org_id);

        if ($request->type == 1) {
            $detail->type = $request->type;
        } else {
            $detail->type = 0;
        }
        if ($request->reconciliation == 1) {
            $detail->type = $request->reconciliation;
        } else {
            $detail->reconciliation = 0;
        }
        $detail->save();

        return $detail->id;
    }

    public function store(CreateUserRequest $request)
    {
        $this->validate($request, \app\User::getCreateValidationRules());

        $attributes = $request->all();

        Audit::log(Auth::user()->id, trans('admin/users/general.audit-log.category'), trans('admin/users/general.audit-log.msg-store', ['username' => $attributes['username']]));

        if ((array_key_exists('selected_roles', $attributes)) && (!empty($attributes['selected_roles']))) {
            $attributes['role'] = explode(',', $attributes['selected_roles']);
        } else {
            $attributes['role'] = [];
        }
        // Create basic user.
        $user = $this->user->create($attributes);
        $user->assign_default_role();
        $full_name = $user->first_name . ' ' . $user->last_name;
        $_ledgers = $this->PostLedgers($full_name, $user->org_id);

        // Run the update method to set enabled status and roles membership.
        $attributes['ledger_id'] = $_ledgers;
        $user->update($attributes);
        
        Flash::success(trans('admin/users/general.status.created')); // 'User successfully created');

        return redirect('/admin/users');
    }


 

    /**
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = $this->user->find($id);

        Audit::log(Auth::user()->id, trans('admin/users/general.audit-log.category'), trans('admin/users/general.audit-log.msg-edit', ['username' => $user->username]));

        $page_title = trans('admin/users/general.page.edit.title'); // "Admin | User | Edit";
        $page_description = trans('admin/users/general.page.edit.description', ['full_name' => $user->full_name]); // "Editing user";

        $roles = $this->role->orderBy('name', 'ASC');
        $perms = $this->perm->orderBy('name', 'ASC');

        // $themes = \Theme::getList();
        //dd($themes);
        $themes = $this->themes;
        $themes = Arr::indexToAssoc($themes, true);

        $time_zones = \DateTimeZone::listIdentifiers();
        //  $time_zone = $user->settings()->get('time_zone', null);
        // $tzKey = array_search($time_zone, $time_zones);
        $tzKey = null;
        // $time_format = $user->settings()->get('time_format', null);

        $locales = Config::get('settings.app_supportedLocales');

        $organization = Organization::get();
        $departments = Department::get();
        $projects = \App\Models\Projects::get();

        $designations = Designation::where('departments_id', $user->departments_id)->orderBy('designations_id', 'asc')->get();
        $users = \App\User::orderBy('first_name','asc')->get();
        

        return view('admin.users.edit', compact('user', 'roles', 'perms', 'themes',  'tzKey', 'locales', 'page_title', 'page_description', 'departments', 'designations', 'organization', 'projects','users'));
    }

    public static function ParseUpdateAuditLog($id)
    {
        $permsObj = [];
        $permsNoFound = [];
        $rolesObj = [];
        $rolesNotFound = [];

        $audit = \App\Models\Audit::find($id);
        $dataAtt = json_decode($audit->data, true);

        // Lookup and load the perms that we can still find, otherwise add to an separate array.
        if (array_key_exists('perms', $dataAtt)) {
            foreach ($dataAtt['perms'] as $id) {
                $perm = \App\Models\Permission::find($id);
                if ($perm) {
                    $permsObj[] = $perm;
                } else {
                    $permsNoFound[] = trans('admin/users/general.error.perm_not_found', ['id' => $id]);
                }
            }
        }
        $dataAtt['permsObj'] = $permsObj;
        $dataAtt['permsNotFound'] = $permsNoFound;

        // Lookup and load the roles that we can still find, otherwise add to an separate array.
        if (array_key_exists('selected_roles', $dataAtt)) {
            $aRolesIDs = explode(',', $dataAtt['selected_roles']);
            foreach ($aRolesIDs as $id) {
                $role = \App\Models\Role::find($id);
                if ($role) {
                    $rolesObj[] = $role;
                } else {
                    $rolesNotFound[] = trans('admin/users/general.error.perm_not_found', ['id' => $id]);
                }
            }
        }
        $dataAtt['rolesObj'] = $rolesObj;
        $dataAtt['rolesNotFound'] = $rolesNotFound;

        // Add the file name of the partial (blade) that will render this data.
        $dataAtt['show_partial'] = 'admin/users/_audit_log_data_viewer_update';

        return $dataAtt;
    }

    /**
     * Loads the audit log item from the id passed in, locate the relevant user, then overwrite all current attributes
     * of the user with the values from the audit log data field. Once the user saved, redirect to the edit page,
     * where the operator can inspect and further edit if needed.
     *
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function replayEdit($id)
    {
        // Loading the audit in question.
        $audit = $this->audit->find($id);
        // Getting the attributes from the data fields.
        $att = json_decode($audit->data, true);
        // Finding the user to operate on from the id field that was populated in the
        // edit action that created this audit record.
        $user = $this->user->find($att['id']);

        if (null == $user) {
            Flash::warning(trans('admin/users/general.error.user_not_found', ['id' => $att['id']]));

            return Redirect::route('admin.audit.index');
        }

        Audit::log(Auth::user()->id, trans('admin/users/general.audit-log.category'), trans('admin/users/general.audit-log.msg-replay-edit', ['username' => $user->username]));

        $page_title = trans('admin/users/general.page.edit.title'); // "Admin | User | Edit";
        $page_description = trans('admin/users/general.page.edit.description', ['full_name' => $user->full_name]); // "Editing user";

        if ($user->isRoot()) {
            abort(403);
        }

        // Setting user attributes with values from audit log to replay the requested action.
        // Password is not replayed.
        $user->first_name = $att['first_name'];
        $user->last_name = $att['last_name'];
        $user->username = $att['username'];
        $user->email = $att['email'];
        $user->enabled = $att['enabled'];
        if (array_key_exists('selected_roles', $att)) {
            $aRoleIDs = explode(',', $att['selected_roles']);
            $user->roles()->sync($aRoleIDs);
        }
        if (array_key_exists('perms', $att)) {
            $user->permissions()->sync($att['perms']);
        }
        $user->save();

        $roles = $this->role->all();
        $perms = $this->perm->all();

        // $themes = \Theme::getList();
        // $themes = Arr::indexToAssoc($themes, true);
        // $theme = $att['theme'];

        $time_zones = \DateTimeZone::listIdentifiers();
        $tzKey = $att['time_zone'];

        $time_format = $att['time_format'];

        $locales = Config::get('settings.app_supportedLocales');
        $locale = $att['locale'];

        return view('admin.users.edit', compact('user', 'roles', 'perms', 'themes', 'theme', 'time_zones', 'tzKey', 'time_format', 'locale', 'locales', 'page_title', 'page_description'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $this->validate($request, \app\User::getUpdateValidationRules($id));

        $user = $this->user->find($id);

        // Get all attribute from the request.
        $attributes = $request->all();

        // Set passwordChanged flag
        $passwordChanged = false;
        // Fix #17 as per @sloan58
        // Check if the password was submitted and has changed.
        if (!Hash::check($attributes['password'], $user->password) && $attributes['password'] != '') {
            // Password was changed, set flag for later.
            $passwordChanged = true;
        } else {
            // Password was not changed or was not submitted, delete attribute from array to prevent it
            // from being set to blank.
            unset($attributes['password']);
            // Set flag just to be sure
            $passwordChanged = false;
        }

        // Get a copy of the attributes that we will modify to save for a replay.
        $replayAtt = $attributes;
        // Add the id of the current user for the replay action.
        $replayAtt['id'] = $id;
        // Create log entry with replay data.
        $tmp = Audit::log(
            Auth::user()->id,
            trans('admin/users/general.audit-log.category'),
            trans('admin/users/general.audit-log.msg-update', ['username' => $user->username]),
            $replayAtt,
            "App\Http\Controllers\UsersController::ParseUpdateAuditLog",
            'admin.users.replay-edit'
        );

        if ((array_key_exists('selected_roles', $attributes)) && (!empty($attributes['selected_roles']))) {
            $attributes['role'] = explode(',', $attributes['selected_roles']);
        } else {
            $attributes['role'] = [];
        }

        if ($user->isRoot()) {
            // Prevent changes to some fields for the root user.
            unset($attributes['username']);
            unset($attributes['first_name']);
            unset($attributes['last_name']);
            unset($attributes['enabled']);
            unset($attributes['selected_roles']);
            unset($attributes['role']);
            unset($attributes['perms']);
        }

        $user->update($attributes);

        if ($passwordChanged) {
            $user->emailPasswordChange();
        }

        Flash::success(trans('admin/users/general.status.updated'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $user = $this->user->find($id);

        if (!$user->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/users/general.audit-log.category'), trans('admin/users/general.audit-log.msg-destroy', ['username' => $user->username]));

        $user->delete($id);

        Flash::success(trans('admin/users/general.status.deleted'));

        return redirect('/admin/users');
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

        $user = $this->user->find($id);

        if (!$user->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/users/dialog.delete-confirm.title');

        if (Auth::user()->id !== $id) {
            $user = $this->user->find($id);
            $modal_route = route('admin.users.delete', ['userId' => $user->id]);

            $modal_body = trans('admin/users/dialog.delete-confirm.body', ['id' => $user->id, 'full_name' => $user->full_name]);
        } else {
            $error = trans('admin/users/general.error.cant-delete-yourself');
        }

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $user = $this->user->find($id);

        Audit::log(Auth::user()->id, trans('admin/users/general.audit-log.category'), trans('admin/users/general.audit-log.msg-enable', ['username' => $user->username]));

        $user->enabled = true;
        $user->save();

        Flash::success(trans('admin/users/general.status.enabled'));

        return redirect('/admin/users');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $user = $this->user->find($id);

        if(!\Request::get('resgination_date') && \Request::ajax()){

            return view('admin.users.resgination_date',compact('user'));
        }



        if (!$user->canBeDisabled()) {
            Flash::error(trans('admin/users/general.error.cant-be-disabled'));
        } else {
            
            Audit::log(Auth::user()->id, trans('admin/users/general.audit-log.category'), trans('admin/users/general.audit-log.msg-disabled', ['username' => $user->username]));

            $user->enabled = false;
            
            $user->save();

            $userDetails = \App\Models\UserDetail::where('user_id',$user->id)->first();

            if($userDetails){
                
                $userDetails->update(['resgination_date'=>$request->resgination_date]);

            }
       
            Flash::success(trans('admin/users/general.status.disabled'));

            \Mail::send('emails.user-disable', compact('user'), function ($message) {
                $message->subject("User has been disabled");
                $message->from(env('APP_EMAIL'), env('APP_COMPANY'));
                $message->to(env('REPORT_EMAIL'), '');
            });

        }






        return redirect('/admin/users');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, trans('admin/users/general.audit-log.category'), trans('admin/users/general.audit-log.msg-enabled-selected'), $chkUsers);

        if (isset($chkUsers)) {
            foreach ($chkUsers as $user_id) {
                $user = $this->user->find($user_id);
                $user->enabled = true;
                $user->save();
            }
            Flash::success(trans('admin/users/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/users/general.status.no-user-selected'));
        }

        return redirect('/admin/users');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, trans('admin/users/general.audit-log.category'), trans('admin/users/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers)) {
            foreach ($chkUsers as $user_id) {
                $user = $this->user->find($user_id);
                if (!$user->canBeDisabled()) {
                    Flash::error(trans('admin/users/general.error.cant-be-disabled'));
                } else {
                    $user->enabled = false;
                    $user->save();
                }
            }
            Flash::success(trans('admin/users/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/users/general.status.no-user-selected'));
        }


        return redirect('/admin/users');
    }

    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $users = $this->user->where('first_name', 'LIKE', '%' . $query . '%')
            ->orWhere('last_name', 'LIKE', '%' . $query . '%')
            ->orWhere('username', 'LIKE', '%' . $query . '%')
            ->get();

        foreach ($users as $user) {
            $id = $user->id;
            $first_name = $user->first_name;
            $last_name = $user->last_name;
            $username = $user->username;

            $entry_arr = ['id' => $id, 'text' => "$first_name $last_name ($username)"];
            $return_arr[] = $entry_arr;
        }

        return $return_arr;
    }

    public function listByPage(Request $request)
    {
        $skipNumb = $request->input('s');
        $takeNumb = $request->input('t');

        $userCollection = \App\User::skip($skipNumb)->take($takeNumb)
            ->get(['id', 'first_name', 'last_name', 'username'])
            ->pluck('full_name_and_username', 'id');
        $userList = $userCollection->all();

        return $userList;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $user = $this->user->find($id);

        return $user;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user();

        Audit::log(Auth::user()->id, trans('general.audit-log.category-profile'), trans('general.audit-log.msg-profile-show', ['username' => $user->username]));

        $page_title = trans('general.page.profile.title');
        $page_description = trans('general.page.profile.description', ['full_name' => $user->full_name]);
        $readOnlyIfLDAP = ('ldap' == $user->auth_type) ? 'readonly' : '';
        $perms = $this->perm->orderBy('name', 'ASC');

        $themes = $this->themes;
        $themes = Arr::indexToAssoc($themes, true);
        $theme = $user->settings()->get('theme.'.\Auth::user()->org_id);

        $time_zones = \DateTimeZone::listIdentifiers();
        $time_zone = $user->settings()->get('time_zone');
        $tzKey = array_search($time_zone, $time_zones);

        $time_format = $user->settings()->get('time_format');

        $locales = Config::get('settings.app_supportedLocales');
        $locale = $user->settings()->get('locale');

        // Unset default crumbtrail set in controller ctor.
        session()->pull('crumbtrail.leaf');

        return view('user.profile', compact('user', 'perms', 'themes', 'theme', 'time_zones', 'tzKey', 'time_format', 'locale', 'locales', 'readOnlyIfLDAP', 'page_title', 'page_description'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function profileUpdate(UpdateUserRequest $request)
    {
        $user = Auth::user();

        $this->validate($request, \app\User::getUpdateValidationRules($user->id));

        Audit::log(Auth::user()->id, trans('general.audit-log.category-profile'), trans('general.audit-log.msg-profile-update', ['username' => $user->username]));

        // Get all attribute from the request.
        $attributes = $request->all();

        if ($request->file('image')) {
            if (Auth::user()->image) {
                $old_file = public_path() . '/images/profiles/' . \Auth::user()->image;
                File::delete($old_file);
            }

            $stamp = time();
            $imageUploadPath = '/images/profiles/';
            $file = \Request::file('image');
            $destinationPath = public_path() . $imageUploadPath;
            $filename = $file->getClientOriginalName();
            \Request::file('image')->move($destinationPath, $stamp . $filename);

            $image = \Image::make($destinationPath . $stamp . $filename)
                ->save($destinationPath . $stamp . $filename);

            $attributes['image'] = $stamp . $filename;
        }

        // Set passwordChanged flag
        $passwordChanged = false;
        // Fix #17 as per @sloan58
        // Check if the password was submitted and has changed.
        if (!Hash::check($attributes['password'], $user->password) && $attributes['password'] != '') {
            // Password was changed, set flag for later.
            $passwordChanged = true;
        } else {
            // Password was not changed or was not submitted, delete attribute from array to prevent it
            // from being set to blank.
            unset($attributes['password']);
            // Set flag just to be sure
            $passwordChanged = false;
        }
        // Prevent changes to some fields for the root user.
        if ($user->isRoot()) {
            unset($attributes['username']);
            unset($attributes['first_name']);
            unset($attributes['last_name']);
            unset($attributes['enabled']);
        }

        // Fix: Editing the profile does not allow to edit the Roles and permissions only to see them.
        // So load the attribute array with current roles and perms to prevent them from being erased.
        $role_ids = [];
        foreach ($user->roles as $role) {
            $role_ids[] = $role->id;
        }
        $attributes['role'] = $role_ids;

        $perm_ids = [];
        foreach ($user->permissions as $perm) {
            $perm_ids[] = $perm->id;
        }
        $attributes['perms'] = $perm_ids;

        // Update user properties.
        $user->update($attributes);
        if ($passwordChanged) {
            $user->emailPasswordChange();
        }

        Flash::success(trans('general.status.profile.updated'));

        return redirect()->route('user.profile');
    }

    public function ajaxGetDesignation(Request $request)
    {
        $designations = Designation::where('departments_id', $request->departments_id)->get();

        $data = '<option value="">Select Designation...</option>';

        foreach ($designations as $key => $value) {
            $data .= '<option value="' . $value->designations_id . '">' . $value->designations . '</option>';
        }

        return ['success' => 1, 'data' => $data];
    }

    public function ajaxUserUpdate(Request $request)
    {
        if ($request->type == 'projects') {
            $attributes['project_id'] = $request->update_value;
        } elseif ($request->type == 'departments') {
            $attributes['departments_id'] = $request->update_value;
            $attributes['designations_id'] = '0';
        } elseif ($request->type == 'designations') {
            $attributes['designations_id'] = $request->update_value;
        } elseif ($request->type == 'payroll_method') {
            $attributes['payroll_method'] = $request->update_value;
        } else {
            return ['status' => 404];
        }

        $user = $this->user->find($request->id);
        $user->update($attributes);

        return ['success' => 1];
    }

    public function getuserByDep($depid)
    {
        $users = $this->user->where(function ($query) use ($depid) {
            if ($depid != 'all') {
                return $query->where('departments_id', $depid);
            }
        })->where('enabled', '1')->get();
        if (\Request::get('avatar')) {
            $usersarr = [];
            foreach ($users as $key => $user) {
                $avatar = "$user->avatar";
                $usersarr[] = [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'designations' => $user->designation->designations,
                    'id' => $user->id,
                    'avatar' => $avatar,
                ];
            }
            $users = $usersarr;
        }

        return ['users' => $users];
    }

    public function get_line_manager($id){


        $user = $this->user->find($id);

        $first_line_mananger = $user->firstLineManger;

        return ['user'=>$user,'first_line_mananger'=>$first_line_mananger];


    }



    public function getemphierachy($uid){



        function create_treeArray($obj,$manager){

            if(!$obj->image){

                $avatar = "$obj->avatar";
   
            }else{
                $avatar = '/images/profiles/'.$obj->image;
            }
            return [
                'name'=>$obj->first_name.' '.$obj->last_name,
                'designation'=>$obj->designation->designations,
                'department'=>$obj->department->deptname,
                'id'=>$obj->id,
                'parentid'=>$manager->id ?? 0,
                'image'=>$avatar

            ];


        }


        function line_manager($user,$data = [],$dept = 0,$taken_user = []){

            $manager_first = $user->firstLineManger;
            
            $cancontinue = true;

            // if($dept > 2){
            //     dd($user->id);
            //     dd($taken_user);
            // }

            if($dept > 0){

                if(in_array( $user->id ,$taken_user)){

                    $cancontinue = false;


                }

            }


            if(!$manager_first){
                
                $cancontinue = false;

            }

            $dept++;

            

            $data[] = create_treeArray($user,$manager_first);  
            
            

            
            $taken_user[] = $user->id;
            if(!$cancontinue){

                return $data;
            }


          
            return  line_manager ( $manager_first, $data , $dept , $taken_user);
            

       


        }

        $data = line_manager($this->user->find($uid));
        $arr =$data;
        $arr = array_reverse($arr);
        $arr[0]['parentid'] = 0;
        function createTree(&$list, $parent){
            $tree = array();
            foreach ($parent as $k=>$l){
            
                if(isset($list[$l['id']])){
               
                    $l['children'] = createTree($list, $list[$l['id']]);
                }
                $tree[] = $l;
            } 
            return $tree;
        }
        $new = array();

        foreach ($arr as $a){
            $new[$a['parentid']][] = $a;
        }
       
        $tree = createTree($new, array($arr[0]));
        return $tree[0];


       





    }


    public function emphirarchy($uid){
        $user = $this->user->find($uid);
        $page_title = $user->first_name .' | Hierarchy';
        return view('admin.portfolio.user_hirarchy',compact('user','page_title'));
    }
    public function myhirarchy(){


        $user = \Auth::user();
        $page_title = $user->first_name .' | Hierarchy';
        return view('admin.portfolio.user_hirarchy',compact('user','page_title'));

    }

    public function assignid($id){

        $page_title = 'Assign Employee Id';
        
        $user = \App\User::find($id);

        return view('admin.users.assign_emp_id',compact('user'));
       


    }

    public function store_emp_id(Request $request,$id){


        $user = $this->user->find($id);

        $attributes = [ 'emp_id'=> $request->emp_id ];

        $check_duplicate = $this->user->where('id','!=',$id)->where('emp_id',$request->emp_id)->exists();

        if($check_duplicate){

            Flash::error("Employee id already taken");
            return redirect()->back();

        }


        $user->safeUpdate($attributes);

        Flash::success("Employee id successfully Updated");
        return redirect()->back();





    }



}
