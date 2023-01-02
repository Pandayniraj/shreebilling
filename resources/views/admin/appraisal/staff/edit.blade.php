@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{$page_title ?? "Page Title"}}
        <small>{{$page_description ?? "Page Description"}}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>
<form method="post" action="{{route('admin.staff.appraisal.edit',$appraisal->id)}}">
    {{ csrf_field() }}
    <div class="panel panel-custom">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-horizontal form-groups-bordered">
                        <div class="form-group">
                            <label for="employee_id" class="col-sm-3 control-label">Employee <span class="required text-red">*</span></label>
                            <div class="col-sm-5">
                                <select required name="employee_id" id="employee_id" class="form-control select_box" @if($appraisal->entry_by != \Auth::user()->id || (int)$appraisal->marks_by_employee != 0) disabled @endif>
                                    <option value="">Select Employee</option>
                                    @foreach($department as $dep)
                                    <optgroup label="{{$dep->deptname}}">
                                        @php
                                            if(\Auth::user()->hasRole('admins'))
                                                $emp = \App\User::select('users.id', 'users.username', 'tbl_designations.designations', 'users.designations_id')->leftjoin('tbl_designations', 'tbl_designations.designations_id', '=', 'users.designations_id')->where('users.departments_id', $dep->departments_id)->where('users.id', '!=', \Auth::user()->id)->get();
                                            else
                                                $emp = \App\User::select('users.id', 'users.username', 'tbl_designations.designations', 'users.designations_id')->leftjoin('tbl_designations', 'tbl_designations.designations_id', '=', 'users.designations_id')->where('users.departments_id', $dep->departments_id)->where('users.id', '!=', \Auth::user()->id)->where('users.first_line_manager', \Auth::user()->id)->get();
                                        @endphp
                                        @foreach($emp as $e)
                                        <option value="{{$e->id}}" @if($appraisal->employee_id == $e->id) selected @endif>
                                            {{ucfirst(trans($e->username))}}({{$e->designations}})
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="appraisal_from" class="col-sm-3 control-label">Date From<span class="required text-red">*</span></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input required="" type="text" class="form-control date_in" value="{{ $appraisal->appraisal_from }}" name="appraisal_from" id="appraisal_from">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="appraisal_to" class="col-sm-3 control-label">Date To<span class="required text-red">*</span></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input required="" type="text" class="form-control date_in" value="{{ $appraisal->appraisal_to }}" name="appraisal_to" id="appraisal_to">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="template_id" class="col-sm-3 control-label">Template</label>
                            <div class="col-sm-5">
                                <select name="template_id" id="template_id" class="form-control select_box" @if($appraisal->entry_by != \Auth::user()->id || (int)$appraisal->marks_by_employee != 0) disabled @endif>
                                    <option value="">Select Template</option>
                                    @foreach($templates as $tm)
                                    <option value="{{$tm->id}}" @if($appraisal->template_id==$tm->id) selected @endif> {{ucfirst($tm->name)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($apprisalObjTypes as $aotk => $aotv)
                    @if($aotv->objectives->count())
                    <div class="col-sm-6">
                        <div class="panel panel-custom">
                            <div class="bg-info box-header">
                                <span class="panel-title pull-left">{{ $aotv->name }}</span>
                                <span class="panel-title pull-right">
                                    <span class="badge bg-yellow">{{ $aotv->points }}</span>
                                    Marks
                                </span>
                                <input type="hidden" name="marks[]" value="{{ $aotv->points }}">
                            </div>
                            <div class="box-body" style="padding-left: 0;padding-right: 0;">
                                @foreach($aotv->objectives as $obk => $obv)
                                <div class="row" style="padding: 1rem 0.2rem;border: 1px solid #eee;margin-bottom: 1rem !important;margin: 0;">
                                    <div class="form-group" id="border-none">
                                        <span class="col-sm-8">{{ $obv->objective }}</span>
                                        <div class="col-sm-4">
                                            @php $mark = ''; $comment = ''; @endphp
                                            @if(isset($appraisal->selfAppraisalData))
                                                @foreach($appraisal->selfAppraisalData as $adk => $adv)
                                                    @php
                                                        //dd($adv->appraisal_data_field.' '.$aotv->id.'|'.$adv->appraisal_data_field_key.' '.$obv->id.'|'.$adv->appraisal_by.' '.\Auth::user()->id);
                                                        if($adv->appraisal_data_field == $aotv->id && $adv->appraisal_data_field_key == $obv->id )
                                                        {
                                                            $mark = $adv->appraisal_point?:'';
                                                            $comment = $adv->comment;
                                                            break;
                                                        }
                                                    @endphp
                                                @endforeach
                                            @endif
                                            <input type="number" class="form-control date_in" min="1" max="{{$obv->marks}}" value="{{ $mark }}" placeholder="Mark max {{$obv->marks}}" name="appraisal_marks[{{$aotv->id}}][{{$obv->id}}]" required>
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 1rem;">
                                            <textarea name="comments[{{$aotv->id}}][{{$obv->id}}]" class="form-control" placeholder="Enter your comment" required>{{ $comment }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
                <span id="template_data">
                    @if($appraisal->template)
                        <div class="clearfix"></div>
                        @foreach($appraisal->template->appraisalTempType as $aott => $aottv)
                            @if($aottv->objectiveType->count())
                            <div class="col-sm-6">
                                <div class="panel panel-custom">
                                    <div class="bg-info box-header">
                                        <span class="panel-title pull-left">{{ $aottv->objectiveType->name }}</span>
                                        <span class="panel-title pull-right">
                                            <span class="badge bg-yellow">{{ $aottv->objectiveType->points }}</span>
                                            Marks {{ $aottv->objectiveType->id }}
                                        </span>
                                        <input type="hidden" name="marks[]" value="{{ $aottv->objectiveType->points }}">
                                    </div>
                                    <div class="box-body">
                                        @foreach($aottv->objectiveType->objectives as $obk => $obv)
                                        <br />
                                        <div class="row">
                                            <div class="form-group" id="border-none">
                                                <span class="col-sm-8">{{ $obv->objective }}</span>
                                                <div class="col-sm-4">
                                                    @php $mark = ''; $comment = ''; @endphp
                                                    @if(isset($appraisal->selfAppraisalData))
                                                        @foreach($appraisal->selfAppraisalData as $adk => $adv)
                                                            @php
                                                                //dd($adv->appraisal_data_field.' '.$aottv->objectiveType->id.'|'.$adv->appraisal_data_field_key.' '.$obv->id.'|'.$adv->appraisal_by.' '.\Auth::user()->id);
                                                                if($adv->appraisal_data_field == $aottv->objectiveType->id && $adv->appraisal_data_field_key == $obv->id && $adv->appraisal_by == \Auth::user()->id)
                                                                {
                                                                    //dd($adv->appraisal_data_field.' '.$aottv->id.'|'.$adv->appraisal_data_field_key.' '.$obv->id.'|'.$adv->appraisal_by.' '.\Auth::user()->id);
                                                                    $mark = $adv->appraisal_point?:'';
                                                                    $comment = $adv->comment;
                                                                    break;
                                                                }
                                                            @endphp
                                                        @endforeach
                                                    @endif
                                                    <input type="number" class="form-control date_in" min="1" max="{{$obv->marks}}" value="{{ $mark }}" placeholder="Mark max {{$obv->marks}}" name="appraisal_marks[{{$aottv->objectiveType->id}}][{{$obv->id}}]" required>
                                                </div>
                                                <div class="col-sm-12" style="margin-top: 1rem;">
                                                    <textarea name="comments[{{$aottv->objectiveType->id}}][{{$obv->id}}]" class="form-control" placeholder="Enter your comment" required>{{ $comment }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @endif
                </span>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <br>
                    <div class="form-group">
                        @if($appraisal->form_level!=2)
                        <button class="btn btn-primary" id="btn-submit-edit" type="submit">Update</button>
                        @endif
                        <a class="btn btn-default" href="/admin/staff-appraisals"> Cancel </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<!-- Timepicker -->
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/timepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/timepicker.js") }}" type="text/javascript"></script>

<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

<script type="text/javascript">
    $(function() {
        $('#appraisal_from').datetimepicker({
            format: 'YYYY-MM-DD',
            sideBySide: true
        });
        $('#appraisal_to').datetimepicker({
            format: 'YYYY-MM-DD',
            sideBySide: true
        });

        $('#template_id').on('change', function() {
            if($(this).val() != '')
            {
                $.get('/admin/appraisal_template/'+$(this).val(),function(response){
                    console.log(response);
                    $('#template_data').html(response.data);
                });
            }
            else
                $('#template_data').html('');
        })
    });
</script>
@endsection
