<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
  <head>
    <meta charset="UTF-8">
    <title>{{ env('APP_COMPANY')}} | {{ ucfirst(Request::segment(4)) }}</title>

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
    <!-- Ionicons 2.0.1 -->
    <link href="{{ asset("/bower_components/admin-lte/ionicons/css/ionicons.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css" />
     <link href="/build/css/all-b123e7e3.css" rel="stylesheet" type="text/css" />

    <!-- Application CSS-->

    <style>
table {
  width:100%;
}
table, th, td {
  border: 1px solid #696969;
  border-collapse: collapse;
}
th, td {
  padding: 15px;
  text-align: left;
}
table#t01 tr:nth-child(even) {
  background-color: #eee;
}
table#t01 tr:nth-child(odd) {
 background-color: #fff;
}
table#t01 th {
  background-color: #696969  ;
  color: white;
}
  

.table>thead>tr>th {
    border-bottom: 1px solid #696969 !important;
}
.table>tbody>tr>th {
    border-top: 1px solid #696969 !important;
}
.table>tbody>tr>td{
  border-top: 1px solid #696969 !important;
}
</style>


  </head>

<body cz-shortcut-listen="true" class="skin-blue sidebar-mini">

   <?php
      $mytime = Carbon\Carbon::now();
      $startOfYear = $mytime->copy()->startOfYear();

      $endOfYear   = $mytime->copy()->endOfYear();
  ?>

  <div class='wrapper'>

      <section class="invoice">
        <!-- title row -->
                <div class="row">
                  <div class="col-xs-12">
                    <h2 class="page-header">
                       <div class="col-xs-3">
                      <img src="{{env('APP_URL')}}/org/{{$imagepath}}" style="max-width: 200px;">
                        </div>
                        <div class="col-xs-9">
                      <span class="pull-right">
                        <span></span>
                      </span>
                    </div>
                     <hr>
                    </h2>
                   
                  </div>
                  <!-- /.col -->
                </div>
                <!-- info row -->


            <div class="col-xs-12">
                 <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Report - Group Statement</h3>
                      <br>
                      Ledger statement for <strong>{{$groups_data->name}}</strong> from <strong>{{date('d-M-Y', strtotime($startOfYear))}}</strong> to <strong>{{date('d-M-Y', strtotime($endOfYear))}}</strong> 
                    </div>
                      <!-- /.box-header -->
                      <div class="box-body">
                          <div>
                            {{--  Bank Or Cash Account :<?php
                        echo ($ledger_data->type == 1) ? 'Yes' : 'No';
                      ?>
                              <br>Notes : {{$groups_data->notes}}
                              <br>Opening balance as on {{date('d-M-Y', strtotime($startOfYear))}}: @if($groups_data->op_balance_dc == 'D') Dr @else Cr @endif{{$groups_data->op_balance}}
                              <?php 
                               $closing_balance =TaskHelper::getLedgerBalance($id);

                              ?>
                              <br>Closing balance as on {{date('d-M-Y', strtotime($endOfYear))}}: {{ $closing_balance }} --}}
                              <br>
                              <table class="table table-bordered">
                                <tbody>
                                  <tr>
                                    <th>Date</th>
                                    <th>No.</th>
                                    <th>Ledger</th>
                                    <th>Description</th>
                                    <th>Entry Type</th>
                                    <th>Tag</th>
                                    <th>({{env(APP_CURRENCY)}})</th>
                                    <th>({{env(APP_CURRENCY)}})</th>
                                    <th>Balance({{env(APP_CURRENCY)}})</th>
                                  </tr>
                                 {{-- <tr>
                                    <td colspan="7">Current opening balance</td>
                                    <td>@if($groups_data->op_balance_dc == 'D') Dr @else Cr @endif  {{$groups_data->op_balance}}</td>
                                  </tr> --}}

                                    <?php
                                    /* Current opening balance */
                                    $entry_balance['amount'] = $groups_data['op_balance'];
                                    $entry_balance['dc'] = $groups_data['op_balance_dc'];
                                    
                                     ?>
                                  @foreach($entry_items as $ei)

                                   <?php 

                                    $entry_balance = TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
                                    $ei['amount'], $ei['dc']);

                                    $getledger= TaskHelper::getLedger($ei->entry_id);
                                    ?>
                                  <tr>
                                      <td>{{$ei->entry->date}}</td>
                                      <td>{{$ei->entry->number}}</td>
                                      <td>{{ substr($ei->ledgerdetail->name, 0,  20) }}</td>
                                      <td>{{$getledger}}</td>
                                      <td>{{$ei->entry->entrytype->name}}</td><td><span class="tag" style="color:#f51421; background-color:#gba(17;">
                                        <span style="color: #f51421;">{{$ei->entry->tagname->title}}</span>
                                          </span>
                                      </td>
                                      @if($ei->dc=='D')
                                        <td>Dr {{$ei->amount}}</td>
                                        <td>-</td>
                                      @else
                                        <td>-</td>
                                        <td>Cr {{$ei->amount}}</td>
                                      @endif

                                      <td>@if($entry_balance['dc']=='D') Dr @else Cr @endif {{number_format($entry_balance['amount'],2)}}</td>

                                    </tr>
                                  @endforeach
                                  <tr>
                                    <td colspan="7">Current closing balance</td>
                                    <td>@if($entry_balance['dc']=='D') Dr @else Cr @endif {{ number_format($entry_balance['amount'],2)}}</td>
                                  </tr>
                                </tbody>
                              </table>  
                           </div>
                      </div>
                </div>
       </div>
      

      </section>

  </div><!-- /.col -->

</body>
<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<script>
    $(document).ready(function() {
        window.print();
    }); 
    
    </script> 