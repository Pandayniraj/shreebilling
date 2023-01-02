<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Client;
use App\Models\InvoiceTrash;
use App\Models\InvoiceDetail;
use App\Models\MasterComments;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Role as Permission;
use App\Models\StockMove;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use PhpParser\Node\Expr\FuncCall;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class InvoiceTrashController extends Controller
{
    /**
     * @var Client
     */
    private $invoice;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $bug
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Permission $permission, InvoiceTrash $invoicetrash)
    {
        parent::__construct();
        $this->permission = $permission;
        $this->invoicetrash = $invoicetrash;
    }





    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $orders = InvoiceTrash::where(function($query){
                    $start_date = \Request::get('start_date');
                    $end_date = \Request::get('end_date');
                    if($start_date && $end_date){
                        return $query->where('bill_date','>=',$start_date)
                            ->where('bill_date','<=',$end_date);
                    }

                })
                ->where(function($query){
                    $bill_no = \Request::get('bill_no');
                    if($bill_no){
                        return $query->where('bill_no',$bill_no);
                    }

                })
                ->where(function($query){
                    $client_id = \Request::get('client_id');
                    if($client_id){
                        return $query->where('client_id',$client_id);
                    }
                })

                ->where('org_id', \Auth::user()->org_id)
                ->orderBy('id', 'desc')
                ->paginate(30);

                // dd($orders);
        $page_title = 'Invoice Trash';
        $page_description = 'Manage Invoice Trash';
        $clients = \App\Models\Client::select('id', 'name')->where('org_id', \Auth::user()->org_id)->orderBy('id', 'DESC')->pluck('name','id')->all();
        $fiscal_years = \App\Models\Fiscalyear::pluck('fiscal_year as name', 'fiscal_year as id')->all();

        return view('admin.invoicetrash.index', compact('orders', 'page_title', 'page_description','clients','fiscal_years'));
    }



}
