<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\JobSheet;
use App\Models\JobsheetItem;
use App\Models\JobsheetRemark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class JobsheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $jobSheet;

    public function __construct(JobSheet $jobSheet)
    {
        $this->jobSheet = $jobSheet;

    }

    public function index()
    {
        $page_title = 'Admin | Job Sheet';


            $jobSheets = $this->jobSheet
            ->whereHas('customer', function ($q) {
                $q->when(\Request::get('name'), function ($q) {
                    $q->where('name', 'LIKE', '%' . \Request::get('customer') . '%');
                });
                $q->when(\Request::get('contact'), function ($q) {
                    $q->where('phone', 'LIKE', '%' . \Request::get('contact') . '%');
                });

            })
            ->when(\Request::get('brand')!="" && \Request::get('brand'), function($query) {
                $query->where('brand','LIKE', '%' . \Request::get('brand') . '%');
            })
            ->when(\Request::get('model_name')!="" && \Request::get('model_name'), function($query) {
                $query->where('model_name','LIKE', '%' . \Request::get('model_name') . '%');
            })
            ->when(\Request::get('model_number')!="" && \Request::get('model_number'), function($query) {
                $query->where('model_number','LIKE', '%' . \Request::get('model_number') . '%');
            })
            ->when(\Request::get('device_status')!="" && \Request::get('device_status'), function($query) {
                $query->where('device_status', \Request::get('device_status'));
            })
            ->orderBy('id', 'desc')->paginate(30);


        return view('admin.job-sheet.index', compact('page_title', 'jobSheets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Admin|Job|Sheet';
        $page_description = 'Create new job sheet';
        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id');
        $types = \App\Models\ProductCategory::pluck('name', 'id');

        return view('admin.job-sheet.create', compact('page_title', 'page_description', 'users', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function searchCustomer()
    {
        $page_title = 'Admin|Job Sheet';
        $page_description = 'Create new Job Sheet';
        return view('admin.job-sheet.customer-search', compact('page_title', 'page_description'));
    }

    public function findCustomer(Request $request)
    {
        $customer = Client::where('phone','like', '%'.$request->mobile.'%')
        ->orWhere('name','like', '%'.$request->mobile.'%')->first();
        if (!$customer) {
            Flash::warning('Customer Not Found !! Please create one');
            return redirect()->to('admin/job-sheet/search-customer');
        }
        $page_title = 'Admin|Job Sheet';
        $page_description = 'Create new Job Sheet';
        return view('admin.job-sheet.customer-search', compact('page_title', 'page_description', 'customer'));
    }

    public function store(Request $request)
    {


        $jobSheet = $this->jobSheet->create($request->all());
        foreach ($request->items as $item) {
            $it = new JobsheetItem();
            $it->create([
                'job_sheet_id' => $jobSheet->id,
                'item' => $item
            ]);
        }
        foreach ($request->remarks as $remark) {
            $rem = new JobsheetRemark();
            $rem->create([
                'job_sheet_id' => $jobSheet->id,
                'remark' => $remark,
                'faults' => $request->faults[$key]
            ]);
        }

        Flash::success('Job Sheet SuccessFully Created');
        return redirect()->to('/admin/job-sheet');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Admin|Job Sheet';
        $page_description = 'Create new Job Sheet';
        $jobSheet = $this->jobSheet->find($id);
        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id');
        $types = \App\Models\ProductCategory::pluck('name', 'id');

        return view('admin.job-sheet.edit', compact('page_title', 'page_description', 'jobSheet', 'users', 'types'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // dd($request->faults);

        $jobSheet = $this->jobSheet->find($id)->update($request->all());
        JobsheetItem::where('job_sheet_id', $id)->delete();
        foreach ($request->items as $item) {
            $it = new JobsheetItem();
            $it->create([
                'job_sheet_id' => $id,
                'item' => $item
            ]);
        }
        JobsheetRemark::where('job_sheet_id', $id)->delete();
        foreach ($request->remarks as $key=>$remark) {
            $rem = new JobsheetRemark();
            $rem->create([
                'job_sheet_id' => $id,
                'remark' => $remark,
                'faults' => $request->faults[$key]
            ]);
        }


        Flash::success('Job-sheet Update Successfully');
        return redirect()->to('/admin/job-sheet');
    }

    public function repairStatus(Request $request, $id)
    {
        $jobSheet = $this->jobSheet->find($id);
        $jobSheet->update(['device_status' => $request->device_status]);
        $request->session()->flash('status', 'Task was successful!');
        return response($jobSheet);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jobSheet = $this->jobSheet->find($id);

//        if (! $jobSheet->isdeletable()) {
//            abort(403);
//        }
        $jobSheet->items()->delete();
        $jobSheet->remarks()->delete();
        $jobSheet->delete();


        Flash::success('Job Sheet deleted.');


        return redirect()->back();
    }

    public function deleteModal($id)
    {
        $error = null;

        $jobSheet = $this->jobSheet->find($id);

//        if (! $jobSheet->isdeletable()) {
//            abort(403);
//        }

        $modal_title = 'Delete Job Sheet';

        $jobSheet = $this->jobSheet->find($id);
        if (\Request::get('type')) {
            $modal_route = route('admin.job-sheet.delete', $jobSheet->id) . '?type=' . \Request::get('type');
        } else {
            $modal_route = route('admin.job-sheet.delete', $jobSheet->id);
        }

        $modal_body = 'Are you sure you want to delete this Job Sheet?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }


    public function print($id)
    {
        $jobSheet = $this->jobSheet->find($id);
        return view('admin.job-sheet.print', compact('jobSheet'));
    }

}
