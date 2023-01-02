@extends('layouts.master')
@section('content')


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title ?? '' }}
        <small>{{ $page_description ?? ''}}</small>
    </h1>


    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<div class='row'>

    <div class='col-md-12'>

        <!-- Box -->

        <div class="box box-primary">
            <span id="index_device_status"></span>

            <div class="box-header with-border">

                <a class="btn btn-primary btn-sm pull-right" title="Create" href="{{ route('admin.job-sheet.search-customer') }}">
                    <i class="fa fa-plus"></i>&nbsp;<strong> Job sheet</strong>
                </a>


            </div>

            <form method="get" action="/admin/job-sheet">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="wrap" style="margin-top:10px;">
                            <div class="filter form-inline" style="margin:0 30px 0 30px;">
                                {!! Form::text('customer', \Request::get('customer'), ['style' => 'width:193px;', 'class' => 'form-control', 'placeholder'=>'Customer']) !!}&nbsp;&nbsp;
                                {!! Form::text('contact', \Request::get('contact'), ['style' => 'width:193px;', 'class' => 'form-control', 'placeholder'=>'Contact']) !!}&nbsp;&nbsp;
                                {!! Form::text('brand', \Request::get('brand'), ['style' => 'width:193px;', 'class' => 'form-control', 'placeholder'=>'Brand']) !!}&nbsp;&nbsp;
                                {!! Form::text('model_name', \Request::get('model_name'), ['style' => 'width:193px;', 'class' => 'form-control', 'placeholder'=>'Product Name']) !!}&nbsp;&nbsp;
                                {!! Form::text('model_number', \Request::get('model_number'), ['style' => 'width:193px;', 'class' => 'form-control', 'placeholder'=>'Model Number']) !!}&nbsp;&nbsp;
                                {!! Form::select('device_status',[''=>'Select Device Status','Repaired'=>'Repaired','Pending'=>'Pending','Parts Pending'=>'Parts Pending','Return Without Repair'=>'Return Without Repair','In Progress'=>'In Progress','Payment Issue'=>'Payment Issue','Return with repaired'=>'Return with repaired'], \Request::get('device_status'), ['style' => 'width:193px;', 'class' => 'form-control']) !!}&nbsp;&nbsp;

                                <button class="btn btn-primary" type="submit" id="btn-submit-filter"><i class="fa fa-list"></i> Filter</button>
                                <span  class="btn btn-danger" id="btn-filter-clear">
                                    <i class="fa fa-close"></i>
                                </span>


                            </div>
                        </div>
                    </div>

                </div>

            </form>

              <div class="wrap" style="margin-top:5px;">




            <div class="box-body">

                <div id="app" class="table-responsive" style="overflow-x:auto;">
                    <a href=""> </a>
                    <table class="table table-hover table-bordered table-striped" id="orders-table" >
                        <thead>
                            <tr class="bg-info">
                                <th>
                                    Date
                                </th>
                                <th>
                                    ID#
                                </th>
                                <th>
                                   Customer
                                </th>
                                <th>
                                   Contact
                                </th>
                                 <th>
                                   Brand
                                </th>
                                <th>
                                   Product Name
                                </th>


                                <th>
                                   Model No
                                </th>
                                 <th>Serial</th>
                                <th>
                                   Device Status
                                </th>
                                <th>
                                  status
                                </th>
                                <th>
                                  To
                                </th>

                                <th>Tools</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(isset($jobSheets) && !empty($jobSheets))
                            @foreach($jobSheets as $o)
                            <tr>
                                <td>
                                    {{date('d-M-Y',strtotime($o->created_at))}}
                                </td>
                                <td>{{ $o->id}}</td>
                                <td style="font-size: px">{!! $o->customer->name !!} </td>
                                <td>{!! $o->customer->phone !!} </td>
                                <td>{!! $o->brand!!} </td>
                                <td> {{$o->model_name}}</td>


                                <td>{!! $o->model_number!!} </td>
                                 <td> {{$o->serial_number}}</td>
{{--                                @dd($o->device_status==='Parts Pending'?'selected':'')--}}
                                <td ><select @change="onChanges(event,{{$o->id}})" class="form-control" style="width: 125px">

                                        <option {{$o->device_status==='Repaired'?'selected':''}}  value="Repaired">
                                            Repaired
                                        </option>
                                        <option {{$o->device_status==='Pending'?'selected':''}}  value="Pending">
                                            Pending
                                        </option> <option {{$o->device_status==='Parts Pending'?'selected':''}}  value="Parts Pending">
                                            Parts Pending
                                        </option>
                                        <option  {{$o->device_status==='Return Without Repair'?'selected':''}} value="Return Without Repair">
                                            Return Without Repair
                                        </option>
                                        <option {{$o->device_status==='In Progress'?'selected':''}} value="In Progress">
                                            In Progress
                                        </option>

                                        <option {{$o->device_status==='Payment Issue'?'selected':''}} value="Payment Issue">
                                            Payment Issue
                                        </option>
                                        <option {{$o->device_status==='Return with repaired'?'selected':''}} value="Return with repaired">
                                            Return with repaired
                                        </option>
                                    </select>
                                </td>
                                <td>{!! ucfirst($o->status)!!} </td>
                                <td> <span class=""> {{ucfirst($o->user->username)}} </span></td>
                                <td>
                                    <a href="{{ route('admin.job-sheet.print',$o->id) }}" target="_blank" title="print"><i class="fa fa-print"></i></a>
                                    <a href="{{ route('admin.job-sheet.edit',$o->id) }}" title="edit"><i class="fa fa-edit"></i></a>

                               <?php
                                  $datas = '<a href="'.route('admin.job-sheet.confirm-delete',$o->id).'?type='.\Request::get('type').'" data-toggle="modal" data-target="#modal_dialog" title="Delete"><i class="fa fa-trash-o deletable"></i></a>';

                                   ?>
                                    <?php
                                    if($datas)
                                        echo $datas ?>


                                </td>

                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                    {!! $jobSheets->render() !!}

                </div> <!-- table-responsive -->

            </div><!-- /.box-body -->
        <!-- /.box -->
  <!-- /.col -->

