@extends('layouts.master')
@section('content')

<style>
    #leads-table td:first-child {
        text-align: center !important;
    }

    #leads-table td:nth-child(2) {
        font-weight: bold !important;
    }

    #leads-table td:last-child a {
        margin: 0 2px;
    }

    tr {
        text-align: center;
    }

    #nameInput,
    #productInput,
    #statusInput,
    #ratingInput {
        background-image: url('/images/searchicon.png');
        /* Add a search icon to input */
        background-position: 10px 12px;
        /* Position the search icon */
        background-repeat: no-repeat;
        /* Do not repeat the icon image */
        font-size: 16px;
        /* Increase font-size */
        padding: 12px 12px 12px 40px;
        /* Add some padding */
        border: 1px solid #ddd;
        /* Add a grey border */
        margin-bottom: 12px;
        /* Add some space below the input */
        margin-right: 5px;
    }

    .courses_td {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        /* display: block;*/
    }

    .rating {
        text-transform: capitalize;
    }

    tr {
        text-align: left !important;
    }

    h4 {
        margin: 0px !important;
    }

    [data-letters]:before {
        content: attr(data-letters);
        display: inline-block;
        font-size: 0.7em;
        width: 2.1em;
        height: 1.9em;
        line-height: 1.8em;
        text-align: center;
        border-radius: 90%;
        background: #5758BB;
        vertical-align: middle;
        margin-right: 0.3em;
        color: white;
    }

