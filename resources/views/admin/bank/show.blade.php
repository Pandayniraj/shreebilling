@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
@endsection

@section('content')
<style>
    select {
        width: 200px !important;
    }

    label {
        font-weight: 600 !important;
    }

    .intl-tel-input {
        width: 100%;
    }

    .intl-tel-input .iti-flag .arrow {
        border: none;
    }


    .selecticons {
        margin-left: 10px !important;
    }

  .andp-header select{
    padding: 0px !important;
  }

</style>
<style type="text/css">
.select-mini {
  font-size: 10px;
  height: 25px;
  width: 90px;
}
 
</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{!! $page_descriptions ?? "Page description" !!}</small>
        <small id='ajax_status'></small>
    </h1> {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">

                {{ csrf_field() }}
                <h3>{{$account->account_name}} <small>Balance Amount &nbsp; &nbsp; {{env('APP_CURRENCY')}} {{ TaskHelper::getLedgerBalance($account->ledger_id)}}

                    </small>
                    <span style="float: right;">
                        <a href="{!! route('admin.bank.income', $account->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}" class="btn btn-primary btn-xs" id='open_modal' data-backdrop="static" data-keyboard="false">Add Income Transaction</a>
                        @if($account->isEditable())
                        <a href="{{route('admin.bank.edit',$account->id)}}" class="btn btn-danger btn-xs" id='btn-submit-edit'>Edit Account</a>
                        @else
                        <button class="btn btn-primary btn-xs" id='btn-submit-edit' disabled="">Update Account</button>
                        @endif
                        <a href="/admin/bank/" class="btn btn-default btn-xs">Cancel</a>
                    </span>
                </h3>
                <div class="row">
                    <div class="col-md-12">
                        <p> {!! $account->description !!} </p>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="control-label">Account Name</label>
                        <div class="input-group">
                            <input type="text" name="account_name" placeholder="Account Name" class="form-control" required="" value="{{$account->account_name}}" readonly="">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa  fa-user"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="control-label">Account Code</label>
                        <div class="input-group">
                            <input type="text" name="account_code" placeholder="Account Code" class="form-control" required="" value="{{$account->account_code}}" readonly="">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-code"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="control-label">Account Number</label>
                        <div class="input-group">
                            <input type="text" name="account_number" placeholder="Account Number" class="form-control" value="{{$account->account_number}}" readonly="">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa  fa-file-text-o"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="control-label">Bank Name</label>
                        <div class="input-group">
                            <input type="text" name="bank_name" placeholder="Bank Name" class="form-control" value="{{$account->bank_name}}" readonly="">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-bank"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="control-label">Currency</label>
                        <div class="input-group">
                            <input type="text" name="currency" placeholder="Currency" class="form-control" value="{{$account->currency}}" readonly="">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-money"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="control-label">Routing Number</label>
                        <div class="input-group">
                            <input type="text" name="routing_number" placeholder="Routing Number" class="form-control" value="{{$account->routing_number}}" readonly="">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-road"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php   $cal = new \App\Helpers\NepaliCalendar(); ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="wrap" style="margin-top:5px;">
                            <form method="get" action="/admin/bank/{{$account->id}}/show">
                                <div class="filter form-inline" style="margin:0 40px 0 0;">
                                    
                                    {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:150px;', 'class' => 'form-control input-sm date-toggle-nep-eng1', 'id'=>'start_date', 'placeholder'=>'Start Date','autocomplete' =>'off']) !!}&nbsp;&nbsp;
                                     {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:150px;', 'class' => 'form-control input-sm date-toggle-nep-eng1', 'id'=>'end_date', 'placeholder'=>'End Date','autocomplete' =>'off']) !!}&nbsp;&nbsp;

                                    {!! Form::select('tags', $tag, \Request::get('tags'), ['id'=>'', 'class'=>'form-control select-mini select2','placeholder'=>'Select Tags', 'style'=>'width:200px; display:inline-block;']) !!}&nbsp;&nbsp;

                                    {!! Form::select('income_type', $types, \Request::get('income_type'), ['id'=>'income_type1', 'class'=>'form-control select-mini select2','placeholder'=>'Select Income Type', 'style'=>'width:200px; display:inline-block;']) !!}&nbsp;&nbsp;

                                    
                                    <button class="btn btn-primary" id="btn-submit-filter" type="submit">
                                        <i class="fa fa-list"></i> Filter
                                    </button>
                                    <a href="/admin/bank/{{$account->id}}/show" class="btn btn-danger" id="btn-filter-clear" >
                                        <i class="fa fa-close"></i> Clear
                                    </a>
                                </div>
                            </form>
                            <div class="box-tools pull-right">
                                <a href="/admin/download/bank/{{$account->id}}/pdf/index" class="btn btn-info btn-xs float-right" style="margin-right: 20px;">PDF Download</a>
                                &nbsp;&nbsp;&nbsp;
                                <a href="/admin/download/bank/{{$account->id}}/excel/index" class="btn btn-success btn-xs float-right" style="margin-right: 5px;">Excel Download</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                           
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped" id="clients-table">
                                <caption>Income Transaction</caption>
                                <thead class="btn-info">
                                    <tr>
                                        <th>Id</th>
                                        <th>Reference</th>
                                        <th>Tags</th>
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
                                        <td title="{{$i->description}}">{{mb_substr($i->reference_no,0,12)}}</td>
                                        <td>{{$i->tag->name}}</td>
                                        <td style="max-width: 140px;font-size:16px">
                                            
                                                {{$types[$i->income_type]}}
                                                <small>
                                                    @if(strlen($i->customers->name) > 25) {{ substr($i->customers->name,0,25).'...' }} @else {{ ucfirst($i->customers->name) }} @endif
                                                </small>
                                            
                                        </td>
                                        <td>{{ env('APP_CURRENCY') }} @money($i->amount)</td>
                                        <td>{{date('d M y',strtotime($i->date_received))}} / {{ $cal->formated_nepali_from_eng_date($i->date_received) }}</td>
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
                <div align="center">{!! $income->render() !!}</div>
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
