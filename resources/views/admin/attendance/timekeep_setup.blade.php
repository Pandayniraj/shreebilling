@extends('layouts.master')

@section('content')

<style type="text/css">
  .blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_description ?? "Page description" !!}</small>


            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<form method="post" action="{{ route('admin.attendance.timekeep_setup') }}">
  {{ csrf_field() }}
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Need this info when running payroll</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <th>Emp ID</th>
                    <th>User</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Time Entry Method</th>
                    <th>Pay Frequency</th>
                    <th>Pay Type</th>
                  </tr>
                @foreach($staffs as $s)
                <input type="hidden" name="user_id[]" value="{{$s->id}}">
                <tr>
                  <td>{{ env('SHORT_NAME') }}{{ $s->id }}</td>
                  <td>{{ $s->first_name }} {{ $s->last_name }}</td>
                  <td>{{ $s->designation->designations ??'' }}</td>
                   <td>{{ $s->department->deptname ??"" }}</td>
                  <td>
                    {!! Form::select('time_entry_method[]',array(''=>'Not Set') +  array('W' => 'Web & Mobile', 'B' => 'Biometric','T'=>'Timesheet'), $s->timekeeping->time_entry_method ?? '') !!}
                  </td>
                  <td>
                     
                    <select name='pay_frequency_id[]'>
                      <option value="">Not Set</option>
                      @foreach($pay_frequency as $pf)
                      <option value="{{$pf->frequency}}" 
                        @if( ($s->timekeeping->pay_frequency ?? '') == $pf->frequency) selected @endif>{{$frequency[$pf->frequency]}}</option>
                      @endforeach
                    </select>
               
                  </td>
                  <td>
                    {!! Form::select('pay_type[]',array(''=>'Not Set') + array('H' => 'Hourly', 'W' => 'Weekly', 'M' => 'Monthly'),  $s->timekeeping->pay_type ?? '') !!}
                  </td>
                 
                </tr>
                @endforeach
          
              </tbody></table>

              
              </a><button type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-play"></i>&nbsp; Set
              </button>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
</div>
</form>
        


     




 


@endsection