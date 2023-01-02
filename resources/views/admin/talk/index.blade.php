@extends('layouts.master')
@section('content')

<style type="text/css">
    


</style>


    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            Welcome to talk
        </div><!-- /.col -->

        <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">All Users</div>

                <div class="panel-body">
                @foreach($users as $user)
                    <table class="table">
                        <tr>
                            <td>
                                <img src="{{$user->avatar}}">
                                {{$user->first_name}} {{$user->last_name}} 
                            <sub >
                                @if(Cache::has('user-is-online-' .$user->id))
                                <i class="fa fa-circle text-success"></i>
                                @else
                                    <i class="fa fa-circle"></i>
                                @endif
                            </sub>
                            </td>
                            <td>
                                <a href="{{route('message.read', ['id'=>$user->id])}}" class="btn btn-success pull-right">Send Message</a>
                            </td>
                        </tr>
                    </table>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->


@endsection
