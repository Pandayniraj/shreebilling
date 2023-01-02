@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               {{$page_title ?? "Page Title"}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
   <div class="box box-header">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
           <span style="font-size: 20px"> Indicator List</span>
            <div style="display: inline; float: right;">
            <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="{{ route('admin.performance.create-performance-indicator') }}">
                            <i class="fa fa-check"></i>&nbsp;<strong>Create new Indicator</strong>
                        </a> <hr/>
            </div>      
        </div>
</div>
<table class="table table-hover table-no-border" id="leads-table">
<thead>
    <tr class="bg-blue">
       
        <th>ID</th>
        <th>Designations</th>
        <th>Department</th>
        <th>Action</th>
    </tr>
</thead> 
<tbody>
	@foreach($performance as $key=>$per)
	<tr>
		
        <td>{{$per->performance_indicator_id}}</td>
        <td style="font-size: 16.5px"><a href="show-performance-indicator/{{$per->performance_indicator_id}}">{{$per->designations}}</a></td>
        <td>{{$per->deptname}}</td>
        <td>
        <?php 
            $datas = '';
            if ( $per->isEditable())
                $datas .= "<a href=".route('admin.performance.edit-performance-indicator', $per->performance_indicator_id)."> <i class='fa fa-edit'></i> </a>";
            else
                $datas .= '<i class="fa fa-edit text-muted" title="{{ trans(\'admin/leads/general.error.cant-edit-this-lead\') }}"></i>';

            if ( $per->isDeletable() )
                $datas .= '<a href="'.route('admin.performance.confirm-delete-indicator', $per->performance_indicator_id).'?type='.\Request::get('type').'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fa fa-trash-o deletable"></i></a>';
            else
                $datas .= '<i class="fa fa-trash text-muted" title="{{ trans(\'admin/leads/general.error.cant-delete-this-lead\') }}"></i>';

            echo $datas;
        ?>
    </td>
    </tr>
    @endforeach

</tbody>
</table>
</div>

@endsection