@extends('layouts.master')
@section('content')

<style>
    .required { color: red; }
    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
    }
    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title ?? "Page Title" !!}

                <small>{!! $page_description ?? "Page Description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                <ul class="nav nav-tabs">
                    <li @if(!\Request::segment(3)) class="active" @endif><a href="#all_department" data-toggle="tab" aria-expanded="true">All Departments</a>
                    </li>
                    <li @if(\Request::segment(3)) class="active" @endif><a href="#new_department" data-toggle="tab" aria-expanded="false">New Department</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content bg-white">
                <div class="tab-pane @if(!\Request::segment(3)) active @endif" id="all_department" style="position: relative;">
                   <!-- NESTED-->
                   <div class="box" style="" data-collapsed="0">
                      <div class="box-body">
                         <!-- Table -->
                         <div class="row">
                            @foreach($departments as $dk => $dv)
                            <div class="col-sm-6">
                               <div class="box-heading">
                                  <div class="box-title">
                                     <h4>
                                        {{ $dv->deptname }}                                   
                                        <div class="pull-right" style="margin-right: 10px;">
                                           <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                            <a href="/admin/departments/{{ $dv->departments_id }}/edit" class="btn btn-primary btn-xs" title="Edit" data-toggle="modal" data-placement="top" data-target="#myModal"><span class="fa fa-edit"></span></a>

                                            <a href="/admin/departments/delete/{{ $dv->departments_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                        </div>
                                     </h4>
                                  </div>
                               </div>
                               <!-- Table -->   
                               <table class="table table-bordered table-hover">
                                  <thead>
                                     <tr>
                                        <td class="text-bold col-sm-1">#</td>
                                        <td class="text-bold"></td>
                                        <td class="text-bold col-sm-2">Action</td>
                                     </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($dv->designation as $dgk => $dgv)
                                     <tr>
                                        <td>{{ $dgk+1 }}</td>
                                        <td>{{ $dgv->designations }}</td>
                                        <td style="text-align:right;">
                                           <a href="/admin/departments/edit/{{ $dv->departments_id }}/{{ $dgv->designations_id }}" class="btn btn-primary btn-xs" title="" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                           <a href="/admin/departments/delete_designation/{{ $dgv->designations_id }}" class="btn btn-danger btn-xs" title="" data-toggle="tooltip" data-placement="top" onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                     </tr>
                                     @endforeach
                                  </tbody>
                               </table>
                            </div>
                            @endforeach
                         </div>
                      </div>
                   </div>
                </div>

                <div class="tab-pane @if(\Request::segment(3)) active @endif" id="new_department" style="position: relative;">
                    <form action="/admin/departments/store" method="post" class="form-horizontal form-groups-bordered" id="save_department">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Select Department <span class="text-danger">*</span>
                                  </label>
                                    <div class="col-lg-8">
                                        <select class="form-control select_box" style="width: 100%" name="departments_id" id="departments_id">
                                          <option value="">New Department</option>
                                          @foreach($departments as $dk => $dv)
                                          <option value="{{ $dv->departments_id }}" @if(isset($designation) && $designation->departments_id == $dv->departments_id) selected="selected" @endif>{{ $dv->deptname }}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group new_stocks" id="new_department_div" @if(\Request::segment(5)) style="display: none;" @endif>
                                    <label class="col-sm-4 control-label">New Department <span class="required">*</span>
                                      </label></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="deptname" id="deptname" class="form-control new_stocks" value="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class=" col-sm-4 control-label" id="designation_label">Designation <span class="required" style="display: none;">*</span>
                                      </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="designations" id="designations" class="form-control" value="@if(isset($designation)){{ $designation->designations }}@endif" />
                                    </div>
                                </div>
                                @if(\Request::segment(5))
                                <input type="hidden" name="designations_id" class="form-control" value="{{ \Request::segment(5) }}" />
                                @endif
                                <div class="form-group margin">
                                    <div class="col-sm-offset-4 col-sm-8">
                                        <button type="button" class="btn btn-primary btn-block" id="create_department_btn">@if(\Request::segment(5)) Update @else Create @endif</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
        $('.select_box').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('#departments_id').on('change', function() {
            $('#deptname').css('border', '1px solid #ccc');
            $('#designations').css('border', '1px solid #ccc');
            if($(this).val() == '')
            {
                $('#new_department_div').css('display', 'block');
                $('#designation_label span').css('display', 'none');
            }
            else
            {
                $('#new_department_div').css('display', 'none');
                $('#designation_label span').css('display', 'block');
                $('#designation_label span').css('float', 'right');
            }
        });
    });

    $('#create_department_btn').click(function () {
        var flag = 0;
        $('#deptname').css('border', '1px solid #ccc');
        $('#designations').css('border', '1px solid #ccc');

        var departments_id = $('#departments_id').val();
        if(departments_id == '')
        {
            if($('#deptname').val().trim() == '')
            {
                flag++;
                $('#deptname').css('border', '1px solid red');
            }
        }
        else
        {
            if($('#designations').val().trim() == '')
            {
                flag++;
                $('#designations').css('border', '1px solid red');
            }
        }
        if(flag == 0)
            $('#save_department').submit();
        else
            return false;
    });
</script>
@endsection
