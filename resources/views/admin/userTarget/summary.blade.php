@extends('layouts.master')
@section('content')

<style>
  /*tr { text-align:center; }*/
</style>

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/buttons.dataTables.css") }}" rel="stylesheet" type="text/css" />

    <div class='row'>
        <div class='col-md-12'>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">

                    <div class="">
                        <table class="table table-hover table-bordered" id="leads-table">
                            <thead>
                                <tr style="background: #000; color: #fff;">
                                    <td><strong>Products</strong></td>
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
