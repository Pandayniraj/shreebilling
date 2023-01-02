@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Products & Inventory
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {{ TaskHelper::topSubMenu('topsubmenu.purchase')}}
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

                <a href="{!! route('admin.download.products.pdf.index') !!}"><button class="btn btn-info btn-sm"> <i class="fa  fa-file-pdf-o">&nbsp;</i> PDF Export</button></a>

                <a class="btn btn-primary btn-sm" href="{{route('admin.products.import-export')}}" title="Import/Export Budget">
                        <i class="fa fa-download"></i>&nbsp;<strong> Import/Export</strong>
                    </a>

<div class="box-tools pull-right">
                    <form method="GET" action="/admin/products">

              

                    {!! Form::select('product_cat',$productCategory,Request::get('product_cat'),['class'=>'bg-danger','style'=>'padding: 3px;','placeholder'=>'Select Category'])  !!}
                
                   
                    <input type="text" class="input-sm" placeholder="Type To search..." id='search-term' name="term">
                    <button class="btn btn-primary btn-sm" id='search' type="submit"><i class="fa fa-search"></i>&nbsp;Search</button>
                    <a href="/admin/products" class="btn btn-danger btn-sm"><i class="fa fa-close"></i>&nbsp;Clear</a>
                    </form>
                </div>
            </div>

            <form method="POST" id='frmCourseList'>

                @csrf

            <div class="box-body">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped" id="courses-table">
                        <thead>
                            <tr class="bg-purple">
                                <th></th>
                                <th style="text-align: center">
                                    Img.
                                </th>
                                <th> ID</th>
                                <th> UPC </th>
                                <th>Barcode</th>
                                <th>Category</th>

                                <th>{{ trans('admin/courses/general.columns.name') }}</th>
                                <th> Unit </th>
                                 <th>Agent Price</th>
                                <th>SP w/o VAT</th>
                                <th>SP with VAT</th>

                                <th> Warranty </th>
                                <th>Onhand</th>
                                <th>{{ trans('admin/courses/general.columns.actions') }}</th>
                            </tr>
                        </thead>
               
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td>
                                   {!! \Form::checkbox('chkCourse[]', $course->id) !!}
                                </td>
                                <td style="text-align: center;">

                                    @if($course->product_image && file_exists(public_path("products/{$course->product_image}")))
                                    <img style="max-width:30px" class="profile-user-img img-responsive img-circle" 
                                    src="/products/{{$course->product_image}}"/> 
                                    @else
                                        <img src="{{ TaskHelper::getAvatarAttribute($course->name) }}" height="22" width="22" 

                                       />
                                    
                                    @endif
                                </td>
                                <td> {{\FinanceHelper::getAccountingPrefix('PRODUCT_PRE')}}{{ $course->id }}</td>
                                <td> {{ $course->product_code }}</td>
                                <td><a target="_blank" href="/admin/products/barcode/{!! $course->id !!}/create">Print</a></td>
                                <td>{!! $course->category->name !!}</td>

                                <td style="font-size: 16.5px">{!! link_to_route('admin.products.edit', $course->name, [$course->id], []) !!}</td>
                                
                                <td>{!! $course->unit->symbol ?? '' !!}</td>
                                <td>{!! number_format($course->agent_price,2) !!}</td>
                                <td>{!! number_format($course->price,2) !!}</td>
                                <td>{!! number_format($course->price+($course->price*0.13),2) !!}</td>
                                <td>{!! $course->warranty==0?'NO': $course->warranty.' months'!!}</td>
                                <td>{!! \TaskHelper::getTranslations($course->id) !!}</td>
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
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/courses/general.error.cant-delete-this-course') }}"></i>
                                    @endif
                                    <a href="{{route('admin.products.int_purch',$course->id)}}"><i class="fa fa-globe" title="International Purchase"></i>
                                     </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                   {!! $courses->appends(Request::except('page')) !!}
                </div> <!-- table-responsive -->

            </div><!-- /.box-body -->
            </form>
        </div><!-- /.box -->

    </div><!-- /.col -->

</div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkCourse[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

</script>
@include('confirm-multiple-delete')
<script type="text/javascript">
    $('.multi-delete-button').multipleDeleteIndex({

        title: "Are you sure !!",
        body: 'Delete All Selected Products',
        route: '{{ route('admin.products.multipledelete') }}',
        formid:'frmCourseList',
    });
</script>

@endsection
