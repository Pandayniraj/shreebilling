@extends('frontend.layouts.app')
@section('content')

<!-- main-cont -->
<div class="main-cont" style="padding:20px;text-align:center">
    <div class="">
        <div class="mp-slider search-only">

        </div>

        <h4 class="text-center">@if(!$redirect) Please Contact {{env('APP_COMPANY')}} @else Redirecting  back @endif</h4>

        <?php  $apiError =  Request::session()->pull('flighterrors'); ?>

        <small><b>Reason:-</b><br> {!! $apiError ? $apiError : $reason !!}</small>
        <?php 

        Request::session()->forget('key'); 


        ?>
    </div>
</div>
<script type="text/javascript">
    $(function(){
    	@if($redirect)
        location.href = `/flights?{!! $query !!}`;
        @endif
    });


</script>
@endsection
