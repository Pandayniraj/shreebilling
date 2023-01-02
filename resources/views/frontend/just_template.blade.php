@extends('frontend.layouts.app')
@section('content')


<div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
  <a href="#">About</a>
  <a href="#">Services</a>
  <a href="#">Clients</a>
  <a href="#">Contact</a>
</div>
<style>
body {
  font-family: "Lato", sans-serif;
}

.sidebar {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1000000;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidebar a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidebar a:hover {
  color: #f1f1f1;
}

.sidebar .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

.openbtn {
  font-size: 20px;
  cursor: pointer;
  background-color: #111;
  color: white;
  padding: 10px 15px;
  border: none;
}

.openbtn:hover {
  background-color: #444;
}

#main {
  transition: margin-left .5s;
  padding: 16px;
}

/* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
@media screen and (max-height: 450px) {
  .sidebar {padding-top: 15px;}
  .sidebar a {font-size: 18px;}
}
</style>
<link rel="stylesheet" type="text/css" href="https://www.cssscript.com/demo/bootstrap-list-group-radio-checkbox/bootstrap-checkbox-radio-list-group-item.min.css">
<!-- main-cont -->
<div class="main-cont" style="padding:20px;text-align:center">
    <div class="">
        <div class="mp-slider search-only">

        </div>
        <div class="container">
        <div class="row">
        	
        	<div class="col-md-4">
        		 <div class="row">
    <div id="main">
  <button class="openbtn  btn btn-success btn-block" onclick="openNav()">☰ Filter</button>  
 
</div>
  </div>
        		<div class="row">
        			<div class="col-sm-12"><div class="card">
    <div class="card">
    <div class="card-header">Header</div>
    <div class="card-body" style="padding: 0;"> <div class="list-group checkbox-list-group text-left">
<div class="list-group-item list-group-item-success"><label>&nbsp;<input type="checkbox"><span class="list-group-item-text"><i class="fa fa-fw"></i> Success</span></label></div>
<div class="list-group-item list-group-item-success"><label>&nbsp;<input type="checkbox"><span class="list-group-item-text"><i class="fa fa-fw"></i> Success</span></label></div>

</div></div> 

  </div>
  </div></div>
        		</div>

        	</div>

        	<div class="col-md-8">
        		
				<div class="card">
				    <div class="card-body">
				      <h4 class="card-title">Card title</h4>
				    
				    </div>
				  </div>

				  <br>
			<div class="row">
				
				<div class="col-sm-12">
					
					<div class="card">
						
						<div class="card-body">
							<div class="catalog-row">
								<div class="flight-item fly-in appeared">
                <div class="flt-i-a">
                  <div class="flt-i-b">
                    <div class="flt-i-bb">
                    <div class="flt-l-a">
                      <div class="flt-l-b">
                        <div class="way-lbl">departure</div>
                        <div class="company"><img alt="" src="img/flyght-01.png"></div>
                      </div>
                      <div class="flt-l-c">
                        <div class="flt-l-cb">
                          <div class="flt-l-c-padding">
                            <div class="flyght-info-head">TUE 06/01 New York - Viena</div>
                            <!-- // -->
                            <div class="flight-line">
                              <div class="flight-radio"><label><input name="radio" type="radio" style="position: absolute; left: -9999px;"><span class="jq-radio" style="display: inline-block"><span></span></span></label> </div>
                              <div class="flight-line-a">
                                <b>departure</b>
                                <span>14:12</span>
                              </div>
                              <div class="flight-line-d"></div>
                              <div class="flight-line-a">
                                <b>arrival</b>
                                <span>17:50</span>
                              </div>
                              <div class="flight-line-d"></div>
                              <div class="flight-line-a">
                                <b>time</b>
                                <span>14H 50M</span>
                              </div>
                              <div class="flight-line-b">
                                <b class="">details</b>
                                <span>Only 2 seats!</span>
                              </div>
                              <div class="clear"></div>
                                <!-- // details // -->
                                <div class="flight-details" style="display: none;">
                                  <div class="flight-details-l">
                                    <div class="flight-details-a">Tue 06/01</div>
                                    <div class="flight-details-b">12:40 John F Kennedy (JFK)</div>
                                    <div class="flight-details-c">USA</div> 
                                  </div>
                                  <div class="flight-details-r">
                                    <div class="flight-details-a">Tue 06/01</div>
                                    <div class="flight-details-b">16:30 Vienna Intl (VIE)</div>
                                    <div class="flight-details-c">Austria</div>                                 
                                  </div>
                                  <div class="clear"></div>
                                  <div class="flight-details-d">
                                    Flight LO-98 Boeing 787-8 (jet) Economy<br>
                                    Operated by austrian airlines
                                  </div>
                                </div>
                                <!-- \\ details \\ -->                              
                            </div>
                            <!-- \\ -->
                            
                            <!-- // -->
                            <div class="flight-line">
                              <div class="flight-radio"><label><input name="radio" type="radio" style="position: absolute; left: -9999px;"><span class="jq-radio" style="display: inline-block"><span></span></span></label></div>
                              <div class="flight-line-a">
                                <b>departure</b>
                                <span>15:30</span>
                              </div>
                              <div class="flight-line-d"></div>
                              <div class="flight-line-a">
                                <b>arrival</b>
                                <span>19:40</span>
                              </div>
                              <div class="flight-line-d"></div>
                              <div class="flight-line-a">
                                <b>time</b>
                                <span>14H 50M</span>
                              </div>
                              <div class="flight-line-b">
                                <b>details</b>
                                <span>Only 2 seats!</span>
                              </div>
                              <div class="clear"></div>
                                <!-- // details // -->
                                <div class="flight-details">
                                  <div class="flight-details-l">
                                    <div class="flight-details-a">Tue 06/01</div>
                                    <div class="flight-details-b">12:40 John F Kennedy (JFK)</div>
                                    <div class="flight-details-c">USA</div> 
                                  </div>
                                  <div class="flight-details-r">
                                    <div class="flight-details-a">Tue 06/01</div>
                                    <div class="flight-details-b">16:30 Vienna Intl (VIE)</div>
                                    <div class="flight-details-c">Austria</div>                                 
                                  </div>
                                  <div class="clear"></div>
                                  <div class="flight-details-d">
                                    Flight LO-98 Boeing 787-8 (jet) Economy<br>
                                    Operated by austrian airlines
                                  </div>
                                </div>
                                <!-- \\ details \\ -->
                            </div>
                            <!-- \\ -->
                            
                            <!-- \\ -->
                            <div class="flight-line">
                              <div class="flight-radio"><label><input name="radio" type="radio" style="position: absolute; left: -9999px;"><span class="jq-radio" style="display: inline-block"><span></span></span></label></div>
                              <div class="flight-line-a">
                                <b>departure</b>
                                <span>20:10</span>
                              </div>
                              <div class="flight-line-d"></div>
                              <div class="flight-line-a">
                                <b>arrival</b>
                                <span>02:12</span>
                              </div>
                              <div class="flight-line-d"></div>
                              <div class="flight-line-a">
                                <b>time</b>
                                <span>14H 50M</span>
                              </div>
                              <div class="flight-line-b">
                                <b>details</b>
                                <span>Only 2 seats!</span>
                              </div>
                              <div class="clear"></div>
                                <!-- // details // -->
                                <div class="flight-details">
                                  <div class="flight-details-l">
                                    <div class="flight-details-a">Tue 06/01</div>
                                    <div class="flight-details-b">12:40 John F Kennedy (JFK)</div>
                                    <div class="flight-details-c">USA</div> 
                                  </div>
                                  <div class="flight-details-r">
                                    <div class="flight-details-a">Tue 06/01</div>
                                    <div class="flight-details-b">16:30 Vienna Intl (VIE)</div>
                                    <div class="flight-details-c">Austria</div>                                 
                                  </div>
                                  <div class="clear"></div>
                                  <div class="flight-details-d">
                                    Flight LO-98 Boeing 787-8 (jet) Economy<br>
                                    Operated by austrian airlines
                                  </div>
                                </div>
                                <!-- \\ details \\ -->                              
                            </div>
                            <!-- \\ -->
                          </div>
                        </div>
                        <br class="clear">
                      </div>
                    </div>
                    <div class="clear"></div>
                    </div>
                    <br class="clear">
                  </div>
                </div>
                <div class="flt-i-c">
                  <div class="flt-i-padding">
                    <div class="flt-i-price-i">
                      <div class="flt-i-price">634.24$</div>
                      <div class="flt-i-price-b">avg/person</div>
                    </div>
                    <a href="#" class="cat-list-btn">select now</a>
                  </div>
                </div>
                <div class="clear"></div>
              </div>

							</div>


						</div>
					</div>


				</div>





			</div>

        	</div>
        </div>
        

    </div>
</div>
</div>
<script>
function openNav() {
	let el = document.getElementById("mySidebar");
	if(el.style.width  == '0px' || el.style.width =='' ){

		el.style.width = "350px";
	}else{
		closeNav();
	}
  
}

function closeNav() {
  document.getElementById("mySidebar").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
}
</script>
@endsection
