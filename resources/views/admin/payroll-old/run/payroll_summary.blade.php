@extends('layouts.master')

@section('content')
<style type="text/css">
	.col{
		margin-left: 5px;
	}
</style>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                <small>{!! $page_description ?? "Page description" !!}</small>


            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
  				<h3 class="box-title">     
				    Monthly: {{date_format(date_create($payfrequency->period_start_date),'Y/m/d')}} 
				    To: {{date_format(date_create($payfrequency->period_end_date),'Y/m/d')}}  
				    Check date: {{date_format(date_create($payfrequency->check_date),'Y/m/d')}}
				    GL Post date:{{date_format(date_create(date('Y-m-d')),'Y/m/d')}}
  				</h3>
			</div>
			<div class="content">
			<div class="row">
				<div class="col-md-6 padding-md">
					<h2 class='text-success'><i class="fa  fa-check"></i> Success! Your Payroll is done</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
				<h3>
					<small>Total Cash Required</small><br>
					<strong>NRP {{ number_format($totalamount,2)}}</strong>
				</h3>
				</div>
				<div class="col-md-4">
				<h3>
					<small>Check Date</small><br>
					<strong>{{date_format(date_create($payfrequency->check_date),'Y/m/d')}}</strong><br>
					<small>
						Month: <b>{{date_format(date_create($payfrequency->period_start_date),'Y/m/d')}}</b>
						To: <b>{{date_format(date_create($payfrequency->period_end_date),'Y/m/d')}} </b>
					</small><br>
					<small>
						GL Post Date: <b>{{date_format(date_create(date('Y-m-d')),'Y/m/d')}}</b>
					</small>
				</h3>
				</div>
		</div>

	</div>

	</div>
	<div class="row">
			<div class="col-md-4">
				<a class="btn btn-primary" href="/admin/payroll/run/step1"><i class="fa fa-home"></i>&nbsp;Home</a>
			</div>
		</div>
</div>



@endsection