<!-- /.row -->
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkClient[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }

</script>

<script type="text/javascript">
  var app = new Vue({
      el: '#app',
      // watch:{
      //     device_status(value){
      //         console.log(value)
      //     }
      // },
      data: {
          message: '',
          device_status:''
      },
      methods :{
          onChanges(e,id) {
              console.log($(e.target).val());
              axios.post(`/admin/job-sheet/repair-status/`+id,{device_status:$(e.target).val()})
                  .then((response) => {
                      // this.message=response.data.device_status;
                      // if(data.status == '1')
                          $("#index_device_status").after("<span style='color:green;' id='index_status_update'>Status Updated Successfully.</span>");
                      // else
                      //     $("#index_lead_ajax_status").after("<span style='color:red;' id='index_status_update'>Problem in updating status; Please try again.</span>");

                      $('#index_status_update').delay(3000).fadeOut('slow');
                  })
                  .catch((error) => {
                      this.message='Failed';
                  })

          }
      }
  })
</script>

<script>


     $(function() {
        $('#start_date').datepicker({
                 //format: 'YYYY-MM-DD',
                dateFormat: 'yy-m-d',
                sideBySide: true,

            });
        $('#end_date').datepicker({
                 //format: 'YYYY-MM-DD',
                dateFormat: 'yy-m-d',
                sideBySide: true,

            });
         $("#btn-filter-clear").on("click", function () {
             $(this).closest('form').find("input[type=text], input[type=number]").val("");

         });


     });

</script>

    <script type="text/javascript">
      $('.searchable').select2();
      $('.select2').select2();
    </script>

@endsection
