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
                          

                            {!! Form::model( $lead_id, ['route' => ['admin.lead.convert-update', $lead->id],  'id' => 'form_edit_lead','class' => 'form-horizontal'] ) !!}


                             <div class="col-md-12">
                          <div class="form-group">  
                <label class="control-label col-sm-2">Amount</label>
                    <div class="input-group ">
                        {!! Form::text('amount', null, ['class' => 'form-control', 'id'=>'amount']) !!}
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-credit-card"></i></a>
                        </div>
                    </div>
                </div>
                        </div>

                    <div class="col-md-12">
                        <div class="form-group">  
                        <label class="control-label col-sm-2">Date</label>
                            <div class="input-group ">
                                 {!! Form::text('target_date', null, ['class' => 'form-control datepicker', 'id' => 'target_date']) !!}
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">
                        Status
                      </label><div class="col-sm-10">
                        {!! Form::select('status_id', $lead_status, $lead_id->status_id, ['class' => 'form-control']) !!}
                    </div>
                    </div>




  <div class="col-md-12">
                          <div class="form-group">  
                <label class="control-label col-sm-2">Owner</label>
                    <div class="input-group ">
                         {!! Form::select('user_id',  $users, \Auth::user()->id, ['class' => 'form-control input-sm', 'id'=>'user_id']) !!}

                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-user"></i></a>
                        </div>
                    </div>
                </div>
                        </div>



            <div class="col-md-6">

                        <input type="hidden" name="lead_id" value="{{ Request::segment(3) }}">
                        <input type="hidden" name="lead_type" value="customer">
                        {!! Form::button( 'Convert', ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                       
                    
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

 <script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        });

      });
</script>
@endsection

