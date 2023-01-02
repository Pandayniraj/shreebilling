@extends('layouts.master')
@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<style>
  tr td { text-align:left !important; }
</style>
    <div class='row'>
        <div class='col-md-6'>
          <!-- Box -->
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="">
                            <p> Total Users for <strong>{{$outlets->name}}</strong> </p>
                            <table class="table table-hover table-bordered" id="leads-table">
                                <thead>
                                    <tr>
                                        <th>Outlet</th>
                                        <th>User</th>
                                        <th>Action</th>
                                    </tr>
                                    @if($outlet_users)
                                    @foreach($outlet_users as $k => $v)
                                    <tr>
                                        <td>{{ $v->outlet->name }}</td>
                                        <td>{{ ucfirst(trans($v->user->username??'')) }} </td>
                                        <td >&nbsp;&nbsp;&nbsp;&nbsp;<a href="{!! route('admin.hotel.pos-outlets.confirm-delete.adduser', $v->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash-o deletable"></i></a></td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </thead>
                            </table>
                        <form method="post" action="{{route('admin.hotel.pos-outlets.postuser',$id)}}">
                            {{csrF_field()}}
                            <label>Add User</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <select class = 'form-control input-sm searchable select2' name="user_id" id="to_room" required>
                                   <option value="">Select User</option>
                                    @foreach($users as $us)
                                    <option value="{{$us->id}}">{{$us->username}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="outlet_id" value="{{$id}}">
                             <br>
                            <button class="btn btn-primary" type="submit">Add User</button>
                            <a class="btn btn-default" 
                            href="{{ route('admin.pos-outlets.index') }}"> Cancel </a>
                        </form>
                    <div class="row">
                        <div class="col-md-3">
                        </div>
                    </div>
            </div>
                        </div> <!-- table-responsive -->
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
@section('body_bottom')

 <script type="text/javascript">
    
     $(document).ready(function(){
            $('.searchable').select2();
        });
</script>
@endsection  