<?php

namespace App\Http\Controllers;

use App\Models\File as LeadFile;
use App\Models\Role as Permission;
use App\Models\Audit as Audit;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File as Files;

class LeadFilesController extends Controller
{
    /**
     * @var LeadFile
     */
    private $LeadFile;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param LeadFile $LeadFile
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(LeadFile $LeadFile, Permission $permission)
    {
        parent::__construct();
        $this->LeadFile = $LeadFile;
        $this->permission = $permission;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, ['file'          => 'required',
                                            'lead_id'          	 => 'required',
                                            'user_id'          	 => 'required',
        ]);

        $attributes = $request->except('_token'); //$request->all();
        $file = $attributes['file'];
        //dd($attributes);
        $file_name = $attributes['file_name'].'_'.date('Y_m_d-h_i_s').'.'.$file->getClientOriginalExtension();
        Audit::log(Auth::user()->id, trans('admin/leadfiles/general.audit-log.category'), trans('admin/leadfiles/general.audit-log.msg-store', ['name' => $file_name]));

        $attributes['file'] = $file_name;
        $LeadFile = $this->LeadFile->create($attributes);

        //fileupload
        $destinationPath = public_path().'/files/';
        $filename = $file_name;
        $file->move($destinationPath, $filename);

        $files = \App\Models\File::where('lead_id', $attributes['lead_id'])->orderBy('id', 'desc')->get();
        $data = '';
        if ($files) {
            foreach ($files as $key => $val) {
                $data .= '<div class="note-wrap" style="margin-top:10px; padding:0 15px; position: relative;">
                    	<p style="margin-bottom:0; font-weight:bold;"><a href="/files/'.$val->file.'">'.$val->file.'</a></p>
                        <i class="date">'.\Carbon\Carbon::createFromTimeStamp(strtotime($val->created_at))->diffForHumans().' by '.$val->user->first_name.'</i>';

                if (Auth::user()->id == $val->user_id) {
                    $data .= '<a title="Delete" data-target="#modal_dialog" data-toggle="modal" href="/admin/leadfiles/'.$val->id.'/confirm-delete" style="position:absolute; top:0; right:0;"><i class="fas fa-trash-alt deletable"></i></a>';
                }
                $data .= '</div>';
            }
        }

        return ['messages' => $data];
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $LeadFile = $this->LeadFile->find($id);

        if (! $LeadFile->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/leadfiles/general.audit-log.category'), trans('admin/leadfiles/general.audit-log.msg-destroy', ['name' => $LeadFile->name]));

        // Delete the File from its location
        $fileUrl = public_path().'/files/'.$LeadFile->file;
        Files::delete($fileUrl);

        $this->LeadFile->delete($id);

        Flash::success(trans('admin/leadfiles/general.status.deleted')); // 'LeadFile successfully deleted');

        return redirect('/admin/leads/'.$LeadFile->lead_id);
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

        $LeadFile = $this->LeadFile->find($id);

        if (! $LeadFile->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/leadfiles/dialog.delete-confirm.title');

        $LeadFile = $this->LeadFile->find($id);
        $modal_route = route('admin.leadfiles.delete', ['id' => $LeadFile->id]);

        $modal_body = trans('admin/leadfiles/dialog.delete-confirm.body', ['id' => $LeadFile->id, 'name' => $LeadFile->file]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
