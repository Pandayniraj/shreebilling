@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {!! $page_title ?? "Page title" !!}
               
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
          

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="box box-header">
   
    <div class="">
       
      

        <div style="min-height:200px" class="" id="">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="bg-purple">
                      <th class="text-center">Id</th>
                      <th class="text-center">Types</th>
                        <th class="text-center">Tran Date</th>
                        <th class="text-center">Module</th>
                        <th class="text-center">Comment</th>
                        <th class="text-center">Reason</th>
                        <th class="text-center">Total Amt.</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>  
                    @foreach($transations as $k => $result)
                      <tr>
                        <td align="center">000{{$result->id}}</td>
                        <td align="center">{{ $result->stockentrytype->name }}</td>
                        <td align="center">{{$result->tran_date}}</td>
                      <td>{{$result->modules}}</td>
                      <td align="center">{{$result->comment}}</td>
                      <td align="center">{{$result->reason->name}} 

                        @if($result->reason->reason_type == "positive")
                        <span style="color: green" class="material-icons">arrow_circle_up </span>
                        @endif

                        @if($result->reason->reason_type == "negative")
                         <span style="color: red" class="material-icons">arrow_circle_down </span>
                        @endif

                      </td>
                      <td align="center">{{$result->total_value}}</td>
                      <td align="center"><a href="/admin/stockentry_detail/{{$result->id}}"> <i class="fa fa-eye"></a></td>
                    </tr>
                    @endforeach


                </tbody>
            </table>

            {!! $transations->render() !!}

        </div>

      

        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>
@endsection