@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {!! $page_title ?? "Leave Year" !!}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
              Current Leave Year: <strong>{{ TaskHelper::cur_leave_yr()->leave_year ?? 'NOT SET'}}</strong>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.leaveyear.enable-selected', 'id' => 'frmFiscalYearList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                       @if(\Auth::user()->hasRole('admins'))
                        <a class="btn btn-default btn-sm" href="{!! 
                            route('admin.leaveyear.create') !!}" title="{{ trans('admin/fiscalyear/general.button.create') }}">
                            <i class="fa fa-plus-square"></i> &nbsp;Add
                        </a>
                        @endif
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmFiscalYearList'].action = '{!! route('admin.leaveyear.enable-selected') !!}';  document.forms['frmFiscalYearList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>
                       

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="FiscalYear-table">
                                <thead>
                                    <tr class="bg-maroon">
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>Leave Year</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th> Action </th>
                                    </tr>
                                </thead> 
                                
                                <tbody>
                    @foreach($allLeaveYear as $v)
                                      
                        <?php  $class = $v->current_year ? 'bg-success':''; ?>
                        <tr  >
                            <td align="center" class="{{$class}}">
                                <input type="radio" name="chkLeaveYear" value="{{$v->id}}" @if($v->current_year) checked @endif> 
                                <!-- {!! Form::radio('chkLeaveYear[]', $v->id); !!} -->
                            </td>
                            <td class="{{$class}}">{!! $v->leave_year !!}</td>
                            <td class="{{$class}}"> {{ $v->start_date}}</td>
                            <td class="{{$class}}"> {{ $v->end_date}}</td>
                            <td class="{{$class}}">
                                @if( $v->isEditable() || $v->canChangePermissions() )
                                    <a href="{!! route('admin.leaveyear.edit', $v->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                @else
                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-edit-this-FiscalYear') }}"></i>
                                @endif
                                @if( $v->isDeletable() )
                                    <a href="{!! route('admin.leaveyear.confirm-delete', $v->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                @else
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/fiscalyear/general.error.cant-delete-this-FiscalYear') }}"></i>
                                @endif
                            </td>
                        </tr>
                                      

                                    @endforeach
                                </tbody>
                            </table>
                            {!! $allLeaveYear->render() !!}
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
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>

    <script>
    $(function() {
        $('#FiscalYear-table').DataTable({
            
        });
    });
    </script>

@endsection
