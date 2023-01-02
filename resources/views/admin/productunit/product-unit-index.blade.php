@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              Product Unit
                <small>Product Unit Index</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
   <div class="box box-primary">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
            <b><font size="4">Product unit</font></b>
            <div style="display: inline; float: right;">
            <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="{{ route('admin.production.product-unit') }}">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;<strong>Add new product unit</strong>
                        </a> 
            </div>      
        </div>
</div>

<table class="table table-hover table-no-border" id="leads-table">
<thead>
    <tr>
        <th style="text-align:center;width:20px !important">
            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                <i class="fa fa-check-square-o"></i>
            </a>
        </th>
        <th>ID</th>
        <th>Name</th>
        <th>Symbol</th>
           <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($productunit as $key=>$pu)
	<tr>
		<td >
			<input type="checkbox" name="event_id" value="{{$pu->id}}">
        </td>
        <td>{{$pu->id}}</td>
        <td>{{ucfirst(trans($pu->name))}}</td>
        <td>{{$pu->symbol}}</td>
        <?php 
         $datas = '<a href="'.route('admin.production.confirm-delete-produnit', $pu->id).'?type='.\Request::get('type').'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fa fa-trash-o deletable"></i></a>';
         ?>
        <td><a href="/admin/production/edit-produnit/{{$pu->id}}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;<?php echo $datas ?></td>
    </tr>
    @endforeach

</tbody>
</table>
</div>

@endsection