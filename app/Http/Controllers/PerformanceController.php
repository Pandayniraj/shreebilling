<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\PerformanceApprisal;
use App\Models\PerformanceIndicator;
use Flash;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Admin | Performance | Indicator';
        $page_description = 'List of  Performance Indicator';
        $performance = PerformanceIndicator::select('tbl_designations.designations', 'performance_indicator.performance_indicator_id', 'tbl_departments.deptname')->leftjoin('tbl_designations', 'tbl_designations.designations_id', '=', 'performance_indicator.designations_id')->leftjoin('tbl_departments', 'tbl_departments.departments_id', '=', 'tbl_designations.departments_id')->orderBy('performance_indicator_id', 'desc')->get();

        return view('admin.performance.indicator', compact('performance', 'page_description', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createIndicator()
    {
        $page_title = 'Admin | create';
        $technical_competency = ['None', 'Beginner', 'Intermediate', 'Advanced', 'Expert leader'];
        $behavioural_competency = ['None', 'Behavioural', 'Intermediate', 'Advanced'];
        $department = Department::all();

        return view('admin.performance.create_indicator', compact('technical_competency', 'behavioural_competency', 'department', 'page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $indicator = $request->all();
        PerformanceIndicator::create($indicator);
        Flash::success('Indicator Created');

        return redirect('/admin/performance/indicator/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showIndicator($id)
    {
        $page_title = 'Admin | showindicator';
        $performance = PerformanceIndicator::leftjoin('tbl_designations', 'tbl_designations.designations_id', '=', 'performance_indicator.designations_id')->leftjoin('tbl_departments', 'tbl_departments.departments_id', '=', 'tbl_designations.departments_id')->where('performance_indicator_id', $id)->first();

        return view('admin.performance.show-indicator', compact('performance', 'page_title'));
    }

    public function editIndicator($id)
    {
        $page_title = 'Admin | editIndicator';
        $edit = PerformanceIndicator::where('performance_indicator_id', $id)->first();
        $technical_competency = ['None', 'Beginner', 'Intermediate', 'Advanced', 'Expert leader'];
        $behavioural_competency = ['None', 'Behavioural', 'Intermediate', 'Advanced'];
        $department = Department::all();

        return view('admin.performance.edit-indicator', compact('edit', 'department', 'technical_competency', 'behavioural_competency', 'page_title'));
    }

    public function updateIndicator($id, Request $request)
    {
        $indicator = $request->all();
        unset($indicator['_token']);
        PerformanceIndicator::where('performance_indicator_id', $id)->update($indicator);
        Flash::success('Indicator Updated');

        return redirect('/admin/performance/indicator/');
    }

    public function getIndicatorDelete($id)
    {
        $error = null;
        $indicator = PerformanceIndicator::where('performance_indicator_id', $id)->first();
        $modal_title = 'Delete Indicator';
        $modal_body = 'Are you sure that you want to delete indicator '.$indicator->performance_indicator_id.'? This operation is irreversible';
        $modal_route = route('admin.performance.delete-indicator', $indicator->performance_indicator_id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function deleteIndicator($id)
    {
        $d = PerformanceIndicator::where('performance_indicator_id', $id)->delete();
        Flash::success('Indicator SucessFully Deleted !! ');

        return redirect('/admin/performance/indicator/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function appraisalIndex()
    {
        $page_title = 'Admin | Appraisal';
        $page_description = 'Lists Of Appraisals';
        $appraisal = PerformanceApprisal::leftjoin('users', 'performance_appraisal.user_id', '=', 'users.id')->orderBy('performance_appraisal_id', 'desc')->get();

        return view('admin.performance.appraisal', compact('page_title', 'appraisal', 'page_description'));
    }

    public function userAppraisal()
    {
        $page_title = 'Admin | User | Appraisal';
        $page_description = 'User Appraisal';
        $department = Department::all();

        return view('admin.performance.create_appeaisal', compact('department', 'page_title', 'page_description'));
    }

    public function userAppraisalCreate(Request $request)
    {
        if (isset($request->showappeaisal)) {
            $userinfo = unserialize($request->user_info);
            $check = PerformanceIndicator::where('designations_id', $userinfo[1])->exists();
            if ($check) {
                $page_title = 'Admin | Appraisal';
                $selecteduser = $userinfo[0];
                $selecteddate = $request->appraisal_month;
                $department = Department::all();
                $showappeaisal = true;
                $technical_competency = ['None', 'Beginner', 'Intermediate', 'Advanced', 'Expert leader'];
                $behavioural_competency = ['None', 'Behavioural', 'Intermediate', 'Advanced'];
                $userappeaisal = PerformanceApprisal::where('user_id', $userinfo[0])->where('appraisal_month', $request->appraisal_month)->first();

                return view('admin.performance.create_appeaisal', compact('showappeaisal', 'department', 'technical_competency', 'behavioural_competency', 'selecteduser', 'userappeaisal', 'page_title', 'selecteddate'));
            } else {
                Flash::warning('No Indicator set for this designations');

                return redirect('/admin/performance/giveappraisal');
            }
        }
        if (isset($request->createappeasial)) {
            $userinfo = unserialize($request->user_info);
            $appraisal = $request->all();
            $appraisal['user_id'] = $userinfo[0];
            PerformanceApprisal::create($appraisal);
            Flash::success('Appeaisal SucessFully Created !! ');

            return redirect('/admin/performance/appraisal/');
        }
        if (isset($request->updateappeasial)) {
            $aid = $request->aid;
            $newappeasial = $request->all();
            unset($newappeasial['_token']);
            unset($newappeasial['user_info']);
            unset($newappeasial['updateappeasial']);
            unset($newappeasial['aid']);
            PerformanceApprisal::where('performance_appraisal_id', $aid)->update($newappeasial);
            Flash::success('Appeaisal SucessFully Updated !! ');

            return redirect('/admin/performance/appraisal/');
        }
    }

    public function showAppraisal($id)
    {
        $page_title = 'Admin | show';
        $appraisal = PerformanceApprisal::leftjoin('users', 'performance_appraisal.user_id', '=', 'users.id')->where('performance_appraisal.performance_appraisal_id', $id)->first();

        return view('admin.performance.show-appeaisal', compact('appraisal', 'page_title'));
    }

    public function editAppraisal($id)
    {
        $page_title = 'Admin | User | Appraisal | Edit';
        $page_description = 'User Appraisal';
        $technical_competency = ['None', 'Beginner', 'Intermediate', 'Advanced', 'Expert leader'];
        $behavioural_competency = ['None', 'Behavioural', 'Intermediate', 'Advanced'];
        $userappeaisal = PerformanceApprisal::leftjoin('users', 'performance_appraisal.user_id', '=', 'users.id')->where('performance_appraisal_id', $id)->first();

        return view('admin.performance.edit-appeaisal', compact('userappeaisal', 'technical_competency', 'behavioural_competency', 'page_title', 'page_description'));
    }

    public function updateAppeasial($id, Request $request)
    {
        $newappeasial = $request->all();
        unset($newappeasial['_token']);
        PerformanceApprisal::where('performance_appraisal_id', $id)->update($newappeasial);
        Flash::success('Appeaisal SucessFully Updated !! ');

        return redirect('/admin/performance/appraisal/');
    }

    public function getappeaisalDelete($id)
    {
        $error = null;
        $appeaisal = PerformanceApprisal::where('performance_appraisal_id', $id)->first();
        $modal_title = 'Delete appeaisal';
        $modal_body = 'Are you sure that you want to delete indicator '.$appeaisal->performance_appraisal_id.'? This operation is irreversible';
        $modal_route = route('admin.performance.delete-appeaisal', $appeaisal->performance_appraisal_id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function deleteAppeaisal($id)
    {
        $d = PerformanceApprisal::where('performance_appraisal_id', $id)->delete();
        Flash::success('Appeaisal SucessFully Deleted !! ');

        return redirect('/admin/performance/appraisal/');
    }

    public function ReportIndex()
    {
        $page_title = 'Admin | Report';

        return view('admin.performance.report', compact('page_title'));
    }

    public function Report($id)
    {
        $technical_competency = ['None', 'Beginner', 'Intermediate', 'Advanced', 'Expert leader'];
        $behavioural_competency = ['None', 'Behavioural', 'Intermediate', 'Advanced'];
        $userappeaisal = PerformanceApprisal::leftjoin('users', 'performance_appraisal.user_id', '=', 'users.id')->where('performance_appraisal_id', $id)->first();
        $html = view('admin.performance.appeaisalmodel', compact('userappeaisal', 'technical_competency', 'behavioural_competency'));

        return $html;
    }
}
