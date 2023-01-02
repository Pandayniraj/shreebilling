@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        POS Outlets
        <small>{{$description}}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<form method="post" action="{{route('admin.pos-outlets.update',$edit->id)}}">

    {{ csrf_field() }}

    <div class="panel panel-custom">
        <div class="panel-heading">

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-12">Outlet Code</label>
                        <div class="input-group ">
                            <input type="text" name="outlet_code" placeholder="Outlet Code" id="name" value="{{ $edit->outlet_code }}" class="form-control" required>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-stack-exchange"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-12"> Name</label>
                        <div class="input-group ">
                            <input type="text" name="name" placeholder="Name" id="name" value="{{ $edit->name }}" class="form-control" required>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-stack-exchange"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-12">Short Name</label>
                        <div class="input-group ">
                            <input type="text" name="short_name" placeholder="Short Name" id="name" value="{{ $edit->short_name }}" class="form-control" required>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-stack-exchange"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-12">Outlet Type</label>
                        <div class="input-group ">
                            <input type="text" name="outlet_type" placeholder="Outlet Type" id="name" value="{{ $edit->outlet_type }}" class="form-control" required>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-stack-exchange"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-12">FNB Outlet</label>
                        <div class="input-group ">

                            {!! Form::select('fnb_outlet', ['0'=>'No', '1'=>'Yes'], $edit->fnb_outlet, ['class' => 'form-control']) !!}

                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-stack-exchange"></i></a>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-12">Ledger Id</label>
                        <div class="input-group ">
                            <input type="number" name="ledger_id" placeholder="Ledger Id" id="ledger_id" value="{{$edit->ledger_id}}" class="form-control" min='0' required>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-stack-exchange"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-12">Bill Printer</label>
                        <div class="input-group ">
                            <input type="text" name="bill_printer" placeholder="Bill Printer" id="bill_printer" class="form-control" value="{{$edit->bill_printer}}">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-stack-exchange"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-12">Kot Printer</label>
                        <div class="input-group ">
                            <input type="text" name="kot_printer" placeholder="Kot Printer" id="ledger_id" class="form-control" value="{{$edit->kot_printer}}">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-stack-exchange"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-12">Bill Printer Port</label>
                        <div class="input-group ">
                            <input type="text" name="bill_printer_port" placeholder="Bill Printer Port" id="bill_printer_port" class="form-control" value="{{$edit->bill_printer_port}}">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-stack-exchange"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-12">Kot Printer Port</label>
                        <div class="input-group ">
                            <input type="text" name="kot_printer_port" placeholder="Kot Printer Port" id="kot_printer_port" class="form-control" value="{{$edit->kot_printer_port}}">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-stack-exchange"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label class="control-label col-sm-12">Bot Printer</label>
                    <div class="input-group ">
                        <input type="text" name="bot_printer" placeholder="Bot Printer" id="bot_printer_port" class="form-control" value="{{$edit->bot_printer}}">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-stack-exchange"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <label class="control-label col-sm-12">Bot Printer Port</label>
                    <div class="input-group ">
                        <input type="text" name="bot_printer_port" placeholder="Bot Printer Port" id="bot_printer_port" class="form-control" value="{{$edit->bot_printer_port}}">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-stack-exchange"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Enabled<i class="imp">*</i></label>
                        <input type="hidden" name="enabled" value="0">
                        {!! Form::checkbox('enabled', '1', $edit->enabled) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-primary" id="btn-submit-edit" type="submit">Update</button>
                        <a class="btn btn-default" href="{{ route('admin.pos-outlets.index') }}"> Cancel </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

@endsection
