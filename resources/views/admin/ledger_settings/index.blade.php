@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Ledger Settings Master
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    Set the key Ledger Ids or Ledger Group IDs. For example set Account Payble Group as Suppliers 

    <br />

    {{ TaskHelper::topSubMenu('topsubmenu.accounts')}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

   <div class="box">
    <div class="box-header with-border">
       <div class='row'>
        <div class='col-md-12'>
           
           
            <div style="display: inline;">
            <a class="btn btn-primary btn-sm"  title="Create" href="{{ route('admin.ledgers.setting.create') }}">
                            <i class="fa fa-plus"></i>&nbsp;<strong>Add New</strong>
                        </a> 
            </div>      
        </div>
</div>

<table class="table table-hover table-no-border table-striped" >
<thead>
    <tr class="bg-info">
        <th>
           No.
        </th>
        <th>Ledger ID</th>
        <th>Ledger Name</th>
        <th>Label</th>
        <th>Table</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
	@foreach($ledgersList as $key=>$value)
	<tr>
        <td>{{$value->id}}</td>
        <th>#{{ $value->ledger_id }}</th>
        <td>{{$value->ledgername->name??'-'}}</td>
        <td>{{ $value->ledger_name }}</td>
        <td><a href="#" class="text-muted">{{ $value->table_name }}</a></td>
        <td>
            @if($value->isEditable())
            <a href="{{ route('admin.ledgers.setting.edit',$value->id) }}"><i class="fa fa-edit editable"></i></a>
            @else
            <a href=""><i class="fa fa-edit text-muted"></i></a>
            @endif
            &nbsp;&nbsp;
             @if($value->isDeletable())
            <a href="javascript::void()" onclick="confirmDel('{{$value->id}}')" ><i class="fa fa-trash deletable"></i></a>
            @else
            <a href=""><i class="fa fa-trash text-muted"></i></a>
            @endif
        </td>
	</tr>
    @endforeach

</tbody>
</table>
<div align="center">{{ $ledgersList->render()  }}</div>
</div>
<script type="text/javascript">
    function confirmDel(id){
        let c = confirm("Are You Sure You Want To Delete");
        if(c){
            location.href = `/admin/ledgers/settings/destroy/${id}`;
        }
    }
</script>

@endsection