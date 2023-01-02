<h3>{{ env('APP_NAME')}} Your Ticked has Been Issued</h3>
<p>Following are the details.</p>


@foreach($ticket_booking_data as $key=>$ticket_booking)
<p>
    <strong>Ticket No: {{$ticket_booking->ticket_no}}</strong><br />
    <strong>PNR No: {{$ticket_booking->pnr}}</strong><br />
    <strong>Passenger: {{$ticket_booking->contact_name}}</strong><br />
    <strong>Payment Status: {{ucwords($ticket_booking->reservation->payment)}}</strong><br />
    <strong>Paid By: {{ ucwords($ticket_booking->reservation->paid_by) }}</strong>
</p>

@endforeach
<strong>E-Mail Adress: {{$ticket_booking->email}}</strong><br />
<strong>Phone Number: {{ $ticket_booking->reservation->contact_no }}</strong><br />
