@extends('layouts.master')
@section('content')
@php 
  $salaryTemplate = isset($salaryTemplate) ? $salaryTemplate : null;
@endphp
<style>
    .required { color: red; }
    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
        margin-bottom: 10px;
    }
    .panel-custom {
        box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.15);
    }

    .btn-purple, .btn-purple:hover {
        color: #ffffff;
        background-color: #7266ba;
        border-color: transparent;
    }

    .show_print { display: none; }

    .mr, #DataTables_length { margin-right: 10px !important; }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Salary List
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p> Employee salary list of the organization</p>

          {{ TaskHelper::topSubMenu('topsubmenu.payroll')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">

        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                <ul class="nav nav-tabs">
                    <li @if(!\Request::segment(4)) class="active" @endif><a href="#template_list" data-toggle="tab" aria-expanded="true">Salary Template List</a>
                    </li>
                    <li @if(\Request::segment(4)) class="active" @endif><a href="#new_template" data-toggle="tab" aria-expanded="false">Set Salary Template</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content bg-white">

                <div class="tab-pane @if(!\Request::segment(4)) active @endif" id="template_list" style="position: relative;">
                   <!-- NESTED-->
                   <div class="box" style="" data-collapsed="0">
                      <div class="box-body">
                         <!-- Table -->
                         <div class="row">
                            <div class="col-sm-12">
                               <table class="table table-bordered table-hover" id="DataTables">
                                  <thead>
                                     <tr>
                                        <td class="text-bold col-sm-1">#</td>
                                        <td class="text-bold">Salary Grades</td>
                                        <td class="text-bold">Basic Salary</td>
                                        <td class="text-bold">Overtime <small>(Per Hour)</small></td>
                                        <td class="text-bold col-sm-2">Action</td>
                                     </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($templates as $tk => $tv)
                                     <tr>
                                        <td>{{ $tk+1 }}</td>
                                        <td>{{ $tv->salary_grade }}</td>
                                        <td>{{ $tv->basic_salary }}</td>
                                        <td>{{ $tv->overtime_salary }}</td>
                                        <td style="text-align:right;">
                                           <a href="/admin/payroll/salary_template_details/{{ $tv->salary_template_id }}" class="btn btn-info btn-xs" title="" data-toggle="modal" data-target="#template_show" data-placement="top" data-original-title="Show"><i class="fa fa-list"></i></a>
                                           <a href="/admin/payroll/salary_template/{{ $tv->salary_template_id }}" class="btn btn-primary btn-xs" title="" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                           <a href="/admin/payroll/salary_template_delete/{{ $tv->salary_template_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                     </tr>
                                     @endforeach
                                  </tbody>
                               </table>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>

                <div class="tab-pane @if(\Request::segment(4)) active @endif" id="new_template" style="position: relative;">
                    <form action="/admin/payroll/salary_template" method="post" class="form-horizontal form-groups-bordered" id="save_template">
                        {{ csrf_field() }}
                        <?php
                          $total_salary = 0;
                          $total_allowance = 0;
                          $total_deduction = 0;
                          $gratuity_salary = 0;
                          if(isset($salaryTemplate)){
                            $total_salary = $salaryTemplate->basic_salary;
                            $gratuity_salary = $salaryTemplate->gratuity_salary;
                          }

                        ?>
                        <div class="row">
                            <div class="form-group" id="border-none">
                                <label for="field-1" class="col-sm-3 control-label">Salary Grades<span class="required"> *</span></label>
                                <div class="col-sm-5">
                                    <input type="text" name="salary_grade" value="@if($salaryTemplate){{ $salaryTemplate->salary_grade }}@endif" class="form-control" required="" placeholder="Enter Salary Grades" data-parsley-id="4">
                                </div>
                            </div>
                            <div class="form-group" id="border-none">
                                <label for="field-1" class="col-sm-3 control-label">Basic Salary<span class="required"> *</span></label>
                                <div class="col-sm-5">
                                    <input type="text" data-parsley-type="number" name="basic_salary" value="@if($salaryTemplate){{ $salaryTemplate->basic_salary }}@endif" class="salary form-control" required="" placeholder="Enter Basic Salary" data-parsley-id="6" id='basic_salary_input'>
                                </div>
                            </div>
                            <div class="form-group" id="border-none">
                                <label for="field-1" class="col-sm-3 control-label">Gratuity Salary<span class="required"></span></label>
                                <div class="col-sm-5">
                                    <input type="text" data-parsley-type="number" 
                                    name="gratuity_salary" value="@if($salaryTemplate){{ $salaryTemplate->gratuity_salary }}@endif" class="salary form-control" required="" placeholder="Gratuity Salary with {{ env('GRATUITY_PERCENT')}} %" data-parsley-id="9" readonly=""
                                    id='gratuity_salary_input'>
                                </div>
                            </div>
                            <div class="form-group" id="border-none">
                                <label for="field-1" class="col-sm-3 control-label">Overtime Rate <small> ( Per Hour)</small>
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" data-parsley-type="number" name="overtime_salary" value="@if($salaryTemplate){{ $salaryTemplate->overtime_salary }}@endif" class="form-control" placeholder="Enter Overtime Rate" data-parsley-id="8">
                                </div>
                            </div>
                            <div class="form-group" id="border-none">
                                <label for="field-1" class="col-sm-3 control-label">Sick Leave <small> ( Per Day)</small>
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" data-parsley-type="number" name="sick_salary" value="@if($salaryTemplate){{ $salaryTemplate->sick_salary }}@endif" class="form-control" placeholder="Enter Sick Leave Rate" data-parsley-id="8">
                                </div>
                            </div>
                            <div class="form-group" id="border-none">
                                <label for="field-1" class="col-sm-3 control-label">Annual Leave<small> ( Per Day)</small>
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" data-parsley-type="number" name="annual_leave_salary" value="@if($salaryTemplate){{ $salaryTemplate->annual_leave_salary }}@endif" class="form-control" placeholder="Enter Annual Leave Rate" data-parsley-id="8">
                                </div>
                            </div>
                            <div class="form-group" id="border-none">
                                <label for="field-1" class="col-sm-3 control-label">Public Holidays<small> ( Per Day)</small>
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" data-parsley-type="number" name="public_holiday_salary" value="@if($salaryTemplate){{ $salaryTemplate->public_holiday_salary }}@endif" class="form-control" placeholder="Enter Public Holidays Rate" data-parsley-id="8">
                                </div>
                            </div>
                               <div class="form-group" id="border-none">
                                <label for="field-1" class="col-sm-3 control-label">Others<small> ( Per Day)</small>
                                </label>
                                <div class="col-sm-5">
                                    <input type="text" data-parsley-type="number" name="other_leave_salary" value="@if($salaryTemplate){{ $salaryTemplate->other_leave_salary }}@endif" class="form-control" placeholder="Enter Others Leave Rate" data-parsley-id="8">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="panel panel-custom">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <strong>Allowances / TADA</strong>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                      @if(!\Request::segment(4))
                                        <div class="">
                                            <label class="control-label">House Rent Allowance </label>
                                            <input type="text" data-parsley-type="number" name="house_rent_allowance" value="" class="salary form-control" data-parsley-id="10">
                                        </div>
                                        <div class="">
                                            <label class="control-label">Medical Allowance </label>
                                            <input type="text" data-parsley-type="number" name="medical_allowance" value="" class="salary form-control" data-parsley-id="12">
                                        </div>
                                      @endif

                                      
                                      @if($salaryTemplate)
                                        <?php $allowances = \PayrollHelper::getSalaryAllowance($salaryTemplate->salary_template_id); ?>

                                        @if(!sizeof($allowances))
                                          <div class="">
                                              <label class="control-label">House Rent Allowance </label>
                                              <input type="text" data-parsley-type="number" name="house_rent_allowance" value="" class="salary form-control" data-parsley-id="10">
                                          </div>
                                          <div class="">
                                              <label class="control-label">Medical Allowance </label>
                                              <input type="text" data-parsley-type="number" name="medical_allowance" value="" class="salary form-control" data-parsley-id="12">
                                          </div>
                                        @else
                                          @foreach($allowances as $ak => $av)
                                          <div class="">
                                              <input type="text" style="margin:5px 0px;height: 28px;width: 56%;" class="form-control" name="allowance_label[]" value="{{ $av->allowance_label }}" data-parsley-id="10">
                                              <input type="text" data-parsley-type="number" name="allowance_value[]" value="{{ $av->allowance_value }}" class="salary form-control" data-parsley-id="12">
                                              <input type="hidden" name="salary_allowance_id[]" value="{{ $av->salary_allowance_id }}" class="form-control">
                                          </div>
                                          <?php $total_allowance += $av->allowance_value; ?>
                                          @endforeach
                                        @endif
                                      @endif
                                        <div id="add_new">
                                        </div>
                                        <div class="margin">
                                            <strong><a href="javascript:void(0);" id="add_more" class="addCF"><i class="fa fa-plus"></i>&nbsp;Add More</a></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ********************Allowance End ******************-->

                            <!-- ************** Deduction Panel Column  **************-->
                            <div class="col-sm-6">
                                <div class="panel panel-custom">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <strong>Deductions</strong>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                      @if(!\Request::segment(4))
                                        <div class="">
                                            <label class="control-label">Provident Fund </label>
                                            <input type="text" data-parsley-type="number" name="provident_fund" value="" class="deduction form-control" data-parsley-id="14">
                                        </div>
                                        <div class="">
                                            <label class="control-label">Tax Deduction </label>
                                            <input type="text" data-parsley-type="number" name="tax_deduction" value="" class="deduction form-control" data-parsley-id="16">
                                        </div>
                                      @endif

                                        @if($salaryTemplate)
                                          <?php $deductions = \PayrollHelper::getSalaryDeduction($salaryTemplate->salary_template_id); ?>

                                          @if(!sizeof($deductions))
                                            <div class="">
                                                <label class="control-label">Provident Fund </label>
                                                <input type="text" data-parsley-type="number" name="provident_fund" value="" class="deduction form-control" data-parsley-id="14">
                                            </div>
                                            <div class="">
                                                <label class="control-label">Tax Deduction </label>
                                                <input type="text" data-parsley-type="number" name="tax_deduction" value="" class="deduction form-control" data-parsley-id="16">
                                            </div>
                                          @else
                                            @foreach($deductions as $dk => $dv)
                                            <div class="">
                                                <input type="text" style="margin:5px 0px;height: 28px;width: 56%;" class="form-control" name="deduction_label[]" value="{{ $dv->deduction_label }}" data-parsley-id="10">
                                                <input type="text" data-parsley-type="number" name="deduction_value[]" value="{{ $dv->deduction_value }}" class="deduction form-control" data-parsley-id="12">
                                                <input type="hidden" name="salary_deduction_id[]" value="{{ $dv->salary_deduction_id }}" class="form-control">
                                            </div>
                                            <?php $total_deduction += $dv->deduction_value; ?>
                                            @endforeach
                                          @endif
                                        @endif
                                        <div id="add_new_deduc">
                                        </div>
                                        <div class="margin">
                                            <strong><a href="javascript:void(0);" id="add_more_deduc" class="addCF"><i class="fa fa-plus"></i>&nbsp;Add More</a></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ****************** Deduction End  *******************-->
                            <!-- ************** Total Salary Details Start  **************-->
                        </div>
                        <div class="row">
                            <div class="col-md-8 pull-right">
                                <div class="panel panel-custom">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <strong>Total Salary Details</strong>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-bordered custom-table">
                                            <tbody>
                                              
                                                <tr>
                                                    <!-- Sub total -->
                                                    <th class="col-sm-8 vertical-td"><strong>Total  Allowance :</strong>
                                                    </th>
                                                    <td class="">
                                                        <input type="text" name="" disabled="" value="{{ $total_allowance }}" id="allowance" class="form-control" data-parsley-id="18">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <!-- Sub total -->
                                                    <th class="col-sm-8 vertical-td"><strong>Gratuity salary :</strong>
                                                    </th>
                                                    <td class="">
                                                        <input type="text" name="" disabled="" value="{{ $gratuity_salary }}" id="gratuity_salary" class="form-control" data-parsley-id="18">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <!-- Sub total -->
                                                    <th class="col-sm-8 vertical-td"><strong>Gross Salary :</strong>
                                                    </th>
                                                    <td class="">
                                                        <input type="text" name="" disabled="" value="{{ $total_salary + $total_allowance + $gratuity_salary}}" id="total" class="form-control" data-parsley-id="18">
                                                    </td>
                                                </tr>
                                                <!-- / Sub total -->
                                                <tr>
                                                    <!-- Total tax -->
                                                    <th class="col-sm-8 vertical-td"><strong>Total Deduction :</strong></th>
                                                    <td class="">
                                                        <input type="text" name="" disabled="" value="{{ $total_deduction }}" id="deduc" class="form-control" data-parsley-id="20">
                                                    </td>
                                                </tr>
                                                <!-- / Total tax -->
                                                <tr>
                                                    <!-- Grand Total -->
                                                    <th class="col-sm-8 vertical-td"><strong>Net Salary :</strong>
                                                    </th>
                                                    <td class="">
                                                        <input type="text" name="" disabled="" required="" value="{{ $total_salary + $total_allowance - $total_deduction + $gratuity_salary }}" id="net_salary" class="form-control" data-parsley-id="22">
                                                    </td>
                                                </tr>
                                                <!-- Grand Total -->
                                            </tbody>
                                        </table>
                                        <!-- Order Total table list start -->

                                    </div>
                                </div>
                            </div>
                            @if(\Request::segment(4))
                            <input type="hidden" name="salary_template_id" class="form-control" value="{{ \Request::segment(4) }}" />
                            @endif

                            <div class="clearfix"></div>
                            <div class="col-sm-2 margin pull-right">
                                <a href="/admin/payroll/salary_template" type="button" class="btn btn-default btn-block">Cancel</a>
                            </div>

                            <div class="col-sm-6 margin pull-right">
                                <button type="submit" class="btn btn-primary btn-block" id="create_template_btn">@if(\Request::segment(4)) Update @else Create @endif</button>
                            </div>
                            
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="template_show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/dataTables.buttons.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/jszip.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/pdfmake.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/vfs_fonts.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/buttons.html5.min.js") }}"></script>
<script src="{{ asset("/bower_components/admin-lte/plugins/datatables/buttons.print.min.js") }}"></script>

