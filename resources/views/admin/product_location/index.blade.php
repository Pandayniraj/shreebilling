@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              Store Location
                <small>Add store or warehouse location</small>
            </h1>
            <p> This is listed while purchasing or selling product items</p>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
   <div class="panel panel-custom">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
            <b><font size="4">Location List</font></b>
           
            <div style="display: inline; float: right;">
            <a class="btn btn-success btn-sm"  title="Import/Export Leads" href="{{ route('admin.product-location.create') }}" >
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;<strong>Add new location</strong>
                        </a> 
            </div>  
  
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
        <th>Location name</th>
        <th>Phone</th>
        <th>Action</th>
    </tr>
</thead>
<tbody id="myTable">
    @foreach($location as $key=>$loc)
    <tr>
        <td >
            <input type="checkbox" name="" value="{{$loc->id}}">
        </td>
        <td>{{$loc->id}}</td>
        <td style="font-size: 16.5px">{{ucfirst(trans($loc->location_name))}}</td>
         <td>{{$loc->phone}}</td>
        <?php 
         $datas = '<a href="'.route('admin.product-location.delete-confirm', $loc->id).'?type='.\Request::get('type').'" data-toggle="modal" data-target="#modal_dialog" title="Delete"><i class="fa fa-trash-o deletable"></i></a>';
         ?>
        <td><a href="{{route('admin.product-location.edit',$loc->id)}}" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
        <a href="{{route('admin.product-location.show',$loc->id)}}" title="View"><i class="fa fa-file-text"></i></a>&nbsp;&nbsp;&nbsp;<?php echo $datas ?></td>
    </tr>
    @endforeach

</tbody>
</table>

</div>


@endsection