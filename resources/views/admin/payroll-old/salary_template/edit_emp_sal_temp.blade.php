<div class="modal-content">
    <div id="printableArea">
        <div class="modal-header hidden-print">
            <h4 class="modal-title" id="myModalLabel">Edit Salary Template
                <small>Update <b
                    title='{{$current_temp->user->first_name}} {{$current_temp->user->last_name}}'>
                    {{$current_temp->user->username}}</b> Salary Template</small>
                <div class="pull-right ">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </h4>
        </div>
        <div class="modal-body wrap-modal wrap">

            <form method="post" class="form-horizontal" id='editEmpSalTemp'
            action="{{route('admin.payroll.manage_emp_salary_template',
            $current_temp->payroll_id)}}">
            {{csrf_field()}}
                <div class="form-group">
                  <label class="control-label col-sm-2">Current Template</label>
                  <div class="col-sm-10">
                    <select class="form-control searchable" name='salary_template_id'>
                        <option value="">Select Salary Template</option>
                        @foreach($templates as $key=>$temp)
                            <option value="{{$temp->salary_template_id}}" 
                                @if($temp->salary_template_id == $current_temp->salary_template_id) selected="" @endif>
                                {{$temp->salary_grade}}
                            </option>
                        @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group">        
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped">
                    <caption>Salary Template Histroy for <b title='{{$current_temp->user->first_name}} {{$current_temp->user->last_name}}'>{{$current_temp->user->username}}</b></caption>
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Salary Template Name</th>
                            <th>Created Date</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histroy as $key=>$h)
                        <tr>    
                            <td>{{++$key}}</td>
                            <td>{{$h->template->salary_grade}}</td>
                            <td>{{date('dS M Y',strtotime($h->created_at))}}</td>
                            <td>{{$h->createdBy->username}}[{{$h->createdBy->id}}]</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#editEmpSalTemp .searchable').select2({
        dropdownParent: $("#modal_dialog"),
        theme:'bootstrap'
    });
</script>