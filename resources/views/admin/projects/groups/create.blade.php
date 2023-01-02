@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')


<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_descriptions ?? "Page description" !!}</small>
                <small id='ajax_status'></small>
            </h1> {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">
              <form action="{{ route('admin.projects.group.create', \Request::segment(4)) }}" method="POST" role="form">
                {{ csrf_field() }}
                      <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Group Name</label>
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" required="" placeholder="Group name should be unique to each project">
                                <div class="input-group-addon">
                                <a href="#"><i class="fa  fa-user"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Descriptions</label>
                            <div class="input-group">
                                <textarea name="description" placeholder="Group Descriptions" class="form-control">
                                </textarea>
                                <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-sticky-note-o"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="" name="enabled" checked="">
                                    Enabled
                                </label>
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-6">
                    <button type="submit" class="btn btn-primary" id='btn-submit-edit'>Create Group</button>
                    <a href="/admin/projectsgroups/{{ \Request::segment(4) }}" class="btn btn-default">Cancel</a>

                </div>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
      $('textarea').each(function(){
            $(this).val($(this).val().trim());
        }
    );
</script>
@endsection
