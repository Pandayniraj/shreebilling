<style type="text/css">
	.modal12 {
  display: block; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  color: black;
  padding: 20px;
  border: 1px solid #888;
  width: 50%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Biometric Device
                <small>{!! $page_description ?? "Add Devices" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

<div id="myModal" class="modal12" >

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>
    <table class="table table-hover table-no-border" id="leads-table">
<thead>
    <tr>
        <th style="text-align:center;width:20px !important">
            S.N
        </th>
        <th>Employee Name</th>
         <th>Time</th>
        <th>Verification Method</th>
    </tr>
</thead>
<tbody>
  @foreach($all_attendence_rec as $key=>$attendence)
  <tr>
        <td class="bg-info">{{$key + 1 }}</td>
        <td class="bg-info">{{$attendence[0]['name']}}</td>

        <td class="bg-info">@foreach($attendence as $time)
          {{$time['datetime']}}
        @endforeach</td>
        <td class="bg-info">{{$attendence[0]['verification_mode']}}</td>
    </tr>
    @endforeach

</tbody>
</table>
  </p>
  </div>

</div>

<script type="text/javascript">
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
// When the user clicks the button, open the modal 

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>