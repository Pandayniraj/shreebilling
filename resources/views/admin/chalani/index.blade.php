@extends('layouts.master')
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ $page_title }}
        <small>View Chalani</small>
    </h1>

   

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class="box box-primary">
    <div class="box-header with-border">
        <div class='row'>
            <div class='col-md-12'>
           
                <div style="display: inline;">
                    <a class="btn btn-primary btn-sm" title="Create New Ticket" href="{{ route('admin.chalani.create') }}">
                        <i class="fa fa-plus"></i>&nbsp;<strong> 
                        {{ trans('admin/ticket/general.button.create') }}</strong>
                    </a>
                </div>

                <div class="box-tools pull-right">
                           <input type="text" class="input-sm" placeholder="{{ trans('admin/ticket/general.placeholder.search') }}" id='search-term'>
                           <button class="btn btn-primary btn-sm" id='search' type="button"><i class="fa fa-search"></i>&nbsp;{{ trans('admin/ticket/general.button.search') }}</button>
                           <button class="btn btn-danger btn-sm"  id='clear'><i class="fa fa-close"></i>&nbsp;{{ trans('admin/ticket/general.button.clear') }}</button>
                        </div>

            </div>
        </div>





        <div class="tab-responsive">
       		<table class="table table-striped">
            
                <thead>
                    <tr>
                        
                        <th>{{ trans('admin/chalani/general.columns.id') }}</th>
                        <th>{{ trans('admin/chalani/general.columns.date') }}</th>
                        <th>{{ trans('admin/chalani/general.columns.letter_number') }}</th>
                        <th>{{ trans('admin/chalani/general.columns.subject') }}</th>
                        <th>{{ trans('admin/chalani/general.columns.letter_date') }}</th>
                        <th>{{ trans('admin/chalani/general.columns.ticket_number') }}</th>
                        <th>{{ trans('admin/chalani/general.columns.action') }}</th>

                    </tr>

                </thead>

                <tbody>
                @foreach($chalani as $key => $dar)
                    <tr>

                        <td> #{{$dar->id}} </td>
                        <td>  {{ date('dS M Y',strtotime($dar->date)) }} </td>
                        <td>{{ $dar->letter_num }}</td>
                        <td>
                        <a href="#" class="text-muted"> 
                            {{ $dar->subject }}
                        </a>
                        </td>
            
                        <td>
                            {{ date('dS M Y',strtotime($dar->letter_date)) }}
                        </td>
                        <td>
                           #{{ $dar->ticket->ticket_number ?? '-' }}
                        </td>
                        <td>
                            
                             @if ( $dar->isEditable()  )
                                    <a href="{!! route('admin.chalani.edit', $dar->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                @else
                                    <i class="fa fa-edit text-muted" title="{{ trans('admin/cases/general.error.cant-edit-this-document') }}"></i>
                                @endif
                                @if ( $dar->isDeletable() )
                                    <a href="{!! route('admin.chalani.confirm-delete', $dar->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                @else
                                    <i class="fa fa-trash text-muted" title="{{ trans('admin/cases/general.error.cant-delete-this-document') }}"></i>
                                @endif

                        </td>

                    </tr>
                @endforeach
            </tbody>

            </table>
            <div align="center"> {!! $chalani->render() !!} </div>
        </div>


    </div>
</div>

<script type="text/javascript">
    

      $('#search').click(function(){
            let val =  $('#search-term').val();
            window.location.href = `{{ url('/') }}/admin/chalani?term=${val}`;
        });
        $('#clear').click(function(){
            window.location.href = `{{ url('/') }}/admin/chalani`;
        });
</script>
@endsection