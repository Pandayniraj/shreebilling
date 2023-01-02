@extends('layouts.master')

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>

   <div class="nav-tabs-custom" id="tabs">

  
    <!-- /.tab-content -->

       <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
            <!-- <b><font size="4">{!!  $page_title !!}</font></b> -->
            <div style="display: inline; float: right;">
            <a class="btn btn-primary btn-sm"  title="Import/Export Folder" href="{{ route('admin.payroll.remote_taxable_amount.create') }}">
                            <i class="fa fa-plus"></i>&nbsp;<strong>Create New Deducted Taxable Amount</strong>
                        </a> 
            </div>      
        </div>
</div>

<table class="table table-hover table-no-border" >
<thead>
    <tr>
        <th style="text-align:center;width:20px !important">
            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                <i class="fa fa-check-square-o"></i>
            </a>
        </th>
        <th>ID</th>
        <th>Name</th>
        <th>Value</th>
       <th>Action</th>
    </tr>
</thead>
<tbody>
@foreach($lists as $key=>$value)
  <tr>
        <td><input type="checkbox" name=""></td>
        <td>#{{ $key+1 }}</td>
        <td>{{ $value->group->group_name }}</td>
        <td><a href="#" class="text-muted">{{ $value->district_name }}</a></td>
       <td>
          
            <a href="{{ route('admin.payroll.remote_taxable_amount.edit',$value->id) }}"><i class="fa fa-edit editable"></i></a>
          
            &nbsp;&nbsp;
           
         <a href="{{ route('admin.payroll.remote_taxable_amount.confirm-delete',$value->id) }}" data-toggle="modal" data-target="#modal_dialog" ><i class="fa fa-trash deletable"></i></a> 
          
        </td>
  </tr>
    @endforeach 

</tbody>
</table>
<div align="center"></div>
</div>


</div>
@endsection