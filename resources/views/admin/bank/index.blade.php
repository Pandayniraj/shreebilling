@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
@endsection
<style type="text/css">
    .andp-datepicker-container{
        z-index: 10000000 !important;
    }
</style>
@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<?php setlocale(LC_MONETARY, 'en_NP'); ?>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
           	Banking and Income
                <small>{!! $page_descriptions !!}</small>
            </h1>
            <p> Create a Bank, Petty Cash and other fund accounts where you can deposit money. Income entries goes here by selecting the bank accounts</p>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
            <b><font size="4">Bank and Cash Account List</font></b>
            <div style="display: inline; float: right;">
                 <a href="{!! route('admin.bank.income.add') !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}" class="btn btn-success btn-xs" id='open_modal' data-backdrop="static" data-keyboard="false">Add Income Transaction</a>
            <a class="btn btn-primary btn-xs"  title="Create New Bank" href="{{route('admin.bank.create')}}">
                            &nbsp;&nbsp;+ Create New Account   
                        </a>
            </div>
        </div>
</div>
<br/>
<table class="table table-hover table-no-border table-striped" id="leads-table">
<thead>
    <tr class="btn-info">
        
        <th>Account Number</th>
        <th>Account Name</th>
        <th>Currency</th>
        <th>Bank Name</th>
        <th>Amount</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @foreach($account as $key=>$acc)
    <tr>
       
        
        <td>{{$acc->account_number}}</td>
        <td style="font-size:16px" ><a href="{{route('admin.bank.show',$acc->id)}}" 
        class="text-uppercase"><strong>{{$acc->account_name}}</a></strong></td>

        <td>{{$acc->currency}}</td>
        <td>{{$acc->bank_name}}</td>
        <td style="font-size:16px">{{env('APP_CURRENCY')}} 
                           {{ TaskHelper::getLedgerBalance($acc->ledger_id)['ledger_balance'] }}</td>
        <td>
            @if( $acc->isEditable())<a href="{{route('admin.bank.edit',$acc->id)}}"><i class="fa fa-edit"></i></a>
            @else
             <i class="fa fa-pencil-square-o text-muted" title="{{ trans('admin/permissions/general.error.cant-edit-this-permission') }}"></i>
            @endif
        </td>
   </tr>

    @endforeach
</tbody>

</table>
<div align="center">{!! $account->render() !!}</div>

<div class="row">
                    <div class="col-md-12">
                        <div class="wrap" style="margin-top:5px;">
                            <form method="get" action="/admin/bank/">
                                <div class="filter form-inline" style="margin:0 40px 0 0;">
                                    
                                    {!! Form::date('start_date', \Request::get('start_date'), ['style' => 'width:150px;', 'class' => 'form-control input-sm date-toggle-nep-eng1', 'id'=>'start_date', 'placeholder'=>'Start Date','autocomplete' =>'off']) !!}&nbsp;&nbsp;
                                     {!! Form::date('end_date', \Request::get('end_date'), ['style' => 'width:150px;', 'class' => 'form-control input-sm date-toggle-nep-eng1', 'id'=>'end_date', 'placeholder'=>'End Date','autocomplete' =>'off']) !!}&nbsp;&nbsp;

                                    {!! Form::select('tags', $tag, \Request::get('tags'), ['id'=>'', 'class'=>'form-control select-mini select2','placeholder'=>'Select Tags', 'style'=>'width:200px; display:inline-block;']) !!}&nbsp;&nbsp;

                                    {!! Form::select('income_type', $types, \Request::get('income_type'), ['id'=>'income_type1', 'class'=>'form-control select-mini select2','placeholder'=>'Select Income Type', 'style'=>'width:200px; display:inline-block;']) !!}&nbsp;&nbsp;

                                    
                                    <button class="btn btn-primary" id="btn-submit-filter" type="submit">
                                        <i class="fa fa-list"></i> Filter
                                    </button>
                                    <a href="/admin/bank/" class="btn btn-danger" id="btn-filter-clear" >
                                        <i class="fa fa-close"></i> Clear
                                    </a>
                                </div>
                            </form>
                            
                           
                        </div>
                    </div>
                </div>



<div class="row">
                    <div class="col-md-12">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped" id="clients-table">
                                <caption>Latest Income Transaction</caption>
                                <thead class="bg-maroon">
                                    <tr>
                                        <th>Id</th>
                                        <th>Num</th>
                                        <th>Reference</th>
                                        <th>Tag</th>
                                        <th>Account</th>
                                        <th>Income Type</th>
                                        <th>Amount Deposited</th>
                                        <th>Date</th>
                                        <th>Fiscal Year</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($income as $i)
                                    <tr>
                                        <td>{{\FinanceHelper::getAccountingPrefix('INCOME_PRE')}}{{$i->id}}</td>
                                        <td> 
            <a target="_blank" href="/admin/entries/show/{{\FinanceHelper::get_entry_type_label($i->entry->entrytype_id )}}/{{$i->entry->id}}">{{$i->entry->number}}</a>
        </td>
                                        <td title="{{$i->description}}">{{$i->reference_no}}</td>
                                        <td title=>{{$i->tag->name}}</td>
                                        <td title=>{{$i->banckAcc->account_name}}</td>
                                        <td style="font-size:16px !important">
                                            
                                                {{$types[$i->income_type]}}
                                                <small>
                                                    @if(strlen($i->customers->name) > 25) {{ substr($i->customers->name,0,25).'...' }} @else {{ ucfirst($i->customers->name) }} @endif
                                                </small>
                                            
                                        </td>
                                        <td style="font-size:16px !important" >{{ env('APP_CURRENCY') }} 
                                        {{number_format($i->amount,2)}}
                                        
                                        </td>
                                        <td>{{date('dS Y M',strtotime($i->date_received))}}</td>
                                        <td>{{ $i->fiscalyear->fiscal_year }}</td>
                                        <td>{{$i->user->username}}</td>
                                         <td>
                                            @if($i->isEditable())
                                            <a href="{{route('admin.bank.income.edit',$i->id)}}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.edit') }}" class="btn btn-primary btn-xs" id='open_modal'><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                            @else
                                            <i class="fa fa-edit"></i>
                                            @endif

                                            <a href="{{route('admin.bank.income.pdf',$i->id)}}" title="Download PDF" class="btn btn-default btn-xs" id=''><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                                            @if($i->income_type == 'sales_without_invoice' || $i->income_type =='customer_payment')
                                            <a href="{{route('admin.bank.income.mail',$i->id)}}" title="Send By Mail" class="btn btn-default btn-xs" id=''><i class="fa fa-envelope"></i></a>&nbsp;&nbsp;
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
</div>

@include('partials._body_bottom_submit_client_edit_form_js')
<link rel="stylesheet" href="/nepali-date-picker/nepali-date-picker.min.css">
<script type="text/javascript" src="/nepali-date-picker/nepali-date-picker.js"> </script>
<script type="text/javascript">
    $(document).on('hidden.bs.modal', '#modal_dialog', function(e) {
        $('#modal_dialog .modal-content').html('');
    });

</script>
@endsection

@section('body_bottom')
@include('partials._date-toggle')
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

@endsection
