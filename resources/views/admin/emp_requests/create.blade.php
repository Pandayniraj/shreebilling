@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection


@section('content')
        <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
        </section>


<div class='row'>
  <div class='col-md-12'>
    <div class="box">
      <div class="box-body ">
          <form class="form-horizontal" 
            action="{{ route('admin.employeeRequest.create') }}"
            method="POST"
            enctype='multipart/form-data' 
            >
            {{ csrf_field() }}

              <div class="form-group">
                <label class="control-label col-sm-2">Title:
                  <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                  <input type="text" class="form-control"  placeholder="Enter Title" 
                  name="title" required="">
                  <div class="input-group-addon">
                    <a href="#"><i class="fa    fa-edit"></i></a>
                    </div>
                  </div>
                </div>
              </div>


              <div class="form-group">
                <label class="control-label col-sm-2">Staff Name:
                  <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group">

                  <select  class="form-control searchable"  name="emp_id" required="">
                      <option value="">Select Staff</option>
                      @foreach($employee as $key => $e)
                        <option value="{{$e->id}}">{{$e->username}}[{{$e->id}}]</option>
                      @endforeach
                  </select>

                  <div class="input-group-addon">
                    <a href="#"><i class="fa  fa-user"></i></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">Request Team:
                  <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    {!! Form::select('request_team',$teams,null,['class'=>'form-control searchable','placeholder'=>'Select request teams','required'=>'true']) !!}
                  
                  <div class="input-group-addon">
                    <a href="#"><i class="fa  fa-users"></i></a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2">Request Type:
                  <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    {!! Form::select('request_type',$request_types,null,['class'=>'form-control','id'=>'request_type','placeholder'=>'Select request types','required'=>'true']) !!}
                  
                  <div class="input-group-addon">
                    <a href="#"><i class="fa  fa-gg"></i></a>
                    </div>
                  </div>
                </div>
              </div>
              <div id='extra_form_appends'></div>

              <div class="form-group">
                <label class="control-label col-sm-2">Description:
                  <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                  <textarea type="text" class="form-control"  placeholder="Enter Description" 
                  name="description" rows="7"></textarea>
                  <div class="input-group-addon">
                    <a href="#"><i class="fa   fa-database"></i></a>
                    </div>
                  </div>
                </div>
              </div>

              
             {{--   <div class="form-group">
                <label class="control-label col-sm-2">Status:</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    {!! Form::select('status',$request_status,null,['class'=>'form-control']) !!}
                  <div class="input-group-addon">
                    <a href="#"><i class="fa  fa-gg"></i></a>
                    </div>
                  </div>
                </div>
              </div>
              --}}
             
              <div class="form-group">        
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="/admin/employeeRequest/" class="btn btn-default">Cancel</a>
                </div>

              </div>

            </form>
      </div>
    </div>
  </div>
</div>

<div id='extra_form' style="display: none;">
  <div id='festivals'>
      <div class="form-group">
        <label class="control-label col-sm-2">Benefit Type:
          <span class="text-danger">*</span></label>
        <div class="col-sm-10">
          <div class="input-group">
            {!! Form::select('benefit_type',$benefit_types,null,['class'=>'form-control _searchable','placeholder'=>'Select request types']) !!}
          
          <div class="input-group-addon">
            <a href="#"><i class="fa  fa-gg"></i></a>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-2">Pay Type:
          <span class="text-danger">*</span></label>
        <div class="col-sm-10">
          <div class="input-group">
            {!! Form::select('pay_type',$pay_type,null,['class'=>'form-control']) !!}
          
          <div class="input-group-addon">
            <a href="#"><i class="fa  fa-paypal"></i></a>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-2">Cost:
          <span class="text-danger">*</span></label>
        <div class="col-sm-10">
          <div class="input-group">
          <input type="number" class="form-control"  placeholder="Enter Request Cost" 
          name="cost" step="any">
          <div class="input-group-addon">
            <a href="#"><i class="fa    fa-money"></i></a>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-2">Upload Bill:
          <span class="text-danger">*</span></label>
        <div class="col-sm-10">
          <div class="input-group">
          <input type="file" class="form-control" 
          name="attachment" step="any">
          <div class="input-group-addon">
            <a href="#"><i class="fa    fa-file"></i></a>
            </div>
          </div>
         {{--  <a href="">awdawd</a> --}}
        </div>
      </div>


  </div>
</div>
@endsection

@section('body_bottom')




<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<script type="text/javascript">
	
     $(function() {
			
       $('.searchable').select2({theme:'bootstrap'});

       function appendForm(name){
          if(name == 'festival'){
            let html = $('#extra_form #festivals').html();
            $('#extra_form_appends').html(html);
            $('#extra_form_appends ._searchable').select2({theme:'bootstrap'})
          }else{
             $('#extra_form_appends').html("");
          }
       }


       $('#request_type').change(function(){
        let val = $(this).val();
        appendForm(val);
       });


		});
     
    

</script>
@endsection
