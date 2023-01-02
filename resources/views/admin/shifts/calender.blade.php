@extends('layouts.master')
@section('content')
@php $filter_type = isset($filter_type) ? $filter_type : null; @endphp
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title"}} 
                <small> {{ $page_description ?? "Page Description" }}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

<style type="text/css">
  .select_box{
    width: 490px;
  }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="{{ route('admin.shift.calender') }}" method="post" class="form-horizontal form-groups-bordered" novalidate="">
                    {{ csrf_field() }}
                      <div class="form-group">
                      <label for='select' class="col-sm-3 control-label">Select Filter option</label>
                      <div class="col-sm-5">
                          <select required name="type" id="select_opt" class="form-control select_box">
                            <option value="pro">By Project</option>
                            <option value="date" @if($filter_type == 'date') selected="" @endif>By Months</option>
                            <option value="all" @if($filter_type == 'all') selected="" @endif>By Months and Projects</option>
                          </select>
                      </div>
                    </div>
                    <div class="form-group"  id='projects' style="
                    @if($filter_type == 'all') display: block; 
                    @elseif($filter_type && ($filter_type !='pro' ))
                      display: none;
                    @endif">
                        <label for="department" class="col-sm-3 control-label">Select Project<span
                                class="required">*</span></label>

                        <div class="col-sm-5" >
                            <select required name="project_id" id="" class="form-control select_box">
                                <option value="">Select Projects</option>
                                @foreach($projects as $dk => $dv)
                                <option value="{{ $dv->id }}" @if($project_id == $dv->id) selected="selected" @endif>{{ $dv->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                     <div class="form-group" style="display: @if($filter_type == 'date' || $filter_type =='all') block  @else none @endif" id='dates' >
                        <label for="date_in" class="col-sm-3 control-label">Date<span class="required"> *</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in" value="{{ isset($date_today) ? $date_today : '' }}" name="date_in" id="date_in">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5 ">
                            <button type="submit" id="sbtn" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if($users)
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

                        <span id="index_lead_ajax_status"></span>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="orders-table">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        @foreach($dates as $date) 
                                        <th> {!! date('D dS M Y', strtotime($date)) !!}</th>
                                        @endforeach
                                           
                                    </tr>
                                 </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->username}}</td>
                                            @foreach($dates as $date)
                                            <?php
                                                $values =  \TaskHelper::getShift($user->id,$date);
                                               ?>
                                               @if($values)
                                                 <td class="bg-blue" style="background-color:">

                                                  @foreach($values as $value)
                                                    <span style="font-size:14px;"> 
                                                      {{$value->shift->shift_name}} 
                                                      ({{ date('h:m', strtotime($value->shift->shift_time)) }} -
                                                      {{  date('h:m', strtotime($value->shift->end_time)) }})</span>
                                                   @endforeach
                                                  </td>
                                                 @else
                                                 <td>-</td>
                                                 @endif
                                                
                                             @endforeach
                                        </tr>
                                    @endforeach    
                                </tbody>
                            </table>

                        </div> <!-- table-responsive --> 

                    </div><!-- /.box-body -->
                     
                </div><!-- /.box -->
                 
        </div><!-- /.col -->

    </div><!-- /.row -->
@endif
@endsection

@section('body_bottom')
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>
<script type="text/javascript">
  $('#select_opt').change(function(){
    let type = $(this).val();
    if(type == 'pro'){
      $('#projects').css('display','block');
      $('#dates').css('display','none');
    }
    else if(type =='date'){
      $('#projects').css('display','none');
      $('#dates').css('display','block');
    }
    else{
      $('#projects').css('display','block');
      $('#dates').css('display','block');
    }
  });
  $('.select_box').select2();
  $('.date_in').datetimepicker({
      //inline: true,
      format: 'YYYY-MM-DD', 
      sideBySide: true,
      allowInputToggle: true
    });
</script>

@endsection

