@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<style>
	select { width:200px !important; }
label {
    font-weight: 600 !important;
}

 .intl-tel-input { width: 100%; }
 .intl-tel-input .iti-flag .arrow {border: none;}


.selecticons{
    margin-left: 10px !important;
}
</style>


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_descriptions ?? "Page description" !!}</small>
                <small id='ajax_status'></small>
            </h1> {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">
                <form method="post" action="{{ route('admin.bank.update',$account->id) }}">
                    {{ csrf_field() }}
                    <h3>Account Info.</h3>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="control-label">Account Name</label>
                            <div class="input-group">
                                <input type="text" name="account_name" placeholder="Account Name" class="form-control" required="" value="{{$account->account_name}}">
                                <div class="input-group-addon">
                                <a href="#"><i class="fa  fa-user"></i></a>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-4 form-group">
                            <label class="control-label">Account Code</label>
                            <div class="input-group">
                                <input type="text" name="account_code" placeholder="Account Code" class="form-control"  value="{{$account->account_code}}">
                                <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-code"></i></a>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-4 form-group">
                            <label class="control-label">Account Number</label>
                            <div class="input-group">
                                <input type="text" name="account_number" placeholder="Account Number" class="form-control" value="{{$account->account_number}}" required="">
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
                                <input type="text" name="bank_name" placeholder="Bank Name" class="form-control"  value="{{$account->bank_name}}">
                                <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-bank"></i></a>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-4 form-group">
                            <label class="control-label">Currency</label>
                            <div class="input-group">
                                <input type="text" name="currency" placeholder="Currency" class="form-control" value="{{$account->currency}}">
                                <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-money"></i></a>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-4 form-group">
                            <label class="control-label">Routing Number</label>
                            <div class="input-group">
                                <input type="text" name="routing_number" placeholder="Routing Number" class="form-control" value="{{$account->routing_number}}">
                                <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-road"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                      
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="control-label">Descriptions</label>
                            <div class="input-group">
                                <textarea name="description" placeholder="Account Descriptions" class="form-control">{!! $account->description !!}</textarea>
                                <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-sticky-note-o"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                 <div class="row">
                    <div class="col-md-6">
                    <button type="submit" class="btn btn-primary" id='btn-submit-edit'>Update Account</button>
                    <a href="/admin/bank/" class="btn btn-default">Cancel</a>

                </div>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection