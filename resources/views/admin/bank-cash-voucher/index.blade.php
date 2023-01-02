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


		font-size: 12px;

	}
</style>

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Bank and Cash Voucher
                <small>{!! $page_description ?? "Page description" !!}


                </small>
            </h1>


            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

 <div class="row">
 <div class="col-xs-12">
          <div class="box">
            {{-- <div class="box-header with-border">
                            <!-- Split button -->
				<div class="btn-group">
				  <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-plus-square"></i> Add Voucher Entry </button>
				  <ul class="dropdown-menu">
				   @foreach($entriestype as $type)
			  		<li> <a href="/admin/entries/add/{{$type->label}}">{{ $type->name}}</a></li>
			  	    @endforeach
				  </ul>
				</div>
                <div style="display: inline-flex;margin-right: 60px" class="pull-right">
                    <label>
                        {!! Form::checkbox('only_missing_entries', 'true',\Request::get('only_missing_entries')==true?'checked':'',['id'=>'only_missing_entries']) !!}
                        Only Problemetic Vouchers
                    </label>
                </div>
				<div style="margin-left: 22px;display: inline-block">
					@if(\Request::get('start_date') || \Request::get('end_date') || \Request::get('tag_id') || \Request::get('entries_type_id')|| \Request::get('legder_id')|| \Request::get('user_id')|| \Request::get('only_missing_entries'))
						<a href="{{route('admin.entries.filter.excel',['type'=>'excel','start_date'=>\Request::get('start_date'),'end_date'=>\Request::get('end_date'),'tag_id'=>\Request::get('tag_id'),'entries_type_id'=>\Request::get('entries_type_id'),'legder_id'=>\Request::get('legder_id'),'user_id'=>\Request::get('user_id'),'only_missing_entries'=>\Request::get('only_missing_entries')])}}"><button class="btn btn-success btn-sm" >Export To Excel</button></a>
					@endif
				</div>
				  <div class="wrap" style="margin-top:5px;">
                            <div class="filter form-inline" style="margin:0 30px 0 0;">
                                {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:120px;', 'class' => 'form-control input-sm', 'id'=>'filter-start_date', 'placeholder'=>'Start date']) !!}&nbsp;&nbsp;
                                {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:120px;', 'class' => 'form-control input-sm', 'id'=>'filter-end_date', 'placeholder'=>'End date']) !!}&nbsp;&nbsp;
                                 {!! Form::select('tag_id', ['' => 'Select categoies'] + $tags ,\Request::get('tag_id'), ['id'=>'filter-tags', 'class'=>'form-control searchable', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                                {!! Form::select('entries_type_id', ['' => 'Select entries type'] + $entries_type ,\Request::get('entries_type_id'), ['id'=>'filter-entries_type', 'class'=>'form-control searchable', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                                {!! Form::select('legder_id', ['' => 'Select Ledger'] + $ledgers,\Request::get('legder_id'), ['id'=>'filter-legder', 'class'=>'form-control searchable', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                                 {!! Form::select('user_id', ['' => 'Select Users'] + $users ,\Request::get('user_id'), ['id'=>'filter-user', 'class'=>'form-control searchable', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;


                                <span class="btn btn-primary btn-sm" id="btn-submit-filter">
                                    <i class="fa fa-list"></i> Filter
                                </span>
                                <span class="btn btn-default btn-sm" id="btn-filter-clear">
                                    <i class="fa fa-close"></i>
                                </span>
                            </div>
						</div>

            </div> --}}
            <!-- /.box-header -->
            <div class="box-body table-responsive">

				<a href="/admin/bank-cash-voucher/create?do=receive" class="btn btn-primary btn-sm pull-right" style="margin-bottom: 10px;">Create</a>

              <table class="table table-bordered" style="border: 1px solid #c4b8b8;">
				<thead>
					<tr class="bg-info"><th>Num</th>
						<th>Date</th>
						<th>Miti</th>
						<th>Bill #</th>

						<th>Ledger</th>

						<th>Tag</th>
						<th>Type</th>
						<th>Entry Source</th>
						<th>Amount</th>

						<th>Actions</th>
					</tr>
				</thead>
				<tbody id='entry_list'>
				   @foreach($entries as $entry)
						<tr><td><a href="/admin/bank-cash-voucher/show/{{$entry->id}}?fiscal_year={{\Session::get('selected_fiscal_year')}}">{{$entry->number}}</a></td>
							<td style="white-space: nowrap;">{{$entry->date}}</td>
							<td style="white-space: nowrap;">{{ \TaskHelper::getNepaliDate($entry->date) }}</td>
							<td>{{ $entry->dynamicSessionBillNum() }}</td>

							{{-- <td style="white-space: nowrap;">
								{{TaskHelper::getDynamicEntryLedger($entry->id)}}</td> --}}

                                <td  style="white-space: nowrap;">
                                    @php
                                        $legder_and_lastest_entry=TaskHelper::getDynamicEntryLedger($entry->id);
                                        //  dd($legder_and_lastest_entry);
                                    @endphp
								
                                      {{$legder_and_lastest_entry}}
                                      <div style="color:grey">
                                          {{$legder_and_lastest_entry}}...
                                      </div>
                                  </td>
							<td>
								<span class="label {{$entry->tagname->color}}">{{$entry->tagname->title}}</span>

							</td>
							<td>
							{{$entry->entrytype->name}}

							</td>
							<td>{{$entry->source}}
                                <br>
                                {!! $entry->checkLedger() !!}
                                @if($entry->entry_difference!=0)
                                <br>
                                {{$entry->entry_difference}}
                                    @endif
                            </td>

							<td>
								<a href="#" class="tip">{{ $entry->currency}} {{number_format($entry->dr_total,2)}}
                                    <span>{{$entry->narration}}</span></a>
							</td>

							<td>

								<span class="link-pad"></span>
                                <?php
                                $current_fiscal_year=\App\Models\Fiscalyear::where('current_year',1)->first();
                                ?>
                                @if(\Session::get('selected_fiscal_year')==$current_fiscal_year->numeric_fiscal_year||!\Session::get('selected_fiscal_year'))
								<a href="/admin/bank-cash-voucher/{{$entry->id}}/edit" class="no-hover" title="Edit" escape="false"><i class="fa fa-edit"></i></a>
								<span class="link-pad"></span>
							 	<a href="/admin/entries/{{$entry->id}}/confirm-delete" class="no-hover" data-toggle="modal" data-target="#modal_dialog" title="Delete" escape="false"><i class="fa fa-trash deletable"></i></a>
							@endif
                            </td>
						</tr>
					@endforeach
				  </tbody>
				</table>
				 <div style="text-align: center;"> {!! $entries->appends(\Request::except('page'))->render() !!} </div>
            </div>
          </div>
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

</script>

@endsection
