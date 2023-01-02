@extends('layouts.master')
@section('content')

<style type="text/css">
	.table-bordered>thead>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{
		border-bottom: 1px solid #c4b8b8;
	}

	a.tip {
    border-bottom: 1px dashed;
    text-decoration: none
	}
	a.tip:hover {
	    cursor: help;
	    position: relative
	}
	a.tip span {
	    display: none
	}
	a.tip:hover span {
	    border: blue 1px solid;
	    padding: 5px 20px 5px 5px;
	    display: block;
	    z-index: 100;
	    color: red;
	    background: yellow no-repeat 100% 5%;
	    left: 0px;
	    margin: 10px;
	    width: 250px;
	    position: absolute;
	    top: 10px;
	    text-decoration: none
	}
	#entry_list td,#entry_list th{


		font-size: 14px;

	}
</style>

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Voucher Entry
                <small>{!! $page_description ?? "Page description" !!}
                	| Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong>

                </small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.accounts')}}
          

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

 <div class="row">       
 <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
                            <!-- Split button -->
				<div class="btn-group">
				  <button type="button" style="" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-plus-square"></i> Add Voucher Entry </button>
				  <ul class="dropdown-menu">
				   @foreach($entriestype as $type)
			  		<li> <a href="/admin/entries/add/{{$type->label}}">{{ $type->name}}</a></li> 
			  	    @endforeach
				  </ul>

				   <form style="display:inline-block;padding-left: 5px; margin-left: 10px; padding-right: 5px;border: 1px solid #ccc;" action="{{ URL::to('admin/ExcelentriesAdd/') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="file" name="import_file" style="display: inline-block; padding-top: 5px;
                     padding-bottom: 5px; border-radius: 3px;" />
                    <button class="btn btn-default btn-sm">Import File</button>
                </form>	
				</div>
				<div style="display: inline-flex;margin-right: 60px" class="pull-right">
                    <label>
                        {!! Form::checkbox('only_missing_entries', 'true',\Request::get('only_missing_entries')==true?'checked':'',['id'=>'only_missing_entries']) !!}
                        Only Problemetic Vouchers
                    </label>
                </div>
				  <div class="wrap" style="margin-top:10px;">
                            <div class="filter form-inline" style="margin:0 30px 0 0;">
                                {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:115px;', 'class' => 'form-control input-sm', 'id'=>'filter-start_date', 'placeholder'=>'Start date']) !!}&nbsp;&nbsp;
                                {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:115px;', 'class' => 'form-control input-sm', 'id'=>'filter-end_date', 'placeholder'=>'End date']) !!}&nbsp;&nbsp;
                                 {!! Form::select('tag_id', ['' => 'Select categoies'] + $tags ,\Request::get('tag_id'), ['id'=>'filter-tags', 'class'=>'form-control searchable', 'style'=>'width:130px; display:inline-block;']) !!}&nbsp;&nbsp;

                                {!! Form::select('entries_type_id', ['' => 'Select entries type'] + $entries_type ,\Request::get('entries_type_id'), ['id'=>'filter-entries_type', 'class'=>'form-control searchable', 'style'=>'width:130px; display:inline-block;']) !!}&nbsp;&nbsp;

                                {!! Form::select('legder_id', ['' => 'Select Ledger'] + $ledgers,\Request::get('legder_id'), ['id'=>'filter-legder', 'class'=>'form-control searchable', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                                 {!! Form::select('user_id', ['' => 'Select Users'] + $users ,\Request::get('user_id'), ['id'=>'filter-user', 'class'=>'form-control searchable', 'style'=>'width:130px; display:inline-block;']) !!}&nbsp;&nbsp;

                          
                                <span class="btn btn-primary btn-sm" id="btn-submit-filter">
                                    <i class="fa fa-list"></i> Filter
                                </span>
                                <span class="btn btn-danger btn-sm" id="btn-filter-clear">
                                    <i class="fa fa-close"></i> Clear
                                </span>
                            </div>
						</div> 

            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-bordered" style="border: 1px solid #c4b8b8;">
				<thead>
					<tr class="bg-primary">
						<th></th>
						<th>Num</th>
						<th>Date</th>
						<th>Miti</th>
						<th>Bill #</th>
					
						<th>Ledger</th>
						<th>Debit</th>
						<th>Credit</th>
						<th>Tag</th>
						<th>Source</th>
					
						<th>Tool</th>
						
						<th>Actions</th>
					</tr>
				</thead>
				<tbody id='entry_list'>		
				   @foreach($entries as $entry)
					   	@php 
						   	$entry_items  = $entry->entry_items; 
						   	$first_entry  = $entry_items->first();
						   	$other_entries = $entry_items->where('id','!=',$first_entry->id??'');  
					   	@endphp				
						<tr style="background-color: #F9F9F9;">
							<td>
                                @php
                                    $check_missing=$entry->checkLedger();
                                @endphp
                                @if($entry->is_approved==0 && $check_missing=='')
                                <input type="checkbox" class="entry_id" name="entry_id[]" value="{{ $entry->id }}">
                                @elseif($entry->is_approved==1)
                                <i  style="color:green;" class="fa fa-check" aria-hidden="true"></i>
                                @else
                                <i  style="color:red;" class="fa fa-times" aria-hidden="true"></i>
                                @endif
                            </td>
							<td><a href="/admin/entries/show/{{$entry->entrytype->label}}/{{$entry->id}}">{{$entry->number}}</a></td>
							<td style="white-space: nowrap;">{{$entry->date}}</td>
							<td style="white-space: nowrap;">{{ \TaskHelper::getNepaliDate($entry->date) }}</td>

					<?php
				       
				        if ($entry->source == 'AUTO_PURCHASE_ORDER') {
				            $href = "/admin/purchase/". $entry->ref_id."?type=bills";
	
				        } elseif ($entry->source == 'TAX_INVOICE') {
				            $href = "/admin/invoice1/". $entry->ref_id ;
				    	} else {
				            $href = "/admin/orders/". $entry->ref_id ;
				        }
				      ?>
							<td><a href="{{ $href }}" target="_blank">{{ $entry->bill_no }}</a></td>
							
							<td style="white-space: nowrap" title="{{$first_entry->ledgerdetail->name??''}}">
								{{mb_substr($first_entry->ledgerdetail->name??'',0,20)}}
							</td>
							@if($first_entry->dc??'' == 'D')
							<td>{{number_format($first_entry->amount,2)}}</td>
							<td>--</td>
							@else
							<td>--</td>
							<td>{{number_format(isset($first_entry->amount)?$first_entry->amount:0, 2)}}</td>
							@endif

							<td>
								<span class="label {{$entry->tagname->color}}">{{$entry->tagname->title}}</span>

							</td>
							<td>
								<small>{{mb_substr($entry->source,0,11)}}</small>
							</td>
							
							<td>
								<span>{{$entry->narration}}</span></a>
								{!! $entry->checkLedger() !!}
							</td>
						
							<td>
								
								<span class="link-pad"></span>
								@if($entry->is_approved==0 || $delete_option==true)
								<a href="/admin/entries/edit/{{$entry->entrytype->label}}/{{$entry->id}}" class="no-hover" title="Edit" escape="false"><i class="fa fa-edit"></i></a>
								<span class="link-pad"></span>
							 	<a href="/admin/entries/{{$entry->id}}/confirm-delete" class="no-hover" data-toggle="modal" data-target="#modal_dialog" title="Delete" escape="false"><i class="fa fa-trash deletable"></i></a>
								@endif
								
							</td>
						</tr>
						@foreach($other_entries as $oe)
						<tr>
							<td colspan="4"></td>
							<td>{{$oe->ledgerdetail->name}}</td>
							
							@if($oe->dc == 'D')
							<td>{{number_format($oe->amount,2)}}</td>
							<td>--</td>
							@else
							<td>--</td>
							<td>{{number_format($oe->amount,2)}}</td>
							@endif
							<td colspan="5"></td>
						</tr>
						@endforeach
					@endforeach						
				  </tbody>
				</table>
				<button type="button" style="display:none" class="btn btn-primary" id="changeRateModalLink" data-toggle="modal" data-target="#exampleModal">
                    Approved Entries
                </button>
				 <div style="text-align: center;"> {!! $entries->appends(\Request::except('page'))->render() !!} </div>
            </div>
          </div>
      </div>
      </div>

	  <!-- Modal -->
      <div class="modal fade" id="changeRateModal" tabindex="-1" role="dialog" aria-labelledby="changeRateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <form method="POST" action="{{route('admin.entry.verified')}}">
            <div class="modal-content">
                <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Verfied Entries</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
                </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="entryIds" id="entryIds">
                        <div class="row">
                            <div class="col-md-12" style="margin-bottom: 15px;">
                                <label>Users</label>
                               <input type="text"  readonly class="form-control" name="approved_by_user" value="{{Auth::user()->first_name}}">
                               <input type="hidden" name="approved_by" value="{{Auth::user()->id}}">
                            </div>

                            <div class="col-md-12" style="margin-bottom: 15px;">
                                <label>Remarks</label>
                                <input type="text" name="remarks" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="sbumit" class="btn btn-primary">Approved</button>
                    </div>
            </div>
        </form>
		</div>
	</div>


@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script type="text/javascript">
	$(function(){
	$('#filter-start_date').datetimepicker({
	//inline: true,
	format: 'YYYY-MM-DD',
	sideBySide: true
	});
	$('#filter-end_date').datetimepicker({
	//inline: true,
	format: 'YYYY-MM-DD',
	sideBySide: true
	});
	// $('#filter-start_date').select2();
	// $('#filter-end_date').select2();
	$('#filter-tags').select2();
	$('#filter-entries_type').select2();
	$('#filter-legder').select2();
	$('#filter-user').select2();

	});

$("#btn-submit-filter").on("click", function () {
	let start_date = $('#filter-start_date').val();
	let end_date = $('#filter-end_date').val();
	let tag_id = $('#filter-tags').val();
	let entries_type_id = $('#filter-entries_type').val();
	let legder_id = $('#filter-legder').val();
	let user_id = $('#filter-user').val();
	let only_missing_entries = $('#only_missing_entries').is(':checked')?true:'';
	let url = `{!! url('/') !!}/admin/entries?start_date=${start_date}&end_date=${end_date}&tag_id=${tag_id}&entries_type_id=${entries_type_id}&legder_id=${legder_id}&user_id=${user_id}&only_missing_entries=${only_missing_entries}`;
	window.location.href = url;
	// console.log(url);
 //    return false;
	// window.location.href = "{!! url('/') !!}/admin/leads?course_id="+course_id+"&source_id="+source_id+"&user_id="+user_id+"&rating="+rating+"&enq_mode="+enq_mode+"&start_date="+start_date+"&end_date="+end_date+"&status_id="+status_id+"&type="+type;
});
$("#btn-filter-clear").on("click", function () {
	window.location.href = "{!! url('/') !!}/admin/entries";
});

$('.entry_id').on("click", function(){
       checked = $("input[type=checkbox]:checked").length-1;
       console.log(checked);
       if(checked>0) {
        $("#changeRateModalLink").show();
       }else{
        $("#changeRateModalLink").hide();
       }
 });
$('#changeRateModalLink').on("click", function(){

var entry_id = [];

$('.entry_id').each(function(index){
    var checked = $(this).is(':checked');

    if (checked){
        var value = $(this).val();
        entry_id.push(value);
    }
});

$('#entryIds').val(entry_id);

$('#changeRateModal').modal('show');
});

</script>

@endsection  