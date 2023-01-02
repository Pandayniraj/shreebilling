<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Cases;
use App\Models\Client;
use App\Models\Contact as Contact;
use App\Models\Contact as ContactModel;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class ContactsController extends Controller
{
    /**
     * @var Contact
     */
    private $contact;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param contact $contact
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Contact $contact, Permission $permission)
    {
        parent::__construct();
        $this->contact = $contact;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/contacts/general.audit-log.category'), trans('admin/contacts/general.audit-log.msg-index'));
        
        $contacts = ContactModel::where('org_id', Auth::user()->org_id)
                                ->where(function ($query) {
                                    if (\Request::get('term') && \Request::get('term') != '') {
                                        return $query->where('full_name', 'LIKE', '%'.\Request::get('term').'%');
                                    }
                                })
                                ->orWhere(function ($query) {
                                    if (\Request::get('term') && \Request::get('term') != '') {
                                        return $query->where('position', 'LIKE', '%'.\Request::get('term').'%');
                                    }
                                })
                                ->orWhere(function ($query) {
                                    if (\Request::get('term') && \Request::get('term') != '') {
                                        return $query->where('phone', \Request::get('term'));
                                    }
                                })
                                ->orWhere(function ($query) {
                                    if (\Request::get('term') && \Request::get('term') != '') {
                                        return $query->where('email_1', 'LIKE', '%'.\Request::get('term').'%');
                                    }
                                })
                                ->orderBy('id', 'DESC')->paginate(26);
        $contacts_count = ContactModel::count();
        $page_title = trans('admin/contacts/general.page.index.title');
        $page_description = trans('admin/contacts/general.page.index.description');

        return view('admin.contacts.index', compact('contacts', 'page_title', 'page_description','contacts_count'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $contact = $this->contact->find($id);

        Audit::log(Auth::user()->id, trans('admin/contacts/general.audit-log.category'), trans('admin/contacts/general.audit-log.msg-show', ['name' => $contact->full_name]));

        $page_title = trans('admin/contacts/general.page.show.title'); // "Admin | contact | Show";
        $page_description = trans('admin/contacts/general.page.show.description'); // "Displaying contact: :name";

        return view('admin.contacts.show', compact('contact', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/contacts/general.page.create.title'); // "Admin | contact | Create";
        $page_description = trans('admin/contacts/general.page.create.description'); // "Creating a new contact";

        $clients = Client::where('enabled', '1')->pluck('name', 'id')->all();
        $contact = new \App\Models\Contact();

        $perms = $this->permission->all();
        $tags = \App\Models\Contacttag::all()->pluck('name', 'id');

        return view('admin.contacts.create', compact('clients', 'contact', 'perms', 'page_title', 'page_description','tags'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'full_name'      => 'required',
            'email_1'   => 'email',
            //'email_2'   => 'email',
            //'website'   => 'active_url',
            'phone'    => 'min:4|max:255',
            'landline' => 'min:4|max:255',
            //'file_name'      => 'required',

            ]);

        $attributes = $request->all();
        $attributes['org_id'] = Auth::user()->org_id;

        $temp_client = Client::where('name', $request['client_id'])->first();
        if (! $temp_client) {
            $attributes['client_id'] = 1;
        //Flash::warning('Please select the valid Client.');
            //return Redirect::back()->withInput(Request::all());
        } else {
            $attributes['client_id'] = $temp_client->id;
        }

        if (! isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        if ($request->file('file_name')) {
            $stamp = time();
            $file = $request->file('file_name');
            //dd($file);
            $destinationPath = public_path().'/contacts/';

            $filename = $file->getClientOriginalName();
            $request->file('file_name')->move($destinationPath, $stamp.'_'.$filename);

            $attributes['file'] = $stamp.'_'.$filename;
        }

        $contacts = $this->contact->create($attributes);

        Audit::log(Auth::user()->id, trans('admin/contacts/general.audit-log.category'), trans('admin/contacts/general.audit-log.msg-store', ['name' => $attributes['full_name']]));

        if ($request->ajax()) {
            return ['contacts'=>$contacts];
        }

        Flash::success(trans('admin/contacts/general.status.created')); // 'contact successfully created');

        return redirect('/admin/contacts');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $clients = Client::where('enabled', '1')->pluck('name', 'id')->all();
        $contact = $this->contact->find($id);

        Audit::log(Auth::user()->id, trans('admin/contacts/general.audit-log.category'), trans('admin/contacts/general.audit-log.msg-edit', ['name' => $contact->full_name]));

        $page_title = trans('admin/contacts/general.page.edit.title'); // "Admin | contact | Edit";
        $page_description = trans('admin/contacts/general.page.edit.description', ['name' => $contact->full_name]); // "Editing contact";
        $tags = \App\Models\Contacttag::all()->pluck('name', 'id');

        if (! $contact->isEditable() && ! $contact->canChangePermissions()) {
            abort(403);
        }

        return view('admin.contacts.edit', compact('clients', 'tags','contact', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
             'full_name'     => 'required',
             'email_1'  => 'email',
             //'email_2'  => 'email',
             //'website'  => 'active_url',
             'phone'    => 'min:4|max:255',
             'landline' => 'min:4|max:255',
        ]);

        $contact = $this->contact->find($id);

        Audit::log(Auth::user()->id, trans('admin/contacts/general.audit-log.category'), trans('admin/contacts/general.audit-log.msg-update', ['name' => $contact->full_name]));

        $attributes = $request->all();
        $attributes['org_id'] = Auth::user()->org_id;

        $temp_client = Client::where('name', $request['client_id'])->first();
        if (! $temp_client) {
            $attributes['client_id'] = 1;
        //Flash::warning('Please select the valid Client.');
            //return Redirect::back()->withInput(Request::all());
        } else {
            $attributes['client_id'] = $temp_client->id;
        }

        if (! isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        if ($request->file('file_name')) {
            $old_file = public_path().'/contacts/'.$contact->file;
            File::delete($old_file);

            $stamp = time();
            $file = $request->file('file_name');
            //dd($file);
            $destinationPath = public_path().'/contacts/';
            $filename = $file->getClientOriginalName();
            $request->file('file_name')->move($destinationPath, $stamp.'_'.$filename);
            $attributes['file'] = $stamp.'_'.$filename;
        }
        //dd($attributes);

        if ($contact->isEditable()) {
            $contact->update($attributes);
        }

        Flash::success(trans('admin/contacts/general.status.updated')); // 'contact successfully updated');

        return redirect('/admin/contacts');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $contacts = $this->contact->find($id);

        if (! $contacts->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/contacts/general.audit-log.category'), trans('admin/contacts/general.audit-log.msg-destroy', ['name' => $contacts->full_name]));

        Cases::where('contact_id', $id)->delete();
        $this->contact->delete($id);

        Flash::success(trans('admin/contacts/general.status.deleted')); // 'contact successfully deleted');

        return redirect('/admin/contacts');
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

        $contacts = $this->contact->find($id);

        if (! $contacts->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/contacts/dialog.delete-confirm.title');

        $contacts = $this->contact->find($id);
        $modal_route = route('admin.contacts.delete',$contacts->id);

        $modal_body = trans('admin/contacts/dialog.delete-confirm.body', ['id' => $contacts->id, 'name' => $contacts->full_name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $contacts = $this->contact->find($id);

        Audit::log(Auth::user()->id, trans('admin/contacts/general.audit-log.category'), trans('admin/contacts/general.audit-log.msg-enable', ['name' => $contacts->full_name]));

        $contacts->enabled = true;
        $contacts->save();

        Flash::success(trans('admin/contacts/general.status.enabled'));

        return redirect('/admin/contacts');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $contacts = $this->contact->find($id);

        Audit::log(Auth::user()->id, trans('admin/contacts/general.audit-log.category'), trans('admin/contacts/general.audit-log.msg-disabled', ['name' => $contacts->full_name]));

        $contacts->enabled = false;
        $contacts->save();

        Flash::success(trans('admin/contacts/general.status.disabled'));

        return redirect('/admin/contacts');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkcontacts = $request->input('chkContact');

        Audit::log(Auth::user()->id, trans('admin/contacts/general.audit-log.category'), trans('admin/contacts/general.audit-log.msg-enabled-selected'), $chkcontacts);

        if (isset($chkcontacts)) {
            foreach ($chkcontacts as $contacts_id) {
                $contacts = $this->contact->find($contacts_id);
                $contacts->enabled = true;
                $contacts->save();
            }
            Flash::success(trans('admin/contacts/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/contacts/general.status.no-contact-selected'));
        }

        return redirect('/admin/contacts');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkcontacts = $request->input('chkContact');

        Audit::log(Auth::user()->id, trans('admin/contacts/general.audit-log.category'), trans('admin/contacts/general.audit-log.msg-disabled-selected'), $chkcontacts);

        if (isset($chkcontacts)) {
            foreach ($chkcontacts as $contacts_id) {
                $contacts = $this->contact->find($contacts_id);
                $contacts->enabled = false;
                $contacts->save();
            }
            Flash::success(trans('admin/contacts/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/contacts/general.status.no-contact-selected'));
        }

        return redirect('/admin/contacts');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $contacts = $this->contact->where('full_name', 'LIKE', '%'.$query.'%')->get();

        foreach ($contacts as $contacts) {
            $id = $contacts->id;
            $name = $contacts->full_name;
            $email = $contacts->email;

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
        $contacts = $this->contact->find($id);

        return $contacts;
    }

    public function get_contact()
    {
        $term = strtolower(\Request::get('term'));
        $contacts = ContactModel::select('id', 'full_name')->where('full_name', 'LIKE', '%'.$term.'%')->where('enabled', '1')->groupBy('full_name')->take(5)->get();
        $return_array = [];

        foreach ($contacts as $v) {
            if (strpos(strtolower($v->full_name), $term) !== false) {
                $return_array[] = [
                    'value' => $v->full_name,
                    'id' =>$v->id,
                    'label'=>$v->full_name.'('.$v->id.')',
            ];
            }
        }

        return Response::json($return_array);
    }

    public function createModal()
    {
        $page_title = trans('admin/contacts/general.page.create.title'); // "Admin | contact | Create";
        $page_description = trans('admin/contacts/general.page.create.description'); // "Creating a new contact";
        if (\Request::get('clientid')) {
            $clientinfo = Client::find(\Request::get('clientid'));
        }
        $clients = Client::where('enabled', '1')->pluck('name', 'id')->all();
        $contact = new \App\Models\Contact();

        $perms = $this->permission->all();
        $createfrommodals = true;

        return view('admin.contacts.modals.create', compact('clients', 'contact', 'perms', 'page_title', 'page_description', 'createfrommodals', 'clientinfo'));
    }
}
