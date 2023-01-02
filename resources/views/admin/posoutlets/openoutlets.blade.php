@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')
@endsection

@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{$page_title ?? "Page Title"}}
        <small>{{$description}}</small>

    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>


<style type="text/css">
    .btn-circle.btn-xl {
        width: 115px;
        height: 115px;
        padding: 24px 30px;
        border-radius: 60px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
    }

</style>




<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">
                <div class="row">

                    <div class='col-md-12'>

                        @foreach($outlets as $k => $v)
                        <button type="button" onclick="window.open('/admin/orders/create?type=invoice&outlet_id={{ $v['id'] }}','_self')" class="btn btn-primary btn-circle btn-xl" style="text-align: center;">{{ $v['short_name'] }}</a>
                            @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });

</script>

<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true
            , allowInputToggle: true
        });

    });

</script>


@endsection
