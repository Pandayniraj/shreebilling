@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

       <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.cases.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">
                  
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="cases-table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/cases/general.columns.status') }}</th>
                                        <th>{{ trans('admin/cases/general.columns.subject') }}</th>
                                         <th>Clients</th>
                                        
                                        <th>{{ trans('admin/cases/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($cases) && !empty($cases))  
                                    @foreach($cases as $case)
                                        <tr>
                                            @if($case->viewed == '0')
                                            <td class="bg-info" align="center">{!! Form::checkbox('chkClient[]', $case->id); !!}</td>
                                            <td class="bg-info">
                                                 @if($case->status == 'pending')
                                                <span class="label bg-red">{{ ucfirst($case->status)}}</span>
                                                @elseif($case->status == 'new')
                                                <span class="label bg-orange">{{ ucfirst($case->status)}}</span>
                                                 @elseif($case->status == 'closed')
                                                <span class="label bg-primary">{{ ucfirst($case->status)}}</span>
                                                @else
                                                <span class="label bg-green">{{ ucfirst($case->status)}}</span>
                                                @endif
                                            </td>
                                            @if($case->status == 'closed')
                                            <td class="lead text-muted"> <h4>{!! link_to_route('admin.cases.show', mb_substr($case->subject,0,70), [$case->id], []) !!}..</h4></td>
                                            @else
                                            <td class="bg-info"> <h4> {!! link_to_route('admin.cases.show', mb_substr($case->subject,0,70), [$case->id], []) !!}..</h4></td>
                                            @endif

                                            <td class="bg-info">{!! $case->lead->name !!}</td> 
                                            
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
                                            <td>
                                                 @if($case->status == 'pending')
                                                <span class="label bg-red">{{ ucfirst($case->status)}}</span>
                                                @elseif($case->status == 'new')
                                                <span class="label bg-orange">{{ ucfirst($case->status)}}</span>
                                                 @elseif($case->status == 'closed')
                                                <span class="label bg-primary">{{ ucfirst($case->status)}}</span>
                                                @else
                                                <span class="label bg-green">{{ ucfirst($case->status)}}</span>
                                                @endif

                                            </td>

                                            @if($case->status == 'closed')
                                            <td class=""> 
                                            <h4><a class="text-muted" href="/admin/cases/{{ $case->id }}"> {{ mb_substr($case->subject,0,70) }}..</a></h4>

                                            </td>
                                            @else
                                            <td class=""><h4> {!! link_to_route('admin.cases.show', mb_substr($case->subject,0,70), [$case->id], []) !!}..</h4></td>
                                            @endif

                                            <td class="">{!! $case->lead->name !!}</td> 
                                            
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
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkClient[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>

   

@endsection
