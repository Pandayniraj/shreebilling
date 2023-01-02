@extends('frontend.layouts.app')
@section('content')
<div class="main-cont" style="padding:20px;text-align:center">
    
        {{-- <div class="mp-slider search-only">

        </div> --}}

       {{--  <div class="body-wrapper">
        <div class="wrapper-padding">
            
            <div class="two-colls">

            	<div class="alert alert-success text-left">
  <strong>Sucess!</strong> Please make payment to continue
				</div>
            </div>

            <div class="card">
    <div class="card-header">
    	<h3>  Kathmandu - Pokhara</h3>
    </div>
    <div class="card-body">
    	<div class="row">
    		
    		<div class="col-md-4">
    			

    			<table class="table table-striped table-hover" style="float: right;">
    				<tr>
    					<th class="bg-primary text-white text-left" colspan="2">Flight Time</th>
    				</tr>
    				<tr>
    					<td class="text-left">Departure</td>
    					<td class="text-left"></td>
    				</tr>
    				<tr>
    					<td class="text-left">Departure</td>
    					<td class="text-left"></td>
    				</tr>
    				
    			</table>
    			<table class="table table-striped table-hover">
    				<tr>
    					<th colspan="2" class="text-left text-white bg-success">Flight Time</th>
    				</tr>
    				<tr>
    					<td class="text-left">Departure</td>
    					<td class="text-left"></td>
    				</tr>
    				<tr>
    					<td class="text-left">Departure</td>
    					<td class="text-left"></td>
    				</tr>
    				
    			</table>

    			
    		</div>

    		<div class="col-md-8">
    			 <div class="row">
            <div class="col-md-3">
        <div class="card" style="padding: 10%;">
            <a href="javascript::void()"  value="ipsconnect" class="submitButton">
            <img class="card-img-bottom" src="https://login.connectips.com/resources/custom/login/main-logo.png" alt="Card image" style="width:100px;height: 100px;" >
            </a>
          </div>
        </div>

         <div class="col-md-5">
        <div class="card" style="padding: 10%;">
           <a href="javascript::void()" value="hbl" class="submitButton">
            <img class="card-img-bottom" src="/frontend/img/cardpayment.png" alt="Card image" style="width:250px;height: 100px;margin-left: -20px;" >
            </a>
          </div>
        </div>


    		</div>
    	</div>


      
    
    </div>
  </div>
          </div>
    </div>
</div> --}}
<div class="main-cont">

    <div class="inner-page" >
        <div class="content-wrapper">
             <div class="alert alert-success text-left">
  			<strong>Sucess!</strong> Please make payment to continue
		</div>

        	<div class="card">
   <div class="card-header">
      <h3>  {{ $flightinfo['depart'] }} - {{ $flightinfo['arrival'] }}</h3>
   </div>
   <div class="card-body">
      <div class="row">
         <div class="col-md-4">
            <table class="table table-striped table-hover table-bordered" style="float: right;">
               <tr>
                  <th class="bg-primary text-white text-left" colspan="2"><i class="fa  fa-clock-o"></i> Flight Time</th>
               </tr>
             
               <tr>
                  <th class="text-left">Departure time</th>
                  <td class="text-left">{{$flightinfo['flight_date']}} {{ $flightinfo['departTime'] }}</td>
               </tr>
               <tr>
                  <th class="text-left">Arrival time</th>
                  <td class="text-left">{{$flightinfo['flight_date']}} {{ $flightinfo['arrivalTime'] }}</td>
               </tr>
            </table>
            <table class="table table-striped table-hover table-bordered">
               <tr>
                  <th colspan="2" class="text-left text-white bg-info"><i class="fa  fa-money"></i> Fair Info</th>
               </tr>
               @if($flightinfo['AdultFare'])
               <tr>
                  <th class="text-left">Adult</th>
                  <td class="text-left">{{$flightinfo['currency'] }} {{ $flightinfo['AdultFare'] }}</td>
               </tr>
               @endif
               @if($flightinfo['ChildFare'])
               <tr>
                  <th class="text-left">Child</th>
                  <td class="text-left">{{$flightinfo['currency'] }} {{ $flightinfo['ChildFare'] }}</td>
               </tr>
               @endif
               @if($flightinfo['InfantFare'])
               <tr>
                  <th class="text-left">Infant</th>
                  <td class="text-left">{{$flightinfo['currency'] }} {{ $flightinfo['InfantFare'] }}</td>
               </tr>
               @endif
               <tr class=" bg-danger ">
               		<th class="text-left text-white">Total Fare (With Service) </th>
               		<td class="text-left text-white">{{$flightinfo['currency'] }} {{ $flightinfo['total_amount'] }}</td>
               </tr>
           
            </table>
         </div>
         <div class="col-md-8">
         	
         	<h5 class="card-title text-left">Please Choose Secure Payment</h5>
            <div class="row">
               <div class="col-md-3">
                  <div class="card" style="padding: 30px;">
                     <a href="javascript::void()"   class="submitButton" onclick="makebooking('ipsconnect')" >
                     <img class="card-img-bottom" src="https://login.connectips.com/resources/custom/login/main-logo.png" alt="Card image" 
                     style="width:100%;height: 100px;" >
                     </a>
                  </div>
               </div>
               <div class="col-md-5">
                  <div class="card" style="padding: 30px;">
                     <a href="javascript::void()" class="submitButton" onclick="makebooking('hbl')" >
                     <img class="card-img-bottom" src="/frontend/img/cardpayment.png" alt="Card image" style="width:100%;height: 100px;margin-left: -20px;" >
                     </a>
                  </div>
               </div>
            </div>
            @auth
            <h4>OR</h4>
            <button class="btn  btn-success btn-block" type="button" onclick="makebooking('wallet')" >Pay With Wallet</button>
         	@endauth
         </div>
      </div>
   </div>
</div>


        </div>
    </div>
    <span class="airplane"></span>
   <form method="POST" id='completeBooking' action="completebooking">
   		<input type="hidden" name="uuid" value="{{ $uuid }}">
   		<input type="hidden" name="paid_by" id='paid_by'>
   		@csrf
   </form>

</div>
</div>
</div>

</div>
<script type="text/javascript">
	function makebooking(paid_by){


		$('#completeBooking #paid_by').val(paid_by);

		$('#completeBooking').submit();



	}
</script>
@endsection