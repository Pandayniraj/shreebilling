@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')
 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title !!}

                <small>{!! $page_description !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

  <div class='row'> 
        <div class='col-md-12'> 
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
                <div class="box box-primary">

                    <div class="box-header with-border">
                        &nbsp;
                        <a class="btn btn-social btn-foursquare" href="{!! route('admin.activity.create') !!}" title="Create Activity">
                            <i class="fa fa-plus"></i>Add Activity
                        </a>
                    </div>

                   <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="orders-table">

                                <thead>
                                    <tr>
                                        <th style="text-align: center;">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>id</th>
                                        <th>Code</th>
                                        <th>Project Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                   @if(isset($activities) && !empty($activities)) 
                                     @foreach($activities as $o)
                                        <tr>
                                            <td align="center">{!! Form::checkbox('chkClient[]', $o->id); !!}</td> 
                                            <td>{!! $o->id !!}</td>
                                            <td>{!! $o->code !!}</td>
                                            <td>{!! $o->project->name !!}</td>
                                            <td>{!! date('dS M y', strtotime($o->start_date)) !!}</td>
                                            <td>{!! date('dS M y', strtotime($o->end_date)) !!}</td>

                                            <td>
                                                @if( $o->isEditable() )
                                                    <a href="/admin/activity/{{$o->id}}/edit" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a> 
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/orders/general.error.cant-edit-this-document') }}"></i>
                                                @endif
                                                
                                                @if($o->isDeletable())
                                                    <a href="{!! route('admin.activity.confirm-delete', $o->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                                @else
                                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/orders/general.error.cant-delete-this-document') }}"></i>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                    @endif

                                </tbody>

                            </table>
                        </div> <!-- table-responsive -->
                    </div><!-- /.box-body -->
                 </div><!-- /.box -->

            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

