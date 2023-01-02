<?php

namespace App\Http\Controllers;

use App\Helpers\UserSystemInfoHelper;
use App\Models\ShiftAttendance;
use AttendanceHelper;
use Flash;
use Illuminate\Http\Request;


class ShiftAttendanceController extends Controller
{



    public function clockin(Request $request)
    {
        


        $makeClock = AttendanceHelper::clockin();


        


        // dd($request->all());
        if($makeClock['success']){

            Flash::success($makeClock['message']);

        }else{

            Flash::error($makeClock['message']);

        }
        return redirect()->back();
    }

    public function clockout(Request $request)
    {
      
        $makeClock = AttendanceHelper::clockout();
        
        if($makeClock['success']){

            Flash::success($makeClock['message']);

        }else{

            Flash::error($makeClock['message']);

        }

        return redirect()->back();
    }

    public function filter_report()
    {
        $page_title = 'Shift | attendance';
        $shifts = \App\Models\Shift::pluck('shift_name as name', 'id');

        return view('admin.shift_attendance.filterreport', compact('shifts', 'page_title'));
    }

    public function filter_reportShow(Request $request)
    {
        $shifts = \App\Models\Shift::pluck('shift_name as name', 'id');

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $ShiftAttendance = ShiftAttendance::where(function ($query) use ($request) {
            if ($request->shift_id && $request->shift_id != '') {
                return $query->where('shift_id', $request->shift_id);
            }
        })
        ->where('date', '>=', $request->start_date)
        ->where('date', '<=', $request->end_date)
        ->orderBy('date', 'desc')
        ->get()
        ->groupBy('shift_id');
        $page_title = 'Shift | attendance';

        $allReport = AttendanceHelper::reportSummary($ShiftAttendance); //pram1=>attendance list with shiftgroup , parm2 => boolean value to filter user (default false), parm3 userlist array to filter (optional)
        $selectedShift = $request->shift_id;
        $allReport = $allReport['result'];
     

        return view('admin.shift_attendance.filterreport', compact('shifts', 'allReport', 'start_date', 'end_date', 'page_title','selectedShift'));
    }

    public function filter_reportByUser($userid, $shift_id, $date)
    {
        $attendance = ShiftAttendance::where('user_id', $userid)
                        ->where('shift_id', $shift_id)
                        ->where('date', $date)->get();
        $report = [];
        $breaks = AttendanceHelper::breakDuration($shift_id);
        $report['user'] = \App\User::find($userid);
        $report['shift'] = \App\Models\Shift::find($shift_id);
        $report['breakduration'] = $breaks;

        $officeTime = AttendanceHelper::calculateOfficeTime($report);

        $report['officeTime'] = $officeTime['officeTime'] / 60;

        $report['requiredworkTime'] = $officeTime['requiredworkTime'];

        $report = AttendanceHelper::singleuserAttendanceReport($attendance, $report);

        $editable = (\Request::get('user') && \Request::get('user') == 'self') ? false : true;

        return view('admin.shift_attendance.detailtimereport', compact('report', 'date', 'editable'));
    }

    public function fixattendance(Request $request, $attendanceId)
    {
        if ($attendanceId != 'new') {
            $attendance = ShiftAttendance::find($attendanceId);
            $attendance->update(['time'=>$request->time]);
        } else {
            $attribute = [
                'user_id'=>$request->user_id,
                'date'=>$request->date,
                'shift_id'=>$request->shift_id,
                'time'=>$request->time,
                'is_adjusted'=>1
            ];
            $lastAttendence = ShiftAttendance::where('shift_id', $request->shift_id)
                            ->where('user_id', $request->user_id)
                            ->where('date', $request->date)
                            ->orderBy('attendance_status', 'desc')
                            ->first();

            if ($lastAttendence) {
                $nextStatus = $lastAttendence->attendance_status + 1;

                $attribute['attendance_status'] = $nextStatus;

                ShiftAttendance::create($attribute);
            } else {
                abort(404);
            }
        }

        return ['success'=>true];
    }

    private function getReportSummaryArray($ShiftAttendance)
    {
        $allReport = AttendanceHelper::reportSummary($ShiftAttendance)['result'];
        $excelReport = [];
        foreach ($allReport as $key => $summaryreport) {
            $thisReport = $summaryreport['data_by_date'];

            foreach ($thisReport as $key => $report) {
                $thisDate = $report['date'];
                $userReports = $report['data'];
                foreach ($userReports as $ur => $thisUserReport) {
                    $result = [];
                    $thisUserReport = (object) $thisUserReport;
                    // if( $thisUserReport->clockin){
                    //  dd($thisUserReport);
                    // }

                    $result['emp_id'] = $thisUserReport->user->id;
                    $result['date'] = $thisDate;
                    $result['first_name'] = $thisUserReport->user->first_name;

                    $result['last_name'] = $thisUserReport->user->last_name;

                    $result['degination'] = $thisUserReport->user->designation->designations;

                    $result['shift_name'] = $thisUserReport->shift->shift_name;

                    $result['officeTime'] = AttendanceHelper::minutesToHours($thisUserReport->officeTime).' hrs/'.AttendanceHelper::minutesToHours($thisUserReport->requiredworkTime).' hrs';

                    $result['clockin'] = $thisUserReport->clockin;

                    $locationIn = json_decode($thisUserReport->in_location);

                    $result['in_location'] = $locationIn ? $locationIn->street_name.'/'.$locationIn->formatted_address : '-';

                    $locationOut = json_decode($thisUserReport->out_location);

                    $result['lateby'] = $thisUserReport->lateby ? AttendanceHelper::minutesToHours($thisUserReport->lateby).' hrs' : '-';

                    $result['earlyby'] = $thisUserReport->earlyby ? AttendanceHelper::minutesToHours($thisUserReport->earlyby).' hrs' : '-';

                    $result['break_taken'] = $thisUserReport->summary['breakTime'] ? AttendanceHelper::minutesToHours($thisUserReport->summary['breakTime']).' hrs' : '-';

                    $result['work_done'] = $thisUserReport->summary['workTime'] ? AttendanceHelper::minutesToHours($thisUserReport->summary['workTime']).' hrs' : '-';

                    $result['overTime'] = $thisUserReport->overTime ? AttendanceHelper::minutesToHours($thisUserReport->overTime).' hrs' : '-';

                    $result['clockout'] = $thisUserReport->clockout;
                    $result['out_location'] = $locationOut ? $locationOut->street_name.'/'.$locationOut->formatted_address : '-';
                    $result['remark'] = $thisUserReport->summary['message'];
                    $excelReport[] = $result;
                }
            }
        }

        return ['summary'=>$excelReport];
    }

