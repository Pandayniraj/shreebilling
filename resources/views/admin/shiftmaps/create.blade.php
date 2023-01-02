@extends('layouts.master')
@section('content')
 <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
 <script type="text/javascript" src="/bower_components/tags/js/tag-it.js"></script>
 <link href="/bower_components/tags/css/jquery.tagit.css" rel="stylesheet" type="text/css"/>
 <link href="/bower_components/tags/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css"/>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title"}} 
                <small> {{ $page_description ?? "Page Description" }}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

 <div class='row'>
       <div class='col-md-12'>
          <div class="box">
		     <div class="box-body ">
		     	<form method="post" action="/admin/shifts/maps" enctype="multipart/form-data" id='mapShift'>  
		     	{{ csrf_field() }}   

		     	    <div class="row">

	                   	<div class="col-md-4">
	                   	    <label class="control-label">Shift Name</label>
                            <select  name="shift_id"  id="shift_id" class="form-control" required>
                               <option value="">Select Option</option>  
                               @foreach($shifts as $shift)
                                 <option value="{{$shift->id}}">{{$shift->shift_name}}</option>   
                               @endforeach                      	
                            </select>
	                   	</div>

	                   	<div class="col-md-4">
	                   	    <label class="control-label">Employees Department</label>
                            
                            {!! Form::select('departments',$departments,null,['class'=>'form-control','placeholder'=>'All Department','id'=>'departments']) !!}

	                   	</div>
	                   
                    </div>

                    <div class="row">
                    	<div class="col-md-4">
	                   	    <label class="control-label">Map From Date</label> 
                            <input type="text" name="map_from_date" placeholder="Map From Date" id="setup" value="" class="form-control datepicker" >
	                   	</div>
	                   	<div class="col-md-4">
	                   	    <label class="control-label">Map To Date</label>
                            <input type="text" name="map_to_date" placeholder="Map To Date" id="setdown" value="" class="form-control datepicker" >
	                   	</div>
                    	
                    </div>
                   

               
                 <div class="row">
                    <div class="col-md-12">
                       
                          <h4>User List Based on Department<i class="fa fa-spinner fa-spin" style="display:none;" id='spinner'></i></h4>
                                <table class="table table-striped" id='users-table'>
                                    <thead>
                                        <tr class="bg-maroon">
                                            <th class="col-sm-1">S.N</th>
                                            <th>Username</th>
                                            <th>Desigination</th>
                                            <th style="text-align: center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id='dep-users'>
                                        @foreach($users as $key=>$user)
                                            <tr>
                                                <td>{{++$key}}</td>
                                                <td>
                                                    <img src="{{$user->avatar}}" style="height: 30px;width: 30px;"> 
                                                    &nbsp;{{$user->first_name}}&nbsp;{{$user->last_name}} #({{$user->id}})
                                                </td>
                                                <td>
                                                    {{$user->designation->designations}}
                                                </td>
                                                <td  style="text-align: center;">
                                            <input type="checkbox" 
                                            name="user_id[]" value="{{ $user->id }}"></td>
                                            </tr>
                                        @endforeach
                                </tbody>
            </table>
                    </div>
                </div>



		    

                <div class="row">
	                <div class="col-md-12">
	                    <div class="form-group">
	                        {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
	                        <a href="{!! route('admin.shift.maps.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
	                    </div>
	                 </div>
	            </div>


		     </form>
		
	</div>
</div>

    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
	<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

 <script type="text/javascript">
   const  datatables =  $('#users-table').DataTable();
    $(function(){
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',   
          sideBySide: true,
          allowInputToggle: true
        });

      });
</script>

<script type="text/javascript">

    $(function() {
    jQuery("#employees").tagit({
        singleField: true,
        singleFieldNode: $('#peoplesField'),
        allowSpaces: true,
        minLength: 1,
        tagLimit: 15,
        placeholderText: 'Enter User Name',
        allowNewTags: false,
        requireAutocomplete: true,

        removeConfirmation: true,
        tagSource: function( request, response ) { 
            //console.log("1");
            $.ajax({
                url: "/admin/getUserTagsJson",
                data: { term:request.term },
                dataType: "json",
                success: function( data ) {
                    response( $.map( data, function( item ) {
                        return {
                            label: item.username ,
                            value: item.value
                        }
                    }));
                }
            });
        }
    });

});
$('#departments').change(function(){
     $('#spinner').show();
    var depid = $(this).val();
    if(depid.trim() == ''){
        depid = 'all';
    }
     $.get(`/admin/usersbydep/${depid}?avatar=true`,function(res){
                let users = res.users;
                var options = '';
              $('#users-table').DataTable().destroy();
                var count = 0;
                for(let user of users){
                    let html =`<tr>
                    <td>${++count}</td>
                                <td>
                                    <img src="${user.avatar}" style="height: 30px;width: 30px;"> 
                                    &nbsp;${user.first_name}&nbsp;${user.last_name}  #(${user.id})
                                </td>
                                <td>${user.designations}</td>
                                <td  style="text-align: center;"><input type="checkbox" 
                                    name="user_id[]" value="${ user.id }"></td>
                            </tr>`;
                            
                    options = options + html;
                }
                console.log(options)
                $('#spinner').hide();
                $('#dep-users').html(options);
                $('#users-table').DataTable();
            
            }).fail(function(){
                $('#spinner').hide();
            })
});


$('#mapShift').on('submit', function(e){
      var form = this;
 
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

</script>



@endsection