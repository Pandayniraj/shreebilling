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
   <p> These are objectives under objective type <a class="btn btn-sm btn-success" style="float: right;" href="{!! route('admin.appraisal.objective-types') !!}">Back</a></p>
   {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

<div class='row'> 
    <div class='col-md-12'> 
        <!-- Box -->
        {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
        <div class="box box-primary">

            <div class="box-header with-border">
                &nbsp;
                <a class="btn btn-social btn-foursquare" style="float: right;;" href="{!! route('admin.appraisal.objectives.create', [$objectiveType]) !!}" title="Create Objective">
                    <i class="fa fa-plus"></i>Create Objective
                </a>
                <span class="text-bold">Objective Type: {{ $objectiveType->name }}</span>
            </div>

            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="orders-table">

                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Objective</th>
                                <th>Mark</th>                                
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                         @if(isset($objectives) && !empty($objectives)) 
                            @foreach($objectives as $key=>$o)
                            <tr>
                                <td>{!! $key+1 !!}.</td>
                                <td>{!! $o->objective !!}</td>
                                <td>{!! $o->marks !!}</td>
                                <td>
                                    @if( $o->isEditable() )
                                    <a href="/admin/appraisal/objectives/{{$o->id}}/edit" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a> 
                                    @endif
                                    @if($o->isDeletable())
                                    <a href="{!! route('admin.appraisal.objectives.delete', $o->id) !!}" onclick="return confirm('Are You sure you want to delete this record ? ');" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
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

