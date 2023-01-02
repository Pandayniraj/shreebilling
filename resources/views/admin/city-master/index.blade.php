@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title ?? '' }}
        <small>{{ $page_description ?? ''}}</small>
    </h1>


    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<div class='row'>

    <div class='col-md-12'>

        <!-- Box -->

        <div class="box box-primary">
            <div class="box-header with-border">

                <a class="btn btn-primary btn-sm pull-right" title="Create" href="{{ route('admin.city.create') }}">
                    <i class="fa fa-plus"></i>&nbsp;<strong> Create New City</strong>
                </a>


            </div>

              <div class="wrap" style="margin-top:5px;">
                  <form method="get" action="/admin/recipe/search">
                      <div class="filter form-inline" style="margin:0 30px 0 0;">
{{--                          {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:120px;', 'class' => 'form-control', 'id'=>'start_date', 'placeholder'=>'Bill start date...','autocomplete' =>'off']) !!}&nbsp;&nbsp;--}}
{{--                          <!-- <label for="end_date" style="float:left; padding-top:7px;">End Date: </label> -->--}}
{{--                          {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:120px; display:inline-block;', 'class' => 'form-control', 'id'=>'end_date', 'placeholder'=>'Bill end date..','autocomplete' =>'off']) !!}&nbsp;&nbsp;--}}
{{--                          {!! Form::text('bill_no', \Request::get('bill_no'), ['style' => 'width:120px; display:inline-block;', 'class' => 'form-control', 'id'=>'bill_no', 'placeholder'=>'Enter bill number...','autocomplete' =>'off']) !!}&nbsp;&nbsp;--}}
{{--                          {!! Form::select('client_id', ['' => 'Select Customer'] + $clients, \Request::get('client_id'), ['id'=>'filter-customer', 'class'=>'form-control searchable', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;--}}
{{--                          {!! Form::select('fiscal_year', ['' => 'Select Fiscal Year']+$fiscal_years, \Request::get('fiscal_year'), ['id'=>'fiscal_year', 'class'=>'form-control', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;--}}
                          <input type="hidden" name="search" value="true">
{{--
                      </div>
                  </form>        <input type="hidden" name="type" value={{ Request::get('type') }}>--}}
                  {{--                          <button class="btn btn-primary" id="btn-submit-filter" type="submit">--}}
                  {{--                              <i class="fa fa-list"></i> Filter--}}
                  {{--                          </button>--}}
                  {{--                          <a href="/admin/invoice1" class="btn btn-danger" id="btn-filter-clear" >--}}
                  {{--                              <i class="fa fa-close"></i> Clear--}}
                  {{--                          </a>--}}
                </div>
                  {!! Form::open( array('route' => 'admin.orders.enable-selected', 'id' => 'frmClientList') ) !!}
            <div class="box-body">

                <div class="table-responsive">
                    <a href=""> </a>
                    <table class="table table-hover table-bordered table-striped" id="orders-table">
                        <thead>
                            <tr class="bg-danger">
                                <th>
                                    id
                                </th>
                                <th>
                                    Country
                                </th>
                                <th>
                                     City
                                </th>
                                <th>
                                   Latitude
                                </th>
                                <th>
                                   Longitude
                                </th>
                                <th>
                                   Iso2
                                </th> <th>
                                 Iso3
                                </th>

                                <th>Tools</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($cities) && !empty($cities))
                            @foreach($cities as $o)
                            <tr>
                                <td>
                                    {{ $o->id}}
                                </td>
                                <td>{{$o->country}} </td>
                                <td>{{$o->city}}</td>
                                <td>{!! $o->lat !!} </td>
                                <td>{!! $o->lng !!} </td>
                                <td> {!! $o->iso2 !!} </td>
                                <td>{!! $o->iso3!!} </td>
                                <td>

                                    <a href="{{ route('admin.city.edit',$o->id) }}" title="edit"><i class="fa fa-edit"></i></a>
                                    <?php
                                    $datas = '<a href="'.route('admin.city.confirm-delete',$o->id).'?type='.\Request::get('type').'" data-toggle="modal" data-target="#modal_dialog" title="Delete"><i class="fa fa-trash-o deletable"></i></a>';

                                    ?>
{{--                                    <a class="btn btn-round btn-xs bg-olive" href="{{route('admin.recipe.show',$o->id)}}" title="View"><i class="fa fa-file-text"></i></a>--}}
                                    <?php
                                    if($datas)
                                        echo $datas ?>


                                </td>

                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                    {!! $cities->render() !!}

                </div> <!-- table-responsive -->

            </div><!-- /.box-body -->
        <!-- /.box -->
  <!-- /.col -->

<!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkClient[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

</script>

<script>
    $(function() {
        $('#orders-table').DataTable({
            pageLength: 25
            , ordering: false
        });
    });

     $(function() {
        $('#start_date').datepicker({
                 //format: 'YYYY-MM-DD',
                dateFormat: 'yy-m-d',
                sideBySide: true,

            });
        $('#end_date').datepicker({
                 //format: 'YYYY-MM-DD',
                dateFormat: 'yy-m-d',
                sideBySide: true,

            });
        });

</script>

    <script type="text/javascript">
      $('.searchable').select2();
      $('.select2').select2();
    </script>

@endsection