<script type="text/javascript">
    $(document).ready(function () {

        // To add new allowance type
        var maxAppend = 0;
        $("#add_more").click(function () {
            if (maxAppend >= 100) {
                alert("Maximum 100 File is allowed");
            } else {
                var add_new = $('<div class="row">\n\
                    <div class="col-sm-12"><input type="text" name="allowance_label[]" style="margin:5px 0px;height: 28px;width: 56%;" class="form-control"  placeholder="Enter Allowances Label" required ></div>\n\
                <div class="col-sm-9"><input  type="text" data-parsley-type="number" name="allowance_value[]" placeholder="Enter Allowances Value" required  value=""  class="salary form-control"></div>\n\
                <div class="col-sm-3"><strong><a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div></div>');
                maxAppend++;
                $("#add_new").append(add_new);
            }
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });

        // To add new Deduction type
        $("#add_more_deduc").click(function () {
            if (maxAppend >= 100) {
                alert("Maximum 100 File is allowed");
            } else {
                var add_new = $('<div class="row">\n\
                  <div class="col-sm-12"><input type="text" name="deduction_label[]" style="margin:5px 0px;height: 28px;width: 56%;" class="form-control" placeholder="Enter Deductions Label" required></div>\n\
              <div class="col-sm-9"><input  type="text" data-parsley-type="number" name="deduction_value[]" placeholder="Enter Deductions Value" required  value=""  class="deduction form-control"></div>\n\
              <div class="col-sm-3"><strong><a href="javascript:void(0);" class="remCF_deduc"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div></div>');
                maxAppend++;
                $("#add_new_deduc").append(add_new);
            }
        });

        $("#add_new_deduc").on('click', '.remCF_deduc', function () {
            $(this).parent().parent().parent().remove();
        });

        $(document).on('click', '#close_modal', function () {
            $('#template_show').modal('hide');
        });
    });


    $(document).on("change", function () {
        var sum = 0;
        var deduc = 0;
        $(".salary").each(function () {
            sum += +$(this).val();
        });

        $(".deduction").each(function () {
            deduc += +$(this).val();
        });
        var ctc = $("#ctc").val();

        $("#total").val(sum);
        $("#deduc").val(deduc);
        var net_salary = 0;
        net_salary = sum - deduc;
        $("#net_salary").val(net_salary);
    });



    $(function() {
        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
        
        // Setting for Datatables
        var total_header = ($('table#DataTables th:last').index());
        var testvar = [];
        for (var i = 0; i < total_header; i++) {
            testvar[i] = i;
        }
        var length_options = [10, 25, 50, 100];
        var length_options_names = [10, 25, 50, 100];

        var tables_pagination_limit =25;
        tables_pagination_limit = parseFloat(tables_pagination_limit);

        if ($.inArray(tables_pagination_limit, length_options) == -1) {
            length_options.push(tables_pagination_limit)
            length_options_names.push(tables_pagination_limit)
        }
        length_options.sort(function (a, b) {
            return a - b;
        });
        length_options_names.sort(function (a, b) {
            return a - b;
        });
        var tbl = $('#DataTables');
        $("[id^=DataTables]").dataTable({
            'paging': true,  // Table pagination
            'responsive': true,  // Table pagination
            "pageLength": tables_pagination_limit,
            "aLengthMenu": [length_options, length_options_names],
            'ordering': true,  // Column ordering
            'dom': 'lBfrtip',  // Bottom left status text
            buttons: [
                {
                    extend: 'print',
                    text: "<i class='fa fa-print'> </i>",
                    className: 'btn btn-success btn-xs mr',
                    exportOptions: {
                        modifier: {
                            //selected: true,
                            columns: [testvar[0], testvar[1], testvar[2], testvar[3], testvar[4], testvar[5]]
                        },
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel"></i>',
                    className: 'btn btn-purple mr btn-xs',
                    exportOptions: {
                        modifier: {
                            //selected: true,
                            columns: [testvar[0], testvar[1], testvar[2], testvar[3], testvar[4], testvar[5]]
                        },
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-excel"></i>',
                    className: 'btn btn-primary mr btn-xs',
                    exportOptions: {
                        modifier: {
                            //selected: true,
                            columns: [testvar[0], testvar[1], testvar[2], testvar[3], testvar[4], testvar[5]]
                        },
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    className: 'btn btn-info mr btn-xs',
                    exportOptions: {
                        modifier: {
                            //selected: true,
                            columns: [testvar[0], testvar[1], testvar[2], testvar[3], testvar[4], testvar[5]]
                        },
                        columns: [ 0, 1, 2, 3 ]
                    },
                    orientation:'landscape',
                    customize : function(doc){
                        var colCount = new Array();
                        $(tbl).find('tbody tr:first-child td').each(function(){
                            if($(this).attr('colspan')){
                                for(var i=1;i<=$(this).attr('colspan');$i++){
                                    colCount.push('*');
                                }
                            }else{ colCount.push('*'); }
                        });
                        doc.content[1].table.widths = colCount;
                    }
                },
            ],
            /*columnDefs: [
              {
                targets: -1,
                visible: false
              }
            ], */
            select: true,
            // Text translation options
            // Note the required keywords between underscores (e.g _MENU_)
            oLanguage: {
                sSearch: 'Search all columns:',
                sLengthMenu: '_MENU_',
                info: 'Showing page _PAGE_ of _PAGES_',
                zeroRecords: 'Nothing found - sorry',
                infoEmpty: 'No records available',
                infoFiltered: '(filtered from _MAX_ Total records)'
            }

        });

    });

  /* $('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
    // location.reload();
  }); */

  $('input#basic_salary_input').keyup(function(){  
    let gratuity_percent = "{{ env('GRATUITY_PERCENT')}}";
    let gratuity_value = Number(gratuity_percent) / 100;
    let gratuity_salary = Number($(this).val() * gratuity_value);
    $('input#gratuity_salary_input').val(gratuity_salary.toFixed(2));
  });
</script>
@endsection
