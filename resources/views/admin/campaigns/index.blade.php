@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               Marketing Campaigns
                <small>Campaigns Index</small>

                </h1>
                <p> Make a list of online and offline campaigns. Basically it works in two ways. You connect <a target="_blank" href="/enquiry">this form </a>with data variables online, or import offline marketing campaigns with campaign_id in <a target="_blank" href="/admin/importExportLeads">csv file.</a> </p>
            
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
   <div class="box box-primary">

    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
            <b><font size="4">Campaigns List</font></b>
            <div style="display: inline; float: right;">
            <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="{{ route('admin.campaigns.create') }}">
                            <i class="fa fa-plus"></i>&nbsp;<strong>Create new campaings</strong>
                        </a> 
            </div>      
        </div>
</div>
<?php
$labelcolor= ['starred'=>"btn bg-maroon  margin",'finished'=>'btn bg-red  margin','ongoing'=>'btn bg-yellow margin']; 
?>

<table class="table table-hover table-no-border" id="leads-table">
<thead class="bg-info">
        <th>ID</th>
        <th>Name</th>
        <th> Records </th>
        <th>Start date</th>
        <th>End date</th>
        <th>Campaign type</th>
        <th>Campaign Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($campaigns as $key=>$camp)
	<tr>
        <td>{{$camp->id}}</td>
        <td style="font-size: 16.5px">
            <a href="/admin/campaigns/show/{{$camp->id}}/"> 
            {{ucfirst(trans($camp->name))}}</a>
           <td> ({{ $camp->leads->count() }})</td>
        </td>
        <td>{{ date('dS M y', strtotime($camp->start_date)) }}</td>
        <td>{{ date('dS M y', strtotime($camp->end_date)) }}</td>
        <td>{{ucfirst(trans($camp->campaign_type))}}</td>
        <td><label class="label {{$labelcolor[$camp->camp_status]}}">{{ucfirst(trans($camp->camp_status))}}</label></td>
       
        <?php 
         $datas = '<a href="'.route('admin.campaigns.confirm-delete', $camp->id).'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fa fa-trash-o deletable"></i></a>';
         ?>
        <td>
            @if($camp->isEditable())
            <a href="{{route('admin.campaigns.edit',$camp->id)}}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
            @else 
            <i class="fa fa-edit"></i>
            @endif
            @if($camp->isDeletable())
            <?php echo $datas ?>
            @else 
            <i class="fa fa-trash-o deletable"></i>
            @endif
        </td>
    </tr>
    @endforeach

</tbody>
</table>
<div align="center">   {!! $campaigns->render() !!} </div>
</div>

@endsection