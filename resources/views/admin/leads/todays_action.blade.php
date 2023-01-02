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

    tr {
            text-align: left !important;
        }

</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               {{$page_title}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
        	<!-- Box -->
            {!! Form::open( array('route' => 'admin.leads.enable-selected', 'id' => 'frmLeadList') ) !!}
                <div class="box">
                    <div class="box-header with-border">
                       
                      

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <span id="index_lead_ajax_status"></span>
                        

                        <div class="">
                            <table class="table table-hover table-no-border" id="leads-table">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;width:20px !important">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th> </th>
                                        <th>{{ trans('admin/leads/general.columns.id') }}</th>
                                        <th>{{ trans('admin/leads/general.columns.name') }}</th>
                                        <th>{{ trans('admin/leads/general.columns.course_name') }}</th>
                                        <th>{{ trans('admin/leads/general.columns.mob_phone') }}</th>
                                        <th>Source</th>
                                        <th>Status</th>
                                        <th>Owner</th>

                                        @if(\Request::get('type') == 'customer')
                                        <th>Amount</th>
                                        @endif

                                        <th>Create Date</th>
                                        <th>Rating</th>
                                        <th>{{ trans('admin/leads/general.columns.actions') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @if(isset($leads) && !empty($leads)) 
                                    @foreach($leads as $lk => $lead)
                                    <tr>
                    
                                        @if($lead->viewed == '0')
                                        <td class="bg-info">{!! \Form::checkbox('chkLead[]', $lead->id) !!}</td>
                                        

                                       <td class="bg-info">
                                        <input type="hidden" name="lead_id" class="index_lead_id" value="{{$lead->id}}"></td>


                                        <td class="bg-info">{{ env('APP_CODE')}}{{ $lead->id }}</td>
                                        <td class="bg-info" style="text-align: left;">
                                            @if(strtolower($lead->rating) != 'active') @endif
                                            <h4>
                                            <a href="/admin/leads/{{$lead->id}}?type={{\Request::get('type')}}">{{$lead->name}}
                                                <small class="text-muted">{{$lead->department}}</small></a></h4>
                                            @if(strtolower($lead->rating) != 'active')  @endif
                                        </td>
                                        <td class="bg-info">
                                            <span class="label label-default">{{ mb_substr($lead->course->name,0,13) }}..</span></td>

                                        <td class="bg-info">{{ $lead->mob_phone }}</td>
                                        <td class="bg-info">
                                            <span class="label label-success">{{ $lead->communication->name }}
                                            </span></td>
                                        <td class="bg-info">
                                            {!! \Form::select('status_id', $lead_status, $lead->status_id, ['class' => 'form-control label-default', 'id' => 'index_status_id'])!!}
                                        </td>
                                        <td class="bg-info">
                                            
                                           <a href="/admin/profile/show/{{ $lead->user_id }}">{{ $lead->user->first_name }}</a>
                                            
                                        </td>
                                        
                                        @if(\Request::get('type') == 'customer')
                                        <td>
                                        {{ number_format($lead->amount, 2, '.', ',') }}
                                        </td>
                                        @endif
                                         
                                        <td class="bg-info">
                                            <span class="label label-default">
                                            <i class="fa fa-bullseye"> </i>{{ date('dS M y', strtotime($lead->created_at)) }}</span></td>
                                         @if( $lead->rating == 'active')
                                        <td class="" style="background-color: #4B77BE">{{ $lead->rating }}</td>
                                        @endif
                                        @if( $lead->rating == 'failed')
                                        <td class="" style="background-color: #8F1D21">{{ $lead->rating }}</td>
                                        @endif
                                        @if( $lead->rating == 'acquired')
                                        <td class="" style="background-color: #26A65B">{{ $lead->rating }}</td>
                                        @endif
                                        @if( $lead->rating == 'blacklist')
                                        <td class="" style="background-color: #000000">{{ $lead->rating }}</td>
                                        @endif


                                        @else
                                        <td>{!! \Form::checkbox('chkLead[]', $lead->id) !!}</td>

                                        <td>
                                        <input type="hidden" name="lead_id" class="index_lead_id" value="{{$lead->id}}"></td>

                                        <td>{{ env('APP_CODE')}}{{ $lead->id }}</td>
                                        <td style="float: left;font-size:" class="">
                                            @if(strtolower($lead->rating) != 'active')  @endif
                                            <h4><a class="" href="/admin/leads/{{$lead->id}}?type={{\Request::get('type')}}">
                                                {{$lead->name}} <small class="text-muted"> {{$lead->department}}</small>
                                            </a></h4>
                                            @if(strtolower($lead->rating) != 'active')  @endif
                                        </td>
                                        <td><span class="label label-default">{{ mb_substr($lead->course->name,0,13) }}..</span></td>
                                        <td>{{ $lead->mob_phone }}</td>
                                        <td><span class="label label-success">{{ $lead->communication->name }}
                                            </span></td>
                                        <td>{!! \Form::select('status_id', $lead_status, $lead->status_id, ['class' => 'form-control label-default', 'id' => 'index_status_id'])!!}
                                        </td>

                                       <td>
                                           
                                           <a href="/admin/profile/show/{{ $lead->user_id }}">{{ $lead->user->first_name }}</a>
                                       </td>
                                       
                                       
                                        @if(\Request::get('type') == 'customer')
                                        <td>
                                        {{ number_format($lead->amount, 2, '.', ',') }}
                                        </td>
                                        @endif
                                        

                                        <td><span class="label label-default">
                                            <i class="fa fa-bullseye"> </i> {{ date('dS M y', strtotime($lead->created_at)) }}
                                        </span></td>

                                         @if( $lead->rating == 'active')
                                        <td class="" style="background-color: #4B77BE">{{ $lead->rating }}</td>
                                        @endif
                                        @if( $lead->rating == 'failed')
                                        <td class="" style="background-color: #8F1D21">{{ $lead->rating }}</td>
                                        @endif
                                        @if( $lead->rating == 'acquired')
                                        <td class="" style="background-color: #26A65B">{{ $lead->rating }}</td>
                                        @endif
                                        @if( $lead->rating == 'blacklist')
                                        <td class="" style="background-color: #000000">{{ $lead->rating }}</td>
                                        @endif

                                        @endif


                                        
                                        
                                        <td>
                                            <?php 
                                                $datas = '';
                                                if ( $lead->isEditable())
                                                    $datas .= '<a href="'.route('admin.leads.edit', $lead->id).'?type='.\Request::get('type').'" title="{{ trans(\'general.button.edit\') }}"> <i class="fa fa-edit"></i> </a>';
                                                else
                                                    $datas .= '<i class="fa fa-edit text-muted" title="{{ trans(\'admin/leads/general.error.cant-edit-this-lead\') }}"></i>';

                                               


                                                if ( $lead->isDeletable() )
                                                    $datas .= '<a href="'.route('admin.leads.confirm-delete', $lead->id).'?type='.\Request::get('type').'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fa fa-trash deletable"></i></a>';
                                                else
                                                    $datas .= '<i class="fa fa-trash text-muted" title="{{ trans(\'admin/leads/general.error.cant-delete-this-lead\') }}"></i>';

                                                echo $datas;
                                            ?>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                          

                        </div> <!-- table-responsive -->

                        

                    </div><!-- /.box-body -->
    
    <div style="text-align: center;"> {!! $leads->appends(\Request::except('page'))->render() !!} </div>

                     <div class="box-header with-border bg-info">
                        <p>Lead Stats</p>
                        <h3 class="lead"> 
                        <i class="fa fa-money"></i> Total Target Value: {{ env('APP_CURRENCY') }} 
                        {{ $total_target_amount[0]->total_target_amount }} 
                        | Leads Value: {{ env('APP_CURRENCY') }} 
                        {{ $total_lead_amount[0]->total_lead_amount }} 
                        </h3>

                        Total number of enquiry data: {!! $total_enquiry !!} | Targets:{!! $target_enquiry !!}| Leads: {!! $lead_enquiry !!} | Qualified: {!! $qualified_enquiry !!}
                    </div>

                </div><!-- /.box -->
                
                <div role="dialog" class="modal fade" id="sendSMS" style="display: none;" aria-hidden="true">
                  <div class="modal-dialog modal-lg" style="width:500px;">    
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div style="background:green; color:#fff; text-align:center; font-weight:bold;" class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Send SMS</h4>
                      </div>
                      <div class="modal-body">
                            <div class="form-group">
                            	<!-- <span>Note: Maximum 138 character limit</span><br/> -->   
                                <!-- <textarea rows="3" name="message" class="form-control" id="compose-textarea" onBlur="countChar(this)" placeholder="Type your message." maxlength="138"></textarea> -->
                                <textarea rows="3" name="message" class="form-control" id="compose-textarea" placeholder="Type your message."></textarea>
                                <!-- <span class="char-cnt"></span> -->        
                            </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" onclick="document.forms['frmLeadList'].action = '{!! route('admin.leads.send-sms') !!}';  document.forms['frmLeadList'].submit(); return false;" title="{{ trans('general.button.disable') }}" class="btn btn-primary">{{ trans('general.button.send') }}</button>
                          <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('general.button.cancel') }}</button>
                      </div>
                    </div>    
                  </div>
                </div>
                <input type="hidden" name="lead_type" id="lead_type" value="{{\Request::get('type')}}">
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

<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script>
$(function() {
	$('#start_date').datetimepicker({
			//inline: true,
			format: 'YYYY-MM-DD HH:mm',
			sideBySide: true
		});
	$('#end_date').datetimepicker({
			//inline: true,
			format: 'YYYY-MM-DD HH:mm',
			sideBySide: true
		});
});
</script>
		
<script>

$("#btn-submit-filter").on("click", function () {
	course_id = $("#filter-course").val();
	user_id = $("#filter-user").val();
	enq_mode = $("#filter-medium").val();
	rating = $("#filter-rating").val();
	start_date = $("#start_date").val();
	end_date = $("#end_date").val();
	status_id = $("#filter-status").val();
    type = $("#lead_type").val();
	window.location.href = "{!! url() !!}/admin/leads?course_id="+course_id+"&user_id="+user_id+"&rating="+rating+"&enq_mode="+enq_mode+"&start_date="+start_date+"&end_date="+end_date+"&status_id="+status_id+"&type="+type;
});
$("#btn-filter-clear").on("click", function () {
    type = $("#lead_type").val();
	window.location.href = "{!! url() !!}/admin/leads?type="+type;
});

//change popup lead status

    $(document).on('change', '#index_status_id', function() {
      var id = $(this).closest('tr').find('.index_lead_id').val(); //$(".index_lead_id").index(this);
      var status_id = $(this).val();

      

      $.post("/admin/ajax_lead_status",
      {id: id, status_id: status_id, _token: $('meta[name="csrf-token"]').attr('content')},
      function(data, status){
        if(data.status == '1')
            $("#index_lead_ajax_status").after("<span style='color:green;' id='index_status_update'>Status is successfully updated.</span>");
        else
            $("#index_lead_ajax_status").after("<span style='color:red;' id='index_status_update'>Problem in updating status; Please try again.</span>");

        $('#index_status_update').delay(3000).fadeOut('slow');
        //alert("Data: " + data + "\nStatus: " + status);
      });
    });
</script>

<script>

/*function searchFields() {
    
  var input, filter, table, tr, td, i;
  input = document.getElementById("nameInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("leads-table");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td_name = tr[i].getElementsByTagName("td")[2];
    td_product = tr[i].getElementsByTagName("td")[3];
    td_status = tr[i].getElementsByTagName("td")[6];
    td_rating = tr[i].getElementsByTagName("td")[7];

    if (td_name) {
      if (td_name.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }

    if (td_product) {
      if (td_product.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }

    if (td_status) {
      if (td_status.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }

    if (td_rating) {
      if (td_rating.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}*/

function searchName() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("nameInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("leads-table");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchProduct() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("productInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("leads-table");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[3];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchStatus() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("statusInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("leads-table");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[6];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchRating() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("ratingInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("leads-table");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[7];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>

@endsection
