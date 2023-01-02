<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Audit as Audit;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\MasterComments;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\Orders as Order;
use App\Models\Product;
use App\Models\PurchaseOrderTrash;
use App\Models\PurchaseOrderDetail;
use App\Models\Role as Permission;
use App\Models\StockMove;
use App\User;
use Auth;
use DB;
use Flash;
use Illuminate\Http\Request;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class PurchaseTrashController extends Controller
{

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $bug
     * @param Permission $permission
     * @param User $user
     */


    /**
     * @return \Illuminate\View\View
     */
   public function index()
    {

        $filterdate = function ($query)  {

            $start_date = \Request::get('start_date');

            $end_date = \Request::get('end_date');

            if($start_date && $end_date){
                return $query->where('bill_date','>=',$start_date)
                        ->where('bill_date','<=',$end_date);
            }

        };


        $filterSupplier = function($query){

            $supplier = \Request::get('supplier_id');
            if($supplier){

                return $query->where('supplier_id',$supplier);
            }

        };

        $filterCurrency = function($query){

            $currency = \Request::get('currency');

            if($currency){

                return $query->where('currency',$currency);
            }

        };


        if (\Request::get('type') && \Request::get('type') == 'purchase_orders') {
            $orders = PurchaseOrderTrash::where('purchase_type', 'purchase_orders')
                ->where('org_id', \Auth::user()->org_id)
                ->where(function($query) use ($filterdate){

                    return $filterdate($query);

                })
                ->where(function($query) use ($filterSupplier){

                    return $filterSupplier($query);

                })->where(function($query) use ($filterCurrency){

                    return $filterCurrency($query);

                })
                ->orderBy('id', 'DESC');
        } elseif (\Request::get('type') && \Request::get('type') == 'request') {
            $orders = PurchaseOrderTrash::where('purchase_type', 'request')
                ->where('org_id', \Auth::user()->org_id)
                ->where(function($query) use ($filterdate){
                    return $filterdate($query);
                })
                  ->where(function($query) use ($filterSupplier){

                    return $filterSupplier($query);

                })->where(function($query) use ($filterCurrency){

                    return $filterCurrency($query);

                })
                ->orderBy('id', 'DESC');
        } elseif (\Request::get('type') && \Request::get('type') == 'bills') {
            $orders = PurchaseOrderTrash::where('purchase_type', 'bills')
                ->where('org_id', \Auth::user()->org_id)
                ->where(function($query) use ($filterdate){
                    return $filterdate($query);
                })
                  ->where(function($query) use ($filterSupplier){

                    return $filterSupplier($query);

                })->where(function($query) use ($filterCurrency){

                    return $filterCurrency($query);

                })
                ->orderBy('id', 'DESC');
        } else {
            $orders = PurchaseOrderTrash::orderBy('id', 'desc')
                ->where('org_id', \Auth::user()->org_id)
                ->where(function($query) use ($filterdate){
                    return $filterdate($query);
                })
                  ->where(function($query) use ($filterSupplier){

                    return $filterSupplier($query);

                })->where(function($query) use ($filterCurrency){

                    return $filterCurrency($query);

                });
        }

        if(\Request::get('search') && \Request::get('search') == 'true'){

            $orders =  $orders->get();


        }else{

            $orders =  $orders->paginate(40);
        }

        $page_title = ' Purchase Orders Trash';
        $page_description = 'Manage Purchase Orders Trash';
        $suppliers = Client::where('relation_type','supplier')->pluck('name','id')->all();
        $currency = \App\Models\Country::whereEnabled('1')->pluck('currency_name','currency_code as id')->all();


        return view('admin.purchasetrash.index', compact('orders','currency', 'page_title', 'page_description','suppliers'));
    }



}
