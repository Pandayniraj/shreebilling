<h3>{{ env('APP_NAME')}} Your Tour has Been Booked</h3>
<p>Following are the details.</p>
<p>
    <strong>Reservation No: {{$tour_booking->id}}</strong><br />
    <strong>Contact Name: {{$tour_booking->first_name}} {{$tour_booking->last_name}}</strong><br />
    <strong>E-Mail Adress: {{$tour_booking->email}}</strong><br />
    <strong>Phone Number: {{ $tour_booking->contact_no }}</strong><br />
    <strong>Payment Status: {{ucwords($tour_booking->payment)}}</strong><br />
    <strong>Paid By: {{ ucwords($tour_booking->paid_by) }}</strong>
</p>
