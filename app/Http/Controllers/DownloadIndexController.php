<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Product;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Input;

/**
FOR ONLINE ENQUIRY

 **/
class DownloadIndexController extends Controller
{
    public function orderpdf()
    {
        $orders = \App\Models\Orders::orderBy('id', 'desc')
                     ->where(function ($query) {
                         if (\Request::get('type') && \Request::get('type') == 'quotation') {
                             return $query->where('order_type', 'quotation');
                         }
                     })
                        ->where(function ($query) {
                            if (\Request::get('type') && \Request::get('type') == 'invoice') {
                                return $query->where('order_type', 'proforma_invoice');
                            }
                        })
                        ->where(function ($query) {
                            if (\Request::get('type') && \Request::get('type') == 'order') {
                                return $query->where('order_type', 'order');
                            }
                        })
                        ->where('org_id', Auth::user()->org_id)
                        ->get();

        $imagepath = Auth::user()->organization->logo;
        $report_title = 'Orders List';
        $pdf = \PDF::loadView('admin.downloadindex.orderspdf', compact('orders', 'imagepath','report_title'));
        $file = 'orderslist-'.time().'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function orderexcel()
    {
        $data = \App\Models\Orders::select('fin_orders.id', 'fin_orders.bill_date', 'source', 'clients.name as clientsname', 'leads.name as leadsname', 'order_type', 'fin_orders.name as ordername', 'fin_orders.total_amount')
            ->leftjoin('clients', 'clients.id', '=', 'fin_orders.client_id')
            ->leftjoin('leads', 'leads.id', '=', 'fin_orders.client_id')
            ->where('fin_orders.org_id', Auth::user()->org_id)
             ->where(function ($query) {
                 if (\Request::get('type') && \Request::get('type') == 'quotation') {
                     return $query->where('order_type', 'quotation');
                 }
             })
                        ->where(function ($query) {
                            if (\Request::get('type') && \Request::get('type') == 'invoice') {
                                return $query->where('order_type', 'proforma_invoice');
                            }
                        })
                        ->where(function ($query) {
                            if (\Request::get('type') && \Request::get('type') == 'order') {
                                return $query->where('order_type', 'order');
                            }
                        })
            ->get()
            ->toArray();

        return \Excel::download(new \App\Exports\ExcelExport($data), 'orderslist.csv');
    }

       public function purchasepdf()
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


        if (\Request::get('type') && \Request::get('type') == 'purchase_orders') {
            $orders = PurchaseOrder::where('purchase_type', 'purchase_orders')->where('org_id', Auth::user()->org_id)->orderBy('id', 'DESC')
              ->where(function($query) use ($filterdate){

                    return $filterdate($query);

                })->where(function($query) use ($filterSupplier){

                    return $filterSupplier($query);

                })->get();
        } elseif (\Request::get('type') && \Request::get('type') == 'request') {
            $orders = PurchaseOrder::where('purchase_type', 'request')->where('org_id', Auth::user()->org_id)->orderBy('id', 'DESC')
              ->where(function($query) use ($filterdate){

                    return $filterdate($query);

                })->where(function($query) use ($filterSupplier){

                    return $filterSupplier($query);

                })->get();
        } elseif (\Request::get('type') && \Request::get('type') == 'bills') {
            $orders = PurchaseOrder::where('purchase_type', 'bills')->where('org_id', Auth::user()->org_id)->orderBy('id', 'DESC')
              ->where(function($query) use ($filterdate){

                    return $filterdate($query);

                })->where(function($query) use ($filterSupplier){

                    return $filterSupplier($query);

                })->get();
        } else {
            $orders = PurchaseOrder::orderBy('id', 'desc')->where('org_id', Auth::user()->org_id)  ->where(function($query) use ($filterdate){

                    return $filterdate($query);

                })->where(function($query) use ($filterSupplier){

                    return $filterSupplier($query);

                })->get();
        }

        $imagepath = Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.downloadindex.purchasepdf', compact('orders', 'imagepath'));
        $file = 'purchaselist-'.time().'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function purchaseexcel()
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
        $data = \App\Models\PurchaseOrder::select('purch_orders.id', 'purch_orders.ord_date', 'purch_orders.status', 'clients.name as suppliername', 'purchase_type', 'purch_orders.total')
            ->leftjoin('clients', 'clients.id', '=', 'purch_orders.supplier_id')
            ->where('purch_orders.org_id', Auth::user()->org_id)
            ->where(function ($query) {
                if (\Request::get('type') && \Request::get('type') == 'purchase_orders') {
                    return $query->where('purchase_type', 'purchase_orders');
                }
            })
                        ->where(function ($query) {
                            if (\Request::get('type') && \Request::get('type') == 'request') {
                                return $query->where('purchase_type', 'request');
                            }
                        })
                        ->where(function ($query) {
                            if (\Request::get('type') && \Request::get('type') == 'bills') {
                                return $query->where('purchase_type', 'bills');
                            }
                        })->where(function($query) use ($filterdate){

                    return $filterdate($query);

                })->where(function($query) use ($filterSupplier){

                    return $filterSupplier($query);

                })
            ->get()
            ->toArray();

         return \Excel::download(new \App\Exports\ExcelExport($data), 'purchaselist.csv');
    }

    public function downloadExpense($type){
        $expenses =  \App\Models\Expense::select('expenses.id','users.username','projects.name as project_name','expenses.date','expenses.expenses_account','expenses.amount','coa_ledgers.name as paid_through','clients.name as vendor','expenses.reference','expenses.expense_type','fiscalyears.fiscal_year')->leftjoin('users','users.id','=','expenses.user_id')
            ->leftjoin('clients','clients.id','=','expenses.vendor_id')
            ->leftjoin('projects','projects.id','=','expenses.project_id')
            ->leftjoin('fiscalyears','fiscalyears.id','=','expenses.fiscal_year_id')
            ->leftjoin('coa_ledgers','coa_ledgers.id','=','expenses.paid_through')
            ->get();
        if($type == 'pdf'){
            $report_title = 'Expense reports';
            $pdf = \PDF::loadView('admin.downloadindex.expensepdf', compact('expenses', 'imagepath','report_title'));
            $file = 'expenseslist-'.time().'.pdf';

            if (File::exists('reports/'.$file)) {
                File::Delete('reports/'.$file);
            }

            return $pdf->download($file);
        }else{
            $data = $expenses->toArray();
            return \Excel::download(new \App\Exports\ExcelExport($data), 'expenses'.date('Y-m-d').'.xls');

        }
       
    }

    public function productspdf()
    {
        $products = Product::orderBy('id', 'desc')
                                ->where('org_id', Auth::user()->org_id)->get();
        

        $imagepath = Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.downloadindex.productspdf', compact('products', 'imagepath'));
        $file = 'productlist-'.time().'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }
}
