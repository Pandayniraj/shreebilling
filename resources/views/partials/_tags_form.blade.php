<div class="row">
    <div class="col-md-12">
      <div class="form-group">
          <label class="col-sm-2 control-label">Title<i class="imp">*</i></label>
           <div class="col-sm-10">
              <input type="text" class="form-control ticket" name="title" placeholder="Title Name" value="@if(isset($tags->title)){{ $tags->title }}@endif" required="required">
          </div>
      </div>
    </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label class="col-sm-2 control-label">Color<i class="imp">*</i></label>
         <div class="col-sm-10">
          <input type="text" class="form-control ticket" name="color" placeholder="Color" value="@if(isset($tags->color)){{ $tags->color }}@endif" >
        </div>
    </div>
   </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label class="col-sm-2 control-label">BackGround<i class="imp">*</i></label>
         <div class="col-sm-10">
      <input type="text" class="form-control ticket" name="background" placeholder="BackGround" value="@if(isset($tags->background)){{ $tags->background }}@endif" >
    </div>
    </div>
   </div>
</div>
   
