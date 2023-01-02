@extends('layouts.master')
@section('content')
<style>
[data-letters]:before {
content:attr(data-letters);
display:inline-block;
font-size:1em;
width:2.5em;
height:2.5em;
line-height:2.5em;
text-align:center;
border-radius:50%;
text-transform: capitalize;
background:red;
vertical-align:middle;
margin-right:0.3em;
color:white;
}
</style>

{{-- $show is campaing --}}
  <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h2>
             <a href="#" data-toggle="modal" data-target="#saveLogo">
              <small data-letters="{{mb_substr($show->name,0,3)}}"></small>
            </a>
                Campaign: {{ucfirst(trans($show->name))}} <small>( {{$leads->total()}} records )</small>
                </h2>

                   <p class="pull-left"> {{$show->content}}</p>
                    <div class="form-group pull-right">
                        <a href="{{ route('admin.campaigns.index') }}" class='btn btn-danger btn-xs'>{{ trans('general.button.close') }}</a>
                          
                          <a  href="{{route('admin.campaigns.edit',$show->id)}}" class='btn btn-success btn-xs'>{{ trans('general.button.edit') }}</a>
                           
                          <a href="{{ route('admin.campaigns.bulk-mail',$show->id) }}" data-toggle="modal" data-target="#modal_dialog" class='btn btn-primary btn-xs' data-backdrop="static" data-keyboard="false">Bulk Email</a>
                        </div>

        </section> 

<div class="content">
  <div class="box box-primary">
 
     
 

   <div class="row">
     <div class="col-md-12">
        <div class='col-md-6'>

                   <p> <i class="fa  fa-calendar"></i> Start date: 
                    <a style="color: black" >{{date('F Y', strtotime($show->start_date))}}</a> 
                  </p>

                  <p> <i class="fa   fa-calendar"></i> End date: 
                    <a style="color: black" >{{date('F Y', strtotime($show->end_date))}}</a> 
                  </p>

                   <p> <i class="fa   fa-usd"></i> Currency: 
                    <a style="color: black" >{{$show->currency}}</a> 
                  </p>

                  <p> <i class="fa   fa-money"></i> Expected Cost: 
                    <a style="color: black" >{{$show->expected_cost}}</a> 
                  </p>

                  <p> <i class="fa   fa-location-arrow"></i> Expected revenue: 
                    <a style="color: black" >{{$show->expected_revenue}}</a> 
                  </p>
                    
                
                 
                   
        </div>
         <div class='col-md-6'>

               <p> <i class="fa  fa-btc"></i> Budget:
                    <a style="color: black" >{{$show->budget}}</a> 
                </p>
                <p> 
                <i class="fa  fa-money"></i> Actual Cost: 
                    <a style="color: black" >{{$show->actual_cost}}</a> 
                </p>
                <p> <i class="fa  fa-bullseye"></i> Campaign type: 
                    <a style="color: black" >{{$show->campaign_type}}</a> 
                </p>
                <p> <i class="fa  fa-hourglass-start"></i> Campaign status  :
                    <a style="color: black" >{{$show->camp_status}}</a> 
                </p>

                <p> <i class="fa  fa-user"></i> Created By  :
                    <a style="color: black" >{{ucfirst(trans($show->user->username))}}</a> 
                </p>
                    
                   

        
      </div>
    </div>
</div>

  <div class="row">
      <div class="col-md-12">
          <span style="margin-left: 20px"> {{$show->objective}} </span>
      </div>

  </div>

<div class="row">
   <div class="col-md-12">
  <table class="table table-hover table-no-border" id="leads-table" cellspacing='0'>
                                <thead>
                                    <tr class="bg-danger">
                                     
                                        <th>{{ trans('admin/leads/general.columns.id') }}</th>
                                        <th>{{ trans('admin/leads/general.columns.name') }}</th>
                    
                                        <th>Product</th>
                                        <th>{{ trans('admin/leads/general.columns.mob_phone') }}</th>
                                        <th>Source</th>
                                        <th>Status</th>
                                        <th>Owner</th>
                                        <th>Create Date</th>
                                        <th>Follow</th>
                                        <th>Rating</th>
                                        <th>{{ trans('admin/leads/general.columns.actions') }}</th>
                                    </tr>
                                </thead>

                                 <tbody>
                                 
                              @foreach($leads as $k => $lead)
                                 <tr>
                                <td>{{ env('APP_CODE')}}{{ $lead->id }}</td>
                                <td><a href="/admin/leads/{{$lead->id}}?type={{ strtolower($lead->leadType->name) }}" target="_blank">{{$lead->name}}
                                </a> 
                                </td>
                                <td> {{ mb_substr($lead->product->name,0,13) }}</td>
                                <td> {{ $lead->mob_phone}}</td>
                                <td>{{ $lead->communication->name }} </td>
                                <td>{{$lead->status->name}} </td>
                                <td> {{ $lead->user->first_name}}</td>
                                <td> {{ $lead->created_at}}</td>
                                <td>{{ $lead->target_date}} </td>
                                <td><span class="label label-{{ $lead->ratings->bg_color }}"> {{ucfirst($lead->ratings->name)}}</span></td>
                                <td>
                                    <?php 
                                        $datas = '';
                                        if ( $lead->isEditable())
                                            $datas .= '<a href="'.route('admin.leads.edit', $lead->id).'?type='.strtolower($lead->leadType->name).'" title="{{ trans(\'general.button.edit\') }}"> <i class="fa fa-edit"></i> </a>';
                                        else
                                            $datas .= '<i class="fa fa-edit text-muted" title="{{ trans(\'admin/leads/general.error.cant-edit-this-lead\') }}"></i>';

                                       
                                        if ( $lead->isDeletable() )
                                            $datas .= '<a href="'.route('admin.leads.confirm-delete', $lead->id).'?type='.strtolower($lead->leadType->name).'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fa fa-trash deletable"></i></a>';
                                        else
                                            $datas .= '<i class="fa fa-trash text-muted" title="{{ trans(\'admin/leads/general.error.cant-delete-this-lead\') }}"></i>';

                                        echo $datas;
                                    ?>
                                </td>
                                </tr>

                               @endforeach
                             <div align="center">{!! $leads->render() !!}</div>

                                </tbody>


                              </table>
                              <div align="center">{!! $leads->render() !!}</div>
                            </div>
</div>
















</div></div>



<script type="text/javascript">
    $(document).on('hidden.bs.modal', '#modal_dialog' , function(e){
        $('#modal_dialog .modal-content').html('');    
   });
</script>



@endsection
