@extends('layouts.master')
@section('content')

<style>
  #leads-table td:first-child{text-align: center !important;}
  #leads-table td:nth-child(2){font-weight: bold !important;}
  #leads-table td:last-child a {margin:0 2px;}
  tr { text-align:center; }

    #nameInput, #productInput, #statusInput, #ratingInput {
        background-image: url('/images/searchicon.png'); /* Add a search icon to input */
        background-position: 10px 12px; /* Position the search icon */
        background-repeat: no-repeat; /* Do not repeat the icon image */
        font-size: 16px; /* Increase font-size */
        padding: 12px 12px 12px 40px; /* Add some padding */
        border: 1px solid #ddd; /* Add a grey border */
        margin-bottom: 12px; /* Add some space below the input */
        margin-right: 5px;
    }

</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Lead search result
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {{ TaskHelper::topSubMenu('topsubmenu.crm')}}
           <p> Lead search result for the term <strong>"{{ $_GET['search']}}"</strong>
            &nbsp; <strong>{{ $leads->count() }}</strong> results
           </p>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
        	<!-- Box -->
            {!! Form::open( array('route' => 'admin.leads.enable-selected', 'id' => 'frmLeadList') ) !!}
                <div class="box box-danger">
                    <div class="box-body">

                        <div class="">
                            <table class="table table-hover table-no-border" id="leads-table">
                                <thead>
                                    <tr class="bg-danger">
                                      
                                        <th>{{ trans('admin/leads/general.columns.id') }}</th>
                                        <th>{{ trans('admin/leads/general.columns.name') }}</th>
                                        <th>{{ trans('admin/leads/general.columns.course_name') }}</th>
                                        <th>{{ trans('admin/leads/general.columns.mob_phone') }}</th>
                                        <th>Staff</th>
                                        <th>{{ trans('admin/leads/general.columns.status_id') }}</th>
                                        <th>{{ trans('admin/leads/general.columns.rating') }}</th>
                                        <th>{{ trans('admin/leads/general.columns.date') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @if(isset($leads) && !empty($leads)) 
                                    @foreach($leads as $lk => $lead)
                                    <tr>
                    
                                        @if($lead->viewed == '0')
                                        <td class="bg-info">{{ env('APP_CODE') }}{{ $lead->id }}</td>

                                        <td class="bg-info" style="font-size: 16.5px;text-align: left;">
                                            @if(strtolower($lead->rating) != 'active') @endif
                                            <a href="/admin/leads/{{$lead->id}}?type={{ strtolower($lead->leadType->name) }}">{{$lead->name}}</a>
                                            @if(strtolower($lead->rating) != 'active')  @endif
                                        </td>

                                        <td class="bg-info">{{ $lead->product->name }}</td>

                                        <td class="bg-info">{{ $lead->mob_phone }}</td>
                                        <td class="bg-info">{{ $lead->user->first_name }}</td>
                                        <td class="bg-info">{{ $lead->status->name }}</td>
                                        <td class="bg-info">{{ ucfirst($lead->rating) }}</td>
                                        <td class="bg-info">{{ date('dS M y', strtotime($lead->created_at)) }}</td>    

                                        @else
                                        <td>{{ env('APP_CODE') }}{{ $lead->id }}</td>
                                        <td style="font-size: 16.5px;text-align: left;">
                                            @if(strtolower($lead->rating) != 'active') @endif
                                            <a href="/admin/leads/{{$lead->id}}?type={{strtolower($lead->leadType->name)}}">{{$lead->name}}</a>
                                            @if(strtolower($lead->rating) != 'active') @endif
                                        </td>
                                        <td>{{ $lead->product->name ??''}}</td>
                                        <td>{{ $lead->mob_phone }}</td>
                                        <td>{{ $lead->user->first_name  ??''}}</td>
                                        <td>{{ $lead->status->name??'' }}</td>
                                        @if(strtolower($lead->rating) == 'failed') <td class="alert-danger"> 
                                        @elseif(strtolower($lead->rating) == 'cancel') <td class="bg-maroon"> 
                                        @elseif(strtolower($lead->rating) == 'shut') <td class="alert-info">
                                        @else <td>
                                        @endif
                                            {{ ucfirst($lead->rating) }}
                                        </td>
                                        <td>{{ date('dS M Y', strtotime($lead->created_at)) }}</td>
                                        @endif

                                      
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                          

                        </div> <!-- table-responsive -->

                        

                    </div><!-- /.box-body -->
                    <div style="text-align: center;"> {!! $leads->appends(['search' => \Request::get('search')])->render() !!} </div>

                </div><!-- /.box -->
                
            {!! Form::close() !!}
            
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')

<script language="JavaScript">
	function toggleCheckbox() {
		checkboxes = document.getElementsByName('chkLead[]');
		for(var i=0, n=checkboxes.length;i<n;i++) {
			checkboxes[i].checked = !checkboxes[i].checked;
		}
	}
</script>

@endsection
