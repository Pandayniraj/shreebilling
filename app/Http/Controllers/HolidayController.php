<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class HolidayController extends Controller
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $client
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Holiday $holiday, Permission $permission)
    {
        parent::__construct();
        $this->holiday = $holiday;
        $this->permission = $permission;
    }

    public function index(Request $request)
    {
        $page_title = 'Holiday';
        $page_description = 'Holiday Lists';

        if ($request->input('year') && $request->input('year') != '') {
            $holidays = Holiday::whereBetween('start_date', [$request->input('year').'-01-01', $request->input('year').'-12-31'])->orderBy('start_date', 'asc')->get();
        } else {
            $holidays = Holiday::whereBetween('start_date', [date('Y').'-01-01', date('Y').'-12-31'])->orderBy('start_date', 'asc')->get();
        }

        return view('admin.holiday.index', compact('page_title', 'page_description', 'holidays'));
    }

    public function store(Request $request)
    {
        $this->holiday->create($request->all());
        Flash::success('Holiday has been updated successfully.');

        return Redirect::back();
    }

    public function destroy($id)
    {
        Holiday::where('holiday_id', $id)->delete();
        Flash::success('Holiday has been deleted successfully.');

        return Redirect::back();
    }

    public function edit($id)
    {
        $holiday = Holiday::where('holiday_id', $id)->first()->toArray();

        return $holiday;
    }

    public function update(Request $request, $id)
    {
        Holiday::where('holiday_id', $id)->update(['event_name' => $request->event_name, 'description' => $request->description, 'start_date' => $request->start_date, 'end_date' => $request->end_date, 'location' => $request->location, 'color' => $request->color, 'types'=>$request->types]);

        Flash::success('Holiday has been updated successfully.');

        return Redirect::back();
    }

    public function DownloadPdf($year)
    {
        $holidays = Holiday::whereBetween('start_date', [$year.'-01-01', $year.'-12-31'])->orderBy('start_date', 'asc')->get();
        $months = [
            ['January', 1],
            ['February', 2],
            ['March', 3],
            ['April', 4],
            ['May', 5],
            ['June', 6],
            ['July', 7],
            ['August', 8],
            ['September', 9],
            ['October', 10],
            ['November', 11],
            ['December', 12],
        ];
        $months = array_chunk($months, 3);
        foreach ($months as $key=>$m) {
            for ($i = 0; $i < 3; $i++) {
                $holiday = [];
                $count_flag = 0;
                foreach ($holidays as $hv) {
                    $m_temp = explode('-', $hv->start_date);
                    if ($m_temp[1] == $m[$i][1]) {
                        $event = '<li>'.$hv->event_name.'<br><i>'.date('dS M', strtotime($hv->start_date)).'-'.date('dS M', strtotime($hv->end_date)).'</i></li>';
                        array_push($holiday, $event);
                    }
                }
                $months[$key][$i][2] = $holiday;
            }
        }
        $pdf = \PDF::loadView('admin.holiday.holidayspdf', compact('months', 'year'));
        $file = 'holdays_'.$year.'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function DownloadExcel($year)
    {
        $holidays = Holiday::whereBetween('start_date', [$year.'-01-01', $year.'-12-31'])->orderBy('start_date', 'asc')->get();
        $name = 'holiday_'.date('Y-m-d').'.csv';

        return \Excel::download(new \App\Exports\ExcelExport($data), $name);
    }
}
