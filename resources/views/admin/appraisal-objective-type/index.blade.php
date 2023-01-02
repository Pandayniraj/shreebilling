@extends('layouts.master')

@section('head_extra')

@include('partials._head_extra_select2_css')
<style>
.badge-success { background-color: #28A745 !important; }
.badge-danger { background-color: #DC3545 !important; }
</style>
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
                <a class="btn btn-social btn-foursquare" href="{!! route('admin.appraisal.objective-types.create') !!}" title="Create Objective Type">
                    <i class="fa fa-plus"></i>Create Objective Type
                </a>
            </div>

            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="orders-table">

                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Title</th>
                                <th>Total Points</th>
                                <th>Master</th>
                                <th>Template</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>

                        <tbody>

                           @if(isset($objectivetypes) && !empty($objectivetypes))
                           @foreach($objectivetypes as $key=>$o)
                           <tr>
                            <td>{!! $key+1 !!}.</td>
                            <td>{!! $o->name !!}</td>
                            <td>{!! $o->points !!}</td>
                            <td>
                                @if($o->is_master == 'master')
                                <span class="btn bg-success">{!! ucfirst($o->is_master) !!}</span>
                                @else
                                --
                                @endif
                            </td>
                            <td>{!! $o->template->appraisalTemplate->name !!}</td>
                            <td><span class="badge @if($o->status) badge-success @else badge-danger @endif">{{ $o->status ? 'Enabled' : 'Disabled' }}</span></td>
                            <td>

                                <a href="/admin/objective/copy/{{$o->id}}"><i class="fa fa-copy"></i></a>

                                <a href="/admin/appraisal/{{$o->id}}/objectives" title="Manage Objectives" class="btn btn-sm btn-primary"> Objectives</a>
                                <a href="/admin/appraisal/objective-types/{{$o->id}}/edit" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>

                                <a href="{!! route('admin.appraisal.objective-types.confirm-delete', $o->id) !!}"  title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>

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

