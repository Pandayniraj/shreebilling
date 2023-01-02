<?php

namespace App\Http\Controllers;

use App\Models\Role as Permission;
use App\Models\Training;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class TrainingController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    public function index()
    {
        $page_title = 'Training';
        $page_description = 'All Trainings';

        $trainings = Training::where('org_id', Auth::user()->org_id)->orderBy('created_at', 'desc')->get();
        $users = User::where('enabled', '1')->orderBy('first_name', 'asc')->get();

        return view('admin.training.index', compact('page_title', 'page_description', 'trainings', 'users'));
    }

    public function save(Request $request)
    {
        /* if(!$training->isEditable())
        {
            abort(403);
        } */

        $attributes = $request->except(['_token', 'remove_attachments']);
        //dd($attributes);
        $attributes['upload_file'] = '';
        $attributes['org_id'] = \Auth::user()->org_id;
        $files = $request->file('upload_file');
        if ($files) {
            $stamp = time();
            $destinationPath = public_path().'/training/';
            foreach ($files as $file) {
                if ($file) {
                    $filename = $file->getClientOriginalName();
                    $file->move($destinationPath, $stamp.'_'.$filename);
                    if ($attributes['upload_file'] == '') {
                        $attributes['upload_file'] = $stamp.'_'.$filename;
                    } else {
                        $attributes['upload_file'] = $attributes['upload_file'].','.$stamp.'_'.$filename;
                    }
                }
            }
        }

        if ($request->training_id == '') {
            $attributes['assigned_by'] = Auth::user()->id;
            Training::create($attributes);
            Flash::success('Training created Successfully');
        } else {
            $training = Training::select('upload_file')->where('training_id', $request->training_id)->first();
            $old_files = explode(',', $training->upload_file);

            if ($request->remove_attachments) {
                foreach (explode(',', $request->remove_attachments) as $lfv) {
                    if (count($old_files)) {
                        $index = array_search($lfv, $old_files);
                        if ($index !== false) {
                            unset($old_files[$index]);
                        }
                    }

                    if (File::exists('training/'.$lfv)) {
                        File::Delete('training/'.$lfv);
                    }
                }
            }

            if (count($old_files)) {
                $oldFiles = implode(',', $old_files);
                if ($attributes['upload_file'] != '') {
                    $attributes['upload_file'] = $attributes['upload_file'].','.$oldFiles;
                } else {
                    $attributes['upload_file'] = $oldFiles;
                }
            }
            Training::where('training_id', $request->training_id)->update($attributes);
            Flash::success('Training updated Successfully');
        }

        return Redirect::back();
    }

    public function edit($training_id)
    {
        $training = Training::where('training_id', $training_id)->first();
        $users = User::where('enabled', '1')->orderBy('first_name', 'asc')->get();

        $returnHTML = view('admin.training.modal', ['training'=> $training, 'users'=> $users])->render();

        return $returnHTML;
        //return response()->json(['success' => true, 'html' => $returnHTML]);
    }

    public function show($training_id)
    {
        $training = Training::where('training_id', $training_id)->first();

        if ($training->status == 0) {
            $status = 'Pending';
        } elseif ($training->status == 1) {
            $status = 'Started';
        } elseif ($training->status == 2) {
            $status = 'Completed';
        } else {
            $status = 'Terminated';
        }

        if ($training->performance == 0) {
            $performance = 'Not Concluded';
        } elseif ($training->performance == 1) {
            $performance = 'Satisfactory';
        } elseif ($training->performance == 2) {
            $performance = 'Average';
        } elseif ($training->performance == 3) {
            $performance = 'Poor';
        } else {
            $performance = 'Excellent';
        }

        $data = '<div class="panel panel-custom">
                <div class="panel-heading">
                    <h4 class="modal-title" id="myModalLabel">Trainings Details
                        <div class="pull-right">
                            <a href="/admin/trainings/generatePdf/'.$training->training_id.'" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="PDF">
                                <span <i="" class="fa fa-file-pdf"></span>
                            </a>
                        </div>
                    </h4>
                </div>
                <div class="modal-body wrap-modal wrap">
                    <div class="panel-body form-horizontal">
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label"><strong>Employee :</strong></label>
                            </div>
                            <div class="col-sm-8">
                                <p class="form-control-static">'.\TaskHelper::getUserName($training->user_id).'</p>
                            </div>
                        </div>
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Course / Training :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">'.$training->training_name.'</p>
                            </div>
                        </div>
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Vendor :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">'.$training->vendor_name.'</p>
                            </div>
                        </div>

                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Start Date :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">'.$training->start_date.'</p>
                            </div>
                        </div>

                         <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Finish Date :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">'.$training->finish_date.'</p>
                            </div>
                        </div>

                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Training Cost :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">'.$training->training_cost.'</p>
                            </div>
                        </div>

                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Status :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">'.$status.'</p>
                            </div>
                        </div>

                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Performance :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">'.$performance.'</p>
                            </div>
                        </div>';

        if ($training->upload_file != '') {
            $data .= '<div class="col-md-12 notice-details-margin">
                                    <div class="col-sm-4 text-right">
                                        <label class="control-label"><strong>Attachment :</strong></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <ul class="mailbox-attachments clearfix mt">';
            foreach (explode(',', $training->upload_file) as $lfv) {
                $data .= '<li>
                                                        <a class="btn btn-xs btn-dark" href="/training/'.$lfv.'" data-original-title="Download" target="_blank">
                                                        <p class="form-control-static">'.$lfv.'</p>
                                                        </a>
                                                    </li>';
            }
            $data .= '                   
                                        </ul>
                                    </div>
                                </div>';
        }

        $data .= '
                        <div class="col-md-12 notice-details-margin">
                            <div class="col-sm-4 text-right">
                                <label class="control-label">Remarks :</label>
                            </div>

                            <div class="col-sm-5">
                                <p class="form-control-static">'.$training->remarks.'</p>
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

    public function delete($training_id)
    {
        $training = Training::select('upload_file')->where('training_id', $training_id)->first();
        if (! $training) {
            Flash::warning('Sorry! Problem in finding the Trainign data.');

            return Redirect::back();
        }

        if (! $training->isDeletable()) {
            Flash::warning('Sorry! You do not have enough privilege to delete this.');

            return Redirect::back();
        }

        foreach (explode(',', $training->upload_file) as $lfv) {
            if (File::exists('training/'.$lfv)) {
                File::Delete('training/'.$lfv);
            }
        }

        Training::where('training_id', $training_id)->delete();
        Flash::success('Training deleted Successfully');

        return Redirect::back();
    }

    public function printNow()
    {
        $trainings = Training::orderBy('created_at', 'desc')->get();

        return view('admin.training.print', compact('trainings'));
    }

    public function generatePdf()
    {
        $trainings = Training::orderBy('created_at', 'desc')->get();

        $pdf = \PDF::loadView('admin.training.generatePdf', compact('trainings'));
        $file = 'All_Trainings.pdf';

        return $pdf->download($file);
    }

    public function generateSinglePdf($training_id)
    {
        $training = Training::where('training_id', $training_id)->first();

        $pdf = \PDF::loadView('admin.training.generateSinglePdf', compact('training'));
        $file = 'Training Detail - '.\TaskHelper::getUserName($training->user_id).'.pdf';

        return $pdf->download($file);
    }
}
