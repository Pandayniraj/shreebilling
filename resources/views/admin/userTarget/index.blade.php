@extends('layouts.master')
@section('content')

<style>
  /*#leads-table td:first-child{text-align: center !important;}
  #leads-table td:nth-child(2){font-weight: bold !important;}
  #leads-table td:last-child a {margin:0 2px;}*/
  /*tr { text-align:center; }*/
</style>

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/buttons.dataTables.css") }}" rel="stylesheet" type="text/css" />

    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                    @if( \Auth::user()->id == 1 || \Auth::user()->id == 5 || \Auth::user()->id == 4)
                    <a class="btn btn-primary btn-sm" href="{!! route('admin.userTarget.create') !!}" title="">
                        <i class="fa fa-plus-square"></i>  <strong>Add New User Target</strong>
                    </a>
                    @endif
                    <?php $y = 0; ?>
                    @foreach($userTargets as $uk => $uv)
                    <?php if($y != $uv->year) { $y = $uv->year; ?>
                        <a class="btn btn-primary btn-sm" href="/admin/userTarget/summary/{{ $uv->year }}" title="">
                            <i class="fa fa-info-circle"></i>  <strong>Summary of {{ $uv->year }}</strong>
                        </a>
                    <?php } ?>
                    @endforeach
                </div>
                <div class="box-body">

                    <div class="">
                        @if(\Auth::user()->id != '1' && \Auth::user()->id != '5')
                        <table class="table table-hover table-bordered" id="leads-table">
                            <thead>
                                <tr style="background: #000; color: #fff;">
                                    <td><strong>Programme</strong></td>
                                    @foreach($targets as $tk => $tv)
                                    <td>{{ $tv->user->first_name }}</td>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $ck => $cv)
                                <tr>
                                    <td><strong>{{ $cv->name }}</strong></td>
                                    @foreach($targets as $tk => $tv)
                                    <td><strong>{!! TaskHelper::userTarget($tv->id, $cv->id, $tv->user_id, $tv->year) !!}</strong></td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <table class="table table-hover table-bordered" id="leads-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Year</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userTargets as $utk => $utv)
                                <tr>
                                    <td style="text-align: left !important;">{{ $utv->user->first_name }}</td>
                                    <td>{{ $utv->year }}</td>
                                    <td>
                                        @if( \Auth::user()->id == 1 || \Auth::user()->id == 5 || \Auth::user()->id == 4)                                        
                                        <a href="/admin/userTarget/{{ $utv->id }}/edit" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a href="/admin/userTarget/{{ $utv->id }}/confirm-delete" data-toggle="modal" data-target="#modal_dialog" title="Delete"><i class="fa fa-trash deletable"></i></a>
                                        @else
                                        <i class="fa fa-pencil-square-o text-muted" title="You cannot edit this."></i>
                                        <i class="fa fa-trash-o text-muted" title="You cannot delete this."></i>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div> <!-- table-responsive -->

                </div><!-- /.box-body -->
            </div>

        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/dataTables.buttons.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/buttons.server-side.js") }}"></script>

@endsection
