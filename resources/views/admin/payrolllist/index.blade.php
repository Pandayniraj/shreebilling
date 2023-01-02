@extends('layouts.master')
@section('content')

<style>
    .required { color: red; }
    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
        margin-bottom: 10px;
    }

    .btn-purple, .btn-purple:hover {
        color: #ffffff;
        background-color: #7266ba;
        border-color: transparent;
    }

    .show_print { display: none; }
    .mr, #DataTables_length { margin-right: 10px !important; }
</style>

 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               List Payment
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<div class="pull-right">
   <a href="/admin/payroll/create_payroll" class="btn btn-primary">Create Payroll</a>
</div>
<br>
<br>

<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">

                <table class="table table-striped table-bordered" id="DataTables">

                  <thead>
                  <tr class="bg-primary">
                      <td class="text-bold col-sm-1" style="width: 5%">S.N</td>
                      <td class="text-bold">Year</td>
                      <td class="text-bold">Month</td>
                      <td class="text-bold">Division</td>
                      <td class="text-bold">Department</td>
                      <td class="text-bold text-center">Total Employee</td>
                      <td class="text-bold text-center">Total Amount</td>
                      <td class="text-bold">Created On</td>
                      <td class="text-bold">Action</td>
                  </tr>
                  </thead>
                   <tbody>
                       @foreach($payroll as $index=>$pv)
                           	<tr>
                               		<td>{{$index+1}}.</td>
                                    <?php
                                     $chosen_date = explode('-', $pv->date);
                                     $year = $chosen_date[0];
                                     $month = $chosen_date[1];
                                $monthsName = ['Baisakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangsir', 'Paush', 'Magh', 'Falgun', 'Chaitra'];

                                ?>
                               		<td>{{$year}}</td>
                               		<td>{{$monthsName[$month-1]}}</td>
                               		<td>{{$pv->division->name}}</td>
                               		<td>{{$pv->department->deptname}}</td>
                               		<td class="text-center">{{$pv->total_employee}}</td>
                               		<td class="text-center">{{$pv->total_amount}}</td>
                               		<td>{{date('d M, Y',strtotime($pv->created_at))}}</td>
                                    <td>
                                        <?php

                                        ?>
                                        <a href="/admin/payroll/show-detail/{{ $pv->id }}" title="View payroll" ><i class="fa fa-eye text-success"></i> </a>
                                     <a href="/admin/payroll/edit/{{ $pv->id }}" title="Edit payroll" ><i class="fa fa-pencil text-primary"></i> </a>
                                        <a href="{!! route('admin.payroll.confirm-delete', $pv->id) !!}" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-trash-o deletable text-danger"></i></a>

                                    </td>
                           	</tr>
                        @endforeach
                   </tbody>
               </table>
                <div class="center">

                    {!!  $payroll->appends(\Request::except('page'))->render() !!}

                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="payment_show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">

        </div>
    </div>
</div>
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')

<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>



<script type="text/javascript">
    $(function() {

        $('#payment_month').datetimepicker({
            format: 'YYYY-MM',
            sideBySide: true
        });

        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();



    });
</script>
@endsection
