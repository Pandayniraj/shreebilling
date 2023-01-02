@extends('layouts.master')

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {{ $page_title ?? 'Page Title' }}
                <small>{{$page_description ?? 'Page Description'}}</small>
            </h1>
           
</section>

  <div class="nav-tabs-custom" id="tabs">

    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">
                <div class="col-md-6">

                  <form action="/admin/payroll/assign_remote_taxable_amount_store" method="post">

                    {{ csrf_field() }}

                       <div class="content">

                            <div class="form-group">
                                {!! Form::label('group_name', 'Remote Group Name') !!}
                                <select name="group_id" class="form-control">
                                   @foreach($categories as $index=>$val)
                                    <option value="{{$val->id}}">{{$val->group_name}}</option>
                                   @endforeach 
                            </select>
                            </div>

                             <div class="form-group">
                                    {!! Form::label('district_name  ', 'District Name') !!}
                                    <input type="text"class="form-control" name="district_name">
                             </div>


                        </div><!-- /.content -->
                </div>
<!-- 
                <div class="col-md-6">

                    <div class="content">
                       
                    </div>
                </div> -->
            </div>

            <div class="form-group">
                           {!! Form::button( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit','type'=>'Submit'] ) !!}
                           <a href="{!! route('admin.payroll.remote_taxable_categories') !!}" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
           </div>

                </form>
            
        </div>

        <!-- /.tab-pane -->
    </div>
</div>
@endsection