@php 

$announcement = isset($announcement) ? $announcement : null;
@endphp

<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">@if($announcement) Edit @else New @endif Announcements</h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <form role="form" id="announcement_form" action="/admin/announcements/save" method="post" class="form-horizontal form-groups-bordered">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="field-1" class="col-sm-3 control-label">Title <span class="required">*</span></label>

                <div class="col-sm-8">
                    <input type="text" name="title" value="@if($announcement){{$announcement->title}}@endif" class="form-control" required />
                </div>
            </div>
            <div class="form-group">
                <label for="field-1" class="col-sm-3 control-label">Description</label>

                <div class="col-sm-8">
                    <textarea name="description" class="form-control textarea">@if($announcement){{$announcement->description}}@endif</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Start Date <span class="required">*</span></label>

                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" name="start_date" id="start_date" placeholder="Enter Start Date" class="form-control datepicker" value="@if($announcement){{$announcement->start_date}}@endif" required>
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">End Date <span class="required">*</span></label>

                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" name="end_date" id="end_date" placeholder="Enter End Date" class="form-control datepicker" value="@if($announcement){{$announcement->end_date}}@endif" required>
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="field-1" class="col-sm-3 control-label">Share With</label>

                <div class="col-sm-8">
                    <div class="col-sm-4 row">
                        <div class="radio-inline c-radio">
                            <label>
                                <input class="select_one" type="radio" name="share_with" value="all_staff" @if($announcement && $announcement->share_with == 'all_staff') checked @endif required>
                                All Staff </label>
                        </div>
                    </div>
                    <div class="col-sm-4 row">
                        <div class="radio-inline c-radio">
                            <label>
                                <input class="select_one" type="radio" name="share_with" value="team" @if($announcement && $announcement->share_with == 'team') checked @endif required>
                                Team </label>
                        </div>
                    </div>
                    <div class="col-sm-4 row">
                        <div class="radio-inline c-radio">
                            <label>
                                <input class="select_one" type="radio" name="share_with" value="department" @if($announcement && $announcement->share_with == 'department') checked @endif required>
                                Department </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="team_announce" style="display:none;">
                <label for="field-1" class="col-sm-3 control-label">Team <span class="required">*</span></label>
                <div class="col-sm-8">
                    <select name="team_id" class="form-control">
                        <option value="">Please Select</option>
                        @foreach($teams as $team)
                        <option value="{{$team->id}}" @if($announcement && $team->id == $announcement->team_id) selected @endif>{{$team->name}} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group" id="department_announce" style="display:none;">
                <label for="field-1" class="col-sm-3 control-label">Department <span class="required">*</span></label>
                <div class="col-sm-8">
                    <select name="department_id" class="form-control">
                        <option value="">Please Select</option>
                        @foreach($departments as $department)
                        <option value="{{$department->departments_id}}" @if($announcement && $department->departments_id == $announcement->department_id) selected @endif>{{$department->deptname}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="field-1" class="col-sm-3 control-label">Status</label>

                <div class="col-sm-8">
                    <div class="col-sm-4 row">
                        <div class="radio-inline c-radio">
                            <label>
                                <input class="select_one" type="radio" name="status" value="published" @if($announcement && $announcement->status == 'published') checked @endif required>
                                Published </label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="radio-inline c-radio">
                            <label>
                                <input class="select_one" type="radio" name="status" value="unpublished" @if($announcement && $announcement->status == 'unpublished') checked @endif required>
                                UnPublished </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <label for="field-1" class="col-sm-3 control-label">Placement</label>

                <div class="col-sm-8">

                    <div class="col-sm-3">
                        <div class="radio-inline c-radio">
                            <label>
                                <input class="select_placement" type="radio" name="placement" value="internal" @if($announcement && $announcement->placement == 'internal') checked @endif required>
                                Internal </label>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="radio-inline c-radio">
                            <label>
                                <input class="select_placement" type="radio" name="placement" value="external" @if($announcement && $announcement->placement == 'external') checked @endif required>
                                External </label>
                        </div>
                    </div>

                    <div class="col-sm-3 ">
                        <div class="radio-inline c-radio">
                            <label>
                                <input class="select_placement" type="radio" name="placement" value="login" @if($announcement && $announcement->placement == 'login') checked @endif required>
                                Login </label>
                        </div>
                    </div>
                    <div class="col-sm-3 ">
                        <div class="radio-inline c-radio">
                            <label>
                                <input class="select_placement" type="radio" name="placement" value="email" @if($announcement && $announcement->placement == 'login') checked @endif required>
                                email </label>
                        </div>
                    </div>

                </div>
            </div>
            <!--hidden input values -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-2">
                    <input type="hidden" name="announcements_id" value="{{ $announcement ? $announcement->announcements_id : '' }}">
                    <button type="submit" id="sbtn" class="btn btn-primary btn-block">@if(!$announcement) Save @else Edit @endif</button>
                </div>
            </div>
        </form>
    </div>
</div>
