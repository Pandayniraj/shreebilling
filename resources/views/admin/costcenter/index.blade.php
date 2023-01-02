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
                        <a class="btn btn-social btn-foursquare" href="{!! route('admin.costcenter.create') !!}" title="Create CostCenter">
                            <i class="fa fa-plus"></i>Add CostCenter
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
                                        <th>Name</th>
                                        <th>Owner Name</th>
                                        <th>Order Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                   @if(isset($costcenter) && !empty($costcenter)) 
                                     @foreach($costcenter as $o)
                                        <tr>
                                            <td align="center">{!! Form::checkbox('chkClient[]', $o->id); !!}</td> 
                                            <td>{!! $o->id !!}</td>
                                            <td>{!! $o->name !!}</td>
                                            <td>{!! $o->owner->username !!}</td>
                                            <td>{!! $o->order_details !!}</td>
                                            <td>
                                                @if( $o->isEditable() )
                                                    <a href="/admin/costcenter/{{$o->id}}/edit" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a> 
                                                @else
                                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/orders/general.error.cant-edit-this-document') }}"></i>
                                                @endif
                                                
                                                @if($o->isDeletable())
                                                    <a href="{!! route('admin.costcenter.confirm-delete', $o->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
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

