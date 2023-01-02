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
    table th,td{
        text-align: center;
    }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                GeoLocations Report
                <small>{!! $page_description ?? "Report" !!}</small>
            </h1>
            <p> This will give the tracking history of x number of days, depend on settings.</p>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong>GeoLocations Report</strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data" action="/admin/geolocations/filter" method="get" class="form-horizontal form-groups-bordered">
                    <div class="form-group">
                        <label for="user_id" class="col-sm-3 control-label">Employee<span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select required name="user_id" id="user_id" class="form-control select_box">
                                <option value="">Select Employee</option>
                                @foreach($users as $uv)
                                <option value="{{$uv->id}}" @if( isset($user) &&  $uv->id == $user->id) selected @endif>{{ucfirst($uv->username).'(#'.$uv->id.')'}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="date_in" class="col-sm-3 control-label">Start Date<span class="required"> *</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in" value="{{isset($start_date) ? $start_date : ''}}" name="start_date" >
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="date_in" class="col-sm-3 control-label">End Date<span class="required">*</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input required="" type="text" class="form-control date_in" value="{{  isset($end_date) ? $start_date : '' }}" name="end_date">
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

@if(isset($locations))
<div id="EmpprintReport">
    <div class="row">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title pull-left">
                            <strong>User:</strong> {{$user->first_name}} {{$user->last_name}},
                            <strong>Total Distance Travelled:</strong>
                            <label id='total_distance_travelled' class="label label-primary"></span>
                        </div>
                            <div class="panel-title pull-right">
                            <strong>Date:</strong> {{date('dS M Y',strtotime($start_date))}} - {{date('dS M Y',strtotime($end_date))}} 
                        </div>
                         <div class="clearfix"></div>
                    </div>
                <table class="table table-striped DataTables  dataTable no-footer dtr-inline" id="DataTables">
                   
                  <thead>
                     <tr>
                        <td class="text-bold col-sm-1">S.N</td>
                        <td  class="text-bold">Date</td>
                        <td class="text-bold">Time</td>
                        <td class="text-bold">Latitude</td>
                        <td class="text-bold">Longtitude</td>
                        <td  class="text-bold">Distance</td>
                        <td class="text-bold">Street</td>
                        <td class="text-bold">Address</td>
                        <td class="text-bold">IP Address</td>
                  <!--       <td class="text-bold">Place</td> -->
                        <td class="text-bold col-sm-2">Action</td>
                     </tr>
                  </thead>
                  <tbody>
                   <?php 
                    $total_distance = 0;
                    $index = 0;
                   ?>
                    @foreach($locations as $key=>$loc)

                    <?php 

                        $distance = 0;
                        $next = $locations[++$index];
                        if($next){
                            
                            $lat1 = $loc->latitude;
                            $lon1 = $loc->longitude;
                            $lat2 = $next->latitude;
                            $lon2 = $next->longitude;
                            $distance = round(\TaskHelper::haversine($lat1, $lon1,$lat2, $lon2),3);
                            $total_distance += $distance;

                        }
                    ?>
                    <tr>
                        <td class="text-bold col-sm-1" style="text-align: left; text-indent: 20px;">&nbsp;&nbsp; 
                            {{ ++$key }}. 
                        </td>
                        <td style="white-space: nowrap;"> {{ date('dS M Y',strtotime($loc->tracked_date)) }}</td>
                        <td class="text-bold" >{{date('H:i',strtotime($loc->created_at))}}</td>
                        <td class="longitude" id="lat-{{$key}}">
                            {{substr($loc->latitude,0,10)}}</td>
                        <td class="latitude" id="long-{{$key}}">{{substr($loc->longitude,0,10)}}</td>
                        <td >
                        <b>{{$distance}}</b> K.M
                        </td>
                        <td>{{$loc->street_name}}</td>
                        <td>{{$loc->formatted_address}}</td>
                        <td class="text-bold">{{$loc->ip_address}}</td>
                       <!--  <td class="places">
                          <i class="fa fa-refresh fa-spin"></i>
                        </td> -->
                        <td><a href="https://www.google.com/maps/search/?api=1&query={{$loc->latitude}},{{$loc->longitude}}" class="btn btn-primary btn-xs" target="_blank" title="View locations"><i class="fa fa-street-view">&nbsp;</i></a></td>
                    </tr>
                    @endforeach

                  </tbody>
              </table>
              
          </div>
      </div>
  </div>
</div>
@endif
</div>
</div>
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/select2/css/select2-bootstrap.css") }}">
<script src="{{ asset("/bower_components/admin-lte/select2/js/select2.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script type="text/javascript">
    $(function() {
        $('.date_in').datetimepicker({
            format: 'YYYY-MM-DD',
            sideBySide: true
        });

        $('.select_box').select2({
            theme: 'bootstrap',
        });

        
    });

    $(document).on('focusin', '#clockin_edit', function(){
      $(this).timepicker();
    });
   $(function() {
        $('#DataTables').DataTable({
            pageLength: 35,
            ordering: false
        });

        $('#total_distance_travelled').text("{{ isset($total_distance) ? $total_distance : '' }} K.M")
    });

</script>
@endsection
