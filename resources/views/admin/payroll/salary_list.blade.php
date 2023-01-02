@extends('layouts.master')
@section('content')

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
                Salary List & History
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            <p> Employee salary list of the organization. You may change the salary by clicking edit button.</p>

          {{ TaskHelper::topSubMenu('topsubmenu.payroll')}}


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">

        <div class="box">
                    <div class="box-header with-border">

                <div class="panel-title">
                    <strong>Employee Salary List</strong>
                </div>
            </div>

            <table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="DataTables">
              <thead>
                 <tr>
                    <td class="text-bold col-sm-1">EMP ID</td>
                    <td class="text-bold">Name</td>
                    <td class="text-bold">Designation</td>
                    <td class="text-bold">Basic Salary</td>
                    <td class="text-bold col-sm-1">Overtime</td>
                    <td class="text-bold">Details</td>
                    <td class="text-bold col-sm-2">Action</td>
                 </tr>
              </thead>
              <tbody>
                @foreach($salaryLists as $sk => $sv)
                @if($sv->user)
                 <tr>
                    <td class="col-sm-1">{{ $sv->user_id }}</td>
                    <?php $user = $sv->user; ?>
                    <td>{{ $user->first_name.' '.$user->last_name }}</td>
                    <?php $template = $sv->template; ?>
                    <td>{{ $template->salary_grade??'' }}</td>
                    <td>{{ $template->basic_salary??'' }}</td>
                    <td>{{ $template->overtime_salary??'' }}</td>
                    <td class="col-sm-1">
                        <a href="/admin/payroll/salary_details/{{ $sv->payroll_id }}" class="btn btn-info btn-xs" title="" data-toggle="modal" data-target="#modal_dialog" data-placement="top" data-original-title="Show"><i class="fa fa-list"></i></a>
                    </td>
                    <td style="text-align:right;">
                       <a href="/admin/payroll/manage_emp_salary_template/{{$sv->payroll_id}}" class="btn btn-primary btn-xs" title="" d data-placement="top" data-original-title="Edit"
                        data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-edit"></i></a>
                       <a href="/admin/payroll/salary_delete/{{ $sv->payroll_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                    </td>
                 </tr>
                 @endif
                 @endforeach
              </tbody>
           </table>

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
            "order": [[ 1, "asc" ]],
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
                        columns: [ 0, 1, 2, 3, 4 ]
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
                        columns: [ 0, 1, 2, 3, 4 ]
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
                        columns: [ 0, 1, 2, 3, 4 ]
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
                        columns: [ 0, 1, 2, 3, 4 ]
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
                sSearch: 'Search:',
                sLengthMenu: '_MENU_',
                info: 'Showing page _PAGE_ of _PAGES_',
                zeroRecords: 'Nothing found - sorry',
                infoEmpty: 'No records available',
                infoFiltered: '(filtered from _MAX_ Total records)'
            }

        });

    });
   $(document).on('hidden.bs.modal', '#modal_dialog' , function(e){
        $('#modal_dialog .modal-content').html('');    
   });
  /* $('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
    // location.reload();
  }); */
</script>
@endsection
