@extends('layouts.master')

@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />

<style>
      select { width:160px !important; }
</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Users Wallets
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
          

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
        	<div class="box box-primary">
        	<div class="box-header with-border">
        	

        	</div>
        	 <div class="box-body">
        	<table class="table">
        			<thead>
        				<tr>
                            <th>ID</th>
	        				<th>User</th>
	        				<th>Balance</th>
	        				<th>Action</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($users as $key=>$user)
        				<tr>
        				    <td>#{{ $user->id }}</td>
                            <td>{{ $user->username }}[{{ $user->id }}]</td>
                            <td>{{env('APP_CURRENCY')}} {{ $user->wallet()->balance }}</td>

                            @if(Auth::user()->hasRole('admins'))
                            @if($user->wallet())
                                <td>
                                    <a href="{{route('admin.users.wallet.deposit',$user->id)}}" class="btn btn-xs btn-success" data-toggle="modal" data-target="#modal_dialog"> 
                                    Deposit Amount</a>
                                </td>
                            @else
                                <td>
                                    <a href="{{route('admin.users.wallet.store',$user->id)}}" class="btn btn-xs btn-primary"> 
                                        Create Wallet
                                    </a>
                                </td>
                            @endif
                            @else
                                <td> </td>
                            @endif
        				</tr>
                        @endforeach
        			</tbody>
        	</table>
        	</div>
        </div>
    </div>
</div>
 @endsection