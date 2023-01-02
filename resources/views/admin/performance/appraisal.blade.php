@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
            {{$page_title ?? "Page Title"}}
                <small>{{$page_description ?? "Page Description"}}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
   <div class="box box-header">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
            <span style="font-size: 20px"> Appraisal List</span>
            <div style="display: inline; float: right;">
            <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="{{ route('admin.performance.giveappraisal') }}">
                            <i class="fa fa-check"></i>&nbsp;<strong>Give Appraisal</strong>
                        </a> <hr/>
            </div>      
        </div>
</div>
<table class="table table-hover table-no-border" id="leads-table">
<thead>
    <tr class="bg-maroon">
        
        <th>ID</th>
        <th>User</th>
        <th>Appraisal month</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($appraisal as $key=>$app)
	<tr>
		
        <td>{{$app->performance_appraisal_id}}</td>
        <td style="font-size: 16.5px"><a href="show-appeaisal/{{$app->performance_appraisal_id}}">{{ucfirst(trans($app->username))}}</a></td>
        <td>{{date('F Y', strtotime($app->appraisal_month))}}</td>
        <td>
        <?php 
            $datas = '';
            if ( $app->isEditable())
                $datas .= "<a href=".route('admin.performance.edit-appeaisal', $app->performance_appraisal_id)."> <i class='fa fa-edit'></i> </a>";
            else
                $datas .= '<i class="fa fa-edit text-muted" title="{{ trans(\'admin/leads/general.error.cant-edit-this-lead\') }}"></i>';

            if ( $app->isDeletable() )
                $datas .= '<a href="'.route('admin.performance.confirm-delete-appeaisal', $app->performance_appraisal_id).'?type='.\Request::get('type').'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fa fa-trash-o deletable"></i></a>';
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