<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Organization;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    /**
     * @var communication
     */
    private $organization;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param communication $communication
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Organization $organization, Permission $permission)
    {
        parent::__construct();
        $this->organization = $organization;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        //    Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-index'));

        $organizations = Organization::all();
        $page_title = 'My Companies';
        $page_description = 'Companis Owned By this Organization';

        return view('admin.organization.index', compact('organizations', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $organization = $this->organization->find($id);

        Audit::log(Auth::user()->id, trans('admin/communications/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-show', ['name' => $communication->name]));

        $page_title = 'Organization'; // "Admin | communication | Show";
        $page_description = 'Organization list'; // "Displaying communication: :name";

        return view('admin.organization.show', compact('organization', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Admin | Organization | Create'; // "Admin | communication | Create";
        $page_description = 'create a new organization'; // "Creating a new communication";

        $perms = $this->permission->all();

        return view('admin.organization.create', compact('perms', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if (\Auth::user()->org_id != 1) {
            Flash::warning('You Cannot Create Organization');
            abort(404);
        }
        $this->validate($request, [
          'organization_name'=> 'required',

      ]);

        // $this->manageLedger($this->organization->find(4));
        // return 0;
        $attributes = $request->all();

        $attributes['enabled'] = isset($request->enabled) ? '1' : '0';

        if ($request->file('logo')) {
            $stamp = time();
            $file = $request->file('logo');
            //dd($file);
            $destinationPath = public_path().'/org/';

            $filename = $file->getClientOriginalName();
            $request->file('logo')->move($destinationPath, $stamp.'_'.$filename);

            $attributes['logo'] = $stamp.'_'.$filename;
        }
        if ($request->file('login_bg')) {
            $stamp = time();
            $file = $request->file('login_bg');
            //dd($file);
            $destinationPath = public_path().'/org/';

            $filename = $file->getClientOriginalName();
            $request->file('login_bg')->move($destinationPath, $stamp.'_'.$filename);

            $attributes['login_bg'] = $stamp.'_'.$filename;
        }


        $org = $this->organization->create($attributes);
        $this->manageLedger($org);
        //creating ledger_groups

        return redirect('/admin/organization');
    }

    private function manageLedger($org)
    {
        $coagroups = \App\Models\COAgroups::where('org_id', '1')->orderBy('id', 'asc')->get();
        foreach ($coagroups as $key => $value) {
            $attributes = [];
            if ($value->parent_id) {
                $parent = \App\Models\COAgroups::find($value->parent_id);
                $attributes['parent_id'] = \App\Models\COAgroups::where('org_id', $org->id)->where('name', $parent->name)->first()->id;
            } else {
                $attributes['parent_id'] = null;
            }
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = $org->id;
            $attributes['name'] = $value->name;
            $attributes['code'] = $value->code;
            $attributes['affects_gross'] = $value->affects_gross;
            \App\Models\COAgroups::create($attributes);
        }
        $legderSetting = \App\Models\LedgerSettings::where('org_id', '1')->where('table_name', 'coa_groups')->get();
        foreach ($legderSetting as $key => $value) {
            $attributes = [];
            $ledger_group_name = $value->coaledgerGroup->name;
            $attributes['ledger_id'] = \App\Models\COAgroups::where('org_id', $org->id)->where('name', $ledger_group_name)->first()->id;
            $attributes['ledger_name'] = $value->ledger_name;
            $attributes['table_name'] = $value->table_name;
            $attributes['org_id'] = $org->id;
            \App\Models\LedgerSettings::create($attributes);
        }
        //now doing same thing for coaledgers with adding is_default ledgers
        $defaultledgers = \App\Models\LedgerSettings::where('table_name','coa_ledgers')->where('is_default','1')->pluck('ledger_id');
        $coaledgers = \App\Models\COALedgers::where('org_id', '1')->whereIn('id', $defaultledgers)->orderBy('id', 'asc')->get();
        foreach ($coaledgers as $key => $value) {
            $attributes = [];
            $parent = \App\Models\COAgroups::find($value->group_id);
            $attributes['group_id'] = \App\Models\COAgroups::where('org_id', $org->id)->where('name', $parent->name)->first()->id;
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = $org->id;
            $attributes['name'] = $value->name;
            $attributes['code'] = \FinanceHelper::getNextCodeLedgers($attributes['group_id'], $org->id);
            $attributes['reconciliation'] = $value->reconciliation;
            \App\Models\COALedgers::create($attributes);
        }
        $legderSetting = \App\Models\LedgerSettings::where('org_id', '1')->where('table_name', 'coa_ledgers')->where('is_default','1')->get();
        foreach ($legderSetting as $key => $value) {
            $attributes = [];
            $ledger = $value->coaledgers;
            $newledgerId = \App\Models\COALedgers::where('org_id', $org->id)->where('name', $ledger->name)->first()->id;
            if($ledger && $newledgerId){
                $attributes['ledger_id'] = $newledgerId;
                $attributes['ledger_name'] = $value->ledger_name;
                $attributes['table_name'] = $value->table_name;
                $attributes['org_id'] = $org->id; 
                \App\Models\LedgerSettings::create($attributes);
            }
            
        }


        $entry_types = \App\Models\Entrytype::where('org_id', '1')->get();
        foreach ($entry_types as $key => $value) {
            $attributes = [];
            $attributes['org_id'] = $org->id;
            $attributes['label'] = $value->label;
            $attributes['name'] = $value->name;
            $attributes['base_type'] = $value->base_type;
            \App\Models\Entrytype::create($attributes);
        }

        return 0;
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $organizations = $this->organization->find($id);

        $page_title = 'edit'; // "Admin | communication | Edit";
        $page_description = 'editing'; // "Editing communication";

        return view('admin.organization.edit', compact('organizations', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        // $this->validate($request, [
        //    'logo' => 'required',
        //    'logo.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        // ]);

        if (\Auth::user()->org_id != 1) {
            Flash::warning('You Cannot Create Organization');
            abort(404);
        }
        $organization = $this->organization->find($id);
        //dd($organization);
        Audit::log(Auth::user()->id, trans('admin/organization/general.audit-log.category'), trans('admin/organization/general.audit-log.msg-update', ['name' => $organization->name]));

        $attributes = $request->all();

        if (! isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        if ($request->file('logo')) {
            // $stamp = time();
            // $file = $request->file('logo');
            // $destinationPath = public_path(). '/images/';

            // $filename = $file->getClientOriginalName();
            // $request->file('logo')->move($destinationPath, $stamp.'_'.$filename);

            // $attributes['logo'] = $stamp.'_'.$filename;

            $stamp = time();
            $destinationPath = public_path().'/org/';
            //file_upload
            $file = \Request::file('logo');
            //base_path() is proj root in laravel
            $filename = $file->getClientOriginalName();
            \Request::file('logo')->move($destinationPath, $stamp.$filename);

            //create second image as big image and delete original
            $image = \Image::make($destinationPath.$stamp.$filename)
            ->save($destinationPath.$stamp.$filename);

            $attributes['logo'] = $stamp.$filename;
        }

        if ($request->file('login_bg')) {
            $stamp = time();
            $file = $request->file('login_bg');
            //dd($file);
            $destinationPath = public_path().'/org/';

            $filename = $file->getClientOriginalName();
            $request->file('login_bg')->move($destinationPath, $stamp.'_'.$filename);

            $attributes['login_bg'] = $stamp.'_'.$filename;
        }
        if ($request->file('stamp')) {
            $stamp = time();
            $file = $request->file('stamp');
            //dd($file);
            $destinationPath = public_path().'/org/';

            $filename = $file->getClientOriginalName();
            $request->file('stamp')->move($destinationPath, $stamp.'_'.$filename);

            $attributes['stamp'] = $stamp.'_'.$filename;
        }

        $attributes['enabled'] = isset($request->enabled) ? '1' : '0';

        if ($organization->isEditable()) {
            $organization->update($attributes);
        }

        Flash::success(trans('admin/organization/general.status.updated')); // 'communication successfully updated');

        return redirect('/admin/organization');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $organization = Organization::find($id);

        if (! $organization->isdeletable()) {
            abort(403);
        }

        $organization->delete();

        Flash::success("Organization Successfully deleted"); // 'communication successfully deleted');

        return redirect()->back();
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

        $communication = Organization::find($id);

        if (! $communication->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/communication/dialog.delete-confirm.title');


        $modal_route = route('admin.organization.destroy',  $communication->id );

        $modal_body = trans('admin/communication/dialog.delete-confirm.body', ['id' => $communication->id, 'name' => $communication->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $communication = $this->communication->find($id);

        Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'),
            trans('admin/communication/general.audit-log.msg-enable', ['name' => $communication->name]));

        $communication->enabled = true;
        $communication->save();

        Flash::success(trans('admin/communication/general.status.enabled'));

        return redirect('/admin/organization');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $communication = $this->communication->find($id);

        Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-disabled', ['name' => $communication->name]));

        $communication->enabled = false;
        $communication->save();

        Flash::success(trans('admin/communication/general.status.disabled'));

        return redirect('/admin/organization');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkcommunication = $request->input('chkCommunication');

        Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-enabled-selected'), $chkcommunication);

        if (isset($chkcommunication)) {
            foreach ($chkcommunication as $communication_id) {
                $communication = $this->communication->find($communication_id);
                $communication->enabled = true;
                $communication->save();
            }
            Flash::success(trans('admin/communication/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/communication/general.status.no-communication-selected'));
        }

        return redirect('/admin/company');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkcommunication = $request->input('chkCommunication');

        Audit::log(Auth::user()->id, trans('admin/communication/general.audit-log.category'), trans('admin/communication/general.audit-log.msg-disabled-selected'), $chkcommunication);

        if (isset($chkcommunication)) {
            foreach ($chkcommunication as $communication_id) {
                $communication = $this->communication->find($communication_id);
                $communication->enabled = false;
                $communication->save();
            }
            Flash::success(trans('admin/communication/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/communication/general.status.no-communication-selected'));
        }

        return redirect('/admin/company');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $communication = $this->communication->pushCriteria(new communicationWhereDisplayNameLike($query))->all();

        foreach ($communication as $communication) {
            $id = $communication->id;
            $name = $communication->name;
            $email = $communication->email;

            $entry_arr = ['id' => $id, 'text' => "$name ($email)"];
            $return_arr[] = $entry_arr;
        }

        return $return_arr;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $communication = $this->communication->find($id);

        return $communication;
    }

    public function ajaxUpdateOrgDropdown(Request $request)
    {
        $id = $request->id;
        //  $attributes['org_id'] = $request->org_id;
        $user = \App\User::find($id);

        // $user->update($attributes);
        $user->updateOrg(['org_id' => $request->org_id]);

        return ['status'=>1];
    }
}
