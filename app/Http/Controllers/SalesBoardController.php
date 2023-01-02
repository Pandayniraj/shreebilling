<?php

namespace App\Http\Controllers;

use App\Models\Role as Permission;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class SalesBoardController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;
    private $org_id;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
        $this->middleware(function ($request, $next) {
            $this->org_id = \Auth::user()->org_id;

            return $next($request);
        });
    }

    // private function getQuotaion($start_date,$end_date){
    //     $quotation =
    //     return $quotation;
    // }

    // Stock Category
    public function index()
    {
        $page_title = 'Sales Dashboard';
        $page_description = 'List of all Figures';
        $all_fiscal_year = \App\Models\Fiscalyear::where('org_id', $this->org_id)->orderBy('numeric_fiscal_year', 'desc')->pluck('fiscal_year as name', 'id')->all();
        //dd(\Request::get('fiscal_year'));
        if (\Request::get('fiscal_year') && \Request::get('fiscal_year') != '') {
            $fiscal = \App\Models\Fiscalyear::find(\Request::get('fiscal_year'));
        } else {
            $fiscal = \FinanceHelper::cur_fisc_yr();
        }

        if(!$fiscal){

            Flash::error('Fiscalyear not set');
            return redirect()->back();


        }
        $start = (new \DateTime($fiscal->start_date))->modify('first day of this month');
        $end = (new \DateTime($fiscal->end_date))->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period = new \DatePeriod($start, $interval, $end);

        $quotation_data = [];

        $invoices_data = [];

        $tax_invoices_data = [];

        $purchase_bill_data = [];

        $order_income_data = [];

        $invoice_income_data = [];

        $expense_data = [];

        $line_chart_purchasetax_salestax = [
            'purch'=>['name'=>'Purchase Tax', 'data'=>[]],
            'sales'=>['name'=>'Sales tax', 'data'=>[]],
        ];
        $line_chart_purchasetax_salestax_categories = [];
        $data_count = ['quotation'=>0, 'invoices'=>0, 'tax_invoices'=>0, 'purchase_bill'=>0];

        foreach ($period as $key=>$dt) {
            $line_chart_purchasetax_salestax_categories[] = $dt->format('M');
            $month_start = $dt->format('Y-m-d');
            $month_end = $dt->format('Y-m-t');
            $month = $dt->format('M Y');
            // echo $month_start .' '.$month_end.'<br>';
            if ($key == 0) {

                $month_start = $fiscal->start_date; //first month start as it is
            }
            if ($key == 12) {
                $month_end = $fiscal->end_date; //end month end as it is
            }

            $quotation = \App\Models\Orders::where('bill_date', '>=', $month_start)->where('bill_date', '<=', $month_end)
                        ->where('order_type', 'quotation')
                        ->where('org_id', $this->org_id)
                        ->where('fiscal_year_id', $fiscal->id)
                        ->sum('subtotal');
       
            $data_count['quotation'] += $quotation;
            array_push($quotation_data, ['name'=>$month, 'y'=>(int) $quotation ?? 0]);

            //tax_invoices
            $tax_invoices = \DB::select("SELECT SUM(invoice.total_amount) as subtotal , SUM(invoice.tax_amount) as tax_amount FROM invoice 
                LEFT JOIN invoice_meta ON invoice_meta.invoice_id = invoice.id
                where invoice.bill_date >= '$month_start' AND invoice.bill_date <= '$month_end' AND fiscal_year_id = '$fiscal->id' AND invoice_meta.is_bill_active = '1' 
                AND invoice.org_id = '$this->org_id' ")[0];
      
            $data_count['tax_invoices'] += $tax_invoices->subtotal;

            array_push($tax_invoices_data, ['name'=>$month, 'y'=>(int) $tax_invoices->subtotal ?? 0]);

            //invoices
            $invoices = \DB::select("SELECT SUM(subtotal) as subtotal , SUM(tax_amount) as tax_amount From fin_orders WHERE order_type = 
                'proforma_invoice' AND  bill_date >= '$month_start' AND bill_date <= '$month_end' AND fiscal_year_id = '$fiscal->id' AND org_id = '$this->org_id'")[0];

            $invoice_taxes_amount = $invoices->tax_amount + $tax_invoices->tax_amount;
            $invoices_total = $invoices->subtotal + $tax_invoices->subtotal;
            $data_count['invoices'] += $invoices_total;
            array_push($invoices_data, ['name'=>$month, 'y'=>(int) $invoices_total ?? 0]);

            array_push($line_chart_purchasetax_salestax['sales']['data'], (float) $invoice_taxes_amount ?? 0);

            //purchase_bill
            $purchase_bill = \DB::select("SELECT SUM(subtotal) as subtotal, SUM(tax_amount) as tax_amount From purch_orders 
                WHERE ord_date >= '$month_start' AND ord_date <= '$month_end' AND purchase_type = 'bills' AND fiscal_year_id = '$fiscal->id' AND org_id = '$this->org_id'")[0];

            $data_count['purchase_bill'] += $purchase_bill->subtotal;
            array_push($purchase_bill_data, ['name'=>$month, 'y'=>(int) $purchase_bill->subtotal ?? 0]);
            array_push($line_chart_purchasetax_salestax['purch']['data'], (float) $purchase_bill->tax_amount ?? 0);

            //income => invoice and order
            $order_income = \App\Models\Orders::where('bill_date', '>=', $month_start)->where('bill_date', '<=', $month_end)
                            ->where('is_renewal', '1')
                            ->where('fiscal_year_id', $fiscal->id)
                             ->where('org_id', $this->org_id)
                            ->sum('subtotal');
            array_push($order_income_data, ['name'=>$month, 'y'=>(int) $order_income ?? 0]);

            $tax_invoices_income = \App\Models\Invoice::select('invoice.*')->where('bill_date', '>=', $month_start)->where('bill_date', '<=', $month_end)
                                    ->where('is_renewal', '1')

                                    ->where('invoice_meta.is_bill_active', '1')
                                    ->where('org_id', $this->org_id)
                                    ->where('fiscal_year_id', $fiscal->id)
                                    ->leftjoin('invoice_meta', 'invoice.id', '=', 'invoice_meta.invoice_id')
                                    ->groupBy('invoice.id')
                                    ->sum('subtotal');

            $invoices_income = \App\Models\Orders::where('bill_date', '>=', $month_start)->where('bill_date', '<=', $month_end)
                                ->where('is_renewal', '1')
                                ->where('order_type', 'proforma_invoice')
                                 ->where('org_id', $this->org_id)
                                ->where('fiscal_year_id', $fiscal->id)
                                ->sum('subtotal');

            $invoices_income = $invoices_income + $tax_invoices_income;

            array_push($invoice_income_data, ['name'=>$month, 'y'=>(int) $invoices_income ?? 0]);

            //expense income

            $expense_income = \App\Models\PurchaseOrder::where('ord_date', '>=', $month_start)->where('ord_date', '<=', $month_end)
                        ->where('is_renewal', '1')
                        ->where('fiscal_year_id', $fiscal->id)
                         ->where('org_id', $this->org_id)
                        ->sum('subtotal');

            array_push($expense_data, ['name'=>$month, 'y'=>(int) $expense_income ?? 0]);
        }
       // dd($invoices_data);
        $line_chart_purchasetax_salestax = array_values($line_chart_purchasetax_salestax);

        $product_sales = \DB::select("SELECT fin_order_detail.product_id, products.name, SUM(fin_order_detail.total) as total FROM fin_order_detail  LEFT JOIN products ON products.id = fin_order_detail.product_id LEFT JOIN fin_orders ON fin_orders.id=fin_order_detail.order_id  WHERE fin_order_detail.product_id != '0' AND products.org_id = '$this->org_id' AND fin_orders.fiscal_year_id = '$fiscal->id'GROUP BY fin_order_detail.product_id");

        $product_sales_invoice = \DB::select("SELECT invoice_detail.product_id, products.name, SUM(invoice_detail.total) as total FROM invoice_detail  LEFT JOIN products ON products.id = invoice_detail.product_id 
        LEFT JOIN  invoice ON invoice.id = invoice_detail.invoice_id  
        LEFT JOIN invoice_meta ON invoice_meta.invoice_id = invoice.id
        WHERE invoice_detail.product_id != '0' AND  invoice_meta.is_bill_active = '1' 
        AND invoice.fiscal_year_id = '$fiscal->id'
        AND products.org_id = '$this->org_id'  GROUP BY invoice_detail.product_id");

        $product_sales = array_merge($product_sales, $product_sales_invoice);
        $product_sales_data = [];
        foreach ($product_sales as $key => $value) {
            if (isset($product_sales_data[$value->product_id])) {
                $product_sales_data[$value->product_id]['y'] += $value->total;
            } elseif ($value->name) {
                $product_sales_data[$value->product_id] = ['name'=>$value->name, 'y'=>(int) $value->total];
            }
        }
        $product_sales_data = array_values($product_sales_data);
        $purchase_product = \DB::select("SELECT purch_order_details.product_id, products.name, SUM(purch_order_details.total) as total FROM purch_order_details  LEFT JOIN products ON products.id = purch_order_details.product_id 
            LEFT JOIN purch_orders ON purch_orders.id = purch_order_details.order_no WHERE purch_order_details.product_id != '0' 
            AND purch_orders.fiscal_year_id = '$fiscal->id' AND products.org_id = '$this->org_id' 
            GROUP BY purch_order_details.product_id");
        $product_purchase_data = [];
        foreach ($purchase_product as $key => $value) {
            if ($value->name) { //name is not null
                array_push($product_purchase_data, ['name'=>$value->name, 'y'=>(int) $value->total]);
            }
        }

        //
        $customer_income_order = \DB::select("SELECT fin_orders.client_id , clients.name ,SUM(fin_orders.subtotal) as total FROM fin_orders LEFT JOIN clients ON fin_orders.client_id = clients.id WHERE fin_orders.source = 'client' AND fin_orders.org_id = '$this->org_id' AND fin_orders.fiscal_year_id = '$fiscal->id' GROUP BY fin_orders.client_id");

        $customer_invoice_income = \DB::select("SELECT  invoice.client_id , clients.name ,SUM(invoice.subtotal) as total FROM invoice 
            LEFT JOIN clients ON invoice.client_id = clients.id 
            LEFT JOIN invoice_meta ON invoice_meta.invoice_id = invoice.id 
            WHERE invoice.client_id != '0' AND  invoice_meta.is_bill_active = '1' 
            AND invoice.org_id = '$this->org_id' AND invoice.fiscal_year_id = '$fiscal->id' GROUP BY invoice.client_id");
        $customer_income = array_merge($customer_income_order, $customer_invoice_income);
        $customer_income_data = [];
        foreach ($customer_income as $key => $value) {
            if (isset($customer_income_data[$value->client_id])) {
                $customer_income_data[$value->client_id]['y'] += $value->total;
            } elseif ($value->name) {
                $customer_income_data[$value->client_id] = ['name'=>ucfirst($value->name), 'y'=>(int) $value->total];
            }
        }
        $customer_income_data = array_values($customer_income_data);
        //dd($customer_income_data);

        $sales_by_loc = \DB::select("SELECT fin_orders.from_stock_location , product_location.location_name ,SUM(fin_orders.total_amount) as total FROM fin_orders LEFT JOIN product_location ON product_location.id = fin_orders.from_stock_location WHERE fin_orders.from_stock_location != '0' AND fin_orders.org_id = '$this->org_id' AND fin_orders.fiscal_year_id = '$fiscal->id' GROUP BY fin_orders.from_stock_location");
        $sales_by_loc_data = [];
        foreach ($sales_by_loc as $key => $value) {
            if ($value->location_name) { //name is not null
                array_push($sales_by_loc_data, ['name'=>$value->location_name, 'y'=>(int) $value->total]);
            }
        }

        $purch_by_loc = \DB::select("SELECT purch_orders.into_stock_location, product_location.location_name, SUM(purch_orders.total) as total FROM purch_orders  LEFT JOIN product_location ON product_location.id = purch_orders.into_stock_location WHERE purch_orders.into_stock_location != '0' AND purch_orders.org_id = '$this->org_id'  AND purch_orders.fiscal_year_id = '$fiscal->id' GROUP BY purch_orders.into_stock_location");
        //dd($purch_by_loc);
        $purc_by_location_data = [];
        foreach ($purch_by_loc as $key => $value) {
            if ($value->location_name) { //name is not null
                array_push($purc_by_location_data, ['name'=>$value->location_name, 'y'=>(int) $value->total]);
            }
        }

        $purch_by_supplier = \DB::select("SELECT purch_orders.supplier_id, clients.name, SUM(purch_orders.total) as total FROM purch_orders  LEFT JOIN clients ON clients.id = purch_orders.supplier_id WHERE purch_orders.supplier_id != '0' 
            AND purch_orders.org_id = '$this->org_id'  AND purch_orders.fiscal_year_id = '$fiscal->id' GROUP BY purch_orders.into_stock_location");

        $purch_by_supplier_data = [];
        foreach ($purch_by_supplier as $key => $value) {
            if ($value->name) { //name is not null
                array_push($purch_by_supplier_data, ['name'=>ucfirst($value->name), 'y'=>(int) $value->total]);
            }
        }
       
        //dd($purch_by_supplier_data);
        return view('salesb', compact('page_title', 'page_description', 'quotation_data', 'invoices_data', 'fiscal', 'tax_invoices_data', 'purchase_bill_data', 'order_income_data', 'invoice_income_data', 'expense_data', 'all_fiscal_year', 'data_count', 'product_sales_data', 'product_purchase_data', 'sales_by_loc_data', 'purc_by_location_data', 'customer_income_data', 'purch_by_supplier_data', 'line_chart_purchasetax_salestax', 'line_chart_purchasetax_salestax_categories'));
    }
}
