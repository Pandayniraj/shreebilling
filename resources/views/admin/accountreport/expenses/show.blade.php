@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ ucfirst($page_title)}}
                <small>{!! $page_description or "Page description" !!}</small>
            </h1>
            <p><i class="fa fa-money"></i> MONEY OUT. Record all the expenses, this will automatically maintain AP.</p>

          {{ TaskHelper::topSubMenu('topsubmenu.accounts')}}

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-header">

                {!! Form::open(['route' => ['admin.expenses.patch', $expenses->id], 'method' => 'PATCH', 'id' => 'form_edit_client','class' => 'myfor form-horizontal'] ) !!}
                @include('partials._expenses_form')

      
            @if(count($history) > 0)
            <div class="col-md-12">
                
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="clients-table">
                                <caption>Expenses History</caption>
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>From Amount</th>
                                        <th>To Amount</th>
                                        <th>User</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history as $hy)
                                    <tr>
                                        <td>#{{$hy->id}}</td>
                                        <td>{{$hy->from_amount}}</td>
                                        <td>{{$hy->to_amount}}</td>
                                        <td>{{$hy->user->username}}</td>
                                        <td>{{date('dS Y M',strtotime($hy->created_at))}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
            </div>
            @endif
                        <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            @if($expenses->isEditable())
                                <a href="/admin/expenses/{{$expenses->id}}/edit" class="btn btn-primary">Edit</a>
                            @else
                             <button type="button" disabled="" class="btn btn-danger" disabled="">Edit</button> 
                            @endif

                            <a href="/admin/expenses/" title="{{ trans('general.button.cancel') }}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                        </div>
                        </div>
                 </div> 
                   {!! Form::close() !!}
            </div>
            </div><!-- /.box-body -->

        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_client_edit_form_js')

    <script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        });

      });
</script>

@endsection

