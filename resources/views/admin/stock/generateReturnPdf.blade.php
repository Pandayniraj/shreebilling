<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Stock Assign List</title>
    <style>
        table td {
            padding: 5px;
            text-align: left;
            border: 1px solid #ccc;
        }

    </style>
</head>
<body>

    @if($assigns)

    <div id="EmpprintReport">
        <div class="row">
            <div class="col-sm-12 std_print">
                <div class="panel panel-custom">
                    <h2 style="text-align: center;">@if($user_id) {{ TaskHelper::getUserName($user_id) }} - @endif Stock Assign List - @if($start_date) {{ $start_date.' and '.$end_date }} @else {{ date('Y-m-d') }} @endif</h2>
                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="col-sm-1">SN</th>
                                <th>Item Name</th>
                                <th>Stock Category</th>
                                <th>Return Quantity</th>
                                <th>Returned User</th>
                                <th>Return Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assigns as $ak => $av)
                            <tr>
                                <td>{{ $ak+1 }}</td>
                                <?php $assign_stock = StockHelper::getStock($av->stock_id); ?>
                                <td>{{ $assign_stock->item_name }}</td>
                                <td>{{ StockHelper::getCatSubCat($assign_stock->stock_sub_category_id) }}</td>
                                <td>{{ $av->return_inventory }}</td>
                                <?php $user = $av->user; ?>
                                <td>{{ $user->first_name.' '.$user->last_name }}</td>
                                <td>{{ $av->return_date }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @endif

</body>
</html>
