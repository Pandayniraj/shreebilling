@extends('layouts.master')

@section('content')
<style>
    .datetimepicker {
        position: relative;
    }
    .datepicker{
      position: relative;
    }
      .required { color: red; }
    .panel-custom .panel-heading {
        border-bottom: 2px solid #1797be;
    }
    .panel-custom .panel-heading {
        margin-bottom: 10px;
    }
    </style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Leave Manager
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
   {{ TaskHelper::topSubMenu('topsubmenu.hr')}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>
@if(isset($carrayOverLeave))
<div class="row">
    
    <div class="col-sm-12">
      
        <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Carry Leave</h3>

                  <div class="box-tools pull-right">
                    <span class="label label-danger">{{$currentYear->leave_year}}</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                            @foreach($carrayOverLeave as $key=>$value)
                            <tr>
                                <td>{{ $value->user->first_name }} {{ $value->user->last_name }}</td>
                                <td>{{ $value->num_of_carried }}</td>
                                <td>
                                 <a href="{{ route('admin.add_earned_leave.edit',$value->id) }}" data-toggle="modal" data-target="#modal_dialog"><i class="fa fa-edit editable"></i></a>
                                 <a href="javascript::void()" data-id='{{ $value->id }}' onclick="removeItem(this)"><i class="fa fa-trash deletable"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        
                    </tbody>
                </table>
                </div>
        </div>
    </div>
</div>
@endif


<div class="row"> 
    <div class="col-sm-12">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong> Add Leave carry forward</strong>
                </div>
            </div>
            <div class="panel-body">
            <form id="leave-form" method='POST' role="form" enctype="multipart/form-data" action="/admin/{{$route}}" method="post" class="form-horizontal form-groups-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="user_id" class="col-sm-3 control-label">Department</label>

                        <div class="col-sm-5">
                            <select  name="dep_id" class="form-control select_box" id='dep_id'>
                                <option value="">Select Department</option>
                                @foreach($department as $dep)
                                <option value="{{ $dep->departments_id }}" @if($dep->departments_id == ($depid ?? \Auth::user()->departments_id) ) selected="selected" @endif>{{ $dep->deptname }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                      
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5 ">
                            <button type="submit" id="sbtn" class="btn btn-primary">Apply</button>
                        </div>
                    </div>
                @if(!$selectedusers)
                    <div class="form-group" >
                        <div class="col-sm-12 " style="background-color: #F8F9FA;">
                            <div class="row">
                                <h3>User List <i class="fa fa-spinner fa-spin" style="display:none;" id='spinner'></i></h3>
                                <table class="table table-striped" id='users-table'>
                                    <thead>
                                        <tr >
                                            <th>Username</th>
                                            <th style="text-align: center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id='dep-users'>
                                        @foreach($users as $key=>$user)
                                            <tr>
                                                <td>
                                                    <img src="{{$user->avatar}}" style="height: 30px;width: 30px;"> 
                                                    &nbsp;{{$user->first_name}}&nbsp;{{$user->last_name}} #({{$user->id}})
                                                </td>
                                                
                                                <td  style="text-align: center;"><input type="checkbox" 
                                                    name="user_id[]" value="{{ $user->id }}"></td>
                                            </tr>
                                        @endforeach
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                 @endif
                
            @if($selectedusers)
                 <div class="form-group">
                    <div class="col-sm-12">
                        <div class="row">
                            <h3>User List</h3>
                            <table class="table table-striped" id='users-table'>
                                <thead>
                                    <tr >
                                        <th>Username</th>
                                        <th>Leave Year</th>
                                        <th>Gained Leave</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id='dep-users'>
                                    @foreach($selectedusers as $key=>$user)
                                        <tr>
                                            <td style="white-space: nowrap;">
                                            <input type="hidden" name="user_id[]" value="{{$user->id}}" class="user_id"> 
                                                <img src="{{$user->avatar}}" style="height: 30px;width: 30px;"> 
                                                &nbsp;{{$user->first_name}}&nbsp;{{$user->last_name}}
                                            </td>
                                            <td>
                                                <select name="from_leave_year_id[]" 
                                                class="form-control bg-default input-sm"  required="">

                                                    @foreach($leaveYear as $key=>$lyr)
                                                    <option value="{{ $lyr->id }}" 
                                                    @if($currentYear->id == $lyr->id ) selected="" 
                                                    @endif>{{ $lyr->leave_year }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="num_of_carried[]" step="any" 
                                                class="form-control input-sm" placeholder="Enter gained leave...">
                                            </td>
                                            
                                            <td>
                                              <a href="javascript::void()">  <i class="fa fa-trash deletable remove-this"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </form>
            </div>
        </div>
    </div>
</div>
@section('body_bottom')
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script type="text/javascript">
   var fixDatatabe = true;
    @if($selectedusers)
    fixDatatable = false;
    const datevalidation = {
        @foreach($selectedusers as $key=>$v)
            user{{$v->id}}: false,
        @endforeach
    }
    // $('#leave-form').submit(function(){
    //         let arr = Object.values(datevalidation);
    //         let check = arr.every((val, i, arr) => val === true)
    //         if(!check){
    //             alert("Please Correct Every dates");
    //             return false;
    //         }


    // })
        $('.datepicker').datetimepicker({
                //inline: true,
                format: 'YYYY-MM-DD',
                sideBySide: true,
                widgetPositioning: {
                    vertical: 'bottom'
                }
        });

      $(".datepicker").on("dp.change", function (e) {
            var parent = $(this).parent().parent();
            var start_date = parent.find('.start_date').val();
            var end_date = parent.find('.end_date').val();
            var user_id = parent.find('.user_id').val();
            var leave_category_id = parent.find('.leave_category').val()
            if( end_date && start_date){
              
                let start_date1 = new Date(start_date).getTime();
                let end_date1 = new Date(end_date).getTime();
                console.log(start_date,end_date)
                if(start_date1 > end_date1){
                    datevalidation[`user${user_id}`] = false;
                    parent.find('.validate').html("Start Date Must Be Less or equal to end date");
                }else if(leave_category_id != '' && end_date && start_date){
                        $.ajax({
                            url: "/admin/check_user_available_leave",
                            data: { user_id:user_id, start_date:start_date, end_date:end_date, leave_category_id:leave_category_id },
                            dataType: "json",
                            success: function( data ) {
                                var result = data.msg;
                                var msg = result.trim();
                                if (msg) {
                                    datevalidation[`user${user_id}`] = false;
                                    parent.find('.validate').html(result);
                                } else {
                                    datevalidation[`user${user_id}`] = true;
                                }
                            }
                        });
                }
                else{
                    datevalidation[`user${user_id}`] = true;
                    parent.find('.validate').html("");
                }
            }
            
        });
    
        $(document).on('click','.remove-this',function(){
          
            $('#users-table').DataTable().destroy();
            let parent = $(this).parent().parent().parent();
            var user_id = parent.find('.user_id').val();
            datevalidation[`user${user_id}`] = true; // user removed from validation
            parent.remove();
            $('#users-table').DataTable();
        });
    @endif
    $('#dep_id').change(function(){
        var depid = $(this).val();
        @if($selectedusers)
            window.location = `/admin/add_earned_leave?dep_id=${depid}`
        @else
            $('#spinner').show();
            
            if(depid.trim() == ''){
                depid = 'all';
            }
            $.get(`/admin/usersbydep/${depid}?avatar=true`,function(res){
                let users = res.users;
                var options = '';
                 $('#users-table').DataTable().destroy();

                for(let user of users){
                    let html =`<tr>
                                <td>
                                    <img src="${user.avatar}" style="height: 30px;width: 30px;"> 
                                    &nbsp;${user.first_name}&nbsp;${user.last_name}  #(${user.id})
                                </td>
                                
                                <td  style="text-align: center;"><input type="checkbox" 
                                    name="user_id[]" value="${ user.id }"></td>
                            </tr>`;
                            
                    options = options + html;
                }
                $('#spinner').hide();
                $('#dep-users').html(options);
                $('#users-table').DataTable()
            
            }).fail(function(){
                $('#spinner').hide();
                alert("Failed To Load");
            });
        @endif
    });
const datatables =     $('#users-table').DataTable({ "paging": false });
$('form').on('submit', function(e){
      var form = this;

      if(!fixDatatable){
        return;
      }

      // Encode a set of form elements from all pages as an array of names and values
      var params = datatables.$('input,select,textarea').serializeArray();

      // Iterate over all form elements
      $.each(params, function(){     
         // If element doesn't exist in DOM
         if(!$.contains(document, form[this.name])){
            // Create a hidden element 
            $(form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', this.name)
                  .val(this.value)
            );
         } 
      });      
});
function removeItem(ev){

    let c  = confirm("Are You Sure");
    if(!c){

        return false;
    }


    let el = $(ev);

    let id = el.attr('data-id');

    location.href = `/admin/add_earned_leave/${id}/destroy`;

}
</script>
@endsection
@endsection