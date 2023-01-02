
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Showing Allowance For {{$user->first_name}} {{$user->last_name}} [{{$user->id}}]</h4>
    @if(!$modified)
    <span style="text-align: right;">
    	<a href="/admin/payroll/salary_template/{{$template->salary_template_id}}" target="_blank">Click Here To Add</a>
    </span>
    @else
     <span style="text-align: right;color:red;">
    	Modified By <b>{{$enter_pay->issuedBy->username}}</b> at <b>{{date('dS Y M',strtotime($enter_pay->updated_at))}}</b>
    </span>
    @endif
</div>
<div class="modal-body">

<form action="#" id='user_allowance_customize_form'>
<input type="hidden" name="user_id" value="{{$user_id}}">
@foreach($allowances as $key => $a)
<div class="row">
   	<div class="col-md-12 form-group">
   	    <label class="control-label allowance_label" >{{$a->allowance_label}}</label>
   	    <input type="hidden" name="formatted_label[]" value="{{\TaskHelper::make_name_slug($a->allowance_label)}}" class="form-control formatted_label">
        <input type="number" name="allowances" placeholder="{{$a->allowance_label}}"  
        value="{{$a->allowance_value}}" class="form-control allowance_value {{\TaskHelper::make_name_slug($a->allowance_label)}}"  step="any">
   	</div>
 </div>
@endforeach           
<div class="modal-footer">
<button type="button" class="btn btn-primary" id='user_allowance_customize'>
    {{ 'Submit' }}
</button>
</div>
</form>

</div>