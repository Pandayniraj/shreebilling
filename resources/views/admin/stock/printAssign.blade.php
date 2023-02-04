<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
  <head>
    <meta charset="UTF-8">
    <title>Stock Assign List</title>
    

    <!-- block from searh engines -->
    <meta name="robots" content="noindex">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Set a meta reference to the CSRF token for use in AJAX request -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons 4.7.0 -->
    <link href="{{ asset("/bower_components/admin-lte/font-awesome/css/all.css") }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css" />

    <!-- Application CSS-->
    <link href="{{ asset(elixir('css/all.css')) }}" rel="stylesheet" type="text/css" />


  </head>

<body cz-shortcut-listen="true" class="skin-blue sidebar-mini">

  <div class='wrapper'>

    @if($assigns)

    <div id="EmpprintReport">
        <div class="row">
            <div class="col-sm-12 std_print">
                <div class="panel panel-custom">
                    <h2 style="text-align: center;">@if($user_id) {{ TaskHelper::getUserName($user_id) }} - @endif Stock Assign List - @if($start_date) {{ $start_date.' '.$end_date }} @else {{ date('Y-m-d') }} @endif</h2>
                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="col-sm-1">SN</th>
                                <th>Item Name</th>
                                <th>Stock Category</th>
                                <th>Assign Quantity</th>
                                <th>Assigned User</th>
                                <th>Assign Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assigns as $ak => $av)
                            <tr>
                                <td>{{ $ak+1 }}</td>
                                <?php $assign_stock = StockHelper::getStock($av->stock_id); ?>
                                <td>{{ $assign_stock->item_name }}</td>
                                <td>{{ StockHelper::getCatSubCat($assign_stock->stock_sub_category_id) }}</td>
                                <td>{{ $av->assign_inventory }}</td>
                                <?php $user = $av->user; ?>
                                <td>{{ $user->first_name.' '.$user->last_name }}</td>
                                <td>{{ $av->assign_date }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @endif

  </div><!-- /.col -->

</body>
<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<script>
    $(document).ready(function() {
        window.print();
    }); 
    
    </script> 