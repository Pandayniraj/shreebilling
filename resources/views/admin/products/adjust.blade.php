@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
@endsection

@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
              {!! $page_title or "Page title" !!}
               
                <small>{!! $page_description or "Page description" !!}</small>
            </h1>
            <p> Whether a supplier gift us some stock or some stocks are just got broken</p>

             <br/>

        

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>



<div class="box box-header">

 <div class="pull-right">
    <a class="btn btn-sm btn-primary" href=""> + Add Adjustment </a>
   </div>
   
    <div class="">
       
      

        <div style="min-height:200px" class="" id="">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="bg-purple">
                      <th class="text-center">Adj Id#</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Warehouse Location</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Reason</th>
                        <th class="text-center">Lines</th>
                        <th class="text-center"> Total Adj Qty</th>
                         <th class="text-center"> Total Adj Price Value</th>
                    </tr>
                </thead>
                <tbody>  
                   
               <tr>
                 <td></td>
               </tr>

                </tbody>
            </table>

            

        </div>

      

        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>
@endsection