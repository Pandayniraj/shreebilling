@extends('layouts.master')
@section('content')

<style>
  tr td { text-align:left !important; }
</style>

    <div class='row'>
        <div class='col-md-12'>
        	<!-- Box -->
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="">
                            <table class="table table-hover table-bordered" id="leads-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>New Comm.. (7 days)</th>
                                        <th>Note</th>
                                        <th>Updated at</th>
                                    </tr>
                                    @foreach($note_udpated as $k => $v)
                                    <tr>
                                        <td>{{$v->name}}</td>
                                        <td><a href="/admin/leads/{{$v->lead_id}}?type=leads">{{$v->lead}}</a></td>
                                        <td>{{mb_substr($v->note,0,80)}}</td>
                                        <td> {{ \Carbon\Carbon::parse($v->updated_at)->diffForHumans()}} </td>
                                    </tr>
                                    @endforeach
                                </thead>
                            </table>
                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