    public function download_report(Request $request, $type)
    {
        $ShiftAttendance = ShiftAttendance::where(function ($query) use ($request) {
            if ($request->shift_id && $request->shift_id != '') {
                return $query->where('shift_id', $request->shift_id);
            }
        })
        ->where('date', '>=', $request->start_date)
        ->where('date', '<=', $request->end_date)
        ->orderBy('date', 'desc')
        ->get()
        ->groupBy('shift_id');

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $report = $this->getReportSummaryArray($ShiftAttendance);

        $summaryReport = $report['summary'];

        // dd($summaryReport);
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('admin.shift_attendance.pdf.filterreportPDF', compact('start_date', 'end_date', 'summaryReport'));
            // return view('admin.shift_attendance.pdf.filterreportPDF',compact('start_date','end_date','summaryReport'));
            $file = "attendance_report_{$start_date}_{$end_date}";
            if (\File::exists('attendance/'.$file)) {
                \File::Delete('attendance/'.$file);
            }

            return $pdf->download($file);
        }
        //else download excel

        return \Excel::download(new \App\Exports\ExcelExport($summaryReport), "attendance_report_{$start_date}_{$end_date}.{$type}");
    }

    public function myTimeHistory()
    {
        $page_title = 'My Time History';
        $page_description = 'Show Your Time History ';
      
        return view('admin.shift_attendance.mytimehistroy', compact('page_title', 'page_description'));
    }

    public function myTimeHistoryUpdate(Request $request)
    {
        $page_title = 'Time Report';

        $page_description = 'Time History Report';
        $date_in = $request->date_in;
        $start_date = $date_in.'-01';
        $end_date = date('Y-m-t', strtotime($start_date));

        $attendance = ShiftAttendance::where('user_id', \Auth::user()->id)
                        ->where('date', '>=', $start_date)
                        ->where('date', '<=', $end_date)
                        ->get()
                        ->groupBy('shift_id');

        $allReport = AttendanceHelper::singleuserAttendanceReportWithShift($attendance);
        // dd($allReport);
        if (count($allReport) == 0) {
            Flash::warning('You have no any attendance records on this day');
        }

        return view('admin.shift_attendance.mytimehistroy', compact('page_title', 'page_description', 'allReport', 'date_in', 'start_date', 'end_date'));
    }

    public function myTimeHistoryStore(Request $request)
    {
        $checkAttendance = ShiftAttendance::find($request->attendance_id);

        if ($checkAttendance->user_id != \Auth::user()->id) {
            Flash::error('You cannot change other attendance');

            return redirect()->back();
        }

        $requestedAttendace = [

            'attendance_id'=>$checkAttendance->id,
            'user_id'=>\Auth::user()->id,
            'actual_time'=>$checkAttendance->time,
            'requested_time'=>$request->time,
            'reason'=>$request->reason,
            'status'=>1,

        ];

        \App\Models\AttendanceChangeRequest::create($requestedAttendace);

        Flash::success('You request is been applied');

        return redirect()->back();
    }

    public function timeChangeRequest()
    {
        $page_title = 'Timechange Request';
        $page_description = 'Timechange Request';

        $timechange = \App\Models\AttendanceChangeRequest::orderBy('created_at', 'desc')->get();

        return view('admin.shift_attendance.timeChangeRequest', compact('page_title', 'page_description', 'timechange'));
    }

    public function timeRequestModal($id)
    {
        $timechange = \App\Models\AttendanceChangeRequest::find($id);
        $attendance = ShiftAttendance::find($timechange->attendance_id);

        return view('admin.shift_attendance.timechange_approve_modal', compact('page_title', 'page_description', 'timechange', 'attendance'));
    }

    public function updateTimeChangeRequest(Request $request, $id)
    {
        $timechange = \App\Models\AttendanceChangeRequest::find($id);

        if ($request->status == 2) {
            $attendance = ShiftAttendance::find($timechange->attendance_id);
            $attendance->update([
                'time'=>$timechange->requested_time,
            ]);
        }

        $timechange->update([
            'status'=>$request->status,
            'approved_by'=>\Auth::user()->id,
        ]);

        Flash::success('TimeChangeRequest Successfully Accepted');

        return redirect()->back();
    }
}
