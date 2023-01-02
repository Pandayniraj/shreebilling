@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              Letter Template Master
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>
   <div class="box">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
           
            <div style="">
            <a class="btn btn-primary btn-sm"  title="" href="{{ route('admin.hrlettertemplate.create') }}">
                            <i class="fa fa-plus"></i>&nbsp;<strong>Create New Templates</strong>
                        </a> 
            </div> <br/>   
        </div>
</div>

<table class="table table-hover table-no-border" >
<thead>
    <tr class="bg-info">
       
        </th>
        <th>ID</th>
        <th>Name</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($hrlettertemplate as $key=>$value)
	<tr>
       
        <td>#{{ $value->id }}</td>
        <td style="font-size: 16.5px">{{ $value->name }}</td>
        <td>
            @if($value->isEditable())
            <a href="{{ route('admin.hrlettertemplate.edit',$value->id) }}"><i class="fa fa-edit editable"></i></a>
            @else
            <a href=""><i class="fa fa-edit text-muted"></i></a>
            @endif
            &nbsp;&nbsp;
             @if($value->isDeletable())
            <a href="{{ route('admin.hrlettertemplate.confirm-delete',$value->id) }}" data-toggle="modal" data-target="#modal_dialog" ><i class="fa fa-trash deletable"></i></a>
            @else
            <a href=""><i class="fa fa-trash text-muted"></i></a>
            @endif
        </td>
	</tr>
    @endforeach

</tbody>
</table>
<div align="center">{{ $hrlettertemplate->render()  }}</div>
</div>

@endsection