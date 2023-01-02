@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
<style>
    .box-comment { margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #eee; }
    .box-comment img {float: left; margin-right:10px;}
    .username { font-weight: bold; }
    .comment-text span{display: block;}
</style>
    <div class='row'>
        <div class='col-md-12'>
        	<div class="box box-primary">
            	<div class="box-header with-border">
                    <h4>
                        <label>Job No.&nbsp;</label><span>#{{$case->job_no}}</span>
                        <label>Case No. &nbsp;</label><span>#{{ env('SHORT_NAME')}}{{$case->id}}</span>
                    </h4>
                	<h3> Case: {!! $case->subject !!} <small>
                        
                            <a href="{!! route('admin.cases.index') !!}" class='btn btn-primary btn-xs'>{{ trans('general.button.close') }}</a>
                            @if ( $case->isEditable() || $case->canChangePermissions() )
                                <a href="{!! route('admin.cases.edit', $case->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default btn-xs'>{{ trans('general.button.edit') }}</a>
                            @endif

                            <a class="btn btn-default btn-xs" href="#"> Send SMS </a>
                    </small>
                    </h3>
                    <p> {!! $case->description !!} </p>
                    <span class="pull-right"> Owner {{ $case->user->first_name }}
                    <img class="img-circle img-sm" height="24" width="24" src="{{ TaskHelper::getProfileImage($case->user_id) }}" alt="">
                    </span> 
                </div>

               {!! $case->resolution !!}

                <div class="box-body">
    
                    {!! Form::model($case, ['route' => 'admin.cases.index', 'method' => 'GET']) !!}
    
                    <div class="content">
                        <div class="col-md-6">
                            @if($case->ticket_name)
                            <div class="form-group">
                                {!! Form::label('ticket_name', trans('admin/cases/general.columns.ticket_name')) !!}
                                {!! Form::text('ticket_name', $case->ticket_name, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                            @endif

                            <div class="form-group">
                                {!! Form::label('priority', trans('admin/cases/general.columns.priority')) !!}
                                {!! Form::text('priority', $case->priority, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', trans('admin/cases/general.columns.status')) !!}
                                {!! Form::text('status', $case->status, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('type', trans('admin/cases/general.columns.type')) !!}
                                {!! Form::text('type', $case->type, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                         

                        </div>

                        <div class="col-md-6">
                            
                            @if($case->ticket_email)
                            <div class="form-group">
                                {!! Form::label('ticket_email', trans('admin/cases/general.columns.ticket_email')) !!}
                                {!! Form::text('ticket_email', $case->ticket_email, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                            @endif

                            

                            <div class="form-group">
                                {!! Form::label('client_id', trans('admin/cases/general.columns.client_id')) !!}
                                {!! Form::text('client_id', $case->client->name, ['class' => 'form-control', 'readonly']) !!}
                            </div>
                            

                            <div class="form-group">
                                {!! Form::label('assigned_to', trans('admin/cases/general.columns.assigned_to')) !!}
                                {!! Form::text('assigned_to', $case->assigned->first_name.' '.$case->assigned->last_name, ['class' => 'form-control', 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                <label>
                                    {!! Form::checkbox('enabled', '1', $case->enabled, ['disabled']) !!} {!! trans('general.status.enabled') !!}
                                </label>
                            </div>

                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                @if($case->attachment != '')
                                {!! Form::label('attachment', 'Attachment') !!}
                                    <strong>File: </strong>
                                    <a href="{{url('/'). '/case_attachments/'.$case->attachment}}" target="_blank">{{url(). '/case_attachments/'.$case->attachment}}</a>
                                @endif
                            </div>

                            
                        </div>
                        <div class="clearfix"></div>
                       
                                  <div class="row">
                <div class="col-md-6">
                    <h4>CUSTOMER SERVICE DEPARTMENTS</h4>
            <div class="form-group">
            {!! Form::label('address', 'Address') !!}
            {!! Form::text('address', null, ['class' => 'form-control input-sm' ,'readonly']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('city', 'City') !!}
            {!! Form::text('city', null, ['class' => 'form-control input-sm' ,'readonly']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('telephone', 'Telephone') !!}
            {!! Form::text('telephone', null, ['class' => 'form-control input-sm' ,'readonly']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('dealer', 'Dealer Name') !!}
            {!! Form::text('dealer_name', $case->dealer->name, ['class' => 'form-control input-sm' ,'readonly']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('preferred', 'Preferred Date/Time') !!}
            {!! Form::text('preff_d_t', null, ['class' => 'form-control preferred input-sm' ,'readonly']) !!}
        </div>
         <div class="form-group">
            {!! Form::label('customerreq', 'CUSTOMER REQUIREMENTS') !!}
            {!! Form::text('cust_req', null, ['class' => 'form-control input-sm' ,'readonly']) !!}
        </div>
           <div class="form-group">
            {!! Form::label('type', 'Problem Observersation') !!}
            {!! Form::textarea('prob_obs', null, ['class'=>'form-control input-sm', 'rows'=>'3','readonly']) !!}
        </div>
        </div>
        <div class="col-md-6">
        <h4>JOB CARD / INSTALLATION SHEET</h4>
        <div class="form-group">
            {!! Form::label('Cal Date','Cal Date/Time' ) !!}
            {!! Form::text('cal_date', null, ['class' => 'form-control caldate input-sm', 'id'=>'subject' ,'readonly']) !!}
        </div>
         <div class="form-group">
            {!! Form::label('Product', 'Product') !!}
            {!! Form::text('product', $case->products->name, ['class' => 'form-control input-sm', 'readonly']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('model', 'Model No.') !!}
            {!! Form::text('model_no', $case->modelNum->model_name, ['class' => 'form-control input-sm', 'readonly']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('serial', 'Serial No.') !!}
            {!! Form::text('serial_no', $case->serialNum->serial_num, ['class' => 'form-control input-sm', 'readonly']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('dateofpur', 'Date Of Puchase') !!}
            {!! Form::text('dop', null, ['class' => 'form-control dop input-sm','readonly']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('dateofamc', 'Date Of AMC') !!}
            {!! Form::text('do_amc', null, ['class' => 'form-control amc input-sm','readonly']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('sysstatus', 'System Status') !!}
            {!! Form::text('sys_status', null, ['class' => 'form-control input-sm','readonly']) !!}
        </div>
        </div>
            </div>
    

            <div class="row">

        <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('action', 'Action Taken') !!}
            {!! Form::textarea('action_taken', null, ['class'=>'form-control input-sm', 'rows'=>'3','readonly']) !!}
        </div>
    </div>
    @if(count($casepart1) > 0)
       <table class="table" style="float: left;">
                    <tbody class="moretr">
                    <tr>
                        <th>Part`s Code</th>
                        <th>Description</th>
                        <th>Quanity</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Remark</th>
                    </tr>
                    @foreach($casepart1 as $case1)
                    <tr>
                        <td><input class="form-control input-sm" type="text" name="part_code[]" placeholder="parts code" value="{{$case1->part_code}}" readonly=""></td>
                        <td><input class="form-control input-sm" type="text" name="description[]" placeholder="description" value="{{$case1->description}}" readonly=""></td>
                        <td><input class="form-control quanity input-sm" type="text" name="quantity[]" placeholder="quanity" value="{{$case1->quantity}}" readonly=""></td>
                        <td><input class="form-control rate input-sm" type="text" name="rate[]" placeholder="rate" value="{{$case1->rate}}" readonly=""></td>
                        <td><input class="form-control amount input-sm" type="text" name="amount[]" placeholder="amount" value="{{$case1->amount}}" readonly=""></td>
                        <td ><input type="text" name="remark[]" class="form-control input-sm" style="float: left" placeholder="remark" value="{{$case1->remark}}" readonly=""></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td><span>Customer`s Comments</span></td>
                        <td><input class="form-control input-sm" type="text" name="cust_comments" value="{{$case->cust_comments}}" readonly=""></td>
                        <td><span>Payment details</span></td>
                        <td><input class="form-control input-sm" type="text" name="payment_details" value="{{$case->payment_details}}" readonly=""></td>
                     
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td style="float: right">Total</td>
                        <td><input class="form-control totalamount input-sm" type="number" name="total_amount" id="totalamount" readonly="" value="{{$case->total_amount}}" readonly=""></td>
                        <td><input class="form-control input-sm" type="text" name="total_amount_rem" placeholder="remark" value="{{$case->total_amount_rem}}" readonly=""></td>
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td style="float: right;">Labour</td>
                        <td><input class="form-control input-sm" type="text" name="labour" id="labour" value="{{$case->labour}}" readonly=""></td>
                        <td><input class="form-control input-sm" type="text" name="labour_rem" placeholder="remark" value="{{$case->labour_rem}}" readonly=""></td>
                     </tr>
                       <tr>
                        <td colspan="3"></td>
                        <td style="float: right">Transport</td>
                         <td><input class="form-control input-sm" type="text" name="transport" id="transport" value="{{$case->transport}}" readonly=""></td>
                         <td><input class="form-control input-sm" type="text" name="transport_rem" placeholder="remark" value="{{$case->transport_rem}}" readonly=""></td>
                     </tr>
                     <tr>
                        <td colspan="3"></td>
                        <td style="float: right">AMC</td>
                         <td><input class="form-control input-sm" type="text" name="amc"  id="amc" value="{{$case->amc}}" readonly=""></td>
                        <td><input class="form-control input-sm" type="text" name="amc_rem" placeholder="remark" value="{{$case->amc_rem}}" readonly=""></td>
                     </tr>
                       <tr>
                        <td colspan="3"></td>
                        <td style="float: right">TAX</td>
                         <td><input class="form-control input-sm" type="text" name="tax" id="tax" value="{{$case->tax}}" readonly=""></td>
                         <td><input class="form-control input-sm" type="text" name="tax_rem" placeholder="remark" value="{{$case->tax_rem}}" readonly=""></td>
                     </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td style="float: right">Net Total</td>
                         <td><input class="form-control input-sm" type="text" name="net_total" id="nettotal" readonly="" value="{{$case->net_total}}" readonly=""></td>
                        <td><input class="form-control input-sm" type="text" name="net_total_rem" placeholder="remark" value="{{$case->net_total_rem}}" readonly=""></td>
                     </tr>
                </tbody>
            </table>
        @endif
        @if(count($casepart2) > 0 )
             <table class="table" style="float: left;">
                    <tbody class="moretr1">
                        <tr>
                            <th>Visit Date/time</th>
                            <th>Service Enginner</th>
                            <th>Call status</th>
                            <th>Pending reasons</th>
                            <th>Remarks</th>
                        </tr>
                        @foreach($casepart2 as $case2)
                        <tr>
                            <td><input class="form-control visitdatetime input-sm" type="text" name="visit_date_time[]" placeholder="Visit date/time" value="{{$case2->visit_date_time}}" readonly=""></td>
                            <td><input class="form-control input-sm" type="text" name="service_engineer[]" placeholder="Service Enginner" value="{{$case2->service_engineer}}" readonly=""></td>
                            <td><input class="form-control input-sm" type="text" name="call_status[]" placeholder="Call status" value="{{$case2->call_status}}" readonly=""></td>
                            <td><input class="form-control input-sm" type="text" name="peding_reasons[]" placeholder="Pending reasons" value="{{$case2->peding_reasons}}" readonly=""></td>
                            <td><input class="form-control input-sm" type="text" name="remarks[]" placeholder="Remaks" value="{{$case2->remarks}}" style="float: left;" readonly="">
                        </td>
                        </tr>
                        @endforeach
                    <tr class="multipleDiv1">
                     </tr>

                    </tbody>
                </table>
                @endif


                        {!! Form::close() !!}
                    </div><!-- /.content -->
    
                </div><!-- /.box-body -->





<div class="row">
   <div class="col-md-4">
    <div class="form-group">  
      <label class="control-label col-sm-4">Latitude</label>
      <div class="input-group ">
        <input type="text"value="{{$case->latitude}}" placeholder="Next Action Date" class="form-control datepicker" readonly="">
        <div class="input-group-addon">
            <a href="#"><i class="fa fa-map-marker"></i></a>
        </div>
      </div>
    </div>
    </div>
     <div class="col-md-4">
    <div class="form-group">  
      <label class="control-label col-sm-4">Longitude</label>
      <div class="input-group ">
        <input type="text" name="target_date" id="target_date" value="{{$case->longitude}}" placeholder="Next Action Date" class="form-control datepicker" readonly="">
        <div class="input-group-addon">
            <a href="#"><i class="fa fa-map-marker"></i></a>
        </div>
      </div>
    </div>
    </div>
    <div class="col-sm-4">
        <a href="https://www.google.com/maps/search/?api=1&query={{$case->latitude}},{{$case->longitude}}"class="btn btn-primary" target="_blank"><i class="fa fa-map"></i>&nbsp;&nbsp;View Location</a>
    </div>
    @if($case->signature)
    <div class="col-md-12">
        <div class="img-responsive">
       <img src="{{$case->signature}}" style="border: 1px solid #ECF0F5">
   </div>
    </div>
   @endif
   
     
</div>
 <div class="col-md-6"> 

                    <div class="box-footer box-comments">
                <h4><strong>Comments: </strong></h4>
                @foreach($comments as $ck => $cv)
                  <div class="box-comment">
                    <!-- User image -->
                    <img class="img-circle img-sm" height="64" width="64" src="{{ TaskHelper::getProfileImage($cv->user_id) }}" alt="User Image">

                    <div class="comment-text">
                          <span class="username">
                            {{ $cv->user->first_name }}
                            <span class="text-muted pull-right"> {{ date('dS M y', strtotime($cv->created_at)) }} </span>
                          </span><!-- /.username -->
                      {{ $cv->comment_text }}
                    </div>
                    <div class="clearfix"></div>
                  </div>
                @endforeach
            </div>

            <div class="box-footer">

              <form action="/admin/post_comment" method="post">
                {{csrf_field()}}
                <!-- .img-push is used to add margin to elements next to floating images -->
                <div class="img-push">
                  <input type="text" style="width:90%; float: left;" class="form-control input-sm" name="comment_text" placeholder="Press enter to post comment">
                  <input type="hidden" name="type" value="case">
                  <input type="hidden" name="master_id" value="{{$case->id}}">
                  <button type="submit" style="padding: 5px; margin-left: 3px;" name="submit_comment" class="btn btn-info btn-xs">Send</button>
                  <div class="clearfix"></div>
                </div>
              </form>
            </div>

                </div>

                  </div>


                  </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

@section('body_bottom')
    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_user_search')
@endsection
