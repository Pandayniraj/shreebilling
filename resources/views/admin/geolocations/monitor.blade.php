@extends('layouts.master')
@section('content')

<style type="text/css">

.content{
    min-height: 100vh;
}
#map {         
 position: relative;
 min-height: 100vh;
}

.label {
color: #000;
background-color: white;
border: 1px solid #000;
font-family: "Lucida Grande", "Arial", sans-serif;
font-size: 12px;
text-align: center;

white-space: nowrap;
padding: 2px;
}
.label.online {
background-color: #58D400;
margin-left: -30px;
}
.label.offline {
background-color: #d9534f;
margin-left: -30px;
}
</style>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Monitor User location
                <small>{!! $page_description ?? "Live" !!}</small>
            </h1>
            <p> We need to install mobile app in order to see the live user, Download <i class="fa fa-android"></i> app <a target="_blank" href="https://play.google.com/store/apps/details?id=meronetwork.app.fieldservice&hl=en">here</a> </p>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div id="map"></div>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBH1VwCF9e5KWE-_C9zl-7odmf4sTgWOgw&v=3.exp" type="text/javascript"></script>
   <script src="https://cdn.sobekrepository.org/includes/gmaps-markerwithlabel/1.9.1/gmaps-markerwithlabel-1.9.1.js" type="text/javascript"></script>

<script type="text/javascript">
const markers = [];
 function setMapOnAll(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}
const infowindow = new google.maps.InfoWindow({});
function addmarker(map,locationdata){
   for(let i=0;i<locationdata.length;i++){
    var checkonline = locationdata[i].isonline;
    if(checkonline){
      var labelclass = 'online';
    }
    else{
      var labelclass = 'offline';
    }
  var marker = new MarkerWithLabel({
    position: {lat:Number(locationdata[i].lat),lng:Number(locationdata[i].lng)},
    map: map,
    draggable:true,
    labelClass:'label ' + labelclass,
    labelContent: locationdata[i].name,
    labelInBackground: true
  });
  markers.push(marker);
  google.maps.event.addListener(marker, 'click', (function(marker, i) {
    return function() {
      infowindow.setContent(locationdata[i].info);
      infowindow.open(map, marker);
    }
  })(marker, i));
}
setTimeout(function(){
  $.get('/admin/geolocations/monitor',function(data){
    setMapOnAll(null);
    addmarker(map,data);
  })
},20000)
}
function initMap() {
  var center = {lat: 27.7172, lng: 85.3240};
  var image = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 14,
    center: center
  });
  let locationdata = <?php print_r(json_encode($userloc))?>;
  addmarker(map,locationdata);

}
 initMap();

</script>

@endsection