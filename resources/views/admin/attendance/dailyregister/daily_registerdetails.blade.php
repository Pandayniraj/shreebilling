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
                Web Attendance Report
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
             <span> Overall web report is <a target="_blank" href="/admin/all_attendance_report">here</a></span><br/>

           {{ TaskHelper::topSubMenu('topsubmenu.hr')}}

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>Attendance Report</strong>
                         &nbsp;
                      

                </div>

            </div>
            <div class="panel-body">
                <form id="filter_form" role="form" enctype="multipart/form-data" 
                action="{{route('admin.shiftAttendance.filter')}}" method="post" class="form-horizontal form-groups-bordered" >
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label class="control-label col-sm-3" >Organization:</label>
                      <div class="col-md-5">          
                        {!! Form::select('org_id',$org,null,['placeholder'=>'All Organization','class'=>'form-control searchable','id'=>'org']) !!}
                      </div>
                    </div>
                     <div class="form-group">
                      <label class="control-label col-sm-3" >Departments:</label>
                      <div class="col-md-5">          
                        {!! Form::select('dep_id',$departments,null,['placeholder'=>'All Departments','class'=>'form-control searchable','id'=>'departments']) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-sm-3" >Deginations:</label>
                      <div class="col-md-5">          
                        {!! Form::select('deg_id',$deginations,null,['placeholder'=>'All Deginations','class'=>'form-control searchable',
                        'id'=>'deginations']) !!}
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-sm-3" >Teams:</label>
                      <div class="col-md-5">          
                        {!! Form::select('teams',$teams,null,['placeholder'=>'All Teams','class'=>'form-control searchable','id'=>'teams']) !!}
                      </div>
                    </div>

                     <div class="form-group">
                      <label class="control-label col-sm-3" >Project:</label>
                      <div class="col-md-5">          
                        {!! Form::select('project',$projects,null,['placeholder'=>'All Project','class'=>'form-control searchable','id'=>'projects']) !!}
                      </div>
                    </div>

                     <div class="form-group">
                        <label for="date_in" class="col-sm-3 control-label">Date<span class="required">*</span></label>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in date-toggle" value="{{ isset($start_date) ? $start_date : '' }}" name="start_date" placeholder="Start Date...">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                         <div class="col-sm-3">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in date-toggle" value="{{ isset($end_date) ? $end_date : '' }}" name="end_date" 
                                placeholder="End Date...">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5 ">
                            <button type="submit"  class="btn btn-primary"
                           id='filter_submit'>Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    
<div class="result">
    <div align="center" style="display: none;" id='filter_spinner'>
      <i class="fa fa-spinner fa-spin" style="zoom: 2;"></i>
    </div>
    <div id='filter_result'>
    </div>
</div>

    

@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
@include('partials._date-toggle')
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>


<!-- SELECT2-->
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>

<script type="text/javascript">
      $('.date-toggle').nepalidatetoggle()
    $(function() {
        $('.date_in').datetimepicker({
            format: 'YYYY-MM-DD',
            sideBySide: true
        });

        $('.searchable').select2({
            theme: 'bootstrap',
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#departments').change(function(){
        var el = $(this);
        var toel = $('#deginations');
        let id = el.val();
        $('.searchable').select2('destroy');
        var options = `<option value=''>All Deginations</option>`;
        toel.html(options);
        
        
        $.get(`/admin/attendance_report/detail?type=dep&value=${id}`,function(response){
            let data = response.data;
            console.log(data);
            for(let d of data){
                options += `<option value='${d.designations_id}'>${d.designations}</option>`;
            }
            toel.html(options); 
             
            $('.searchable').select2({
                theme: 'bootstrap',
            });
        }).fail(function(){
            el.val('');
            alert('Failed To load designations');
        });
    }); 

    $('#org').change(function(){
        var el = $(this);
        var toel = {
           deg: $('#deginations'),
           dep: $('#departments'),
           team: $('#teams'),
           proj: $('#projects')
        };
        let id = el.val();
        $('.searchable').select2('destroy');
        var options = {
          deg:  `<option value=''>All Deginations</option>`,
          dep:  `<option value=''>All Departments</option>`,
          team:  `<option value=''>All Teams</option>`,
          proj:  `<option value=''>All Project</option>`,
        };
        toel.deg.html(options.deg);
        toel.dep.html(options.dep);
        toel.team.html(options.team);
        toel.proj.html(options.proj);
        toappend = ['departments','teams','projects'];
        $.get(`/admin/attendance_report/detail?type=org&value=${id}`,function(response){
           
            let departments = response.departments;
            let teams = response.teams;
            let projects = response.projects;
            for(let d of departments){
            options.dep += `<option value='${d.departments_id}'>${d.deptname}</option>`;
            }
            toel.dep.html(options.dep)
            for(let d of projects){
                options.proj += `<option value='${d.id}'>${d.name}</option>`;
            }
            toel.proj.html(options.proj);
            for(let d of teams){
                options.team += `<option value='${d.id}'>${d.name}</option>`;
            }
            toel.team.html(options.team);
            $('.searchable').select2({
                theme: 'bootstrap',
            });
            
        }).fail(function(){
            el.val('');
            alert('Failed To load data');
        });
    }); 

    var _canSubmitAjax = true;

    $(document).on('click','#DownloadExcel',function(){
        _canSubmitAjax = false;
        alert("To DO");
        return false;
        $('#filter_form').submit();
    });
    $('#filter_form').submit(function(ev){

        if(!_canSubmitAjax){
            _canSubmitAjax = true;
            return true; //noa ajax submit
        }
        //return true;
        $('#filter_result').html('');
        $('#filter_spinner').show();
        let token = $('#filter_form input[name=_token]').val();
        let data = {
          _token: token,
          org_id:  $('#filter_form select[name=org_id]').val(),
          dep_id: $('#filter_form select[name=dep_id]').val(),
          deg_id:  $('#filter_form select[name=deg_id]').val(),
          project: $('#filter_form select[name=project]').val(),
          teams: $('#filter_form select[name=teams]').val(),
          start_date: $('#filter_form input[name=start_date').val(),
          end_date: $('#filter_form input[name=end_date').val(),
        };
        console.log(data)
        ev.preventDefault();
        $.post(`/admin/attendance_report/detail`,data,function(response){
           $('#filter_spinner').hide();
           $('#filter_result').html(response.result)
        }).fail(function(){
          alert("failed");
        })
        return false;
    });

</script>
@endsection
