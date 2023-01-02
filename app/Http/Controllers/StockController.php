<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Models\Role as Permission;
use App\Models\Stock;
use App\Models\StockAssign;
use App\Models\StockCategory;
use App\Models\StockReturn;
use App\Models\StockSubCategory;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class StockController extends Controller
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

    // Stock Category
    public function category()
    {
        $page_title = 'Stock Category';
        $page_description = 'All Stock Categories';

        $categories = StockCategory::orderBy('stock_category', 'asc')->get();

        return view('admin.stock.category', compact('page_title', 'page_description', 'categories'));
    }

    public function saveCategory(Request $request)
    {
        if ($request->stock_category_id == '') {
            $this->validate($request, ['stock_category' => 'required']);
            $stockCategory = StockCategory::create(['stock_category' => $request->stock_category]);

            if ($request->stock_sub_category != '') {
                StockSubCategory::create(['stock_category_id' => $stockCategory->id, 'stock_sub_category' => $request->stock_sub_category]);
            }

            Flash::success('Category created Successfully');
        } else {
            if ($request->stock_sub_category_id) {
                StockSubCategory::where('stock_sub_category_id', $request->stock_sub_category_id)->update(['stock_category_id' => $request->stock_category_id, 'stock_sub_category' => $request->stock_sub_category]);
                Flash::success('Sub Category updated Successfully');
            } else {
                StockSubCategory::create(['stock_category_id' => $request->stock_category_id, 'stock_sub_category' => $request->stock_sub_category]);
                Flash::success('Sub Category created Successfully');
            }
        }
        //return Redirect::back();
        return redirect('/admin/stock/category');
    }

    public function editCategory($stock_category_id)
    {
        $category = StockCategory::where('stock_category_id', $stock_category_id)->first();
        $data = '<div class="panel panel-custom">
                    <div class="panel-heading">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit Stock Category</h4>
                    </div>
                    <div class="modal-body wrap-modal wrap">
                        <form id="form_validation" action="/admin/stock/category/'.$stock_category_id.'" method="post" class="form-horizontal form-groups-bordered">'.csrf_field().'
                            <div class="form-group" id="border-none">
                                <label for="field-1" class="col-sm-4 control-label">Edit Stock Category<span class="required">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" name="stock_category" class="form-control" value="'.$category->stock_category.'" required="required">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>';

        return $data;
    }

    public function updateCategory(Request $request, $stock_category_id)
    {
        StockCategory::where('stock_category_id', $stock_category_id)->update(['stock_category' => $request->stock_category]);
        Flash::success('Category updated Successfully');

        return redirect('/admin/stock/category');
    }

    public function deleteCategory($stock_category_id)
    {
        StockCategory::where('stock_category_id', $stock_category_id)->delete();
        StockSubCategory::where('stock_category_id', $stock_category_id)->delete();
        Flash::success('Category deleted Successfully');

        return Redirect::back();
    }

    public function editSubCategory($cat, $subCat)
    {
        $page_title = 'Stock Category';
        $page_description = 'All Stock Categories';

        $categories = StockCategory::orderBy('stock_category', 'asc')->get();
        $subCategory = StockSubCategory::where('stock_sub_category_id', $subCat)->first();

        return view('admin.stock.category', compact('page_title', 'page_description', 'categories', 'subCategory'));
    }

    public function deleteSubCategory($subCat)
    {
        StockSubCategory::where('stock_sub_category_id', $subCat)->delete();
        Flash::success('Sub Category deleted Successfully');

        return Redirect::back();
    }

    // Stock Inventory
    public function lists()
    {
        $page_title = 'Stock List';
        $page_description = 'All Stocks';

        $allStockCats = StockSubCategory::select(
            'asset_sub_category.stock_category_id',
            'asset_sub_category.stock_sub_category_id',
            'asset_sub_category.stock_sub_category'
        )
            ->join('assets', 'assets.stock_sub_category_id', '=', 'asset_sub_category.stock_sub_category_id')
            //->groupBy('asset_sub_category.stock_category_id')
            ->orderBy('stock_id', 'desc')
            ->get();

        $stockCats = StockCategory::select('stock_category_id', 'stock_category')->orderBy('stock_category', 'asc')->get();

        $projectslist = Projects::orderBy('id', 'desc')->get();
        $departments = \App\Models\Department::all();
        //dd($allStockCats);
        $suplier = \App\Models\Client::where('relation_type', 'supplier')->get();
        $stocks = Stock::select('assets.*')->where(function ($query) {
            if (\Request::get('category') && \Request::get('category') != '') {
                return $query->where(
                    'asset_sub_category.stock_category_id',
                    \Request::get('category')
                );
            }
        })
            ->join('asset_sub_category', 'assets.stock_sub_category_id', '=', 'asset_sub_category.stock_sub_category_id')
            ->groupBy('assets.stock_id')
            ->paginate(20);

        // dd($stocks);

        return view('admin.stock.list', compact('page_title', 'page_description', 'allStockCats', 'stockCats', 'projectslist', 'departments', 'suplier', 'stocks'));
    }

    public function saveStock(Request $request)
    {
        if ($request->stock_id) {
            Stock::where('stock_id', $request->stock_id)->update(
                [
                    'stock_sub_category_id' => $request->stock_sub_category_id,
                    'project_id' => $request->project_id,
                    'item_name' => $request->item_name,
                    'total_stock' => $request->total_stock,
                    'unit_price' => $request->unit_price,
                    'buying_date' => $request->buying_date,
                    'departments_id' => $request->departments_id,
                    'types' => $request->types,
                    'asset_number' => $request->asset_number,
                    'conditions' => $request->conditions,
                    'item_model' => $request->item_model,
                    'location' => $request->location,
                    'supplier' => $request->supplier,
                    'invoice_number' => $request->invoice_number,
                    'unit_salvage_value' => $request->unit_salvage_value,
                    'service_date' => $request->service_date,
                    'depreciation_rate' => $request->depreciation_rate,
                    'annual_depreciation' => $request->annual_depreciation,
                    'accumulated_depreciation' => $request->accumulated_depreciation,
                    'fiscal_year_id' => \FinanceHelper::cur_fisc_yr()->id,
                    'asset_status' => $request->asset_status,

                ]
            );
            Flash::success('Stock updated Successfully');
        } else {
            Stock::create([
                'stock_sub_category_id' => $request->stock_sub_category_id,
                'project_id' => $request->project_id,
                'item_name' => $request->item_name,
                'total_stock' => $request->total_stock,
                'unit_price' => $request->unit_price,
                'buying_date' => $request->buying_date,
                'departments_id' => $request->departments_id,
                'types' => $request->types,
                'asset_number' => $request->asset_number,
                'conditions' => $request->conditions,
                'item_model' => $request->item_model,
                'location' => $request->location,
                'supplier' => $request->supplier,
                'invoice_number' => $request->invoice_number,
                'unit_salvage_value' => $request->unit_salvage_value,
                'service_date' => $request->service_date,
                'depreciation_rate' => $request->depreciation_rate,
                'annual_depreciation' => $request->annual_depreciation,
                'accumulated_depreciation' => $request->accumulated_depreciation,
                'fiscal_year_id' => \FinanceHelper::cur_fisc_yr()->id,
                'asset_status' => 'draft',

            ]);
            Flash::success('Stock created Successfully');
        }

        return redirect('/admin/stock/list');
    }

    public function editStock($stock_id)
    {
        $page_title = 'Edit Stock';
        $page_description = 'Stock List';

        $allStockCats = StockSubCategory::select('asset_sub_category.stock_category_id', 'asset_sub_category.stock_sub_category_id', 'asset_sub_category.stock_sub_category')
            ->join('assets', 'assets.stock_sub_category_id', '=', 'asset_sub_category.stock_sub_category_id')
            //->groupBy('asset_sub_category.stock_category_id')
            ->orderBy('asset_sub_category.stock_category_id', 'desc')
            ->get();

        $stockCats = StockCategory::select('stock_category_id', 'stock_category')->orderBy('stock_category', 'asc')->get();
        $stock = Stock::where('stock_id', $stock_id)->first();
        $projectslist = Projects::orderBy('id', 'desc')->get();
        $departments = \App\Models\Department::all();
        $suplier = \App\Models\Client::where('relation_type', 'supplier')->get();
        $stocks = Stock::paginate(20);

        return view('admin.stock.list', compact('page_title', 'page_description', 'allStockCats', 'stock', 'stockCats', 'stock', 'projectslist', 'departments', 'suplier', 'stocks'));
    }

    public function deleteStock($stock_id)
    {
        Stock::where('stock_id', $stock_id)->delete();
        Flash::success('Stock deleted Successfully');

        return redirect('/admin/stock/list');
    }

    public function history(Request $request)
    {
        $page_title = 'Stock History';
        $page_description = 'Stock History';

        $categories = StockCategory::select('stock_category_id', 'stock_category')->orderBy('stock_category', 'asc')->get();

        $stocks = null;
        $stock_sub_category_id = null;
        if ($request->stock_sub_category_id) {
            $stocks = Stock::where('stock_sub_category_id', $request->stock_sub_category_id)->get();
            $stock_sub_category_id = $request->stock_sub_category_id;
        }

        return view('admin.stock.history', compact('page_title', 'page_description', 'categories', 'stock_sub_category_id', 'stocks'));
    }

    public function assign()
    {
        $page_title = 'Assign Stock';
        $page_description = 'Assign Stock';
        $projectslist = Projects::orderBy('id', 'desc')->get();

        $assigns = StockAssign::orderBy('assign_date', 'desc')->get();

        $categories = StockCategory::orderBy('stock_category', 'asc')->get();
        $users = User::select('id', 'first_name', 'last_name')->where('enabled', '1')->where('id', '!=', '1')->get();

        return view('admin.stock.assign', compact('page_title', 'page_description', 'projectslist', 'assigns', 'categories', 'users'));
    }

    public function getStockBySubCategory(Request $request)
    {
        $stocks = Stock::where('stock_sub_category_id', $request->stock_sub_category_id)->get();

        $data = '<option value="">Select Item Name</option>';
        foreach ($stocks as $sk => $sv) {
            $data .= '<option value="'.$sv->stock_id.'" data-stock="'.$sv->total_stock.'">'.$sv->item_name.'</option>';
        }

        return ['success' => 1, 'data' => $data];
    }

    public function getStockQuantity(Request $request)
    {
        $assign = StockAssign::orderBy('id', 'desc')->where('stock_id', $request->stock_id)->sum('assign_inventory');
        $return = StockReturn::orderBy('id', 'desc')->where('stock_id', $request->stock_id)->sum('return_inventory');
        $temp = (int) $assign - (int) $return;

        return ['success' => 1, 'data' => $temp];
    }

    public function saveAssign(Request $request)
    {
        StockAssign::create($request->all());
        Flash::success('Stock has been assigned Successfully');

        return redirect('/admin/stock/assign');
    }

    public function deleteAssign($assign_item_id)
    {
        StockAssign::where('assign_item_id', $assign_item_id)->delete();
        Flash::success('Stock Assigned deleted Successfully');
        //return redirect('/admin/stock/assign');
        return Redirect::back();
    }

    public function printAssign()
    {
        $assigns = StockAssign::orderBy('assign_date', 'desc')->get();
        $user_id = null;

        return view('admin.stock.printAssign', compact('assigns', 'user_id'));
    }

    public function generateAssignPdf()
    {
        $assigns = StockAssign::orderBy('assign_date', 'desc')->get();
        $user_id = null;

        $pdf = \PDF::loadView('admin.stock.generateAssignPdf', compact('assigns', 'user_id'));
        $file = 'Stock_Assign_List_'.date('Y_m_d').'.pdf';

        return $pdf->download($file);
    }

    public function assignReport(Request $request)
    {
        $page_title = 'Stock Assign Report';
        $page_description = 'Stock Assign Report';

        $users = User::select('id', 'first_name', 'last_name')->where('enabled', '1')->where('id', '!=', '1')->get();

        $assigns = null;
        $user_id = null;
        if ($request->user_id) {
            $assigns = StockAssign::where('user_id', $request->user_id)->orderBy('assign_date', 'desc')->get();
            $user_id = $request->user_id;
        }

        return view('admin.stock.assignReport', compact('page_title', 'page_description', 'users', 'assigns', 'user_id'));
    }

    public function printUserAssign($user_id)
    {
        $assigns = StockAssign::where('user_id', $user_id)->orderBy('assign_date', 'desc')->get();
        $user_id = $user_id;

        return view('admin.stock.printAssign', compact('assigns', 'user_id'));
    }

    public function generateUserAssignPdf($user_id)
    {
        $assigns = StockAssign::where('user_id', $user_id)->orderBy('assign_date', 'desc')->get();
        $user_id = $user_id;

        $pdf = \PDF::loadView('admin.stock.generateAssignPdf', compact('assigns', 'user_id'));
        $file = 'Stock_Assign_List_'.date('Y_m_d').'.pdf';

        return $pdf->download($file);
    }

    public function report()
    {
        $page_title = 'Stock Report';
        $page_description = 'Stock Report';

        $assigns = null;

        return view('admin.stock.report', compact('page_title', 'page_description', 'assigns'));
    }

    public function reportList(Request $request)
    {
        $page_title = 'Stock Report';
        $page_description = 'Stock Report';

        $assigns = StockAssign::orderBy('stock_id', 'asc')->whereBetween('assign_date', [$request->start_date, $request->end_date])->get();

        return view('admin.stock.report', compact('page_title', 'page_description', 'assigns'));
    }

    public function printUserAssignByDate($start_date, $end_date)
    {
        $assigns = StockAssign::whereBetween('assign_date', [$start_date, $end_date])->orderBy('assign_date', 'desc')->get();
        $user_id = null;

        return view('admin.stock.printAssign', compact('assigns', 'user_id', 'start_date', 'end_date'));
    }

    public function generateUserAssignPdfByDate($start_date, $end_date)
    {
        $assigns = StockAssign::whereBetween('assign_date', [$start_date, $end_date])->orderBy('assign_date', 'desc')->get();
        $user_id = null;

        $pdf = \PDF::loadView('admin.stock.generateAssignPdf', compact('assigns', 'user_id', 'start_date', 'end_date'));
        $file = 'Stock_Assign_List_'.date('Y_m_d').'.pdf';

        return $pdf->download($file);
    }

    public function return()
    {
        $page_title = 'Assign Stock';
        $page_description = 'Assign Stock';
        $projectslist = Projects::orderBy('id', 'desc')->get();

        $returns = StockReturn::orderBy('return_date', 'desc')->get();

        $categories = StockCategory::orderBy('stock_category', 'asc')->get();
        $users = User::select('id', 'first_name', 'last_name')->where('enabled', '1')->where('id', '!=', '1')->get();

        return view('admin.stock.return', compact('page_title', 'page_description', 'projectslist', 'returns', 'categories', 'users'));
    }

    public function saveReturn(Request $request)
    {
        StockReturn::create($request->all());
        Flash::success('Stock has been Returned Successfully');

        return redirect('/admin/stock/return');
    }

    public function printReturn()
    {
        $assigns = StockReturn::orderBy('return_date', 'desc')->get();
        $user_id = null;

        return view('admin.stock.printReturn', compact('assigns', 'user_id'));
    }

    public function generateReturnPdf()
    {
        $assigns = StockReturn::orderBy('return_date', 'desc')->get();
        $user_id = null;

        $pdf = \PDF::loadView('admin.stock.generateReturnPdf', compact('assigns', 'user_id'));
        $file = 'Stock_Return_List_'.date('Y_m_d').'.pdf';

        return $pdf->download($file);
    }

    public function deleteReturn($return_item_id)
    {
        StockReturn::where('return_item_id', $return_item_id)->delete();
        Flash::success('Stock Returned deleted Successfully');
        //return redirect('/admin/stock/assign');
        return Redirect::back();
    }
}
