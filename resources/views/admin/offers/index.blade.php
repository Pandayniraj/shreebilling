@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {{-- Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->

        <div class="box box-primary">
            <div class="box-header with-border">
                @if(\Auth::user()->hasRole('admins'))
                <a class="btn btn-default btn-sm" href="{!! route('admin.offers.create') !!}" title="Create a new offers">
                    <i class="fa fa-plus-square"></i> &nbsp;Add
                </a>
                @endif

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="FiscalYear-table">
                        <thead>
                            <tr class="bg-info">
                                <th>ID</th>
                               <th>Title</th>
                               <th>Phone</th>
                               <th>Email</th>
                               <th>Price</th>
                               <th>Sequence</th>
                               <th>Start Date</th>
                               <th>End Date</th>
                               <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($offers as $v)
                            <tr>
                                <td>#{{$v->id}}</td>
                               <td>{{$v->offer_title}}</td>
                               <td>{{$v->phone}}</td>
                               <td>{{$v->email}}</td>
                               <td>{{env('APP_CURRENCY')}} {{$v->offer_price}}</td>
                               <td ><span class="order_sequence" data-id='{{$v->id}}'>{{$v->order_sequence}}</span></td>
                               <td>{{ $v->available_from}} </td>
                               <td> {{$v->available_to}}</td>
                                <td>
                                    @if( $v->isEditable() || $v->canChangePermissions() )
                                    <a href="{!! route('admin.offers.edit', $v->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                    <a href="{{route('admin.offers.enabledisable',$v->id)}}" title="Enable Disable">
                                        @if($v->enabled)
                                        <i class="fa fa-check-circle" style="color: green;"></i>
                                        @else
                                        <i class="fa fa-close (alias)" style="color: red;"></i>
                                        @endif
                                    </a>
                                    @else
                                    <i class="fa fa-edit text-muted" ></i>
                                    @endif
                                    @if( $v->isDeletable() )
                                    <a href="{!! route('admin.offers.confirm-delete', $v->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                    @else
                                    <i class="fa fa-trash text-muted" ></i>
                                    @endif
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div> <!-- table-responsive -->

            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div><!-- /.col -->

</div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<link href="/x-editable/bootstrap-editable.css" rel="stylesheet" />
<script src="/x-editable/bootstrap-editable.min.js"></script>
<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkFiscalYear[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }


$('.order_sequence').each(function(){

    let id = $(this).attr('data-id');

     $(this).editable({
        success: function(response, newValue) {

            let data = {
                id: id,
                value: newValue,
                _token: $('meta[name="csrf-token"]').attr('content'),
            }


            $.post(`/admin/offers/${id}/reorder`,data,function(response){

                alert("Successfully updated");

            }).fail(function(){
                alert("Failed to update");
            });
        }
        , validate: function(value) {
            if ($.isNumeric(value) == '') {
                return 'Only Numberical value is allowed';
            } 
        }
    });



});

</script>


@endsection
