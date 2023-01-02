@extends('layouts.master')

@section('content')

 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Import Data
                <small>{!! $page_description ?? "Import Data" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

   <div class="box box-primary">
    <div class="box-header with-border">
    <div class='row'>
        <div class='col-md-12'>
           
            <b><font size="4"> {{$device_->device_name}}</font></b>
            <div style="display: inline; float: right;">
            <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="/admin/ImportAttendence">
                            <i class="fa fa-check"></i>&nbsp;<strong>Import Attendence</strong>
                        </a>
                <a class="btn btn-danger btn-sm"  title="Import/Export Leads" href="{{ route('ImportEmployee') }}">
                            <i class="fa fa-check"></i>&nbsp;<strong>Import Employee</strong>
                        </a>  
            </div>      
        </div>

    </div>
</div>
<table class="table table-hover table-no-border" id="leads-table">
<thead>
    <tr>
        <th> S.N</th>
         <th>ID</th>
        <th>Employee Name</th>
        <th>Privilege</th>
    </tr>
</thead>
<tbody>
	@foreach($bio_employee as $key=>$employee)
	<tr>
        @if($employee->privilege == '14')
		
        <td class="bg-info">{{$key + 1 }}</td>
        <td class="bg-info">{{$employee->id}}</td>
        <td class="bg-info">{{$employee->name}}</td>
        <td class="bg-info">Super Administrator</td>
        @else
        <td >{{$key + 1 }}</td>
        <td>{{$employee->id}}</td>
        <td>{{$employee->name}}</td>
        <td>Users</td>
        @endif
    </tr>
    @endforeach

</tbody>
</table>
</div>
<div id="showattendence"></div>

<script type="text/javascript">
	function openModel(id){
	window.open("showAttendence?emp_id="+id);
	return false;
	}
</script>
@endsection