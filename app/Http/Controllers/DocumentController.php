<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Audit as Audit;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Documents as DocumentsModel;
use App\Models\Folder;
use App\Models\Role as Permission;
use App\Repositories\DocumentsRepository as Documents;
use Auth;
use DB;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class DocumentController extends Controller
{
    /**
     * @var Client
     */
    private $documents;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $document
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(DocumentsModel $documents, Permission $permission)
    {
        parent::__construct();
        $this->documents = $documents;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/documents/general.audit-log.category'), trans('admin/documents/general.audit-log.msg-index'));
        $documents = DocumentsModel::select('documents.*')->where(function ($query) {
            if (\Request::get('folder')) {
                $sharedWithme =  \App\Models\Folder::find(\Request::get('folder'));
                $sharedUser = json_decode($sharedWithme->shared_user);
                if(is_array($shared_user)){
                  return $query->where('folder_id', \Request::get('folder'))->whereIn('documents.user_id',$sharedUser)->orWhere('documents.user_id',\Auth::user()->id);  
                }else{
                     return $query->where('folder_id', \Request::get('folder'));
                }
                
            }
        })->where(function ($query) {
            if (\Request::get('category')) {
                return $query->where('doc_cats', \Request::get('category'));
            }
        })->where(function ($query) {
            if (\Request::get('term')) {
                return $query->where('doc_name', 'LIKE', '%' . \Request::get('term') . '%');
            }
        })
            ->where(function ($query) {
                if (\Request::get('type') && \Request::get('type') == 'shared') {
                    return $query->where('shared_docs.user_id', \Auth::user()->id);
                } elseif(!\Request::get('folder')) {

                    return $query->where('documents.user_id', \Auth::user()->id);
                }
            })
            ->leftjoin('shared_docs', 'shared_docs.doc_id', '=', 'documents.id')
            ->groupBy('documents.id')
            ->orderBy('id', 'DESC')
            ->get();
        $page_title = trans('admin/documents/general.page.index.title');
        $category = $documents->unique('doc_cats');

        $page_description = trans('admin/documents/general.page.index.description');

       


        $auth_id = \Auth::user()->id;

        $allFolders = Folder::where('org_id', \Auth::user()->org_id)->get();

        if(!Auth::user()->hasRole('admins')){
        
        $folders = [];

        foreach ($allFolders as $key => $value) {
            
            $shared_user = json_decode($value->shared_user);

            if(in_array($auth_id,$shared_user) || $value->user_id == $auth_id  ){
               
                $folders [] = $value;
            }
        }

        }else{

            $folders = $allFolders;
        
        }

  


        return view('admin.documents.index', compact('documents', 'page_title', 'page_description', 'folders', 'category'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $document = $this->documents->find($id);

        if ($document->user_id != \Auth::user()->id) {
            Flash::warning('Sorry! You do not have enough right to view the file.');
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/documents/general.audit-log.category'), trans('admin/documents/general.audit-log.msg-show', ['name' => $document->name]));

        $page_title = trans('admin/documents/general.page.show.title'); // "Admin | Client | Show";
        $page_description = trans('admin/documents/general.page.show.description'); // "Displaying client: :name";

        return view('admin.documents.show', compact('document', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/documents/general.page.create.title'); // "Admin | Client | Create";
        $page_description = trans('admin/documents/general.page.create.description'); // "Creating a new client";

        $document = new \App\Models\Client();
        $perms = $this->permission->all();
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');
        $categories = \App\Models\DocCategory::orderBy('name', 'asc')->pluck('name', 'id');
        $folders = \App\Models\Folder::where('enabled', '1')->pluck('name', 'id');
        if (\Request::ajax()) {
            if (\Request::get('type') == 'docs') {
                $type = 'docs';

                return view('admin.documents.modals.create', compact('document', 'perms', 'page_title', 'page_description', 'users', 'folders', 'categories', 'type'));
            } elseif (\Request::get('type') == 'note') {
                $type = 'note';

                return view('admin.documents.modals.createnote', compact('document', 'perms', 'page_title', 'page_description', 'users', 'folders', 'categories', 'type'));
            } else {
                $type = 'noteDoc';

                return view('admin.documents.modals.createnote', compact('document', 'perms', 'page_title', 'page_description', 'users', 'folders', 'categories', 'type'));
            }
        }

        return view('admin.documents.create', compact('document', 'perms', 'page_title', 'page_description', 'users', 'folders', 'categories'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if ($request->doc_type == 'docs') {
            $this->validate($request, [
                'file_name'      => 'required',
                'doc_name'      => 'required',

            ]);
        }

        $attributes = $request->all();

        $attributes['user_id'] = \Auth::user()->id;
        $stamp = time();
        $file = $request->file('file_name');
        //dd($file);
        if ($file) {
            $destinationPath = public_path() . '/documents/';
            $filename = $file->getClientOriginalName();
            $request->file('file_name')->move($destinationPath, $stamp . '_' . $filename);
            $attributes['file'] = $stamp . '_' . $filename;
        }

        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        $document = $this->documents->create($attributes);

        Audit::log(Auth::user()->id, trans('admin/documents/general.audit-log.category'), trans('admin/documents/general.audit-log.msg-store', ['name' => 'Docs']));

        Flash::success(trans('admin/documents/general.status.created')); // 'Client successfully created');

        return redirect('/admin/documents');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $document = $this->documents->find($id);

        if ($document->user_id != \Auth::user()->id) {
            Flash::warning('Sorry! You do not have enough right to edit the file.');
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/documents/general.audit-log.category'), trans('admin/documents/general.audit-log.msg-edit', ['name' => $document->name]));

        $page_title = trans('admin/documents/general.page.edit.title'); // "Admin | Client | Edit";
        $page_description = trans('admin/documents/general.page.edit.description', ['name' => $document->name]); // "Editing client";
        $users = \App\User::where('enabled', '1')->pluck('first_name', 'id');
        $folders = \App\Models\Folder::where('enabled', '1')->pluck('name', 'id');
        if (!$document->isEditable() && !$document->canChangePermissions()) {
            abort(403);
        }
        if (\Request::ajax()) {
            if ($document->doc_type == 'docs') {
                $type = 'docs';

                return view('admin.documents.modals.edit', compact('document', 'perms', 'page_title', 'page_description', 'users', 'folders', 'type'));
            } elseif ($document->doc_type == 'note') {
                $type = 'note';

                return view('admin.documents.modals.editnote', compact('document', 'perms', 'page_title', 'page_description', 'users', 'folders', 'categories', 'type'));
            } else {
                $type = 'noteDoc';

                return view('admin.documents.modals.editnote', compact('document', 'perms', 'page_title', 'page_description', 'users', 'folders', 'categories', 'type'));
            }
        }

        return view('admin.documents.edit', compact('document', 'users', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'contact_id'      => 'required',
            'doc_name'      => 'required',

        ]);

        $attributes = $request->all();

        // $temp_contact = Contact::where('full_name', $request['contact_id'])->first();
        // if(!$temp_contact)
        // {
        //     Flash::warning('Please select the valid Contact.');
        //     return \Redirect::back()->withRequest(\Request::all());
        // }
        // else
        //     $attributes['contact_id'] = $temp_contact->id;

        if (!isset($attributes['enabled'])) {
            $attributes['enabled'] = 0;
        }

        $documents = $this->documents->find($id);

        if ($request->file('file_name')) {
            $old_file = public_path() . '/documents/' . $documents->file;
            File::delete($old_file);

            $stamp = time();
            $file = $request->file('file_name');
            //dd($file);
            $destinationPath = public_path() . '/documents/';
            $filename = $file->getClientOriginalName();
            $request->file('file_name')->move($destinationPath, $stamp . '_' . $filename);
            $attributes['file'] = $stamp . '_' . $filename;
        }

        if ($documents->isEditable()) {
            $documents->update($attributes);
        }

        Audit::log(Auth::user()->id, trans('admin/documents/general.audit-log.category'), trans('admin/documents/general.audit-log.msg-update', ['name' => $documents->name]));

        Flash::success(trans('admin/documents/general.status.updated')); // 'Client successfully updated');

        return redirect('/admin/documents');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $documents = $this->documents->find($id);

        if (!$documents->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/documents/general.audit-log.category'), trans('admin/documents/general.audit-log.msg-destroy', ['name' => $documents->name]));

        $old_file = public_path() . '/documents/' . $documents->file;
        File::delete($old_file);

        $documents->delete($id);

        Flash::success(trans('admin/documents/general.status.deleted')); // 'Client successfully deleted');

        return redirect('/admin/documents');
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

        $documents = $this->documents->find($id);

        if (!$documents->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/documents/dialog.delete-confirm.title');

        $documents = $this->documents->find($id);
        $modal_route = route('admin.documents.delete', ['documentId' => $documents->id]);

        $modal_body = trans('admin/documents/dialog.delete-confirm.body', ['id' => $documents->id, 'name' => $documents->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $documents = $this->documents->find($id);

        Audit::log(Auth::user()->id, trans('admin/documents/general.audit-log.category'), trans('admin/documents/general.audit-log.msg-enable', ['name' => $documents->name]));

        $documents->enabled = true;
        $documents->save();

        Flash::success(trans('admin/documents/general.status.enabled'));

        return redirect('/admin/documents');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $documents = $this->documents->find($id);

        Audit::log(Auth::user()->id, trans('admin/documents/general.audit-log.category'), trans('admin/documents/general.audit-log.msg-disabled', ['name' => $documents->name]));

        $documents->enabled = false;
        $documents->save();

        Flash::success(trans('admin/documents/general.status.disabled'));

        return redirect('/admin/documents');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkdocuments = $request->Request('chkClient');

        Audit::log(Auth::user()->id, trans('admin/documents/general.audit-log.category'), trans('admin/documents/general.audit-log.msg-enabled-selected'), $chkdocuments);

        if (isset($chkdocuments)) {
            foreach ($chkdocuments as $documents_id) {
                $documents = $this->documents->find($documents_id);
                $documents->enabled = true;
                $documents->save();
            }
            Flash::success(trans('admin/documents/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/documents/general.status.no-client-selected'));
        }

        return redirect('/admin/documents');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkdocuments = $request->Request('chkClient');

        Audit::log(Auth::user()->id, trans('admin/documents/general.audit-log.category'), trans('admin/documents/general.audit-log.msg-disabled-selected'), $chkdocuments);

        if (isset($chkdocuments)) {
            foreach ($chkdocuments as $documents_id) {
                $documents = $this->documents->find($documents_id);
                $documents->enabled = false;
                $documents->save();
            }
            Flash::success(trans('admin/documents/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/documents/general.status.no-client-selected'));
        }

        return redirect('/admin/documents');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->Request('query');

        $documents = $this->documents->pushCriteria(new documentsWhereDisplayNameLike($query))->all();

        foreach ($documents as $documents) {
            $id = $documents->id;
            $name = $documents->name;
            $email = $documents->email;

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
        $id = $request->id;

        $documents = $this->documents->find($id);

        return $documents;
    }

    public function get_client()
    {
        $term = strtolower(\Request::get('term'));
        $contacts = ClientModel::select('id', 'name')->where('name', 'LIKE', '%' . $term . '%')->groupBy('name')->take(5)->get();
        $return_array = [];

        foreach ($contacts as $v) {
            if (strpos(strtolower($v->name), $term) !== false) {
                $return_array[] = ['value' => $v->name, 'id' => $v->id];
            }
        }

        return \Response::json($return_array);
    }

    public function userlist($docid)
    {
        $users = \App\User::where('enabled', '1')->orderBy('username', 'asc')->get();

        return view('admin.documents.modals.usershare', compact('users', 'docid'));
    }

    public function docsharepost(Request $request, $docid)
    {
        $doc = DocumentsModel::find($docid);
        if ($doc->user_id != \Auth::user()->id) {
            Flash::error('You cannot share this doc');
        }
        $attributes = [];
        foreach ($request->user_id as $key => $users) {
            $attributes[] = ['user_id' => $users, 'doc_id' => $docid];
        }
        if (count($attributes) > 0) {
            \DB::table('shared_docs')->insert($attributes);
        }

        return redirect()->back();
    }
}
