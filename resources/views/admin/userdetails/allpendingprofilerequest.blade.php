@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
       {{ $page_title ?? "Page Title"}}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<div class='row'>
        <div class='col-md-12 '>

        	   <div class="box">


                    	<div class="">

                    		<table class="table table-hover table-no-border table-striped" id="leads-table">



                                    <tr class="bg-info">
                                      <th> Req. #</th>
                                        <th>Name</th>



                                        <th class="col-sm-2">Action</th>
                                    </tr>

                                <tbody>
                                	 @foreach($allrequest as $lv)
                                        <tr>
                                          <td>PR{{ $lv->id }}</td>
                                            <td style="font-size: 16.5px;">

                                              <img src="/images/profiles/{{$lv->user->image ? $lv->user->image : 'attReq.jpg'}}" class="rounded" style="width: 30px;height: 30px;" alt="User Image">

                                              {{ $lv->user->first_name.' '.$lv->user->last_name }}</td>
                                          </td>
                                            <td>
                                                <a href="/admin/users/{{ $lv->user_id }}/detail/{{$lv->user_detail_id}}/edit" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"><span class="fa fa-list-alt"></span></a>

                                            </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                    	</div>
                    </div>
                  <div style="text-align: center;"> {!! $allrequest->appends(\Request::except('page'))->render() !!} </div>
                </div>
        </div>
 </div>
<link rel="stylesheet" href="/nepali-date-picker/nepali-date-picker.min.css">
<script type="text/javascript" src="/nepali-date-picker/nepali-date-picker.js"> </script>


 <script type="text/javascript">



    $(function() {
        $('.startdate').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true,
        });
      });
       $(function() {
        $('.enddate').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true,
        });
      });

 $("#nep-startdate").nepaliDatePicker({

});
$("#nep-enddate").nepaliDatePicker({

});

  $("#btn-filter-clear").on("click", function () {
      window.location.href = "{!! url('/') !!}/admin/allpendingleaves";
    });



 </script>


@endsection
