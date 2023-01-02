@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {!! $page_title ?? "Page description" !!}
                <small>{!! $page_description ?? "Page description" !!}


                </small>
            </h1>

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

   <div class="col-xs-12">
          <div class="box">

            <!-- /.box-header -->
            <div class="box-body">
                    <!-- <div id="accordion"> -->
                        <!-- <h3>Options</h3> -->
                        <div class="balancesheet form">
                            <form  method="GET" action="/admin/coa/filterbygroups">
                                {{ csrf_field() }}
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">

                                        <select class="form-control customer_id select2" id="ReportLedgerId" name="parent_id" aria-hidden="true" required>
                                            <option value="">Select Account Head</option>
                                            @foreach($parentgroups as $pg)
                                            <option value="{{$pg->id}}">{{$pg->name}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                            <div class="col-md-6">
                            <div class="form-group">
                                <input type="reset" name="reset" class="btn btn-primary pull-right" style="margin-left: 5px;" value="Clear">
                                <input type="submit" class="btn btn-primary pull-right" value="Submit">
                            </div></div>   </div>
                            </form>
                        </div>
                    <!-- </div> -->
                 </div>
          </div>
      </div>






 @endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')


@endsection
