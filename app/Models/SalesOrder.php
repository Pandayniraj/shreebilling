<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $table = 'sales_orders';
    protected $fillable = ['trans_type', 'invoice_type', 'lead_id', 'branch_id', 'payment_id', 'user_id', 'reference', 'comments', 'trans_type', 'invoice_type', 'ord_date', 'from_stk_loc', 'total'];

    public function getAllSalseOrder($from, $to, $location, $customer)
    {
        $where = "so.trans_type=201 AND so.invoice_type='directOrder'";

        $data = DB::select("SELECT so.order_no,so.reference, so.debtor_no, dm.name, so.ord_date, so.total as order_amount,so.paid_amount, sod.quantity as qty
             FROM sales_orders as so

             LEFT JOIN (select SUM(sales_order_details.quantity) as quantity,sales_order_details.order_no from sales_order_details GROUP BY order_no) as sod
             ON sod.order_no = so.order_no



             WHERE $where");

        return $data;
    }

    public function getAllSalseOrderQuotation($from, $to, $location, $customer)
    {
        $where = "so.trans_type=201 AND so.invoice_type='directOrder'";

        if ($location) {
            $where .= " AND so.from_stk_loc = '$location' ";
        }
        if ($customer) {
            $where .= " AND so.debtor_no = '$customer' ";
        }

        if ($from != null && $to != null) {
            $from = DbDateFormat($from);
            $to = DbDateFormat($to);
            $where .= " AND so.ord_date BETWEEN '$from' AND '$to' ";
        }

        $data = DB::select("SELECT so.order_no,so.reference, so.debtor_no, dm.name, so.ord_date, so.total as order_amount,so.paid_amount, sod.quantity as qty
             FROM sales_orders as so

             LEFT JOIN (select SUM(sales_order_details.quantity) as quantity,sales_order_details.order_no from sales_order_details GROUP BY order_no) as sod
             ON sod.order_no = so.order_no

             LEFT JOIN debtors_master as dm
             ON dm.debtor_no = so.debtor_no

             WHERE $where");

        return $data;
    }

    public function getSalseInvoiceByID($order_no)
    {
        $data = DB::table('sales_order_details')
                         ->where(['order_no'=>$order_no])
                         ->leftJoin('item_code', 'sales_order_details.stock_id', '=', 'item_code.stock_id')
                         ->leftJoin('item_tax_types', 'item_tax_types.id', '=', 'sales_order_details.tax_type_id')
                         ->select('sales_order_details.*', 'item_code.id as item_id', 'item_tax_types.tax_rate')
                         ->orderBy('sales_order_details.is_inventory', 'desc')
                         ->get();

        return $data;
    }

    public function getSalseInvoicePdf($id)
    {
        return $this->where(['sales_orders.order_no'=>$id])
                         ->leftJoin('debtors_master', 'sales_orders.debtor_no', '=', 'debtors_master.debtor_no')
                         ->leftJoin('sales_order_details', 'sales_orders.order_no', '=', 'sales_order_details.order_no')
                         ->select('sales_orders.*', 'debtors_master.*')
                         ->first();
    }

    public function stockValidate($loc, $id)
    {
        $data = DB::table('stock_moves')
                          ->select(DB::raw('sum(qty) as total'))
                          ->where(['stock_id'=>$id, 'loc_code'=>$loc])
                          ->groupBy('loc_code')
                          ->first();

        if (count($data) > 0) {
            if ($data->total <= 0) {
                $total = 0;
            } else {
                $total = $data->total;
            }
        } else {
            $total = 0;
        }

        return $total;
    }

    public function calculateTaxRow($order_no)
    {
        $tax_rows = DB::table('sales_order_details')
                     ->join('item_tax_types', 'item_tax_types.id', '=', 'sales_order_details.tax_type_id')
                     ->select(DB::raw('((sales_order_details.quantity*sales_order_details.unit_price-sales_order_details.quantity*sales_order_details.unit_price*sales_order_details.discount_percent/100)*item_tax_types.tax_rate)/100 AS tax_amount,item_tax_types.tax_rate'))
                     ->where('order_no', $order_no)
                     ->get();
        $tax_amount = [];
        $tax_rate = [];
        $i = 0;
        foreach ($tax_rows as $key => $value) {
            if (isset($tax_amount[$value->tax_rate])) {
                $tax_amount[strval($value->tax_rate)] += $value->tax_amount;
            } else {
                $tax_amount[strval($value->tax_rate)] = $value->tax_amount;
            }
        }

        return $tax_amount;
    }

    public function getSalseOrderByID($order_no, $location)
    {
        $datas = [];
        $data = DB::table('sales_order_details')
                         ->where(['order_no'=>$order_no])
                         ->leftJoin('item_code', 'sales_order_details.stock_id', '=', 'item_code.stock_id')
                         ->leftJoin('item_tax_types', 'item_tax_types.id', '=', 'sales_order_details.tax_type_id')
                         ->select('sales_order_details.*', 'item_code.id as item_id', 'item_tax_types.tax_rate')
                         ->orderBy('sales_order_details.quantity', 'DESC')
                         ->get();
        //  d($data,1);
        foreach ($data as $key => $value) {
            //d($location,1);
            $datas[$key]['id'] = $value->id;
            $datas[$key]['order_no'] = $value->order_no;
            $datas[$key]['trans_type'] = $value->trans_type;
            $datas[$key]['stock_id'] = $value->stock_id;
            $datas[$key]['tax_type_id'] = $value->tax_type_id;
            $datas[$key]['description'] = $value->description;
            $datas[$key]['unit_price'] = $value->unit_price;
            $datas[$key]['qty_sent'] = $value->qty_sent;
            $datas[$key]['quantity'] = $value->quantity;

            $datas[$key]['discount_percent'] = $value->discount_percent;
            $datas[$key]['item_id'] = $value->item_id;
            $datas[$key]['tax_rate'] = $value->tax_rate;
            $datas[$key]['total_qty'] = DB::table('stock_moves')->where(['stock_id'=>$value->stock_id, 'loc_code'=>$location])->sum('qty');
        }
        // d($datas,1);
        return $datas;
    }

    public function getRestOrderItemsByOrderID($order_no)
    {
        $data = DB::select(DB::raw("select orders.from_stk_loc as location, so.*,(so.quantity+COALESCE(sm.iqty,0)) as item_rest,ic.id as item_id,itt.tax_rate from (SELECT * FROM `sales_order_details` where order_no = $order_no)so
             left join (select stock_id, sum(qty) as iqty from stock_moves where order_no = $order_no group by stock_id)sm on so.stock_id = sm.stock_id
             left join item_code as ic
             on ic.stock_id = so.stock_id
             left join item_tax_types as itt
             on itt.id = so.tax_type_id
             left join sales_orders as orders
             on orders.order_no = so.order_no"));

        return $data;
    }

    public function getInvoicedItemsQty($orderNo)
    {
        $data = DB::table('stock_moves')
                          ->select(DB::raw('sum(qty) as total'), 'stock_id', 'order_no')
                          ->where(['order_no'=>$orderNo])
                          ->groupBy(['stock_id', 'order_no'])
                          ->get();

        return $data;
    }

    public function calculateTaxRowRestItem($order_no)
    {
        $tax_rows = DB::select(DB::raw("select so.discount_percent, (so.quantity+COALESCE(sm.iqty,0)) as item_rest,so.unit_price,itt.tax_rate from (SELECT * FROM `sales_order_details` where order_no = $order_no)so left join (select stock_id, sum(qty) as iqty from stock_moves where order_no = $order_no group by stock_id)sm on so.stock_id = sm.stock_id left join item_tax_types as itt on itt.id = so.tax_type_id"));
        $tax_amount = [];
        $tax_rate = [];
        $i = 0;

        foreach ($tax_rows as $key => $value) {
            $sum = (($value->item_rest * $value->unit_price) - ($value->item_rest * $value->unit_price * $value->discount_percent / 100)) * $value->tax_rate / 100;
            if (isset($tax_amount[$value->tax_rate])) {
                $tax_amount[strval($value->tax_rate)] += $sum;
            } else {
                $tax_amount[strval($value->tax_rate)] = $sum;
            }
        }

        return $tax_amount;
    }

    public function getInvoicedQty($orderReferenceId, $stock_id, $location, $orderReference)
    {
        $data = DB::table('stock_moves')
                 ->where(['stock_id'=>$stock_id, 'order_no'=>$orderReferenceId, 'loc_code'=>$location, 'order_reference'=>$orderReference])
                 ->sum('qty');

        return $data;
    }

    public function getOrderedQty($orderReferenceId, $stock_id)
    {
        $data = DB::table('sales_order_details')
                 ->where(['stock_id'=>$stock_id, 'order_no'=>$orderReferenceId])
                 ->sum('quantity');

        return $data;
    }

    public function getAllSalseOrderByCustomer($customer_id)
    {
        $data = DB::select(DB::raw("SELECT so.`order_no`,so.debtor_no,dm.name,so.`reference`,so.`total` as
                   order_amount,COALESCE(ph.paid_amount,0) as paid_amount,COALESCE
                   (shipment.status,0) as status,COALESCE(shipment.packed_qty,0) as
                   packed_qty,sod.ordered_quantity,COALESCE
                   (invocie.invoiced_quantity,0) as invoiced_quantity,so.ord_date  FROM
                   `sales_orders` as so
                   LEFT JOIN debtors_master as dm
                   ON dm.`debtor_no` = so.`debtor_no`
                   LEFT JOIN(SELECT SUM(`amount`) as paid_amount,`order_reference`
                   FROM `payment_history` GROUP BY `order_reference`)ph
                   ON ph.order_reference = so.reference
                   LEFT JOIN(SELECT s.order_no,s.status,sd.packed_qty FROM shipment as
                   s
                   LEFT JOIN(SELECT `order_no`,SUM(`quantity`) as packed_qty FROM
                   `shipment_details` GROUP BY `order_no`)sd
                   ON sd.order_no = s.order_no
                   GROUP BY `order_no`)shipment
                   ON shipment.order_no = so.order_no
                   LEFT JOIN(SELECT order_no, SUM(quantity) as ordered_quantity FROM
                   `sales_order_details` WHERE `trans_type` = 201 GROUP BY order_no)
                   sod
                   ON sod.order_no = so.order_no
                   LEFT JOIN(SELECT order_no, SUM(qty) as invoiced_quantity FROM
                   `stock_moves` WHERE `trans_type` = 202 GROUP BY order_no)
                   invocie
                   ON invocie.order_no = so.order_no
                   WHERE so.`trans_type` = 201 AND so.debtor_no = $customer_id ORDER BY so.order_no DESC"));
        //d($data,1);
        return $data;
    }

    public function getAllSalseInvoiceByCustomer($customer_id)
    {
        $data = $this->leftJoin('debtors_master', 'sales_orders.debtor_no', '=', 'debtors_master.debtor_no')
                         ->leftJoin('location', 'sales_orders.from_stk_loc', '=', 'location.loc_code')
                         ->select('sales_orders.*', 'debtors_master.name as cus_name', 'location.location_name as loc_name')
                         ->where(['sales_orders.trans_type'=>SALESINVOICE, 'sales_orders.debtor_no'=>$customer_id])
                         ->orderBy('sales_orders.reference', 'desc')
                         ->get();
        // d($data,1);
        return $data;
    }

    public function getAllSalseOrderByUserId($from, $to, $customer, $location, $uid)
    {
        $from = DbDateFormat($from);
        $to = DbDateFormat($to);

        if ($customer == 'all' && $location == 'all') {
            $data = DB::select(DB::raw("SELECT so.`order_no`,so.debtor_no,dm.name,so.`reference`,so.`total` as
           order_amount,COALESCE(ph.paid_amount,0) as paid_amount,COALESCE
           (shipment.status,0) as status,COALESCE(shipment.packed_qty,0) as
           packed_qty,sod.ordered_quantity,COALESCE
           (invocie.invoiced_quantity,0) as invoiced_quantity,so.ord_date  FROM
           `sales_orders` as so
           LEFT JOIN debtors_master as dm
           ON dm.`debtor_no` = so.`debtor_no`
           LEFT JOIN(SELECT SUM(`amount`) as paid_amount,`order_reference`
           FROM `payment_history` GROUP BY `order_reference`)ph
           ON ph.order_reference = so.reference
           LEFT JOIN(SELECT s.order_no,s.status,sd.packed_qty FROM shipment as
           s
           LEFT JOIN(SELECT `order_no`,SUM(`quantity`) as packed_qty FROM
           `shipment_details` GROUP BY `order_no`)sd
           ON sd.order_no = s.order_no
           GROUP BY `order_no`)shipment
           ON shipment.order_no = so.order_no
           LEFT JOIN(SELECT order_no, SUM(quantity) as ordered_quantity FROM
           `sales_order_details` WHERE `trans_type` = 201 GROUP BY order_no)
           sod
           ON sod.order_no = so.order_no
           LEFT JOIN(SELECT order_no, SUM(qty) as invoiced_quantity FROM
           `stock_moves` WHERE `trans_type` = 202 GROUP BY order_no)
           invocie
           ON invocie.order_no = so.order_no
           WHERE so.`trans_type` = 201 AND so.person_id = $uid AND so.ord_date BETWEEN '$from' AND '$to' ORDER BY so.ord_date DESC"));

            return $data;
        } elseif ($customer != 'all' && $location == 'all') {
            $data = DB::select(DB::raw("SELECT so.`order_no`,so.debtor_no,dm.name,so.`reference`,so.`total` as
           order_amount,COALESCE(ph.paid_amount,0) as paid_amount,COALESCE
           (shipment.status,0) as status,COALESCE(shipment.packed_qty,0) as
           packed_qty,sod.ordered_quantity,COALESCE
           (invocie.invoiced_quantity,0) as invoiced_quantity,so.ord_date  FROM
           `sales_orders` as so
           LEFT JOIN debtors_master as dm
           ON dm.`debtor_no` = so.`debtor_no`
           LEFT JOIN(SELECT SUM(`amount`) as paid_amount,`order_reference`
           FROM `payment_history` GROUP BY `order_reference`)ph
           ON ph.order_reference = so.reference
           LEFT JOIN(SELECT s.order_no,s.status,sd.packed_qty FROM shipment as
           s
           LEFT JOIN(SELECT `order_no`,SUM(`quantity`) as packed_qty FROM
           `shipment_details` GROUP BY `order_no`)sd
           ON sd.order_no = s.order_no
           GROUP BY `order_no`)shipment
           ON shipment.order_no = so.order_no
           LEFT JOIN(SELECT order_no, SUM(quantity) as ordered_quantity FROM
           `sales_order_details` WHERE `trans_type` = 201 GROUP BY order_no)
           sod
           ON sod.order_no = so.order_no
           LEFT JOIN(SELECT order_no, SUM(qty) as invoiced_quantity FROM
           `stock_moves` WHERE `trans_type` = 202 GROUP BY order_no)
           invocie
           ON invocie.order_no = so.order_no
           WHERE so.`trans_type` = 201 AND so.person_id = $uid AND so.ord_date BETWEEN '$from' AND '$to' AND so.debtor_no = '$customer' ORDER BY so.ord_date DESC"));

            return $data;
        } elseif ($customer == 'all' && $location != 'all') {
            $data = DB::select(DB::raw("SELECT so.`order_no`,so.debtor_no,dm.name,so.`reference`,so.`total` as
           order_amount,COALESCE(ph.paid_amount,0) as paid_amount,COALESCE
           (shipment.status,0) as status,COALESCE(shipment.packed_qty,0) as
           packed_qty,sod.ordered_quantity,COALESCE
           (invocie.invoiced_quantity,0) as invoiced_quantity,so.ord_date  FROM
           `sales_orders` as so
           LEFT JOIN debtors_master as dm
           ON dm.`debtor_no` = so.`debtor_no`
           LEFT JOIN(SELECT SUM(`amount`) as paid_amount,`order_reference`
           FROM `payment_history` GROUP BY `order_reference`)ph
           ON ph.order_reference = so.reference
           LEFT JOIN(SELECT s.order_no,s.status,sd.packed_qty FROM shipment as
           s
           LEFT JOIN(SELECT `order_no`,SUM(`quantity`) as packed_qty FROM
           `shipment_details` GROUP BY `order_no`)sd
           ON sd.order_no = s.order_no
           GROUP BY `order_no`)shipment
           ON shipment.order_no = so.order_no
           LEFT JOIN(SELECT order_no, SUM(quantity) as ordered_quantity FROM
           `sales_order_details` WHERE `trans_type` = 201 GROUP BY order_no)
           sod
           ON sod.order_no = so.order_no
           LEFT JOIN(SELECT order_no, SUM(qty) as invoiced_quantity FROM
           `stock_moves` WHERE `trans_type` = 202 GROUP BY order_no)
           invocie
           ON invocie.order_no = so.order_no
           WHERE so.`trans_type` = 201 AND so.person_id = $uid AND so.ord_date BETWEEN '$from' AND '$to' AND so.from_stk_loc = '$location' ORDER BY so.ord_date DESC"));

            return $data;
        } elseif ($customer != 'all' && $location != 'all') {
            $data = DB::select(DB::raw("SELECT so.`order_no`,so.debtor_no,dm.name,so.`reference`,so.`total` as
           order_amount,COALESCE(ph.paid_amount,0) as paid_amount,COALESCE
           (shipment.status,0) as status,COALESCE(shipment.packed_qty,0) as
           packed_qty,sod.ordered_quantity,COALESCE
           (invocie.invoiced_quantity,0) as invoiced_quantity,so.ord_date  FROM
           `sales_orders` as so
           LEFT JOIN debtors_master as dm
           ON dm.`debtor_no` = so.`debtor_no`
           LEFT JOIN(SELECT SUM(`amount`) as paid_amount,`order_reference`
           FROM `payment_history` GROUP BY `order_reference`)ph
           ON ph.order_reference = so.reference
           LEFT JOIN(SELECT s.order_no,s.status,sd.packed_qty FROM shipment as
           s
           LEFT JOIN(SELECT `order_no`,SUM(`quantity`) as packed_qty FROM
           `shipment_details` GROUP BY `order_no`)sd
           ON sd.order_no = s.order_no
           GROUP BY `order_no`)shipment
           ON shipment.order_no = so.order_no
           LEFT JOIN(SELECT order_no, SUM(quantity) as ordered_quantity FROM
           `sales_order_details` WHERE `trans_type` = 201 GROUP BY order_no)
           sod
           ON sod.order_no = so.order_no
           LEFT JOIN(SELECT order_no, SUM(qty) as invoiced_quantity FROM
           `stock_moves` WHERE `trans_type` = 202 GROUP BY order_no)
           invocie
           ON invocie.order_no = so.order_no
           WHERE so.`trans_type` = 201 AND so.person_id = $uid AND so.ord_date BETWEEN '$from' AND '$to' AND so.from_stk_loc = '$location' AND so.debtor_no = '$customer' ORDER BY so.ord_date DESC"));

            return $data;
        }
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Intakes from editing changes
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Intakes from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    public function hasPerm(Permission $perm)
    {
        // perm 'basic-authenticated' is always checked.
        if ('basic-authenticated' == $perm->name) {
            return true;
        }
        // Return true if the Intake has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
    }

    public function lead()
    {
        return $this->belongsTo(\App\Models\Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
