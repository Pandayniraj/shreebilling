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

    tr {
        text-align: left !important;
    }

    .openlink:hover {
        color: blue;
        cursor: pointer;
    }

</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Just In Tasks!
        <small>Tasks that are just created</small>
    </h1>

    {{ TaskHelper::topSubMenu('topsubmenu.projects')}}
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
        {!! Form::open( array('route' => 'admin.leads.enable-selected', 'id' => 'frmLeadList') ) !!}
        <div class="box">
            <div class="box-header with-border">

                <div class="wrap" style="margin-top:5px;">
                    <div class="filter form-inline" style="margin:0 30px 0 0;">
                        {!! Form::text('start_date', \Request::get('start_date'), ['style' => 'width:120px;', 'class' => 'form-control', 'id'=>'start_date_project', 'placeholder'=>'Start Date']) !!}&nbsp;&nbsp;
                        <!-- <label for="end_date" style="float:left; padding-top:7px;">End Date: </label> -->
                        {!! Form::text('end_date', \Request::get('end_date'), ['style' => 'width:120px; display:inline-block;', 'class' => 'form-control', 'id'=>'end_date_project', 'placeholder'=>'End Date']) !!}&nbsp;&nbsp;

                        {!! Form::select('user_id', ['' => 'Select user'] + $users, \Request::get('user_id'), ['id'=>'filter-user-project', 'class'=>'form-control', 'style'=>'width:110px; display:inline-block;']) !!}
                        &nbsp;&nbsp;

                        {!! Form::select('status_id', ['' => 'select','new' => 'New','ongoing' => 'ongoing','completed'=>'completed'], \Request::get('status_id'), ['id'=>'filter-status-project', 'class'=>'form-control', 'style'=>'width:100px; display:inline-block;']) !!}&nbsp;&nbsp;

                        <span class="btn btn-primary" id="btn-submit-filter-project-task">
                            <i class="fa fa-list"></i> {{ trans('admin/projects/general.button.filter') }}
                        </span>

                        <span class="btn btn-default" id="">
                            <i class="fa fa-list"></i>

                            <a href="/admin/project_tasks">{{ trans('admin/projects/general.button.reset') }}</a>
                        </span>

                    </div>
                </div>


                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">


                <div class="">
                    <table class="table table-hover table-no-border table-striped" id="leads-table">
                        <thead>
                            <tr class="bg-danger">
                                <th>{{ trans('admin/projects/general.columns.id') }}</th>
                                <th>{{ trans('admin/projects/general.columns.project') }}</th>
                                <th>{{ trans('admin/projects/general.columns.project_task') }}</th>
                                <th>{{ trans('admin/projects/general.columns.status') }}</th>
                                <th>{{ trans('admin/projects/general.columns.owner') }}</th>
                                <th>{{ trans('admin/projects/general.columns.people') }}</th>
                                <th>{{ trans('admin/projects/general.columns.priority') }}</th>
                                <th>{{ trans('admin/projects/general.columns.end_date') }}</th>

                                <th>{{ trans('admin/leads/general.columns.actions') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($projects_tasks) && !empty($projects_tasks))
                            @foreach($projects_tasks as $lk => $lead)
                            <tr>

                                <td>{{\FinanceHelper::getAccountingPrefix('PROJECT_TASK_PRE')}}{{ $lead->id }}</td>
                                <td style="float: left;font-size:" class="" title="{{ $lead->project->name}}">
                                    {{ mb_substr($lead->project->name,0,30) }}
                                </td>
                                <td><span title="{{ $lead->subject}}" class="" style="font-size: 16.5px !important">
                                        <span class="openlink" id="{{$lead->id}}">{!! mb_substr($lead->subject,0,50) !!}</span>

                                    </span></td>

                                <td><span class="label label-primary">{{ $lead->status }}</span></td>

                                <td>{{ $lead->user->first_name }}</td>
                                <td>{{ $lead->peoples }}</td>
                                <td>
                                    <span class="label label-default">
                                        {{$lead->priority}}
                                    </span>
                                </td>

                                <td>{{ date('dS M y', strtotime($lead->end_date))}}</td>

                                <td>
                                    <?php
                                                $datas = '';
                                                if ( $lead->isEditable())
                                                    $datas .= '<a href="'.route('admin.project_task.edit', $lead->id).' " title="{{ trans(\'general.button.edit\') }}"> <i class="fa fa-edit"></i> </a>';
                                                else
                                                    $datas .= '<i class="fa fa-edit text-muted" title="{{ trans(\'admin/leads/general.error.cant-edit-this-lead\') }}"></i>';


                                                if ( $lead->isDeletable() )
                                                    $datas .= '<a href="'.route('admin.project_task.confirm-delete', $lead->id).'" data-toggle="modal" data-target="#modal_dialog" title="{{ trans(\'general.button.delete\') }}"><i class="fa fa-trash deletable"></i></a>';
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
            <div style="text-align: center;"> {!! $projects_tasks->render() !!} </div>


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

<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<script>
    $(function() {
        $('#start_date_project').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD HH:mm'
            , sideBySide: true
        });
        $('#end_date_project').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD HH:mm'
            , sideBySide: true
        });
    });

</script>
<script>
    $("#btn-submit-filter-project-task").on("click", function() {

        user_id = $("#filter-user-project").val();
        start_date = $("#start_date_project").val();
        end_date = $("#end_date_project").val();
        status_id = $("#filter-status-project").val();



        window.location.href = "{!! url('/') !!}/admin/project_tasks?user_id=" + user_id + "&start_date=" + start_date + "&end_date=" + end_date + "&status_id=" + status_id;
    });

</script>

<script>
    function HandlePeopleChanges(prams, task_ids, isChanged) { // this function is called from another window
        if (prams) {
            console.log(prams);
            $.post("/admin/ajaxTaskPeopleUpdate", {
                    id: task_ids
                    , peoples: prams
                    , _token: $('meta[name="csrf-token"]').attr('content')
                }
                , function(data) {
                    console.log(data);
                    //alert("Data: " + data + "\nStatus: " + status);
                });
        }
        if (isChanged) {
            location.reload();
        }

    }

    function UpdateChanges(isChanged) {
        if (isChanged) {
            location.reload();
        }
    }


    $('.openlink').click(function() {
        let id = this.id;
        //window.open('/admin/project_task/'+id);
        window.open('/admin/project_task/' + id, '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=0,left=100,width=999, height=660');
    });



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
