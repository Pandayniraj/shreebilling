@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               {{$page_title ?? "Page Title"}}
                <small>{{$page_description ?? "Page Description" }}</small>
            </h1>
            <p> This is useful if you are registered in VAT</p>
            Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>


    <div class='row'>

        <div class='col-md-12'>

            <!-- Box -->
            {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">

                         <a class="btn btn-primary btn-sm pull-right"  title="Create new invoice" href="{{ route('admin.assembly.create') }}">
                            <i class="fa fa-plus"></i>&nbsp;<strong> Create new Assembly</strong>
                        </a> 
                      
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                           
                            <table class="table table-hover table-bordered table-striped" id="orders-table">
                                <thead>
                                    <tr class="bg-danger">
                                        <th>
                                         Assembly Number 
                                        </th>
                                        <th>Date</th>
                                        <th>Product Code</th>
                                        <th>Status</th>
                                        
                                        <th>Source</th>
                                         <th>Destination</th>
                                        
                                         <th>Quantity</th>
                                         <th>Cost</th>
                                         <th>Tools</th>
                                         <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($assembly) && !empty($assembly)) 
                                    @foreach($assembly as $o)
                                        <tr>
                                            <td>#{{ $o->id}}</td>
                                            <td>#{{ $o->assemble_by }}</td>
                                             <td><a href="/admin/assembly/{{$o->id}}">{{ $o->productname->name }}</a></td>
                                             <td>
                                                @if($o->status == 'completed')
                                                   <label class="label label-success"> {!! strtoupper($o->status) !!}</label>
                                                    @else
                                                 <label class="label label-warning"> {!! strtoupper($o->status) !!}</label>
                                                 @endif
                                            </td>
                                            <td>{{ $o->sourcename->location_name}}</td>
                                             <td>{{ $o->destinationname->location_name}}</td>
                                            <td>{{ $o->assembled_quantity}}</td>
                                            <td>{!! number_format($o->total_amount,2) !!}</td>
                                           
                                            <td>
                                                <a href="/admin/assembly/print/{{$o->id}}" target="_blank" title="print"><i class="fa fa-print"></i></a>
                                                <a href="/admin/assembly/pdf/{{$o->id}}" title="download"><i class="fa fa-download"></i></a>
                                            </td>
                                            <td>
                                                   <a href="{!! route('admin.assembly.edit', $o->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                   <a href="{!! route('admin.assembly.confirm-delete', $o->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkClient[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>

   
@endsection
