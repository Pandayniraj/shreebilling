@extends('layouts.master')
@section('content')

<style>
  tr td { text-align:left !important; }
</style>

    <div class='row'>
        <div class='col-md-6'>
        	<!-- Box -->
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="">
                            <p> Transfer History for Lead #{{ $lead_id }}</p>
                            <table class="table table-hover table-bordered" id="leads-table">
                                <thead>
                                    <tr>
                                        <th>From User</th>
                                        <th>To User</th>
                                        <th>Date</th>
                                    </tr>
                                    @if($transfer_history)
                                    @foreach($transfer_history as $k => $v)
                                    <tr>
                                        <td>{{ \TaskHelper::getUser($v->from_user_id)->first_name }}
                                        </td>
                                        <td>{{ \TaskHelper::getUser($v->to_user_id)->first_name  }} </td>
                                        <td>{{ $v->created_at }} </td>
                                    </tr>
                                    @endforeach
                                    @endif

                                </thead>
                            </table>

                            {!! Form::open( ['route' => 'admin.lead.transfer-update', 'class' => 'form-horizontal', 'id' => 'form_edit_lead'] ) !!}



                            <label>Transfer Lead To</label> <i class="fa fa-user"></i> 
                            <div class="row">

                            <div class="col-md-3">
                            
                      
                        {!! Form::select('to_user_id',  $users, \Auth::user()->first_name, ['class' => 'form-control input-sm', 'id'=>'to_user_id']) !!}
                        <input type="hidden" name="lead_id" value="{{ Request::segment(3) }}">
                       </div>

                        <div class="col-md-3">
                    
                        {!! Form::button( 'Transfer', ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                       
                    
                </div>

                </div>

                    {!! Form::close() !!}


                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('body_bottom')
 <script type="text/javascript">
    $("#btn-submit-edit").on("click", function () {
        // Post form.
        $("#form_edit_lead").submit();
    });
</script>
@endsection

