@extends('layouts.master')
@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
@endsection
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
   <h1>
      Product Unit
      <small>{{$description}}</small>
   </h1>
   {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>
@if(isset($edit))
<form method="post" action="{{route('admin.production.edit-produnit',$edit->id)}}">
   {{ csrf_field() }}
   <div class="panel panel-custom">
      <div class="panel-heading">
         <div class="row">
            <div class="col-sm-12">
               <div class="form-group">
                  <label class="control-label col-sm-12">Name</label>
                  <div class="input-group ">
                     <input type="text" name="name" placeholder="Name" id="name" value="{{$edit->name}}" class="form-control" required>
                     <div class="input-group-addon">
                        <a href="#"><i class="fa fa-stack-exchange"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-12">
               <div class="form-group">
                  <label class="control-label col-sm-12">Symbol</label>
                  <div class="input-group ">
                     <input type="text" name="symbol" placeholder="Symbol" id="symbol" class="form-control" value="{{$edit->symbol}}" required>
                     <div class="input-group-addon">
                        <a href="#"><i class="fa fa-lastfm"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-12">
               <div class="form-group">
                  <label class="control-label col-sm-12">Quantity Count</label>
                  <div class="input-group ">
                     <input type="text" name="qty_count" placeholder="qty_count" id="qty_count" class="form-control" value="{{$edit->qty_count}}" required>
                     <div class="input-group-addon">
                        <a href="#"><i class="fa fa-lastfm"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="form-group">
                  <button class="btn btn-primary" id="btn-submit-edit" type="submit" >Update</button>
                  <a class="btn btn-default"
                    href="/admin/production/product-unit-index">Close</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</form>
@else
<form method="post" action="{{route('admin.production.products-unit')}}">
   {{ csrf_field() }}
   <div class="panel panel-custom">
      <div class="panel-heading">
         <div class="row">
            <div class="col-sm-12">
               <div class="form-group">
                  <label class="control-label col-sm-12">Name</label>
                  <div class="input-group ">
                     <input type="text" name="name" placeholder="Name" id="name" class="form-control" required>
                     <div class="input-group-addon">
                        <a href="#"><i class="fa fa-stack-exchange"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-12">
               <div class="form-group">
                  <label class="control-label col-sm-12">Symbol</label>
                  <div class="input-group ">
                     <input type="text" name="symbol" placeholder="Symbol" id="symbol" class="form-control" required>
                     <div class="input-group-addon">
                        <a href="#"><i class="fa fa-lastfm"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-12">
               <div class="form-group">
                  <label class="control-label col-sm-12">Quantity Count</label>
                  <div class="input-group ">
                     <input type="text" name="qty_count" placeholder="Symbol" id="symbol" class="form-control" value="1" required>
                     <div class="input-group-addon">
                        <a href="#"><i class="fa fa-lastfm"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="form-group">
                  <button class="btn btn-primary" id="btn-submit-edit" type="submit" >Add</button>
                    <a class="btn btn-default"
                    href="/admin/production/product-unit-index">Close</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</form>
@endif
@endsection