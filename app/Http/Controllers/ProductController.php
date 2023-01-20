<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Fiscalyear;
use App\Models\Entryitem;
use \App\Exports\ExcelExport;
use App\Models\StockAdjustmentDetail;
use App\Models\Invoice;
USE App\Helpers\FinanceHelper;
use App\Models\InvoiceDetail;
use App\Models\StockMove;
use App\Models\SupplierReturnDetail;
use App\Models\SupplierReturn;
use App\Models\ProductSerialNumber;
use App\Models\ProductModel;
use App\Models\COALedgers;
use App\Models\Entrytype;
use App\Helpers\TaskHelper;
use App\Helpers\StockHelper;
use App\Models\Department;
use App\Models\StockAdjustment;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTypeMaster;
use App\Models\ProductsUnit;
use App\Models\AdjustmentReason;
use App\Models\PosOutlets;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrder;
use App\Models\Store;
use App\Models\StockMaster;
use App\Models\Audit as Audit;
use App\Models\Product as Course;
use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER
 */

class ProductController extends Controller
{
    /**
     * @var Course
     */
    private $course;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Course $course
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Course $course, Permission $permission)
    {
        parent::__construct();
        $this->course = $course;
        $this->permission = $permission;
    }

    /**
     * @return \Illuminate\View\View
     */


    public function downloadExcel($query){


        $courses = $query->get()->toArray();


        return \Excel::download(new ExcelExport($courses), "products.csv");;


    }

    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-index'));

        $courses = $this->course->select('products.*','product_categories.name as product_categories','pos_outlets.name as outlet_name')->where(function ($query) {
            $terms = \Request::get('term');
            if ($terms)
                return $query->where('products.name', 'LIKE', '%' . $terms . '%');
        })
        ->where(function ($q){
            $q->where('parent_product_id',0);
            $q->orWhereNull('parent_product_id');
        })->where(function($query){

            $type_master_id = \Request::get('product_cat_master');

            if($type_master_id){

                return $query->where('product_type_masters.id',$type_master_id);
            }


        })->where(function($query){

            $product_cat = \Request::get('product_cat');

            if($product_cat){

                return $query->where('product_categories.id',$product_cat);
            }


        })->leftjoin('product_type_masters','products.product_type_id','=','product_type_masters.id')
        ->leftjoin('pos_outlets','pos_outlets.id','=','products.outlet_id')
        ->leftjoin('product_categories','products.category_id','=','product_categories.id')
        ->orderBy('id', 'desc');



        if(\Request::get('productSearch') == 'excel'){


            return $this->downloadExcel($courses);

        }
        $courses = $courses->paginate(30);


        $page_title = 'Products & Inventory';

        $page_description = trans('admin/courses/general.page.index.description');

        $producttypeMaster = ProductTypeMaster::pluck('name','id');

        $productCategory = ProductCategory::pluck('name','id');
        //dd($transations);

        return view('admin.products.index', compact('courses', 'page_title', 'page_description','producttypeMaster','productCategory'));
    }



    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $course = $this->course->find($id);

        Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-show', ['name' => $course->name]));

        $page_title = trans('admin/courses/general.page.show.title'); // "Admin | Course | Show";
        $page_description = trans('admin/courses/general.page.show.description'); // "Displaying course: :name";
        return view('admin.courses.show', compact('course', 'page_title', 'page_description'));

        return view('admin.products.show', compact('course', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/courses/general.page.create.title'); // "Admin | Course | Create";
        $page_description = trans('admin/courses/general.page.create.description'); // "Creating a new course";

        $course = new Product();
        $perms = $this->permission->all();
        $categories = ProductCategory::orderBy('name', 'ASC')->where('org_id', \Auth::user()->org_id)->pluck('name', 'id');
        $product_unit = ProductsUnit::pluck('name', 'id');
        $outlets  = PosOutlets::orderBy('id', 'asc')->pluck('name', 'id')->all();

        $product_type_masters = ProductTypeMaster::pluck('name', 'id');
        $products = Product::latest()->where('org_id', \Auth::user()->org_id)->pluck('name', 'id')->all();
        $stores = Store::pluck('name', 'id');

        if(\Request::ajax()){

            return view('admin.products.modals.create', compact('course', 'perms', 'outlets', 'page_title', 'page_description', 'categories', 'product_unit', 'product_type_masters','stores'));
        }

        return view('admin.products.create', compact('products','course', 'perms', 'outlets', 'page_title', 'page_description', 'categories', 'product_unit', 'product_type_masters','stores'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if (\Request::ajax()) {
            $validator = \Validator::make($request->all(), [
                'name'  => 'required|unique:products',
            ]);
            if ($validator->fails()) {
                return ['error' => $validator->errors()];
            }
        }
        $this->validate($request, ['name'  => 'required|unique:products',]);
        $attributes = $request->all();

        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['created_by'] = Auth::user()->id;
        if ($request->file('product_image')) {
            $stamp = time();
            $file = $request->file('product_image');
            $destinationPath = public_path() . '/products/';
            if (!\File::isDirectory($destinationPath)) {
                \File::makeDirectory($destinationPath, 0777, true, true);
            }
            $filename = $file->getClientOriginalName();
            $request->file('product_image')->move($destinationPath, $stamp . '_' . $filename);
            $attributes['product_image'] = $stamp . '_' . $filename;
        }

        // dd($attributes);
        if (!empty($request->inventory)){
            $attributes['product_division'] = 'raw_material';
        }
        else if(!empty($request->fixed_asset)){
            $attributes['product_division'] = 'fixed_asset';
        }
        else if(!empty($request->service)){
            $attributes['product_division'] = 'service';
        }

        Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-store', ['name' => $attributes['name']]));

        $course = $this->course->create($attributes);

        if ($request->is_fixed_assets) {
            $this->postledgers($course);
        }
        if (\Request::ajax()) {
            return ['status' => 'success', 'lastcreated' => $course];
        }
        Flash::success(trans('admin/courses/general.status.created')); // 'Course successfully created');

        return redirect('/admin/products');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $course = $this->course->find($id);
        $categories = ProductCategory::orderBy('name', 'ASC')->where('org_id', \Auth::user()->org_id)->pluck('name', 'id');

        //dd($categories);
        Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-edit', ['name' => $course->name]));

        $page_title = trans('admin/courses/general.page.edit.title'); // "Admin | Course | Edit";
        $page_description = trans('admin/courses/general.page.edit.description', ['name' => $course->name]); // "Editing course";


        $transations = StockMove::where('product_stock_moves.stock_id', $id)
        ->leftjoin('products', 'products.id', '=', 'product_stock_moves.stock_id')
        ->leftjoin('product_location', 'product_location.id', '=', 'product_stock_moves.store_id')
        ->select('product_stock_moves.*', 'products.name', 'product_location.location_name')
        ->get();

        $locData = DB::table('store')->get();
        $loc =  DB::table('store')->get();

        $loc_name = array();
        foreach ($loc as $value) {
            $loc_name[$value->id] = $value->name;
        }

        $loc_name = $loc_name;


        if (!$course->isEditable() &&  !$course->canChangePermissions()) {
            abort(403);
        }

        $product_unit = ProductsUnit::pluck('name', 'id');

        $product_type_masters = ProductTypeMaster::pluck('name', 'id');
        $outlets  = PosOutlets::orderBy('id', 'asc')->pluck('name', 'id')->all();
        $products = Product::latest()->where('org_id', \Auth::user()->org_id)
        ->where('id','!=',$id)
        ->pluck('name', 'id')->all();

        $stores = Store::pluck('name', 'id');

        return view('admin.products.edit', compact('products','course', 'product_unit', 'outlets', 'page_title', 'locData', 'loc_name', 'page_description', 'transations', 'categories', 'product_type_masters','stores'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        $this->validate($request, array('name'=> 'required|unique:products,name,' . $id,));
        $course = $this->course->find($id);

        Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-update', ['name' => $course->name]));

        $attributes = $request->all();
        $attributes['org_id'] = \Auth::user()->org_id;

        $lastedUpdated = $course->updated_by ? $course->updated_by : '{}';
        $lastedUpdated = json_decode($lastedUpdated,true);

        $lastedUpdated [] = [
            'user_id'=>Auth::user()->id,
            'date'=>date('Y-m-d H:i:s'),
            'price'=>$request->price,
            'name'=>$request->name
        ];

        $lastedUpdated = json_encode($lastedUpdated);

        $attributes['updated_by'] = $lastedUpdated;

        // dd($request->file('product_image'));

        if ($request->file('product_image')) {
            $stamp = time();
            $destinationPath = public_path() . '/products/';
            $file = \Request::file('product_image');

            $filename = $file->getClientOriginalName();
            \Request::file('product_image')->move($destinationPath, $stamp . '_' . $filename);

            $attributes['product_image'] = $stamp . '_' . $filename;
        }

        if ($request->purchase=='raw_material'){
            $attributes['product_division'] = 'raw_material';
        }
        else if($request->purchase == 'fixed_asset'){
            $attributes['product_division'] = 'fixed_asset';
        }
        else if($request->purchase == 'service'){
            $attributes['product_division'] = 'service';
        }
        if ($course->isEditable()) {
            $course->update($attributes);
        }

        // dd($course);

        Flash::success(trans('admin/courses/general.status.updated')); // 'Course successfully updated');

        return redirect('/admin/products');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $course = $this->course->find($id);

        if (!$course->isdeletable()) {
            abort(403);
        }

        Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-destroy', ['name' => $course->name]));

        $course->delete();

        Flash::success(trans('admin/courses/general.status.deleted')); // 'Course successfully deleted');

        return redirect('/admin/products');
    }

    /**
     * Delete Confirm
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $error = null;

        $course = $this->course->find($id);

        if (!$course->isdeletable()) {
            abort(403);
        }

        $modal_title = trans('admin/courses/dialog.delete-confirm.title');

        $course = $this->course->find($id);
        $modal_route = route('admin.products.delete', $course->id);

        $modal_body = trans('admin/courses/dialog.delete-confirm.body', ['id' => $course->id, 'name' => $course->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $course = $this->course->find($id);

        Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-enable', ['name' => $course->name]));

        $course->enabled = true;
        $course->save();

        Flash::success(trans('admin/courses/general.status.enabled'));

        return redirect('/admin/products');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $course = $this->course->find($id);

        Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-disabled', ['name' => $course->name]));

        $course->enabled = false;
        $course->save();

        Flash::success(trans('admin/courses/general.status.disabled'));

        return redirect('/admin/products');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkCourses = $request->input('chkCourse');

        Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-enabled-selected'), $chkCourses);

        if (isset($chkCourses)) {
            foreach ($chkCourses as $course_id) {
                $course = $this->course->find($course_id);
                $course->enabled = true;
                $course->save();
            }
            Flash::success(trans('admin/courses/general.status.global-enabled'));
        } else {
            Flash::warning(trans('admin/courses/general.status.no-course-selected'));
        }
        return redirect('/admin/products');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkCourses = $request->input('chkCourse');

        Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-disabled-selected'), $chkCourses);

        if (isset($chkCourses)) {
            foreach ($chkCourses as $course_id) {
                $course = $this->course->find($course_id);
                $course->enabled = false;
                $course->save();
            }
            Flash::success(trans('admin/courses/general.status.global-disabled'));
        } else {
            Flash::warning(trans('admin/courses/general.status.no-course-selected'));
        }
        return redirect('/admin/courses');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;
        $query = $request->input('query');

        $courses = Product::where('name', 'LIKE', '%' . $query . '%')->get();

        foreach ($courses as $course) {
            $id = $course->id;
            $name = $course->name;
            $email = $course->email;

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
        $course = $this->course->find($id);

        return $course;
    }

    public function get_products()
    {
        $term = strtolower(\Request::get('term'));
        $outlet_id = \Request::get('outlet_id');
        $menu_id = \Request::get('menu_id');

        $productcategory_id = \Request::get('productcategory_id');

        // dd($productcategory_id);
        $categories = ProductCategory::where('enabled', '1')->pluck('id')->all();


        $products = Product::select('id', 'name')

        ->where(function ($query)  use ($categories, $productcategory_id, $term) {
            if ($categories && !$productcategory_id) {
                return $query->where('name', 'LIKE', '%' . $term . '%')->whereIn('category_id', $categories);
            }
        })
        ->where(function ($query)  use ($menu_id, $term) {
            if (!$menu_id) {
                return $query->where('name', 'LIKE', '%' . $term . '%');
            }
        })
        ->where(function($query){

            return $query->whereNull('is_raw_material')
            ->orWhere('is_raw_material','!=','1');

        })
        ->where('outlet_id',$outlet_id)
        ->where('enabled', '1')
        ->groupBy('name')
        ->take(15)
        ->get();
        $return_array = array();

        foreach ($products as $v) {
            if (strpos(strtolower($v->name), $term) !== FALSE) {
                $return_array[] = array('value' => $v->name, 'id' => $v->id);
            }
        }
        return \Response::json($return_array);
    }


    public function stocks_by_location()
    {

        $page_title = 'Stock Report';
        $page_description = 'counts';


        $stores = PosOutlets::pluck('name', 'id')->all();
        $products = Product::pluck('name', 'id')->all();

        return view('admin.products.stocksbylocation', compact('page_title', 'page_description','stores','products'));

    }

    public function stocks_by_location_post(Request $request)
    {

        $page_title = 'Outlets Balance Report';
        $page_description = 'counts';
        $current_store = $request->store_id;
        $current_product = $request->product_id;

        $transations =StockMove::
        leftjoin('products', 'products.id', '=', 'product_stock_moves.stock_id')
        ->leftjoin('pos_outlets', 'pos_outlets.id', '=', 'product_stock_moves.store_id')
        ->select('product_stock_moves.*', 'products.name as pname', 'pos_outlets.name')
        ->orderBy('product_stock_moves.tran_date', 'DESC')
        ->where(function ($query) use($current_store) {
            if($current_store!='' && $current_store!=null)
            $query->where('product_stock_moves.store_id', $current_store);
        })
        ->where(function ($query) use($current_product) {
            if($current_product!='' && $current_product!=null)
            $query->where('product_stock_moves.stock_id', $current_product);

        })
        ->get();

        $stores = PosOutlets::pluck('name', 'id')->all();
        $products = Product::pluck('name', 'id')->all();
        // dd($store);
        return view('admin.products.stocksbylocation', compact('page_title', 'page_description', 'transations', 'stores','products', 'current_store','current_product'));
    }
    public function delete_stockmoves_product($id){
        $item=StockMove::where('id',$id)->delete();

        Flash::success('Stock Entry Delete Successfully');

        return redirect('/admin/product/stocks_count');


    }
    public function stocks_count(Request $request)
    {
        $page_title = 'Stock Feeds';
        $page_description = 'counts';

        $todaydate = date('Y-m-d');
        $yesterdaydate = date('Y-m-d', strtotime($todaydate . ' -1 day'));

        $startdate = $request->startdate?$request->startdate:$yesterdaydate;
        $enddate = $request->enddate?$request->enddate:$todaydate;
        $trans_type = $request['trans_type']?$request['trans_type']:'';

        $transations = StockMove::select('product_stock_moves.*', 'products.name', 'pos_outlets.name as storename', 'products.id as pid')
        ->leftjoin('products', 'products.id', '=', 'product_stock_moves.stock_id')
        ->leftjoin('pos_outlets', 'pos_outlets.id', '=', 'product_stock_moves.store_id')
        ->when($startdate&&$enddate,function ($q) use ($enddate,$startdate) {
            $q->whereBetween('product_stock_moves.tran_date',array($startdate,$enddate));
        })
        ->when($trans_type,function ($q) use ($trans_type) {
            $q->where('product_stock_moves.trans_type',$trans_type);
        })
        ->orderBy('product_stock_moves.tran_date', 'DESC')
       
        ->get();
        if ($request->export){

            $transations = StockMove::select('product_stock_moves.*', 'products.name', 'pos_outlets.name as storename', 'products.id as pid')
            ->leftjoin('products', 'products.id', '=', 'product_stock_moves.stock_id')
            ->leftjoin('pos_outlets', 'pos_outlets.id', '=', 'product_stock_moves.store_id')
            ->when($startdate&&$enddate,function ($q) use ($enddate,$startdate) {
                $q->whereBetween('product_stock_moves.tran_date',array($startdate,$enddate));
            })
            ->when($trans_type,function ($q) use ($trans_type) {
                $q->where('product_stock_moves.trans_type',$trans_type);
            })
            ->orderBy('product_stock_moves.tran_date', 'DESC')
            ->get();
            return \Excel::download(new \App\Exports\StockFeedExport($transations,'Stock Feed Count',
                $startdate,$enddate), "stock_count_view".date('d M Y').".xls");
        }
        return view('admin.products.stockscount', compact('page_title', 'page_description', 'transations','startdate','enddate','trans_type'));
    }

    public function stockentry_detail($id)
    {
        $page_title = 'Stock Entries Detail';
        $page_description = 'detail';

        $transations = DB::table('product_stock_moves')
            // ->where('product_stock_moves.stock_id',$id)
        ->leftjoin('products', 'products.id', '=', 'product_stock_moves.stock_id')
        ->leftjoin('pos_outlets', 'pos_outlets.id', '=', 'product_stock_moves.store_id')
        ->select('product_stock_moves.*', 'products.name', 'pos_outlets.name as storename', 'products.id as pid')
        ->orderBy('product_stock_moves.tran_date', 'DESC')
        ->where('master_id', $id)
        ->paginate(40);

        return view('admin.products.stockentries_detail', compact('page_title', 'page_description', 'transations'));
    }

    public function stocks_entries()
    {
        $page_title = 'Stock Entries Main';
        $page_description = 'feed';

        $transations = StockMaster::orderBy('tran_date', 'DESC')
        ->paginate(40);

        return view('admin.products.stockentries', compact('page_title', 'page_description', 'transations'));
    }


    public function stock_adjustment(Request $request)
    {

        $page_title = 'Stock Adjustment';
        $page_description = 'Add stock or remove stocks fr example damaged or others';
        $current_fiscalyear=Fiscalyear::where('current_year',1)->where('org_id',Auth::user()->org_id)->first();
        $reason=$request->reason?$request->reason:'';
        $startdate=$request->startdate?$request->startdate:$current_fiscalyear->start_date;
        $enddate=$request->enddate?$request->enddate:$current_fiscalyear->end_date;
        $store=$request->store?$request->store:'';

        $stockadjustment = StockAdjustment::orderBy('id', 'desc')
                            ->when($reason, function ($q) use ($reason) {
                                $q->where('reason', $reason);
                            })
                            ->when($startdate && $enddate, function ($q) use ($startdate, $enddate) {
                                $q->where('transaction_date', '>=', $startdate);
                                $q->where('transaction_date', '<=', $enddate);
                            })
                            ->when($store, function ($q) use ($store) {
                                $q->where('store_id', $store);
                            })
                            ->with('detail')
                            ->get();

        $stores = PosOutlets::select('name', 'id')->get();
        $reasons = AdjustmentReason::select('name', 'id')->get();

        return view('admin.products.adjust.adjust', compact('page_title', 'page_description', 'stockadjustment', 'stores', 'reasons'));
    }


     public function stock_adjustment_create()
    {


        $page_title = 'Stock Adjustment Create';
        $page_description = 'Add stock or remove stocks fr example damaged or others';

        $stores = Store::pluck('name', 'id')->all();

        $account_ledgers = COALedgers::where('group_id', env('COST_OF_GOODS_SOLD'))->pluck('name', 'id')->all();

        $units = ProductsUnit::select('id', 'name', 'symbol')->get();
        $products = Product::where('enabled', '1')
        ->where(function ($q){
            $q->where('parent_product_id',0);
            $q->orWhereNull('parent_product_id');
        })
        ->get();
        $reasons  = AdjustmentReason::pluck('name', 'id')->all();
        $costcenter = PosOutlets::pluck('name', 'id')->all();
        $users = \App\User::pluck('username', 'id')->all();
        $departments = Department::pluck('deptname', 'departments_id')->all();

        return view('admin.products.adjust.create', compact('page_title', 'page_description', 'stores', 'account_ledgers', 'units', 'products', 'reasons','costcenter','users','departments'));
    }

    public function stock_adjustment_store(Request $request)
    {
        DB::beginTransaction();
        $attributes = $request->all();

        $attributes['tax_amount'] = $request->taxable_tax;
        $attributes['total_amount'] = $request->final_total;
        $attributes['transaction_date'] = $request->transaction_date;

        $stock_adjustment = StockAdjustment::create($attributes);

        $product_id = $request->product_id;
        $price = $request->price;
        $quantity = $request->quantity;
        $tax_amount = $request->tax_amount;
        $tax_rate = $request->tax_rate;
        $total = $request->total;
        $units = $request->units;


        $total_qty=0;
        foreach ($quantity as $qty){
            $total_qty+=$qty;
        }
        $stockmaster = new StockMaster();
        $stockmaster->stock_entry_id = 1;
        $stockmaster->tran_date = $request->transaction_date;
        $stockmaster->modules = "Stock Adjustments";
        $stockmaster->comment =  " From Stock Adjustment";
        $stockmaster->reason_id = $stock_adjustment->comments;
        $stockmaster->total_value = $stock_adjustment->total_amount;
        $stockmaster->total_qty = $total_qty;
        $stockmaster->store_id = $request->store_id;
        $stockmaster->reason_id = $request->reason;
        $stockmaster->module_id = $stock_adjustment->id;
        $stockmaster->active = 1;
        $stockmaster->save();


//        $stockmaster = new \App\Models\StockMaster();
//        $stockmaster->stock_entry_id = 1;
//        $stockmaster->tran_date = $request->transaction_date;
//        $stockmaster->modules = "Stock Adjustments";
//        $stockmaster->comment =  " From Stock Adjustment";
//        $stockmaster->reason_id = $request->reason;
//        $stockmaster->total_value = $request->final_total;
//        $stockmaster->save();
        $request_reason = AdjustmentReason::find($request->reason);

        foreach ($product_id as $key => $value) {
            if ($value != '') {

                $detail = new StockAdjustmentDetail();
                $detail->adjustment_id = $stock_adjustment->id;
                $detail->product_id = $product_id[$key];
                $detail->price = $price[$key];
                $detail->qty = $quantity[$key];
                $detail->total = $total[$key];
                $detail->tax_amount = $tax_amount[$key];
                $detail->tax_rate = $tax_rate[$key];
                $detail->unit = $units[$key];
                $detail->save();



                if ($request_reason) {

                    $stockMove = new StockMove();
                    $stockMove->stock_id = $product_id[$key];
                    $stockMove->master_id = $stockmaster->id;
                    $stockMove->order_no = $stock_adjustment->id;
                    $stockMove->unit_id = $units[$key];
                    $stockMove->tran_date = $request->transaction_date;
                    $stockMove->user_id = \Auth::user()->id;

                    $stockMove->trans_type = $request_reason->trans_type;
                    $stockMove->reference = $request_reason->name . '_' . $stock_adjustment->id;
                    $stockMove->order_reference =  $stock_adjustment->id;
                    $stockMove->location=$request->store_id;
                    if ($request_reason->reason_type == 'positive') {

                        $stockMove->qty =  $quantity[$key]  * StockHelper::getUnitPrice($detail->unit);
                    } else {

                        $stockMove->qty = '-' . $quantity[$key]  * StockHelper::getUnitPrice($detail->unit);
                    }


                    $stockMove->transaction_reference_id = $stock_adjustment->id;
                    $stockMove->store_id = $request->store_id;

                    $stockMove->price = $price[$key];
                    $stockMove->save();
                }
            }
        }
        if ($request_reason->reason_type == 'negative')
            $this->updateEntries($stock_adjustment->id);

        DB::commit();

        Flash::success('Stock Adjustment Done Successfully');

        return redirect('/admin/product/stock_adjustment');
    }

    private function updateEntries($adj_id)
    {
        $stock_adjustment =  StockAdjustment::find($adj_id);

        $totalAmountBeforeTax = $stock_adjustment->total_amount;
        if ($stock_adjustment->entry_id && $stock_adjustment->entry_id != '0') {
            //update the ledgers
            $attributes['entrytype_id'] = '16'; //Journal
            $attributes['tag_id'] = '2'; //Material cost
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = \Auth::user()->org_id;
//            $attributes['number'] = \FinanceHelper::get_last_entry_number($attributes['entrytype_id']);
            $attributes['bill_no'] = '';
            $attributes['date'] = $stock_adjustment->transaction_date;
            $attributes['dr_total'] = $totalAmountBeforeTax;
            $attributes['cr_total'] = $totalAmountBeforeTax;
            $attributes['source'] = 'AUTO_ADJUSTMENT';
            $attributes['currency'] = 'NPR';
            $attributes['notes'] = "ADJUSTMENT ID No # ". $adj_id;
            $entry = Entry::find($stock_adjustment->entry_id);
            $entry->update($attributes);

            Entryitem::where('entry_id',$entry->id)->delete();
        } else {
            
            //create the new entry items
            $attributes['entrytype_id'] = '16'; //Journal
            $attributes['tag_id'] = '2'; //Adjustment
            $attributes['user_id'] = \Auth::user()->id;
            $attributes['org_id'] = \Auth::user()->org_id;
            $attributes['bill_no'] = '';
            $attributes['date'] = $stock_adjustment->transaction_date;
            $attributes['dr_total'] = $totalAmountBeforeTax;
            $attributes['cr_total'] = $totalAmountBeforeTax;
            $attributes['source'] = 'AUTO_ADJUSTMENT';
            $attributes['fiscal_year_id'] = FinanceHelper::cur_fisc_yr()->id;
            $attributes['currency'] = 'NPR';
            $attributes['notes'] = "Adjustment ID No # ". $stock_adjustment->id;
            $type = Entrytype::find(5);
            $attributes['number'] = TaskHelper::generateId($type);

            $entry = Entry::create($attributes);
        }

        // Debitte to Bank or cash account that we are already in
        $order_product_type  = StockAdjustmentDetail::where('adjustment_id', $stock_adjustment->id)
        ->select('products.product_type_id')
        ->leftJoin('products','products.id','=','stock_adjustment_details.product_id')
        ->distinct('product_type_id')->get();
        foreach ($order_product_type as $opt) {
            if ($opt->product_type_id) {

                $purchase_ledger_id = ProductTypeMaster::find($opt->product_type_id)->purchase_ledger_id;
                $cogs_ledger_id = ProductTypeMaster::find($opt->product_type_id)->cogs_ledger_id;
                $product_type_total_amount = StockAdjustmentDetail::where('adjustment_id', $stock_adjustment->id)
                ->whereHas('product',function ($q) use ($opt) {
                    $q->where('product_type_id', $opt->product_type_id);
                })
                ->sum(\DB::raw('total'));
//                    dd($product_type_total_amount);

                $sub_amount = new Entryitem();
                $sub_amount->entry_id = $entry->id;
                $sub_amount->dc = 'D';
                $sub_amount->user_id = \Auth::user()->id;
                $sub_amount->org_id = \Auth::user()->org_id;
                $sub_amount->ledger_id = $cogs_ledger_id;
                $sub_amount->amount = $product_type_total_amount;
                $sub_amount->narration = 'Being purchase issued';
                $sub_amount->save();

                $cogs = new Entryitem();
                $cogs->entry_id = $entry->id;
                $cogs->dc = 'C';
                $cogs->user_id = \Auth::user()->id;
                $cogs->org_id = \Auth::user()->org_id;
                $cogs->ledger_id = $purchase_ledger_id;
                $cogs->amount = $product_type_total_amount;
                $cogs->narration = 'Being purchase issued';
                $cogs->save();
            }
        }

        //now update entry_id in income row
        $stock_adjustment->update(['entry_id' => $entry->id]);

        return 0;
    }


    public function stock_adjustment_edit($id)
    {
        $page_title = 'Stock Adjustment Edit';
        $page_description = 'Add stock or remove stocks fr example damaged or others';

        $stock_adjustment = StockAdjustment::find($id);

        $stock_adjustment_details = StockAdjustmentDetail::where('adjustment_id', $stock_adjustment->id)->get();
        //dd($stock_adjustment_details);

        $stores = Store::pluck('name', 'id')->all();

        $account_ledgers = COALedgers::where('group_id', env('COST_OF_GOODS_SOLD'))->pluck('name', 'id')->all();

        $units = ProductsUnit::select('id', 'name', 'symbol')->get();
        $products = Product::where('enabled', '1')
        ->where(function ($q){
            $q->where('parent_product_id',0);
            $q->orWhereNull('parent_product_id');
        })->get();

        $reasons  = AdjustmentReason::pluck('name', 'id')->all();
        $costcenter = PosOutlets::pluck('name', 'id')->all();
        $users = \App\User::pluck('username', 'id')->all();
        $departments = Department::pluck('deptname', 'departments_id')->all();
        return view('admin.products.adjust.edit', compact('departments','users','costcenter','page_title', 'page_description', 'stores', 'account_ledgers', 'units', 'products', 'stock_adjustment', 'stock_adjustment_details', 'reasons'));
    }

    public function stock_adjustment_update(Request $request, $id)
    {
        DB::beginTransaction();

//        $old_reason_name = \App\Models\StockAdjustment::find($id)->adjustmentreason->name;
//
//        $old_trans_type = \App\Models\StockAdjustment::find($id)->adjustmentreason->trans_type;


        $attributes = $request->all();
        $attributes['tax_amount'] = $request->taxable_tax;
        $attributes['total_amount'] = $request->final_total;

        $stock_adjustment = StockAdjustment::find($id);
        $stock_adjustment->update($attributes);

//        $purchasedetails = StockAdjustmentDetail::where('adjustment_id', $id)->get();


//        foreach ($purchasedetails as $pd) {
//
//            $stockmove =  \App\Models\StockMove::where('stock_id', $pd->product_id)->where('order_no', $id)->where('trans_type', $old_trans_type)->where('reference', $old_reason_name . '_' . $id)->delete();
//        }
        $product_id = $request->product_id;
        $price = $request->price;
        $quantity = $request->quantity;
        $tax_amount = $request->tax_amount;
        $tax_rate = $request->tax_rate;
        $total = $request->total;
        $units = $request->units;



        StockAdjustmentDetail::where('adjustment_id', $id)->delete();
        $total_qty=0;
        foreach ($quantity as $qty){
            $total_qty+=$qty;
        }

        $stockmaster_attr['stock_entry_id'] = 1;
        $stockmaster_attr['tran_date'] = $request->transaction_date;
        $stockmaster_attr['modules'] = "Stock Adjustments";
        $stockmaster_attr['comment'] =  " From Stock Adjustment";
        $stockmaster_attr['total_value'] = $stock_adjustment->total_amount;
        $stockmaster_attr['total_qty'] = $total_qty;
        $stockmaster_attr['store_id'] = $request->store_id;
        $stockmaster_attr['reason_id'] = $request->reason;
        $stockmaster_attr['module_id'] = $stock_adjustment->id;
        $stockmaster_attr['active'] = 1;

        $stockmaster=StockMaster::where('modules','Stock Adjustments')
        ->where('module_id',$id)->first();
//        dd($stock_adjustment);
        $stockmaster->update($stockmaster_attr);

        StockMove::where('master_id',$stockmaster->id)->delete();

        $request_reason = AdjustmentReason::find($request->reason);

        foreach ($product_id as $key => $value) {
            if ($value != '') {

                $detail = new StockAdjustmentDetail();
                $detail->adjustment_id = $stock_adjustment->id;
                $detail->product_id = $product_id[$key];
                $detail->price = $price[$key];
                $detail->qty = $quantity[$key];
                $detail->total = $total[$key];
                $detail->tax_amount = $tax_amount[$key];
                $detail->tax_rate = $tax_rate[$key];
                $detail->unit = $units[$key];
                $detail->save();

                if ($request_reason) {

                    $stockMove = new StockMove();
                    $stockMove->stock_id = $product_id[$key];
                    $stockMove->master_id = $stockmaster->id;
                    $stockMove->order_no = $stock_adjustment->id;
                    $stockMove->unit_id = $units[$key];
                    $stockMove->tran_date = $request->transaction_date;
                    $stockMove->user_id = \Auth::user()->id;


                    $stockMove->trans_type = $request_reason->trans_type;
                    $stockMove->reference = $request_reason->name . '_' . $stock_adjustment->id;
                    $stockMove->order_reference =  $stock_adjustment->id;

                    if ($request_reason->reason_type == 'positive') {

                        $stockMove->qty =  $quantity[$key]  * StockHelper::getUnitPrice($detail->unit);
                    } else {

                        $stockMove->qty = '-' . $quantity[$key]  * StockHelper::getUnitPrice($detail->unit);
                    }


                    $stockMove->transaction_reference_id = $stock_adjustment->id;
                    $stockMove->store_id = $request->store_id;

                    $stockMove->price = $price[$key];
                    $stockMove->save();
                }
            }
        }
        $product_id_new = $request->product_id_new;
        $price_new = $request->price_new;
        $quantity_new = $request->quantity_new;
        $tax_amount_new = $request->tax_amount_new;
        $tax_rate_new = $request->tax_rate_new;
        $total_new = $request->total_new;
        $units_new = $request->units_new;

        foreach ($product_id_new as $key => $value) {
            if ($value != '') {

                $detail = new StockAdjustmentDetail();
                $detail->adjustment_id = $id;
                $detail->product_id = $product_id_new[$key];
                $detail->price = $price_new[$key];
                $detail->qty = $quantity_new[$key];
                $detail->total = $total_new[$key];
                $detail->tax_rate = $tax_rate_new[$key];
                $detail->tax_amount = $tax_amount_new[$key];
                $detail->unit = $units_new[$key];
                $detail->save();


                if ($request_reason) {

                    $stockMove = new StockMove();
                    $stockMove->stock_id = $product_id_new[$key];
                    $stockMove->order_no = $id;
                    $stockMove->tran_date = \Carbon\Carbon::now();
                    $stockMove->user_id = \Auth::user()->id;

                    $stockMove->trans_type = $request_reason->trans_type;
                    $stockMove->reference = $request_reason->name . '_' . $id;
                    $stockMove->order_reference =  $id;

                    if ($request_reason->reason_type == 'positive') {
                        $stockMove->qty = $quantity_new[$key]  * StockHelper::getUnitPrice($detail->unit);
                    } else {
                        $stockMove->qty = '-' . $quantity_new[$key]  * StockHelper::getUnitPrice($detail->unit);
                    }


                    $stockMove->transaction_reference_id = $id;
                    $stockMove->store_id = $request->store_id;

                    $stockMove->price = $price[$key];
                    $stockMove->save();
                }
            }
        }
        if ($request_reason->reason_type == 'negative')
            $this->updateEntries($stock_adjustment->id);
        DB::commit();


        Flash::success('Stock Adjustment Updated Successfully');

        return redirect('/admin/product/stock_adjustment');
    }

    public function stock_adjustment_destroy($id)
    {

        DB::beginTransaction();
        $old_reason_name = StockAdjustment::find($id)->adjustmentreason->name;

        $old_trans_type = StockAdjustment::find($id)->adjustmentreason->trans_type;


        $adjdetails = StockAdjustmentDetail::where('adjustment_id', $id)->get();
        $stockmaster=StockMaster::where('modules','Stock Adjustments')->where('module_id',$id)->delete();


        foreach ($adjdetails as $pd) {

            $stockmove =  StockMove::where('stock_id', $pd->product_id)->where('order_no', $id)->where('trans_type', $old_trans_type)->where('reference', $old_reason_name . '_' . $id)->delete();
        }


        StockAdjustmentDetail::where('adjustment_id', $id)->delete();
        $stock_adjustment = StockAdjustment::find($id);

        if($stock_adjustment->entry_id!=null){
            Entryitem::where('entry_id',$stock_adjustment->entry_id)->delete();
            Entry::find($stock_adjustment->entry_id)->delete();
        }
        $stock_adjustment->delete();

        DB::commit();

        Flash::success('Stock Adjustment Destroyed');

        return redirect('/admin/product/stock_adjustment');
    }
    public function stock_adjustment_getModalDelete($id)
    {
        $error = null;



        $modal_title = 'Want to delete Stock Adjustment';

        $stock_adjustment = StockAdjustment::find($id);

        $stock_adjustment_details = StockAdjustmentDetail::where('adjustment_id', $stock_adjustment->id)->get();


        $modal_route = route('admin.products.stock_adjustment.delete', array('id' => $stock_adjustment->id));

        $modal_body = "Are you Sure to Delete This?";

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

//    private function updateEntries($orderId)
//    {
//
//
//        $purchaseorder = \App\Models\StockAdjustment::find($orderId);
//
//        //dd($purchaseorder);
//
//        $reason = \App\Models\AdjustmentReason::find($purchaseorder->reason);
//
//        if ($purchaseorder->entry_id && $purchaseorder->entry_id != '0') { //update the ledgers
//            $attributes['entrytype_id'] = '9'; //Adjustment
//            $attributes['tag_id'] = '2'; //Adjustment
//            $attributes['user_id'] = \Auth::user()->id;
//            $attributes['org_id'] = \Auth::user()->org_id;
//            $attributes['number'] =  $purchaseorder->id;
//            $attributes['date'] = \Carbon\Carbon::today();
//            $attributes['dr_total'] = $purchaseorder->total_amount;
//            $attributes['cr_total'] = $purchaseorder->total_amount;
//            $attributes['source'] = strtoupper($reason->name);
//            $entry = \App\Models\Entry::find($purchaseorder->entry_id);
//            $entry->update($attributes);
//
//            // Creddited to Customer or Interest or eq ledger
//
//            if ($reason->reason_type == 'negative') {
//
//                $sub_amount = \App\Models\Entryitem::where('entry_id', $purchaseorder->entry_id)->where('dc', 'C')->first();
//                $sub_amount->entry_id = $entry->id;
//                $sub_amount->user_id = \Auth::user()->id;
//                $sub_amount->org_id = \Auth::user()->org_id;
//                $sub_amount->dc = 'C';
//                $sub_amount->ledger_id = env('ADJUSTMENT_INVENTORY'); //Inventory ledger
//                $sub_amount->amount =  $purchaseorder->total_amount;
//                $sub_amount->narration = $reason->name; //$request->user_id
//                //dd($sub_amount);
//                $sub_amount->update();
//
//                // Debitte to Bank or cash account that we are already in
//                $cash_amount = \App\Models\Entryitem::where('entry_id', $purchaseorder->entry_id)->where('dc', 'D')->first();
//                $cash_amount->entry_id = $entry->id;
//                $cash_amount->user_id = \Auth::user()->id;
//                $cash_amount->org_id = \Auth::user()->org_id;
//                $cash_amount->dc = 'D';
//                $cash_amount->ledger_id = $purchaseorder->ledgers_id; // Purchase ledger if selected or ledgers from .env
//                // dd($cash_amount);
//                $cash_amount->amount   =  $purchaseorder->total_amount;
//                $cash_amount->narration = $reason->name;
//                $cash_amount->update();
//            } else {
//
//                $sub_amount = \App\Models\Entryitem::where('entry_id', $purchaseorder->entry_id)->where('dc', 'D')->first();
//                $sub_amount->entry_id = $entry->id;
//                $sub_amount->user_id = \Auth::user()->id;
//                $sub_amount->org_id = \Auth::user()->org_id;
//                $sub_amount->dc = 'D';
//                $sub_amount->ledger_id = env('ADJUSTMENT_INVENTORY'); //Inventory ledger
//                $sub_amount->amount =  $purchaseorder->total_amount;
//                $sub_amount->narration = $reason->name; //$request->user_id
//                //dd($sub_amount);
//                $sub_amount->update();
//
//                // Debitte to Bank or cash account that we are already in
//                $cash_amount = \App\Models\Entryitem::where('entry_id', $purchaseorder->entry_id)->where('dc', 'C')->first();
//                $cash_amount->entry_id = $entry->id;
//                $cash_amount->user_id = \Auth::user()->id;
//                $cash_amount->org_id = \Auth::user()->org_id;
//                $cash_amount->dc = 'C';
//                $cash_amount->ledger_id =  $purchaseorder->ledgers_id; // Purchase ledger if selected or ledgers from .env
//                // dd($cash_amount);
//                $cash_amount->amount   =  $purchaseorder->total_amount;
//                $cash_amount->narration = $reason->name;
//                $cash_amount->update();
//            }
//        } else {
//            //create the new entry items
//            $attributes['entrytype_id'] = '9'; //Adjustment
//            $attributes['tag_id'] = '2'; //Adjustment
//            $attributes['user_id'] = \Auth::user()->id;
//            $attributes['org_id'] = \Auth::user()->org_id;
//            $attributes['number'] =  $purchaseorder->id;
//            $attributes['date'] = \Carbon\Carbon::today();
//            $attributes['dr_total'] = $purchaseorder->total_amount;
//            $attributes['cr_total'] = $purchaseorder->total_amount;
//            $attributes['source'] =  strtoupper($reason->name);
//            $entry = \App\Models\Entry::create($attributes);
//
//
//            if ($reason->reason_type == 'negative') {
//                // Creddited to Customer or Interest or eq ledger
//                $sub_amount = new \App\Models\Entryitem();
//                $sub_amount->entry_id = $entry->id;
//                $sub_amount->user_id = \Auth::user()->id;
//                $sub_amount->org_id = \Auth::user()->org_id;
//                $sub_amount->dc = 'C';
//                $sub_amount->ledger_id = env('ADJUSTMENT_INVENTORY'); //Client ledger
//                $sub_amount->amount =  $purchaseorder->total_amount;
//                $sub_amount->narration = $reason->name; //$request->user_id
//                //dd($sub_amount);
//                $sub_amount->save();
//
//                // Debitte to Bank or cash account that we are already in
//
//                $cash_amount = new \App\Models\Entryitem();
//                $cash_amount->entry_id = $entry->id;
//                $cash_amount->user_id = \Auth::user()->id;
//                $cash_amount->org_id = \Auth::user()->org_id;
//                $cash_amount->dc = 'D';
//                $cash_amount->ledger_id =  $purchaseorder->ledgers_id; // Puchase ledger if selected or ledgers from .env
//                // dd($cash_amount);
//                $cash_amount->amount   =   $purchaseorder->total_amount;
//                $cash_amount->narration =  $reason->name;
//                $cash_amount->save();
//            } else {
//
//
//                $sub_amount = new \App\Models\Entryitem();
//                $sub_amount->entry_id = $entry->id;
//                $sub_amount->user_id = \Auth::user()->id;
//                $sub_amount->org_id = \Auth::user()->org_id;
//                $sub_amount->dc = 'D';
//                $sub_amount->ledger_id =  env('ADJUSTMENT_INVENTORY'); //Client ledger
//                $sub_amount->amount =  $purchaseorder->total_amount;
//                $sub_amount->narration = $reason->name; //$request->user_id
//                //dd($sub_amount);
//                $sub_amount->save();
//
//                // Debitte to Bank or cash account that we are already in
//
//                $cash_amount = new \App\Models\Entryitem();
//                $cash_amount->entry_id = $entry->id;
//                $cash_amount->user_id = \Auth::user()->id;
//                $cash_amount->org_id = \Auth::user()->org_id;
//                $cash_amount->dc = 'C';
//                $cash_amount->ledger_id = $purchaseorder->ledgers_id; // Puchase ledger if selected or ledgers from .env
//                // dd($cash_amount);
//                $cash_amount->amount   =   $purchaseorder->total_amount;
//                $cash_amount->narration =  $reason->name;
//                $cash_amount->save();
//            }
//
//            //now update entry_id in income row
//            $purchaseorder->update(['entry_id' => $entry->id]);
//        }
//        return 0;
//    }

    public function ajaxGetMenu(Request $request)
    {

        if ($request->outlet_id != '') {

            $menus = \App\Models\PosMenu::orderBy('id', 'desc')->where('outlet_id', $request->outlet_id)->get();
        } else {

            $menus = \App\Models\PosMenu::orderBy('id', 'desc')->get();
        }

        // dd($menus);

        $data = '<option value="">Select Menu...</option>';

        foreach ($menus as $key => $value) {

            $data .= '<option value="' . $value->id . '">' . $value->menu_name . '</option>';
        }

        return ['success' => 1, 'data' => $data];
    }


    public function transExcel($id,$type){
        $transations = DB::table('product_stock_moves')
        ->where('product_stock_moves.stock_id', $id)
        ->leftjoin('products', 'products.id', '=', 'product_stock_moves.stock_id')
        ->leftjoin('location', 'location.id', '=', 'product_stock_moves.store_id')
        ->select('product_stock_moves.*', 'products.name', 'location.location_name')
        ->get();

        $product = $this->course->find($id);
        $transData = [];

        $sum = 0;
        $StockIn = 0;
        $StockOut = 0;


        // $tdata [] = [
        //     'Product Name',$product->name
        // ];

        foreach ($transations as $key => $result) {
            if($result->trans_type == PURCHINVOICE){

                $purchtype = 'Purchase';

            }elseif($result->trans_type == SALESINVOICE){

                $purchtype = 'Sale';

            }elseif($result->trans_type == STOCKMOVEIN) {

               $purchtype = 'Transfer';

           }elseif ($result->trans_type == STOCKMOVEOUT) {

               $purchtype = 'Transfer';

           }
           elseif ($result->trans_type == OPENINGSTOCK) {

               $purchtype = 'Opening Stock';

           }

           if($result->qty > 0){
            $StockIn +=$result->qty;
        }else{
            $StockOut +=$result->qty;
        }

        $tdata [] = [

            'TransactionType'=>$purchtype,
            'Date'=>$result->tran_date,
            'Location'=>$result->location_name,
            'Quantity In'=>($result->qty > 0) ? $result->qty : '-',
            'Quantity Out'=>($result->qty < 0) ? str_ireplace('-','',$result->qty) : '-',
            'Quantity On Hand(closing)'=> $sum += $result->qty


        ];


    }


    $tdata [] = [
        'TransactionType'=>'',
        'Date'=>'',
        'Location'=>'Total',
        'Quantity In'=>$StockIn,
        'Quantity Out'=>str_ireplace('-','',$StockOut),
        'Quantity On Hand(closing)'=>$StockIn+$StockOut,
    ];

    $products = [
        'Product Name',$product->name

    ];

        // dd($tdata);

    return \Excel::download(new \App\Exports\ExcelExport($tdata,true,$products), "products_trans.{$type}");

}


public function product_statement(Request $request){

    $page_title = 'Product Statement';
    $page_description = 'Search product to find stock ledger statement';

    $products = Product::orderBy('ordernum')->where('enabled', '1')
    ->where('org_id', Auth::user()->org_id)->orderBy('name', 'ASC')
    ->pluck('name', 'id');

    $current_product = $request->product_id;
    $transations = StockMove::where(function($query) use ($current_product){
        return $query->where('stock_id',$current_product);
    })->where(function($query){

        $start_date = \Request::get('start_date');
        $end_date = \Request::get('end_date');

        if($start_date && $end_date){

            return $query->whereBetween('tran_date',[$start_date,$end_date]);


        }
    })

    ->orderBy('id');

    $isExcel = false;
    if($request->submit && $request->submit == 'excel' ){
        $transations = $transations->get();
        $view = view('admin.products.product-statement',compact('transations','isExcel'));
        return \Excel::download(new \App\Exports\ExcelExportFromView($view), 'product_statement.xlsx');

    }
    $transations = $transations->paginate(50);

    $isExcel = false;
    return view('admin.products.statement', compact('transations','page_description','page_title','products','current_product','transations','isExcel'));

}

public function multipledelete(Request $request){

   $ids = $request->chkCourse;
   Audit::log(Auth::user()->id, trans('admin/courses/general.audit-log.category'), trans('admin/courses/general.audit-log.msg-destroy', ['name' => 'Deleted multiple products' ]));
   try{

    $this->course->whereIn('id',$ids)->delete();
    ProductModel::whereIn('product_id', $ids)->delete();
    ProductSerialNumber::whereIn('product_id', $ids)->delete();
    Flash::success(trans('admin/courses/general.status.deleted'));


}catch(\Exception $e){

    Flash::error("The selected Products Are Related With Invoice");
}



return redirect('/admin/products');
}

public function stockLedger(Request $request){

    $page_title = 'Product Stock Ledger';
    $page_description = 'Search product to find stock ledger statement';

    $products = Product::orderBy('ordernum')->where('enabled', '1')
    ->where('org_id', Auth::user()->org_id)->orderBy('name', 'ASC')
    ->pluck('name', 'id');

    $current_product = $request->product_id;



    $transations = StockMove::where(function($query) use ($current_product){

        return $query->where('stock_id',$current_product);
    })->where(function($query){

        $start_date = \Request::get('start_date');
        $end_date = \Request::get('end_date');

        if($start_date && $end_date){

            return $query->whereDate('tran_date','>=',$start_date)->whereDate('tran_date','<=',$end_date);


        }



    })

    ->orderBy('id');
    $purchasePrice= PurchaseOrderDetail::where('product_id', $request->product_id)->select('unitpricewithimport','unit_price', 'quantity_recieved','quantity_ordered', 'discount')->get()->toArray();
    $isExcel = false;
    if($request->submit && $request->submit == 'excel' ){
        $transations = $transations->get();
        $view = view('admin.products.product-statement',compact('transations','isExcel'));
        ob_end_clean();
        return \Excel::download(new \App\Exports\ExcelExportFromView($view), 'product_statement.xlsx');

    }
    $transations = $transations->paginate(50);

        // ->paginate(50);

    $isExcel = false;



        // dd('');

    return view('admin.products.stock-ledger', compact('transations','page_description','page_title','products','purchasePrice','current_product','transations','isExcel'));

}
// public function stockLedger(Request $request){

//     $page_title = 'Product Stock Ledger';
//     $page_description = 'Search product to find stock ledger statement';

//     $products = \App\Models\Product::orderBy('ordernum')->where('product_division','raw_material')->where('enabled', '1')
//     ->where('org_id', Auth::user()->org_id)->orderBy('name', 'ASC')
//     ->pluck('name', 'id');

//     $current_product = $request->product_id;
//     $transations = \App\Models\StockMove::where(function($query) {
   
//         $start_date = \Request::get('start_date');
//         $end_date = \Request::get('end_date');

//         if($start_date && $end_date){
//             return $query->whereDate('tran_date','>=',$start_date)->whereDate('tran_date','<=',$end_date);
//         }
//     })
//     ->orwhere('stock_id',$current_product)
//     ->orderby('id');
    
//     $purchasePrice= \App\Models\PurchaseOrderDetail::where('product_id', $request->product_id)->select('unit_price', 'quantity_recieved','quantity_ordered', 'discount')->get()->toArray();
//     $isExcel = false;
//     if($request->submit && $request->submit == 'excel' ){
        
//         $transations = $transations->get();
//         $view = view('admin.products.product-statement',compact('transations','isExcel'));
//         return \Excel::download(new \App\Exports\ExcelExportFromView($view), 'product_statement.xlsx');
//     }
//     $transations = $transations->paginate(50);
//     // ->paginate(50);
//     $isExcel = false;
//     return view('admin.products.stock-ledger', compact('transations','purchasePrice','page_description','page_title','products','current_product','transations','isExcel'));

// }

public function stocksOverview()
    {
        $page_title = 'Store Overview';
        $page_description = 'Export Store Overview Report';

        $current_fiscal = Fiscalyear::where('current_year', 1)->first();

        $fiscal_year = request()->fiscal_year ? request()->fiscal_year : $current_fiscal->fiscal_year;

        $op = \Request::get('op');
        $outlets = PosOutlets::where('enabled', '1')->pluck('name', 'id')->all();
        $allFiscalYear = Fiscalyear::pluck('fiscal_year', 'fiscal_year')->all();

        if (\Request::get('start_date_nep') != '' && \Request::get('end_date_nep') != '') {
            $start_date = \Request::get('start_date_nep');
            $end_date = \Request::get('end_date_nep');
            $cal = new \App\Helpers\NepaliCalendar();
            $startdate = explode('-', $start_date);
            $date = $cal->nep_to_eng($startdate[0], $startdate[1], $startdate[2]);
            $startdate = $date['year'] . '-' . $date['month'] . '-' . $date['date'];
            $enddate = explode('-', $end_date);
            $date = $cal->nep_to_eng($enddate[0], $enddate[1], $enddate[2]);
            $enddate = $date['year'] . '-' . $date['month'] . '-' . $date['date'];

        } elseif(\Request::get('start_date') != '' && \Request::get('end_date') != '') {
            $startdate = \Request::get('start_date');
            $enddate = \Request::get('end_date');
        }else{
            $fyc=Fiscalyear::where('fiscal_year',$fiscal_year)->first();
            $startdate = $fyc->start_date;
            $enddate =$fyc->end_date;
        }

        $prefix = '';
        if ($fiscal_year != $current_fiscal->fiscal_year) {
            $prefix = Fiscalyear::where('fiscal_year', $fiscal_year)->first()->numeric_fiscal_year . '_';
        }
        $categories= ProductCategory::where('org_id',1)->pluck('name','id')->all();
        // dd($categories);

        $productCategorylists= request()->category_id ? [request()->category_id] :ProductCategory::where('org_id',1)->pluck('id');
        $filter_category_name= request()->category_id ?ProductCategory::find( request()->category_id)->name : null;
        $productlistwithgrn= Product::select('products.id','products.name','products.category_id')
        ->whereIn('category_id',$productCategorylists)
        // ->rightJoin('grn_details','grn_details.product_id','=','products.id')
        //
        ->get();
        $current_store=\Request::get('outlet_id')?\Request::get('outlet_id'):3;
        $outlet_name=PosOutlets::find($current_store)->name;
//anamol
        $dataarray=$productlistwithgrn->groupby('category_id');
        foreach($dataarray as $category_id=>$products){
            $category_name=ProductCategory::find($category_id)->name;
            foreach($products as $product){
                $opening_detail=StockAdjustmentDetail::select(DB::raw("SUM(qty) as quantity,SUM(total) as total, AVG(price) as rate"))
                ->where('product_id',$product->id)
                ->whereIn('adjustment_id',StockAdjustment::where('reason',5)->where('store_id',$current_store)
                    ->when($startdate && $enddate, function ($q) use($startdate,$enddate) {
                        return $q->wherebetween('transaction_date',[$startdate,$enddate]);
                    })->pluck('id'))//opening stock only
               ->first();
                $records[$category_name][$product->name]['opening']=$opening_detail;
                $purchase_receipt=PurchaseOrderDetail::
                select(DB::raw("SUM(CASE WHEN units = 17 THEN quantity_recieved * 12
                                WHEN units = 19 THEN quantity_recieved * 24 
                                ELSE quantity_recieved
                            END) as quantity, SUM(total) as total,AVG(unit_price) as rate"))
                ->where('product_id',$product->id)
                ->whereIn('order_no',PurchaseOrder::where('into_stock_location',$current_store)
                    ->when($startdate && $enddate, function ($q) use($startdate,$enddate) {
                        return $q->wherebetween('bill_date',[$startdate,$enddate]);
                    })->pluck('id'))//opening stock only
               ->first();
                $records[$category_name][$product->name]['receipt']=$purchase_receipt;

                $receipt_return=SupplierReturnDetail::select(DB::raw("SUM(CASE WHEN units = 17 THEN return_quantity * 12
                WHEN units = 19 THEN return_quantity * 24 
                ELSE return_quantity
            END) as quantity, SUM(return_total) as total,AVG(return_price) as rate"))
                ->where('product_id',$product->id)
                ->whereIn('supplier_return_id',SupplierReturn::where('into_stock_location',$current_store)
                ->when($startdate && $enddate, function ($q) use($startdate,$enddate) {
                    return $q->wherebetween('return_date',[$startdate,$enddate]);
                })->pluck('id'))//opening stock only
           ->first();
                $records[$category_name][$product->name]['receipt_return']=$receipt_return;
               
                $issue=InvoiceDetail::select(DB::raw("SUM(CASE WHEN unit = 17 THEN quantity * 12
                WHEN unit = 19 THEN quantity * 24 
                ELSE quantity
            END) as quantity,SUM(total) as total,unit, AVG(price) as rate"))
                ->where('product_id',$product->id)
                ->whereIn('invoice_id',Invoice::where('outlet_id',$current_store)
                    ->when($startdate && $enddate, function ($q) use($startdate,$enddate) {
                        return $q->wherebetween('bill_date',[$startdate,$enddate]);
                    })->pluck('id'))
               ->first();
             
                $records[$category_name][$product->name]['issue']=$issue;

                $invoice_ids = Invoice::select( 'invoice.*')
                ->leftjoin('invoice_meta', 'invoice.id', '=', 'invoice_meta.invoice_id')
                ->when($startdate && $enddate, function ($q) use($startdate,$enddate) {
                    return $q->wherebetween('invoice.bill_date',[$startdate,$enddate]);
                })
                ->where('outlet_id',$current_store)
                ->where('invoice.org_id', \Auth::user()->org_id)
                ->where('invoice_meta.is_bill_active', 0)
                ->pluck('invoice.id');

                $issue_return=InvoiceDetail::select(DB::raw("SUM(CASE WHEN unit = 17 THEN quantity * 12
                WHEN unit = 19 THEN quantity * 24 
                ELSE quantity
            END) as quantity,SUM(total) as total,unit, AVG(price) as rate"))
                ->where('product_id',$product->id)
                ->whereIn('invoice_id',$invoice_ids)
               ->first();
                $records[$category_name][$product->name]['issue_return']=$issue_return;

                $adjustment=StockAdjustmentDetail::select(DB::raw("SUM(CASE WHEN unit = 17 THEN qty * 12
                WHEN unit = 19 THEN qty * 24 
                ELSE qty
            END) as quantity,SUM(total) as total, AVG(price) as rate"))
                ->where('product_id',$product->id)
                ->whereIn('adjustment_id',StockAdjustment::where('reason','!=',5)->where('store_id',$current_store)
                    ->when($startdate && $enddate, function ($q) use($startdate,$enddate) {
                        return $q->wherebetween('transaction_date',[$startdate,$enddate]);
                    })->pluck('id'))//opening stock only
               ->first();
                $records[$category_name][$product->name]['adjustment']=$adjustment;
            }
        }
        // dd($records);
        if ($op == 'excel') {

            $date = date('Y-m-d');
            $title = 'Store Overview Report';
            return \Excel::download(new \App\Exports\StoreOverviewExcelExport($title,$records,$fiscal_year,$startdate,$enddate,$outlet_name,$filter_category_name), "store_overview_{$date}.xls");

        }
        $title = 'Store Overview Report';
        return view('admin.products.storeoverview', compact('outlet_name','page_description', 'page_title', 'outlets', 'allFiscalYear', 'fiscal_year','records','title','startdate','enddate','categories','filter_category_name'));
    }

}
