<?php

namespace App\Helpers;

use App\Models\Stock;
use App\Models\StockCategory;
use App\Models\StockSubCategory;

class StockHelper
{
    public static function getSubCategory($stock_sub_category_id)
    {
        $temp = StockSubCategory::where('stock_sub_category_id', $stock_sub_category_id)
                 ->first();
        return $temp;
    }

    public static function getSubCategories($stock_category_id)
    {
        $temp = StockSubCategory::where('stock_category_id', $stock_category_id)
                 ->get();

        return $temp;
    }

    

    public static function getRemaingStocks($stock_id){
        
        $remaining = \App\Models\StockMove::where('stock_id',$stock_id)->sum('qty');
        return $remaining;

    }

    public static function getCatName($stock_category_id)
    {
        $temp = StockCategory::select('stock_category')->where('stock_category_id', $stock_category_id)
                 ->first();

        return $temp;
    }

    public static function getSubCatName($stock_sub_category_id)
    {
        $temp = StockSubCategory::select('stock_sub_category')->where('stock_sub_category_id', $stock_sub_category_id)
                 ->first();

        return $temp;
    }

    public static function getStockItemName($stock_id)
    {
        $temp = Stock::select('item_name')->where('stock_id', $stock_id)
                 ->first();
        if ($temp) {
            return $temp->item_name;
        } else {
            return '';
        }
    }

    public static function getCatSubCat($stock_sub_category_id)
    {
        $temp = StockCategory::select('asset_category.stock_category', 'asset_sub_category.stock_sub_category')
                        ->join('asset_sub_category', 'asset_sub_category.stock_category_id', '=', 'asset_category.stock_category_id')
                        ->where('asset_sub_category.stock_sub_category_id', $stock_sub_category_id)
                        ->first();
        if ($temp) {
            return $temp->stock_category.' > '.$temp->stock_sub_category;
        } else {
            return '';
        }
    }

    public static function getStock($stock_id)
    {
        $temp = Stock::select('stock_sub_category_id', 'item_name', 'project_id')->where('stock_id', $stock_id)
                 ->first();

        return $temp;
    }

    public static function getStockSubCategory($stock_sub_category_id)
    {
        $temp = Stock::select('stock_id', 'item_name', 'unit_price', 'total_stock', 'project_id')->where('stock_sub_category_id', $stock_sub_category_id)
                 ->get();

        return $temp;
    }

        public static function getUnitPrice($unitId){

        $unitsPrice = \App\Models\ProductsUnit::find($unitId)->qty_count ?? '1';

        return $unitsPrice;

    }
//niraj
public static function averageAmount($priceList, $qty)
{
    
    if(isset($priceList, $qty)){
    foreach($priceList as $k => $price)
    {   
       
        $copyvalue=$price['quantity_recieved'];
        if($copyvalue > abs($qty))
        {   
            $amount=$price['unitpricewithimport']-($price['discount']/$price['quantity_ordered']);
            $priceList[$k]['quantity_recieved']  = $priceList[$k]['quantity_recieved'] - abs($qty);
            return ['list' => $priceList, 'checkvalue'=> 0, 'result' => $amount, 'qty' => abs($qty), 'more' => 0];
        }
        else if($copyvalue == abs($qty))
        {
            $amount=$price['unitpricewithimport']-($price['discount']/$price['quantity_ordered']);
            array_shift($priceList);
            return ['list' => $priceList, 'checkvalue'=> 0, 'result' => $amount, 'qty' => abs($qty), 'more'=>0];
        }
        else {
            // $checkvalue=$checkvalue+$price['quantity_recieved'];
            // if($checkvalue >= (int)$salesquantity){
            //     $amount=$price['unitpricewithimport']- ($price['discount']/$price['quantity_ordered']);
            //     array_shift($priceList);
            //     return ['list' => $priceList, 'result' => $amount, 'checkvalue'=> $price['quantity_recieved'], 'qty' => $price['quantity_recieved'], 'more'=>0];
            // }
            $amount=$price['unitpricewithimport']- ($price['discount']/$price['quantity_ordered']);
            array_shift($priceList);
            return ['list' => $priceList, 'result' => $amount, 'checkvalue'=> abs($qty) - $price['quantity_recieved'], 'qty' => $price['quantity_recieved'], 'more'=>abs($qty) - $price['quantity_recieved']];
        }
    }
}
}
}
