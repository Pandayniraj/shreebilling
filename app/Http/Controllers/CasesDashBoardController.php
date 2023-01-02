<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Cases;
use DB;
use Illuminate\Http\Request;

class CasesDashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Request::get('year')) {
            $thisyear = \Request::get('year');
        } else {
            $thisyear = date('Y');
        }
        $m_install_complain = [
            ['name'=>'Installment', 'data'=>[], 'color'=>'#00A65A'],
            ['name'=>'Complain', 'data'=>[], 'color'=>'#DC3545'],
        ];
        foreach (range(1, 12) as $key => $m) {
            $start_date = $thisyear.'-'.$m.'-'.'01';
            $totaldays = \Carbon\Carbon::parse($start_date)->daysInMonth;
            $end_date = $thisyear.'-'.$m.'-'.$totaldays;
            $m_installment = Cases::where('type', 'installation')->where('cal_date', '>=', $start_date)->where('cal_date', '<=', $end_date)->count();
            $m_complain = Cases::where('type', 'complain')->where('cal_date', '>=', $start_date)->where('cal_date', '<=', $end_date)->count();
            $m_install_complain[0]['data'][] = $m_installment;
            $m_install_complain[1]['data'][] = $m_complain;

            // code...
        }

        $install_products = Cases::where('type', 'installation')->whereNotNull('product')->get()->groupBy('product');
        $install_products_data = [];
        foreach ($install_products as $key=>$value) {
            $product = $value[0]->products->name;
            if ($product) {
                $data = $value->count();
                $install_products_data[] = ['name'=>$product, 'y'=>$data];
            }
        }
        $install_products = Cases::where('type', 'complain')->whereNotNull('product')->get()->groupBy('product');
        $complain_products_data = [];
        foreach ($install_products as $key=>$value) {
            $product = $value[0]->products->name;
            if ($product) {
                $data = $value->count();
                $complain_products_data[] = ['name'=>$product, 'y'=>$data];
            }
        }
        $status_installment = DB::select("
        SELECT sum(CASE WHEN cases.status = 'new' THEN 1 ELSE 0 END) as new,
               sum(CASE WHEN cases.status = 'assigned' THEN 1 ELSE 0 END) as assigned,
               sum(CASE WHEN cases.status = 'closed' THEN 1 ELSE 0 END) as closed,
               sum(CASE WHEN cases.status = 'pending' THEN 1 ELSE 0 END) as pending,
               sum(CASE WHEN cases.status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM cases WHERE type = 'installation'")[0];
        $status_installment = json_decode(json_encode($status_installment), true);
        $status_installment_data = [];
        foreach ($status_installment as $key=>$value) {
            $status_installment_data[] = ['name'=>ucfirst($key), 'y'=>(int) $value];
        }

        $years = $years = range(date('Y'), 1900);

        $page_title = 'Dashboard';
        $page_description = 'Cases Dashboard';

        return view('admin.cases.dashboard', compact('years', 'thisyear', 'm_install_complain', 'install_products_data', 'complain_products_data', 'status_installment_data', 'page_title', 'page_description'));
    }
}
