@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Help Desk Tickets
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
             <span> Tickets can be submitted from external portal by clicking <a target="_blank" href="/ticket">here</a></span> App for field staff <a target="_blank" href="https://play.google.com/store/apps/details?id=meronetwork.app.fieldservice&hl=en">mobile apps</a>

             <br/>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.cases.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        
                        &nbsp;
                        <a class="btn bg-maroon btn-sm" href="{!! route('admin.cases.create') !!}" title="{{ trans('admin/cases/general.button.create') }}">
                            <i class="fa fa-plus"></i> Create Case
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.cases.enable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle"></i>
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmClientList'].action = '{!! route('admin.cases.disable-selected') !!}';  document.forms['frmClientList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i>
                        </a>


                        <div class="pull-right">
                            <div class="filter form-inline" style="margin:0 30px 0 0;">
                                {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:120px;', 'class' => 'form-control input-sm', 'id'=>'filter-start_date', 'placeholder'=>'Start date']) !!}&nbsp;&nbsp;
                                {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:120px;', 'class' => 'form-control input-sm', 'id'=>'filter-end_date', 'placeholder'=>'End date']) !!}&nbsp;&nbsp;
                                 {!! Form::select('case_status', ['' => 'Select status'] + $case_status ,\Request::get('case_status'), ['id'=>'filter-case_status', 'class'=>'form-control searchable', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                                {!! Form::select('client_id', ['' => 'Select customers'] + $customers  ,\Request::get('client_id'), ['id'=>'filter-clients', 'class'=>'form-control searchable', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;


                                 {!! Form::select('user_id', ['' => 'Select Users'] + $users  ,\Request::get('user_id'), ['id'=>'filter-user', 'class'=>'form-control searchable', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                          
                                <span class="btn btn-primary btn-sm" id="btn-submit-filter">
                                    <i class="fa fa-list"></i> Filter
                                </span>
                                <span class="btn btn-danger btn-sm" id="btn-filter-clear">
                                    <i class="fa fa-close"></i> Clear
                                </span>
                            </div>
                        </div> 

                        

                    </div>
                    <div class="box-body">

                      
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="cases-table">
                                <thead>
                                    <tr class="bg-danger">
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                
                                            </a>
                                        </th>
                                        <th style="text-align: center;">ID</th>
                                        <th style="text-align: center;">Job No.</th>
                                        <th style="text-align: center;">Date</th>
                                        <th style="text-align: center;">Address</th>
                                        <th>{{ trans('admin/cases/general.columns.status') }}</th>
                                        <th>Title</th>
                                        <th>Product</th>
                                         <th>Customer</th>
                                         <th>Owner</th>
                                        
                                        <th>{{ trans('admin/cases/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($cases) && !empty($cases))  
                                    @foreach($cases as $case)
                                        <tr>

                                            @if($case->viewed == '0')
                                            <td class="bg-info" align="center">{!! Form::checkbox('chkClient[]', $case->id); !!}</td>
                                            <td class="bg-info">{{env('SHORT_NAME')}}{{$case->id}}</td>
                                            <td class="bg-info">#{{$case->job_no}}</td>
                                            <td class="bg-info">{{ date('dS M y', strtotime($case->created_at)) }}</td>
                                            <td class="bg-info">{{mb_substr($case->address,0,7)}}</td>
                                            <td class="bg-info">
                                                 @if($case->status == 'pending')
                                                <span class="label bg-red">{{ ucfirst($case->status)}}</span>
                                                @elseif($case->status == 'new')
                                                <span class="label bg-orange">{{ ucfirst($case->status)}}</span>
                                                 @elseif($case->status == 'closed')
                                                <span class="">{{ ucfirst($case->status)}}</span>
                                                @else
                                                <span class="label bg-green">{{ ucfirst($case->status)}}</span>
                                                @endif
                                            </td>

                                            @if($case->status == 'closed')
                                            <td style="font-size: 16.5px" class="text-muted bg-info"> {!! link_to_route('admin.cases.show', mb_substr($case->subject,0,70), [$case->id], []) !!}..</td>
                                            @else
                                            <td style="font-size: 16.5px" class="bg-info"> {!! link_to_route('admin.cases.show', mb_substr($case->subject,0,70), [$case->id], []) !!}..</td>
                                            @endif
                                            <td class="bg-info">{{$case->products->name}}</td>
                                             <td class="bg-info">
                                                @if($case->cust_name) 
                                                {{ucfirst($case->cust_name)}}
                                                @else
                                                <a href="/admin/clients/{{$case->client_id}}" target="_blank">
                                                {!! $case->client->name !!} 
                                                </a>
                                                @endif
                                            </td> 
                                            <td class="bg-info"> {{ $case->user->first_name }} </td>
                                            <td class="bg-info">
                                                @if ( $case->isEditable() || $case->canChangePermissions() )
                                                    <a href="{!! route('admin.cases.edit', $case->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/cases/general.error.cant-edit-this-document') }}"></i>
                                                @endif

                                                @if ( $case->enabled )
                                                    <a href="{!! route('admin.cases.disable', $case->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                                @else
                                                    <a href="{!! route('admin.cases.enable', $case->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif

                                                @if ( $case->isDeletable() )
                                                    <a href="{!! route('admin.cases.confirm-delete', $case->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/cases/general.error.cant-delete-this-document') }}"></i>
                                                @endif
                                            </td>

                                               @else

                                               <td align="center">{!! Form::checkbox('chkClient[]', $case->id); !!}</td>
                                                <td align="center">{{env('SHORT_NAME')}}{{$case->id}}</td>
                                                <td >#{{$case->job_no}}</td>
                                                <td>{{ date('dS M y', strtotime($case->created_at)) }}</td>
                                                <td >{{mb_substr($case->address,0,10)}}</td>
                                            <td>

                                                 @if($case->status == 'pending')
                                                <span class="label bg-red">{{ ucfirst($case->status)}}</span>
                                                @elseif($case->status == 'new')
                                                <span class="label bg-orange">{{ ucfirst($case->status)}}</span>
                                                 @elseif($case->status == 'closed')
                                                <span class="">{{ ucfirst($case->status)}}</span>
                                                @else
                                                <span class="label bg-green">{{ ucfirst($case->status)}}</span>
                                                @endif

                                            </td>

                                            @if($case->status == 'closed')
                                            <td style="font-size: 16.5px" class=""> 
                                            <a class="text-muted" href="/admin/cases/{{ $case->id }}"> {{ mb_substr($case->subject,0,70) }}..</a>

                                            </td>
                                            @else
                                            <td style="font-size: 16.5px" class=""> {!! link_to_route('admin.cases.show', mb_substr($case->subject,0,70), [$case->id], []) !!}..</td>
                                            @endif

                                            
                                            <td class="">{{$case->products->name}}</td>

                                            <td class=""> 
                                            @if($case->cust_name) 
                                                {{ucfirst($case->cust_name)}}
                                                @else
                                                <a href="/admin/clients/{{$case->client_id}}" target="_blank">
                                                {!! $case->client->name !!} 
                                                </a>
                                            @endif
                                            <td> {{ $case->user->first_name }} </td>
                                            
                                            <td>
                                                @if ( $case->isEditable() || $case->canChangePermissions() )
                                                    <a href="{!! route('admin.cases.edit', $case->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/cases/general.error.cant-edit-this-document') }}"></i>
                                                @endif

                                                @if ( $case->enabled )
                                                    <a href="{!! route('admin.cases.disable', $case->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                                @else
                                                    <a href="{!! route('admin.cases.enable', $case->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                                @endif

                                                @if ( $case->isDeletable() )
                                                    <a href="{!! route('admin.cases.confirm-delete', $case->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/cases/general.error.cant-delete-this-document') }}"></i>
                                                @endif
                                            </td>


                                               @endif 

                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
<div style="text-align: center;"> {!! $cases->appends(\Request::except('page'))->render() !!} </div>
            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkClient[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
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
            $('#filter-case_status').select2();
            $('#filter-user').select2();
            $('#filter-clients').select2();
        });
    $("#btn-submit-filter").on("click", function () {
    let start_date = $('#filter-start_date').val();
    let end_date = $('#filter-end_date').val();
    let case_status = $('#filter-case_status').val();
    let user_id = $('#filter-user').val();
    let client_id = $('#filter-clients').val();
    let url = `{!! url('/') !!}/admin/cases?start_date=${start_date}&end_date=${end_date}&case_status=${case_status}&client_id=${client_id}&user_id=${user_id}`;
    window.location.href = url;
    // console.log(url);
 //    return false;
    // window.location.href = "{!! url('/') !!}/admin/leads?course_id="+course_id+"&source_id="+source_id+"&user_id="+user_id+"&rating="+rating+"&enq_mode="+enq_mode+"&start_date="+start_date+"&end_date="+end_date+"&status_id="+status_id+"&type="+type;
    });
    $("#btn-filter-clear").on("click", function () {
        window.location.href = "{!! url('/') !!}/admin/cases";
    });
    $('.searchable').select2();
    </script>

   

@endsection

