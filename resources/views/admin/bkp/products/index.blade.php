@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title ?? "Page Title" }}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('admin/courses/general.page.index.table-title') }}</h3>
                &nbsp;
                <a class="btn btn-default btn-sm" href="{!! route('admin.products.create') !!}" title="{{ trans('admin/courses/general.button.create') }}">
                    <i class="fa fa-plus-square"></i>
                </a>
                &nbsp;
                <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmCourseList'].action = '{!! route('admin.products.enable-selected') !!}';  document.forms['frmCourseList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                    <i class="fa fa-check-circle"></i>
                </a>
                &nbsp;
                <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmCourseList'].action = '{!! route('admin.products.disable-selected') !!}';  document.forms['frmCourseList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                    <i class="fa fa-ban"></i>
                </a>
                 <a class="btn btn-default btn-sm multi-delete-button" href="#">
                    <i class="fa fa-trash" style="color:red;"></i>
                </a>
                <div class="box-tools pull-right">
                    <form method="GET" action="/admin/products">

                    {!! Form::select('product_cat_master',$producttypeMaster,Request::get('product_cat_master'),['class'=>'bg-info','style'=>'padding: 3px;','placeholder'=>'Select Master Category'])  !!}

                    {!! Form::select('product_cat',$productCategory,Request::get('product_cat'),['class'=>'bg-danger','style'=>'padding: 3px;','placeholder'=>'Select Category'])  !!}


                    <input type="text" class="input-sm" placeholder="Type To search..." id='search-term' name="term">


                   <button class="btn btn-primary btn-sm" id='search' name="productSearch" type="submit"><i class="fa fa-search"></i>&nbsp;Search</button>
                     <button type="submit" value="excel"  name="productSearch" class="btn btn-success btn-sm"><i class="fa  fa-cloud-download"> Download</i> </button>
                    <a href="/admin/products" class="btn btn-danger btn-sm"><i class="fa fa-close"></i>&nbsp;Clear</a>
                </div>
            </div>
               {!! Form::open( array('route' => 'admin.products.enable-selected', 'id' => 'frmCourseList') ) !!}
            <div class="box-body">

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-responsive" id="clients-table">
                        <thead>
                            <tr class="bg-info">
                                <th style="text-align: center">
                                    <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">

                                    </a>
                                </th>
                                <th>{{ trans('admin/courses/general.columns.name') }}</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Outlet</th>
                                <th>Store</th>
                                <th>Cost</th>
                                <th>Price</th>
                                <th>OH</th>
                                <th>Unit</th>
                                <th>{{ trans('admin/courses/general.columns.actions') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th style="text-align: center; width:10px">
                                    <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                        <i class="fa fa-check-square-o"></i>
                                    </a>
                                </th>
                                <th>{{ trans('admin/courses/general.columns.name') }}</th>
                                <th>Category</th>
                                <th> Type</th>
                                <th>Outlet</th>
                                <th>Store</th>
                                <th>Cost</th>
                                <th>Price</th>
                                <th>OH</th>
                                <th>Unit</th>

                                <th>{{ trans('admin/courses/general.columns.actions') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td align="center">{!! Form::checkbox('chkCourse[]', $course->id); !!}</td>
                                <td style="font-size:16.5px">{!! link_to_route('admin.products.edit', $course->name, [$course->id], []) !!}</td>
                                <td>{!! $course->category['name'] !!}</td>
                                <td>{!! $course->producttypemaster['name'] !!}</td>
                                <td>{!! $course->outlet->name !!}</td>
                                <td>{!! $course->store->name !!}</td>
                                <td>{!! $course->cost !!}</td>
                                <td>{!! $course->price !!}</td>
                                <td>{!! \TaskHelper::getTranslations($course->id) !!}</td>
                                <td>{!!$course->unit->name !!}</td>
                                <td>
                                    @if ( $course->isEditable() || $course->canChangePermissions() )
                                    <a href="{!! route('admin.products.edit', $course->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                    @else
                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/courses/general.error.cant-edit-this-course') }}"></i>
                                    @endif

                                    @if ( $course->enabled )
                                    <a href="{!! route('admin.products.disable', $course->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                    @else
                                    <a href="{!! route('admin.products.enable', $course->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                    @endif

                                    @if ( $course->isDeletable() )
                                    <a href="{!! route('admin.products.confirm-delete', $course->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                    @else
                                    <i class="fa fa-trash-alt text-muted" title="{{ trans('admin/courses/general.error.cant-delete-this-course') }}"></i>
                                    @endif
                                </td>
                            </tr>
                            @if($course->childProducts)
                                @foreach($course->childProducts as $product)
                                    <tr>
                                        <td align="center">{!! Form::checkbox('chkCourse[]', $product->id); !!}</td>
                                        <td style="margin-left: 20px;"><span style="margin-left: 30px;"><i class="fa fa-sort-amount-desc text-success"></i>
                                    {!! link_to_route('admin.products.edit', $product->name, [$product->id], []) !!}</span></td>
                                        <td>{!! $product->category['name'] !!}</td>
                                        <td>{!! $product->producttypemaster['name'] !!}</td>
                                        <td>{!! $product->outlet->name !!}</td>
                                        <td>{!! $product->store->name !!}</td>
                                        <td>{!! $product->cost !!}</td>
                                        <td>{!! $product->price !!}</td>
                                        <td>-</td>
                                        <td>{!! $product->unit->name!!}</td>

                                        <td>
                                    <span>
                                    @if ( $product->isEditable() || $product->canChangePermissions() )
                                            <a href="{!! route('admin.products.edit', $product->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                        @else
                                            <i class="fa fa-edit text-muted" title="{{ trans('admin/courses/general.error.cant-edit-this-course') }}"></i>
                                        @endif

                                        @if ( $product->enabled )
                                            <a href="{!! route('admin.products.disable', $product->id) !!}" title="{{ trans('general.button.disable') }}"><i class="fa fa-check-circle enabled"></i></a>
                                        @else
                                            <a href="{!! route('admin.products.enable', $product->id) !!}" title="{{ trans('general.button.enable') }}"><i class="fa fa-ban disabled"></i></a>
                                        @endif

                                        @if ( $product->isDeletable() )
                                            <a href="{!! route('admin.products.confirm-delete', $product->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                        @else
                                            <i class="fa fa-trash-alt text-muted" title="{{ trans('admin/courses/general.error.cant-delete-this-course') }}"></i>
                                        @endif
                                    </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                    {!! $courses->appends(Request::except('page')) !!}
                </div> <!-- table-responsive -->

            </div><!-- /.box-body -->
        </div><!-- /.box -->
        {!! Form::close() !!}
    </div><!-- /.col -->

</div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
@include('confirm-multiple-delete')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkCourse[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

    // $('#search').click(function() {
    //     let val = $('#search-term').val();
    //     window.location.href = `{{ url('/') }}/admin/products?term=${val}`;
    // });

</script>


<script type="text/javascript">
    $('.multi-delete-button').multipleDeleteIndex({

        title: "Are you sure !!",
        body: 'Delete All Selected Products',
        route: '{{ route('admin.products.multipledelete') }}',
        formid:'frmCourseList',
    });
</script>

<script>
        $(function() {
            $('#clients-table').DataTable({
                dom: 'Bfrtip',
                buttons: [],
                'pageLength'  : 65,
                'lengthChange': true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true,
                "paging"      : false
            });
        });

    </script>
@endsection
