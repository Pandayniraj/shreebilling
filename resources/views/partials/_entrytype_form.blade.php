<div class="row">
    <div class="col-md-6">
      <div class="form-group">
          <label class="col-sm-2 control-label">Label<i class="imp">*</i></label>
           <div class="col-sm-10">
              <input type="text" class="form-control ticket" name="label" placeholder="Label" value="@if(isset($entrytype->label)){{ $entrytype->label }}@endif" required="required">
          </div>
      </div>
    </div>


  <div class="col-md-6">
    <div class="form-group">
      <label class="col-sm-2 control-label">Name<i class="imp">*</i></label>
         <div class="col-sm-10">
          <input type="text" class="form-control ticket" name="name" placeholder="Name" value="@if(isset($entrytype->name)){{ $entrytype->name }}@endif" >
        </div>
    </div>
   </div>
</div>

<div class="row">
    <div class="col-md-6">
      <div class="form-group">
          <label class="col-sm-2 control-label">Prefix</label>
           <div class="col-sm-10">
              <input type="text" class="form-control ticket" name="prefix" placeholder="Prefix" value="@if(isset($entrytype->prefix)){{ $entrytype->prefix }}@endif">
          </div>
      </div>
    </div>


  <div class="col-md-6">
    <div class="form-group">
      <label class="col-sm-2 control-label">Suffix</label>
         <div class="col-sm-10">
          <input type="text" class="form-control ticket" name="suffix" placeholder="Suffix" value="@if(isset($entrytype->suffix)){{ $entrytype->suffix }}@endif" >
        </div>
    </div>
   </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label class="col-sm-2 control-label">Description</label>
         <div class="col-sm-10">
          <textarea placeholder="Description" class="form-control">@if(isset($entrytype->description)){{ $entrytype->description }}@endif</textarea>
    </div>
    </div>
   </div>
</div>
   