</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ ucfirst(\Request::get('type'))}}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    <span> Leads can be submitted from external portal by clicking <a target="_blank" href="/enquiry">here</a></span> Leads at your <a target="_blank" href="https://play.google.com/store/apps/details?id=com.meronetwork.crm&hl=en">mobile apps</a>

    <br />

    {{ TaskHelper::topSubMenu('topsubmenu.crm')}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
        {!! Form::open( array('route' => 'admin.leads.enable-selected', 'id' => 'frmLeadList') ) !!}
        <div class="box">
            <div class="box-header with-border">

                &nbsp;

                @if(\Request::get('type') == 'target')

                @else
                <a class="btn btn-social btn-bitbucket btn-sm" href="{!! route('admin.leads.create') !!}?type={!! isset($_GET['type']) ? $_GET['type'] : null  !!}" title="{{ trans('admin/leads/general.button.create') }}">
                    <i class="fa fa-edit"></i> <strong> Create New {!! ucfirst(
                    isset($_GET['type']) ? isset($_GET['type']) : null) !!}</strong>
                </a>

                <a class="btn btn-social bg-blue btn-sm" href="#" onClick="openmodal()" title="{{ trans('admin/leads/general.button.create') }}">
                    <i class="fa fa-edit"></i> <strong> Quick {!! isset($_GET['type']) ? $_GET['type'] : null  !!}
                    </strong>
                </a>
                @endif
                &nbsp;
                <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmLeadList'].action = '{!! route('admin.leads.enable-selected') !!}';  document.forms['frmLeadList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                    <i class="fa fa-check-circle" style="color:green;"></i>
                </a>
                
                <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmLeadList'].action = '{!! route('admin.leads.disable-selected') !!}';  document.forms['frmLeadList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                    <i class="fa fa-ban" style="color:orange;"></i>
                </a>
                 <a class="btn btn-default btn-sm multi-delete-button" href="#">
                    <i class="fa fa-trash" style="color:red;"></i>
                </a>
             {{--    <button class="btn btn-default btn-sm multi-delete-button" 
                    onclick="alert('DOPNE');return false;"  title="Multiple delete">
                    <i class="fa fa-trash" style="color:red;"></i>
                </button> --}}
                <a class="btn btn-default btn-sm" href="#" data-target="#sendSMS" data-toggle="modal">
                    <i class="fa fa-mobile"></i> Send SMS
                </a>
                
                <a class="btn btn-default btn-sm" href="{!! route('admin.import-export.leads') !!}" title="Import/Export Leads">
                    <i class="fa fa-download"></i> Upload or Download
                </a>

                <?php
                        $product_id= \Request::get('product_id');
                        $user_id = \Request::get('user_id');
                        $rating = \Request::get('rating');
                        $start_date=\Request::get('start_date');
                        $end_date=\Request::get('end_date'); 
                        $status_id=\Request::get('status_id');
                        $source_id=\Request::get('source_id'); 
                        $type=\Request::get('type'); 
                ?>

                @if( $product_id !='' || $user_id!='' || $rating!='' || $start_date!='' || $end_date!='' || $status_id!='' || $source_id!='')
                <a class="btn btn-success btn-sm" href="/admin/downloadexcelfilter?product_id={{$product_id}}&user_id={{$user_id}}&rating={{$rating}}&start_date={{$start_date}}&end_date={{$end_date}}&status_id={{$status_id}}&source_id={{$source_id}}&type={{$type}}" title="Import/Export Leads">
                    <i class="fa fa-download"></i>&nbsp;<strong>Download this Search</strong>
                </a>
                @endif
                <div class="wrap" style="margin-top:5px;">
                    <div class="filter form-inline" style="margin:0 30px 0 0;">
                        {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:120px;', 'class' => 'form-control input-sm', 'id'=>'start_date', 'placeholder'=>trans('general.columns.start_date')]) !!}&nbsp;&nbsp;
                        <!-- <label for="end_date" style="float:left; padding-top:7px;">End Date: </label> -->
                        {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:120px; display:inline-block;', 'class' => 'form-control input-sm', 'id'=>'end_date', 'placeholder'=>trans('general.columns.end_date')]) !!}&nbsp;&nbsp;

                        {!! Form::select('product_id', ['' => 'Select Product'] + $courses, \Request::get('product_id'), ['id'=>'filter-course', 'class'=>'form-control input-sm', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                        {!! Form::select('source_id', ['' => 'Select Source'] + $sources, \Request::get('source_id'), ['id'=>'filter-source', 'class'=>'form-control input-sm', 'style'=>'width:150px; display:inline-block;']) !!}&nbsp;&nbsp;

                        {!! Form::select('user_id', ['' => 'Select user'] + $users, \Request::get('user_id'), ['id'=>'filter-user', 'class'=>'form-control input-sm', 'style'=>'width:110px; display:inline-block;']) !!}
                        &nbsp;&nbsp;
                        {!! Form::select('rating',[''=>'Select rating']+ $lead_rating, \Request::get('rating'), ['id'=>'filter-rating', 'class'=>'form-control input-sm', 'style'=>'width:100px; display:inline-block;']) !!}&nbsp;&nbsp;

                        {!! Form::select('status_id', ['' => 'Status'] + $lead_status, \Request::get('status_id'), ['id'=>'filter-status', 'class'=>'form-control input-sm', 'style'=>'width:100px; display:inline-block;']) !!}&nbsp;&nbsp;

                        <span class="btn btn-sm btn-primary" id="btn-submit-filter">
                            Filter
                        </span>
                        <span class="btn btn-sm btn-default" id="btn-filter-clear">
                            Clear
                        </span>
                    </div>
                </div>


                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <span id="index_lead_ajax_status"></span>


                <div class="">
                    <table class="table table-hover table-no-border table-striped table-responsive" id="leads-table" cellspacing='0'>
                        <thead>
                            <tr class="bg-blue">
                                <th style="text-align:center;width:20px !important">
                                    <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">

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
                                <th>Follow</th>
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


                                <td class="bg-info">{{\FinanceHelper::getAccountingPrefix('LEADS_PRE')}}{{ $lead->id }}</td>

                                <td class="bg-info" style="text-align: left;font-size: 16.5px">


                                    <a href="/admin/leads/{{$lead->id}}?type={{\Request::get('type')}}">{{$lead->name}}</a>


                                </td>



                                <td class="bg-info courses_td">

                                    <span class="label label-default courses_id" data-type="select" data-pk="1" data-title="Select courses" data-value="{{$lead->product_id}}"></span>
                                    {{-- <span class="label label-default">{{ mb_substr($lead->course->name,0,13) }}..</span> --}}
                                </td>

                                <td class="bg-info">
                                    <span class="mob_phone">{{ $lead->mob_phone }}</span></td>
                                <td class="bg-info ">
                                    <span class="label label-success source_id" data-type="select" data-pk="1" data-title="Select sources" data-value="{{$lead->communication_id}}"></span>
                                    {{-- <span class="label label-success">{{ $lead->communication->name }}
                                    </span> --}}
                                </td>
                                <td class="bg-info">
                                    <span class="label label-{{$lead->status['color']}} status_id" data-type="select" data-pk="1" data-title="Select staus" data-value="{{$lead->status_id}}"></span>
                                    {{-- {!! \Form::select('status_id', $lead_status, $lead->status_id, ['class' => 'form-control label-default', 'id' => 'index_status_id'])!!} --}}
                                </td>


                                <td class="bg-info">

                                    <!--  <a href="/admin/profile/show/{{ $lead->user_id }}">{{ $lead->user->first_name }}</a> -->
                                    @if($lead->user->image)
                                    <img class="profile-user-img img-responsive img-circle" src="/images/profiles/{{$lead->user->image}}" alt="{{ $lead->user->first_name}}" style="margin: 0 auto;width: 25px; border: 1px solid #d2d6de;">
                                    @else
                                    <span data-letters="{{ mb_substr($lead->user->first_name,0,1).' '.mb_substr($lead->user->last_name,0,1) }}"></span>
                                    @endif

                                </td>
                                @if(\Request::get('type') == 'customer')
                                <td>
                                    {{ number_format($lead->amount, 2, '.', ',') }}
                                </td>
                                @endif

                                <td class="bg-info">
                                    <span class="label label-default">
                                        <i class="fa fa-bullseye"> </i>{{ date('dS M y', strtotime($lead->created_at)) }}</span></td>
                                <td>

                                    @if($lead->target_date >= date('Y-m-d') || $lead->target_date == date('0000-00-00'))
                                    <input class="datepicker_follow_date" style="width: 60px;border:none;" value="{{date('d M y',strtotime($lead->target_date))}}">
                                    @else
                                    <span class="btn btn-danger btn-xs">
                                        <i class="fa fa-clock-o fa-spin"></i>
                                        MISSED</span>
                                    @endif
                                </td>
                                @if($lead->rating)
                                <td class="rating_td">
                                    <span class="label rating" data-type="select" data-pk="1" data-title="Select Rating" data-value="{{$lead->rating}}"></span>

                                </td>
                                @else
                                <td></td>
                                @endif

                                @else
                                <td class="">{!! \Form::checkbox('chkLead[]', $lead->id) !!}</td>

                                <td class="">
                                    <input type="hidden" name="lead_id" class="index_lead_id" value="{{$lead->id}}"></td>

                                <td class="">{{\FinanceHelper::getAccountingPrefix('LEADS_PRE')}}{{ $lead->id }}</td>

                                <td style="float:left;font-size: 16.5px" class="">

                                    <a class="" href="/admin/leads/{{$lead->id}}?type={{\Request::get('type')}}">
                                        {{$lead->name}}
                                    </a>

                                </td>



                                <td class="courses_td">
                                    <span class="label label-default courses_id" data-type="select" data-pk="1" data-title="Select courses" data-value="{{$lead->product_id}}"></span></td>
                                <td><span class="mob_phone">{{ $lead->mob_phone }}</span></td>
                                <td>
                                    <span class="label label-success source_id" data-type="select" data-pk="1" data-title="Select sources" data-value="{{$lead->communication_id}}"></span>

                                </td>
                                <td>
                                    <span class="label label-{{$lead->status['color']}}  status_id" data-type="select" data-pk="1" data-title="Select staus" data-value="{{$lead->status_id}}"></span>
                                </td>

                                <td>
                                    @if($lead->user->image)
                                    <img class="profile-user-img img-responsive img-circle" src="/images/profiles/{{$lead->user->image}}" alt="{{ $lead->user->first_name}}" style="margin: 0 auto;width: 25px; border: 1px solid #d2d6de;">
                                    @else
                                    <span data-letters="{{ mb_substr($lead->user->first_name,0,1).' '.mb_substr($lead->user->last_name,0,1) }}"></span>
                                    @endif
                                </td>


                                @if(\Request::get('type') == 'customer')
                                <td>
                                    {{ number_format($lead->amount, 2, '.', ',') }}
                                </td>
                                @endif

                                <td><span class="label label-default">
                                        <i class="fa fa-bullseye"> </i> {{ date('dS M y', strtotime($lead->created_at)) }}
                                    </span></td>
                                <td>
                                    <input class="datepicker_follow_date" style="width: 60px;border:none;" @if($lead->target_date >= date('Y-m-d') || $lead->target_date == date('0000-00-00'))>
                                    <input class="datepicker_follow_date" style="width: 60px;border:none;" value="{{date('d M y',strtotime($lead->target_date))}}">
                                    @else
                                    <input class="datepicker_follow_date" style="width: 60px;border:none" value="MISSED">
                                    @endif


                                </td>

                                @if($lead->rating)
                                <td class="rating_td">

                                    <span class="label rating label-{{ isset($lead->ratings['bg_color']) ? $lead->ratings['bg_color'] : ''  }}" data-type="select" data-pk="1" data-title="Select Rating" data-value="{{$lead->rating}}"></span>

                                </td>
                                @else
                                <td></td>
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
                <p>Statistics</p>
                <h3 class="lead">
                    <i class="fa fa-money"></i> Total Target Value: {{ env('APP_CURRENCY') }}
                    {{ $total_target_amount[0]->total_target_amount }}
                    | Leads Value: {{ env('APP_CURRENCY') }}
                    {{ $total_lead_amount[0]->total_lead_amount }}
                </h3>

                Total number of enquiry data: {!! $total_enquiry !!} | Targets:{!! $target_enquiry !!}| Leads: {!! $lead_enquiry !!} | Qualified: {!! $qualified_enquiry !!}
                <br>




                Total number By products:
                @foreach($courses_count as $cc)
                <?php

                            if(Request::get('type')=='customer')
                            $type_id= 4;
                            elseif(Request::get('type')=='leads')
                             $type_id= 2;
                            elseif(Request::get('type')=='contact') 
                             $type_id= 5;
                            elseif(Request::get('type')=='target')
                             $type_id= 1;
                            elseif(Request::get('type')=='qualified')
                             $type_id= 3;
                            elseif(Request::get('type')=='agent')
                             $type_id= 6;
                            else
                                $type_id= null;
                         ?>

                {!! $cc->name !!}-({!! TaskHelper::getCountByProduct($cc->id,$type_id); !!}) |

                @endforeach



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
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

</script>

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<!-- ChartJS -->
<!-- <script src="{{ asset ("/bower_components/admin-lte/plugins/chartjs/Chart.min.js") }}" type="text/javascript"></script> -->
<script src="{{ asset ("/bower_components/highcharts/highcharts.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/highcharts/funnel.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/highcharts/highcharts-3d.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/highcharts/exporting.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/highcharts/export-data.js") }}" type="text/javascript"></script>

<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

   <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<link href="/x-editable/bootstrap-editable.css" rel="stylesheet" />
<script src="/x-editable/bootstrap-editable.min.js"></script>
<script>
    $(function() {
        $('#start_date').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD HH:mm'
            , sideBySide: true
        });
        $('#end_date').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD HH:mm'
            , sideBySide: true
        });
    });

</script>

<script>
    $("#btn-submit-filter").on("click", function() {
        product_id = $("#filter-course").val();
        source_id = $("#filter-source").val();
        user_id = $("#filter-user").val();
        enq_mode = $("#filter-medium").val();
        rating = $("#filter-rating").val();
        start_date = $("#start_date").val();
        end_date = $("#end_date").val();
        status_id = $("#filter-status").val();
        type = $("#lead_type").val();
        window.location.href = "{!! url('/') !!}/admin/leads?product_id=" + product_id + "&source_id=" + source_id + "&user_id=" + user_id + "&rating=" + rating + "&enq_mode=" + enq_mode + "&start_date=" + start_date + "&end_date=" + end_date + "&status_id=" + status_id + "&type=" + type;
    });
    $("#btn-filter-clear").on("click", function() {
        type = $("#lead_type").val();
        window.location.href = "{!! url('/') !!}/admin/leads?type=" + type;
    });

    //change popup lead status

    $(document).on('change', '#index_status_id', function() {
        var id = $(this).closest('tr').find('.index_lead_id').val(); //$(".index_lead_id").index(this);
        var status_id = $(this).val();



        $.post("/admin/ajax_lead_status", {
                id: id
                , status_id: status_id
                , _token: $('meta[name="csrf-token"]').attr('content')
            }
            , function(data, status) {
                if (data.status == '1')
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

    function openmodal() {
        var win = window.open(`/admin/leads/create/modal?type={!! isset($_GET['type']) ? $_GET['type'] : null   !!}`, '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
    }

    function HandlePopupResult(result) {
        console.log(result);
        location.reload();
    }


    //ajax update


    function makechanges(value, type, parent) {
        if (type == 'rating') {
            let parent_el = parent.find('.rating');
            var classes = (parent_el.attr("class").split(/\s+/));
            var newclass = 'label-' + value.rating_color;
            for (let i = 0; i < classes.length; i++) {
                if (classes[i].startsWith('label-')) {
                    parent_el.toggleClass(classes[i] + ' ' + newclass);
                    return 0;
                }
            }
        } else if (type == 'status') {
            let parent_el = parent.find('.status_id');
            var classes = (parent_el.attr("class").split(/\s+/));
            var newclass = 'label-' + value.status_color;
            for (let i = 0; i < classes.length; i++) {
                if (classes[i].startsWith('label-')) {
                    parent_el.toggleClass(classes[i] + ' ' + newclass);
                    return 0;
                }
            }
        } else if (type == 'stages') {
            let parent_el = parent.find('.stage_id');
            var classes = (parent_el.attr("class").split(/\s+/));
            var newclass = 'label-' + value.stages_color;
            console.log(newclass);
            for (let i = 0; i < classes.length; i++) {
                if (classes[i].startsWith('label-')) {
                    parent_el.toggleClass(classes[i] + ' ' + newclass);
                    return 0;
                }
            }
        }
    }

    function handleChange(lead_id, value, type, parent) {
        $.post("/admin/ajaxLeadUpdate", {
                id: lead_id
                , update_value: value
                , type: type
                , _token: $('meta[name="csrf-token"]').attr('content')
            }
            , function(data) {
                if (data.status == '1') {
                    makechanges(data.data, type, parent);
                    $("#ajax_status").after("<span style='color:green;' id='status_update'>" + type + " sucessfully updated</span>");
                    $('#status_update').delay(3000).fadeOut('slow');
                }

                //alert("Data: " + data + "\nStatus: " + status);
            });
    }

    $('.stage_id').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = parent.find('.index_lead_id').val();
        $(this).editable({
            source: <?php echo json_encode($stages); ?> 
            , success : function(response, newValue) {
                handleChange(lead_id, newValue, 'stages', parent);
            }
        });
    });

    var courses = <?php echo json_encode($courses); ?>;

    $('table .courses_id').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = parent.find('.index_lead_id').val();
        $(this).editable({
            source: courses
            , success: function(response, newValue) {
                handleChange(lead_id, newValue, 'products', parent);
            }
        , });
    });
    $('table .mob_phone').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = parent.find('.index_lead_id').val();
        $(this).editable({
            success: function(response, newValue) {
                handleChange(lead_id, newValue, 'mob_phone', parent);
            }
        });
    });
    $('table .source_id').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = parent.find('.index_lead_id').val();
        $(this).editable({
            source: <?php echo json_encode($sources); ?> 
            , success: function(response, newValue) {
                handleChange(lead_id, newValue, 'sources', parent);
            }
        , });
    });

    $('table .status_id').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = parent.find('.index_lead_id').val();
        $(this).editable({
            source: <?php echo json_encode($lead_status); ?> 
            , success : function(response, newValue) {
                handleChange(lead_id, newValue, 'status', parent);
            }
        , });
    });
    const lead_rating = <?php echo json_encode($lead_rating); ?> ;
    $('table .rating').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = parent.find('.index_lead_id').val();
        $(this).editable({
            source: lead_rating
            , success: function(response, newValue) {
                handleChange(lead_id, newValue, 'rating', parent);
            }
        , });
    });


    $('.datepicker_follow_date').each(function() {
        let parent = $(this).parent().parent();
        let lead_id = parent.find('.index_lead_id').val();
        $(this).datepicker({

            dateFormat: 'd M y'
            , sideBySide: true
            , onSelect: function(dateText) {
                handleChange(lead_id, dateText, 'target_date', parent);
            }
        });
    });



</script>
@include('confirm-multiple-delete')
@endsection
