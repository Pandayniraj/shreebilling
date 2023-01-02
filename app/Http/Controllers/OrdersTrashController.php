<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Lead;
use App\Models\MasterComments;
use App\Models\Order as Order;
use App\Models\OrderDetail;
use App\Models\OrderPaymentTerms;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Role as Permission;
use App\Models\StockMove;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Models\Invoice;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class OrdersTrashController extends Controller
{
    /**
     * @var Client
     */

    public function index()
    {
// dd(\Request::get('order_type'));
        $orders = \App\Models\OrdersTrash::orderBy('id', 'desc')
            ->where('org_id', \Auth::user()->org_id)
            ->where(function ($query) {
                if (\Request::get('order_type') && \Request::get('order_type') == 'quotation') {
                    return $query->where('order_type', 'quotation');
                }
            })
            ->where(function ($query) {
                if (\Request::get('order_type') && \Request::get('order_type') == 'invoice') {
                    return $query->where('order_type', 'proforma_invoice');
                }
            })
            ->where(function ($query) {
                if (\Request::get('order_type') && \Request::get('order_type') == 'order') {
                    return $query->where('order_type', 'order');
                }
            })
            ->where(function ($query) {
                if (\Request::get('fiscal_id') && \Request::get('fiscal_id') != '') {

                    return $query->where('fiscal_year_id',\Request::get('fiscal_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('status') && \Request::get('status') != '') {
                    return $query->where('status', \Request::get('status'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('customer_id') && \Request::get('customer_id') != '') {
                    return $query->where('source','lead')->where('client_id', \Request::get('customer_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('client_id') && \Request::get('client_id') != '') {
                    return $query->where('source','client')->where('client_id', \Request::get('client_id'));
                }
            })
            ->where(function ($query) {
                if (\Request::get('location_id') && \Request::get('location_id') != '') {
                    return $query->where('from_stock_location', \Request::get('location_id'));
                }
            })
            ->where(function($query){
                if(!Auth::user()->hasRole('admins')){
                    return $query->where('user_id',Auth::user()->id);
                }
            })->where(function($query){

                $pay_status = \Request::get('pay_status');
                if($pay_status == 'Pending'){

                    return $query->where('payment_status','Pending')
                            ->orWhereNull('payment_status')
                            ->orWhere('payment_status','');

                }elseif($pay_status == 'Partial'){

                   return $query->where('payment_status','Partial');

                }elseif($pay_status == 'Paid'){

                  return  $query->where('payment_status','Paid');
                }

            })
            ->where(function($query){
                $payments = \Request::get('payment');
                if($payments == 'pending_partial'){
                    return $query->whereIn('payment_status',['Pending','Partial'])
                            ->orWhereNull('payment_status')
                            ->orWhere('payment_status','');
                }
            })
            ->paginate(25);

        //dd($orders);

        $locations = \App\Models\ProductLocation::where('enabled', '1')
            ->where('org_id', \Auth::user()->org_id)
            ->pluck('location_name', 'id')->all();

        $leads = Lead::pluck('name', 'id')->all();
        $clients = Client::pluck('name', 'id')->all();

        $fiscalyears = \App\Models\Fiscalyear::orderBy('fiscal_year', 'desc')->pluck('fiscal_year', 'id')->all();

        // dd($fiscalyears);

        $page_title = 'Orders Trash';
        $page_description = 'Manage Orders Trash';

        return view('admin.orderstrash.index', compact('orders', 'page_title', 'page_description', 'locations', 'leads', 'fiscalyears','clients'));
    }




}
