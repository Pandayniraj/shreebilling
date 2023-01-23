<table id="" class="table table-bordered std_table">
                    <thead>
                     <tr class="bg-purple">
                      <th class="text-center">Ord Num.</th>
                      <th class="text-center">Party</th>
                      <th class="text-center">Bill No.</th>
                        
                      <th class="text-center">Ref No.#</th>
                        <th class="text-center">Tran Type</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Quantity In</th>
                        <th class="text-center">Quantity Out</th>
                        <th class="text-center"> <i class="fa fa- fa-hand-paper-o"></i>  On Hand (BottleWise) </th>
                        <th class="text-center"> <i class="fa fa- fa-hand-paper-o"></i>On Hand (Case-Wise)</th>

                    </tr>
                    </thead>
                        <tbody>  
                     <?php
                    $sum = 0;
                    $sumbottlewise=0;
                    $StockIn = 0;
                    $StockOut = 0;
                    ?>
                    @if(count($transations)>0)
                    @foreach($transations as $result)
                        <?php 
                           $reasons = \App\Models\AdjustmentReason::all();
                           $type = '';
                           $href = null;
                           $order = null;
                           
                           if($result->trans_type == PURCHINVOICE){

                            $order = $result->get_purchase;
                            $type = 'Purchase';
                            $href = $order->id ?  "/admin/purchase/{$order->id}?type={$order->purchase_type}" : null;

                           }elseif ($result->trans_type == SALESINVOICE) {
                            $order = $result->get_sales;
                            $type = 'Sales';
                            $href = $order->id ? "/admin/orders/{$order->id}" : null;
                           }elseif ($result->trans_type == OTHERSALESINVOICE) {
                            $order = $result->get_invoice;
                            $type = 'Invoice';
                            $href = $order->id ? "/admin/invoice1/{$order->id}": null;
                           }elseif ($result->trans_type == STOCKMOVEIN) {
                          
                            $type = 'Transfer';
                           }elseif ($result->trans_type == STOCKMOVEOUT) {
                          
                            $type = 'Transfer';
                           }elseif ($result->trans_type == GRN) {                          
                            $type = 'GRN';
                           }
                           else{  // For Stock Adjustment
                            
                            foreach($reasons as $reason)
                            {
                              if($reason->trans_type == $result->trans_type)
                              {
                                $type = ucwords($reason->name);
                                break;
                              }                           
                            }
                           }
                        ?>
                      <tr>
                        <td align="center">
                          @if($href)
                          <a href="{{ $href }}" target="_blank">
                            #{{$result->id}}
                          </a>
                          @else 
                            #{{$result->id}}
                          @endif
                        </td>
                      <td style="font-size: 16.5px" align="left">
                        {{ isset($order) ? $order->client->name : $result->store->name}}
                      </td>
                      <td align="center">{{ isset($order) ? $order->bill_no : '' }} </td>
                      <td align="center">{{ isset($order) ? $order->reference: $result->reference}}</td>
                      <td align="center">

                          {{ $type}}

                      </td>
                      <td align="center">{{$result->tran_date}}</td>
                      <td align="center">{{$result->location_name}}</td>
                      <td align="center">
                        @if($result->qty >0) 
                          {{$result->qty * $result->unit->qty_count}}
                          <?php
                          $StockIn +=$result->qty * $result->unit->qty_count ;
                          ?>
                        @else
                        -
                        @endif
                      </td>
                      <td align="center">
                        @if($result->qty <0 )
                          {{str_ireplace('-','',$result->qty * $result->unit->qty_count)}} 
                          <?php
                          $StockOut +=$result->qty * $result->unit->qty_count;
                          ?>
                        @else
                        -
                        @endif
                      </td>
                      <?php
                      $sumbottlewise+=$result->qty * $result->unit->qty_count;
                      ?>
                      <td align="center">{{$sumbottlewise}}</td>
                      <?php
								      $unitid= \App\Models\Product::where('id', $result->stock_id)->first()->product_unit;
								      $unitqty= \App\Models\ProductsUnit::where('id', $unitid)->first()->qty_count;
								      $sum=$sumbottlewise/$unitqty;
							?>
                      <td align="center">{{number_format($sum, 2) }}</td>
                    </tr>
                    @endforeach
                     <tr><td colspan="7" align="right">Total</td><td align="center">{{$StockIn}}</td><td align="center">{{str_ireplace('-','',$StockOut)}}</td><td align="center">{{$StockIn+$StockOut}}</td><td align="center">{{number_format($sum,2)}}</td></tr>
                    @else
                    <tr>
                        <td colspan="9" class="text-center text-danger">No Transaction Yet</td>
                    </tr>
                   @endif

                </tbody>
                </table>