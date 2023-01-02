@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{$page_title ?? "Page Title"}}
        <small>{{$page_description ?? "Page Description"}}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>
<form method="post" action="{{route('admin.appraisal.store')}}">
    {{ csrf_field() }}
    <div class="panel panel-custom">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-horizontal form-groups-bordered">
                        <div class="form-group">
                            <label for="employee_id" class="col-sm-3 control-label">Employee <span class="required text-red">*</span></label>

                            <div class="col-sm-5">
                                <select required name="employee_id" id="employee_id" class="form-control select_box">

                                    <option value="">Select Employee</option>
                                    @foreach($department as $dep)
                                    <optgroup label="{{$dep->deptname}}">
                                        <?php $emp = \App\User::select('users.id', 'users.username', 'tbl_designations.designations', 'users.designations_id')->leftjoin('tbl_designations', 'tbl_designations.designations_id', '=', 'users.designations_id')->where('users.departments_id', $dep->departments_id)->get();
                                        ?>
                                        @foreach($emp as $e)
                                        <option value="{{$e->id}}">
                                            {{ucfirst(trans($e->username))}}({{$e->designations}})
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="evaluator_id" class="col-sm-3 control-label">Evaluator <span class="required text-red">*</span></label>

                            <div class="col-sm-5">
                                <select required name="evaluator_id" id="evaluator_id" class="form-control select_box">

                                    <option value="">Select Evaluator</option>
                                    @foreach($department as $dep)
                                    <optgroup label="{{$dep->deptname}}">
                                        <?php $emp = \App\User::select('users.id', 'users.username', 'tbl_designations.designations', 'users.designations_id')->leftjoin('tbl_designations', 'tbl_designations.designations_id', '=', 'users.designations_id')->where('users.departments_id', $dep->departments_id)->get();
                                        ?>
                                        @foreach($emp as $e)
                                        <option value="{{$e->id}}">
                                            {{ucfirst(trans($e->username))}}({{$e->designations}})
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach

                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="appraisal_month" class="col-sm-3 control-label">Month <span class="required text-red">*</span></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input required="" type="text" class="form-control date_in" value="{{ isset($selecteddate) ? $selecteddate : '' }}" name="appraisal_month" id="appraisal_month">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="template_id" class="col-sm-3 control-label">Template</label>
                            <div class="col-sm-5">
                                <select name="template_id" id="template_id" class="form-control select_box">
                                    <option value="">Select Template</option>
                                    @foreach($templates as $tm)
                                    <option value="{{$tm->id}}"> {{ucfirst($tm->name)}}</option>
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
                            </div>
                            <div class="box-body">
                                @foreach($aotv->objectives as $obk => $obv)
                                <br />
                                <div class="row">
                                    <div class="form-group" id="border-none">
                                        <span class="col-sm-8">{{ $obv->objective }}</span>
                                        <div class="col-sm-4">
                                            <select name="appraisal_marks[{{$aotv->id}}][{{$obv->id}}]" class="form-control" required>
                                                @foreach($qw as $key=>$tm)
                                                    <option value="{{round(($key*$obv->marks*0.2), 2).':'.$key}}">{{$tm}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
                <span id="template_data"></span>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="panel panel-custom">
                        <div class="col-md-4">
                            <label for="line_manager_comment" class="control-label">Line Manager Comment</label>
                            <textarea class="form-control" name="line_manager_comment" id="line_manager_comment" placeholder="Write Comment"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="line_manager_score" class="control-label">Line Manager Score</label>
                            <input type="number" class="form-control date_in" value="" name="line_manager_score" id="line_manager_score">
                        </div>
                        <div class="col-md-4">
                            <label for="line_manager_rating" class="control-label">Line Manager Rating</label>
                            <input type="number" class="form-control date_in" value="" name="line_manager_rating" id="line_manager_rating">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    <div class="form-group">
                        <button class="btn btn-primary" id="btn-submit-edit" type="submit">Create</button>
                        <a class="btn btn-default" href="/admin/appraisals"> Cancel </a>
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
        $('#appraisal_month').datetimepicker({
            format: 'YYYY-MM',
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