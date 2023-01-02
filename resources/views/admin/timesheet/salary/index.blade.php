@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title ?? 'page_title' !!}

                <small>{!! $page_description ?? 'page_description' !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

  <div class='row'> 
        <div class='col-md-12'> 
            <!-- Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        &nbsp;
                        <a class="btn btn-social btn-foursquare" href="{!! route('admin.timesheetsalary.create') !!}" title="Create Activity">
                            <i class="fa fa-plus"></i>Add Salary Templates
                        </a>
                    </div>
                   <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="orders-table">

                                <thead>
                                    <tr>
                                        <th style="text-align: center;">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>Id</th>
                                        <th>Salary Grade</th>
                                        <th>Salary/Hours</th>
                                        <th>OverTime/Hours</th>
                                        <th>Others salary</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                            @foreach($timesheetsalary as $ts)
                                <tr>
                                    <td style="text-align: center;">
                                     @if ( $ts->enabled )
                                        <a href="{!! route('admin.timesheetsalary.enable', $ts->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle-o enabled"></i></a>
                                    @else
                                        <a href="{!! route('admin.timesheetsalary.enable', $ts->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                    @endif
                                    </td>
                                    <td>#{{$ts->id}}</td>
                                    <td>{{ucwords($ts->salary_grade)}}</td>
                                    <td>{{$ts->salary_per_hour}}</td>
                                    <td>{{$ts->overtime_salary_per_hour}}</td>
                                    <td>{{$ts->other_salary_per_hour}}</td>
                                    <td>
                                    @if ( $ts->isEditable()  )
                                    <a href="{!! route('admin.timesheetsalary.edit', $ts->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                    @else
                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/communication/general.error.cant-edit-this-organization') }}"></i>
                                    @endif
                                    &nbsp;
                                    @if($ts->isDeletable())
                                    <a href="#" onClick="confirmdelete('{{$ts->id}}')" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a>
                                    @else
                                    asdsad
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/organization/general.error.cant-delete-this-organization') }}"></i>
                                    @endif
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
        <script type="text/javascript">
            function confirmdelete(id){
                let c = confirm('Are you sure you want to delete TimeSheet Salary Templates with id ' + id);
                if(c){
                   location.href = `/admin/timesheetsalary/delete/${id}`;
                }
                else{
                    return false;
                }

            }
        </script>
@endsection