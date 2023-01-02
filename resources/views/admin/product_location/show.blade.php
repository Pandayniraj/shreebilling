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
background:red;
vertical-align:middle;
margin-right:0.3em;
color:white;
}
</style>
  <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h2>
             <a href="#" data-toggle="modal" data-target="#saveLogo">
              <small data-letters="{{mb_substr($show->location_name,0,3)}}"></small>
            </a>
           			{{$show->location_name}}
                </h2>


        </section> 
<div>
        <div class='col-md-4'>
        	    <p> <i class="fa  fa-location-arrow"></i>&nbsp;<b>Delivery address:</b> 
                    <a style="color: black" >  {{ ucfirst(trans($show->delivery_address)) }}</a> 
                  </p>
                   <p> <i class="fa  fa-envelope-o"></i>&nbsp;<b>Email:</b> 
                    <a style="color: black" >{{$show->email  }}</a> 
                  </p>
                  
                  <p> <i class="fa   fa-user"></i>&nbsp;<b>Contact person:</b> 
                    <a style="color: black" > {{ ucfirst(trans($show->contact_person)) }}</a> 
                  </p>
               
                   
        </div>
          <div class='col-md-4'>
        	    <p> <i class="fa  fa-location-arrow"></i>&nbsp;<b>Location code:</b> 
                    <a style="color: black" > {{ ucfirst(trans($show->loc_code)) }} </a> 
                  </p>
                    <p> <i class="fa    fa-users"></i>&nbsp;<b>Phone:</b> 
                    <a style="color: black" >{{$show->phone }} </a> 
                  </p>
                   <p> <i class="fa   fa-money"></i>&nbsp;<b>Enabled:</b> 
                    <a style="color: black" > {{ ucfirst(trans($show->location_name)) }} </a> 
                  </p>
                  <p> <i class="fa"></i>
                    
                  </p>

        </div>
</div>
  
<div class="col-md-7" style="margin-left: -5px">
   <div class="form-group">
                        	<a href="javascript:history.back()" class='btn btn-danger'>{{ trans('general.button.close') }}</a>
                        	
	                                <a  href="{{route('admin.product-location.edit',$show->id)}}" class='btn btn-success'>{{ trans('general.button.edit') }}</a>
	                         
                        </div>
</div>
@endsection