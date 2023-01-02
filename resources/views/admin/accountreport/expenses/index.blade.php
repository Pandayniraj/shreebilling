@extends('layouts.master')
@section('content')
<style type="text/css">
.select-mini {
  font-size: 10px;
  height: 25px;
  width: 90px;
}
 /*.nep-date-toggle{*/
 /*       width: 120px !important;*/
 /*   }*/
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ ucfirst($page_title)}}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    <p><i class="fa fa-money"></i> MONEY OUT. Record all the expenses, this will automatically maintain AP.</p>

    {{ TaskHelper::topSubMenu('topsubmenu.accounts')}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
        <div class="box box-primary">
             <form method="get" action="/admin/expenses">
            <div class="box-header with-border">

                &nbsp;
                <a class="btn btn-default btn-sm" href="{!! route('admin.expenses.create') !!}" title="Create New Expense">
                    Add New &nbsp; <i class="fa fa-plus-square"></i>
                </a>
                &nbsp;


                <div class="box-tools pull-right">
{{--                    <a href="/admin/download/expenses/pdf/index" class="btn btn-success btn-xs float-right no-loading" style="margin-right: 20px;">PDF Download</a>--}}
                    &nbsp;

                    <button type="submit" name="submit" value="excel" class="btn btn-success btn-xs float-right no-loading">
                        Excel Download
                    </button>
{{--
                    <butt href="/admin/download/expenses/excel/index" class="btn btn-success btn-xs float-right">Excel Download</a --}}
                    &nbsp;
                </div>
                <div class="wrap" style="margin-top:5px;">
                <div class="filter form-inline" >
                    <div class="row">
                        <div class="col-md-2">
                        @if(\Auth::user()->hasRole(['admins']))
                        <select name="user" id="user" class="form-control select2">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                            <option value="{{$user->id}}" {{\Request::get('user') ==$user->id ? 'selected':'' }}>{{$user->first_name.' '.$user->last_name}} ({{$user->id}})</option>
                            @endforeach()
                        </select>
                        @endif
                        </div>
                        <div class="col-md-2">
                        {!! Form::text('start_date', \Request::get('start_date'), ['class' => 'form-control input-sm date-toggle-nep-eng1', 'id'=>'start_date', 'placeholder'=>'Start Date','autocomplete' =>'off']) !!}
                        </div>
                        <div class="col-md-2">
                        {!! Form::text('end_date', \Request::get('end_date'), ['class' => 'form-control input-sm date-toggle-nep-eng1', 'id'=>'end_date', 'placeholder'=>'End Date','autocomplete' =>'off']) !!}
                    </div>
                        <div class="col-md-2">
                            <select class='form-control searchable select2' style="width: 215px!important;" name="paid_through" id="paid_through">
                                <option value="">Select Ledger</option>
                                {{ \FinanceHelper::ledgerGroupsOptionshtml(6,null,'cash_equivalence') }}
                            </select>
                        </div>
                        <div class="col-md-2">
                            {!! Form::select('tags', $tags, \Request::get('tags'), ['style'=>"width: 215px!important;",'id'=>'', 'class'=>'form-control select-mini select2','placeholder'=>'Select Tags']) !!}
                </div>
                    <div class="col-md-2">
                            <button class="btn btn-primary btn-sm" id="btn-submit-filter" type="submit">
                            <i class="fa fa-list"></i> Filter
                        </button>

                        <a href="/admin/expenses" class="btn btn-danger btn-sm" id="btn-filter-clear" >
                            <i class="fa fa-close"></i> Clear
                        </a>
                        </div>
                    </div>







                    </div>

                </div>
            </div>
              </form>
            <div class="box-body">

                <div class="table-responsive">

<table class="table table-hover table-bordered" id="clients-table">
<thead>
    <tr class="bg-maroon">

        <th>EXP ID</th>
        <th>#JV</th>
        <th>Bill#</th>
        @if(\Auth::user()->hasRole(['admins']))
        <th>User</th>
        @endif
        <th style="width: 80px">Date</th>
{{--        <th>Expenses Account</th>--}}
         <th> Tags</th>
        <th>Paid Through</th>
         <th>Supplier</th>
         <th>Taxable/Non Taxable Amt</th>
        <th>Tax Amt</th>
        <th>Amount</th>

        <th>Action</th>

        <!--  <th>Actions</th> -->
    </tr>
</thead>
<tbody>
<?php
$total_taxable=0;
$total_tax=0;
$total=0;
?>
    @foreach($clients as $client)
    <tr>

        <td>{{\FinanceHelper::getAccountingPrefix('EXPENSE_PRE')}}{{$client->id}}</td>

        <td>
            <a target="_blank" href="/admin/entries/show/{{\FinanceHelper::get_entry_type_label($client->entry->entrytype_id)}}/{{$client->entry->id}}">{{$client->entry->number}}</a>
        </td>
        <td>
            {{$client->bill_no}}
        </td>
        @if(\Auth::user()->hasRole(['admins']))
        <td>{{ ($client->user->first_name??'' ) .' ' .($client->user->last_name??'')}}</td>
        @endif
        <td  >{!! date('d M y', strtotime($client->date)) !!}
        <div style="color:grey;font-size: grey"> {{  TaskHelper::getNepaliDate($client->date) }} BS</div>
        </td>
{{--        --}}
{{--        <td title="{{$client->ledger->name}}" style="font-size:16px">--}}
{{--            <a href="/admin/expenses/{{ $client->id }}">--}}
{{--                {{ mb_substr($client->ledger->name,0,16)}}.--}}
{{--            </a>--}}
{{--        </td>--}}
        <td>{{ $client->tag->name ?? '' }}</td>

        <td title="{{$client->paidledger->name??''}}">{!! mb_substr($client->paidledger->name,0,15) !!}.</td>
        <td>{{$client->vendor->name }}</td>

        <td><strong>{!! number_format($client->amount,2) !!}</strong></td>
        <td><strong>{!! number_format($client->tax_amount,2) !!}</strong></td>
        <td><strong>{{ $client->currency->symbol}}{!! number_format($client->amount+$client->tax_amount,2) !!}</strong></td>


        <td>
            @if ( $client->isEditable() || $client->canChangePermissions() )
            <a href="{!! route('admin.expenses.edit', $client->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
            @else
            <i class="fa fa-edit text-muted" title="{{ trans('admin/clients/general.error.cant-edit-this-client') }}"></i>
            @endif

            &nbsp;&nbsp;
            @if ( $client->isDeletable() )
            <a href="{!! route('admin.expenses.confirm-delete', $client->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
            @else
            <i class="fa fa-trash text-muted" title="{{ trans('admin/clients/general.error.cant-delete-this-client') }}"></i>
            @endif
        </td>
        <?php
        $total_taxable+=$client->amount;
        $total_tax+=$client->tax_amount;
        $total+=$client->amount+$client->tax_amount;
        ?>

    </tr>
    @endforeach
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th style="text-align: right">Total</th>
        <th>{{env('app_currency')}} {{number_format($total_taxable,2)}}</th>
        <th>{{env('app_currency')}} {{number_format($total_tax,2)}}</th>
        <th>{{env('app_currency')}} {{number_format($total,2)}}</th>
        <th></th>
    </tr>
</tbody>
</table>

                </div> <!-- table-responsive -->
                <div style="text-align: center;"> {!! $clients->appends(\Request::except('page'))->render() !!} </div>

            </div><!-- /.box-body -->
        </div><!-- /.box -->
        {!! Form::close() !!}
    </div><!-- /.col -->

</div><!-- /.row -->



<div class="modal fade" id="announcement_show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
@include('partials._date-toggle')
<!-- DataTables -->
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkClient[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }
    $('.date-toggle-nep-eng1').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true
            , allowInputToggle: true,

        });
    $('.date-toggle-nep-eng1').nepalidatetoggle();
    $('.select2').select2();

</script>



<script>
    $(function() {
        $('#clients-table').DataTable({
            pageLength: 35
            , ordering: false
        });
    });

</script>

@endsection
