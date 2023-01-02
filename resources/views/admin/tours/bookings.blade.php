@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {{-- Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->

        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="FiscalYear-table">
                        <thead>
                            <tr class="bg-info">
                                <th style="text-align: center">
                                    ID
                                </th>
                                <th>Tour</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Email</th>
                                <th>Number</th>
                                 <th>Currency</th>
                                <th>Total Amount</th>
                                <th>Paid By</th>
                                <th>User</th>
                                <th>Total People</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tour_bookings as $v)
                            <tr>
                                <td align="center">
                                    {{$v->id}}
                                </td>
                                <td><a href="/admin/tourbookings/{{$v->id}}">{!! $v->tour->tour_title !!}</a></td>
                                <td>{!! $v->first_name !!} {!! $v->last_name !!}</td>
                                <td>{{$v->tour_date}}</td>
                                <td>{{ $v->email}}</td>
                                <td>{{ $v->contact_no}}</td>
                                <td>{{ $v->currency}}</td>
                                <td>{{ $v->total_amount}}</td>
                                
                                <td>{{ $v->paid_by}}</td>
                                <td>{{ $v->user->username}}</td>
                                <td>A:{{$v->adult_no}} C:{{$v->child_no}} I:{{$v->infant_no}}  = T:{{$v->adult_no + $v->child_no + $v->infant_no }}</td>
                                <td>
                                    @if($v->status == 'confirmed')
                                         <span class="label label-info">{{ucwords($v->status)}}</span> 
                                    @elseif($v->status == 'paid')
                                          <span class="label label-success">{{ucwords($v->status)}}</span> 
                                    @else 
                                     <span class="label label-primary">{{ucwords($v->status)}}</span> 
                                    @endif
                                </td>
                                <td>
                                     <a href="/tourbooking/download/pdf/{{$v->id}}" title="PDF"><i class="fa fa-download"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $tour_bookings->render() !!}
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
        checkboxes = document.getElementsByName('chkFiscalYear[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

</script>


@endsection
